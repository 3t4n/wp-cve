<?php
/*
Plugin Name: Easy Pixels by JEVNET
Plugin URI: https://es.wordpress.org/plugins/easy-pixels-by-jevnet/
Description:  Tracking for Analytics, Tag Manager, Google Ads, Facebook, Yandex, Bing, LinkedIn, Twitter, tracking codes
Version: 2.13
Author: JEVNET
Author URI: https://www.jevnet.es
License: GPLv2 or later
Text Domain: easy-pixels-by-jevnet
Domain Path:       /lang

*/

if ( !function_exists( 'add_action' ) ) {
	echo '¿Qué quieres hacer?';
	exit;
}

/* Translations */
add_action('plugins_loaded', 'jn_ep_load_textdomain');
function jn_ep_load_textdomain() {
	load_plugin_textdomain( 'easy-pixels-by-jevnet', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

define('JN_EasyPixels_PATH', dirname(__FILE__));
define('JN_EasyPixels_URL', plugins_url('', __FILE__));

include(JN_EasyPixels_PATH."/classes/easy-pixels.php");
include(JN_EasyPixels_PATH."/classes/easy-analytics.php");
include(JN_EasyPixels_PATH."/classes/easy-facebook.php");
include(JN_EasyPixels_PATH."/classes/easy-gads.php");
include(JN_EasyPixels_PATH."/classes/easy-bing.php");
include(JN_EasyPixels_PATH."/classes/easy-yandex.php");
include(JN_EasyPixels_PATH."/classes/easy-tw.php");
include(JN_EasyPixels_PATH."/classes/easy-linkedin.php");
include(JN_EasyPixels_PATH."/classes/easy-gTagManager.php");





register_activation_hook( __FILE__, 'easyPixels_activate' );


if( function_exists('icl_object_id') ) //Checks WPML
{
	add_action( 'wpml_loaded', 'easyPixels_run' );
}
else
{
	add_action( 'wp_loaded', 'easyPixels_run' );
}

function easyPixels_run()
{
	$easyPixels=new jn_easypixels();
	do_action('jn_load_easypixels_extensions',$easyPixels);
	apply_filters('jn_load_easypixels_extensions_init',$easyPixels);
//	add_action('jn_init_easypixels_extensions','easypixelsWC_run');
	if(is_admin())
	{
		add_action('plugins_loaded', 'jn_easypixels_load_textdomain');
		add_action('easypixels_admintabs','jn_easypixels_admintabs_basic',10);
		require(JN_EasyPixels_PATH . '/admin/easyPixelsAdmin.php');
		add_action('admin_init','jn_easypixels_saveSettings');
		add_action('admin_menu','jn_easypixels_createMenuOption');
	}
	else
	{
		add_action('admin_bar_menu', 'jn_easypixels_adminBarMsg', 100);

		add_action('easyPixelsHeaderScripts',function() use ($easyPixels){$easyPixels->trackingOptions->facebook->putTrackingCode();});
		add_action('easyPixelsHeaderScripts',function() use ($easyPixels){$easyPixels->trackingOptions->gtm->putTrackingCode();});
		add_action('easyPixelsHeaderScripts',function() use ($easyPixels){$easyPixels->trackingOptions->linkedin->putTrackingCode();});
		add_action('easyPixelsHeaderScripts',function() use ($easyPixels){$easyPixels->trackingOptions->twitter->putTrackingCode();});
		add_action('easyPixelsHeaderScripts',function() use ($easyPixels){$easyPixels->trackingOptions->bing->putTrackingCode();});
		add_action('easyPixelsHeaderScripts',function() use ($easyPixels){$easyPixels->trackingOptions->yandex->putTrackingCode();});
		
	//	add_action('easyPixelsAsyncHeaderScripts',function() use ($jn_easypixels_yandex){$jn_easypixels_yandex->putAsyncTrackingCode();});

		add_action('wp_head',function() use ($easyPixels){jn_easypixels_headerTracking($easyPixels);});
		add_action('wp_footer','jn_easypixels_footerTracking');
		add_action('easyPixelsHeaderScripts','jn_easypixels_put_gtag_code');
	}
	do_action('jn_init_easypixels_extensions',$easyPixels);
}

function jn_easypixels_headerTracking($easyPixels=null)
{
	if((!is_user_logged_in())||((!current_user_can('administrator'))&&(!current_user_can('editor')))||($easyPixels->trackAdminUsers()))
	{
		do_action('easyPixelsHeaderScripts');
	}
	else
	{
		add_action('wp_head','jn_easypixels_fakegtagFunction');

		echo '<style>
		@keyframes jnepfadeout {
			  0%   {bottom: 0;}
			  95%  {bottom: 0;}
			  100% {bottom: -3em;}
		}</style>';
		echo '<div style="position:fixed;bottom:-3em;right:0;background:#000;color:#fff;z-index:10000;padding:.2em 2em;font-size:.8em;border-radius:50px;
  animation-name: jnepfadeout;animation-duration: 5s;visibility: visible;animation-delay: 2s;">'.__('Easy Pixels is not tracking for the logged user','easy-pixels-by-jevnet').'</div>';
  		echo '<script>function showUserInfoMessage(){alert("'.__("Easy Pixels doesn't track admin and editor user profiles",'easy-pixels-by-jevnet').'");return false;}</script>';
  		jn_easypixels_fakegtagFunction();
	}
}

function jn_easypixels_adminBarMsg($admin_bar)
{
	if ( class_exists( 'jn_easypixels' ) ){$jn_EasyPixels=new jn_easypixels();}
	if((is_user_logged_in())&&((current_user_can('administrator'))||(current_user_can('editor')))&&(!$jn_EasyPixels->trackAdminUsers()))
	{
		$admin_bar->add_menu( array(
	        'id'    => 'my-item',
	        'title' => '<span class="ab-icon"><img src="'.JN_EasyPixels_URL.'/img/icon20x20.png" style="margin-top:3px;display:block"></span><span class="ab-label">'.__('User is not tracked','easy-pixels-by-jevnet').'</span>',
	        'href'  => '#',
	        'meta'  => array(
	            'title' => __('User is not tracked'),
	            'onclick'  => "showUserInfoMessage();",
	        ),
	    ));
	}
}

function jn_easypixels_fakegtagFunction()
{
	echo '<script>function gtag(event,eventname,params){}</script>';
	echo '<script>function fbq(event,type,params){}</script>';
	echo '<script>dataLayer={push:function(event,eventname,Params){}}</script>';
	echo '<script>function twq(event,eventname,Params){}</script>';
}

function jn_easypixels_put_gtag_code()
{
	$gtagCode='';
	$jn_EasyPixels=new jn_easypixels();

	if($jn_EasyPixels->trackingOptions->gads->is_enabled())
	{
		$gtagCode=$jn_EasyPixels->trackingOptions->gads->getCode();
		add_action('put_jn_google_tracking',function() use ($jn_EasyPixels){$jn_EasyPixels->trackingOptions->gads->putTrackingCode();$jn_EasyPixels->trackingOptions->gads->putForwardingCall();});
	}
	if($jn_EasyPixels->trackingOptions->analytics->is_enabled())
	{
		$gtagCode=$jn_EasyPixels->trackingOptions->analytics->getCode();
		add_action('put_jn_google_tracking',function() use ($jn_EasyPixels){$jn_EasyPixels->trackingOptions->analytics->putTrackingCode();});
	}

	if($gtagCode!='')
	{
		echo "<!-- Easy Pixels: Global site tag (gtag.js) - Google Analytics --><script async src='https://www.googletagmanager.com/gtag/js?id=".$gtagCode."'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());";
			do_action('put_jn_google_tracking');
		echo "</script>";
	}
}

function jn_easypixels_footerTracking()
{
	if ( class_exists( 'jn_easypixels' ) ){$jn_EasyPixels=new jn_easypixels();}
	if((!is_user_logged_in())||((!current_user_can('administrator'))&&(!current_user_can('editor')))||($jn_EasyPixels->trackAdminUsers()))
	{
		do_action('jn_easyPixels_footer');
	}
}


/* Translations */
function jn_easypixels_load_textdomain() {
	load_plugin_textdomain( 'easy-pixels-by-jevnet', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}






function easyPixels_activate()
{
	$easyPixels=new jn_easypixels();
//	echo $easyPixels->getVersion(). " - ".$easyPixels::get_plugin_version();
//    if($easyPixels->getVersion()!=$easyPixels::get_plugin_version())
/*	if(true)
	{
		include(JN_EasyPixels_PATH."/utils/update.php");
	}*/
}


