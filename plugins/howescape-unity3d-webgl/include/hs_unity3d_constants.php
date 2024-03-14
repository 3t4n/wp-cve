<?php
/*
    Description: Plugin for Wordpress to create Forge Of Empire build suggestion
    Author: P.T.Howe
    Version: 0.0.2	
*/ 
global	$wpdb;

// Table Names - Constant Definition for DB
define ( 'HS_DB_UNITY3D_SCORE',		$wpdb->prefix .'_hs_unity3d_score');
define ( 'HS_DB_UNITY3D_PLATFORM',	$wpdb->prefix .'_hs_unity3d_platform');

// Setting value names
define ( 'HS_SETTING_UNITY3D_SCORELINES', 	'HoweScape_unity3d_score_line');
define ( 'HS_SETTING_UNITY3D_SCOREPAGE', 	'HoweScape_unity3d_addPageName');
define ( 'HS_SETTING_UNITY3D_MACKEY', 		'HoweScape_unity3d_macKey');
// Cookie value name
define ( 'HS_COOKIE_UNITY3D_USER', 		'HoweScape_unity3d_user');
define ( 'HS_COOKIE_UNITY3D_SCOREPAGE', 'HoweScape_unity3d_scorePage');
define ( 'HS_COOKIE_UNITY3D_SCORELINES', 'HoweScape_unity3d_scoreLines');
define ( 'HS_COOKIE_UNITY3D_PLATFORMICON', 'HoweScape_unity3d_platformIcon');
define ( 'HS_COOKIE_UNITY3D_MACKEY', 	'HoweScape_unity3d_macKey');

// Constants for PLATFORM ID
define ( 'HS_DB_UNITY3D_iOS',				1);
define ( 'HS_DB_UNITY3D_Android',			2);
define ( 'HS_DB_UNITY3D_WindowsPhone8',		3);
define ( 'HS_DB_UNITY3D_Tizen',				4);
define ( 'HS_DB_UNITY3D_Windows',			5);
define ( 'HS_DB_UNITY3D_WindowsStoreApps',	6);
define ( 'HS_DB_UNITY3D_MAC',				7);
define ( 'HS_DB_UNITY3D_Linux',				8);
define ( 'HS_DB_UNITY3D_WebGL',				9);
define ( 'HS_DB_UNITY3D_PS4',				10);
define ( 'HS_DB_UNITY3D_PSVita',			11);
define ( 'HS_DB_UNITY3D_XBoxOne',			12);
define ( 'HS_DB_UNITY3D_XBox360',			13);
define ( 'HS_DB_UNITY3D_WiiU',				14);
define ( 'HS_DB_UNITY3D_3DS',				15);
define ( 'HS_DB_UNITY3D_OculusRift',		16);
define ( 'HS_DB_UNITY3D_GoogleCardboard',	17);
define ( 'HS_DB_UNITY3D_StreamVR',			18);
define ( 'HS_DB_UNITY3D_PlaystationVR',		19);
define ( 'HS_DB_UNITY3D_GearVR',			20);
define ( 'HS_DB_UNITY3D_MicrosoftHoloens',	21);
define ( 'HS_DB_UNITY3D_UniversalWindows',	22);
define ( 'HS_DB_UNITY3D_AndroidTV',			23);
define ( 'HS_DB_UNITY3D_SamsungSmartTV',	24);
define ( 'HS_DB_UNITY3D_tvOS',				25);


// Constants for PLATFORM NAME
define ( 'HS_DB_UNITY3D_iOS_NAME',				'iOS');
define ( 'HS_DB_UNITY3D_Android_NAME',			'Android');
define ( 'HS_DB_UNITY3D_WindowsPhone8_NAME',	'WindowsPhone8');
define ( 'HS_DB_UNITY3D_Tizen_NAME',			'Tizen');
define ( 'HS_DB_UNITY3D_Windows_NAME',			'Windows');
define ( 'HS_DB_UNITY3D_WindowsStoreApps_NAME',	'Windows Store Apps');
define ( 'HS_DB_UNITY3D_MAC_NAME',				'MAC');
define ( 'HS_DB_UNITY3D_Linux_NAME',			'Linux / Steam OS');
define ( 'HS_DB_UNITY3D_WebGL_NAME',			'WebGL');
define ( 'HS_DB_UNITY3D_PS4_NAME',				'PS4');
define ( 'HS_DB_UNITY3D_PSVita_NAME',			'PSVita');
define ( 'HS_DB_UNITY3D_XBoxOne_NAME',			'XBox One');
define ( 'HS_DB_UNITY3D_XBox360_NAME',			'XBox 360');
define ( 'HS_DB_UNITY3D_WiiU_NAME',				'Wii U');
define ( 'HS_DB_UNITY3D_3DS_NAME',				'3DS');
define ( 'HS_DB_UNITY3D_OculusRift_NAME',		'Oculus Rift');
//define ( 'HS_DB_UNITY3D_GoogleCardboard_NAME',	'Google Cardboard');
define ( 'HS_DB_UNITY3D_GoogleCardboard_NAME',	'Stream VR');
define ( 'HS_DB_UNITY3D_PlaystationVR_NAME',	'Playstation VR');
define ( 'HS_DB_UNITY3D_GearVR_NAME',			'Gear VR');
define ( 'HS_DB_UNITY3D_MicrosoftHoloens_NAME',	'Microsoft Holoens');
define ( 'HS_DB_UNITY3D_UniversalWindows_NAME',	'Universal Windows');
define ( 'HS_DB_UNITY3D_AndroidTV_NAME',		'Andriod TV');
define ( 'HS_DB_UNITY3D_SamsungSmartTV_NAME',	'Samsung Smart TV');
define ( 'HS_DB_UNITY3D_tvOS_NAME',				'tvOS');

// Constants for Platform Image
define ('HS_DB_UNITY3d_iOS_IMAGE', 				'platform_logo_ios.png');
define ('HS_DB_UNITY3d_Android_IMAGE', 			'platform_logo_android.png');
define ( 'HS_DB_UNITY3D_WindowsPhone8_IMAGE',	'platform_logo_windows_phone_8.png');
define ( 'HS_DB_UNITY3D_Tizen_IMAGE',			'platform_logo_tizen');
define ( 'HS_DB_UNITY3D_Windows_IMAGE',			'platform_logo_windows.png');
define ( 'HS_DB_UNITY3D_WindowsStoreApps_IMAGE','platform_logo_windows_store_apps.png');
define ( 'HS_DB_UNITY3D_MAC_IMAGE',				'platform_logo_mac.png');
define ( 'HS_DB_UNITY3D_Linux_IMAGE',			'platform_logo_linux.png');
define ( 'HS_DB_UNITY3D_WebGL_IMAGE',			'platform_logo_webgl.png');
define ( 'HS_DB_UNITY3D_PS4_IMAGE',				'platform_logo_ps4.png');
define ( 'HS_DB_UNITY3D_PSVita_IMAGE',			'platform_logo_psvita.png');
define ( 'HS_DB_UNITY3D_XBoxOne_IMAGE',			'platform_logo_xboxone.png');
define ( 'HS_DB_UNITY3D_XBox360_IMAGE',			'platform_logo_xbox360.png');
define ( 'HS_DB_UNITY3D_WiiU_IMAGE',			'platform_logo_wii_u.png');
define ( 'HS_DB_UNITY3D_3DS_IMAGE',				'platform_logo_3ds.png');
define ( 'HS_DB_UNITY3D_OculusRift_IMAGE',		'platform_logo_oculus_rift.png');
define ( 'HS_DB_UNITY3D_GoogleCardboard_IMAGE',	'platform_logo_google_cardboard.png');
define ( 'HS_DB_UNITY3D_StreamVR_IMAGE',		'platform_logo_steam_vr.png');
define ( 'HS_DB_UNITY3D_PlaystationVR_IMAGE',	'platform_logo_playstation_vr.png');
define ( 'HS_DB_UNITY3D_GearVR_IMAGE',			'platform_logo_gear_vr.png');
define ( 'HS_DB_UNITY3D_MicrosoftHoloens_IMAGE','platform_logo_microsoft_hololens.png');
define ( 'HS_DB_UNITY3D_UniversalWindows_IMAGE','platform_logo_universal_windows.png');
define ( 'HS_DB_UNITY3D_AndroidTV_IMAGE',		'platform_logo_android_tv.png');
define ( 'HS_DB_UNITY3D_SamsungSmartTV_IMAGE',	'platform_logo_samsung_smart_tv.png');
define ( 'HS_DB_UNITY3D_tvOS_IMAGE',			'platform_logo_tv_os.png');

// Constants
	DEFINE('RELEASE_SUFFIX', '-Release');
	DEFINE('GAME_DIR', 'game_dir');
	DEFINE('SUPPORT_DIR', 'game_support_dir');
	DEFINE('CHUNK_SIZE', '10240');
	DEFINE('HS_UNITY3D_SCORE_PLUGIN_URL', plugin_dir_url(__FILE__));
//	DEFINE('UNITY3D_VERSION',array('5.6.0','5.5.1','5.3.1'));
//	$Unity3D_Version=array("5.3.1","5.5.1","5.6.0","2017.4.0f1");
	DEFINE('HS_WORDPRESS_CURRENT_USER_ID', 'HS_CURRENT_USER_ID');
	DEFINE('HS_WORDPRESS_CURRENT_USER_NAME', 'HS_CURRENT_USER_NAME');
	
	DEFINE( 'HS_UNITY3D_ERROR_NORMAL', 'Normal');
	DEFINE( 'HS_UNITY3D_ERROR_VERBOSE', 'Verbose');
	DEFINE( 'HS_UNITY3D_BUILDTYPE_PRODUCTION', 'Production');
	DEFINE( 'HS_UNITY3D_BUILDTYPE_DEVELOPMENT', 'Development');
	DEFINE( 'HS_UNITY3D_BUILDTYPE_PRODUCTION_GZIP', 'Production.Gzip');
	DEFINE( 'HS_UNITY3D_BUILDTYPE_PRODUCTION_BROTLI', 'Production.Brotli');
	

?>
