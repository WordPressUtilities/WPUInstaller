<?php
/*
Plugin Name: [wpuprojectname] Search
Description: Search Settings
*/

/* ----------------------------------------------------------
  Disable UX Tweak if installed
---------------------------------------------------------- */

add_filter('disable__wpuux_redirect_only_result_search', '__return_true');

/* ----------------------------------------------------------
  Enable search
---------------------------------------------------------- */

add_filter('wputheme_search__enabled', '__return_true');
add_filter('wputheme_search_load_more_classname', function ($classname) {
    return 'wpuprojectid-button';
}, 10, 1);
add_filter('wputheme_search_load_more_label', function ($text) {
    return __('Load more', 'wpuprojectid');
}, 10, 1);

/* ----------------------------------------------------------
  Search post types
---------------------------------------------------------- */

add_filter('wpuprojectid_search_post_types', function ($types = array()) {
    $types['post'] = array(
        'label' => __('News', 'wpuprojectid'),
        'tpl' => 'loop-post.php'
    );
    return $types;
}, 10, 1);
