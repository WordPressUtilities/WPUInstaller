#!/bin/bash

###################################
## MU-Plugins installation v 0.1
###################################

echo '### MU-Plugins installation';

mkdir "${MAINDIR}${WP_MUPLUGINS_DIR}";

# Classic MU Plugins
for i in $WPU_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_MUPLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${i}.php";
done;

# Forced MU Plugins
for i in $WPU_FORCED_MUPLUGINS
do
    echo "## Install ${i}";
    cp "${MAINDIR}WPUtilities/${WP_PLUGINS_DIR}${i}.php" "${MAINDIR}${WP_MUPLUGINS_DIR}${i}.php";
done;

# Commit Add mu-plugins
git add .
git commit -m "Installation - MU-Plugins" --quiet;
