#!/bin/bash

###################################
## Vars
###################################

WP_LOCALE='fr_FR';
WP_THEME_DIR='wp-content/themes/';
WP_LANG_DIR='wp-content/languages/';
WP_MUPLUGINS_DIR='wp-content/mu-plugins/';
WP_PLUGINS_DIR='wp-content/plugins/';
WPU_SUBMODULE_PLUGINS="wpuoptions wpupostmetas wpuseo wpuposttypestaxos wputhumbnails";
WPU_MUPLUGINS="wpu_body_classes wpu_ux_tweaks";
WPU_FORCED_MUPLUGINS="wpudisablecomments wpudisablesearch wpudisableposts wputh_admin_protect";
MAINDIR="${PWD}/";
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";
alias wp='php wp-cli.phar';

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

default_project_id="$(echo -e "${project_name}" | tr -d '[[:space:]]' | tr [:upper:] [:lower:])";
default_project_id="$(echo ${default_project_id} | iconv -f utf8 -t ascii//TRANSLIT)";
default_project_id="$(echo ${default_project_id} | tr -cd '[[:alnum:]]._-')";
read -p "What's the project id ? [${default_project_id}] : " project_id;
if [[ $project_id == '' ]]; then
    project_id="${default_project_id}";
fi;
echo "- Project ID: ${project_id}";

read -p "What's the project dev url ? [http://localhost/${project_id}/] : " project_dev_url;
if [[ $project_dev_url == '' ]]; then
    project_dev_url="http://localhost/${project_id}/";
fi;
echo "- Project URL: ${project_dev_url}";

read -p "Is it a multilingual project ? (Y/n) " project_l10n;
if [[ $project_l10n == '' ]]; then
    project_l10n="y";
else
    read -p "What's the locale ? [${WP_LOCALE}] : " user_locale;
    if [[ $user_locale == '' ]]; then
        user_locale="${WP_LOCALE}";
    fi;
    WP_LOCALE="${user_locale}";
    echo "- Locale: ${WP_LOCALE}";
fi;

read -p "What's your email address ? [test@yopmail.com] : " email_address;
if [[ $email_address == '' ]]; then
    email_address="test@yopmail.com";
fi;
echo "- Email: ${email_address}";

read -p "What's the MYSQL HOST ? [127.0.0.1] : " mysql_host;
if [[ $mysql_host == '' ]]; then
    mysql_host="127.0.0.1";
fi;
echo "- MySQL Host: ${mysql_host}";

read -p "What's the MYSQL USER ? [root] : " mysql_user;
if [[ $mysql_user == '' ]]; then
    mysql_user="root";
fi;
echo "- MySQL User: ${mysql_user}";

read -p "What's the MYSQL PASSWORD ? [root] : " mysql_password;
if [[ $mysql_password == '' ]]; then
    mysql_password="root";
fi;
echo "- MySQL Pass: ${mysql_password}";

read -p "What's the MYSQL DATABASE ? [${project_id}] : " mysql_database;
if [[ $mysql_database == '' ]]; then
    mysql_database="${project_id}";
fi;

###################################
## Shorthand vars
###################################

WPU_THEME="${MAINDIR}${WP_THEME_DIR}${project_id}/";

###################################
## Test git
###################################

if [[ ! -d '.git' ]]; then
    git init;
fi;

###################################
## Set gitignore
###################################

echo '### Set gitignore';

echo "node_modules
.sass-cache
wp-cli.phar
/WPUtilities/
/.htaccess
/wp-content/uploads/
/wp-content/debug.log
/wp-config.php" >> "${MAINDIR}.gitignore";

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
    mysql -h${mysql_host} -u${mysql_user} -p${mysql_password} -e "create database IF NOT EXISTS ${mysql_database};";
    php wp-cli.phar core config --dbhost=${mysql_host} --dbname=${mysql_database} --dbuser=${mysql_user} --dbpass=${mysql_password} --extra-php <<PHP
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
    php wp-cli.phar core install --url=${project_dev_url} --title="${project_name}" --admin_user=admin --admin_password=admin --admin_email=${email_address}
fi

# Deleting default items
wp plugin delete akismet;
wp plugin delete hello;
rm -rf "${MAINDIR}${WP_THEME_DIR}twentythirteen/";
rm -rf "${MAINDIR}${WP_THEME_DIR}twentyfourteen/";
rm -rf "${MAINDIR}${WP_THEME_DIR}twentyfifteen/";
rm -rf "${MAINDIR}${WP_LANG_DIR}plugins/";
rm -rf "${MAINDIR}${WP_LANG_DIR}themes/";
rm -rf "${MAINDIR}readme.html";

# Commit WordPress Installation
git add .
git commit -m "Installation - WordPress";

# Delete default content
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='page' --format=ids)
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='post' --format=ids)
php wp-cli.phar comment delete $(php wp-cli.phar comment list --format=ids)


###################################
## WPUtilities installation
###################################

echo '### WPUtilities installation';
git clone --depth=1 https://github.com/Darklg/WPUtilities.git;

###################################
## Theme installation
###################################

cd "${MAINDIR}${WP_THEME_DIR}";

echo '### Parent Theme installation';

git submodule add "https://github.com/WordPressUtilities/WPUTheme.git";

# Commit Theme Installation
git add .
git commit -m "Installation - Framework Theme";

echo '### Child theme initialisation';

# Generate child theme
cd "${MAINDIR}${WP_THEME_DIR}";
mkdir "${project_id}";

# - Style CSS
touch "${WPU_THEME}style.css";
echo "/*
Theme Name: ${project_name} theme
Description: A WordPress theme for ${project_name}
Template: WPUTheme
Author: Darklg
Author URI: http://darklg.me/
*/" > "${WPU_THEME}style.css";

# - Index
cp "${SCRIPTDIR}inc/home.php" "${WPU_THEME}home.php";

# - Functions
cp "${SCRIPTDIR}inc/functions.php" "${WPU_THEME}functions.php";

# - Templates
mkdir "${WPU_THEME}tpl/";
touch "${WPU_THEME}tpl/.htaccess";
echo 'deny from all' > "${WPU_THEME}tpl/.htaccess";
cp "${SCRIPTDIR}inc/tpl/header.php" "${WPU_THEME}tpl/header.php";
cp "${SCRIPTDIR}inc/tpl/footer.php" "${WPU_THEME}tpl/footer.php";

# - Translation
mkdir "${WPU_THEME}inc/";
mkdir "${WPU_THEME}inc/lang/";
cp "${SCRIPTDIR}inc/lang/fr_FR.po" "${WPU_THEME}inc/lang/fr_FR.po";

# - Assets
mkdir "${WPU_THEME}assets/";
mkdir "${WPU_THEME}assets/images";
wget "http://placehold.it/200x100/fff/000?text=${project_id}" -q -O "${WPU_THEME}assets/images/logo.png";
mkdir "${WPU_THEME}assets/js";
touch "${WPU_THEME}assets/js/events.js";

# Activate child theme
cd "${MAINDIR}";
php wp-cli.phar theme activate "${project_id}";

# Commit Theme Installation
git add .
git commit -m "Installation - Child Theme";

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

# Commit Add mu-plugins
git add .
git commit -m "Installation - MU-Plugins";

###################################
## Plugins installation
###################################

echo '### Plugins installation';

cd "${MAINDIR}${WP_PLUGINS_DIR}";

for i in $WPU_SUBMODULE_PLUGINS
do
    echo "## Install ${i}";
    git submodule add "https://github.com/WordPressUtilities/${i}.git";
done;

cd "${MAINDIR}";

for i in $WPU_SUBMODULE_PLUGINS
do
    php wp-cli.phar plugin activate "${i}";
done;

if [[ $project_l10n == 'y' ]]; then
    echo "## Install Qtranslate X";
    php wp-cli.phar plugin install qtranslate-x --activate
    php wp-cli.phar option update qtranslate_default_language 'fr';
    php wp-cli.phar option update qtranslate_enabled_languages '["fr","en"]' --format=json;
fi;

# Commit Add plugins
git add .
git commit -m "Installation - Plugins";

###################################
## Set .htaccess
###################################

echo '### Set htaccess - performance';

cp "${MAINDIR}WPUtilities/wp-content/.htaccess" "${MAINDIR}wp-content/.htaccess";

echo '### Set htaccess - permalinks';

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

echo '### Update permalinks';

php wp-cli.phar rewrite flush --hard

###################################
## Clean up
###################################

echo '### Clean up';

# Unused plugins
rm -f "${WP_PLUGINS_DIR}hello.php";

# Unused themes
rm -rf "WPUtilities/";
rm -rf "wp-cli.phar";

echo '### Success';

cd "${WPU_THEME}";
open "${project_dev_url}";