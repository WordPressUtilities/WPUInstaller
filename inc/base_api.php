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

    public function get_options() {
        $opt = get_option('wpuprojectid_myapiid_options');

        if (!is_array($opt) || !isset($opt['api_token'], $opt['api_endpoint']) || empty($opt['api_token']) || empty($opt['api_endpoint'])) {
            error_log('API - Missing values');
            return false;
        }

        /* Checking if this call can be made */
        if (defined('WP_HTTP_BLOCK_EXTERNAL') && WP_HTTP_BLOCK_EXTERNAL) {
            error_log('Error: WP_HTTP_BLOCK_EXTERNAL is enabled.');
            return false;
        }

        return $opt;
    }

    public function extract_result($result_body) {
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
}

$wpuprojectid_myapiid = new wpuprojectid_myapiid();

/* ----------------------------------------------------------
  Import Action
---------------------------------------------------------- */

function wpuprojectid_myapiid_import_action() {
    global $wpuprojectid_myapiid;

    $content = wpuprojectid_myapiid_get('route', array(
        'key' => 'value'
    ));
    $content = wpuprojectid_myapiid_post('route', array(
        'key' => 'value'
    ));
}

/* ----------------------------------------------------------
  API callback
---------------------------------------------------------- */

/* Get : Example
-------------------------- */

function wpuprojectid_myapiid_get($route, $data = array()) {

    global $wpuprojectid_myapiid;

    /* Get options */
    $opt = $wpuprojectid_myapiid->get_options();
    if (!$opt) {
        return false;
    }

    /* Build URL & args */
    $data['api_token'] = $opt['api_token'];
    $remote_url = $opt['api_endpoint'] . $route . '?' . http_build_query($data);

    /* Making the call */
    $result_body = wp_remote_retrieve_body(wp_remote_get($remote_url));

    /* Log result */
    error_log('myapiid - URL : ' . $remote_url);
    error_log('myapiid - GET : ' . json_encode($data));
    error_log('myapiid - RESULTGET : ' . $result_body);

    /* Return cleaned values */
    return $wpuprojectid_myapiid->extract_values($result_body);
}

/* POST : Example
-------------------------- */

function wpuprojectid_myapiid_post($route, $data = array()) {
    global $wpuprojectid_myapiid;

    /* Get options */
    $opt = $wpuprojectid_myapiid->get_options();
    if (!$opt) {
        return false;
    }

    /* Build URL & args */
    $remote_url = $opt['api_endpoint'] . $route . '?' . http_build_query(array(
        'api_token' => $opt['api_token']
    ));
    $args = array(
        'headers' => array(
            "Accept: application/json",
            "Content-Type: application/json"
        ),
        'method' => 'POST',
        'data_format' => 'json',
        'body' => $data
    );

    /* Making the call */
    $result_body = wp_remote_retrieve_body(wp_remote_post($remote_url, $args));

    /* Log result */
    error_log('myapiid - URL : ' . $remote_url);
    error_log('myapiid - POST : ' . json_encode($data));
    error_log('myapiid - RESULTPOST : ' . $result_body);

    /* Return cleaned values */
    return $wpuprojectid_myapiid->extract_result($result_body);

}
