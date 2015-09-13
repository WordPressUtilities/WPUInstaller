#!/bin/bash

###################################
## Theme installation v 0.1
###################################

cd "${MAINDIR}${WP_THEME_DIR}";

echo '### Parent Theme installation';

git submodule --quiet add "https://github.com/WordPressUtilities/WPUTheme.git";

# Commit Theme Installation
git add .
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
php "${MAINDIR}wp-cli.phar" theme activate "${project_id}";

# Commit Theme Installation
git add .
git commit -m "Installation - Child Theme" --quiet;