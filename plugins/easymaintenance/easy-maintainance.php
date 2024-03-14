<?php
/*
Plugin Name: Easy Maintenance
Plugin URI: http://shafi.info/plugins/easy-maintenance/
Description: This plugin can be used for Maintenance mood. Even you can use this as Coming soon page, Landin Page. You can use your pages or any text to display 
Author: S M Shafiuzzaman
Tags: Maintaince, Landing Page, Coming Soon, Will Back Soon, Maintaince Mood, Easy Maintainance, easymaintenance, Maintenance
Version: 1.9.10.2017
*/

$easyMaintenanceData = $_GET['easymaintenance'] ? $_GET['easymaintenance'] : '';
add_action('admin_menu', 'EasyMaintainceMenu');
	
	function EasyMaintainceMenu() {
		
		add_menu_page('Easy Maintenance', 'Easy Maintenance', 10, 'maintainpage', 'easyMaintenancecallBackFunction', plugins_url() . '/easymaintenance' . $plugin_name . '/assets/images/development.png', 4);
	 
	   
		  
		
	}
	
	function easyMaintenancecallBackFunction() {
		$easyMaintenancep = isset($_GET['page']) ? trim($_GET['page']) : '';
		$easyMaintenancebaseDir = dirname(__FILE__) . '/';
	
		if ($easyMaintenancep == 'userspage') {
			( easyMaintenanceloadMyFile($easyMaintenancebaseDir . '/users.php') );
		} else {
			( easyMaintenanceloadMyFile($easyMaintenancebaseDir . 'settings.php') );
		}
	}
	
	function easyMaintenanceloadMyFile($FilePath) {
		if ($FilePath && file_exists($FilePath)) {
			require_once( $FilePath );
		} else {
			echo '<div style="padding:10px; background-color:#ff0"> File Missing ' . $FilePath . ' </div>';
		}
	}
	
$easyMaintenancekillSwitch = get_option("maintainchoicePluginMood");

if($easyMaintenancekillSwitch == 'active'){
	$maintainchoicePlugin_messageType = get_option("maintainchoicePlugin_messageType");
	
	
	$maintainchoicePlugin_message_page = get_option("maintainchoicePlugin_message_page");
	$maintainchoicePlugin_TextToDisplay = get_option("maintainchoicePlugin_TextToDisplay");
	
	 
	add_action( 'template_redirect', function() {
		global $easyMaintenanceData;
	 if( $easyMaintenanceData == NULL){
		   wp_redirect(home_url('/' .get_option("maintainchoicePlugin_message_page") . '?easymaintenance=true'));
			//wp_redirect(site_url().'/'.$maintainchoicePlugin_message_page.'under-maintainance/?easymaintenance=1');
		exit;
	 }
	});
	
	if( $easyMaintenanceData != NULL && $maintainchoicePlugin_messageType =='ut'){
		echo stripslashes($maintainchoicePlugin_TextToDisplay);
		exit;
	}
	
	
	
}


	if( $easyMaintenanceData != NULL){
	if($easyMaintenancekillSwitch =="deactive"){
		echo'<script>window.location.replace("'.site_url().'");</script>';
		 
		exit;
	}
}