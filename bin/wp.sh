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

# Update
define('WP_AUTO_UPDATE_CORE', false);
define('AUTOMATIC_UPDATER_DISABLED', true);

# Files
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );

# CRONs
define('DISABLE_WP_CRON', true);

# Environment
define('WPU_ENVIRONMENT', 'local');
define('WP_ENVIRONMENT_TYPE', 'local');

# Config
define('EMPTY_TRASH_DAYS', 7);
define('WP_POST_REVISIONS', 6);

# Memory
define('WP_MEMORY_LIMIT', '128M');
define('WP_MAX_MEMORY_LIMIT', '256M');

# Assets
define('WPUTHEME_ASSETS_VERSION', time());

# Debug
if(!defined('WP_DEBUG')){
    define('WP_DEBUG', true);
}
if (WP_DEBUG) {
    @ini_set('display_errors', 0);
    if (!defined('WP_DEBUG_LOG')) { define('WP_DEBUG_LOG', dirname(__FILE__) . '/../logs/debug-' . date('Ymd') . '.log'); }
    if (!defined('WP_DEBUG_DISPLAY')) { define('WP_DEBUG_DISPLAY', false); }
    if (!defined('SAVEQUERIES')) { define('SAVEQUERIES', (php_sapi_name() !== 'cli')); }
}

##WPUINSTALLER##
PHP

    if [[ ${use_subfolder} == 'y' ]]; then
        bashutilities_sed "s/##WPUINSTALLER##/define('WP_CONTENT_DIR', dirname(__FILE__)\.'\/\.\.\/wp-content');/g" "${MAINDIR}wp-cms/wp-config.php";
    else
        bashutilities_sed "s/##WPUINSTALLER##//g" "${MAINDIR}wp-config.php";
    fi;

fi;

# If table are not present
if ! $(php ${WPU_PHPCLI} core is-installed); then
    echo '### Install WP';
    php ${WPU_PHPCLI} core install --url="${project_dev_url}" --title="${project_name}" --admin_user=admin --admin_password=admin --admin_email="${email_address}"
fi

php ${WPU_PHPCLI} core language install ${WP_LOCALE};
php ${WPU_PHPCLI} site switch-language ${WP_LOCALE};

# Add README
_readme_file="${MAINDIR}project-readme.md";
if [[ -f "${_readme_file}" ]];then
    rm "${_readme_file}";
fi;
cp "${SCRIPTDIR}inc/base_readme.md" "${_readme_file}";
wpuinstaller_replace "${_readme_file}";

# Deleting default items
echo '### Deleting default items';
if [[ $use_subfolder == 'n' ]]; then
    rm -rf "${MAINDIR}readme.html";
    rm -rf "${MAINDIR}license.txt";
fi;

if [[ "${need_comments}" == 'n' ]];then
    rm -rf "${MAINDIR}wp-comments-post.php";
    rm -rf "${MAINDIR}wp-trackback.php";
fi;

# Install subfolder
if [[ $use_subfolder == 'y' ]]; then
    cp "${SCRIPTDIR}inc/htaccess-wpsubfolder.txt" "${MAINDIR}.htaccess";
    cp "${SCRIPTDIR}inc/index-subfolder.php" "${MAINDIR}index.php";
    git add -f "${MAINDIR}.htaccess";
fi;

# Commit WordPress Installation
bashutilities_commit_all "Installation - WordPress";

