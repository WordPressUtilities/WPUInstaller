<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';
include dirname(__FILE__) . '/inc/helpers.php';
include dirname(__FILE__) . '/inc/parent-theme.php';
include dirname(__FILE__) . '/inc/scripts.php';
include dirname(__FILE__) . '/inc/social.php';
include dirname(__FILE__) . '/inc/styles.php';

/* ----------------------------------------------------------
  Theme options
---------------------------------------------------------- */

/* Supported features
-------------------------- */

function wputh_custom_theme_setup() {
/*
    // WooCommerce
    add_theme_support('woocommerce');

    // Theme style
    add_theme_support('custom-background');
    add_theme_support('custom-header');

    // Supporting RSS Links
    add_theme_support('automatic-feed-links');
*/

    // Supporting Custom Logo
    add_theme_support('custom-logo');

    // Supporting HTML5
    add_theme_support('html5', array(
        'comment-list',
        'comment-form',
        'search-form',
        'gallery',
        'caption',
        'style',
        'script'
    ));

    // Supporting thumbnails
    add_theme_support('post-thumbnails');

    // Supporting Title
    add_theme_support('title-tag');
}

/* Lang
 -------------------------- */

add_action('after_setup_theme', function () {
    load_theme_textdomain('wpuprojectid', get_stylesheet_directory() . '/lang');
});

/* Load header & footer
 -------------------------- */

add_action('wputheme_header_items', function () {
    include get_stylesheet_directory() . '/tpl/header.php';
});

add_action('wp_footer', function () {
    include get_stylesheet_directory() . '/tpl/footer.php';
});

/* Sidebars
-------------------------- */

// /* Disable Sidebars */
add_filter('wputh_has_sidebar', '__return_false', 1, 1);
add_filter('wputh_default_sidebars', '__return_empty_array', 1, 1);

/* Menus
-------------------------- */

add_filter('wputh_default_menus', function ($menus) {
    return array(
        'main' => 'Main menu',
        'footer' => 'Footer menu'
    );
}, 10, 1);

/* Pages
 -------------------------- */

function wputh_set_pages_site($pages_site) {
    /*
    $pages_site['mentions__page_id'] = array(
        'constant' => 'MENTIONS__PAGE_ID',
        'post_title' => 'Mentions légales',
        'post_content' => '<p>Contenu des mentions légales</p>'
    );
    */
    return $pages_site;
}

/* Thumbnails
 -------------------------- */

/* All post types */
add_action('after_setup_theme', function () {
    set_post_thumbnail_size(500, 9999);
    add_image_size('big', 1600, 1600);
});
