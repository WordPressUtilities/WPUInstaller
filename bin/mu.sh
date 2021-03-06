#!/bin/bash

###################################
## MU-Plugins installation
###################################

echo '### MU-Plugins installation';

mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/entities";

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
    echo "## Install ${i}";
    cd "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/${i}.git" "${use_submodules}";
    cd "${MAINDIR}";
    echo "- ${i} is installed.";
done;

# Classic MU Plugins
for i in $WPU_SUBMODULES_MUPLUGINS_OK
do
    echo "## Install ${i}";
    cd "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/${i}.git" "${use_submodules}";
    cd "${MAINDIR}";
    echo "- ${i} is installed.";
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
if [[ ${WPU_SUBMODULES_MUPLUGINS_OK} != *"wpudisableposts"* ]];then
    cp "${SCRIPTDIR}inc/base_posts.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_posts.php";
    wpuinstaller_replace "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_posts.php";
fi

# Home page
if [[ "${home_is_cms}" == 'y' ]]; then
    home__cms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages/${project_id}_home.php";
    cp "${SCRIPTDIR}inc/cms_home.php" "${home__cms_file}";
    wpuinstaller_replace "${home__cms_file}";
    home__page_id=$(php ${WPU_PHPCLI} option get home__page_id)
    php ${WPU_PHPCLI} option update page_on_front "${home__page_id}";
    php ${WPU_PHPCLI} option update show_on_front "page";
fi;

if [[ "${home_is_cms}" == 'y' && "${need_acf}" == 'y' ]]; then
    cat "${SCRIPTDIR}inc/cms_home--acf.php" >> "${home__cms_file}";
    bashutilities_sed "s+<?php \/\*\ \*\/++g" "${home__cms_file}";
fi;

if [[ "${need_acf_forms}" == 'y' ]]; then
    base_forms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_forms.php";
    cp "${SCRIPTDIR}inc/base_forms.php" "${base_forms_file}";
    wpuinstaller_replace "${base_forms_file}";
fi;

# Commit Add mu-plugins
git add -A
git commit --no-verify -m "Installation - MU-Plugins" --quiet;
