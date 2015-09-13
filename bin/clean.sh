#!/bin/bash

###################################
## Clean up
###################################

echo '### Clean up';

# Unused plugins
rm -f "${WP_PLUGINS_DIR}hello.php";

# Unused themes
rm -rf "WPUtilities/";
rm -rf "wp-cli.phar";