# wpuproject

## Tools

- wp-cli must be installed : https://wp-cli.org/fr/

## How to install

### Clone

- Create a dedicated root project folder.
- Create a `logs` directory in the root folder.
- Clone this project in the root folder and go to the cloned folder.
- Ensure all submodules are loaded : `git submodule update --init --recursive;` (do it everytime you change branch)

### Database
- Setup your favorite local LAMP/WAMP/MAMP to target the root of the project (the folder containing wp-config-sample.php)
- Create a MySQL database and note the login & base. (On this project, the table prefix is `wpumysqlprefix`)
- Import your MySQL dump on this database.

### Setup
- Trigger the WordPress install from the front-end, pointing to the previous database.
- In the admin, don't forget to refresh permalinks (hitting save on the Settings / Permalinks page)
- Replace admin email or forms email, or disable wp-mail-smtp to avoid sending test emails.

### Config

In your wp-config.php :

Add this code to force you local host name (dont forget the trailing slash) :
```php
define('WP_SITEURL', 'http://wpuprojectid.test/');
define('WP_HOME', 'http://wpuprojectid.test/');
```

Change the WP_DEBUG value to true
```php
define('WP_DEBUG', true);
```

Add this code to allow debugging. Logs will appear in the `logs` folder previously created.
```php
if (WP_DEBUG) {
    @ini_set('display_errors', 0);
    if (!defined('WP_DEBUG_LOG')) { define('WP_DEBUG_LOG', dirname(__FILE__) . '/../logs/debug-' . date('Ymd') . '.log'); }
    if (!defined('WP_DEBUG_DISPLAY')) { define('WP_DEBUG_DISPLAY', false); }
    if (!defined('SAVEQUERIES')) { define('SAVEQUERIES', (php_sapi_name() !== 'cli')); }
}
```

## How to update

- First, you can load the latest version of every git submodule : `git submodule foreach git pull`;
- Next, just update plugins, core & language files from the WordPress back-office.
- Lastly, dont forget to recompile the assets if some theme dependencies were updated.

## Plugins

- Query Monitor should not be active in a prod environment, but it will provide useful info on your local env if you're logged in.
- WPU Admin Protect adds a lot of rules in the htaccess to protect the website.

## Front-end

The README for the Front-End part should be in `wp-content/themes/wpuprojectid`.

## Notes

- Dont edit the files inside a submodule, they are dependencies and your changes will not be saved.
- The mu-plugins are automatically loaded in alphabetical order. There is a main mu-plugin which allow recursive loading (not the default WordPress behavior)
- You can create a file named wp-content/mu-plugins/wpu_local_overrides.php which will not be tracked, and add some hacks inside to test a new feature.
