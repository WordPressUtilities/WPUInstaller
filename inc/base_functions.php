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
  Hide author in JSON Metas
---------------------------------------------------------- */

add_filter('wpuseo_metas_json_after_settings', function ($metas_json) {
    if (isset($metas_json['author'])) {
        $metas_json['author'] = get_bloginfo('name');
    }
    return $metas_json;
}, 10, 1);

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

/* ----------------------------------------------------------
  Allow SVG upload for admins
---------------------------------------------------------- */

add_filter('upload_mimes', function ($mimes) {
    if (current_user_can('delete_users')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

/* ----------------------------------------------------------
  Remove Gutemberg styles
---------------------------------------------------------- */

add_action('wp_print_styles', function () {
    wp_dequeue_style('wp-block-library');
}, 100);

/* ----------------------------------------------------------
  Force HTTPS via PHP
---------------------------------------------------------- */

add_action('plugins_loaded', function () {
    $site_url = site_url();
    if (defined('WP_SITEURL')) {
        $site_url = WP_SITEURL;
    }
    /* Only if HTTPS is needed */
    if (substr($site_url, 0, 5) != 'https') {
        return;
    }
    if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'http') || (isset($_SERVER['HTTP_X_REMOTE_PROTO']) && $_SERVER['HTTP_X_REMOTE_PROTO'] == 'http')) {
        header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit();
    }
}, 10, 1);
