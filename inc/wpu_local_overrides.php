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

function wpu_local_overrides_html2text($html){
    $text = str_replace('<br />',"\n", $html);
    $text = str_replace(array('<p>','<ul>','<li>','<h1>','<h2>','<h3>'),"\n", $text);
    $text = preg_replace('/<title>(.*)<\/title>/isU','',$text);
    $text = preg_replace('/<style([^>]*)>(.*)<\/style>/isU','',$text);
    $text = strip_tags($text);
    $text = trim($text);
    $text = preg_replace("/[\r\n]{3,}/", "\n", $text);
    return $text;
}

function wp_mail($to, $subject, $message, $headers = '', $attachments = array()) {
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
    return true;
}

/* ----------------------------------------------------------
  Override some 404 for images
---------------------------------------------------------- */

add_filter('mod_rewrite_rules',function ($rules){

    # Add your own URL below and remove the next line
    return $rules;

    $new_rules = <<<EOT

# Redirect to prod if image does not exists
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} (\.jpg|\.jpeg|\.png|\.gif)$ [NC]
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
    if ($request_filename ~* [jpg|png|gif]$){
        return 302 https://PRODUCTIONURL/$document_uri;
    }
}
*/

/* ----------------------------------------------------------
  Accepts every password
---------------------------------------------------------- */

add_filter('authenticate', function ($user, $username, $password) {
    /* Disable if ok, if no username or if no posted password field */
    if ($user instanceof WP_User || !$username || !isset($_POST['pwd'])) {
        return $user;
    }
    $user_get = get_user_by('login', $username);
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
<button id="wpu_magic_form_button" style="z-index:999;position:fixed;bottom:20px;right:20px;padding:0.5em" type="button">Magic Form</button>
<script>
function trigger_wpu_magic_form_button(){
    jQuery('[name="contact_birthdate"]').val('11/11/1981').trigger('change');
    jQuery('[name="contact_company"]').val('Internet.com').trigger('change');
    jQuery('[name="contact_email"]').val('test@example.com').trigger('change');
    jQuery('[name="contact_phone"],[name="contact_telephone"]').val('test@example.com').trigger('change');
    jQuery('[name="contact_firstname"]').val('Kevin').trigger('change');
    jQuery('[name="contact_name"]').val('MyName').trigger('change');
    jQuery('select[name="contact_license"]').prop("selectedIndex", 1);
    jQuery('[name="contact_zipcode"]').val('75011').trigger('change');
    jQuery('[name="contact_city"]').val('Paris').trigger('change');
    jQuery('[name="contact_message"]').val('Hello World').trigger('change');
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
