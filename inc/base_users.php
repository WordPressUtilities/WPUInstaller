<?php
/*
Plugin Name: [wpuprojectname] Users
Description: User settings
*/

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

/* ----------------------------------------------------------
  Allow more user roles to edit private policy page
---------------------------------------------------------- */

/* Thx to https://wordpress.stackexchange.com/a/325784 */

add_action('map_meta_cap', function ($caps, $cap, $user_id, $args) {
    if ($cap != 'manage_privacy_options') {
        return $caps;
    }
    if (current_user_can('edit_posts')) {
        $manage_name = is_multisite() ? 'manage_network' : 'manage_options';
        $caps = array_diff($caps, [$manage_name]);
    }
    return $caps;
}, 1, 4);

/* ----------------------------------------------------------
  Admin : filter posts by authors
---------------------------------------------------------- */

add_action('restrict_manage_posts', function () {
    $users = get_users(array(
        'orderby' => 'display_name',
        'role__not_in' => array(
            'subscriber',
            'customer'
        )
    ));
    if (count($users) <= 1) {
        return;
    }
    echo '<select name="author" class="">';
    echo '<option value="">' . __('All authors', 'wputh') . '</option>';
    foreach ($users as $usr) {
        echo '<option ' . (isset($_GET['author']) && $_GET['author'] == $usr->ID ? 'selected="selected"' : '') . ' value="' . esc_attr($usr->ID) . '">' . esc_html($usr->display_name) . '</option>';
    }
    echo '</select>';
});
