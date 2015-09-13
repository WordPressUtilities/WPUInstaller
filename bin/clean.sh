#!/bin/bash

###################################
## Clean up
###################################

echo '### Clean up';

# Delete default content
php "${MAINDIR}wp-cli.phar" post delete $(php ${MAINDIR}wp-cli.phar post list --post_type='page' --format=ids)
php "${MAINDIR}wp-cli.phar" post delete $(php ${MAINDIR}wp-cli.phar post list --post_type='post' --format=ids)
php "${MAINDIR}wp-cli.phar" comment delete $(php ${MAINDIR}wp-cli.phar comment list --format=ids)

# Unused items
rm -rf "${MAINDIR}WPUtilities/";
rm -rf "${MAINDIR}wp-cli.phar";