<?php
/*
Plugin Name: [wpuprojectname] Lang
Description: Settings for translation
*/

/* ----------------------------------------------------------
  Set translation folder
---------------------------------------------------------- */

add_filter('wpupllutilities__folders_to_scan', function ($folders) {
    $folders[] = ABSPATH . '/wp-content/themes/wpuprojectid';
    $folders[] = ABSPATH . '/wp-content/mu-plugins/wpuprojectid';
    return $folders;
}, 10, 1);

add_filter('wpu_pll_utilities_helper_translate_domain', function ($content) {
    return 'wpuprojectid';
}, 10, 1);

/* ----------------------------------------------------------
  Lang : Autoredirect
---------------------------------------------------------- */

add_filter('wpu_pll_utilities_settings_front', function ($settings) {
    // $settings['autoredirect'] = 0;
    return $settings;
}, 10, 1);
