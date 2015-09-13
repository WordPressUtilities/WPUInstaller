#!/bin/bash

###################################
## Set .htaccess v 0.1
###################################

echo '### Set htaccess - performance';

cp "${MAINDIR}WPUtilities/wp-content/.htaccess" "${MAINDIR}wp-content/.htaccess";

echo '### Set htaccess - permalinks';

echo "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress" >> "${MAINDIR}.htaccess";

echo '### Update permalinks';

php "${MAINDIR}wp-cli.phar" rewrite flush;
