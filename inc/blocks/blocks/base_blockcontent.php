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
        $classes[] = 'centered-container--thin';
        $classes[] = 'section-m--large';
    }
    return $classes;
}, 10, 2);
*/
