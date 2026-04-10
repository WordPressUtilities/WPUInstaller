<?php

echo '<div class="centered-container cc-news-list section-m">';
echo '<div class="news-list">';

echo wputh_get_term_switcher(array(
    'view_all_url' => wpuprojectid_pagenews_get_url()
));

$wpq_posts = new WP_Query(wputh_pagenews_get_query());
if ($wpq_posts->have_posts()) {

    echo '<ul class="list-posts">';
    while ($wpq_posts->have_posts()) {
        $wpq_posts->the_post();
        echo '<li>';
        include get_stylesheet_directory() . '/tpl/loops/loop-post.php';
        echo '</li>';
    }
    echo '</ul>';
    echo wputh_paginate(false, false, $wpq_posts);

} else {
    echo '<p>' . __('No news found.', 'wpuprojectid') . '</p>';
}
wp_reset_postdata();

echo '</div>';
echo '</div>';
