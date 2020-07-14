<?php
/*
Plugin Name: [wpuproject] Users
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
  Only an admin can add a new admin
---------------------------------------------------------- */

add_filter('editable_roles', function ($roles) {
    if (isset($roles['administrator']) && !current_user_can('activate_plugins')) {
        unset($roles['administrator']);
    }
    return $roles;
}, 10, 1);
