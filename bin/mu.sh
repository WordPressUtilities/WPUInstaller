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
bashutilities_sed "s/wpuproject/${project_name}/g" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_local_overrides.php";
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
    wpui_submodule_or_install "https://github.com/WordPressUtilities/${i}.git" "${use_submodules}";
    cd "${MAINDIR}";
    echo "- ${i} is installed.";
done;

# Classic MU Plugins
for i in $WPU_MUPLUGINS
do
    read -p "## Install ${i} ? (y/N) " install_muplugin;
    if [[ $install_muplugin == 'y' ]];then
        cp "${MAINDIR}WPUtilities/${WP_PLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu/${i}.php";
        echo "- ${i} is installed.";
    fi;
done;

# Base Functions plugin
_functions_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";
cp "${SCRIPTDIR}inc/base_functions.php" "${_functions_file}";
bashutilities_sed "s/wpuprojectid/${project_id}/g" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";
bashutilities_sed "s/wpuproject/${project_name}/g" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";

# Settings plugin
cp "${SCRIPTDIR}inc/base_settings.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
bashutilities_sed "s/wpuprojectid/${project_id}/g" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
bashutilities_sed "s/wpuproject/${project_name}/g" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";

# Home page
if [[ "${home_is_cms}" == 'y' ]]; then
    home__cms_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/pages/${project_id}_home.php";
    cp "${SCRIPTDIR}inc/cms_home.php" "${home__cms_file}";
    bashutilities_sed "s/wpuprojectname/${project_name}/g" "${home__cms_file}";
    bashutilities_sed "s/wpuprojectid/${project_id}/g" "${home__cms_file}";
    home__page_id=$(php ${WPU_PHPCLI} option get home__page_id)
    php ${WPU_PHPCLI} option update page_on_front "${home__page_id}";
    php ${WPU_PHPCLI} option update show_on_front "page";
fi;

# Commit Add mu-plugins
git add -A
git commit --no-verify -m "Installation - MU-Plugins" --quiet;
