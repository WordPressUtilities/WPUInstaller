#!/bin/bash

MAINDIR="${PWD}/";
SCRIPTDIR="$( dirname "${BASH_SOURCE[0]}" )/";
export PATH=$PATH:/Applications/MAMP/Library/bin/

. "${SCRIPTDIR}bin/tools.sh";
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
. "${SCRIPTDIR}bin/intestarter.sh";

echo '### Success';

cd "${WPU_THEME}";
open "${project_dev_url}";
