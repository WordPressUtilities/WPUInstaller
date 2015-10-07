#!/bin/bash

###################################
## Theme installation
###################################

cd "${MAINDIR}${WP_THEME_DIR}";

echo '### Parent Theme installation';

if [[ $use_submodules == 'y' ]]; then
    git submodule --quiet add "https://github.com/WordPressUtilities/WPUTheme.git";
else
    git clone --quiet "https://github.com/WordPressUtilities/WPUTheme.git";
    rm -rf "WPUTheme/.git";
fi;

# Commit Theme Installation
git add -A
git commit -m "Installation - Framework Theme" --quiet;

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
touch "${WPU_THEME}inc/.htaccess";
echo 'deny from all' > "${WPU_THEME}inc/.htaccess";
mkdir "${WPU_THEME}inc/lang/";
cp "${SCRIPTDIR}inc/lang/fr_FR.po" "${WPU_THEME}inc/lang/fr_FR.po";

# - Assets
mkdir "${WPU_THEME}assets/";
mkdir "${WPU_THEME}assets/images";
wget "http://placehold.it/200x100/fff/000?text=${project_id}" -q -O "${WPU_THEME}assets/images/logo.png";
mkdir "${WPU_THEME}assets/js";
touch "${WPU_THEME}assets/js/events.js";

# Delete default content ( Before creation by theme )
php "${MAINDIR}wp-cli.phar" post delete $(php ${MAINDIR}wp-cli.phar post list --post_type='page' --format=ids)
php "${MAINDIR}wp-cli.phar" post delete $(php ${MAINDIR}wp-cli.phar post list --post_type='post' --format=ids)
php "${MAINDIR}wp-cli.phar" comment delete $(php ${MAINDIR}wp-cli.phar comment list --format=ids)

# Activate child theme
cd "${MAINDIR}";
php "${MAINDIR}wp-cli.phar" theme activate "WPUTheme";
php "${MAINDIR}wp-cli.phar" theme activate "${project_id}";

# Commit Theme Installation
git add -A
git commit -m "Installation - Child Theme" --quiet;