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
    wpuinstaller_install_mu "wpu_pll_utilities";

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Plugin : Polylang" --quiet;
fi;

# Woocommerce
if [[ $is_woocommerce == 'y' ]]; then
    echo "## Install WooCommerce";
    php ${WPU_PHPCLI} plugin install woocommerce --activate

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Plugin : WooCommerce" --quiet;
fi;

# ACF
if [[ $need_acf == 'y' ]];then
    echo "## Install ACF";
    php ${WPU_PHPCLI} plugin install "http://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=${acf_api_key}" --activate;
    php ${WPU_PHPCLI} plugin install acf-extended --activate;
    wpuinstaller_install_mu "wpu_acf_flexible";

    # Add blocks
    _blocks_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_blocks.php";
    cp "${SCRIPTDIR}inc/base_blocks.php" "${_blocks_file}";
    wpuinstaller_replace "${_blocks_file}";

    # Add masterheader
    _masterheader_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/blocks/${project_id}_masterheader.php";
    cp "${SCRIPTDIR}inc/base_masterheader.php" "${_masterheader_file}";
    wpuinstaller_replace "${_masterheader_file}";

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Plugin : ACF" --quiet;
fi;

# Forms
if [[ "${need_acf_forms}" == 'y' || "${need_contact_form}" == 'y' ]]; then
    cd "${MAINDIR}${WP_PLUGINS_DIR}";
    bashutilities_submodule_or_install "https://github.com/WordPressUtilities/wpucontactforms.git" "${use_submodules}";
    cd "${MAINDIR}";
    git add -A
    git commit --no-verify -m "Installation - Plugin : WPU Contact forms" --quiet;
fi;

# WP Rocket
if [[ "${need_wprocket}" == 'y' ]];then
    wpuinstaller_github_plugin wp-media wp-rocket;
    git add -A
    git commit --no-verify -m "Installation - Plugin : WP Rocket" --quiet;
fi;

# Search
if [[ "${need_search}" == 'y' ]];then
    php ${WPU_PHPCLI} plugin install relevanssi --activate;
    _plugin_search_settings="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_search.php";
    cp "${SCRIPTDIR}inc/base_search.php" "${_plugin_search_settings}";
    wpuinstaller_replace "${_plugin_search_settings}";

    git add -A
    git commit --no-verify -m "Installation - Plugin : Relevanssi" --quiet;
fi

# Recommended
if [[ $install_recommended_plugins == 'y' ]]; then
    echo "## Install recommended plugins";
    php ${WPU_PHPCLI} plugin install limit-login-attempts-reloaded --activate;
    php ${WPU_PHPCLI} plugin install safe-svg --activate;
    php ${WPU_PHPCLI} plugin install taxonomy-terms-order --activate;

    # No activation
    php ${WPU_PHPCLI} plugin install duplicate-post;
    php ${WPU_PHPCLI} plugin install google-sitemap-generator;
    php ${WPU_PHPCLI} plugin install query-monitor;
    php ${WPU_PHPCLI} plugin install redirection;

    # Commit plugin
    git add -A
    git commit --no-verify -m "Installation - Recommended Plugins" --quiet;
fi;

# Activation
echo "## Plugin Activation";
_plugins_list=$(php ${WPU_PHPCLI} option get active_plugins);
_plugin_settings_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
methods_string=$(cat <<EOF
    private \$plugins_list=${_plugins_list};
EOF
);
bashutilities_add_after_marker '#plugins_list' "${methods_string}" "${_plugin_settings_file}";
bashutilities_sed "s/    #plugins_list//g" "${_plugin_settings_file}";

git add -A
git commit --no-verify -m "Installation - Plugin Activation" --quiet;

# Language
echo "## Update language";
php ${WPU_PHPCLI} language plugin install --all "${WP_LOCALE}";
php ${WPU_PHPCLI} language plugin update --all;

git add -A
git commit --no-verify -m "Installation - Plugin Lang" --quiet;
