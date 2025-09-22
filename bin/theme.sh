#!/bin/bash

###################################
## Theme installation
###################################

cd "${MAINDIR}${WP_THEME_DIR}";

echo '### Parent Theme installation';

bashutilities_submodule_or_install "https://github.com/WordPressUtilities/WPUTheme.git" "${use_submodules}";

# Commit Theme Installation
bashutilities_commit_all "Installation - Framework Theme";

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
Author: Author
Author URI: https://www.Author.com/
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
wpuinstaller_cp_replace "${HOME_PAGE_SOURCE}" "${WPU_THEME}front-page.php";

# - JSON
echo "{}" > "${WPU_THEME}theme.json";

# - Functions
wpuinstaller_cp_replace "${SCRIPTDIR}inc/functions.php" "${WPU_THEME}functions.php";

# - Various functions files
cp -r "${SCRIPTDIR}inc/theme_inc" "${WPU_THEME}inc";
wpuinstaller_replace "${WPU_THEME}inc/parent-theme.php";
wpuinstaller_replace "${WPU_THEME}inc/styles.php";
wpuinstaller_replace "${WPU_THEME}inc/menus.php";

if [[ $has_attachment_tpl == 'n' ]];then
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/attachment.php" "${WPU_THEME}attachment.php";
fi;

_functions_enable_multilingual='false';
if [[ "${project_l10n}" == 'y' ]]; then
    _functions_enable_multilingual='true';
fi;
bashutilities_sed "s/project_is_multilingual/__return_${_functions_enable_multilingual}/g" "${WPU_THEME}inc/parent-theme.php";

# - Templates
cp -rf "${SCRIPTDIR}inc/tpl/" "${WPU_THEME}tpl/";
wpuinstaller_replace "${WPU_THEME}tpl/header.php";
wpuinstaller_replace "${WPU_THEME}tpl/footer.php";
wpuinstaller_cp_replace "${SCRIPTDIR}inc/page.php" "${WPU_THEME}page.php";

# - Search
if [[ "${need_search}" == 'y' ]];then
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/search-results.php" "${WPU_THEME}search.php";
fi

# - Translation
mkdir "${WPU_THEME}lang/";
touch "${WPU_THEME}lang/.htaccess";
echo 'deny from all' > "${WPU_THEME}lang/.htaccess";
WPU_THEME_TRANSLATE_FILE="${WPU_THEME}lang/${WP_LOCALE}.po";
cp "${SCRIPTDIR}inc/lang/fr_FR.po" "${WPU_THEME_TRANSLATE_FILE}";
bashutilities_sed "s/fr_FR/${WP_LOCALE}/g" "${WPU_THEME_TRANSLATE_FILE}";
wpuinstaller_replace "${WPU_THEME_TRANSLATE_FILE}";

# - Assets
mkdir "${WPU_THEME}assets/";
mkdir "${WPU_THEME}assets/images";
cp "${SCRIPTDIR}inc/assets/logo.png" "${WPU_THEME}assets/images/logo.png";
cp "${SCRIPTDIR}inc/assets/screenshot.png" "${WPU_THEME}screenshot.png";
mkdir "${WPU_THEME}assets/js";
cp "${SCRIPTDIR}inc/assets/app.js" "${WPU_THEME}assets/js/app.js";

# - Specific templates
if [[ "${need_acf}" == 'y' ]];then
    mkdir "${WPU_THEME}tpl/blocks";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/tpl-master-header.php" "${WPU_THEME}tpl/blocks/master-header.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/tpl-page-master.php" "${WPU_THEME}tpl/page-master.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/page-master.php" "${WPU_THEME}page.php";
    mkdir "${WPU_THEME}assets/js";
fi;

# News page
if [[ "${need_posts_tpl}" == 'y' ]];then
    mkdir "${WPU_THEME}tpl/loops";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/tpl-page-news.php" "${WPU_THEME}page-news.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/loop-post.php" "${WPU_THEME}tpl/loops/loop-post.php";
fi;

# Page 404
if [[ "${need_404_page}" == 'y' ]];then
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/tpl-page-404.php" "${WPU_THEME}404.php";
fi;

cd "${MAINDIR}";

# Delete default content (Before creation at theme activation)
php ${WPU_PHPCLI} post delete $(php ${WPU_PHPCLI} post list --post_type='page' --format=ids)
php ${WPU_PHPCLI} post delete $(php ${WPU_PHPCLI} post list --post_type='post' --format=ids)

# Activate child theme
php ${WPU_PHPCLI} theme activate "WPUTheme";
php ${WPU_PHPCLI} theme activate "${project_id}";

# Commit Theme Installation
bashutilities_commit_all "Installation - Child Theme";
