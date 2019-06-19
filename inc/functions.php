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

add_filter('wputheme_social_links', 'wpuproject_wputheme_social_links', 10, 1);
function wpuproject_wputheme_social_links($links) {
    return array(
        'twitter' => 'Twitter',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
    );
}

/* Load header & footer
 -------------------------- */

add_action('wputheme_header_items', 'wpuproject_header');
function wpuproject_header() {
    include get_stylesheet_directory() . '/tpl/header.php';
}

add_action('wp_footer', 'wpuproject_footer');
function wpuproject_footer() {
    include get_stylesheet_directory() . '/tpl/footer.php';
}

/* Parent Theme
 -------------------------- */

add_filter('wputheme_display_languages', 'project_is_multilingual', 1, 1);
add_filter('wputheme_display_breadcrumbs', '__return_false', 1, 1);
add_filter('wputheme_display_header', '__return_false', 1, 1);
add_filter('wputheme_display_mainwrapper', '__return_false', 1, 1);
add_filter('wputheme_display_footer', '__return_false', 1, 1);

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

add_filter('wputh_javascript_files', 'wpuproject_javascript_files', 99, 1);
function wpuproject_javascript_files($js_files) {
    unset($js_files['functions-faq-accordion']);
    unset($js_files['functions-search-form-check']);
    $js_files['events'] = array(
        'uri' => '/assets/js/events.js',
        'footer' => 1
    );
    return $js_files;
}

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

add_filter('theme_page_templates', 'wpuproject_remove_page_templates');
function wpuproject_remove_page_templates($templates) {
    unset($templates['page-templates/page-bigpictures.php']);
    unset($templates['page-templates/page-contact.php']);
    unset($templates['page-templates/page-downloads.php']);
    unset($templates['page-templates/page-faq.php']);
    unset($templates['page-templates/page-gallery.php']);
    unset($templates['page-templates/page-sitemap.php']);
    unset($templates['page-templates/page-webservice.php']);
    unset($templates['page-templates/page-woocommerce.php']);
    return $templates;
}

/* Google Fonts
 -------------------------- */

add_action('wputh_google_fonts', 'wpuproject_googlefonts');
function wpuproject_googlefonts() {
    return array(
        // 'family' => 'Open+Sans',
        // 'subset' => 'latin',
    );
}

/* Thumbnails
 -------------------------- */

add_filter('wpu_thumbnails_sizes', 'wpuproject_set_wpu_thumbnails_sizes');
function wpuproject_set_wpu_thumbnails_sizes($sizes) {
    $sizes['big'] = array(
        'w' => 1280,
        'h' => 1280,
    );
    return $sizes;
}
