#!/bin/bash

###################################
## Git
###################################

## Test git

if [[ ! -d '.git' ]]; then
    git init;
fi;

## Set gitignore

echo '### Set gitignore';

echo "node_modules
tests/config.json
.sass-cache
wp-cli.phar
/WPUtilities/
/.htaccess
/wp-content/uploads/
/wp-content/debug.log
/wp-config.php" >> "${MAINDIR}.gitignore";
