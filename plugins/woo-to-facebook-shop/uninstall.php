<?php
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
		$wctofb_site_url = 'https://fbshop.premium-themes.co';
		// remove user fanpage and store from premium themes.co as user uninstall the plugin
		$wctofb_api = esc_attr(get_option('wctofb_api'));
		if(!empty($wctofb_api)){
		$jsondata = array("apikey"=>$wctofb_api,"action"=>"delete");
		$sendjson = json_encode($jsondata);
		$url = $wctofb_site_url."/apicalls/wp/plugin_delete";
		$apisucces = wp_remote_post( $url, array(
		'headers' => array( 'Content-Type' => 'application/json' ),
		'method' => 'POST',
		'timeout' => 200,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'sslverify'=> true,
		'body' => $sendjson,
		'cookies' => array()) );
		}
		$apiresponse = $apisucces['body'];
		$apimessage = json_decode($apiresponse);
		$apimessage = (int)$apimessage->{'message'};
		// Delete table and option when plugin delete
		global $wpdb;
		$table_name = $wpdb->prefix . 'wctofb';
		$sql = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query($sql);
		delete_option('wctofb_api');
		delete_option('wctofb_apikey_success');
		delete_option('wctofb_runonce');
		delete_option('wctofb_pg_version');