<?php
if (!function_exists('add_action')) die('Access denied');

include_once('view/admin-settings.php');
include_once('view/admin-add-hotspots.php');
//include_once('view/admin-edit-vtours.php');

add_action('wp_ajax_wppano_AddNewHotspot', 'wppano_AddNewHotspot');
function wppano_AddNewHotspot(){
	$nonce = $_REQUEST['nonce'];
	if ( !wp_verify_nonce( $nonce, 'wppano-nonce' ) ) { die ('Access denied');}

	$hotspot_data = array(
		'ath' =>  		sanitize_text_field(floatval($_REQUEST["ath"])),
		'atv' =>  		sanitize_text_field(floatval($_REQUEST["atv"])),
		'style' =>  	''
		);		
	$arg = array(
		'post_id' => intval($_REQUEST["post_id"]),
		'vtour_name' => sanitize_text_field($_REQUEST["vtourname"]),
		'pano' => sanitize_text_field($_REQUEST["pano_name"]),
		'scene' => sanitize_text_field($_REQUEST["scene_name"]),
		'data' => $hotspot_data
	);
	if ( wppano_add_hotspot($arg) ) $result['type'] = "success"; else $result['type'] = "error";
	echo json_encode($result);
	wp_die();
}	

add_action('wp_ajax_wppano_UpdateHotspot', 'wppano_UpdateHotspot');
function wppano_UpdateHotspot(){
	$nonce = $_REQUEST['nonce'];
	$result['type'] = "error";
	if ( !wp_verify_nonce( $nonce, 'wppano-nonce' ) ) wp_die();
	$data = array(
		'ath' =>  		sanitize_text_field(floatval($_REQUEST["ath"])),
		'atv' =>  		sanitize_text_field(floatval($_REQUEST["atv"])),
		'style' =>  	''
		);
	$arg = array(
		'post_id' =>  	intval($_REQUEST["post_id"]),	
		'vtour_name' => sanitize_text_field($_REQUEST["vtourname"]),
		'pano' => 		sanitize_text_field($_REQUEST["pano_name"]),
		'scene' =>  	sanitize_text_field($_REQUEST["scene_name"])
		);
	$count = wppano_update_hotspot ($arg, $data);
	if ( $count ) $result['type'] = "success";
	if ( $count == 0 ) $result['type'] = "nochanges";
	echo json_encode($result);
	wp_die();
}

add_action('wp_ajax_wppano_DeleteHotspot', 'wppano_DeleteHotspot');
function wppano_DeleteHotspot(){
	$nonce = $_REQUEST['nonce'];
	if ( !wp_verify_nonce( $nonce, 'wppano-nonce' ) ) { die ('Access denied');}
	$arg = array(
		'post_id' =>  	intval($_REQUEST["post_id"]),
		'vtour_name' => sanitize_text_field($_REQUEST["vtourname"]),
		'pano' => 		sanitize_text_field($_REQUEST["pano_name"]),
		'scene' =>  	sanitize_text_field($_REQUEST["scene_name"])
		);
	if ( wppano_delete_hotspot($arg) ) $result['type'] = "success"; else $result['type'] = "error";
	$result['type'] = "success";
	echo json_encode($result);
	wp_die();
}

add_action( 'delete_post', 'wppano_Delete_Hotspots' );
function wppano_Delete_Hotspots($post_id){
   wppano_Delete_Hotspots_by_post_id($post_id);
}

?>