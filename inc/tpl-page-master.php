<?php
get_header();
the_post();

if (is_singular('mycustomposttype')) {
    include get_stylesheet_directory() . '/tpl/mycustomposttype/master-header.php';
} else {
    include get_stylesheet_directory() . '/tpl/blocks/master-header.php';
}

echo '<div class="main-content">';
echo get_wpu_acf_flexible_content('content-blocks');
echo '</div>';

get_footer();
