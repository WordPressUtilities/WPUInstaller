<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';
$post_types = apply_filters('wpuprojectid_search_post_types', array());
get_header();
echo '<div class="main-content">';
echo '<h1>' . sprintf(__('Search results for &ldquo;%s&rdquo;', 'wputh'), get_search_query()) . '</h1>';
foreach ($post_types as $post_type_key => $pt_settings) {
    $html = wpuprojectid_search_get_post_type_content($post_type_key, $pt_settings);
    if (!$html) {
        continue;
    }
    echo '<div class="centered-container cc-posts-list"><div>';
    echo '<h2><span>' . $pt_settings['label'] . '</span></h2>';
    echo $html;
    echo '</div></div>';
}
echo '</div>';
get_footer();
