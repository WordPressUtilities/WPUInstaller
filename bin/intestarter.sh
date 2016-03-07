#!/bin/bash

###################################
## Launch InteStarter
###################################

read -p "Do you want to use InteStarter ? (y/N) " use_intestarter;
if [[ $use_intestarter == 'y' ]]; then

## Init values
is_wp_theme='y';
from_wpinstaller='y';

## Backup files
mv "${WPU_THEME}assets/images/logo.png" "${WPU_THEME}logo.png";
mv "${WPU_THEME}assets/js/events.js" "${WPU_THEME}events.js";
rm -rf "${WPU_THEME}assets/";
cd "${WPU_THEME}";

## Launch script
newinte;

## Restore files
mv "${WPU_THEME}logo.png" "${WPU_THEME}assets/images/logo.png";
mv "${WPU_THEME}events.js" "${WPU_THEME}assets/js/events.js";

fi;
