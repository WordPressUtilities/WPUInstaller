<?php /* */

add_filter('wpu_acf_flexible_content', 'wpuprojectid_home_wpu_acf_flexible_content', 10, 1);
function wpuprojectid_home_wpu_acf_flexible_content($contents) {

    $layouts = array();

    /* Add layout from master blocks */
    $all_layouts = apply_filters('wpuprojectid_blocks', array());
    if (isset($all_layouts['cta-image'])) {
        $layouts['cta-image'] = $all_layouts['cta-image'];
    }

    /* Add a specific layout */
    /*
    $layouts['basique'] = array(
        'label' => 'Basique',
        'sub_fields' => array(
            'title' => array(
                'label' => 'Titre'
            ),
            'content' => array(
                'label' => 'Contenu',
                'type' => 'textarea'
            ),
            'link' => array(
                'label' => 'URL Bouton',
                'type' => 'link'
            )
        )
    );
    */

    /* Logos : A list of clickable logos */
    $layouts['logos'] = array(
        'wpuacf_model' => 'logos'
    );
    /* Video : A simple embed with optional title and content */
    $layouts['video'] = array(
        'wpuacf_model' => 'video'
    );

    /* Target home page */
    $acf_location = array();
    $home_ids = array(get_option('home__page_id'));
    if (function_exists('pll_get_post_translations')) {
        $home_ids = pll_get_post_translations($home_ids[0]);
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

    $contents['content-home-blocks'] = array(
        /* Save HTML content in post_content */
        'save_post' => 1,
        /* Create initial layout files */
        'init_files' => 1,
        /* Custom location */
        'location' => $acf_location,
        /* Global Conf */
        'name' => 'Blocks',
        /* Load layouts */
        'layouts' => $layouts
    );
    return $contents;
}
