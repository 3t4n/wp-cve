<?php 


//Prevent directly browsing to the file
if (function_exists('plugin_dir_url')) 
{

	$host = $_SERVER['HTTP_HOST'];

	switch ($host)
	{
	case 'wordpress':
		$create_target = 'http://localhost';
		break;

	default:
		$create_target = 'https://beacon.by';
		break;
	}

	define('BEACONBY_VERSION',      '1.5.7');
	define("BEACONBY_HOMEPAGE",     "https://beacon.by/");
	define("BEACONBY_HELPLINK",     "https://beacon.by/wordpress");
	define('BEACONBY_PLUGIN_URL',   plugin_dir_url(__FILE__));
	define('BEACONBY_PLUGIN_PATH',  plugin_dir_path(__FILE__));
	define('BEACONBY_SITE_URL',	get_site_url());
	define('BEACONBY_INCLUDE_TITLES', true);
	define('BEACONBY_PER_PAGE',	50);

	define('BEACONBY_CREATE_TARGET',	$create_target);

}



