<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';
include dirname(__FILE__) . '/inc/parent-theme.php';
include dirname(__FILE__) . '/inc/scripts.php';
include dirname(__FILE__) . '/inc/helpers.php';

/* ----------------------------------------------------------
  Theme options
---------------------------------------------------------- */

/* Supported features
-------------------------- */

/*
function wputh_custom_theme_setup() {
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

    // Supporting RSS Links
    add_theme_support('automatic-feed-links');

    // Supporting Title
    add_theme_support('title-tag');
}
*/

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

/* Lang
 -------------------------- */

add_action('after_setup_theme', function () {
    load_theme_textdomain('wpuprojectid', get_stylesheet_directory() . '/lang');
});

/* Social networks
 -------------------------- */

add_filter('wputheme_social_links', function ($links) {
    return array(
        'discord' => 'Discord',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'Linkedin',
        'tiktok' => 'Tiktok',
        'twitch' => 'Twitch',
        'twitter' => 'Twitter',
        'youtube' => 'Youtube'
    );
}, 10, 1);

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

/* Styles
 -------------------------- */

function wputh_control_stylesheets() {
    wp_dequeue_style('wputhmain');
    wp_enqueue_style('wpuprojectid-styles', get_stylesheet_directory_uri() . '/assets/css/main.css', array(), WPUTHEME_ASSETS_VERSION, false);
}

if (file_exists(get_stylesheet_directory() . '/assets/css/admin.css')) {
    add_filter('wpu_acf_flexible__disable_front_css', '__return_true', 10, 1);
    add_filter('wpu_acf_flexible__admin_css', function ($content) {
        return array('admin-styles' => get_stylesheet_directory_uri() . '/assets/css/admin.css');
    }, 10, 1);
}

/* Thumbnails
 -------------------------- */

/* All post types */
add_action('after_setup_theme', function () {
    add_image_size('big', 1600, 1600);
});
