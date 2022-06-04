<?php

/*
Plugin Name: [wpuprojectname] Filters
Description: Config for Filters
*/

/* ----------------------------------------------------------
  Basic filters settings
---------------------------------------------------------- */

add_filter('wpulivesearch_settings', function ($settings = array()) {
    # $settings['load_all_default'] = true;
    # $settings['pager_load_more'] = true;
    # $settings['results_per_page'] = 6;
    # $settings['dynamic_url'] = true;
    # $settings['inclusive_search'] = true;
    return $settings;
}, 10, 1);
