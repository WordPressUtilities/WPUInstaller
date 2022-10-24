<?php

/* ----------------------------------------------------------
  Custom field types
---------------------------------------------------------- */

/* Title
-------------------------- */

add_filter('wpu_acf_flexible__field_types', function ($types) {
    /* Title */
    $types['wpuprojectid_title'] = array(
        'instructions' => 'Blue text : &lt;u&gt;Text&lt;/u&gt;.',
        'label' => 'Title',
        'rows' => 2,
        'type' => 'textarea',
        'field_vars_callback' => function ($id, $sub_field, $level) {
            return '$' . $id . ' = get_sub_field(\'' . $id . '\');' . "\n";
        },
        'field_html_callback' => function ($id, $sub_field, $level) {
            return '<?php echo wpuprojectid_title($' . $id . '); ?>' . "\n";
        }
    );
    return $types;
}, 10, 1);

function wpuprojectid_title($field, $args = array()) {
    if(!is_array($args)){
        $args = array();
    }
    if(!isset($args['wrapper'])){
        $args['wrapper'] = true;
    }
    $field = strip_tags($field, '<u>');
    $field = trim($field);
    if (!$args['wrapper']) {
        return $field;
    }
    return '<h2 class="wpuprojectid-title">' . nl2br($field) . '</h2>';
}

/* Icons
-------------------------- */

add_filter('wpu_acf_flexible__field_types', function ($types) {
    $types['wpuprojectid_icon'] = array(
        'label' => 'Icône',
        'type' => 'select',
        'choices' => wpuprojectid_get_icons(),
        'field_vars_callback' => function ($id, $sub_field, $level) {
            return '$' . $id . ' = get_sub_field(\'' . $id . '\');' . "\n";
        },
        'field_html_callback' => function ($id, $sub_field, $level) {
            return '<?php echo wpuprojectid_icon($' . $id . '); ?>' . "\n";
        }
    );
    return $types;
}, 10, 1);

function wpuprojectid_get_icons() {
    $cache_id = 'wpuprojectid_get_icons';
    $cache_duration = 60;

    $icons = wp_cache_get($cache_id);
    if ($icons === false) {
        $icons_raw = glob(get_stylesheet_directory() . '/src/icons/*.svg');
        $icons = array();
        foreach ($icons_raw as $icn) {
            $icn = str_replace('.svg', '', basename($icn));
            $icons[$icn] = $icn;
        }
        wp_cache_set($cache_id, $icons, '', $cache_duration);
    }
    return array('' => '-- No Icon --') + $icons;
}

function wpuprojectid_icon($icon = '') {
    $icons = wpuprojectid_get_icons();
    if (!$icon || !array_key_exists($icon, $icons)) {
        return '';
    }
    return '<i aria-hidden="true" class="icon icon_' . esc_attr($icon) . ' wpuprojectid-icn"></i>';
}
