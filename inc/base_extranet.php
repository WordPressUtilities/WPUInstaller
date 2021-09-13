<?php
/*
Plugin Name: [wpuprojectname] Extranet
Description: Handle website extranet
*/

/* ----------------------------------------------------------
  Pages
---------------------------------------------------------- */

add_filter('wputh_pages_site', function ($pages_site) {
    /* Uncomment when your pages are configured */
    return $pages_site;
    $pages_site['extranet_dashboard__page_id'] = array(
        'post_title' => 'Dashboard',
        'page_template' => 'extranet-dashboard.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_comments__page_id'] = array(
        'post_title' => 'My comments',
        'page_template' => 'extranet-comments.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    return $pages_site;
});

/* ----------------------------------------------------------
  Pages settings
---------------------------------------------------------- */

/* Login page
-------------------------- */

function wpuprojectid_extranet__get_login_page() {
    return site_url('#panel-login');
}

/* Dashboard page
-------------------------- */

function wpuprojectid_extranet__get_dashboard_page() {
    return get_page_link(get_option('extranet_dashboard__page_id'));
}

/* Mark a page as extranet
-------------------------- */

add_filter('wputh_post_metas_boxes', function ($boxes) {
    $boxes['box_page_extranet'] = array(
        'name' => 'Extranet',
        'post_type' => array('page')
    );
    return $boxes;
});

add_filter('wputh_post_metas_fields', function ($fields) {
    $fields['is_extranet_page'] = array(
        'box' => 'box_page_extranet',
        'name' => 'Extranet page',
        'type' => 'checkbox'
    );
    return $fields;
});

/* Fix private pages format
-------------------------- */

add_filter('private_title_format', 'wpuprojectid_extranet__title_format', 10, 2);
add_filter('protected_title_format', 'wpuprojectid_extranet__title_format', 10, 2);
function wpuprojectid_extranet__title_format($content, $p) {
    $is_extranet_page = get_post_meta($p->ID, 'is_extranet_page', 1) == '1';
    if ($is_extranet_page) {
        return '%s';
    }
    return $content;
}

/* Force private status for extranet pages
-------------------------- */

add_action('save_post', function ($post_id, $post, $update) {
    /* Avoid an infinite loop */
    if (defined('wpuprojectid_extranet__force_private')) {
        return;
    }
    if ('page' !== $post->post_type) {
        return;
    }
    $is_extranet_page = get_post_meta($post_id, 'is_extranet_page', 1) == '1';
    if (!$is_extranet_page) {
        return;
    }
    define('wpuprojectid_extranet__force_private', 1);
    wp_update_post(array(
        'ID' => $post_id,
        'post_status' => 'private'
    ));

}, 99, 3);

/* Redirect to login if invalid access to a private page
-------------------------- */

add_action('wp', function () {
    $queried_object = get_queried_object();
    if (!is_object($queried_object) || !isset($queried_object->post_status)) {
        return;
    }
    if ($queried_object->post_status != "private") {
        return;
    }
    if (is_user_logged_in()) {
        return;
    }
    wp_redirect(wpuprojectid_extranet__get_login_page());
});

/* Failed login in front : redirect to the home page
-------------------------- */

add_action('wp_login_failed', function ($username) {
    if (!strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
        wp_redirect(add_query_arg('login', 'failed', wpuprojectid_extranet__get_login_page()));
        exit;
    }
});

/* ----------------------------------------------------------
  Templates
---------------------------------------------------------- */

/* Header
-------------------------- */

add_action('wputheme_main_overcontent_inajax', function () {
    if (!is_page()) {
        return;
    }
    $is_extranet_page = get_post_meta(get_the_ID(), 'is_extranet_page', 1) == '1';
    if (!$is_extranet_page) {
        return;
    }

    echo '<div><h1>' . __('Extranet', 'wpuprojectid') . '</h1></div>';
    echo wpuprojectid_extranet_get_menu();
    echo '<h2>' . get_the_title() . '</h2>';

}, 999);

/* Menu
-------------------------- */

function wpuprojectid_extranet_get_menu() {

    $html = '';
    $posts_extranet = get_posts(array(
        'post_type' => 'page',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'meta_query' => array(array(
            'key' => 'is_extranet_page',
            'compare' => '1',
            'value' => '1'
        ))
    ));

    $html .= '<ul>';
    foreach ($posts_extranet as $p) {
        $html .= '<li><a ' . ($p->ID == get_the_ID() ? 'class="active"' : '') . ' href="' . get_permalink($p) . '">' . get_the_title($p) . '</a></li>';
    }
    $html .= '<li><a href="' . wp_logout_url(site_url()) . '">' . __('Log out', 'wpuprojectid') . '</a></li>';
    $html .= '</ul>';

    return $html;
}
