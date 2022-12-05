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
        return array('admin-wpuprojectid-styles' => get_stylesheet_directory_uri() . '/assets/css/admin.css?cacheversion=' . WPUTHEME_ASSETS_VERSION);
    }, 10, 1);
}

if (file_exists(get_stylesheet_directory() . '/assets/css/editor.css')) {
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
    /* Force scrollbar */
    echo '<style>html,body{min-height:100.1vh!important}</style>';
}, 1);


/* Preloaded resources
-------------------------- */

add_filter('wp_preload_resources', function ($preload_resources = array()) {

    $theme_path = str_replace(site_url(), '', get_stylesheet_directory_uri());

    /* Preload icon font */
    $icon_path = $theme_path . '/assets/fonts/icons';
    $icon_file = $icon_path . '/icons.woff2';
    $version_file = $icon_path . '/version.txt';
    if (file_exists(ABSPATH . $icon_file) && file_exists(ABSPATH . $version_file)) {
        $preload_resources[] = array(
            'href' => $icon_file . '?' . file_get_contents(ABSPATH . $version_file),
            'as' => 'font',
            'crossorigin' => 'anonymous',
            'type' => 'font/woff2'
        );
    }

    /* Preload main fonts */
    $files = array(
        // '/assets/fonts/galano_grotesque/galano_grotesque_light-webfont.woff2',
        // '/assets/fonts/poppins/poppins-regular-webfont.woff2'
    );
    foreach ($files as $file) {
        if (file_exists(ABSPATH . $theme_path . $file)) {
            $preload_resources[] = array(
                'href' => $theme_path . $file,
                'as' => 'font',
                'crossorigin' => 'anonymous',
                'type' => 'font/woff2'
            );
        }
    }

    return $preload_resources;
}, 10, 1);
