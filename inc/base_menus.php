<?php

/*
Plugin Name: [wpuprojectname] Menus
Description: Config for menus
*/

/* ----------------------------------------------------------
  Add back button on submenus
---------------------------------------------------------- */

add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {
    if ($depth == 0 && $args->container_class == 'main-menu__wrapper') {
        $item_output = str_replace('</a>', '<span class="wpuprojectid-menu-go"><i aria-hidden="true" class="icon icon_go"></i></span></a>', $item_output);
        if (in_array('menu-item-has-children', $item->classes)) {
            $item_output .= '<div class="sub-menu-wrapper">';
            $item_output .= '<a href="#" role="button" class="wpuprojectid-menu-back"><i aria-hidden="true" class="icon icon_back"></i>' . __('Back', 'wpuprojectid') . '</a>';
            $item_output .= '<div class="item-title">' . $item->title . '</div>';
        }
    }
    return $item_output;
}, 10, 4);

class WpuProjectCamelCaseName_Main_Menu_Walker extends Walker_Nav_Menu {
    function end_el(&$output, $data_object, $depth = 0, $args = NULL) {
        if ($depth == 0 && $args->container_class == 'main-menu__wrapper') {
            if (in_array('menu-item-has-children', $data_object->classes)) {
                $output .= '</div>' . "\n";
            }
        }
    }
}
/*
wp_nav_menu(array(
    ...
    'container_class' => 'main-menu__wrapper',
    'walker' => new WpuProjectCamelCaseName_Main_Menu_Walker(),
    ...
));
*/

/* ----------------------------------------------------------
  Custom fields
---------------------------------------------------------- */

add_filter('wpu_acf_flexible_content', function ($contents) {
    $fields = array(
        'wpuprojectidmenu' => array(
            'label' => 'Custom',
            /* Only on level 1 */
            'wpuacf_nav_item_depth' => array(1),
            'type' => 'group',
            'sub_fields' => array(
                'as_title' => array(
                    'label' => 'Display as a title',
                    'type' => 'true_false'
                ),
                'icon' => array(
                    'label' => 'Icon',
                    'type' => 'wpuprojectid_icon'
                )
            )
        )
    );
    $contents['menu-blocks'] = array(
        'location' => array(
            array(
                array(
                    'param' => 'nav_menu_item',
                    'operator' => '==',
                    /* Only on footer location */
                    'value' => 'location/footer'
                )
            )
        ),
        'name' => 'Blocks',
        'fields' => $fields
    );
    return $contents;
}, 10, 1);

/* Adds a classname
-------------------------- */

add_filter('nav_menu_css_class', function ($classes, $item, $args, $depth) {
    if ($args->theme_location == 'footer' && $depth == 1) {
        if (get_field('wpuprojectidmenu_as_title', $item->ID)) {
            $classes[] = 'menu-item--astitle';
        }
        if (get_field('wpuprojectidmenu_icon', $item->ID)) {
            $classes[] = 'menu-item--has-icon';
        }
    }
    return $classes;
}, 10, 4);

/* Adds an icon
-------------------------- */

add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
    if ($args->theme_location == 'footer' && $depth == 1) {
        /* Icon */
        $icon = get_field('wpuprojectidmenu_icon', $item->ID);
        if ($icon) {
            $title = wpuprojectid_icon($icon) . $title;
        }
    }
    return $title;
}, 10, 4);
