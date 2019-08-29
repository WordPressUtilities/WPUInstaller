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
project_l10n_tool='qtranslate';
if [[ $project_l10n == 'y' ]]; then
    read -p "Use Polylang instead of qtranslate ? (Y/n) " use_polylang;
    if [[ $use_polylang != 'n' ]]; then
        project_l10n_tool='polylang';
    fi;
fi;

home_is_cms=$(bashutilities_get_yn "Is the home page a CMS page ?" 'y');
is_woocommerce=$(bashutilities_get_yn "Is it an ecommerce ?" 'n');
use_submodules=$(bashutilities_get_yn "Use git submodules ?" 'y');
install_recommended_plugins=$(bashutilities_get_yn "Install recommended plugins ?" 'y');
wpu_add_shell_scripts=$(bashutilities_get_yn "Add shell scripts ?" 'n');

read -p "Install WordPress in a subfolder ? (y/N) " use_subfolder;
if [[ $use_subfolder == 'y' ]]; then
    WPU_PHPCLI="${WPU_PHPCLI} --path=wp-cms";
else
    use_subfolder='n';
fi;

read -p "Start installation ? (Y/n) " start_installation;
if [[ $start_installation == 'n' ]]; then
    exit 0;
fi;

WPU_THEME="${MAINDIR}${WP_THEME_DIR}${project_id}/";
