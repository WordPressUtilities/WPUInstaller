#!/bin/bash

###################################
## Plugins installation
###################################

echo '### Plugins installation';

for i in $WPU_SUBMODULE_PLUGINS
do
    wpuinstaller_install_plugin "${i}";
    php ${WPU_PHPCLI} plugin activate "${i}";
done;

# Commit Add plugins
bashutilities_commit_all "Installation - Plugins";

# Polylang
if [[ $project_l10n == 'y' ]]; then
    echo "## Install Polylang";
    php ${WPU_PHPCLI} plugin install polylang --activate

    echo "## Install PLL Utilities";
    wpuinstaller_install_mu "wpu_pll_utilities";

    echo "## Install Lang settings";
    _lang_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_lang.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/lang/base_lang.php" "${_lang_file}";

    # Commit plugin
    bashutilities_commit_all "Installation - Plugin : Polylang";
fi;

# Woocommerce
if [[ $is_woocommerce == 'y' ]]; then
    echo "## Install WooCommerce";
    php ${WPU_PHPCLI} plugin install woocommerce --activate

    # Commit plugin
    bashutilities_commit_all "Installation - Plugin : WooCommerce";
fi;

# ACF
if [[ $need_acf == 'y' ]];then
    echo "## Install ACF";
    php ${WPU_PHPCLI} plugin install "http://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=${acf_api_key}" --activate;
    php ${WPU_PHPCLI} plugin install acf-extended --activate;
    wpuinstaller_install_mu "wpu_acf_flexible";

    _WPUINSTALLER_BLOCKSDIR="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/blocks/";

    # Add blocks
    _blocks_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_blocks.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/blocks/base_blocks.php" "${_blocks_file}";

    # Add blocks sub items
    _blocks_subitems_path="${SCRIPTDIR}inc/blocks/blocks/";
    for filename in "${_blocks_subitems_path}"*.php; do
        _filename=${filename/"${_blocks_subitems_path}base_"/}
        _blocks_file="${_WPUINSTALLER_BLOCKSDIR}/${project_id}_${_filename}";
        wpuinstaller_cp_replace "${_blocks_subitems_path}base_${_filename}" "${_blocks_file}";
    done

    if [[ "${need_acf_forms}" == 'y' ]];then
        _blocks_file="${_WPUINSTALLER_BLOCKSDIR}/${project_id}_blockforms.php";
        wpuinstaller_cp_replace "${SCRIPTDIR}inc/blocks/base_blockforms.php" "${_blocks_file}";
    fi;

    # Commit plugin
    bashutilities_commit_all "Installation - Plugin : ACF";
fi;

# Filters
if [[ "${need_advanced_filters}" == 'y' ]];then
    wpuinstaller_install_plugin "wpulivesearch";

    _plugin_filters_settings="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_filters.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_filters.php" "${_plugin_filters_settings}";

    bashutilities_commit_all "Installation - Plugin : WPU Live Search";
fi;

# Forms
if [[ "${need_acf_forms}" == 'y' || "${need_contact_form}" == 'y' ]]; then
    wpuinstaller_install_plugin "wpucontactforms";
    bashutilities_commit_all "Installation - Plugin : WPU Contact forms";
fi;

# Search
if [[ "${need_search}" == 'y' ]];then
    php ${WPU_PHPCLI} plugin install relevanssi --activate;
    _plugin_search_settings="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_search.php";
    wpuinstaller_cp_replace "${SCRIPTDIR}inc/base_search.php" "${_plugin_search_settings}";

    bashutilities_commit_all "Installation - Plugin : Relevanssi";
fi

# Recommended
if [[ $install_recommended_plugins == 'y' ]]; then
    echo "## Install recommended plugins";
    php ${WPU_PHPCLI} plugin install limit-login-attempts-reloaded --activate;
    php ${WPU_PHPCLI} plugin install safe-svg --activate;
    php ${WPU_PHPCLI} plugin install taxonomy-terms-order --activate;
    php ${WPU_PHPCLI} plugin install two-factor --activate;

    # No activation
    php ${WPU_PHPCLI} plugin install duplicate-post;
    php ${WPU_PHPCLI} plugin install query-monitor;
    php ${WPU_PHPCLI} plugin install redirection;
    php ${WPU_PHPCLI} plugin install w3-total-cache;

    # Commit plugin
    bashutilities_commit_all "Installation - Recommended Plugins";
fi;

# Local plugins
if [[ -d "${SCRIPTDIR}local/plugins/" ]];then
    rsync -rv "${SCRIPTDIR}local/plugins/" "${MAINDIR}${WP_PLUGINS_DIR}/";
    bashutilities_commit_all "Installation - Local Plugins";
fi;

# Activation
echo "## Plugin Activation";
_plugins_list=$(php ${WPU_PHPCLI} option get active_plugins);
if [[ "${_plugins_list}" == '' ]];then
    _plugins_list='array()';
fi;
_plugin_settings_file="${MAINDIR}${WP_MUPLUGINS_DIR}${project_id}/${project_id}_settings.php";
methods_string=$(cat <<EOF
    private \$plugins_list=${_plugins_list};
EOF
);
bashutilities_add_after_marker '#plugins_list' "${methods_string}" "${_plugin_settings_file}";
bashutilities_sed "s/    #plugins_list//g" "${_plugin_settings_file}";

bashutilities_commit_all "Installation - Plugin Activation";

# Language
echo "## Update language";
php ${WPU_PHPCLI} language plugin install --all "${WP_LOCALE}";
php ${WPU_PHPCLI} language plugin update --all;

bashutilities_commit_all "Installation - Plugin Lang";
