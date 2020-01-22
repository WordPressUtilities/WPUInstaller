<?php
/*
Plugin Name: [wpuproject] Local Overrides
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
  Override some 404 for images
---------------------------------------------------------- */

add_filter('rewrite_rules',function ($rules){

    # Add your own URL below and remove the next line
    return $rules;

    $new_rules = <<<EOT

# Redirect to prod if image does not exists
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} (\.jpg|\.png|\.gif)$ [NC]
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
