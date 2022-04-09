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

echo "## Install wpu_muplugin_autoloader";
cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}wpu_muplugin_autoloader.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_muplugin_autoloader.php";
echo "- wpu_muplugin_autoloader is installed.";

echo "## Install wpu_local_overrides.php";
cp "${SCRIPTDIR}inc/wpu_local_overrides.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_local_overrides.php";
wpuinstaller_replace "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_local_overrides.php";
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

# Base Functions plugin
_functions_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";
cp "${SCRIPTDIR}inc/base_functions.php" "${_functions_file}";
wpuinstaller_replace "${_functions_file}";

# Settings plugin
cp "${SCRIPTDIR}inc/base_settings.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
wpuinstaller_replace "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";

# Users plugin
cp "${SCRIPTDIR}inc/base_users.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_users.php";
wpuinstaller_replace "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_users.php";

# Posts
if [[ "${need_search}" == 'y' ]];then
    # Ensure dir exists
    _POSTS_DIR="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/entities";
    mkdir -p "${_POSTS_DIR}";
    # Copy file
    _POSTS_FILE="${_POSTS_DIR}/${project_id}_posts.php";
    cp "${SCRIPTDIR}inc/base_posts.php" "${_POSTS_FILE}";
    wpuinstaller_replace "${_POSTS_FILE}";
fi

# Menus
if [[ "${need_advanced_menus}" == 'y' ]];then
    cp "${SCRIPTDIR}inc/base_menus.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_menus.php";
    wpuinstaller_replace "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_menus.php";
fi;

# Menus
if [[ "${need_extranet}" == 'y' ]];then
    cp "${SCRIPTDIR}inc/base_extranet.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_extranet.php";
    wpuinstaller_replace "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_extranet.php";
    wpuinstaller_install_mu "wpu_extranet";
fi;

# Home page
if [[ "${home_is_cms}" == 'y' ]]; then
    home__cms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages/${project_id}_home.php";
    cp "${SCRIPTDIR}inc/cms_home.php" "${home__cms_file}";
    wpuinstaller_replace "${home__cms_file}";
    home__page_id=$(php ${WPU_PHPCLI} option get home__page_id)
    php ${WPU_PHPCLI} option update page_on_front "${home__page_id}";
    php ${WPU_PHPCLI} option update show_on_front "page";
fi;

if [[ "${need_acf_forms}" == 'y' ]]; then
    base_forms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_forms.php";
    cp "${SCRIPTDIR}inc/base_forms.php" "${base_forms_file}";
    wpuinstaller_replace "${base_forms_file}";
fi;

# Commit Add mu-plugins
git add -A
git commit --no-verify -m "Installation - MU-Plugins" --quiet;
