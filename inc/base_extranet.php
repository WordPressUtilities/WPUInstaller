<?php
/*
Plugin Name: [wpuprojectname] Extranet
Description: Handle website extranet
*/

/* ----------------------------------------------------------
  Pages
---------------------------------------------------------- */

add_filter('wputh_pages_site', function ($pages_site) {
    /* Uncomment when your pages are configured */
    return $pages_site;
    $pages_site['extranet_register__page_id'] = array(
        'post_title' => 'Register',
        'page_template' => 'extranet-register.php',
        'wpu_post_metas' => array(
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_lostpassword__page_id'] = array(
        'post_title' => 'Lost Password',
        'page_template' => 'extranet-lostpassword.php',
        'wpu_post_metas' => array(
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_dashboard__page_id'] = array(
        'post_title' => 'Dashboard',
        'page_template' => 'extranet-dashboard.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_comments__page_id'] = array(
        'post_title' => 'Mes commentaires',
        'page_template' => 'extranet-comments.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_avis__page_id'] = array(
        'post_title' => 'Mes avis',
        'page_template' => 'extranet-avis.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_wishlist__page_id'] = array(
        'post_title' => 'Ma wishlist',
        'page_template' => 'extranet-wishlist.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    $pages_site['extranet_infos__page_id'] = array(
        'post_title' => 'Mes infos',
        'page_template' => 'extranet-infos.php',
        'wpu_post_metas' => array(
            'is_extranet_page' => 1
        ),
        'disable_items' => array()
    );
    return $pages_site;
});

/* ----------------------------------------------------------
  Templates
---------------------------------------------------------- */

/* Header
-------------------------- */

add_action('wputheme_main_overcontent_inajax', function () {
    if (!is_page()) {
        return;
    }
    $is_extranet_page = get_post_meta(get_the_ID(), 'is_extranet_page', 1) == '1';
    if (!$is_extranet_page) {
        return;
    }

    echo '<div><h1>' . __('Extranet', 'wpuprojectid') . '</h1></div>';
    echo wpu_extranet_get_menu();
    echo '<h2>' . get_the_title() . '</h2>';

}, 999);
