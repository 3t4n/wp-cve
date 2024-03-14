<?php

//	ini_set('display_errors', 1);
//	ini_set('display_startup_errors', 1);
//	error_reporting(E_ALL);

$acfct_include_path = ACF_CUSTOM_TABLE_PATH . 'includes/';

include_once $acfct_include_path . 'class-acfct-update.php';
include_once $acfct_include_path . 'trait-acf-util.php';
include_once $acfct_include_path . 'class-acfct-utils.php';
include_once $acfct_include_path . 'pro/trait-acfct-pro-formatters.php';
include_once $acfct_include_path . 'trait-acfct-formatters.php';
include_once $acfct_include_path . 'class-acfct-table-data.php';
include_once $acfct_include_path . 'class-acfct-database-manager.php';
include_once $acfct_include_path . 'class-acfct-register-hooks.php';
include_once $acfct_include_path . 'api/api-helper.php';
include_once $acfct_include_path . 'api/public-api.php';
