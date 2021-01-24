<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

/* ----------------------------------------------------------
  Theme options
---------------------------------------------------------- */

/* Lang
 -------------------------- */

add_action('after_setup_theme', 'wpuprojectid_setup');
function wpuprojectid_setup() {
    load_theme_textdomain('wpuproject', get_stylesheet_directory() . '/lang');
}

/* Social networks
 -------------------------- */

add_filter('wputheme_social_links', 'wpuprojectid_wputheme_social_links', 10, 1);
function wpuprojectid_wputheme_social_links($links) {
    return array(
        'twitter' => 'Twitter',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram'
    );
}

/* Load header & footer
 -------------------------- */

add_action('wputheme_header_items', 'wpuprojectid_header');
function wpuprojectid_header() {
    include get_stylesheet_directory() . '/tpl/header.php';
}

add_action('wp_footer', 'wpuprojectid_footer');
function wpuprojectid_footer() {
    include get_stylesheet_directory() . '/tpl/footer.php';
}

/* Parent Theme
 -------------------------- */

add_filter('wputheme_display_languages', 'project_is_multilingual', 1, 1);
add_filter('wputheme_display_breadcrumbs', '__return_false', 1, 1);
add_filter('wputheme_display_header', '__return_false', 1, 1);
add_filter('wputheme_display_mainwrapper', '__return_false', 1, 1);
add_filter('wputheme_display_footer', '__return_false', 1, 1);

/* Sidebars
-------------------------- */

// /* Disable Sidebars */
// add_filter('wputh_has_sidebar', '__return_false', 1, 1);
// add_filter('wputh_default_sidebars', '__return_empty_array', 1, 1);

/* Menus
-------------------------- */

add_filter('wputh_default_menus', function ($menus) {
    return array(
        'main' => __('Main menu', 'wpuproject'),
        'footer' => __('Footer menu', 'wpuproject')
    );
}, 10, 1);

/* Pages
 -------------------------- */

function wputh_set_pages_site($pages_site) {
    $pages_site['mentions__page_id'] = array(
        'constant' => 'MENTIONS__PAGE_ID',
        'post_title' => 'Mentions légales',
        'post_content' => '<p>Contenu des mentions légales</p>'
    );
    return $pages_site;
}

/* Scripts
 -------------------------- */

add_filter('wputh_javascript_files', 'wpuprojectid_javascript_files', 99, 1);
function wpuprojectid_javascript_files($js_files) {
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
}

/* Load common libs */

add_filter('wputh_common_libraries__slickslider', '__return_true', 1, 1);
add_filter('wputh_common_libraries__simplebar', '__return_false', 1, 1);

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

add_filter('theme_page_templates', 'wpuprojectid_remove_page_templates');
function wpuprojectid_remove_page_templates($templates) {
    unset($templates['page-templates/page-bigpictures.php']);
    unset($templates['page-templates/page-contact.php']);
    unset($templates['page-templates/page-downloads.php']);
    unset($templates['page-templates/page-faq.php']);
    unset($templates['page-templates/page-forgottenpassword.php']);
    unset($templates['page-templates/page-gallery.php']);
    unset($templates['page-templates/page-sitemap.php']);
    unset($templates['page-templates/page-webservice.php']);
    unset($templates['page-templates/page-woocommerce.php']);
    return $templates;
}

/* Exclude default widgets
-------------------------- */

add_action('widgets_init', 'wpuprojectid_unregister_default_widgets', 11);
function wpuprojectid_unregister_default_widgets() {
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
}

/* Thumbnails
 -------------------------- */

add_filter('wpu_thumbnails_sizes', 'wpuprojectid_set_wpu_thumbnails_sizes');
function wpuprojectid_set_wpu_thumbnails_sizes($sizes) {
    $sizes['big'] = array('w' => 1280,'h' => 1280);
    return $sizes;
}
