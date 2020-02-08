#!/bin/bash

###################################
## Submodule or install
###################################

function wpui_submodule_or_install(){
    local repo_git=$1
    local use_submodules=$2
    local filename=$(basename -- "$repo_git")
    local extension="${filename##*.}"
    local filename="${filename%.*}"
    if [[ "${use_submodules}" == 'y' ]]; then
        git submodule --quiet add "${repo_git}";
    else
        git clone --quiet "${repo_git}";
        rm -rf "${filename}/".git*;
    fi;
}
