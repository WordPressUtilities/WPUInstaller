#!/bin/bash

###################################
## Git
###################################

## If git
## https://stackoverflow.com/a/38088814
wpu_git_is_active="$(git rev-parse --is-inside-work-tree 2>/dev/null)";
if [[ $wpu_git_is_active != 'true' ]]; then
    git init;
fi;

# Get root dir
wpu_git_root_dir="$(git rev-parse --show-toplevel)";

## Set gitignore
echo '### Set gitignore';
cat "${SCRIPTDIR}inc/base_gitignore"  >> "${wpu_git_root_dir}/.gitignore";

if [[ "${need_comments}" == 'n' ]];then
    echo '/wp-comments-post.php' >> "${wpu_git_root_dir}/.gitignore";
    echo '/wp-trackback.php' >> "${wpu_git_root_dir}/.gitignore";
fi;
