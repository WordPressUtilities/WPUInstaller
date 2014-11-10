#!/bin/bash

###################################
## Vars
###################################

WP_DOMAIN='https://fr.wordpress.org/';
WP_INSTALL_FILE='latest-fr_FR.zip';
WP_THEME_DIR='wp-content/themes/';
WP_MUPLUGINS_DIR='wp-content/mu-plugins/';
WP_PLUGINS_DIR='wp-content/plugins/';
WPU_SUBMODULE_PLUGINS="wpuoptions wpupostmetas wpuseo";
WPU_MUPLUGINS="wpu_body_classes wpu_posttypestaxos wpu_ux_tweaks";
WPU_FORCED_MUPLUGINS="wpudisablecomments wpudisablesearch wpudisableposts wputh_admin_protect";
MAINDIR="${PWD}/";

###################################
## Questions
###################################

read -p "What's the project name ? " project_name;
if [[ $project_name == '' ]]; then
    echo "— The project name is required"; exit 0;
fi;

read -p "What's the project dev url ? " project_dev_url;
if [[ $project_dev_url == '' ]]; then
    echo "— The project dev_url is required"; exit 0;
fi;

read -p "What's the project id ? " project_id;
if [[ $project_id == '' ]]; then
    echo "— The project id is required"; exit 0;
fi;

###################################
## Test git
###################################

if [[ ! -f 'wp-content' ]]; then
    git init;
fi;

###################################
## WordPress installation
###################################

# If there is no wp-content dir
if [[ ! -f 'wp-content' ]]; then
    echo '### WordPress installation';
    curl -O "${WP_DOMAIN}${WP_INSTALL_FILE}";
    unzip "${WP_INSTALL_FILE}";
    rm "${WP_INSTALL_FILE}";
    mv wordpress/* .;
    rm -rf "wordpress/";
fi;

###################################
## WPUtilities installation
###################################

echo '### WPUtilities installation';
git clone git@github.com:Darklg/WPUtilities.git;

###################################
## Theme installation
###################################

echo '### Theme installation';

cp -r "WPUtilities/${WP_THEME_DIR}WPUTheme/" "${WP_THEME_DIR}${project_id}";

echo "/*
Theme Name: ${project_name} theme
Description: A WordPress theme for ${project_name}
Author: Darklg
Author URI: http://darklg.me/
*/" > "${WP_THEME_DIR}${project_id}/style.css";

###################################
## MU-Plugins installation
###################################

echo '### MU-Plugins installation';

mkdir "${WP_MUPLUGINS_DIR}";

# Classic MU Plugins
for i in $WPU_MUPLUGINS
do
    echo "## Install ${i}";
    cp "WPUtilities/${WP_MUPLUGINS_DIR}/${i}.php" "${WP_MUPLUGINS_DIR}/${i}.php";
done;

# Forced MU Plugins
for i in $WPU_FORCED_MUPLUGINS
do
    echo "## Install ${i}";
    cp "WPUtilities/${WP_PLUGINS_DIR}/${i}.php" "${WP_MUPLUGINS_DIR}/${i}.php";
done;

###################################
## Plugins installation
###################################

echo '### Plugins installation';

cd "${WP_PLUGINS_DIR}";

WPU_SUBMODULE_PLUGINS="wpuoptions wpupostmetas wpuseo";
for i in $WPU_SUBMODULE_PLUGINS
do
    echo "## Install ${i}";
    git submodule add "git@github.com:WordPressUtilities/${i}.git";
done;

cd "${MAINDIR}";

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
wp-config.php" >> "${MAINDIR}.gitignore";

###################################
## Clean up
###################################

echo '### Clean up';

# Unused plugins
rm -f "${WP_PLUGINS_DIR}hello.php";
rm -rf "${WP_PLUGINS_DIR}akismet/";

# Unused themes
rm -rf "${WP_THEME_DIR}twentyfourteen/";
rm -rf "${WP_THEME_DIR}twentythirteen/";
rm -rf "${WP_THEME_DIR}twentytwelve/";
rm -rf "WPUtilities/";