#!/bin/bash

###################################
## Shell scripts
###################################

WPU_SHELL="${MAINDIR}shell/";

if [[ $wpu_add_shell_scripts == 'y' ]]; then

    cd "${MAINDIR}";

    # Create folder
    mkdir "${WPU_SHELL}";
    cd "${WPU_SHELL}";

    # Quick protection
    touch "${WPU_SHELL}.htaccess";
    echo 'deny from all' > "${WPU_SHELL}.htaccess";

    # Add submodule
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/wpuwooimportexport.git" "${use_submodules}";

    # Add example
    cp "${SCRIPTDIR}inc/base_shell.php" "${WPU_SHELL}clean.php"
    wpuinstaller_replace "${WPU_SHELL}clean.php";

    cd "${MAINDIR}";
fi;

###################################
## API
###################################

if [[ "${use_external_api}" == 'y' ]];then

    # Load Base Plugin Settings
    git clone --quiet "https://github.com/WordPressUtilities/wpubaseplugin.git";
    mv "${MAINDIR}wpubaseplugin/inc/WPUBaseSettings" "${MAINDIR}${WP_MUPLUGINS_DIR}/wpu/WPUBaseSettings";
    rm -rf "${MAINDIR}wpubaseplugin/";
    bashutilities_sed "s/namespace\ .*/namespace ${project_id}_wpubasesettings;/g"  "${MAINDIR}${WP_MUPLUGINS_DIR}/wpu/WPUBaseSettings/WPUBaseSettings.php";

    # Create mu-plugin API
    _mu_plugin_api_dir="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/api/";
    _mu_plugin_api_file="${_mu_plugin_api_dir}${project_id}_api.php";
    mkdir "${_mu_plugin_api_dir}";
    cp "${SCRIPTDIR}inc/base_api.php" "${_mu_plugin_api_file}";
    wpuinstaller_replace "${_mu_plugin_api_file}";

fi;

###################################
## Tests
###################################

if [[ "${use_code_tests}" == 'y' ]];then
    # Create mu-plugin API
    _phpstanfile="${MAINDIR}phpstan.neon";
    cp "${SCRIPTDIR}inc/phpstan.neon" "${_phpstanfile}";
    wpuinstaller_replace "${_phpstanfile}";
fi;
