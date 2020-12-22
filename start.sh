#!/bin/bash

MAINDIR="${PWD}/";
SCRIPTDIR="$( dirname "${BASH_SOURCE[0]}" )/";
export PATH=$PATH:/Applications/MAMP/Library/bin/

if [[ -f "${SCRIPTDIR}local.sh" ]];then
    . "${SCRIPTDIR}local.sh";
fi

. "${SCRIPTDIR}BashUtilities/bashutilities.sh";
. "${SCRIPTDIR}bin/vars.sh";
. "${SCRIPTDIR}bin/questions.sh";

if [[ "${_website_access}" != '1' ]]; then
    return 0;
fi;
if [[ "${_mysql_access_ok}" != '1' ]]; then
    return 0;
fi;

. "${SCRIPTDIR}bin/git.sh";
. "${SCRIPTDIR}bin/wpcli.sh";
. "${SCRIPTDIR}bin/wp.sh";
. "${SCRIPTDIR}bin/wpu.sh";
if [[ "${need_theme}" == 'y' ]];then
    . "${SCRIPTDIR}bin/theme.sh";
fi;
. "${SCRIPTDIR}bin/mu.sh";
. "${SCRIPTDIR}bin/plugins.sh";
. "${SCRIPTDIR}bin/shell.sh";
. "${SCRIPTDIR}bin/htaccess.sh";
. "${SCRIPTDIR}bin/clean.sh";
if [[ "${use_intestarter}" == 'y' ]];then
    . "${SCRIPTDIR}bin/intestarter.sh";
fi;

echo '### Success';
if [[ "${need_theme}" == 'y' ]];then
    cd "${WPU_THEME}";
fi;
open "${project_dev_url}";
