<?php
/*
Plugin Name: [wpuprojectname] Users
Description: User settings
*/

/* ----------------------------------------------------------
  Min admin level
---------------------------------------------------------- */

add_filter('wputh_admin_protect_block_admin__level_access_min_admin_access', function ($level) {
    return 'manage_categories';
});

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
