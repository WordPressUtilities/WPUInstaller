<?php
/*
Plugin Name: [wpuprojectname] Blocks
Description: Add project blocks
*/

/* ----------------------------------------------------------
  Master ACF location
---------------------------------------------------------- */

function wpuprojectid_get_master_location() {
    $acf_location = array();
    $home_ids = array(get_option('home__page_id'));
    if (function_exists('pll_get_post_translations')) {
        $home_ids_tr = pll_get_post_translations($home_ids[0]);
        if ($home_ids_tr) {
            $home_ids = $home_ids_tr;
        }
    }
    foreach ($home_ids as $home_id) {
        $acf_location[] = array(
            array(
                'param' => 'post',
                'operator' => '==',
                'value' => $home_id
            )
        );
    }

    $acf_location[] = array(
        array(
            'param' => 'page_template',
            'operator' => '==',
            'value' => 'page-master.php'
        )
    );

    $post_types = apply_filters('wpuprojectid_master_post_types', array('post'));
    foreach ($post_types as $post_type) {
        $acf_location[] = array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => $post_type
            )
        );
    }
    return $acf_location;
}

/* ----------------------------------------------------------
  Global layouts
---------------------------------------------------------- */

add_filter('wpu_acf_flexible_content', function ($contents) {

    $all_layouts = apply_filters('wpuprojectid_blocks', array());

    $all_layouts['content'] = array(
        'label' => 'WYSIWYG',
        'wpuacf_model' => 'content-classic'
    );
    $all_layouts['video'] = array(
        'label' => 'Video',
        'wpuacf_model' => 'video'
    );
    $all_layouts['image'] = array(
        'label' => 'Image',
        'wpuacf_model' => 'image'
    );

    /* Page */
    $contents['content-blocks'] = array(
        'init_files' => (wp_get_environment_type() == 'local'),
        'save_post' => 1,
        'location' => wpuprojectid_get_master_location(),
        'name' => 'Master Blocks',
        'layouts' => $all_layouts
    );

    return $contents;
}, 12, 1);

/* Layouts on subfiles
add_filter('wpuprojectid_blocks', function ($layouts) {
    $layouts['home-slider-top'] = array(
        'label' => 'Slider Top',
        'sub_fields' => array(
            'test' => array(
                'label' => 'Test'
            )
        )
    );
    return $layouts;
}, 10, 1);
*/

/* ----------------------------------------------------------
  Master Generator model
---------------------------------------------------------- */

add_filter('wpu_acf_flexible__master_generator__post_details', function ($content) {
    $content['post_type'] = 'page';
    $content['post_title'] = 'Page Master';
    // $content['post_status'] = 'publish';
    return $content;
}, 10, 1);

add_action('wpu_acf_flexible__master_generator__after_insert_post', function ($post_id) {
    update_post_meta($post_id, '_wp_page_template', 'page-master.php');
    // update_post_meta($post_id, 'master_header_surtitle', 'A sur-title');
    // update_post_meta($post_id, 'master_header_intro', 'The world needs dreamers and the world needs doers. But above all, the world needs dreamers who do — Sarah Ban Breathnach.');
}, 10, 1);

/* ----------------------------------------------------------
  Default loaded blocks
---------------------------------------------------------- */

add_filter('acf/load_value/name=content-blocks', function ($value, $post_id, $field) {
    if ($value !== NULL) {
        return $value;
    }
    $value = array(
        array(
            'acf_fc_layout' => 'content'
        ),
        array(
            'acf_fc_layout' => 'image'
        )
    );
    return $value;
}, 10, 3);

/* ----------------------------------------------------------
  Set default image
---------------------------------------------------------- */

add_action('save_post', function ($post_id) {
    if (!function_exists('get_field')) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }
    $post_thumbnail = get_post_meta($post_id, '_thumbnail_id', true);
    if (!empty($post_thumbnail)) {
        return;
    }
    $master_header_image = get_field('master_header_image', $post_id);
    if (!$master_header_image) {
        return;
    }
    update_post_meta($post_id, '_thumbnail_id', $master_header_image);
}, 99);

/* ----------------------------------------------------------
  Default themes
---------------------------------------------------------- */

/* Button
-------------------------- */

add_filter('get_wpu_acf_link_classname', function ($classname) {
    if ($classname == '') {
        $classname = 'wpuprojectid-button';
    }
    return $classname;
}, 10, 1);

/* Title
-------------------------- */

add_filter('get_wpu_acf__title__html', function ($title, $_title, $field_name) {
    return wpuprojectid_title($_title);
}, 10, 3);
