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
