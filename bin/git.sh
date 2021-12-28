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
.disable_wpu_admin_protect
/.htaccess
/vendor
/composer.lock
diff-before.txt
diff-after.txt
my.cnf
node_modules
package-lock.json
wp-cli.phar
yarn.lock
wp-config.php
wp-content/advanced-cache.php
wp-content/cache/
wp-content/db.php
wp-content/debug.log
wp-content/languages/themes/twenty*
wp-content/object-cache.php
wp-content/plugins/wp-rocket/license-data.php
wp-content/themes/${project_id}/tests/config.json
wp-content/themes/twenty*
wp-content/uploads/
wp-content/w3tc-config/
wp-content/wp-rocket-config/
wpu-test.php
wpu_local_overrides.php
WPUtilities/" >> "${wpu_git_root_dir}/.gitignore";
