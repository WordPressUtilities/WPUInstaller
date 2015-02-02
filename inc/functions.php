<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

/* ----------------------------------------------------------
  Theme options
---------------------------------------------------------- */

/* Parent Theme
 -------------------------- */

add_filter('wputheme_display_header', 'wpusubtheme_show_item', 1, 1);
add_filter('wputheme_display_mainwrapper', 'wpusubtheme_show_item', 1, 1);
add_filter('wputheme_display_footer', 'wpusubtheme_show_item', 1, 1);
function wpusubtheme_show_item() {
    return false;
}

/* Post types
 -------------------------- */

function wputh_set_theme_posttypes($post_types) {
    $post_types = array(
        // 'projects' => array(
        //     'menu_icon' => 'dashicons-portfolio',
        //     'name' => __('Project', 'wputh') ,
        //     'plural' => __('Projects', 'wputh') ,
        //     'female' => 0
        // ) ,
    );
    return $post_types;
}

/* Taxonomies
 -------------------------- */

function wputh_set_theme_taxonomies($taxonomies) {
    $taxonomies = array(
        // 'projects-director' => array(
        //     'name' => __('Directors', 'wputh') ,
        //     'post_type' => 'projects'
        // )
    );
    return $taxonomies;
}

/* Pages
 -------------------------- */

function wputh_set_pages_site($pages_site) {
    $pages_site = array(
        'about__page_id' => array(
            'constant' => 'ABOUT__PAGE_ID',
            'post_title' => 'A Propos',
            'post_content' => '<p>Contenu à propos.</p>',
        ) ,
        'mentions__page_id' => array(
            'constant' => 'MENTIONS__PAGE_ID',
            'post_title' => 'Mentions légales',
            'post_content' => '<p>Contenu des mentions légales</p>',
        ) ,
    );
    return $pages_site;
}

/* Scripts
 -------------------------- */

$WPUJavaScripts = array(
    'jquery' => array()
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
        'family' => 'Open+Sans',
        // 'subset' => 'latin',
    );
}

/* Thumbnails
 -------------------------- */

if (function_exists('add_image_size')) {
    add_image_size('maxithumb', 200, 200, 1);
}