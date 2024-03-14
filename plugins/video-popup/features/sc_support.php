<?php

defined( 'ABSPATH' ) or die(':)');


function video_popup_support_soundcloud(){
	if( isset($_GET['vp_soundcloud']) ){

		$url = $_GET['vp_soundcloud'];

		if( filter_var($url, FILTER_VALIDATE_URL) === false ){
			exit();
		}

		if( !preg_match("/(soundcloud.com)/", $url) ){
			exit();
		}

		if( isset($_GET['vp_soundcloud_a']) and $_GET['vp_soundcloud_a'] == 'true' ){
			$auto_play = 'true';
		}else{
			$auto_play = 'false';
		}

		$get = wp_remote_get("http://soundcloud.com/oembed?url=$url&format=json&auto_play=$auto_play");

		$retrieve = wp_remote_retrieve_body($get);
		$result = json_decode($retrieve, true);

		if( preg_match("/(errors)+/", $retrieve) ){
			return false;
		}

		$track_html = $result['html'];

		preg_match_all('~src="(.*)"~', $track_html, $matches);

		$track_link = esc_url($matches[1][0]);

		$ex = explode('url', $track_link);

		$eq = preg_replace("/(&)+(.*)/", null, $ex[1]);

		$remove_eq = str_replace('=', null, $eq);

		wp_redirect("https://w.soundcloud.com/player/?visual=true&url=$remove_eq&show_artwork=true&auto_play=$auto_play");

		exit();
	}
}
add_action('init', 'video_popup_support_soundcloud');