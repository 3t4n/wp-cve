<?php
/*
Plugin Name: Show Visitor IP
Plugin URI: http://wordpress.org/plugins/show-visitor-ip/
Description: This plgin show the current user ip address & other location info by ip. Short-code [show_ip], [svip_location type="countryCode"] regarding another shortcode please check the plugin readme file or visit on plugin website.
Author: Vikas Sharma
Version: 5.2
Author URI: https://profiles.wordpress.org/devikas301
*/

 function showVisitorIp(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters('wpb_get_ip', $ip);
 }
 add_shortcode('show_ip', 'showVisitorIp'); 
 
 function showVisitorLocationByIp($svip){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)){		
       $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
       $ip = $forward;
    } else {
        $ip = $remote;
    }
	
	$url = 'http://ip-api.com/json/'. $ip.'?fields=status,country,countryCode,region,regionName,city,lat,lon,timezone,currency';
	$response = wp_remote_get($url);	
	
    $svip_data = '';
    $svip_ltype = $svip['type'];
	
	if(is_array($response)){
		
		$ip_data = json_decode($response['body'], true);
		
		if($ip_data){		
		 if($svip_ltype == 'countryCode' || strpos($svip_ltype, 'countryCode') !== false){
		   $svip_data = $ip_data['countryCode'];
		 } elseif($svip_ltype == 'region' || strpos($svip_ltype, 'region') !== false){
		   $svip_data = $ip_data['regionName'];
		 } elseif($svip_ltype == 'lat' || strpos($svip_ltype, 'lat') !== false){
		   $svip_data = $ip_data['lat'];
		 } elseif($svip_ltype == 'long' || strpos($svip_ltype, 'long') !== false){
		   $svip_data = $ip_data['lon'];
		 } elseif($svip_ltype == 'city' || strpos($svip_ltype, 'city') !== false){
		   $svip_data = $ip_data['city'];
		 } elseif($svip_ltype == 'timeZone' || strpos($svip_ltype, 'timeZone') !== false){
		   $svip_data = $ip_data['timezone'];
		 } elseif($svip_ltype == 'currency' || strpos($svip_ltype, 'currency') !== false){
		   $svip_data = $ip_data['currency'];
		 } else {
		   $svip_data = $ip_data['country'];
		 }
		}
	}
    return $svip_data;
 }
 add_shortcode('svip_location', 'showVisitorLocationByIp');       
?>