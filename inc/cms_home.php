<?php

/*
Plugin Name: [wpuproject] Home
Description: Handle Home Page
*/

/* ----------------------------------------------------------
  Page
---------------------------------------------------------- */

add_filter('wputh_pages_site', 'wpuproject_home_wputh_set_pages_site', 1);
function wpuproject_home_wputh_set_pages_site($pages_site) {
    $pages_site['home__page_id'] = array(
        'post_title' => 'Home',
        'post_content' => '<p>Contenu non utilisé</p>',
    );
    return $pages_site;
}

/* Force template home.php
 -------------------------- */

add_filter('template_include', 'wpuproject_home_template');
function wpuproject_home_template($original_template) {
    global $post;
    if (is_object($post) && is_numeric($post->ID) && $post->ID == HOME__PAGE_ID) {
        return get_stylesheet_directory() . '/home.php';
    }
    return $original_template;
}
