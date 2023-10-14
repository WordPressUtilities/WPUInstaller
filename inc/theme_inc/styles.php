<?php

/* ----------------------------------------------------------
  Vars
---------------------------------------------------------- */

define('MAIN_THEME_COLOR', '#336699');
define('MAIN_THEME_BACKGROUND_COLOR', '#FFFFFF');

/* Size
-------------------------- */

$content_width = 680;

/* Main color
-------------------------- */

add_action('wp_head', function () {
    echo '<style>:root{--base-theme-color:' . MAIN_THEME_COLOR . '}</style>';
    echo '<meta name="theme-color" content="' . MAIN_THEME_BACKGROUND_COLOR . '" />';
    echo '<meta name="msapplication-navbutton-color" content="' . MAIN_THEME_BACKGROUND_COLOR . '">';
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="' . MAIN_THEME_BACKGROUND_COLOR . '">';
});

/* ----------------------------------------------------------
  Styles
---------------------------------------------------------- */

function wputh_control_stylesheets() {
    wp_dequeue_style('wputhmain');
    wp_enqueue_style('wpuprojectid-styles', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), WPUTHEME_ASSETS_VERSION, false);
}

if (is_readable(get_stylesheet_directory() . '/assets/css/admin.css')) {
    add_filter('wpu_acf_flexible__disable_front_css', '__return_true', 10, 1);
    add_filter('wpu_acf_flexible__admin_css', function ($content) {
        return array('admin-wpuprojectid-styles' => get_stylesheet_directory_uri() . '/assets/css/admin.css?cacheversion=' . WPUTHEME_ASSETS_VERSION);
    }, 10, 1);
}

if (is_readable(get_stylesheet_directory() . '/assets/css/editor.css')) {
    function wputh_add_editor_styles() {
        add_editor_style(get_stylesheet_directory_uri() . '/assets/css/editor.css?cacheversion=' . WPUTHEME_ASSETS_VERSION);
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

/* ----------------------------------------------------------
  Performances
---------------------------------------------------------- */

/* Required style
-------------------------- */

add_action('wp_head', function () {
    echo '<style>html,body{';
    /* Force background color */
    echo 'background-color:' . MAIN_THEME_BACKGROUND_COLOR . ';';
    /* Force scrollbar */
    echo 'min-height:100.1vh!important;';
    echo '}</style>';
}, 1);

/* Preloaded resources
-------------------------- */

add_filter('wputheme_preload_fonts', function ($fonts = array()) {

    /* Preload icon font */
    $fonts[] = wputheme_preload_font('/assets/fonts/icons/icons.woff2', '/assets/fonts/icons/version.txt');

    /* Preload main fonts */
    $files = array(
        // '/assets/fonts/galano_grotesque/galano_grotesque_light-webfont.woff2',
        // '/assets/fonts/poppins/poppins-regular-webfont.woff2'
    );
    foreach ($files as $file) {
        $fonts[] = wputheme_preload_font($file);
    }

    return $fonts;

}, 10, 1);
