<?php
/*
Plugin Name: [wpuproject] Search
Description: Search Settings
*/

/* ----------------------------------------------------------
  Index extra fields
---------------------------------------------------------- */

add_filter('relevanssi_index_custom_fields', 'wpuprojectid_search_custom_fields');
function wpuprojectid_search_custom_fields($custom_fields = array()) {

    /* Add fields */
    $custom_fields[] = 'wpu_post_content';

    return $custom_fields;
}
