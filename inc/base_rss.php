<?php

/*
Plugin Name: [wpuprojectname] RSS
Description: Config for RSS
*/

/* ----------------------------------------------------------
  Features
---------------------------------------------------------- */

add_filter('wpu_better_rss__display_thumbnail_before_content__enable', '__return_true', 10, 1);
add_filter('wpu_better_rss__add_tracking_to_links__enable', '__return_true', 10, 1);
add_filter('wpu_better_rss__add_enclosure_field__enable', '__return_true', 10, 1);
add_filter('wpu_better_rss__force_sitename_as_author__enable', '__return_true', 10, 1);
add_filter('wpu_better_rss__add_copyright_in_feed__enable', '__return_true', 10, 1);

/* ----------------------------------------------------------
  RSS : Post types
---------------------------------------------------------- */

add_filter('request', function ($q) {
    if (isset($q['feed'])) {
        $q['post_type'] = array('post');
    }
    return $q;
});

/* ----------------------------------------------------------
  RSS : Copyright
---------------------------------------------------------- */

add_filter('disable__wpuux_add_copyright_feed', '__return_true', 10, 1);
