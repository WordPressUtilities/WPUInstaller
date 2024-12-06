<?php

/* ----------------------------------------------------------
  Share links
---------------------------------------------------------- */

add_filter('wputheme_share_methods', function ($methods) {

    /* Comment to edit methods  */
    return $methods;

    if (isset($methods['email'])) {
        unset($methods['email']);
    }
    if (isset($methods['googleplus'])) {
        unset($methods['googleplus']);
    }
    if (isset($methods['pinterest'])) {
        unset($methods['pinterest']);
    }
    if (isset($methods['whatsapp'])) {
        unset($methods['whatsapp']);
    }
    /* Linkedin is set as first method */
    if (isset($methods['linkedin'])) {
        $_method_linkedin = $methods['linkedin'];
        $methods = array_merge(array('linkedin' => $_method_linkedin), $methods);
    }
    return $methods;
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
