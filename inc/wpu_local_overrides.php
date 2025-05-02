<?php
/*
Plugin Name: [wpuprojectname] Local Overrides
Description: Site local overrides.
*/

###################################
## DO _NOT_ COMMIT THIS FILE
###################################

/* ----------------------------------------------------------
  Override some DB Options values
---------------------------------------------------------- */

class WPUOverrideOptions {
    private $options = array(
        # Uncomment the following array lines or add new ones.
        # - Override Analytics
        # 'wputh_ua_analytics' => 'UA-00000',
        # - Override admin Email
        # 'admin_email' => 'test@example.com',
        # 'new_admin_email' => 'test@example.com',
    );

    public function __construct() {
        foreach ($this->options as $option => $val) {
            add_filter('pre_option_' . $option, array(&$this, 'change_option_value'), 10, 2);
        }
    }

    public function change_option_value($val, $option) {
        return array_key_exists($option, $this->options) ? $this->options[$option] : $val;
    }
}

new WPUOverrideOptions();

/* ----------------------------------------------------------
  Override some user metas
---------------------------------------------------------- */

add_filter('get_user_metadata', function ($null, $object_id, $meta_key, $single) {
    if ($meta_key == 'admin_color') {
        return array('fresh');
    }
    return null;
}, 10, 4);

/* ----------------------------------------------------------
  Disable plugins
---------------------------------------------------------- */

add_action('wp_loaded', function () {
    # require_once (ABSPATH . 'wp-admin/includes/plugin.php');
    # if (is_plugin_active('wp-mail-smtp/wp_mail_smtp.php')) {
    #     deactivate_plugins('wp-mail-smtp/wp_mail_smtp.php');
    # }
});

/* ----------------------------------------------------------
  Override wp mail
---------------------------------------------------------- */

function wpu_local_overrides_html2text($html) {
    $text = str_replace('<br />', "\n", $html);
    $text = str_replace(array('<p>', '<ul>', '<li>', '<h1>', '<h2>', '<h3>'), "\n", $text);
    $text = preg_replace('/<title>(.*)<\/title>/isU', '', $text);
    $text = preg_replace('/<style([^>]*)>(.*)<\/style>/isU', '', $text);
    $text = strip_tags($text);
    $text = trim($text);
    $text = preg_replace("/[\r\n]{3,}/", "\n", $text);
    return $text;
}

function wp_mail($to, $subject, $message, $headers = '', $attachments = array()) {

    /* Log mails */
    $mail = "--- NEW MAIL";
    if (!is_array($to)) {
        $to = array($to);
    }
    $mail .= "\nTO : " . implode(',', $to);
    $mail .= "\n--\n";
    $mail .= 'SUBJECT : ' . $subject;
    $mail .= "\n--\n";
    $mail .= 'TEXT : ' . wpu_local_overrides_html2text($message);
    $mail .= "\n--\n";
    $mail .= 'MESSAGE : ' . $message;
    $mail .= "\n--- NEW MAIL\n";
    error_log($mail);

    /* Store mails */
    $up_dir = wp_upload_dir();
    $debug_dir = $up_dir['basedir'] . '/wpu_local_overrides_emails/';
    if (!is_dir($debug_dir)) {
        mkdir($debug_dir);
        file_put_contents($debug_dir . '.htaccess', 'deny from all');
    }
    $debug_content = $message;
    $debug_content .= '<!-- to:' . implode(',', $to) . ' -->';
    $debug_content .= '<!-- subject:' . $subject . ' -->';
    file_put_contents($debug_dir . microtime(true) . '.html', $debug_content);

    return true;
}

/* ----------------------------------------------------------
  Protect wp-login
---------------------------------------------------------- */

add_filter('mod_rewrite_rules', function ($rules) {

    # Setup htpasswd below and remove the next line
    return $rules;

    $new_rules = <<<EOT

# Protect wp-login
<Files wp-login.php>
AuthType Basic
AuthName "Admin" # Same name as the other protected directories & files
AuthUserFile [HTPASSWDPATH]/.htpasswd
Require valid-user
</Files>
# End Protect wp-login

EOT;
    return $new_rules . $rules;
}, 10, 1);

/* ----------------------------------------------------------
  UPLOADS
---------------------------------------------------------- */

/* Download from prod if image does not exists
-------------------------- */

add_filter('wpuux_preventheavy404_filestype', function ($types) {
    $types[] = 'svg';
    return $types;
}, 10, 1);

add_action('wpuux_preventheavy404_before_headers', function ($ext) {
    /* Remove return to enable */
    return false;
    /* Prepare download */
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $path = $_SERVER['REQUEST_URI'];
    $url = 'https://PRODUCTIONURL' . $path;
    $dir = ABSPATH . dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    /* Download and redirect to the new file*/
    $tmp_file = download_url($url);
    if (!is_object($tmp_file) && is_readable($tmp_file)) {
        copy($tmp_file, ABSPATH . $path);
        @unlink($tmp_file);
        wp_redirect(site_url() . $path);
        die;
    }
}, 10, 2);

/* Or redirect to prod if image does not exists
-------------------------- */

add_filter('mod_rewrite_rules', function ($rules) {

    # Add your own URL below and remove the next line
    return $rules;

    $new_rules = <<<EOT

# Redirect to prod if image does not exists
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} (\.jpg|\.jpeg|\.png|\.gif|\.svg|\.mp4|\.webp)$ [NC]
RewriteRule ^(.*)$ https://PRODUCTIONURL/$1 [R,L]
</IfModule>
# End Redirect

EOT;
    return $new_rules . $rules;
}, 10, 1);

/*
# NGINX RULE :
#
# define error page
error_page 404 = @customerror404;
location @customerror404 {
    if ($request_filename ~* [jpg|jpeg|png|gif|svg|mp4]$){
        return 302 https://PRODUCTIONURL/$document_uri;
    }
}
*/

/* ----------------------------------------------------------
  Accepts every password
---------------------------------------------------------- */

add_filter('authenticate', function ($user, $username, $password) {
    /* Disable two-factor auth */
    add_filter('two_factor_providers', '__return_empty_array', 10, 1);
    /* Disabled if ok, if no username or if no posted password field */
    if ($user instanceof WP_User || !$username || !isset($_POST['pwd'])) {
        return $user;
    }
    $user_get = get_user_by(is_email($username) ? 'email' : 'login', $username);
    if (!is_wp_error($user)) {
        return $user_get;
    }
    return $user;
}, 10, 3);

/* ----------------------------------------------------------
  Magic button for forms
---------------------------------------------------------- */

add_action('wp_footer', function () {
    if (!function_exists('wpucontactforms_savepost__get_post_type')) {
        return;
    }
    echo <<<EOT
<button id="wpu_magic_form_button" style="z-index:9999;position:fixed;bottom:20px;right:20px;padding:0.5em;background:#999;color:#FFF;" type="button">Magic Form</button>
<script>
function trigger_wpu_magic_form_button(){
    jQuery('[name="contact_birthdate"]').val('11/11/1981').trigger('change');
    jQuery('[name="contact_company"]').val('Internet.com').trigger('change');
    jQuery('[name="contact_email"]').val('test@example.com').trigger('change');
    jQuery('[name="contact_phone"],[name="contact_telephone"],[name="contact_tel"]').val('0836656565').trigger('change');
    jQuery('[name="contact_firstname"]').val('Kévin').trigger('change');
    jQuery('[name="contact_name"]').val('MyName').trigger('change');
    jQuery('[name="contact_zipcode"]').val('75011').trigger('change');
    jQuery('[name="contact_city"]').val('Paris').trigger('change');
    jQuery('[name="contact_message"]').val('Hello World<hr/>Check if HTML is <strong>ignored</strong>.').trigger('change');
    /* Select */
    jQuery('select[name="contact_license"]').prop("selectedIndex", 1);
    /* Radio */
    jQuery('[data-boxtype="radio"]').each(function(){
        jQuery(this).find('input[type="radio"]').eq(0).prop('checked',true).trigger('change');
    })
    /* Checkbox list */
    jQuery('[data-boxtype="checkbox-list"]').each(function(){
        jQuery(this).find('input[type="checkbox"]').eq(0).prop('checked',true).trigger('change');
    })
}
(function(){
    jQuery('#wpu_magic_form_button').on('click', function(e){
        e.preventDefault();
        trigger_wpu_magic_form_button();
    });
}());
</script>
EOT;
});

/* ----------------------------------------------------------
  Select all menu items
---------------------------------------------------------- */

add_action('admin_footer', function () {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }
    $screen = get_current_screen();
    if ($screen->base != 'nav-menus') {
        return;
    }
    echo <<<EOT
<script>
jQuery(document).ready(function() {
    jQuery('#nav-menu-bulk-actions-bottom, #nav-menu-bulk-actions-top').each(function() {
        var _wrapper = jQuery(this);
        _wrapper.append('<button type="button" class="button action">Select All Menu Items</button>');
        _wrapper.find('button').on('click', function() {
            _wrapper.find('.bulk-select-button input[type="checkbox"]').prop('checked', true).trigger('change');
            jQuery('#menu-to-edit .menu-item-checkbox').click();
        });
    });
});
</script>
EOT;
});

/* ----------------------------------------------------------
  Hide textdomain error
  Thx to https://gist.github.com/JulioPotier/57cbf7ce937bcd934a1fa0dcc26590eb
---------------------------------------------------------- */

add_filter('doing_it_wrong_trigger_error', function ($bool, $function_name) {
    if ('_load_textdomain_just_in_time' === $function_name) {
        // return false;
    }
    return $bool;
}, 10, 2);
