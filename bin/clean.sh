#!/bin/bash

###################################
## Clean up
###################################

echo '### Clean up';

# Unused items
rm -rf "${MAINDIR}WPUtilities/";
rm -rf "${MAINDIR}wp-cli.phar";