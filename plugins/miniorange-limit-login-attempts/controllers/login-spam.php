<?php
   global $mollaUtility,$mo_lla_dirName;
	$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
	$sqlC 			= $mo_lla_handler->get_blocked_attacks_count("SQL");
	$rceC 			= $mo_lla_handler->get_blocked_attacks_count("RCE");
	$rfiC 			= $mo_lla_handler->get_blocked_attacks_count("RFI");
	$lfiC 			= $mo_lla_handler->get_blocked_attacks_count("LFI");
	$xssC 			= $mo_lla_handler->get_blocked_attacks_count("XSS");
	$totalAttacks	= $sqlC+$lfiC+$rfiC+$xssC+$rceC;
	$manualBlocks 	= $mo_lla_handler->get_manual_blocked_ip_count();
	$realTime		= 0;
	$countryBlocked = $mo_lla_handler->get_blocked_countries();
	$IPblockedByWAF = $mo_lla_handler->get_blocked_ip_waf();
	$totalIPBlocked = $manualBlocks+$realTime+$IPblockedByWAF;
	$mo_waf 		= get_site_option('WAFEnabled');
	if($mo_waf)
	{
		$mo_waf = false;
	}
	else
	{
		$mo_waf = true;	
	}

	$img_loader_url	= plugins_url('miniorange-limit-login-attempts/includes/images/loader.gif');
	
	if($totalIPBlocked>999)
	{
		$totalIPBlocked = strval(intval($totalIPBlocked/1000)).'k+';
	}
	
	if($totalAttacks>999)
	{
		$totalAttacks = strval(intval($totalAttacks/1000)).'k+';
	}

if( isset( $_GET[ 'tab' ] ) ) {
		$active_sub_tab = sanitize_text_field($_GET[ 'tab' ]);
} else {
		$active_sub_tab = 'login_sec';
}
include_once $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'login_spam.php';
?>