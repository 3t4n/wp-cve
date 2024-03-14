<?php
	
	global $mollaUtility,$mo_lla_dirName;


	$profile_url	= add_query_arg( array('page' => 'wpnsaccount'		), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$login_security	= add_query_arg( array('page' => 'default'			), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$login_and_spam = add_query_arg( array('page' => 'mo_lla_login_and_spam'   ), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$waf			= add_query_arg( array('page' => 'waf'				), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$register_url	= add_query_arg( array('page' => 'registration'		), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$blocked_ips	= add_query_arg( array('page' => 'blockedips'		), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$advance_block	= add_query_arg( array('page' => 'advancedblocking'	), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$notif_url		= add_query_arg( array('page' => 'notifications'	), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$reports_url	= add_query_arg( array('page' => 'reports'			), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$license_url	= add_query_arg( array('page' => 'upgrade'  		), sanitize_text_field($_SERVER['REQUEST_URI'] ));	
	$content_protect= add_query_arg( array('page' => 'content_protect'	), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$backup			= add_query_arg( array('page' => 'backup'			), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	$scan_url       = add_query_arg( array('page' => 'malwarescan'      ), sanitize_text_field($_SERVER['REQUEST_URI'] ));
	//Added for new design
    $dashboard_url	= add_query_arg(array('page' => 'dashboard'			), sanitize_text_field($_SERVER['REQUEST_URI']));
    $upgrade_url	= add_query_arg(array('page' => 'upgrade'				), sanitize_text_field($_SERVER['REQUEST_URI']));
   //dynamic
    $logo_url = plugin_dir_url(dirname(__FILE__)) . 'includes/images/miniorange_logo.png';
    $shw_feedback	= get_option('donot_show_feedback_message') ? false: true;
    $moPluginHandler= new Mo_lla_MoWpnsHandler();
    $safe			= $moPluginHandler->is_whitelisted($mollaUtility->get_client_ip());

    $active_tab 	= sanitize_text_field($_GET['page']);

	$is_brute_force_enable=get_site_option("mo_lla_enable_brute_force");

	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'navbar.php';