<?php
/*
Plugin Name: [wpuprojectname] Image
Description: Handle Image
*/

/* ----------------------------------------------------------
  Block Image
---------------------------------------------------------- */

add_filter('wpuprojectid_blocks', function ($layouts) {
    $layouts['image'] = array(
        'key' => 'image',
        'label' => 'Image',
        'wpuacf_model' => 'image'
    );
    return $layouts;
}, 10, 1);
