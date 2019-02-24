<?php

include dirname(__FILE__) . '/wpuwooimportexport/inc/bootstrap.php';

class wpuprojectid_WPUWOO_Clean extends WPUWooImportExport {
    public function __construct() {
        echo 'Hello !';
    }
}

new wpuprojectid_WPUWOO_Clean();
