#!/bin/bash

###################################
## Questions
###################################

read -p "What's the project name ? " project_name;
if [[ $project_name == '' ]]; then
    project_name="${PWD##*/}";
fi;
echo "- Project name: ${project_name}";

default_project_id="$(echo -e "${project_name}" | tr -d '[[:space:]]' | tr [:upper:] [:lower:])";
default_project_id="$(echo ${default_project_id} | iconv -f utf8 -t ascii//TRANSLIT)";
default_project_id="$(echo ${default_project_id} | tr -cd '[[:alnum:]]._-')";
read -p "What's the project id ? [${default_project_id}] : " project_id;
if [[ $project_id == '' ]]; then
    project_id="${default_project_id}";
fi;
echo "- Project ID: ${project_id}";

project_dev_url=$(bashutilities_get_user_var "What's the project dev url ?" "http://${project_id}.test");
project_dev_url_raw=${project_dev_url/http:\/\//};
project_dev_url_raw=${project_dev_url_raw/http:\//};
project_dev_url_raw=${project_dev_url_raw/https:\/\//};
project_dev_url_raw=${project_dev_url_raw/\//};
echo "- Project URL: ${project_dev_url}";

touch "${MAINDIR}/test.html";
echo '1' > "${MAINDIR}/test.html";
if [[ $(wget "${project_dev_url}/test.html" -O-) ]] 2>/dev/null; then
    _website_access='1';
    rm "${MAINDIR}/test.html";
else
    _website_access='0';
    rm "${MAINDIR}/test.html";
    echo $(bashutilities_message 'The project URL is not reachable. Please check it and try again.' 'error');
    return 0;
fi

email_address=$(bashutilities_get_user_var "What's your email address ?" "test@example.com");
echo "- Email: ${email_address}";

###################################
## MYSQL
###################################

mysql_host=$(bashutilities_get_user_var "What's the MYSQL HOST?" "localhost");
echo "- MySQL Host: ${mysql_host}";

mysql_user=$(bashutilities_get_user_var "What's the MYSQL USER?" "root");
echo "- MySQL User: ${mysql_user}";

mysql_password=$(bashutilities_get_user_var "What's the MYSQL PASSWORD?" "root");
echo "- MySQL Pass: ${mysql_password}";

mysql_prefix=$(bashutilities_get_user_var "What's the MYSQL PREFIX?" "$(echo ${project_id} | cut -c1-3)_");
echo "- MySQL Prefix: ${mysql_prefix}";

mysql_database=$(bashutilities_get_user_var "What's the MYSQL DATABASE?" "${project_id}");
echo "- MySQL Database: ${mysql_database}";

# Create my.cnf
MY_CNF_CONTENT=$(cat <<TXT
[client]
user=${mysql_user}
password=${mysql_password}
host=${mysql_host}
TXT
);
echo "${MY_CNF_CONTENT}" > "${MAINDIR}my.cnf";

# Test MySQL access
_mysql_access_ok='1';
if ! mysql --defaults-extra-file="${MAINDIR}my.cnf" -e "use ${mysql_database}"; then
    echo $(bashutilities_message 'Your MySQL Ids are invalid. Please check them and try again.' 'error');
    _mysql_access_ok='0';
    return 0;
fi

# Create database
new_database='y';
for db in $(mysql --defaults-extra-file="${MAINDIR}my.cnf" -N <<< "show databases like '%${mysql_database}%'")
do
  case $db in
    $mysql_database)
      read -p "Database '${mysql_database}' already exists. Should it be dropped ? (y/N) " new_database;
      if [[ $new_database == 'y' ]];then
        echo '- Old database dropped';
        echo $(mysql --defaults-extra-file="${MAINDIR}my.cnf" -e "DROP DATABASE IF EXISTS ${mysql_database}") > /dev/null;
      fi;
    ;;
  esac
done
if [[ $new_database != 'n' ]]; then
    new_database='y';
fi;

###################################
## Config
###################################

read -p "Is it a multilingual project ? (y/N) " project_l10n;
if [[ $project_l10n != '' ]]; then
    project_l10n="y";
else
    read -p "What's the locale ? [${WP_LOCALE}] : " user_locale;
    if [[ $user_locale == '' ]]; then
        user_locale="${WP_LOCALE}";
    fi;
    WP_LOCALE="${user_locale}";
    echo "- Locale: ${WP_LOCALE}";
fi;

need_theme=$(bashutilities_get_yn "Do you need a theme ?" 'y');
home_is_cms='n';
has_attachment_tpl='n';
need_advanced_menus='n';
if [[ "${need_theme}" == 'y' ]];then
    home_is_cms=$(bashutilities_get_yn "Is the home page a CMS page ?" 'y');
    has_attachment_tpl=$(bashutilities_get_yn "Do you need the attachment template ?" 'n');
    need_advanced_menus=$(bashutilities_get_yn "Do you need advanced menus ?" 'y');
fi;
is_woocommerce=$(bashutilities_get_yn "Is it an ecommerce ?" 'n');
use_submodules=$(bashutilities_get_yn "Use git submodules ?" 'y');

WPU_SUBMODULES_MUPLUGINS_OK="";
for i in $WPU_SUBMODULES_MUPLUGINS
do
    read -p "## Install ${i} ? (y/N) " install_muplugin;
    if [[ $install_muplugin == 'y' ]];then
        WPU_SUBMODULES_MUPLUGINS_OK="${WPU_SUBMODULES_MUPLUGINS_OK} ${i}";
    fi;
done;
need_search='n';
if [[ ${WPU_SUBMODULES_MUPLUGINS_OK} != *"wpudisablesearch"* ]];then
    need_search='y';
fi;

install_recommended_plugins=$(bashutilities_get_yn "Install recommended plugins ?" 'y');

need_contact_form=$(bashutilities_get_yn "Do you need a contact form ?" 'y');

# ACF
need_acf=$(bashutilities_get_yn "Do you need Advanced Custom Fields ?" 'y');
need_acf_forms='n';
if [[ "${need_acf}" == 'y' && "${acf_api_key}" == "" ]]; then
    read -p "What's your ACF API Key ? " acf_api_key;
    if [[ $acf_api_key == '' ]]; then
        need_acf="n";
    fi;
fi;
if [[ "${need_acf}" != 'n' && "${acf_api_key}" != "" ]]; then
    need_acf_forms=$(bashutilities_get_yn "Do you need a contact form in ACF ?" 'y');
fi;

# Extranet
need_extranet=$(bashutilities_get_yn "Do you need an extranet ?" 'n');

# WP Rocket
need_wprocket=$(bashutilities_get_yn "Do you need WP Rocket ?" 'y');

# Shell
wpu_add_shell_scripts=$(bashutilities_get_yn "Add shell scripts ?" 'n');
use_external_api='n';
if [[ "${wpu_add_shell_scripts}" == 'y' ]];then
    use_external_api=$(bashutilities_get_yn "Do you need to use an external API ?" 'n');
fi;
use_code_tests=$(bashutilities_get_yn "Add code tests ?" 'n');

read -p "Install WordPress in a subfolder ? (y/N) " use_subfolder;
if [[ $use_subfolder == 'y' ]]; then
    WPU_PHPCLI="${WPU_PHPCLI} --path=wp-cms";
else
    use_subfolder='n';
fi;

use_intestarter='n';
if [[ "${need_theme}" == 'y' ]];then
    read -p "Do you want to use InteStarter ? (y/N) " use_intestarter;
fi;

read -p "Start installation ? (Y/n) " start_installation;
if [[ $start_installation == 'n' ]]; then
    exit 0;
fi;

WPU_THEME="${MAINDIR}${WP_THEME_DIR}${project_id}/";
