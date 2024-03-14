<?php

	global $mollaUtility,$mo_lla_dirName;

	$controller = $mo_lla_dirName . 'controllers/';

	include $controller 	 . 'navbar.php';

	if( isset($_GET[ 'page' ])) 
	{
		switch(sanitize_text_field($_GET['page']))
		{
			case 'dashboard':
			case 'Limit_Login_Attempts':
                include $controller . 'dashboard.php';			    break;
            case 'mo_lla_login_and_spam':
				include $controller . 'login-spam.php';				break;
			case 'default':
				include $controller . 'login-security.php';			break;
			case 'wpnsaccount':
				include $controller . 'account.php';				break;		
			case 'backup':
				include $controller . 'backup.php'; 				break;
			case 'upgrade':
				include $controller . 'upgrade.php';                break;
			case 'blockedips':
				include $controller . 'ip-blocking.php';			break;
			case 'advancedblocking':
				include $controller . 'advanced-blocking.php';		break;
			case 'notifications':
				include $controller . 'notification-settings.php';	break;
			case 'reports':
				include $controller . 'reports.php';				break;
			case 'licencing':
				include $controller . 'licensing.php';				break;
			
			
		}
	}

	if(isset($_GET[ 'page' ]) && sanitize_text_field( $_GET[ 'page' ] )!='dashboard')
	{
		if(isset($_GET[ 'page' ]) && sanitize_text_field( $_GET[ 'page' ] )!='upgrade'){}
			// include $controller . 'support.php';
	}