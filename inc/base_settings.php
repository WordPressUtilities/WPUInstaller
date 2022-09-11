<?php
/*
Plugin Name: [wpuprojectname] Settings
Description: Site Auto Settings
*/

class wpuprojectid_settings {

    #plugins_list

    public function __construct() {
        add_filter('plugins_loaded', array(&$this, 'plugins_loaded'), 10, 1);
        add_filter('wp', array(&$this, 'wp'), 10, 1);
    }

    function plugins_loaded() {
        add_filter('wpusettingsversion_actions', array(&$this, 'list_actions'), 10, 1);
    }

    public function list_actions($actions) {
        global $wpu_settings_version;
        if (is_object($wpu_settings_version)) {
            # Use a key lower than PHP_INT_MAX
            #$actions[1001] = array(&$this, 'set_plugins');
            #$actions[1002] = array(&$this, 'set_theme');
            #$actions[1003] = array(&$this, 'set_options');
            #$actions[1004] = array(&$this, 'set_menus');
        }
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

        # SEO
        update_option('blogdescription', 'Agence Web');
        update_option('wpu_home_meta_description', 'Agence Web with a longer text.');

        # Social
        update_option('social_instagram_url', 'https://www.instagram.com/wpuprojectid');
        update_option('social_linkedin_url', 'https://www.linkedin.com/wpuprojectid');
        update_option('social_twitter_url', 'https://www.twitter.com/wpuprojectid');

        if (false) {
            $this->set_options__woocommerce();
        }
    }

    function set_options__woocommerce() {

        # - Common
        update_option('woocommerce_store_address', '3, rue du Chat');
        update_option('woocommerce_store_postcode', '75011');
        update_option('woocommerce_store_city', 'Paris');
        update_option('woocommerce_default_country', 'FR');
        update_option('woocommerce_task_list_welcome_modal_dismissed', 'yes');

        # - Taxes
        update_option('woocommerce_calc_taxes', 'yes');
        update_option('woocommerce_currency', 'EUR');
        update_option('woocommerce_prices_include_tax', 'yes');
        update_option('woocommerce_currency_pos', 'right');
        update_option('woocommerce_tax_display_cart', 'incl');
        update_option('woocommerce_tax_display_shop', 'incl');
        update_option('woocommerce_tax_total_display', 'single');

        # - Shipping
        update_option('woocommerce_allowed_countries', 'specific');
        update_option('woocommerce_specific_allowed_countries', array(
            'FR'
        ));

        # - Payment
        update_option('woocommerce_cheque_settings', array(
            'enabled' => 'yes',
            'title' => 'Paiements par chèque',
            'description' => 'Veuillez envoyer un chèque à Nom de la boutique, rue, code postal, ville, pays.',
            'instructions' => ''
        ));
        update_option('woocommerce_bacs_settings', array(
            'enabled' => 'yes',
            'title' => 'Virement bancaire',
            'description' => 'Effectuez le paiement directement depuis votre compte bancaire. Veuillez utiliser l’ID de votre commande comme référence du paiement. Votre commande ne sera pas expédiée tant que les fonds ne seront pas reçus.',
            'instructions' => '',
            'account_details' => '',
            'account_name' => '',
            'account_number' => '',
            'sort_code' => '',
            'bank_name' => '',
            'iban' => '',
            'bic' => ''
        ));
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
function wpu_settings_export_options($id) {
    $opt_value = get_option($id);
    echo "<pre>update_option('" . $id . "', ";
    var_export(get_option($id));
    echo ");</pre>";
}
wpu_settings_export_options('duplicate_post_show_link');
wpu_settings_export_options('duplicate_post_roles');

echo '<pre>$wpu_settings_version->activate_plugins(';
var_export(get_option('active_plugins'));
echo ');</pre>';
die;
/**/
