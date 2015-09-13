#!/bin/bash

###################################
## Questions
###################################

read -p "What's the project name ? " project_name;
if [[ $project_name == '' ]]; then
    project_name="WordPress Theme";
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

read -p "What's the project dev url ? [http://localhost/${project_id}/] : " project_dev_url;
if [[ $project_dev_url == '' ]]; then
    project_dev_url="http://localhost/${project_id}/";
fi;
echo "- Project URL: ${project_dev_url}";

read -p "Is it a multilingual project ? (Y/n) " project_l10n;
if [[ $project_l10n == '' ]]; then
    project_l10n="y";
else
    read -p "What's the locale ? [${WP_LOCALE}] : " user_locale;
    if [[ $user_locale == '' ]]; then
        user_locale="${WP_LOCALE}";
    fi;
    WP_LOCALE="${user_locale}";
    echo "- Locale: ${WP_LOCALE}";
fi;

read -p "What's your email address ? [test@yopmail.com] : " email_address;
if [[ $email_address == '' ]]; then
    email_address="test@yopmail.com";
fi;
echo "- Email: ${email_address}";

read -p "What's the MYSQL HOST ? [127.0.0.1] : " mysql_host;
if [[ $mysql_host == '' ]]; then
    mysql_host="127.0.0.1";
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

read -p "What's the MYSQL DATABASE ? [${project_id}] : " mysql_database;
if [[ $mysql_database == '' ]]; then
    mysql_database="${project_id}";
fi;

read -p "Start installation ? (Y/n) " start_installation;
if [[ $start_installation == 'n' ]]; then
    exit 0;
fi;

WPU_THEME="${MAINDIR}${WP_THEME_DIR}${project_id}/";