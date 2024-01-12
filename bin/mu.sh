#!/bin/bash

###################################
## MU-Plugins installation
###################################

echo '### MU-Plugins installation';

mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/blocks";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/entities";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/taxonomies";

cp "${SCRIPTDIR}inc/htaccess-muplugins.txt" "${MAINDIR}${WP_MUPLUGINS_DIR}.htaccess";

echo "## Install wpu_muplugin_autoloader";
wpuinstaller_cp_replace "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}wpu_muplugin_autoloader.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_muplugin_autoloader.php";
echo "- wpu_muplugin_autoloader is installed.";

echo "## Install wpu_local_overrides.php";
wpuinstaller_cp_replace "${SCRIPTDIR}inc/wpu_local_overrides.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_local_overrides.php";
echo "- wpu_local_overrides is installed.";

# Forced MU Plugins
for i in $WPU_FORCED_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu/${i}.php";
    echo "- ${i} is installed.";
done;

# Forced Submodules MU Plugins
for i in $WPU_SUBMODULES_FORCED_MUPLUGINS
do
    wpuinstaller_install_mu "${i}";
done;

# Classic MU Plugins
for i in $WPU_SUBMODULES_MUPLUGINS_OK
do
    wpuinstaller_install_mu "${i}";
done;

# Translation
if [[ $project_l10n == 'n' ]]; then
    echo "## Install Translation";
    wpuinstaller_install_mu "wpu_override_gettext";
fi;

# Base Functions plugin
_functions_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";
wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_functions.php" "${_functions_file}";

# Base Perfs plugin
_perfs_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_perfs.php";
wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_perfs.php" "${_perfs_file}";

# Base Options plugin
_options_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_options.php";
wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_options.php" "${_options_file}";

# Settings plugin
wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_settings.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";

# Users plugin
wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_users.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_users.php";

# Posts
if [[ "${need_posts}" == 'y' ]];then
    # Ensure dir exists
    _POSTS_DIR="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/entities";
    mkdir -p "${_POSTS_DIR}";
    # Copy file
    _POSTS_FILE="${_POSTS_DIR}/${project_id}_posts.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_posts.php" "${_POSTS_FILE}";

    # RSS Feeds
    if [[ "${need_rss}" == 'y' ]];then
        wpuinstaller_install_mu "wpu_better_rss";
        base_rss_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_rss.php";
        wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_rss.php" "${base_rss_file}";
    fi;
fi

# News page
if [[ "${need_posts_tpl}" == 'y' ]];then
    pagenews__cms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages/${project_id}_pagenews.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_page_news.php" "${pagenews__cms_file}";
fi;

# Menus
if [[ "${need_advanced_menus}" == 'y' ]];then
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_menus.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_menus.php";
fi;

# Menus
if [[ "${need_extranet}" == 'y' ]];then
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_extranet.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_extranet.php";
    wpuinstaller_install_mu "wpu_extranet";
fi;

# Home page
if [[ "${home_is_cms}" == 'y' ]]; then
    home__cms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages/${project_id}_home.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/cms_home.php" "${home__cms_file}";
    home__page_id=$(php ${WPU_PHPCLI} option get home__page_id)
    php ${WPU_PHPCLI} option update page_on_front "${home__page_id}";
    php ${WPU_PHPCLI} option update show_on_front "page";
fi;

if [[ "${need_acf_forms}" == 'y' || "${need_contact_form}" == 'y' ]]; then
    base_forms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_forms.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_forms.php" "${base_forms_file}";
fi;

# Commit Add mu-plugins
bashutilities_commit_all "Installation - MU-Plugins";
