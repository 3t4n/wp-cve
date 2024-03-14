<?php

/* main class for this plugin */
include_once 'WPVR_OXY_INTEGRATION.php';

global $wpvr_oxy_integration;
$wpvr_oxy_integration = new WPVR_OXY_INTEGRATION();

/* Our Base Oxygen Element to extend upon */
include_once 'WPVR_CUSTOM_OXY_ELEMENT.php';

/* Include all of our Oxygen Elements dynamically */
$elements_filenames = glob(plugin_dir_path(__FILE__)."elements/*.php");
foreach ($elements_filenames as $filename) {
    include_once $filename;
}
