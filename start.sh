#!/bin/bash

###################################
## Vars
###################################

WP_LOCALE='fr_FR';
WP_THEME_DIR='wp-content/themes/';
WP_MUPLUGINS_DIR='wp-content/mu-plugins/';
WP_PLUGINS_DIR='wp-content/plugins/';
WPU_SUBMODULE_PLUGINS="wpuoptions wpupostmetas wpuseo wpuposttypestaxos";
WPU_MUPLUGINS="wpu_body_classes wpu_ux_tweaks";
WPU_FORCED_MUPLUGINS="wpudisablecomments wpudisablesearch wpudisableposts wputh_admin_protect";
MAINDIR="${PWD}/";
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";

###################################
## Conf
###################################

export PATH=$PATH:/Applications/MAMP/Library/bin/

###################################
## Questions
###################################

read -p "What's the project name ? " project_name;
if [[ $project_name == '' ]]; then
    project_name="WordPress Theme";
fi;
echo "- Project name: ${project_name}";

read -p "What's the project dev url ? " project_dev_url;
if [[ $project_dev_url == '' ]]; then
    project_dev_url="http://localhost/wptheme/";
fi;
echo "- Project URL: ${project_dev_url}";


read -p "What's the project id ? " project_id;
if [[ $project_id == '' ]]; then
    project_id="wptheme";
fi;
echo "- Project ID: ${project_id}";

read -p "What's your email address ? " email_address;
if [[ $email_address == '' ]]; then
    email_address="test@yopmail.com";
fi;
echo "- Email: ${email_address}";

read -p "What's the MYSQL HOST ? " mysql_host;
if [[ $mysql_host == '' ]]; then
    mysql_host="localhost";
fi;
echo "- MySQL Host: ${mysql_host}";

read -p "What's the MYSQL USER ? " mysql_user;
if [[ $mysql_user == '' ]]; then
    mysql_user="root";
fi;
echo "- MySQL User: ${mysql_user}";

read -p "What's the MYSQL PASSWORD ? " mysql_password;
if [[ $mysql_password == '' ]]; then
    mysql_password="root";
fi;
echo "- MySQL Pass: ${mysql_password}";

read -p "What's the MYSQL DATABASE ? " mysql_database;
if [[ $mysql_database == '' ]]; then
    echo "— The MYSQL DATABASE is required"; exit 0;
fi;

###################################
## Test git
###################################

if [[ ! -d '.git' ]]; then
    git init;
fi;

###################################
## Install WP-CLI
###################################

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar;
chmod +x wp-cli.phar;

###################################
## WordPress installation
###################################

# If there is no wp-content dir
if [[ ! -d 'wp-content' ]]; then
    php wp-cli.phar core download --locale=${WP_LOCALE}
fi;

# WP Config
if [[ ! -f 'wp-config.php' ]]; then
    php wp-cli.phar core config --dbhost=${mysql_host} --dbname=${mysql_database} --dbuser=${mysql_user} --dbpass=${mysql_password} --extra-php <<PHP
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
PHP
fi;

# If table are not present
if ! $(php wp-cli.phar core is-installed); then
    php wp-cli.phar core install --url=${project_dev_url} --title="${project_name}" --admin_user=admin --admin_password=admin --admin_email=${email_address}
fi

###################################
## WPUtilities installation
###################################

echo '### WPUtilities installation';
git clone git@github.com:Darklg/WPUtilities.git;

###################################
## Theme installation
###################################

echo '### Parent Theme installation';

cd "${MAINDIR}${WP_THEME_DIR}";
git submodule add "git@github.com:WordPressUtilities/WPUTheme.git";

echo '### Child theme initialisation';

# Generate child theme
cd "${MAINDIR}${WP_THEME_DIR}";
mkdir "${project_id}";
touch "${MAINDIR}${WP_THEME_DIR}${project_id}/style.css";

echo "/*
Theme Name: ${project_name} theme
Description: A WordPress theme for ${project_name}
Template: WPUTheme
Author: Darklg
Author URI: http://darklg.me/
*/" > "${MAINDIR}${WP_THEME_DIR}${project_id}/style.css";

cp "${SCRIPTDIR}inc/functions.php" "${MAINDIR}${WP_THEME_DIR}${project_id}/functions.php";

# Activate child theme
cd "${MAINDIR}";
php wp-cli.phar theme activate "${project_id}";

###################################
## MU-Plugins installation
###################################

echo '### MU-Plugins installation';

mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}";

# Classic MU Plugins
for i in $WPU_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${i}.php";
done;

# Forced MU Plugins
for i in $WPU_FORCED_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_PLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${i}.php";
done;

###################################
## Plugins installation
###################################

echo '### Plugins installation';

cd "${MAINDIR}${WP_PLUGINS_DIR}";

for i in $WPU_SUBMODULE_PLUGINS
do
    echo "## Install ${i}";
    git submodule add "git@github.com:WordPressUtilities/${i}.git";
done;

cd "${MAINDIR}";

for i in $WPU_SUBMODULE_PLUGINS
do
    php wp-cli.phar plugin activate "${i}";
done;

###################################
## Set .htaccess
###################################

echo '### Set htaccess';

echo "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress" >> "${MAINDIR}.htaccess";

###################################
## Set gitignore
###################################

echo '### Set gitignore';

echo "wp-content/uploads/
wp-content/debug.log
wp-config.php" >> "${MAINDIR}.gitignore";

###################################
## Clean up
###################################

echo '### Clean up';

# Unused plugins
rm -f "${WP_PLUGINS_DIR}hello.php";
rm -rf "${WP_PLUGINS_DIR}akismet/";

# Unused themes
rm -rf "${WP_THEME_DIR}twentyfifteen/";
rm -rf "${WP_THEME_DIR}twentyfourteen/";
rm -rf "${WP_THEME_DIR}twentythirteen/";
rm -rf "${WP_THEME_DIR}twentytwelve/";
rm -rf "WPUtilities/";
rm -rf "wp-cli.phar";
rm -rf "readme.html";