<?php

/*
Plugin Name: [wpuprojectname] Home
Description: Handle Home Page
*/

/* ----------------------------------------------------------
  Page
---------------------------------------------------------- */

add_filter('wputh_pages_site', 'wpuprojectid_home_wputh_set_pages_site', 1);
function wpuprojectid_home_wputh_set_pages_site($pages_site) {
    $pages_site['home__page_id'] = array(
        'post_title' => 'Home',
        'post_content' => '<p></p>',
    );
    return $pages_site;
}
