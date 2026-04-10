<?php
/* Template Name: News */
get_header();
the_post();
get_header();
echo '<h1>' . __('News', 'wputh') . '</h1>';
include get_stylesheet_directory() . '/tpl/news/list.php';
get_footer();
