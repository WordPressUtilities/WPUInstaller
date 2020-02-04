#!/bin/bash

###################################
## WordPress installation
###################################

# If there is no wp-content dir
if [[ ! -d 'wp-content' ]]; then
    echo '### Download WP Core';

    if [[ $use_subfolder == 'y' ]]; then
        # Retrieve last WordPress version
        wget -O "${MAINDIR}version.json" http://api.wordpress.org/core/version-check/1.7/;
        wp_version=$(bashutilities_parse_json $(cat ${MAINDIR}version.json) version);
        git submodule add --quiet --depth 1 https://github.com/wordpress/wordpress wp-cms;
        echo "... loading latest WordPress version";
        cd "${MAINDIR}wp-cms";
        git fetch --tags --quiet;
        git checkout --quiet ${wp_version};
        cd "${MAINDIR}";
        rm "${MAINDIR}version.json";
        echo "### Using WordPress v ${wp_version}";
    else
        php ${WPU_PHPCLI} core download --locale=${WP_LOCALE} --skip-themes --skip-plugins --skip-content;
    fi;
fi;

mkdir "${MAINDIR}wp-content/";
mkdir "${MAINDIR}${WP_THEME_DIR}";
mkdir "${MAINDIR}${WP_LANG_DIR}";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}";
mkdir "${MAINDIR}${WP_PLUGINS_DIR}";

# WP Config
if [[ ! -f "${MAINDIR}wp-config.php" ]]; then
    echo '### Create WP Config';

    echo $(mysql --defaults-extra-file="${MAINDIR}my.cnf" -e "create database IF NOT EXISTS ${mysql_database};") > /dev/null;
    php ${WPU_PHPCLI} core config --dbhost=${mysql_host} --dbname=${mysql_database} --dbuser=${mysql_user} --dbpass=${mysql_password} --dbprefix=${mysql_prefix} --extra-php <<PHP

# URLs
if(!isset(\$_SERVER['HTTP_HOST']) || !\$_SERVER['HTTP_HOST']){
    \$_SERVER['HTTP_HOST'] = '${project_dev_url_raw}';
}
if(!isset(\$_SERVER['SERVER_PROTOCOL']) || !\$_SERVER['SERVER_PROTOCOL']){
    \$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.0';
}
define('WP_SITEURL', 'http://' . \$_SERVER['HTTP_HOST'] . '/');
define('WP_HOME', 'http://' . \$_SERVER['HTTP_HOST'] . '/');

# CRONs
define('DISABLE_WP_CRON', true);

# Environment
define('WPU_ENVIRONMENT', 'local');

# Config
define('EMPTY_TRASH_DAYS', 7);
define('WP_POST_REVISIONS', 6);

# Memory
define('WP_MEMORY_LIMIT', '128M');
define('WP_MAX_MEMORY_LIMIT', '256M');

# Debug
define('WP_DEBUG', true);
if (WP_DEBUG) {
    @ini_set('display_errors', 0);
    if (!defined('WP_DEBUG_LOG')) { define('WP_DEBUG_LOG', 1); }
    if (!defined('WP_DEBUG_DISPLAY')) { define('WP_DEBUG_DISPLAY', false); }
    if (!defined('SCRIPT_DEBUG')) { define('SCRIPT_DEBUG', 1); }
    if (!defined('SAVEQUERIES')) { define('SAVEQUERIES', 1); }
}

##WPUINSTALLER##
PHP

    if [[ ${use_subfolder} == 'y' ]]; then
        bashutilities_sed "s/##WPUINSTALLER##/define('WP_CONTENT_DIR', dirname(__FILE__)\.'\/\.\.\/wp-content');/g" "${MAINDIR}wp-cms/wp-config.php";
    else
        bashutilities_sed "s/##WPUINSTALLER##//g" "${MAINDIR}wp-config.php";
    fi;

fi;

## Default robots.txt
CONTENT_ROBOTS_TXT=$(cat <<TXT
User-agent: *
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/
Allow: /wp-content/uploads/
Allow: /wp-admin/admin-ajax.php
TXT
);
echo "${CONTENT_ROBOTS_TXT}" > "${MAINDIR}robots.txt";

# If table are not present
if ! $(php ${WPU_PHPCLI} core is-installed); then
    echo '### Install WP';
    php ${WPU_PHPCLI} core install --url="${project_dev_url}" --title="${project_name}" --admin_user=admin --admin_password=admin --admin_email="${email_address}"
fi


php ${WPU_PHPCLI} core language install ${WP_LOCALE};
php ${WPU_PHPCLI} core language activate ${WP_LOCALE};

# Deleting default items
echo '### Deleting default items';
if [[ $use_subfolder == 'n' ]]; then
    rm -rf "${MAINDIR}readme.html";
    rm -rf "${MAINDIR}license.txt";
fi;

# Install subfolder
if [[ $use_subfolder == 'y' ]]; then
    cp "${SCRIPTDIR}inc/htaccess-wpsubfolder.txt" "${MAINDIR}.htaccess";
    cp "${SCRIPTDIR}inc/index-subfolder.php" "${MAINDIR}index.php";
    git add -f "${MAINDIR}.htaccess";
fi;

# Commit WordPress Installation
git add -A
git commit --no-verify -m "Installation - WordPress" --quiet;

