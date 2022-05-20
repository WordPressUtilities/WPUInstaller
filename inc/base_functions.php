<?php
/*
Plugin Name: [wpuprojectname] Functions
Description: Website common functions
*/

/* ----------------------------------------------------------
  Disable REST API for non logged-in users
---------------------------------------------------------- */

add_action('plugins_loaded', function () {
    if (is_user_logged_in()) {
        return;
    }
    if (current_user_can('remove_users')) {
        return;
    }
    add_filter('wpudisabler__disable_wp_api', '__return_true');
}, 5);

/* ----------------------------------------------------------
  Disable block editor
---------------------------------------------------------- */

add_filter('use_block_editor_for_post_type', '__return_false', 100);

/* ----------------------------------------------------------
  Core Sitemaps : Disable authors list
---------------------------------------------------------- */

add_filter('core_sitemaps_users_url_list', '__return_empty', 10, 1);

/* ----------------------------------------------------------
  Author
---------------------------------------------------------- */

/* Disable author page
-------------------------- */

add_action('template_redirect', function () {
    if (is_author()) {
        wp_redirect(site_url());
        die;
    }
});

/* Hide author in JSON Metas
-------------------------- */

add_filter('wpuseo_metas_json_after_settings', function ($metas_json) {
    if (isset($metas_json['author'])) {
        $metas_json['author'] = get_bloginfo('name');
    }
    return $metas_json;
}, 10, 1);

/* ----------------------------------------------------------
  Uploads
---------------------------------------------------------- */

/* Prevent ugly uploads
-------------------------- */

add_filter('wp_handle_upload_prefilter', function ($file) {
    $size = $file['size'] / 1024;
    $blocked_types = array(
        'image/png'
    );

    $limit = 500;
    if (($file['type'] == 'image/png') && ($size > $limit)) {
        $file['error'] = sprintf(__('PNG images should weight less than %s ko ! Shouldn’t this image be converted to JPG?', 'wpuprojectid'), $limit);
    }
    return $file;
});

/* Allow SVG upload for admins
-------------------------- */

add_filter('upload_mimes', function ($mimes) {
    if (current_user_can('remove_users')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

/* ----------------------------------------------------------
  Search only in posts
---------------------------------------------------------- */

add_filter('pre_get_posts', function ($query) {
    if (!function_exists('relevanssi_do_query') && $query->is_search && !is_admin()) {
        $query->set('post_type', array('post'));
    }
    return $query;
});

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
  Disable some plugins not needed in frontend
---------------------------------------------------------- */

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$is_admin = strpos($request_uri, '/wp-admin/');

if (false === $is_admin) {
    add_filter('option_active_plugins', function ($plugins) {
        $disabled_plugins = array(
            'happyfiles-pro/happyfiles-pro.php'
        );
        foreach ($disabled_plugins as $plugin_path) {
            $k = array_search($plugin_path, $plugins);
            if (false !== $k) {
                unset($plugins[$k]);
            }
        }
        return $plugins;
    });
}

/* ----------------------------------------------------------
  Get loops
---------------------------------------------------------- */

function wpuprojectid_get_loop_from_ids($wpq_posts = array(), $classname = 'loop-list', $loopfile = 'loop-post.php', $args = array()) {
    if (!$wpq_posts) {
        return '';
    }
    $html = '<ul class="' . $classname . '">';
    foreach ($wpq_posts as $wpq_post) {
        $html .= '<li>';
        $html .= wpuprojectid_get_loop_item_from_id($wpq_post, $loopfile, $args);
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function wpuprojectid_get_loop_item_from_id($post_obj, $loopfile = 'loop-post.php', $args = array()) {
    global $post;
    if (is_numeric($post_obj)) {
        $post_obj = get_post($post_obj);
    }
    $loop_file = get_stylesheet_directory() . '/tpl/loops/' . $loopfile;
    if (!file_exists($loop_file)) {
        return '<div>Error : ' . $loopfile . ' does not exists</div>';
    }
    $old_post = $post;
    $post = $post_obj;
    ob_start();
    include $loop_file;
    $html = ob_get_clean();
    $post = $old_post;
    return $html;
}
