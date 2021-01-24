<?php
/*
Plugin Name: [wpuprojectname] Settings
Description: Site Auto Settings
*/

class wpuprojectid_settings {

    #plugins_list

    public function __construct() {
        add_filter('wpusettingsversion_actions', array(&$this, 'list_actions'), 10, 1);
        add_filter('wp', array(&$this, 'wp'), 10, 1);
    }

    public function list_actions($actions) {
        #$actions[20200417153516] = array(&$this, 'set_plugins');
        #$actions[20200417153545] = array(&$this, 'set_theme');
        #$actions[20200417153547] = array(&$this, 'set_options');
        return $actions;
    }

    public function wp() {
        global $wpu_settings_version;
        if (method_exists($wpu_settings_version, 'force_home_page_id')) {
            $wpu_settings_version->force_home_page_id();
        }
    }

    public function set_plugins() {
        global $wpu_settings_version;
        if (method_exists($wpu_settings_version, 'activate_plugins')) {
            $wpu_settings_version->activate_plugins($this->plugins_list);
        }
    }

    public function set_theme() {
        switch_theme('WPUTheme');
        switch_theme('wpuprojectid');
        wp_redirect(site_url());
        return;
    }

    public function set_options() {
        # update_option('page_on_front', get_option('home__page_id'));
        # update_option('show_on_front', 'page');
    }
}

$wpuprojectid_settings = new wpuprojectid_settings();

/*
echo '<pre>$wpu_settings_version->activate_plugins(';
var_export(get_option('active_plugins'));
echo ');</pre>';
die;
*/
