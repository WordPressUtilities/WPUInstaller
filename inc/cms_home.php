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
        'post_content' => '<p>Contenu non utilisé</p>',
    );
    return $pages_site;
}

/* Force template home.php
 -------------------------- */

add_filter('template_include', 'wpuprojectid_home_template');
function wpuprojectid_home_template($original_template) {
    global $post;
    if (is_object($post) && is_numeric($post->ID) && $post->ID == HOME__PAGE_ID) {
        return get_stylesheet_directory() . '/home.php';
    }
    return $original_template;
}
