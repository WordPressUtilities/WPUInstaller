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
    if ($pagenow == 'edit-tags.php' && isset($_GET['taxonomy']) && in_array($_GET['taxonomy'], $tax)) {
        wp_die('Invalid taxonomy');
    }
});

/* ----------------------------------------------------------
  Missed Schedule
---------------------------------------------------------- */

add_action('init', function () {
    $freq_in_minutes = 2;

    /* Once every N minutes */
    $transient = get_transient('wpuprojectid_posts_missed_schedule_transient');
    if ($transient) {
        return false;
    }
    set_transient('wpuprojectid_posts_missed_schedule_transient', 1, 60 * $freq_in_minutes);

    /* Select all failed transients */
    global $wpdb;
    $q = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_status='future' AND post_date_gmt > 0 AND post_date_gmt < UTC_TIMESTAMP()");
    $missed_ids = $wpdb->get_col($q);
    if (empty($missed_ids)) {
        return false;
    }

    /* Publish these posts */
    foreach ($missed_ids as $missed_id) {
        wp_publish_post($missed_id);
    }

});
