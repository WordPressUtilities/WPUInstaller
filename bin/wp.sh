#!/bin/bash

###################################
## WordPress installation
###################################

# If there is no wp-content dir
if [[ ! -d 'wp-content' ]]; then
    php "${MAINDIR}wp-cli.phar" core download --locale=${WP_LOCALE}
fi;

# WP Config
if [[ ! -f 'wp-config.php' ]]; then
    echo $($wpu_mysql_args -e "create database IF NOT EXISTS ${mysql_database};") > /dev/null;
    php "${MAINDIR}wp-cli.phar" core config --dbhost=${mysql_host} --dbname=${mysql_database} --dbuser=${mysql_user} --dbpass=${mysql_password} --extra-php <<PHP
define( 'WP_DEBUG', true );
if ( WP_DEBUG ) {
    @ini_set( 'display_errors', 0 );
    if ( !defined( 'WP_DEBUG_LOG' ) ) define( 'WP_DEBUG_LOG', 1 );
    if ( !defined( 'WP_DEBUG_DISPLAY' ) ) define( 'WP_DEBUG_DISPLAY', false );
    if ( !defined( 'SCRIPT_DEBUG' ) ) define( 'SCRIPT_DEBUG', 1 );
    if ( !defined( 'SAVEQUERIES' ) ) define( 'SAVEQUERIES', 1 );
}
PHP
fi;

# If table are not present
if ! $(php wp-cli.phar core is-installed); then
    php "${MAINDIR}wp-cli.phar" core install --url=${project_dev_url} --title="${project_name}" --admin_user=admin --admin_password=admin --admin_email=${email_address}
fi

# Deleting default items
wp plugin delete akismet;
wp plugin delete hello;
rm -rf "${MAINDIR}${WP_THEME_DIR}twentythirteen/";
rm -rf "${MAINDIR}${WP_THEME_DIR}twentyfourteen/";
rm -rf "${MAINDIR}${WP_THEME_DIR}twentyfifteen/";
rm -rf "${MAINDIR}${WP_THEME_DIR}twentysixteen/";
rm -rf "${MAINDIR}${WP_LANG_DIR}plugins/";
rm -rf "${MAINDIR}${WP_LANG_DIR}themes/";
rm -rf "${MAINDIR}readme.html";


# Commit WordPress Installation
git add -A
git commit -m "Installation - WordPress" --quiet;

