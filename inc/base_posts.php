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
    unregister_taxonomy_for_object_type('category', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
});

/* ----------------------------------------------------------
  Force display post excerpt in admin
---------------------------------------------------------- */

add_filter('enable__wpuux_force_display_excerpt_field', '__return_true', 10, 1);

/* ----------------------------------------------------------
  Missed Schedule
---------------------------------------------------------- */

add_action('init', function () {
    $freq_in_minutes = 5;

    /* If doing cron */
    if (!defined('DOING_CRON') || !DOING_CRON) {
        return;
    }

    /* Only if installed */
    if (!get_option('db_version')) {
        return;
    }

    /* Once every N minutes */
    $transient = get_transient('wpuprojectid_posts_missed_schedule_transient');
    if ($transient) {
        return false;
    }
    set_transient('wpuprojectid_posts_missed_schedule_transient', 1, 60 * $freq_in_minutes);

    /* Select all failed transients */
    global $wpdb;
    $q = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_status=%s AND post_date_gmt > 0 AND post_date_gmt < UTC_TIMESTAMP()", 'future');
    $missed_ids = $wpdb->get_col($q);
    if (empty($missed_ids)) {
        return false;
    }

    /* Publish these posts */
    foreach ($missed_ids as $missed_id) {
        wp_publish_post($missed_id);
    }
});

/* ----------------------------------------------------------
  Oembed fixes
---------------------------------------------------------- */

add_filter('embed_oembed_html', function ($html, $url, $attr, $post_id) {
    /* Wrap and anonymize Youtube */
    if (strpos($html, 'youtube') !== false) {
        $html = str_replace('src="https://www.youtube.com/embed', 'src="https://www.youtube-nocookie.com/embed', $html);
        return '<div class="cssc-content-video-wrapper">' . $html . '</div>';
    }
    return $html;
}, 10, 4);

/* ----------------------------------------------------------
  WYSIWYG fixes
---------------------------------------------------------- */

add_filter('tiny_mce_before_init', function ($settings) {
    $block_formats = array(
        'Paragraph=p',
        'Heading 2=h2',
        'Heading 3=h3',
        'Heading 4=h4'
    );
    $settings['block_formats'] = implode(';', $block_formats);
    return $settings;
});
