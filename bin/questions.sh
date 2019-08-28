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

read -p "What's the project dev url ? [http://${project_id}.test] : " project_dev_url;
if [[ $project_dev_url == '' ]]; then
    project_dev_url="http://${project_id}.test";
fi;
project_dev_url_raw=${project_dev_url/http:\/\//};
project_dev_url_raw=${project_dev_url_raw/\//};
echo "- Project URL: ${project_dev_url}";

touch "${MAINDIR}/test.html";
echo '1' > "${MAINDIR}/test.html";
if [[ $(wget "${project_dev_url}/test.html" -O-) ]] 2>/dev/null; then
    _website_access='1';
    rm "${MAINDIR}/test.html";
else
    rm "${MAINDIR}/test.html";
    echo $(bashutilities_message 'The project URL is not reachable. Please check it and try again.' 'error');
    _website_access='0';
    return 0;
fi


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
    if [[ $use_polylang != 'y' ]]; then
        project_l10n_tool='polylang';
    fi;
fi;

home_is_cms=$(bashutilities_get_yn "Is the home page a CMS page ?" 'y');
is_woocommerce=$(bashutilities_get_yn "Is it an ecommerce ?" 'n');

read -p "What's your email address ? [test@yopmail.com] : " email_address;
if [[ $email_address == '' ]]; then
    email_address="test@yopmail.com";
fi;
echo "- Email: ${email_address}";

read -p "What's the MYSQL HOST ? [localhost] : " mysql_host;
if [[ $mysql_host == '' ]]; then
    mysql_host="localhost";
fi;
echo "- MySQL Host: ${mysql_host}";

read -p "What's the MYSQL USER ? [root] : " mysql_user;
if [[ $mysql_user == '' ]]; then
    mysql_user="root";
fi;
echo "- MySQL User: ${mysql_user}";

read -p "What's the MYSQL PASSWORD ? [root] : " mysql_password;
if [[ $mysql_password == '' ]]; then
    mysql_password="root";
fi;
echo "- MySQL Pass: ${mysql_password}";

default_mysql_prefix="$(echo ${project_id} | cut -c1-3)_";
read -p "What's the MYSQL PREFIX ? [${default_mysql_prefix}] : " mysql_prefix;
if [[ $mysql_prefix == '' ]]; then
    mysql_prefix="${default_mysql_prefix}";
fi;
echo "- MySQL Prefix: ${mysql_prefix}";

read -p "What's the MYSQL DATABASE ? [${project_id}] : " mysql_database;
if [[ $mysql_database == '' ]]; then
    mysql_database="${project_id}";
fi;
echo "- MySQL Database: ${mysql_database}";

# Test MySQL access
_mysql_access_ok='1';
if ! mysql -h "${mysql_host}" -u "${mysql_user}" -p"${mysql_password}" -e "use ${mysql_database}"; then
    echo $(bashutilities_message 'Your MySQL Ids are invalid. Please check them and try again.' 'error');
    _mysql_access_ok='0';
    return 0;
fi

use_submodules=$(bashutilities_get_yn "Use git submodules ?" 'y');
install_recommended_plugins=$(bashutilities_get_yn "Install recommended plugins ?" 'y');
wpu_add_shell_scripts=$(bashutilities_get_yn "Add shell scripts ?" 'n');

read -p "Install WordPress in a subfolder ? (y/N) " use_subfolder;
if [[ $use_subfolder == 'y' ]]; then
    WPU_PHPCLI="${WPU_PHPCLI} --path=wp-cms";
else
    use_subfolder='n';
fi;

new_database='y';
for db in $(mysql -h${mysql_host} -u${mysql_user} -p${mysql_password} -N <<< "show databases like '%${mysql_database}%'")
do
  case $db in
    $mysql_database)
      read -p "Database '${mysql_database}' already exists. Should it be dropped ? (y/N) " new_database;
      if [[ $new_database == 'y' ]];then
        echo '- Old database dropped';
        echo $(mysql -h${mysql_host} -u${mysql_user} -p${mysql_password} -e "DROP DATABASE IF EXISTS ${mysql_database}") > /dev/null;
      fi;
    ;;
  esac
done
if [[ $new_database != 'n' ]]; then
    new_database='y';
fi;

read -p "Start installation ? (Y/n) " start_installation;
if [[ $start_installation == 'n' ]]; then
    exit 0;
fi;

WPU_THEME="${MAINDIR}${WP_THEME_DIR}${project_id}/";
