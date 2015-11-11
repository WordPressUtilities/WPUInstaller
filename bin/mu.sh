#!/bin/bash

###################################
## MU-Plugins installation
###################################

echo '### MU-Plugins installation';

mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}";

# Forced MU Plugins
for i in $WPU_FORCED_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${i}.php";
done;

# Classic MU Plugins
for i in $WPU_MUPLUGINS
do
    read -p "## Install ${i} ? (y/N) " install_muplugin;
    if [[ $install_muplugin == 'y' ]];then
        cp "${MAINDIR}WPUtilities/${WP_PLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${i}.php";
    fi;
done;

# Commit Add mu-plugins
git add -A
git commit -m "Installation - MU-Plugins" --quiet;
