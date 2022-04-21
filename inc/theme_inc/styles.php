<?php

/* ----------------------------------------------------------
  Vars
---------------------------------------------------------- */

/* Size
-------------------------- */

$content_width = 680;

/* Main color
-------------------------- */

add_action('wp_head', function () {
    $theme_color = '#336699';
    echo '<style>:root{--base-theme-color:' . $theme_color . '}</style>';
    echo '<meta name="theme-color" content="' . $theme_color . '" />';
    echo '<meta name="msapplication-navbutton-color" content="' . $theme_color . '">';
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="' . $theme_color . '">';
});

/* ----------------------------------------------------------
  Styles
---------------------------------------------------------- */

function wputh_control_stylesheets() {
    wp_dequeue_style('wputhmain');
    wp_enqueue_style('wpuprojectid-styles', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), WPUTHEME_ASSETS_VERSION, false);
}

if (file_exists(get_stylesheet_directory() . '/assets/css/admin.css')) {
    add_filter('wpu_acf_flexible__disable_front_css', '__return_true', 10, 1);
    add_filter('wpu_acf_flexible__admin_css', function ($content) {
        return array('admin-wpuprojectid-styles' => get_stylesheet_directory_uri() . '/assets/css/admin.css');
    }, 10, 1);
}

if (file_exists(get_stylesheet_directory() . '/assets/css/editor.css')) {
    function wputh_add_editor_styles() {
        add_editor_style(get_stylesheet_directory_uri() . '/assets/css/editor.css');
    }
}

/* ----------------------------------------------------------
  Reset WooCommerce
---------------------------------------------------------- */

add_filter('woocommerce_enqueue_styles', function ($enqueue_styles) {
    unset($enqueue_styles['woocommerce-general']);
    unset($enqueue_styles['woocommerce-smallscreen']);
    return $enqueue_styles;
}, 99);

add_action('enqueue_block_assets', function () {
    wp_deregister_style('wc-blocks-style');
    wp_dequeue_style('wc-blocks-style');
});
