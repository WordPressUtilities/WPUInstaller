<?php
/*
Plugin Name: [wpuprojectname] Users
Description: User settings
*/

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
