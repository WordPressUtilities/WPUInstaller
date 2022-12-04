#!/bin/bash

###################################
## Generate install template
###################################

_install_tpl="../wpuinstaller-tpl.sh";
if [[ ! -f "${_install_tpl}" ]];then

_tpl_content=$(cat <<EOF
#!/bin/bash

# Project
wpuinstaller_init__project_name="${project_name}";
wpuinstaller_init__project_id="${project_id}";
wpuinstaller_init__project_dev_url="${project_dev_url}";
wpuinstaller_init__email_address="${email_address}";

# MySQL
wpuinstaller_init__mysql_host="${mysql_host}";
wpuinstaller_init__mysql_user="${mysql_user}";
wpuinstaller_init__mysql_password="${mysql_password}";
wpuinstaller_init__mysql_prefix="${mysql_prefix}";
wpuinstaller_init__mysql_database="${mysql_database}";

# Tech
wpuinstaller_init__use_submodules="${use_submodules}";
wpuinstaller_init__use_subfolder="${use_subfolder}";
wpuinstaller_init__project_l10n="${project_l10n}";
wpuinstaller_init__need_extranet="${need_extranet}";
wpuinstaller_init__wpu_add_shell_scripts="${wpu_add_shell_scripts}";
wpuinstaller_init__use_external_api="${use_external_api}";
wpuinstaller_init__use_code_tests="${use_code_tests}";

# Theme
wpuinstaller_init__need_theme="${need_theme}";
wpuinstaller_init__use_intestarter="${use_intestarter}";
wpuinstaller_init__home_is_cms="${home_is_cms}";
wpuinstaller_init__has_attachment_tpl="${has_attachment_tpl}";
wpuinstaller_init__need_advanced_menus="${need_advanced_menus}";
wpuinstaller_init__need_404_page="${need_404_page}";

# Features
wpuinstaller_init__is_woocommerce="${is_woocommerce}";
wpuinstaller_init__install_recommended_plugins="${install_recommended_plugins}";
wpuinstaller_init__need_advanced_filters="${need_advanced_filters}";
wpuinstaller_init__need_contact_form="${need_contact_form}";
wpuinstaller_init__wpu_submodules_muplugins_ok="${WPU_SUBMODULES_MUPLUGINS_OK}";
wpuinstaller_init__need_posts_tpl="${need_posts_tpl}";
wpuinstaller_init__need_acf="${need_acf}";
wpuinstaller_init__need_acf_forms="${need_acf_forms}";
wpuinstaller_init__acf_api_key="${acf_api_key}";
EOF
);

echo "${_tpl_content}" > ${_install_tpl};

fi;

unset wpuinstaller_init__project_name;
unset wpuinstaller_init__project_id;
unset wpuinstaller_init__project_dev_url;
unset wpuinstaller_init__email_address;
unset wpuinstaller_init__mysql_host;
unset wpuinstaller_init__mysql_user;
unset wpuinstaller_init__mysql_password;
unset wpuinstaller_init__mysql_prefix;
unset wpuinstaller_init__mysql_database;
unset wpuinstaller_init__use_submodules;
unset wpuinstaller_init__use_subfolder;
unset wpuinstaller_init__project_l10n;
unset wpuinstaller_init__need_extranet;
unset wpuinstaller_init__wpu_add_shell_scripts;
unset wpuinstaller_init__use_external_api;
unset wpuinstaller_init__use_code_tests;
unset wpuinstaller_init__need_theme;
unset wpuinstaller_init__use_intestarter;
unset wpuinstaller_init__home_is_cms;
unset wpuinstaller_init__has_attachment_tpl;
unset wpuinstaller_init__need_advanced_menus;
unset wpuinstaller_init__need_404_page;
unset wpuinstaller_init__is_woocommerce;
unset wpuinstaller_init__install_recommended_plugins;
unset wpuinstaller_init__need_advanced_filters;
unset wpuinstaller_init__need_contact_form;
unset wpuinstaller_init__wpu_submodules_muplugins_ok;
unset wpuinstaller_init__need_posts_tpl;
unset wpuinstaller_init__need_acf;
unset wpuinstaller_init__need_acf_forms;
unset wpuinstaller_init__acf_api_key;
