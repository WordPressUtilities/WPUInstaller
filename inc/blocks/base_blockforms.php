<?php
/*
Plugin Name: [wpuprojectname] Forms
Description: Handle Forms
*/

/* ----------------------------------------------------------
  Block Forms
---------------------------------------------------------- */

add_filter('wpuprojectid_blocks', function ($layouts) {
    if (function_exists('wpuprojectid_forms_get_form_acf_layout')) {
        $layouts['forms'] = wpuprojectid_forms_get_form_acf_layout();
    }
    return $layouts;
}, 10, 1);
