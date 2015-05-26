<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

/* ----------------------------------------------------------
  Theme options
---------------------------------------------------------- */

/* Lang
 -------------------------- */

add_action('after_setup_theme', 'wpuproject_setup');
function wpuproject_setup() {
    load_theme_textdomain('wpuproject', get_stylesheet_directory() . '/inc/lang');
}

/* Social networks
 -------------------------- */

$wpu_social_links = array(
    'twitter' => 'Twitter',
    'facebook' => 'Facebook',
    'instagram' => 'Instagram',
);

/* Load header & footer
 -------------------------- */

add_action('wputheme_header_items', 'wputhchild_header');
function wputhchild_header() {
    include get_stylesheet_directory() . '/tpl/header.php';
}

add_action('wp_footer', 'wputhchild_footer');
function wputhchild_footer() {
    include get_stylesheet_directory() . '/tpl/footer.php';
}

/* Parent Theme
 -------------------------- */

add_filter('wputheme_display_breadcrumbs', 'wpusubtheme_show_item', 1, 1);
add_filter('wputheme_display_header', 'wpusubtheme_show_item', 1, 1);
add_filter('wputheme_display_mainwrapper', 'wpusubtheme_show_item', 1, 1);
add_filter('wputheme_display_footer', 'wpusubtheme_show_item', 1, 1);
function wpusubtheme_show_item() {
    return false;
}

/* Post types
 -------------------------- */

function wputh_set_theme_posttypes($post_types) {
    // $post_types['work'] = array(
    //     'menu_icon' => 'dashicons-portfolio',
    //     'name' => __('Work', 'wputh') ,
    //     'plural' => __('Works', 'wputh') ,
    //     'female' => 0
    // );
    return $post_types;
}

/* Taxonomies
 -------------------------- */

function wputh_set_theme_taxonomies($taxonomies) {
    // $taxonomies['work-type'] = array(
    //     'name' => __( 'Work type', 'wputh' ),
    //     'post_type' => 'work'
    // );
    return $taxonomies;
}

/* Pages
 -------------------------- */

function wputh_set_pages_site($pages_site) {
    $pages_site['about__page_id'] = array(
        'constant' => 'ABOUT__PAGE_ID',
        'post_title' => 'A Propos',
        'post_content' => '<p>Contenu à propos.</p>',
    );
    $pages_site['mentions__page_id'] = array(
        'constant' => 'MENTIONS__PAGE_ID',
        'post_title' => 'Mentions légales',
        'post_content' => '<p>Contenu des mentions légales</p>',
    );
    return $pages_site;
}

/* Scripts
 -------------------------- */

$WPUJavaScripts = array(
    'jquery' => array(),
    'events' => array(
        'uri' => '/assets/js/events.js',
        'footer' => 1
    ) ,
);

/* Styles
 -------------------------- */

define('PROJECT_CSS_DIR', get_stylesheet_directory() . '/assets/css/');
define('PROJECT_CSS_URL', get_stylesheet_directory_uri() . '/assets/css/');

function wputh_control_stylesheets() {
    wp_dequeue_style('wputhmain');

    // Add child theme
    $css_files = parse_path(PROJECT_CSS_DIR);
    foreach ($css_files as $file) {
        wpu_add_css_file($file, PROJECT_CSS_DIR, PROJECT_CSS_URL);
    }
}

/* Exclude all parent templates
 -------------------------- */

add_filter('theme_page_templates', 'wputhchild_remove_page_templates');
function wputhchild_remove_page_templates($templates) {
    unset($templates['page-templates/page-bigpictures.php']);
    unset($templates['page-templates/page-contact.php']);
    unset($templates['page-templates/page-downloads.php']);
    unset($templates['page-templates/page-faq.php']);
    unset($templates['page-templates/page-gallery.php']);
    unset($templates['page-templates/page-sitemap.php']);
    unset($templates['page-templates/page-webservice.php']);
    return $templates;
}

/* Google Fonts
 -------------------------- */

add_action('wputh_google_fonts', 'wputhchild_googlefonts');
function wputhchild_googlefonts() {
    return array(
        // 'family' => 'Open+Sans',
        // 'subset' => 'latin',
    );
}

/* Thumbnails
 -------------------------- */

add_filter('wpu_thumbnails_sizes', 'wputhchild_set_wpu_thumbnails_sizes');
function wputhchild_set_wpu_thumbnails_sizes($sizes) {

    // $sizes['maxithumb'] = array(
    //     'w' => 200,
    //     'h' => 200,
    //     'crop' => true,
    //     'name' => 'Maxithumb',
    //     'post_type' => array(
    //         'page'
    //     )
    //     'display_gallery_insert' => true,
    // );
    return $sizes;
}
