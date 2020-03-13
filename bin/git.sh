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

echo "# WordPress
.sass-cache
/.htaccess
my.cnf
node_modules
wp-cli.phar
wp-config.php
wp-content/db.php
wp-content/debug.log
wp-content/object-cache.php
wp-content/themes/${project_id}/tests/config.json
wp-content/themes/twenty*
wp-content/uploads/
wpu_local_overrides.php
WPUtilities/" >> "${wpu_git_root_dir}/.gitignore";
