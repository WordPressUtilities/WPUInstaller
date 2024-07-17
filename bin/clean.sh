#!/bin/bash

###################################
## Clean up
###################################

echo '### Clean up';

# Unused items
rm -rf "${MAINDIR}WPUtilities/";
rm "${MAINDIR}wp-cli.phar";
rm "${MAINDIR}my.cnf";

# Functions
unset -f wpuinstaller_cp_replace;
unset -f wpuinstaller_github_plugin;
unset -f wpuinstaller_install_mu;
unset -f wpuinstaller_install_plugin;
unset -f wpuinstaller_replace;
