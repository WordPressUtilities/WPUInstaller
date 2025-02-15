<?php

/* ----------------------------------------------------------
  Custom field types
---------------------------------------------------------- */

/* Theme
-------------------------- */

function wpuprojectid_get_layout_themes($type = 'theme') {
    $themes = array(
        /* Default layout always has merged margin and is similar to the page background */
        'clear' => array(
            'name' => __('Clear background', 'wpuprojectid'),
            'classname' => 'section--clear'
        ),
        'dark' => array(
            'name' => __('Dark background', 'wpuprojectid'),
            'classname' => 'section--dark'
        )
    );
    $return_themes = array();
    foreach ($themes as $theme_id => $theme) {
        $val = $theme;
        if ($type && isset($theme[$type])) {
            $val = $theme[$type];
        }
        $return_themes[$theme_id] = $val;
    }

    return $return_themes;
}

add_filter('wpu_acf_flexible__field_types', function ($types) {

    $types['wpuprojectid_theme'] = array(
        'label' => 'Theme',
        'type' => 'group',
        'sub_fields' => array(
            'acc' => array(
                'label' => __('Settings', 'wpuprojectid'),
                'type' => 'accordion'
            ),
            'cola' => 'wpuacf_25p',
            'margin' => array(
                'label' => __('Margin', 'wpuprojectid'),
                'type' => 'select',
                'choices' => array(
                    'large' => __('Large', 'wpuprojectid'),
                    'medium' => __('Medium', 'wpuprojectid'),
                    'thin' => __('Thin', 'wpuprojectid')
                )
            ),
            'colb' => 'wpuacf_25p',
            'width' => array(
                'label' => __('Width', 'wpuprojectid'),
                'type' => 'select',
                'default_value' => 'medium',
                'choices' => array(
                    'full' => __('Full', 'wpuprojectid'),
                    'medium' => __('Medium', 'wpuprojectid'),
                    'thin' => __('Thin', 'wpuprojectid')
                )
            ),
            'colc' => 'wpuacf_25p',
            'theme' => array(
                'label' => __('Theme', 'wpuprojectid'),
                'type' => 'select',
                'choices' => wpuprojectid_get_layout_themes('name')
            ),
            'cold' => 'wpuacf_25p',
            'responsive_visibility' => 'wpuacf_responsive_visibility'
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

    $layout_themes = wpuprojectid_get_layout_themes('classname');
    $layout_keys = array_keys($layout_themes);
    $default_layout = isset($layout_keys[0]) ? $layout_keys[0] : 'clear';

    if (!isset($theme['theme']) || !$theme['theme']) {
        $theme['theme'] = $default_layout;
    }
    if (!isset($theme['margin']) || !$theme['margin']) {
        $theme['margin'] = 'large';
    }
    if (!isset($theme['width']) || !$theme['width']) {
        $theme['width'] = 'medium';
    }

    /* Margins */
    if ($theme['theme'] == $default_layout) {
        $classnames[] = 'section-m--' . $theme['margin'];
    } else {
        $classnames[] = 'section--' . $theme['margin'];
    }

    /* Width */
    $classnames[] = 'centered-container--' . $theme['width'];

    /* Theme */
    if (isset($layout_themes[$theme['theme']])) {
        $classnames[] = $layout_themes[$theme['theme']];
    }

    /* Visibility */
    if (isset($theme['responsive_visibility']) && is_array($theme['responsive_visibility'])) {
        if (in_array('desktop', $theme['responsive_visibility'])) {
            $classnames[] = 'hidden-on-full';
        }
        if (in_array('tablet', $theme['responsive_visibility'])) {
            $classnames[] = 'hidden-on-tablet';
        }
        if (in_array('phone', $theme['responsive_visibility'])) {
            $classnames[] = 'hidden-on-phone';
        }
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
        'wpuacf_validate_html' => true,
        'label' => __('Title'),
        'rows' => 2,
        'type' => 'textarea',
        'field_vars_callback' => function ($id, $sub_field, $level) {
            return '';
        },
        'field_html_callback' => function ($id, $sub_field, $level) {
            return '<?php echo wpuprojectid_title(get_sub_field(\'' . $id . '\')); ?>' . "\n";
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
