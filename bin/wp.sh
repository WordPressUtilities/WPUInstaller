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
        wp_version=$(stackov_parse_json $(cat ${MAINDIR}version.json) version);
        git submodule add --quiet --depth 1 https://github.com/wordpress/wordpress wp-cms;
        echo "... loading latest WordPress version";
        cd "${MAINDIR}wp-cms";
        git fetch --tags --quiet;
        git checkout --quiet ${wp_version};
        cd "${MAINDIR}";
        rm "${MAINDIR}version.json";
        echo "### Using WordPress v ${wp_version}";
        mkdir "${MAINDIR}wp-content/";
        mkdir "${MAINDIR}wp-content/themes/";
        mkdir "${MAINDIR}wp-content/plugins/";
        mkdir "${MAINDIR}wp-content/languages/";
    else
        php ${WPU_PHPCLI} core download --locale=${WP_LOCALE} --skip-themes --skip-plugins;
    fi;

fi;

# WP Config
if [[ ! -f "${MAINDIR}wp-config.php" ]]; then
    echo '### Create WP Config';

    echo $(mysql -h${mysql_host} -u${mysql_user} -p${mysql_password} -e "create database IF NOT EXISTS ${mysql_database};") > /dev/null;
    php ${WPU_PHPCLI}  core config --dbhost=${mysql_host} --dbname=${mysql_database} --dbuser=${mysql_user} --dbpass=${mysql_password} --extra-php <<PHP
define( 'WP_DEBUG', true );
if ( WP_DEBUG ) {
    @ini_set( 'display_errors', 0 );
    if ( !defined( 'WP_DEBUG_LOG' ) ) define( 'WP_DEBUG_LOG', 1 );
    if ( !defined( 'WP_DEBUG_DISPLAY' ) ) define( 'WP_DEBUG_DISPLAY', false );
    if ( !defined( 'SCRIPT_DEBUG' ) ) define( 'SCRIPT_DEBUG', 1 );
    if ( !defined( 'SAVEQUERIES' ) ) define( 'SAVEQUERIES', 1 );
}
##WPUINSTALLER##
PHP

    if [[ $use_subfolder == 'y' ]]; then
        sed -i '' "s/##WPUINSTALLER##/define('WP_CONTENT_DIR', dirname(__FILE__)\.'\/\.\.\/wp-content');/" "${MAINDIR}wp-cms/wp-config.php";
    else
        sed -i '' "s/##WPUINSTALLER##//" "${MAINDIR}wp-config.php";
    fi;

fi;

# If table are not present
if ! $(php ${WPU_PHPCLI} core is-installed); then
    echo '### Install WP';
    php ${WPU_PHPCLI} core install --url=${project_dev_url} --title="${project_name}" --admin_user=admin --admin_password=admin --admin_email=${email_address}
fi


if [[ $use_submodules == 'y' ]]; then
    php ${WPU_PHPCLI} core language install ${WP_LOCALE};
    php ${WPU_PHPCLI} core language activate ${WP_LOCALE};
fi;

# Deleting default items
echo '### Deleting default items';
if [[ $use_subfolder == 'n' ]]; then
    php ${WPU_PHPCLI} plugin delete akismet;
    php ${WPU_PHPCLI} plugin delete hello;
    rm -rf "${MAINDIR}${WP_THEME_DIR}twentythirteen/";
    rm -rf "${MAINDIR}${WP_THEME_DIR}twentyfourteen/";
    rm -rf "${MAINDIR}${WP_THEME_DIR}twentyfifteen/";
    rm -rf "${MAINDIR}${WP_THEME_DIR}twentysixteen/";
    rm -rf "${MAINDIR}${WP_LANG_DIR}plugins/";
    rm -rf "${MAINDIR}${WP_LANG_DIR}themes/";
    rm -rf "${MAINDIR}readme.html";
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

