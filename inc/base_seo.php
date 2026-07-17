<?php
/*
Plugin Name: [wpuprojectname] SEO
Description: Website SEO fixes
*/

/* ----------------------------------------------------------
  WPUSEO - Disable Cookie Tracking
---------------------------------------------------------- */

// add_filter('wpuseo__cookie_notice_tracking_feature_flag', '__return_false', 10, 1);

/* ----------------------------------------------------------
  WPUSEO - Force metas
---------------------------------------------------------- */

/* Force open graph */
add_filter('option_wpu_seo_user_facebook_enable', function () {
    return '1';
}, 10, 1);

/* ----------------------------------------------------------
  Breadcrumbs
---------------------------------------------------------- */

add_filter('wputh_get_breadcrumbs_html__default_args', function ($args) {
    //$args['link_on_last_item'] = false;
    //$args['wrapper'] = true;
    return $args;
}, 10, 1);
