<?php
/*
Plugin Name: [wpuprojectname] Users
Description: User settings
*/

/* ----------------------------------------------------------
  Add Super Editor role
---------------------------------------------------------- */

add_action('init', function () {

    /* Details */
    $role_opt = 'wpu_super_editor_role';
    $role_id = 'super_editor';
    $role_name = 'Super Editor';

    /* Start on editor role */
    $editor_role = get_role('editor');
    $role_details = $editor_role->capabilities;

    /* Add new capacities */
    $role_details['create_users'] = true;
    $role_details['add_users'] = true;
    $role_details['list_users'] = true;
    $role_details['edit_users'] = true;
    $role_details['remove_users'] = true;
    $role_details['promote_users'] = true;

    /* WooCommerce */
    $role_details['edit_product'] = true;
    $role_details['read_product'] = true;
    $role_details['delete_product'] = true;
    $role_details['edit_products'] = true;
    $role_details['edit_others_products'] = true;
    $role_details['publish_products'] = true;
    $role_details['read_private_products'] = true;
    $role_details['delete_products'] = true;
    $role_details['delete_private_products'] = true;
    $role_details['delete_published_products'] = true;
    $role_details['delete_others_products'] = true;
    $role_details['edit_private_products'] = true;
    $role_details['edit_published_products'] = true;
    $role_details['manage_product_terms'] = true;
    $role_details['edit_product_terms'] = true;
    $role_details['delete_product_terms'] = true;
    $role_details['assign_product_terms'] = true;

    /* Yoast SEO */
    $role_details['wpseo_bulk_edit'] = true;
    $role_details['wpseo_edit_advanced_metadata'] = true;
    $role_details['wpseo_manage_options'] = true;

    $role_version = md5($role_id . $role_name . json_encode($role_details));

    /* Update role only if it doesn’t exists */
    if (get_option($role_opt) != $role_version) {
        if (get_role($role_id)) {
            remove_role($role_id);
        }
        add_role($role_id, $role_name, $role_details);
        update_option($role_opt, $role_version);
    }
});

/* ----------------------------------------------------------
  Clean admin menus
---------------------------------------------------------- */

add_action('admin_menu', function () {
    global $submenu;
    /* For non admins only */
    if (current_user_can('activate_plugins')) {
        return;
    }
    /* Remove menus */
    remove_menu_page('tools.php');
    remove_submenu_page('themes.php', 'themes.php');
    /* Remove some theme parts */
    if (isset($submenu['themes.php'])) {
        foreach ($submenu['themes.php'] as $i => $item) {
            if (isset($item[1], $item[4]) && $item[1] == 'edit_theme_options' && $item[4] == 'hide-if-no-customize') {
                unset($submenu['themes.php'][$i]);
            }
        }
    }
});

/* ----------------------------------------------------------
  Only an admin can add a new admin
---------------------------------------------------------- */

add_filter('editable_roles', function ($roles) {
    if (isset($roles['administrator']) && !current_user_can('activate_plugins')) {
        unset($roles['administrator']);
    }
    return $roles;
}, 10, 1);

/* ----------------------------------------------------------
  Redirection : Allow access to users
---------------------------------------------------------- */

add_action('admin_menu', function () {
    if (!defined('REDIRECTION_DB_VERSION')) {
        return;
    }
    add_menu_page(
        __('Redirection', 'wpuprojectid'),
        __('Redirection', 'wpuprojectid'),
        'list_users',
        'tools.php?page=redirection.php'
    );
});

add_filter('redirection_role', function ($role) {
    return 'list_users';
});
