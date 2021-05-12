<?php
/*
Plugin Name: [wpuprojectname] API
Description: Handle API from myapiid
*/

/* ----------------------------------------------------------
  Main
---------------------------------------------------------- */

class wpuprojectid_myapiid {
    public function __construct() {
        add_filter('plugins_loaded', array(&$this, 'plugins_loaded'));
    }

    public function plugins_loaded() {
        $this->settings_details = array(
            # Admin page
            'create_page' => true,
            'plugin_basename' => plugin_basename(__FILE__),
            # Default
            'plugin_name' => 'Import myapiid',
            'plugin_id' => 'wpuprojectid_myapiid',
            'option_id' => 'wpuprojectid_myapiid_options',
            'sections' => array(
                'import' => array(
                    'name' => 'Import Settings'
                )
            )
        );
        $this->settings = array(
            'sources' => array(
                'label' => 'Sources',
                'help' => 'One #hashtag or one @user per line.',
                'type' => 'textarea'
            )
        );
        if (is_admin()) {
            new \wpuprojectid_wpubasesettings\WPUBaseSettings($this->settings_details, $this->settings);
        }
    }
}

$wpuprojectid_myapiid = new wpuprojectid_myapiid();

/* ----------------------------------------------------------
  Import Action
---------------------------------------------------------- */

function wpuprojectid_myapiid_import_action() {
    $opt = get_option('wpuprojectid_myapiid_options');
    $url = '';
    $content = json_decode(wp_remote_retrieve_body(wp_remote_get($url)), true);
}
