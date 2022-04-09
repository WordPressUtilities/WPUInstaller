<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

$post_types = array(
    'post' => array(
        'label' => 'News',
        'tpl' => 'loop-post.php'
    )
);

get_header();
echo '<div class="main-content">';
echo '<h1>' . sprintf(__('Search results for &ldquo;%s&rdquo;', 'wputh'), get_search_query()) . '</h1>';
foreach ($post_types as $post_type_key => $pt_settings) {
    $wpq_search = new WP_Query();
    $wpq_search->parse_query(array(
        'posts_per_page' => -1,
        'post_type' => $post_type_key,
        's' => get_search_query()
    ));
    if (function_exists('relevanssi_do_query')) {
        relevanssi_do_query($wpq_search);
    }
    if ($wpq_search->have_posts()) {
        $has_result = true;
        echo '<div class="centered-container cc-posts-list"><div>';
        echo '<h2><span>' . $pt_settings['label'] . '</span></h2>';
        echo '<ul class="loop-list">';
        while ($wpq_search->have_posts()) {
            $wpq_search->the_post();
            echo '<li>';
            include get_stylesheet_directory() . '/tpl/loops/' . $pt_settings['tpl'];
            echo '</li>';
        }
        echo '</ul>';
        echo '</div></div>';
    }
    wp_reset_postdata();
}
echo '</div>';
get_footer();
