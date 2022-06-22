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
  Custom field types
---------------------------------------------------------- */

add_filter('wpu_acf_flexible__field_types', function ($types) {
    /* Title */
    $types['wpuprojectid_title'] = array(
        'instructions' => 'Special text : &lt;u&gt;Text&lt;/u&gt;.',
        'label' => 'Title',
        'rows' => 3,
        'type' => 'textarea',
        'field_vars_callback' => function ($id, $sub_field, $level) {
            return '$' . $id . ' = get_sub_field(\'' . $id . '\');' . "\n";
        },
        'field_html_callback' => function ($id, $sub_field, $level) {
            return '<?php echo $' . $id . ' ? strtoupper($' . $id . ') : \'\'; ?>' . "\n";
        }
    );
    return $types;
}, 10, 1);

/* ----------------------------------------------------------
  Ignore some types on file generation
---------------------------------------------------------- */

add_filter('wpu_acf_flexible__value_content_field', function ($values, $id, $sub_field, $level) {
    $ignored_types = array('wpuprojectid_custom_field');
    if (in_array($sub_field['original_field_type'], $ignored_types)) {
        return '';
    }
    return $values;
}, 10, 4);

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
echo '<section class="centered-container section-m cc-block--${layout_id}"><div class="block--${layout_id}">';
?>
${values}
<?php
echo '</div></section>';
EOT;
}, 10, 5);

/* ----------------------------------------------------------
  At block creation : create SCSS & JS files
---------------------------------------------------------- */

add_action('wpu_acf_flexible__set_file_content', function ($layout_id, $group) {
    if (in_array($layout_id, array('excluded_layout_id'))) {
        return;
    }
    $scss_path = get_stylesheet_directory() . '/src/scss/wpuprojectid/blocks/';
    $js_path = get_stylesheet_directory() . '/src/js/blocks/';
    if (!is_dir($scss_path)) {
        mkdir($scss_path);
    }
    if (!is_dir($js_path)) {
        mkdir($js_path);
    }

    if (isset($group['layouts'][$layout_id]['label'])) {
        $title = $group['layouts'][$layout_id]['label'];
    } else {
        $title = str_replace(array('-', '_'), ' ', $layout_id);
        $title = ucwords($title);
    }

    $scss_file = $scss_path . '_' . $layout_id . '.scss';
    if (!file_exists($scss_file)) {

        $comment = <<<EOT
@charset "UTF-8";

/* ----------------------------------------------------------
  Block ${title}
---------------------------------------------------------- */

.cc-block--${layout_id} {

}

.block--${layout_id} {

}

EOT;
        $comment .= wpuacfflex_template_get_layout_css($layout_id, $group['layouts'][$layout_id], '.block--' . $layout_id);

        file_put_contents($scss_file, $comment);
    }

    $js_file = $js_path . $layout_id . '.js';
    if (!file_exists($js_file)) {
        $comment = <<<EOT
/**
 * Block ${title}
 */

jQuery(document).ready(function($) {
    jQuery('.block--${layout_id}').each(function(){
        var \$this = jQuery(this);
    });
});

EOT;
        file_put_contents($js_file, $comment);
    }
}, 10, 2);

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
