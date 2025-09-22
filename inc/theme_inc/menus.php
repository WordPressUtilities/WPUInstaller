<?php

/* ----------------------------------------------------------
  Menus
---------------------------------------------------------- */

/* Menu IDs
-------------------------- */

add_filter('wputh_default_menus', function ($menus) {
    return array(
        'main' => 'Main menu',
        'footer' => 'Footer menu',
        'copy' => 'Copyright menu'
    );
}, 10, 1);

/* Max depth
-------------------------- */

add_filter('wputh_default_menus_depth', function ($menus_depth) {
    return array(
        'footer' => 1,
        'main' => 1,
        'copy' => 0
    );
}, 10, 1);

/* Max length
-------------------------- */

add_filter('wputh_default_menus_length', function ($menus_length) {
    return array(
        'footer' => 3,
        'main' => 7,
        'copy' => 99
    );
}, 10, 1);
