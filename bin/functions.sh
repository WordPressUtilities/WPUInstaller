#!/bin/bash

function wpuinstaller_replace() {
    bashutilities_sed "s/wpuprojectname/${project_name}/g" "${1}";
    bashutilities_sed "s/wpuprojectid/${project_id}/g" "${1}";
    bashutilities_sed "s/wpuproject/${project_name}/g" "${1}";
}
