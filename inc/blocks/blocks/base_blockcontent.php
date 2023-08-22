<?php
/*
Plugin Name: [wpuprojectname] Content
Description: Handle Content
*/

/* ----------------------------------------------------------
  Block Content
---------------------------------------------------------- */

add_filter('wpuprojectid_blocks', function ($layouts) {
    $layouts['content'] = array(
        'label' => 'WYSIWYG',
        'wpuacf_model' => 'content-classic'
    );
    return $layouts;
}, 10, 1);

/* Add a classname */
/*
add_filter('get_wpu_acf_wrapper_classname', function ($classes, $block_type) {
    if ($block_type == 'content-classic') {
        $classes[] = wpuprojectid_theme(get_sub_field('wpuprojectid_theme'));
    }
    return $classes;
}, 10, 2);
*/

/* Override fields */
/*
add_filter('wpu_acf_flexible__override_model', function ($model, $layout_id) {
    if ($layout_id == 'content') {
        $model['sub_fields']['wpuprojectid_theme'] = 'wpuprojectid_theme';
    }
    return $model;
}, 10, 2);
*/
