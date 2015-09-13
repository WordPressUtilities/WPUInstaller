#!/bin/bash

MAINDIR="${PWD}/";
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/";
export PATH=$PATH:/Applications/MAMP/Library/bin/

. "${SCRIPTDIR}bin/vars.sh";
. "${SCRIPTDIR}bin/questions.sh";
. "${SCRIPTDIR}bin/git.sh";
. "${SCRIPTDIR}bin/wpcli.sh";
. "${SCRIPTDIR}bin/wp.sh";
. "${SCRIPTDIR}bin/wpu.sh";
. "${SCRIPTDIR}bin/theme.sh";
. "${SCRIPTDIR}bin/mu.sh";
. "${SCRIPTDIR}bin/plugins.sh";
. "${SCRIPTDIR}bin/htaccess.sh";
. "${SCRIPTDIR}bin/clean.sh";


echo '### Success';

cd "${WPU_THEME}";
open "${project_dev_url}";