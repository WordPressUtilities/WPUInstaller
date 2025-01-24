<?php

/* ----------------------------------------------------------
  Share links
---------------------------------------------------------- */

add_filter('wputheme_share_methods', function ($methods) {
    $share_methods = array(
        'email',
        'clipboard',
        'whatsapp',
        'facebook',
        'sharesheet'
    );

    $new_methods = array();
    foreach ($share_methods as $method) {
        if (isset($methods[$method])) {
            $new_methods[$method] = $methods[$method];
        }
    }

    return $new_methods;
}, 10, 1);

/* ----------------------------------------------------------
  Social networks
---------------------------------------------------------- */

add_filter('wputheme_social_links', function ($links) {
    return array(
        'discord' => 'Discord',
        'bluesky' => 'Bluesky',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin' => 'Linkedin',
        'mastodon' => 'Mastodon',
        'tiktok' => 'Tiktok',
        'twitch' => 'Twitch',
        'twitter' => 'Twitter',
        'youtube' => 'Youtube'
    );
}, 10, 1);
