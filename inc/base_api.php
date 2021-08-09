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
            'api_endpoint' => array(
                'label' => 'API Endpoint',
                'help' => 'Your endpoint.',
                'type' => 'url'
            ),
            'api_token' => array(
                'label' => 'API Token',
                'help' => 'Your token.',
                'type' => 'text'
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

    if (!is_array($opt) || !isset($opt['api_token']) || !isset($opt['api_endpoint']) || empty($opt['api_token']) || empty($opt['api_endpoint'])) {
        echo 'Missing config values';
        return;
    }

    $url = '';
    $content = wpuprojectid_myapiid_get($url, array(
        'token' => $opt['api_token']
    ));
}

/* ----------------------------------------------------------
  API callback
---------------------------------------------------------- */

function wpuprojectid_myapiid_get($remote_url, $headers = array(), $args = array()) {

    /* Default args */
    if (!is_array($args)) {
        $args = array();
    }
    if ($headers && is_array($headers)) {
        $args['headers'] = $headers;
    }

    /* Checking if this call can be made */
    if (defined('WP_HTTP_BLOCK_EXTERNAL') && WP_HTTP_BLOCK_EXTERNAL) {
        error_log('Error: WP_HTTP_BLOCK_EXTERNAL is enabled.');
        return false;
    }

    /* Making the call */
    $result_body = wp_remote_retrieve_body(wp_remote_get($remote_url, $args));
    if (!$result_body) {
        return false;
    }

    /* Converting to JSON */
    $first_char = substr($result_body, 0, 1);
    if ($first_char == '{' || $first_char == '[') {
        return json_decode($result_body, true);
    }

    /* Sending result */
    return $result_body;
}
