<?php

/*
Plugin Name: [wpuprojectname] Home
Description: Handle Home Page
*/

/* ----------------------------------------------------------
  Page
---------------------------------------------------------- */

add_filter('wputh_pages_site', 'wpuprojectid_home_wputh_set_pages_site', 1);
function wpuprojectid_home_wputh_set_pages_site($pages_site) {
    $pages_site['home__page_id'] = array(
        'post_title' => 'Home',
        'post_content' => '<p></p>',
        'disable_deletion' => 1,
        'disable_pll_lang_selector' => 1
    );
    return $pages_site;
}

/* ----------------------------------------------------------
  Override breadcrumbs
---------------------------------------------------------- */

add_filter('wputh_get_breadcrumbs__after_all', function ($breadcrumbs) {
    if (is_array($breadcrumbs) && isset($breadcrumbs['home'])) {
        $breadcrumbs['home']['name'] = __('Home', 'wpuprojectid');
    }
    return $breadcrumbs;
}, 10, 1);
