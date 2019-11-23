<?php
/*
Plugin Name: [wpuproject] Functions
Description: Website common functions
*/

/* ----------------------------------------------------------
  Disable REST API for non logged-in users
---------------------------------------------------------- */

add_action('plugins_loaded', 'wpuprojectid_functions_plugins_loaded', 5);
function wpuprojectid_functions_plugins_loaded() {
    if (!is_user_logged_in()) {
        add_filter('wpudisabler__disable_wp_api', '__return_true');
    }
}
