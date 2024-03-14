<?php
/*

Plugin Name: Auto FTP
Plugin URI: http://www.appchain.com/auto-ftp/
Description: Makes plugins and themes installs and updates faster, no longer needs ftp details confirmation.
Author: Turcu Ciprian
License: GPL
Version: 1.0.1
Author URI: http://www.appchain.com
*/


function xAFTPInclude(){
	include("pages/AdminPage.php");
}

function xAFTPApimenu() {
	if($_POST['xAFTPHidd']=="xAFTPHidd"){
		$xAFTPHost = $_POST['xAFTPHost'];
		$xAFTPUser = $_POST['xAFTPUser'];
		$xAFTPPassword = $_POST['xAFTPPassword'];
		
		$xArray[0]=$xAFTPHost;
		$xArray[1]=$xAFTPUser;
		$xArray[2]=$xAFTPPassword;
		
		$xAFTP = serialize($xArray);
		update_option('xAFTP', $xAFTP);
	}
	
	add_options_page('My Plugin Options', 'Auto FTP', 8, __FILE__, 'xAFTPInclude');
}
function xAFTPApiInit(){
	$xArray = unserialize(get_option('xAFTP'));
	if($xArray[0]!=""){
		define('FTP_HOST', $xArray[0]);
		define('FTP_USER', $xArray[1]);
		define('FTP_PASS', $xArray[2]);
	}
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('admin_menu', 'xAFTPApiMenu');
add_action('admin_init', 'xAFTPApiInit');


 
?>