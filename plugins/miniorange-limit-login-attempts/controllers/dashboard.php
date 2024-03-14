<?php

    //all the variables and links
	$lla_database = new Mo_lla_MoWpnsDB;
	$lla_count_ips_blocked = $lla_database->get_count_of_blocked_ips();
	$lla_count_ips_whitelisted = $lla_database->get_number_of_whitelisted_ips();
	$lla_attacks_blocked = $lla_database->get_count_of_attacks_blocked();

	$mo_lla_handler 	= new Mo_lla_MoWpnsHandler();
	$sqlC 			= $mo_lla_handler->get_blocked_attacks_count("SQL");
	$rceC 			= $mo_lla_handler->get_blocked_attacks_count("RCE");
	$rfiC 			= $mo_lla_handler->get_blocked_attacks_count("RFI");
	$lfiC 			= $mo_lla_handler->get_blocked_attacks_count("LFI");
	$xssC 			= $mo_lla_handler->get_blocked_attacks_count("XSS");
	$totalAttacks	= $sqlC+$lfiC+$rfiC+$xssC+$rceC;
	$totalAttacks	= $sqlC+$lfiC+$rfiC+$xssC+$rceC;
	$manualBlocks 	= $mo_lla_handler->get_manual_blocked_ip_count();
	$realTime		= 0;
	$countryBlocked = $mo_lla_handler->get_blocked_countries();
	$IPblockedByWAF = $mo_lla_handler->get_blocked_ip_waf();
	$totalIPBlocked = $manualBlocks+$realTime+$IPblockedByWAF;
	$mo_waf 		= get_site_option('WAFEnabled');
    include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'dashboard.php';