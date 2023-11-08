<?php
/**
 * This file contains settings for auto-generated code & debug posts
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
  Default layout
---------------------------------------------------------- */

add_filter('wpu_acf_flexible__file_content', function ($content, $layout_id, $vars, $values, $group) {
    return <<<EOT
<?php
{$vars}
echo '<section class="centered-container cc-block--{$layout_id} ' . wpuprojectid_theme(get_sub_field('wpuprojectid_theme')) . '"><div class="block--{$layout_id}">';
?>
{$values}
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
  Block {$title}
---------------------------------------------------------- */

.cc-block--{$layout_id} {

}

.block--{$layout_id} {

}

EOT;
        $comment .= wpuacfflex_template_get_layout_css($layout_id, $group['layouts'][$layout_id], '.block--' . $layout_id);

        file_put_contents($scss_file, $comment);
    }

    $js_file = $js_path . $layout_id . '.js';
    if (!file_exists($js_file)) {
        $comment = <<<EOT
/**
 * Block {$title}
 */

jQuery(document).ready(function() {
    'use strict';
    jQuery('.block--{$layout_id}').each(function(){
        var \$this = jQuery(this);
    });
});

EOT;
        file_put_contents($js_file, $comment);
    }
}, 10, 2);
