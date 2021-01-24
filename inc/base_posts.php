<?php
/*
Plugin Name: [wpuprojectname] Posts
Description: Post settings
*/

/* ----------------------------------------------------------
  Disable default taxonomies
---------------------------------------------------------- */

add_action('init', function () {
    return;
    global $pagenow;
    $tax = array('post_tag', 'category');
    foreach ($tax as $t) {
        register_taxonomy($t, array());
    }
    if ($pagenow == 'edit-tags.php' && in_array($_GET['taxonomy'], $tax)) {
        wp_die('Invalid taxonomy');
    }
});
