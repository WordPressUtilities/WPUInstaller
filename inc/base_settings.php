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
        #$actions[1001] = array(&$this, 'set_plugins');
        #$actions[1002] = array(&$this, 'set_theme');
        #$actions[1003] = array(&$this, 'set_options');
        #$actions[1004] = array(&$this, 'set_menus');
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
        if (!defined('WP_CLI')) {
            wp_redirect(site_url());
        }
    }

    public function set_options() {
        # WordPress
        update_option('start_of_week', '1');
        update_option('date_format', 'j F Y');
        update_option('time_format', 'H:i');
        update_option('timezone_string', 'Europe/Paris');
        update_option('permalink_structure', '/%postname%/');

        # Pages
        update_option('page_on_front', get_option('home__page_id'));
        update_option('show_on_front', 'page');

        # Social
        update_option('social_instagram_url', 'https://www.instagram.com/');
        update_option('social_linkedin_url', 'https://www.linkedin.com/');
        update_option('social_twitter_url', 'https://www.twitter.com/');
    }

    public function set_menus() {
        global $wpu_settings_version;

        /* Use theme menus */
        $menus = apply_filters('wputh_default_menus', array());

        /* Add pages to these menus */
        $pages = array(
            get_option('home__page_id'),
            get_option('contact__page_id'),
            array(
                'type' => 'post_type_archive',
                'post_type' => 'projets'
            ),
            array(
                'type' => 'custom',
                'url' => 'https://darklg.me',
                'title' => 'Custom Link'
            )
        );

        /* Create menus */
        if (!is_object($wpu_settings_version)) {
            $wpu_settings_version = new wpu_settings_version();
        }
        $wpu_settings_version->set_menus($pages, $menus, 'wpuprojectid');
    }
}

$wpuprojectid_settings = new wpuprojectid_settings();

/*
echo '<pre>$wpu_settings_version->activate_plugins(';
var_export(get_option('active_plugins'));
echo ');</pre>';
die;
*/
