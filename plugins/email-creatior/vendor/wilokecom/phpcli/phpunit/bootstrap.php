<?php
if (is_file("./wp-content/projecttype/projectname/vendor/autoload.php")) {
	require "./wp-content/projecttype/projectname/vendor/autoload.php";
} else {
	require "./vendor/autoload.php";
}

if (is_file("./wp-load.php")) {
	include './wp-load.php';
} else {
	include "../../../wp-load.php";
}

global $aWILOKEGLOBAL;
if (isset($_ENV['HOME_URL']) && $_ENV['HOME_URL'] !== 'HOME_URL_VALUE') {
	$aWILOKEGLOBAL['homeUrl'] = $_ENV['HOME_URL'];
} else {
	$aWILOKEGLOBAL['homeUrl'] = get_option('site_url');
}

$aWILOKEGLOBAL['ajaxUrl'] = trailingslashit($aWILOKEGLOBAL['homeUrl']) . 'wp-admin/admin-ajax.php';

if (isset($_ENV['REST_BASE']) && $_ENV['REST_BASE'] !== 'REST_BASE_VALUE') {
	$aWILOKEGLOBAL['restBaseUrl'] = trailingslashit($aWILOKEGLOBAL['homeUrl']) . 'wp-json/' .
		untrailingslashit($_ENV['REST_BASE']);
} else {
	$aWILOKEGLOBAL['restBaseUrl'] = get_rest_url('', 'wiloke/v1');
}

$aWILOKEGLOBAL['ADMIN_USERNAME'] = $_ENV['ADMIN_USERNAME'];
$aWILOKEGLOBAL['ADMIN_AUTH_PASS'] = $_ENV['ADMIN_AUTH_PASS'];
$aWILOKEGLOBAL['ADMIN_PASSWORD'] = $_ENV['ADMIN_PASSWORD'];
$aWILOKEGLOBAL['SAMPLE_DATA_DIR'] = plugin_dir_path(__FILE__) . 'sample-data/';
