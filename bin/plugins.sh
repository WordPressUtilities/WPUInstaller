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
    php ${WPU_PHPCLI} plugin activate "${i}";
done;

# Commit Add plugins
git add -A
git commit --no-verify -m "Installation - Plugins" --quiet;

# Qtranslate
if [[ $project_l10n == 'y' ]]; then
    if [[ $project_l10n_tool == 'qtranslate' ]]; then
        echo "## Install Qtranslate X";
        php ${WPU_PHPCLI} plugin install qtranslate-x --activate
        php ${WPU_PHPCLI} option update qtranslate_default_language 'fr';
        php ${WPU_PHPCLI} option update qtranslate_enabled_languages '["fr","en"]' --format=json;

        # Commit plugin
        git add -A
        git commit --no-verify -m "Installation - Plugin : Qtranslate X" --quiet;
    fi;
    if [[ $project_l10n_tool == 'polylang' ]]; then
        echo "## Install Polylang";
        php ${WPU_PHPCLI} plugin install polylang --activate

        # Commit plugin
        git add -A
        git commit --no-verify -m "Installation - Plugin : Polylang" --quiet;
    fi;
fi;

# Woocommerce
if [[ $is_woocommerce == 'y' ]]; then
    echo "## Install Woocommerce";
    php ${WPU_PHPCLI} plugin install woocommerce --activate

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Plugin : Woocommerce" --quiet;
fi;

# Recommended
if [[ $install_recommended_plugins == 'y' ]]; then
    echo "## Install recommended plugins";
    php ${WPU_PHPCLI} plugin install limit-login-attempts --activate;
    php ${WPU_PHPCLI} plugin install health-check --activate;

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Recommended Plugins" --quiet;
fi;

# Language
echo "## Update language";
php ${WPU_PHPCLI} language core update;
