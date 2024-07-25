<?php

/* ----------------------------------------------------------
  Disable jQuery migrate
---------------------------------------------------------- */

add_action('wp_enqueue_scripts', function () {
    global $wp_scripts;
    if (isset($wp_scripts->registered['jquery']) && $wp_scripts->registered['jquery']->deps) {
        $wp_scripts->registered['jquery']->deps = array_diff($wp_scripts->registered['jquery']->deps, array('jquery-migrate'));
    }
});

/* ----------------------------------------------------------
  Base scripts
---------------------------------------------------------- */

add_filter('wputh_javascript_files', function ($js_files) {
    /* Remove some WPUTheme scripts */
    unset($js_files['functions-faq-accordion']);
    unset($js_files['functions-search-form-check']);
    unset($js_files['functions-menu-scroll']);
    unset($js_files['functions-smooth-scroll']);
    unset($js_files['wputh-maps']);
    unset($js_files['events']);

    /* Load this theme scripts */
    $js_files['app'] = array(
        'uri' => '/assets/js/app.js',
        'footer' => 1
    );
    return $js_files;
}, 99, 1);

/* ----------------------------------------------------------
  Load common libs
---------------------------------------------------------- */

add_filter('wputh_common_libraries__swiper', '__return_false', 1, 1);
add_filter('wputh_common_libraries__slickslider', '__return_false', 1, 1);
add_filter('wputh_common_libraries__simplebar', '__return_false', 1, 1);
add_filter('wputh_common_libraries__juxtapose', '__return_false', 1, 1);
add_filter('wputh_common_libraries__clipboard', '__return_false', 1, 1);
add_filter('wputh_common_libraries__photoswipe', '__return_false', 1, 1);
