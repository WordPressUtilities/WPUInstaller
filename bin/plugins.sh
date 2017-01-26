#!/bin/bash

###################################
## Plugins installation
###################################

echo '### Plugins installation';


for i in $WPU_SUBMODULE_PLUGINS
do
    echo "## Install ${i}";
    cd "${MAINDIR}${WP_PLUGINS_DIR}";
    if [[ $use_submodules == 'y' ]]; then
        git submodule --quiet add "https://github.com/WordPressUtilities/${i}.git";
    else
        git clone --quiet "https://github.com/WordPressUtilities/${i}.git";
        rm -rf "${i}/.git";
    fi;
    cd "${MAINDIR}";
    php ${WPU_PHPCLI}  plugin activate "${i}";
done;

if [[ $project_l10n == 'y' ]]; then
    echo "## Install Qtranslate X";
    php ${WPU_PHPCLI}  plugin install qtranslate-x --activate
    php ${WPU_PHPCLI}  option update qtranslate_default_language 'fr';
    php ${WPU_PHPCLI}  option update qtranslate_enabled_languages '["fr","en"]' --format=json;
fi;

# Commit Add plugins
git add -A
git commit -m "Installation - Plugins" --quiet;
