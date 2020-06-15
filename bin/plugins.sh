#!/bin/bash

###################################
## Plugins installation
###################################

echo '### Plugins installation';

for i in $WPU_SUBMODULE_PLUGINS
do
    echo "## Install ${i}";
    cd "${MAINDIR}${WP_PLUGINS_DIR}";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/${i}.git" "${use_submodules}";
    cd "${MAINDIR}";
    php ${WPU_PHPCLI} plugin activate "${i}";
done;

# Commit Add plugins
git add -A
git commit --no-verify -m "Installation - Plugins" --quiet;

# Polylang
if [[ $project_l10n == 'y' ]]; then
    echo "## Install Polylang";
    php ${WPU_PHPCLI} plugin install polylang --activate

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Plugin : Polylang" --quiet;
fi;

# Woocommerce
if [[ $is_woocommerce == 'y' ]]; then
    echo "## Install Woocommerce";
    php ${WPU_PHPCLI} plugin install woocommerce --activate

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Plugin : Woocommerce" --quiet;
fi;

# ACF
if [[ $need_acf == 'y' ]];then
    echo "## Install ACF";
    wp plugin install "http://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=${acf_api_key}" --activate;
    wp plugin install acf-extended --activate;
    cd "${MAINDIR}${WP_MUPLUGINS_DIR}wpu";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/wpu_acf_flexible.git" "${use_submodules}";
    cd "${MAINDIR}";
fi;

# Recommended
if [[ $install_recommended_plugins == 'y' ]]; then
    echo "## Install recommended plugins";
    php ${WPU_PHPCLI} plugin install limit-login-attempts --activate;
    php ${WPU_PHPCLI} plugin install classic-editor --activate;

    # No activation
    php ${WPU_PHPCLI} plugin install query-monitor;
    php ${WPU_PHPCLI} plugin install google-sitemap-generator;

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Recommended Plugins" --quiet;
fi;

# Language
echo "## Update language";
php ${WPU_PHPCLI} language plugin install --all "${WP_LOCALE}";
php ${WPU_PHPCLI} language plugin update --all;

git add -A
git commit --no-verify -m "Installation - Plugin Lang" --quiet;
