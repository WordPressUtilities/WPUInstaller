<?php
/*
Plugin Name: [wpuprojectname] Page News
Description: Handle page News
*/

/* ----------------------------------------------------------
  Add Page
---------------------------------------------------------- */

add_filter('wputh_pages_site', 'wpuprojectid_pagenews_wputh_set_pages_site');
function wpuprojectid_pagenews_wputh_set_pages_site($pages_site) {
    $pages_site['news__page_id'] = array(
        'post_title' => 'News',
        'page_template' => "page-news.php",
        'disable_items' => array()
    );
    return $pages_site;
}

/* ----------------------------------------------------------
  Add current class to news menu item on single posts
---------------------------------------------------------- */

add_filter('nav_menu_css_class', 'wpuprojectid_pagenews_add_current_page_class', 10, 2);
function wpuprojectid_pagenews_add_current_page_class($classes, $item) {
    if (is_singular('post') && $item->object_id == get_option('news__page_id')) {
        $classes[] = 'current-menu-item';
    }
    return $classes;
}
