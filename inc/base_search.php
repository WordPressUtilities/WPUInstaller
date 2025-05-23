<?php
/*
Plugin Name: [wpuprojectname] Search
Description: Search Settings
*/

/* ----------------------------------------------------------
  Index extra fields
---------------------------------------------------------- */

add_filter('relevanssi_index_custom_fields', 'wpuprojectid_search_custom_fields');
function wpuprojectid_search_custom_fields($custom_fields = array()) {

    /* Add fields */
    $custom_fields[] = 'wpu_post_content';

    return $custom_fields;
}

/* ----------------------------------------------------------
  Disable UX Tweak if installed
---------------------------------------------------------- */

add_filter('disable__wpuux_redirect_only_result_search', '__return_true');

/* ----------------------------------------------------------
  Disable relevanssi excerpts
---------------------------------------------------------- */

add_filter('pre_option_relevanssi_excerpts', function ($val) {
    return 'off';
});

/* ----------------------------------------------------------
  Allow search in custom post types
---------------------------------------------------------- */

add_filter('pre_get_posts', function ($query) {
    if ($query->is_search && !is_admin() && isset($query->query['post_type'])) {
        $query->set('post_type', $query->query['post_type']);

    }
    return $query;
});

/* ----------------------------------------------------------
  Search post types
---------------------------------------------------------- */

add_filter('wpuprojectid_search_post_types', function ($types = array()) {
    $types['post'] = array(
        'label' => __('News', 'wpuprojectid'),
        'tpl' => 'loop-post.php'
    );
    return $types;
}, 10, 1);

/* ----------------------------------------------------------
  Display search
---------------------------------------------------------- */

add_action('wp', function () {
    if (!isset($_GET['s'], $_GET['search_ajax_pt'], $_GET['search_ajax_paged'])) {
        return;
    }
    $post_types = apply_filters('wpuprojectid_search_post_types', array());
    if (!is_numeric($_GET['search_ajax_paged']) || !array_key_exists($_GET['search_ajax_pt'], $post_types)) {
        return;
    }
    echo wpuprojectid_search_get_post_type_content($_GET['search_ajax_pt'], $post_types[$_GET['search_ajax_pt']], array(
        'paged' => $_GET['search_ajax_paged']
    ));
    die;
});

/* Add content
-------------------------- */

function wpuprojectid_search_get_post_type_content($post_type_key, $pt_settings, $args = array()) {
    if (!is_array($args)) {
        $args = array();
    }

    /* Per page */
    if (!isset($args['per_page'])) {
        $args['per_page'] = 6;
    }
    if (isset($pt_settings['per_page'])) {
        $args['per_page'] = $pt_settings['per_page'];
    }

    /* Paged */
    if (!isset($args['paged']) || $args['paged'] == 0) {
        $args['paged'] = 1;
    }

    /* Classname */
    if (!isset($args['classname'])) {
        $args['classname'] = 'loop-list';
    }
    if (isset($pt_settings['classname'])) {
        $args['classname'] = $pt_settings['classname'];
    }

    $start = $args['per_page'] * $args['paged'] - $args['per_page'];
    $max = $start + $args['per_page'];
    $wpq_search = new WP_Query();
    $wpq_search->parse_query(array(
        'posts_per_page' => $args['per_page'],
        'offset' => $start,
        'ignore_sticky_posts' => true,
        'post_type' => $post_type_key,
        's' => get_search_query()
    ));
    if (function_exists('relevanssi_do_query')) {
        relevanssi_do_query($wpq_search);
    }
    $html = '';
    if ($wpq_search->have_posts()) {
        $html .= '<ul class="' . esc_attr($args['classname']) . '" data-max="' . esc_attr($max) . '" data-found="' . esc_attr($wpq_search->found_posts) . '">';
        while ($wpq_search->have_posts()) {
            $wpq_search->the_post();
            $html .= '<li>';
            ob_start();
            include get_stylesheet_directory() . '/tpl/loops/' . $pt_settings['tpl'];
            $html .= ob_get_clean();
            $html .= '</li>';
        }
        $html .= '</ul>';
        if ($wpq_search->found_posts > $max) {
            $html .= '<div class="search-load-more-wrapper">';
            $html .= '<button type="button" class="wpuprojectid-button" data-s="' . esc_attr(get_search_query()) . '" data-search-pt="' . esc_attr($post_type_key) . '" data-search-paged="' . esc_attr($args['paged'] + 1) . '"><span>' . __('Load more', 'wpuprojectid') . '</span></button>';
            $html .= '</div>';
        }
    }
    wp_reset_postdata();

    return $html;
}

/* Quick ajax load more
-------------------------- */

add_action('wp_footer', function () {
    if (!isset($_GET['s'])) {
        return;
    }
?>
<script>jQuery('body').on('click', '[data-search-pt][data-search-paged]', function(e) {
    e.preventDefault();
    var $button = jQuery(this),
        $wrapper = $button.closest('.search-load-more-wrapper');
    $wrapper.addClass('is-loading');
    $button.prop('disabled', 1);
    jQuery.ajax({
        url: "/",
        data: {
            search_ajax_pt: $button.attr('data-search-pt'),
            search_ajax_paged: $button.attr('data-search-paged'),
            s: $button.attr('data-s'),
        }
    })
    .done(function(data) {
        /* Inject content */
        $wrapper.after(jQuery(data));
        /* Delete button */
        $wrapper.remove();
    });
});</script>
<?php
});
