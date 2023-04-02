<?php
/*
Plugin Name: [wpuprojectname] Perfs
Description: Website perfs fixes
*/

/* ----------------------------------------------------------
  Disable some plugins scripts & styles
---------------------------------------------------------- */

/* Safe SVG
-------------------------- */

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_script('safe-svg-block-script');
    wp_dequeue_style('safe-svg-block-frontend');
}, 999);

/* ----------------------------------------------------------
  Cache oembeds
---------------------------------------------------------- */

/* Actions */
add_filter('pre_oembed_result', function ($content = '', $url = '', $args = array()) {
    $url_hash = 'wpuprojectid_oembed_' . md5($url . json_encode($args));

    // Return result if available
    $cached_content = wpufilecache_get($url_hash);
    if (!empty($cached_content) && !is_null($cached_content)) {
        return $cached_content;
    }

    return $content;
}, 10, 3);

add_filter('oembed_result', function ($content = '', $url = '', $args = array()) {
    $url_hash = 'wpuprojectid_oembed_' . md5($url . json_encode($args));

    // Create cache
    wpufilecache_set($url_hash, $content);

    // Return content
    return $content;
}, 10, 3);
