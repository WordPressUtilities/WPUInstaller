#!/bin/bash

###################################
## Vars
###################################

WP_LOCALE='fr_FR';
WP_THEME_DIR='wp-content/themes/';
WP_LANG_DIR='wp-content/languages/';
WP_MUPLUGINS_DIR='wp-content/mu-plugins/';
WP_PLUGINS_DIR='wp-content/plugins/';
WPU_SUBMODULE_PLUGINS="wpuseo ";
WPU_FORCED_MUPLUGINS="wpu_body_classes ";
WPU_SUBMODULES_FORCED_MUPLUGINS="wpu_ux_tweaks wpu_file_cache wpu_health_check_tests wpu_settings_version wpu_admin_protect wpu_super_editor wpuhtaccessprotect wpuoptions wpupostmetas wpuposttypestaxos wputaxometas wpuusermetas wpudisabler";
WPU_SUBMODULES_MUPLUGINS="wpudisablesearch wpudisableposts wpudisablecomments wpusimilar";
WPU_PHPCLI="${MAINDIR}wp-cli.phar";
