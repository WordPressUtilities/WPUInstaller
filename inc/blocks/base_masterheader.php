<?php

/* ----------------------------------------------------------
  Master Header
---------------------------------------------------------- */

add_filter('wpu_acf_flexible_content', function ($contents) {

    /* Conditions */
    $condition_home = array(
        array(
            array(
                'field' => 'master_headerheader_type',
                'operator' => '==',
                'value' => 'home'
            )
        )
    );

    /* Fields */
    $fields = array();
    $fields['header_type'] = array(
        'required' => 1,
        'label' => 'Header type',
        'type' => 'select',
        'choices' => array(
            'basic' => 'Basic page',
            'home' => 'Homepage'
        )
    );;
    $fields['cola'] = 'wpuacf_50p';
    $fields['title'] = array(
        'label' => 'Titre',
        'type' => 'textarea',
        'rows' => 2
    );
    $fields['intro'] = array(
        'label' => 'Introduction',
        'type' => 'editor',
        'toolbar' => 'wpuacf_mini',
        'conditional_logic' => $condition_home
    );
    $fields['cta'] = array(
        'type' => 'wpuacf_cta'
    );
    $fields['colb'] = 'wpuacf_50p';
    $fields['image'] = array(
        'type' => 'wpuacf_image',
        'required' => false
    );
    $fields['video'] = array(
        'type' => 'wpuacf_video'
    );

    /* Group */
    $contents['master-header'] = array(
        'location' => wpuprojectid_get_master_location(),
        'fields' => array(
            'master_header' => array(
                'key' => 'master_header',
                'label' => 'Master - Header',
                'type' => 'group',
                'sub_fields' => $fields
            )
        )
    );
    return $contents;
}, 13, 1);

/* Save some extra fields
-------------------------- */

add_filter('wpu_acf_flexible__save_post_default_content_html', function ($content, $post_id) {
    $content .= wpautop(trim(get_post_meta($post_id, 'intro', 1)));
    return $content;
}, 10, 2);
