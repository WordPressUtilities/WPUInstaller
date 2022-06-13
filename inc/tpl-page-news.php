<?php
/* Template Name: News */
get_header();
the_post();
?>
<div class="centered-container cc-main-content--news">
    <div class="main-content main-content--news">
        <h1><?php the_title();?></h1>
        <div>
            <?php the_content();?>
        </div>
    </div>
</div>
<?php
$wpq_posts = new WP_Query(array(
    'post_type' => 'post',
    'paged' => get_query_var('paged')
));
if ($wpq_posts->have_posts()) {
    echo '<div class="centered-container cc-news-list section-m">';
    echo '<div class="news-list">';
    echo '<ul class="list-posts">';
    while ($wpq_posts->have_posts()) {
        $wpq_posts->the_post();
        echo '<li>';
        include get_stylesheet_directory() . '/tpl/loops/loop-post.php';
        echo '</li>';
    }
    echo '</ul>';
    echo wputh_paginate(false, false, $wpq_posts);
    echo '</div>';
    echo '</div>';
}
wp_reset_postdata();

get_footer();
