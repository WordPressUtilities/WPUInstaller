<?php

/*
Plugin Name: [wpuprojectname] RSS
Description: Config for RSS
*/

/* ----------------------------------------------------------
  Images in RSS
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
