<?php
/*
Plugin Name: [wpuproject] Blocks
Description: Add project blocks
*/

/* ----------------------------------------------------------
  Master ACF location
---------------------------------------------------------- */

function project_id_get_master_location() {
    return array(
        array(
            array(
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page-master.php'
            )
        ),
        array(
            array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'post'
            )
        )
    );
}

/* ----------------------------------------------------------
  Global layouts
---------------------------------------------------------- */

add_filter('wpu_acf_flexible_content', function ($contents) {

    $all_layouts = apply_filters('project_id_blocks', array());

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
        'save_post' => 1,
        'location' => project_id_get_master_location(),
        'name' => 'Master Blocks',
        'layouts' => $all_layouts
    );

    return $contents;
}, 12, 1);

/* Layouts on subfiles
add_filter('project_id_blocks', function ($layouts) {
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
  Default layout
---------------------------------------------------------- */

add_filter('wpu_acf_flexible__file_content', function ($content, $layout_id, $vars, $values, $group) {
    return <<<EOT
<?php
${vars}
?><section class="centered-container section cc-block--${layout_id}">
    <div class="block--${layout_id}">
${values}
    </div>
</section>
EOT;
}, 10, 5);

/* ----------------------------------------------------------
  At block creation : create SCSS file
---------------------------------------------------------- */

add_action('wpu_acf_flexible__set_file_content', function ($layout_id, $group) {
    $scss_path = get_stylesheet_directory() . '/src/scss/project_id/blocks/';
    $js_path = get_stylesheet_directory() . '/src/js/blocks/';
    if (!is_dir($scss_path)) {
        mkdir($scss_path);
    }
    if (!is_dir($js_path)) {
        mkdir($js_path);
    }

    $title = str_replace(array('-', '_'), ' ', $layout_id);
    $title = ucwords($title);

    $scss_file = $scss_path . '_' . $layout_id . '.scss';
    if (!file_exists($scss_file)) {

        $comment = <<<EOT
@charset "UTF-8";

/* ----------------------------------------------------------
  ${title}
---------------------------------------------------------- */

.block--${layout_id} {

}

EOT;
        file_put_contents($scss_file, $comment);
    }

    $js_file = $js_path . $layout_id . '.js';
    if (!file_exists($js_file)) {
        $comment = <<<EOT
jQuery(document).ready(function($) {
    jQuery('.block--${layout_id}').each(function(){
        var \$this = jQuery(this);
    });
});

EOT;
        file_put_contents($js_file, $comment);
    }
}, 10, 2);

