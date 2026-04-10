<?php
get_header();
echo '<h1>' . single_term_title('', false) . '</h1>';
include get_stylesheet_directory() . '/tpl/news/list.php';
get_footer();
