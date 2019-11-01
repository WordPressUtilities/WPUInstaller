<?php
/*
Plugin Name: [wpuproject] Settings
Description: Site Auto Settings
*/

class wpuprojectid_settings {
    public function __construct() {
        add_filter('wpusettingsversion_actions', array(&$this, 'list_actions'), 10, 1);
    }

    public function list_actions($actions) {
        // $actions[20180905171540] = array(&$this, 'set_plugins');
        return $actions;
    }

    public function set_plugins() {
        global $wpu_settings_version;
        if (method_exists($wpu_settings_version, 'activate_plugins')) {
            $wpu_settings_version->activate_plugins(array(
                'wpudisabler/wpudisabler.php',
                'wpuoptions/wpuoptions.php',
                'wpupostmetas/wpupostmetas.php',
                'wpuposttypestaxos/wpuposttypestaxos.php',
                'wpuseo/wpuseo.php',
                'wputaxometas/wputaxometas.php'
            ));
        }

    }
}

$wpuprojectid_settings = new wpuprojectid_settings();
