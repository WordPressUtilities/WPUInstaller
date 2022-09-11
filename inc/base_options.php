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
        'type' => 'wp_link'
    );
    $options['wpuprojectid_header_link'] = array(
        'label' => 'Lien header',
        'box' => 'wpuprojectid_header',
        'type' => 'wp_link'
    );
    return $options;
}, 10, 1);
