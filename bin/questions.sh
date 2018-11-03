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
echo "- Project URL: ${project_dev_url}";

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

read -p "Is the home page a CMS page ? (Y/n) " home_is_cms;
if [[ $home_is_cms == '' ]]; then
    home_is_cms="y";
fi;

read -p "Is it an ecommerce ? (y/N) " is_woocommerce;
if [[ $is_woocommerce == 'y' ]]; then
    is_woocommerce="y";
fi;

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

read -p "Use git submodules ? (Y/n) " use_submodules;
if [[ $use_submodules != 'n' ]]; then
    use_submodules='y';
fi;

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
