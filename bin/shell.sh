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
    if [[ $use_submodules == 'y' ]]; then
        git submodule --quiet add "https://github.com/WordPressUtilities/wpuwooimportexport.git";
    else
        git clone --quiet "https://github.com/WordPressUtilities/wpuwooimportexport.git";
        rm -rf "wpuwooimportexport/.git";
    fi;
    # Add example
    cp "${SCRIPTDIR}inc/base_shell.php" "${WPU_SHELL}clean.php"
    bashutilities_sed "s/wpuprojectid/${project_id}/g" "${WPU_SHELL}clean.php";

    cd "${MAINDIR}";
fi;
