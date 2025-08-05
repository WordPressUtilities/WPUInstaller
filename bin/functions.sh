#!/bin/bash


function wpuinstaller_replace() {
    bashutilities_sed "s/wpuprojectname/${project_name}/g" "${1}";
    bashutilities_sed "s/wpuprojectid/${project_id}/g" "${1}";
    bashutilities_sed "s/wpuproject/${project_name}/g" "${1}";
    bashutilities_sed "s/wpumysqlprefix/${mysql_prefix}/g" "${1}";
}

function wpuinstaller_cp_replace() {
    rm -f "${2}";
    cp "${1}" "${2}";
    wpuinstaller_replace "${2}";
}

function wpuinstaller_github_plugin(){
    _PLUGIN_USER="$1";
    _PLUGIN_NAME="$2";

    # Download latest
    curl -s "https://api.github.com/repos/${_PLUGIN_USER}/${_PLUGIN_NAME}/releases/latest" | grep -E 'zipball_url' | cut -d '"' -f 4 | wget -O "${_PLUGIN_NAME}.zip" -qi -
    # Unzip file
    unzip  -d tmp_plugin_dir/ "${_PLUGIN_NAME}.zip" && rm -f "${_PLUGIN_NAME}.zip";
    # Fix dirname
    mv -f tmp_plugin_dir/* "tmp_plugin_dir/${_PLUGIN_NAME}"
    # Install dependencies
    if [[ -f "tmp_plugin_dir/${_PLUGIN_NAME}/composer.json" ]];then
        $(cd "tmp_plugin_dir/${_PLUGIN_NAME}" && composer install --no-dev --no-scripts);
    fi;
    # Move to plugins dir
    mv -f "tmp_plugin_dir/${_PLUGIN_NAME}" "wp-content/plugins/${_PLUGIN_NAME}";
    # Delete tmp dir
    rm -rf tmp_plugin_dir;
}

function wpuinstaller_install_mu(){
    echo "## Install ${1}";
    cd "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/${1}.git" "${use_submodules}";
    cd "${MAINDIR}";
    echo "- ${1} is installed.";
}

function wpuinstaller_install_plugin(){
    echo "## Install ${1}";
    cd "${MAINDIR}${WP_PLUGINS_DIR}";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/${1}.git" "${use_submodules}";
    cd "${MAINDIR}";
    echo "- ${1} is installed.";
}
