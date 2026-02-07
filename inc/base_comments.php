<?php

/*
Plugin Name: [wpuprojectname] Comments
Description: Comment settings
*/

/* ----------------------------------------------------------
  Basic anti-spam measures for comments
---------------------------------------------------------- */

add_filter('pre_comment_approved', function ($approved, $commentdata) {
    if ($approved === 'spam' || $commentdata['comment_content'] === '') {
        return $approved;
    }

    $patterns = array(
        '/<a\s[^>]*href\s*=\s*["\']?/i',
        '/\[url=.*?\]/i',
        '/<script\b/i',
        '/<iframe\b/i'
    );

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $commentdata['comment_content'])) {
            return 'spam';
        }
    }

    return $approved;
}, 10, 2);
