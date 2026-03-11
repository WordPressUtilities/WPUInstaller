<?php
include dirname(__FILE__) . '/../WPUTheme/z-protect.php';

/* ----------------------------------------------------------
  Vars
---------------------------------------------------------- */

$nb_results = wputheme_search_get_total_results();
$nb_results_string = __('Sorry, no results were found.', 'wpuprojectid');
if ($nb_results) {
    $s_q = get_search_query();
    $n_b = number_format_i18n($nb_results);
    $nb_results_string = sprintf(__('1 result for "%s"', 'wpuprojectid'), $s_q);
    if ($nb_results > 1) {
        $nb_results_string = sprintf(__('%s results for "%s"', 'wpuprojectid'), $n_b, $s_q);
    }
}

/* ----------------------------------------------------------
  Layout
---------------------------------------------------------- */

get_header();
echo '<div class="main-content">';
echo '<h1>' . $nb_results_string . '</h1>';

if (get_search_query()) {
    $has_results = false;
    $post_types = apply_filters('wputheme_search_post_types', array());
    foreach ($post_types as $post_type_key => $pt_settings) {
        $html = wputheme_search_get_post_type_content($post_type_key, $pt_settings);
        if (!$html) {
            continue;
        }
        $has_results = true;
        echo '<div class="centered-container cc-posts-list section"><div>';
        echo '<h2><span>' . wputheme_search_get_results_string($post_type_key) . '</span></h2>';
        echo $html;
        echo '</div></div>';
    }
    if (!$has_results) {
        echo '<div class="centered-container cc-noresults section">';
        echo '<div class="noresults">';
        echo '<h2 class="noresults__title">' . __('No results available', 'wpuprojectid') . '</h2>';
        echo '</div>';
        echo '</div>';
    }
}
echo '</div>';
get_footer();
