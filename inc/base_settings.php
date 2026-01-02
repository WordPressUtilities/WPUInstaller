<?php
/*
Plugin Name: [wpuprojectname] Settings
Description: Site Auto Settings
*/

class wpuprojectid_settings {

    #plugins_list

    private $network_plugins_list = array(
    );

    public function __construct() {
        add_filter('plugins_loaded', array(&$this, 'plugins_loaded'), 10, 1);
        add_filter('wp', array(&$this, 'wp'), 10, 1);
    }

    function plugins_loaded() {
        add_filter('wpusettingsversion_actions', array(&$this, 'list_actions'), 10, 1);
    }

    public function list_actions($actions) {
        global $wpu_settings_version;

        /* If network plugins are listed, do not continue until multisite is configured */
        if (!empty($this->network_plugins_list) && !is_multisite()) {
            error_log('[wpuprojectid_settings] Network plugins are listed but multisite is not configured.');
            return;
        }

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
        if (is_object($wpu_settings_version) && method_exists($wpu_settings_version, 'force_home_page_id')) {
            $wpu_settings_version->force_home_page_id();
        }
    }

    public function set_plugins() {
        global $wpu_settings_version;
        if (is_object($wpu_settings_version) && method_exists($wpu_settings_version, 'activate_plugins')) {
            $wpu_settings_version->activate_plugins($this->plugins_list);
            $wpu_settings_version->activate_plugins($this->network_plugins_list, true);
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
        /* WordPress */
        update_option('start_of_week', '1');
        update_option('date_format', 'j F Y');
        update_option('time_format', 'H:i');
        update_option('timezone_string', 'Europe/Paris');
        update_option('permalink_structure', '/%postname%/');

        /* Pages */
        update_option('page_on_front', get_option('home__page_id'));
        update_option('show_on_front', 'page');

        /* Pager */
        update_option('posts_per_page', 12);

        /* SEO */
        update_option('blogdescription', 'Agence Web');
        $meta_desc = 'Agence Web with a longer text.';
        update_option('wpu_home_meta_description', $meta_desc);
        # update_option('fr___wpu_home_meta_description', $meta_desc);
        # update_option('en___wpu_home_meta_description', $meta_desc);

        /* Social */
        update_option('social_instagram_url', 'https://www.instagram.com/wpuprojectid');
        update_option('social_linkedin_url', 'https://www.linkedin.com/wpuprojectid');
        update_option('social_twitter_url', 'https://www.twitter.com/wpuprojectid');

        /* Images */
        update_option('thumbnail_size_w', 150);
        update_option('thumbnail_size_h', 150);
        update_option('medium_size_w', 650);
        update_option('medium_size_h', 0);
        update_option('medium_large_size_w', 920);
        update_option('medium_large_size_h', 0);
        update_option('large_size_w', 1390);
        update_option('large_size_h', 0);
        update_option('image_default_align', 'center');
        update_option('image_default_size', 'large');

        /* Plugin : Limit Login Attempts*/
        update_option('limit_login_show_warning_badge', '0');
        update_option('limit_login_hide_dashboard_widget', '1');
        update_option('limit_login_review_notice_shown', '1');
        update_option('limit_login_onboarding_popup_shown', '1');
        update_option('limit_login_show_top_bar_menu_item', '0');
        update_option('limit_login_show_top_level_menu_item', '0');
        update_option('limit_login_blacklist_usernames', array(
            'admin'
        ));

        /* Happyfiles Pro */
        update_option('happyfiles_featured_image_quick_edit', '1');
        update_option('happyfiles_folder_access', array(
            'editor' => 'full',
            'author' => '',
            'contributor' => '',
            'subscriber' => '',
            'super_editor' => 'full'
        ));

        /* WPU Action Logs */
        update_option('wpuactionlogs_options', array(
            'action__posts' => '1',
            'action__wp_update_nav_menu' => '1',
            'action__terms' => '1',
            'action__options' => '1',
            'action__mails' => '1',
            'action__users' => '1',
            'action__plugins' => '1',
            'extras__display_active_users' => '1',
            'min_capability_log' => 'read'
        ));

        /* Plugin : Duplicate Post */
        update_option('duplicate_post_show_notice', 0);
        update_option('duplicate_post_show_link', array(
            'new_draft' => '1'
        ));
        update_option('duplicate_post_types_enabled', array(
            'post',
            'page'
        ));
        update_option('duplicate_post_roles', array(
            'administrator',
            'editor',
            'super_editor'
        ));

        /* Plugin : Polylang */
        update_option('pll_dismissed_notices', array(
            'wizard',
            'review'
        ));

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
function wpu_settings_export_options($id, $key = false) {
    $opt_value = get_option($id);
    if ($key && isset($opt_value[$key])) {
        $opt_value = $opt_value[$key];
        echo "<pre>\$wpu_settings_version->add_args_to_option('" . $id . "', array(\n";
        echo "'$key' => " ;
        var_export($opt_value);
        echo "\n);</pre>";
    } else {
        echo "<pre>update_option('" . $id . "', ";
        var_export($opt_value);
        echo ");</pre>";
    }
}
wpu_settings_export_options('duplicate_post_show_link');
wpu_settings_export_options('duplicate_post_roles');

echo '<pre>$wpu_settings_version->activate_plugins(';
var_export(get_option('active_plugins'));
echo ');</pre>';

echo '<pre>$wpu_settings_version->activate_plugins(';
var_export(array_keys(get_site_option('active_sitewide_plugins', array())));
echo ', true);</pre>';
die;
/**/
