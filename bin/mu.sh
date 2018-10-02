#!/bin/bash

###################################
## MU-Plugins installation
###################################

echo '### MU-Plugins installation';

mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}";

echo "## Install wpu_muplugin_autoloader";
cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}wpu_muplugin_autoloader.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu_muplugin_autoloader.php";
echo "- wpu_muplugin_autoloader is installed.";

# Forced MU Plugins
for i in $WPU_FORCED_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}wpu/${i}.php";
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
cp "${SCRIPTDIR}inc/base_functions.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";
wpuinstaller_sed "s/wpuproject/${project_name}/" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_functions.php";

# Settings plugin
cp "${SCRIPTDIR}inc/base_settings.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
wpuinstaller_sed "s/wpuprojectid/${project_id}/" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
wpuinstaller_sed "s/wpuproject/${project_name}/" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";

# Home page
if [[ $home_is_cms == 'y' ]]; then
    cp "${SCRIPTDIR}inc/cms_home.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_home.php";
    wpuinstaller_sed "s/wpuprojectname/${project_name}/" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_home.php";
    wpuinstaller_sed "s/wpuprojectid/${project_id}/" "${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_home.php";
    home__page_id=$(php ${WPU_PHPCLI} option get home__page_id)
    php ${WPU_PHPCLI} option update page_on_front "${home__page_id}";
    php ${WPU_PHPCLI} option update show_on_front "page";
fi;

# Commit Add mu-plugins
git add -A
git commit --no-verify -m "Installation - MU-Plugins" --quiet;
