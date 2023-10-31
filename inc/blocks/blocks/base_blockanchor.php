<?php
/*
Plugin Name: [wpuprojectname] Anchor
Description: Handle Anchor
*/

/* ----------------------------------------------------------
  Block Anchor
---------------------------------------------------------- */

add_filter('wpuprojectid_blocks', function ($layouts) {
    $layouts['anchor'] = array(
        'key' => 'anchor',
        'label' => 'Anchor',
        'wpuacf_model' => 'anchor'
    );
    return $layouts;
}, 10, 1);
