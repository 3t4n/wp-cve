<?php
	
	global $mollaUtility,$mo_lla_dirName;

	if(isset($_POST['option']) and 	sanitize_text_field($_POST['option']=='mo_lla_manual_clear')){
		global $wpdb;
		$wpdb->query("DELETE FROM ".$wpdb->prefix."wpns_transactions WHERE Status='success' or Status= 'pastfailed' or Status='failed' ");

	}



	if(isset($_POST['option']) and sanitize_text_field($_POST['option']) =='mo_wpns_manual_errorclear'){
		global $wpdb;
		$wpdb->query("DELETE FROM ".$wpdb->prefix."wpns_transactions WHERE Status='accessDenied'");

	}

	$mo_lla_handler   = new Mo_lla_MoWpnsHandler();
	$logintranscations = $mo_lla_handler->get_login_transaction_report();
	$errortranscations = $mo_lla_handler->get_error_transaction_report();

	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'reports.php';

?>
		
