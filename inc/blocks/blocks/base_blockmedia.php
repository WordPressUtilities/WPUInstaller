<?php
/*
Plugin Name: [wpuprojectname] Media
Description: Handle Media
*/

/* ----------------------------------------------------------
  Block Media
---------------------------------------------------------- */

add_filter('wpuprojectid_blocks', function ($layouts) {
    $layouts['media'] = array(
        'key' => 'media',
        'label' => 'Media',
        'wpuacf_model' => 'media'
    );
    return $layouts;
}, 10, 1);

/* Add a classname */
/*
add_filter('get_wpu_acf_wrapper_classname', function ($classes, $block_type) {
    if ($block_type == 'media') {
        $classes[] = wpuprojectid_theme(get_sub_field('wpuprojectid_theme'));
    }
    return $classes;
}, 10, 2);
*/

/* Override fields */
/*
add_filter('wpu_acf_flexible__override_model__media', function ($model) {
    $model['sub_fields']['wpuprojectid_theme'] = 'wpuprojectid_theme';
    return $model;
}, 10, 1);
*/
