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
node_modules
.sass-cache
my.cnf
wp-cli.phar
wpu_local_overrides.php
WPUtilities/
/.htaccess
wp-content/themes/${project_id}/tests/config.json
wp-content/themes/twenty*
wp-content/uploads/
wp-content/db.php
wp-content/debug.log
wp-config.php" >> "${wpu_git_root_dir}/.gitignore";
