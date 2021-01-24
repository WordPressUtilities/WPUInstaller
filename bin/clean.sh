#!/bin/bash

###################################
## Clean up
###################################

echo '### Clean up';

# Unused items
rm -rf "${MAINDIR}WPUtilities/";
rm "${MAINDIR}wp-cli.phar";
rm "${MAINDIR}my.cnf";
