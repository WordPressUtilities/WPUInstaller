<?php
/*
Plugin Name: [wpuproject] Functions
Description: Website common functions
*/

/* ----------------------------------------------------------
  Disable REST API for non logged-in users
---------------------------------------------------------- */

add_action('plugins_loaded', function () {
    if (!is_user_logged_in()) {
        add_filter('wpudisabler__disable_wp_api', '__return_true');
    }
}, 5);

/* ----------------------------------------------------------
  Core Sitemaps : Disable authors list
---------------------------------------------------------- */

add_filter('core_sitemaps_users_url_list', '__return_empty', 10, 1);

/* ----------------------------------------------------------
  Search only in posts
---------------------------------------------------------- */

add_filter('pre_get_posts', function ($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('post'));
    }
    return $query;
});

/* ----------------------------------------------------------
  Disable author page
---------------------------------------------------------- */

add_action('template_redirect', function () {
    if (is_author()) {
        wp_redirect(site_url());
        die;
    }
});
