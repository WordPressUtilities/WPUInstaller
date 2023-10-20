<?php

/* ----------------------------------------------------------
  Custom field types
---------------------------------------------------------- */

/* Theme
-------------------------- */

add_filter('wpu_acf_flexible__field_types', function ($types) {

    $types['wpuprojectid_theme'] = array(
        'label' => 'Theme',
        'type' => 'group',
        'sub_fields' => array(
            'acc' => array(
                'label' => 'Settings',
                'type' => 'accordion'
            ),
            'margin' => array(
                'label' => 'Margin',
                'type' => 'select',
                'choices' => array(
                    'large' => 'Large',
                    'medium' => 'Medium',
                    'thin' => 'Thin'
                )
            ),
            'theme' => array(
                'label' => 'Theme',
                'type' => 'select',
                'choices' => array(
                    'white' => 'White background',
                    'dark' => 'Dark background'
                )
            )
        ),
        'field_vars_callback' => function ($id, $sub_field, $level) {
            return '';
        },
        'field_html_callback' => function ($id, $sub_field, $level) {
            return '';
        }
    );
    return $types;
}, 10, 1);

function wpuprojectid_theme($theme = array()) {
    if (!is_array($theme)) {
        $theme = array();
    }

    $classnames = array();

    if (!isset($theme['theme']) || !$theme['theme']) {
        $theme['theme'] = 'white';
    }
    if (!isset($theme['margin']) || !$theme['margin']) {
        $theme['margin'] = 'large';
    }

    /* Margins */
    if ($theme['theme'] == 'white') {
        $classnames[] = 'section-m--' . $theme['margin'];
    } else {
        $classnames[] = 'section--' . $theme['margin'];
    }

    /* Theme */
    switch ($theme['theme']) {
    case 'dark':
        $classnames[] = 'section--dark';
        break;
    default:
        $classnames[] = 'section--clear';
    }

    return implode(' ', $classnames);
}

/*
$margin-sizes: ("large": 1,"medium": 0.5,"thin": 0.3);
@each $name,$size in $margin-sizes {
    .section--#{$name} {
        & {
            padding-top: ($desktop-section-padding * $size);
            padding-bottom: ($desktop-section-padding * $size);
        }

        @include resp($desktop_excluded) {
            padding-top: ($tablet-section-padding * $size);
            padding-bottom: ($tablet-section-padding * $size);
        }

        @include resp($mobile_only) {
            padding-top: ($mobile-section-padding * $size);
            padding-bottom: ($mobile-section-padding * $size);
        }
    }

    .section-m--#{$name} {
        & {
            margin-top: ($desktop-section-padding * $size);
            margin-bottom: ($desktop-section-padding * $size);
        }

        @include resp($desktop_excluded) {
            margin-top: ($tablet-section-padding * $size);
            margin-bottom: ($tablet-section-padding * $size);
        }

        @include resp($mobile_only) {
            margin-top: ($mobile-section-padding * $size);
            margin-bottom: ($mobile-section-padding * $size);
        }
    }
}
*/

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
    if (!is_array($args)) {
        $args = array();
    }
    if (!isset($args['wrapper'])) {
        $args['wrapper'] = true;
    }
    if (!isset($args['tag'])) {
        $args['tag'] = 'h2';
    }
    if (!isset($args['classname'])) {
        $args['classname'] = '';
    }
    $field = strip_tags($field, '<u>');
    $field = trim($field);
    if (!$args['wrapper']) {
        return $field;
    }
    if (!$field) {
        return;
    }
    return '<' . $args['tag'] . ' class="wpuprojectid-title ' . esc_attr($args['classname']) . '">' . nl2br($field) . '</' . $args['tag'] . '>';
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
