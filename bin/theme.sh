#!/bin/bash

###################################
## Theme installation
###################################

cd "${MAINDIR}${WP_THEME_DIR}";

echo '### Parent Theme installation';

bashutilities_submodule_or_install "https://github.com/WordPressUtilities/WPUTheme.git" "${use_submodules}";

# Commit Theme Installation
git add -A
git commit --no-verify -m "Installation - Framework Theme" --quiet;

echo '### Child theme initialisation';

# Generate child theme
cd "${MAINDIR}${WP_THEME_DIR}";
mkdir "${project_id}";

# - Style CSS
touch "${WPU_THEME}style.css";
echo "/*
Theme Name: ${project_name}
Description: A WordPress theme for ${project_name}
Template: WPUTheme
Author: Darklg
Author URI: http://darklg.me/
*/" > "${WPU_THEME}style.css";

# - htaccess
cp "${SCRIPTDIR}inc/htaccess.txt" "${WPU_THEME}.htaccess";

# - Index
HOME_PAGE_SOURCE="${SCRIPTDIR}inc/front-page.php";
if [[ "${home_is_cms}" == 'y' ]]; then
    if [[ "${need_acf}" == 'y' ]];then
        HOME_PAGE_SOURCE="${SCRIPTDIR}inc/front-page--acf.php";
    else
        HOME_PAGE_SOURCE="${SCRIPTDIR}inc/front-page--cms.php";
    fi;
fi;
cp "${HOME_PAGE_SOURCE}" "${WPU_THEME}front-page.php";


# - Functions
cp "${SCRIPTDIR}inc/functions.php" "${WPU_THEME}functions.php";
bashutilities_sed "s/wpuproject/${project_id}/g" "${WPU_THEME}functions.php";

if [[ $has_attachment_tpl == 'n' ]];then
    cp "${SCRIPTDIR}inc/attachment.php" "${WPU_THEME}attachment.php";
fi;

_functions_enable_multilingual='false';
if [[ "${project_l10n}" == 'y' ]]; then
    _functions_enable_multilingual='true';
fi;
bashutilities_sed "s/project_is_multilingual/__return_${_functions_enable_multilingual}/g" "${WPU_THEME}functions.php";

# - Templates
cp -rf "${SCRIPTDIR}inc/tpl/" "${WPU_THEME}tpl/";

# - Tests
cp -rf "${SCRIPTDIR}inc/tests/" "${WPU_THEME}tests/";
bashutilities_sed "s,wpuprojecturl,${project_dev_url},g" "${WPU_THEME}tests/config.json";

# - Translation
mkdir "${WPU_THEME}lang/";
touch "${WPU_THEME}lang/.htaccess";
echo 'deny from all' > "${WPU_THEME}lang/.htaccess";
WPU_THEME_TRANSLATE_FILE="${WPU_THEME}lang/${WP_LOCALE}.po";
cp "${SCRIPTDIR}inc/lang/fr_FR.po" "${WPU_THEME_TRANSLATE_FILE}";
bashutilities_sed "s/fr_FR/${WP_LOCALE}/g" "${WPU_THEME_TRANSLATE_FILE}";
bashutilities_sed "s/wpuprojectid/${project_id}/g" "${WPU_THEME_TRANSLATE_FILE}";
bashutilities_sed "s/wpuproject/${project_name}/g" "${WPU_THEME_TRANSLATE_FILE}";

# - Assets
mkdir "${WPU_THEME}assets/";
mkdir "${WPU_THEME}assets/images";
cp "${SCRIPTDIR}inc/assets/logo.png" "${WPU_THEME}assets/images/logo.png";
cp "${SCRIPTDIR}inc/assets/screenshot.png" "${WPU_THEME}screenshot.png";
mkdir "${WPU_THEME}assets/js";
cp "${SCRIPTDIR}inc/assets/events.js" "${WPU_THEME}assets/js/events.js";

cd "${MAINDIR}";

# Delete default content (Before creation at theme activation)
php ${WPU_PHPCLI} post delete $(php ${WPU_PHPCLI} post list --post_type='page' --format=ids)
php ${WPU_PHPCLI} post delete $(php ${WPU_PHPCLI} post list --post_type='post' --format=ids)

# Activate child theme
php ${WPU_PHPCLI} theme activate "WPUTheme";
php ${WPU_PHPCLI} theme activate "${project_id}";

# Commit Theme Installation
git add -A
git commit --no-verify -m "Installation - Child Theme" --quiet;
