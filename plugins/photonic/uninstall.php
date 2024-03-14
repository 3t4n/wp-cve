<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

delete_option('photonic_options');
delete_option('photonic_authentication');
delete_option('photonic_css');

$photonic_token_monitor_timestamp = wp_next_scheduled('photonic_token_monitor');
wp_unschedule_event($photonic_token_monitor_timestamp, 'photonic_token_monitor');

/*
$url = wp_nonce_url('plugins.php?page=photonic-options-manager');
if (false === ($creds = request_filesystem_credentials($url, '', false, false))) {
	return true;
}

if (!WP_Filesystem($creds)) {
	request_filesystem_credentials($url, '', true, false);
	return true;
}

global $wp_filesystem;
if (!is_dir(PHOTONIC_UPLOAD_DIR)) {
	if (!$wp_filesystem->mkdir(PHOTONIC_UPLOAD_DIR)) {
		echo "<div class='error'><p>Failed to create directory ".PHOTONIC_UPLOAD_DIR.". Please check your folder permissions.</p></div>";
		return false;
	}
}

$filename = trailingslashit(PHOTONIC_UPLOAD_DIR).'custom-styles.css';

if (empty($custom_css)) {
	return false;
}

if (!$wp_filesystem->put_contents($filename, $custom_css, FS_CHMOD_FILE)) {
	echo "<div class='error'><p>Failed to save file $filename. Please check your folder permissions.</p></div>";
	return false;
}



$upload_dir = wp_upload_dir();
$photonic_dir = trailingslashit($upload_dir['basedir']).'photonic';
if (@file_exists($photonic_dir)) {
	WP_Filesystem_Direct::delete($upload_dir, true);
}
*/
