#!/bin/bash

###################################
## Plugins installation
###################################

echo '### Plugins installation';

cd "${MAINDIR}${WP_PLUGINS_DIR}";

for i in $WPU_SUBMODULE_PLUGINS
do
    echo "## Install ${i}";
    git submodule --quiet add "https://github.com/WordPressUtilities/${i}.git";
    php "${MAINDIR}wp-cli.phar" plugin activate "${i}";
done;

if [[ $project_l10n == 'y' ]]; then
    echo "## Install Qtranslate X";
    php "${MAINDIR}wp-cli.phar" plugin install qtranslate-x --activate
    php "${MAINDIR}wp-cli.phar" option update qtranslate_default_language 'fr';
    php "${MAINDIR}wp-cli.phar" option update qtranslate_enabled_languages '["fr","en"]' --format=json;
fi;

# Commit Add plugins
git add -A
git commit -m "Installation - Plugins" --quiet;
