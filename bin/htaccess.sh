#!/bin/bash

###################################
## Set .htaccess
###################################

echo '### Set htaccess - performance';

cp "${MAINDIR}WPUtilities/wp-content/.htaccess" "${MAINDIR}wp-content/.htaccess";

# Commit Add htaccess
bashutilities_commit_all "Installation - content htaccess";

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

php ${WPU_PHPCLI} rewrite flush;
