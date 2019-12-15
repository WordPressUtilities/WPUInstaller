<?php

global $post;
if ($post && $post->post_parent) {
    wp_redirect(get_permalink($post->post_parent));
}
wp_redirect(home_url());
