<?php

/*
Plugin Name: [wpuprojectname] RSS
Description: Config for RSS
*/

/* ----------------------------------------------------------
  RSS : Images
---------------------------------------------------------- */

// display featured post thumbnails in WordPress feeds
// Thanks to https://github.com/kasparsd/feed-image-enclosure/blob/master/feed-image-enclosure.php
add_action('rss2_item', function () {
    if (!has_post_thumbnail()) {
        return;
    }

    $thumbnail_id = get_post_thumbnail_id(get_the_ID());
    $thumbnail = image_get_intermediate_size($thumbnail_id, 'medium');

    if (empty($thumbnail)) {
        return;
    }

    $filepath = get_attached_file($thumbnail_id);
    $filesize = 0;
    if (file_exists($filepath)) {
        $filesize = filesize($filepath);
    }

    printf(
        '<enclosure url="%s" length="%s" type="%s" />',
        $thumbnail['url'],
        $filesize,
        get_post_mime_type($thumbnail_id)
    );
});

/* ----------------------------------------------------------
  Display thumb before content
---------------------------------------------------------- */

add_filter('the_content_feed', function ($content) {
    $thumb = (get_post_type() == 'post') ? wpautop(get_the_post_thumbnail(), 'large') : '';
    return $thumb . $content;
}, 10, 1);

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
  RSS : Author
---------------------------------------------------------- */

add_filter('the_author', function ($display_name) {
    if (is_feed()) {
        return get_bloginfo('name');
    }
    return $display_name;
}, 40);

/* ----------------------------------------------------------
  RSS : Copyright
---------------------------------------------------------- */

add_filter('disable__wpuux_add_copyright_feed', '__return_true', 10, 1);
