<?php

/* ----------------------------------------------------------
  Features
---------------------------------------------------------- */

add_filter('wputheme_display_languages', 'project_is_multilingual', 1, 1);
add_filter('wputheme_display_breadcrumbs', '__return_false', 1, 1);
add_filter('wputheme_display_header', '__return_false', 1, 1);
add_filter('wputheme_display_mainwrapper', '__return_false', 1, 1);
add_filter('wputheme_display_footer', '__return_false', 1, 1);

/* ----------------------------------------------------------
  Settings
---------------------------------------------------------- */

add_filter('wputheme_usesessions', '__return_false', 1, 1);

/* ----------------------------------------------------------
  Parent templates
---------------------------------------------------------- */

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

/* ----------------------------------------------------------
  Default widgets
---------------------------------------------------------- */

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
