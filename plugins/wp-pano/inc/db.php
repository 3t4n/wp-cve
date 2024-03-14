<?php 
if (!function_exists('add_action')) die('Access denied');

define('WPPANO_HOTSPOTS_TABLE', 'wppano_hotspots');
global $wppano_db_version;
$wppano_db_version = "1.0";

register_activation_hook(WPPANO_BASEFILE, 'wppano_table_install');
function wppano_table_install () {   
	global $wpdb;
	global $wppano_db_version;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE " . $table_name . " (
			id int(10) NOT NULL AUTO_INCREMENT,
			post_id int(10) DEFAULT '0' NOT NULL,
			vtour_name varchar(255) NOT NULL COLLATE utf8_general_ci,
			pano varchar(255) NOT NULL COLLATE utf8_general_ci,
			scene varchar(255) NOT NULL COLLATE utf8_general_ci,
			data varchar(255) NOT NULL COLLATE utf8_general_ci,
			thumb longtext NOT NULL COLLATE utf8_general_ci,
			PRIMARY KEY  (id),
			KEY vtour_name (vtour_name),
			KEY pano (pano),
			KEY scene (scene)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
		add_option("wppano_db_version", $wppano_db_version);
		
		$arg = array(
			'type' => array(
				'post' => 'post'
			),
			'hs_style' => array(
				'post' => 'hs_info'
			),
			'window' => array(
				'post' => 'standard'
			)
		);
		
		add_option('wppano_vtourpath', '');
		add_option('wppano_vtourjs', '');
		add_option('wppano_vtourxml', '');
		add_option('wppano_vtourswf', '');
		add_option('wppano_target_container', '');
		add_option('user_script_before', '');
		add_option('user_script_after', '');
		add_option('wppano_posttype', $arg);
}

function wppano_get_hotspots_by_post_id ($post_id) {  
	global $wpdb;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	$sql = $wpdb->prepare(
		"
			SELECT      *
			FROM        $table_name
			WHERE       post_id = %d
		",
		$post_id);
	return $wpdb->get_results($sql, 'ARRAY_A');
}

function wppano_get_hotspots ($vtour_name, $pano, $scene) { 
	global $wpdb;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	$sql = $wpdb->prepare(
		"
			SELECT      post_id, data
			FROM        $table_name
			WHERE       vtour_name = %s AND pano = %s AND scene = %s
			ORDER BY 	post_id
		",
		$vtour_name, $pano, $scene);
	return $wpdb->get_results($sql, 'ARRAY_A'); 	
}
	
function wppano_add_hotspot ($arg) {  
	global $wpdb;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	$s_data = serialize($arg['data']);
	$sql = $wpdb->prepare(
			"
				INSERT INTO $table_name
				( post_id, vtour_name, pano, scene, data) 
				VALUES ( %d, %s, %s, %s, %s )
			",
			$arg['post_id'], $arg['vtour_name'], $arg['pano'], $arg['scene'], $s_data);
	return $wpdb->query($sql);
}

function wppano_update_hotspot ($arg, $data) { 
	global $wpdb;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	$s_data = serialize($data);
	$sql = $wpdb->prepare(
			"
				UPDATE 		$table_name
				SET 		data = %s
				WHERE       post_id = %d AND vtour_name = %s AND pano = %s AND scene = %s
			",
			$s_data, $arg['post_id'], $arg['vtour_name'], $arg['pano'], $arg['scene']);
	return $wpdb->query($sql);	
}

function wppano_delete_hotspot ($arg) {
	global $wpdb;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	$sql = $wpdb->prepare(
			"
				DELETE FROM $table_name
				WHERE post_id = %d AND vtour_name = %s AND pano = %s AND scene = %s
			",
			$arg['post_id'], $arg['vtour_name'], $arg['pano'], $arg['scene']);
	return $wpdb->query($sql);
}

function wppano_Delete_Hotspots_by_post_id ($post_id) {
	global $wpdb;
	$table_name = $wpdb->prefix . WPPANO_HOTSPOTS_TABLE;
	$sql = $wpdb->prepare(
			"
				DELETE FROM $table_name
				WHERE post_id = %d
			",
			$post_id);
	return $wpdb->query($sql);
}
?>