<?php

/*
Plugin Name: [wpuprojectname] Menus
Description: Config for menus
*/

/* ----------------------------------------------------------
  Add back button on submenus
---------------------------------------------------------- */

add_action('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {
    if ($depth == 0 && $args->container_class == 'main-menu__wrapper') {
        $item_output = str_replace('</a>', '<span class="wpuprojectid-menu-go"><i aria-hidden="true" class="icon icon_go"></i></span></a>', $item_output);
        if (in_array('menu-item-has-children', $item->classes)) {
            $item_output .= '<div class="sub-menu-wrapper">';
            $item_output .= '<a href="#" role="button" class="wpuprojectid-menu-back"><i aria-hidden="true" class="icon icon_back"></i>'.__('Back', 'wpuprojectid').'</a>';
            $item_output .= '<div class="item-title">'.$item->title.'</div>';
        }
    }
    return $item_output;
}, 10, 4);

class WpuProjectCamelCaseName_Main_Menu_Walker extends Walker_Nav_Menu {
    function end_el(&$output, $data_object, $depth = 0, $args = NULL) {
        if ($depth == 0 && $args->container_class == 'main-menu__wrapper') {
            if (in_array('menu-item-has-children', $data_object->classes)) {
                $output .= '</div>'."\n";
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
  Change link render if image
---------------------------------------------------------- */

add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
    if ($args->container_class == 'main-menu__wrapper' && $depth > 0) {
        $link_menu_image = get_field('menu_image', $item->ID);
        if ($link_menu_image) {
            $menu_image_url = wp_get_attachment_image_url($link_menu_image, 'medium');
            $title = '<div class="item-menu-image"><img class="item-menu-image__image" src="' . $menu_image_url . '" loading="lazy" alt="" /><span class="item-menu-image__title">' . $title . '</span></div>';
        }
    }
    return $title;

}, 10, 4);

/* ----------------------------------------------------------
  Add a menu image
---------------------------------------------------------- */

add_action('plugins_loaded', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_60fe82e35b088',
        'title' => 'Image menu',
        'fields' => array(
            array(
                'key' => 'field_60fe82e50cda5',
                'label' => 'image',
                'name' => 'menu_image',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => ''
                ),
                'uploader' => '',
                'acfe_thumbnail' => 0,
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'translations' => 'copy_once',
                'library' => 'all'
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'nav_menu_item',
                    'operator' => '==',
                    'value' => 'all'
                )
            )
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'left',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'acfe_display_title' => '',
        'acfe_autosync' => '',
        'acfe_form' => 0,
        'acfe_meta' => '',
        'acfe_note' => ''
    ));

});
