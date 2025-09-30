<?php
/*
Plugin Name: [wpuprojectname] Options
Description: Website common Options
*/

/* ----------------------------------------------------------
  Options
---------------------------------------------------------- */

/* Boxes */
add_filter('wpu_options_boxes', function ($boxes) {
    $boxes['wpuprojectid_header'] = array(
        'name' => '[wpuprojectname] Header'
    );
    return $boxes;
}, 10, 1);

/* Fields */
add_filter('wpu_options_fields', function ($options) {

    /* Details */
    $options['wpuprojectid_phone'] = array(
        'label' => 'Phone',
        'box' => 'virtual_contacts',
        'type' => 'tel'
    );

    /* Header */
    $options['wpuprojectid_header_button'] = array(
        'label' => 'Bouton header',
        'box' => 'wpuprojectid_header',
        'type' => 'wp_link',
        'lang' => true
    );
    $options['wpuprojectid_header_button2'] = array(
        'label' => 'Bouton header 2',
        'box' => 'wpuprojectid_header',
        'type' => 'wp_link',
        'lang' => true
    );
    return $options;
}, 10, 1);
