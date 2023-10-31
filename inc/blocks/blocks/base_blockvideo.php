<?php
/*
Plugin Name: [wpuprojectname] Video
Description: Handle Video
*/

/* ----------------------------------------------------------
  Block Video
---------------------------------------------------------- */

add_filter('wpuprojectid_blocks', function ($layouts) {
    $layouts['video'] = array(
        'key' => 'video',
        'label' => 'Video',
        'wpuacf_model' => 'video'
    );
    return $layouts;
}, 10, 1);
