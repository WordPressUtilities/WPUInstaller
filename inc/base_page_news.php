<?php
/*
Plugin Name: [wpuprojectname] Page News
Description: Handle page News
*/

/* ----------------------------------------------------------
  Add Page
---------------------------------------------------------- */

add_filter('wputh_pages_site', 'wpuprojectid_pagenews_wputh_set_pages_site');
function wpuprojectid_pagenews_wputh_set_pages_site($pages_site) {
    $pages_site['news__page_id'] = array(
        'post_title' => 'News',
        'page_template' => "page-news.php",
        'disable_items' => array()
    );
    return $pages_site;
}

/* ----------------------------------------------------------
  Add current class to news menu item on single posts & categories
---------------------------------------------------------- */

add_filter('nav_menu_css_class', function ($classes, $item) {
    if ($item->object_id == wpuprojectid_pagenews_get_id() && (is_singular('post') || is_category())) {
        $classes[] = 'current-menu-item';
    }
    return $classes;
}, 10, 2);

/* ----------------------------------------------------------
  Get page news
---------------------------------------------------------- */

function wpuprojectid_pagenews_get_id() {
    $news_page_id = get_option('news__page_id');
    if (function_exists('pll_get_post')) {
        $news_page_id = pll_get_post($news_page_id);
    }
    return $news_page_id;
}

function wpuprojectid_pagenews_get_url() {
    $news_page_id = wpuprojectid_pagenews_get_id();
    if (!$news_page_id) {
        return home_url();
    }
    return get_permalink($news_page_id);
}

/* ----------------------------------------------------------
  Simple filters
---------------------------------------------------------- */

function wpuprojectid_pagenews_get_filters() {
    $filter_html = '';

    /* Link to news page */
    if (is_category()) {
        $filter_html .= '<a class="wpuprojectid-button wpuprojectid-button--small wpuprojectid-button--secondary" href="' . get_permalink(wpuprojectid_pagenews_get_id()) . '"><span>' . __('All', 'wpuprojectid') . '</span></a>';
    }

    /* Link to each category */
    $categories = get_categories();
    foreach ($categories as $category) {
        $filter_html .= '<a class="wpuprojectid-button wpuprojectid-button--small ' . (is_category($category->term_id) ? '' : 'wpuprojectid-button--secondary') . '" href="' . get_category_link($category->term_id) . '"><span>' . $category->name . '</span></a>';
    }

    return $filter_html;
}

/* ----------------------------------------------------------
  Redirect Year/Month/Day archives to news page
---------------------------------------------------------- */

add_action('template_redirect', function () {
    if (is_year() || is_month() || is_day()) {
        wp_redirect(wpuprojectid_pagenews_get_url());
        exit;
    }
});

/* ----------------------------------------------------------
  Breadcrumb on News Page
---------------------------------------------------------- */

add_filter('wputh_get_breadcrumbs__after_home', function ($elements_ariane) {
    if (is_category() || is_singular('post')) {
        $elements_ariane['archive-news'] = array(
            'name' => get_the_title(wpuprojectid_pagenews_get_id()),
            'link' => wpuprojectid_pagenews_get_url()
        );
    }
    return $elements_ariane;
}, 10, 1);
