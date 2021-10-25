<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

/* ----------------------------------------------------------
  Theme options
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

/* Parent Theme
 -------------------------- */

add_filter('wputheme_display_languages', 'project_is_multilingual', 1, 1);
add_filter('wputheme_display_breadcrumbs', '__return_false', 1, 1);
add_filter('wputheme_display_header', '__return_false', 1, 1);
add_filter('wputheme_display_mainwrapper', '__return_false', 1, 1);
add_filter('wputheme_display_footer', '__return_false', 1, 1);

/* Settings
-------------------------- */

add_filter('wputheme_usesessions', '__return_false', 1, 1);

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

/* Scripts
 -------------------------- */

add_filter('wputh_javascript_files', function ($js_files) {
    /* Remove some WPUTheme scripts */
    unset($js_files['functions-faq-accordion']);
    unset($js_files['functions-search-form-check']);
    unset($js_files['wputh-maps']);
    unset($js_files['events']);

    /* Load this theme scripts */
    $js_files['events'] = array(
        'uri' => '/assets/js/events.js',
        'footer' => 1
    );
    return $js_files;
}, 99, 1);

/* Load common libs */

add_filter('wputh_common_libraries__slickslider', '__return_true', 1, 1);
add_filter('wputh_common_libraries__simplebar', '__return_false', 1, 1);
add_filter('wputh_common_libraries__juxtapose', '__return_false', 1, 1);
add_filter('wputh_common_libraries__clipboard', '__return_false', 1, 1);
add_filter('wputh_common_libraries__photoswipe', '__return_false', 1, 1);

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

/* Exclude all parent templates
 -------------------------- */

add_filter('theme_page_templates', function ($templates) {
    unset($templates['page-templates/page-bigpictures.php']);
    unset($templates['page-templates/page-contact.php']);
    unset($templates['page-templates/page-downloads.php']);
    unset($templates['page-templates/page-faq.php']);
    unset($templates['page-templates/page-forgottenpassword.php']);
    unset($templates['page-templates/page-gallery.php']);
    unset($templates['page-templates/page-sitemap.php']);
    unset($templates['page-templates/page-webservice.php']);
    unset($templates['page-templates/page-woocommerce.php']);
    unset($templates['page-templates/page-template-flexible.php']);
    return $templates;
});

/* Exclude default widgets
-------------------------- */

add_action('widgets_init', function () {
    remove_action('welcome_panel', 'wp_welcome_panel');
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Meta');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Media');
    unregister_widget('WP_Widget_Media_Audio');
    unregister_widget('WP_Widget_Media_Image');
    unregister_widget('WP_Widget_Media_Video');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Nav_Menu_Widget');
}, 11);

/* Thumbnails
 -------------------------- */

/* All post types */
add_action('after_setup_theme', function () {
    add_image_size('big', 1600, 1600);
});
