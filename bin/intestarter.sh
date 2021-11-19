#!/bin/bash

###################################
## Launch InteStarter
###################################

## Init values
is_wp_theme='y';
from_wpinstaller='y';
project_hostname="${project_dev_url_raw}";

## Backup files
if [[ -f "${WPU_THEME}assets/images/logo.png" ]];then
    mv "${WPU_THEME}assets/images/logo.png" "${WPU_THEME}logo.png";
fi;
if [[ -f "${WPU_THEME}assets/js/events.js" ]];then
    mv "${WPU_THEME}assets/js/events.js" "${WPU_THEME}events.js";
fi;

rm -rf "${WPU_THEME}assets/";
cd "${WPU_THEME}";

## Launch script
newinte;

## Restore files
if [[ -f "${WPU_THEME}logo.png" ]];then
    mv "${WPU_THEME}logo.png" "${WPU_THEME}assets/images/logo.png";
fi;
if [[ -f "${WPU_THEME}events.js" ]];then
    mv "${WPU_THEME}events.js" "${WPU_THEME}assets/js/events.js";
fi;

## Add extra SCSS
WPU_SCSSFILE="${WPU_THEME}src/main.scss";
if [[ -f "${WPU_SCSSFILE}" ]];then
    if [[ "${need_acf}" == 'y' ]];then
        echo "@import \"${project_id}/blocks/*\";" >> "${WPU_SCSSFILE}";
    fi;
fi;
