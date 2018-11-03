#!/bin/bash

###################################
## Vars
###################################

WP_LOCALE='fr_FR';
WP_THEME_DIR='wp-content/themes/';
WP_LANG_DIR='wp-content/languages/';
WP_MUPLUGINS_DIR='wp-content/mu-plugins/';
WP_PLUGINS_DIR='wp-content/plugins/';
WPU_SUBMODULE_PLUGINS="wpuoptions wpupostmetas wpuseo wpuposttypestaxos wputaxometas wpudisabler";
WPU_FORCED_MUPLUGINS="wpu_body_classes wpu_ux_tweaks wpu_settings_version wputh_admin_protect";
WPU_MUPLUGINS="wpudisablecomments wpudisablesearch wpudisableposts";
WPU_PHPCLI="${MAINDIR}wp-cli.phar";
