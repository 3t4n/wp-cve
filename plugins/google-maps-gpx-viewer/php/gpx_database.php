<?php
/*
gpx_database.php, V 1.2, altm, 20.09.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
*/
	// global tablename of GPX files;
	$gmap_gpx_table_name = $wpdb->prefix . "gmap_poi_db";
	global $wpdb;
	if($wpdb->get_var("SHOW TABLES LIKE '$gmap_gpx_table_name'") != $gmap_gpx_table_name) {
		poi_db_install();
	}
	
	/* debug to file */
 	// function eddi_debug($res ) {
		// $upload_d = wp_upload_dir();
		// $filename = $upload_d['basedir'] . '/debug.txt';
		// if ($handle = fopen($filename, "a+")) {
			// fwrite($handle, var_export($res,true)."\r\n");
			// fclose($handle);
		// }
	// }
	
	// on save & update poi_db POIs only
	add_action( 'wp_ajax_gmap_poi_action', 'instance_gpx_callback' );
	add_action( 'wp_ajax_nopriv_gmap_poi_action', 'instance_gpx_callback' );

	function instance_gpx_callback() {
		global $wpdb;
		$action	= isset($_POST["action"]) ? $_POST["action"] : "";
		$post_id	= isset($_POST["post_id"]) ? $_POST["post_id"] : "";
		
		if($action == 'gmap_poi_action' && $post_id ){
			if ($_POST["lat"] && $_POST["lng"]){ // update or insert
				if($_POST["poi_db_id"]) // update
					$ret = poi_get_item_by_id($_POST["poi_db_id"]); // by Poi id
				else	
					$ret = poi_find_data($_POST); // by lat-lng
				
				if (count($ret) > 0){
				
					$update_id = $ret[0]->id;
					if($_POST["new_lat"] && $_POST["new_lng"]){ // position has changed
						$_POST["lat"] = $_POST["new_lat"];
						$_POST["lng"] = $_POST["new_lng"];
						$ret = poi_update_data($update_id, $_POST);
					}
					else
						$ret = poi_update_data($update_id, $_POST);
						
					if($ret == false) 
						exit('error on update!');
					else {
						$ret = poi_get_item_by_id($update_id);
						$ret['status'] =  'updated';
						exit(json_encode($ret));
					}
				}  else  {
					if($_POST["gmap_poi_act_map"] > 0 && strlen(get_option('gmap_v3_gpx_proKey')) != 32)
						exit('error on insert!');
					$ret = poi_insert_data($_POST);
					if($ret == 1){
						$ret = poi_get_item_by_id($wpdb->insert_id);
						$ret['status'] = 'inserted';
						exit(json_encode($ret));
					}
					else
						exit('error on insert!');
				}
			} else 
				die('error on inner service request!');
		} else {
			$action	= isset($_GET["action"]) ? $_GET["action"] : "";
			$post_id	= isset($_GET["post_id"]) ? $_GET["post_id"] : "";
			
			if($action == 'gmap_poi_action' && $post_id){
				if($_GET["get_pois"] == "true"){
					exit(json_encode(post_get_pois_only($post_id)));
				}			
				else if($_GET["del_pois"] == "true"){
						exit(json_encode(post_delete_poidb_item($_GET["poi_db_id"])));//
				}
			} else
				die('error on get request!');
		}
		die('error on service request!');
	}

	// save or update poi_db GPX only
	add_action( 'save_post', 'gmv3_update_gpxdata' );
	function gmv3_update_gpxdata( $post_id ) {

		//verify post is not a revision
		if ( !wp_is_post_revision( $post_id ) ) {
			// find gpx
			$post_title = get_the_title( $post_id );
			$content_post = get_post($post_id);
			$content = $content_post->post_content;

			
			// find GPX tracks in map
			preg_match_all('/\[map .* gpx\s*=\s*"(http:\/\/.+?\.gpx)\s*".*\]/i', $content , $matches);
			$match = $matches[1];

			for ($i = 0; $i < count($match); $i++) { // get gpx's from post 
				// check if download button enabled
				if(preg_match('/.* download\s*=\s*"(yes)\s*".*/i', $matches[0][$i] , $database)){
					$a_gpx_map = array('gpx' => $match[$i], 'download'  => 1);
				} else {	
					if(preg_match('/.* download\s*=\s*"(no)\s*".*/i', $matches[0][$i] , $database)){
						$a_gpx_map = array('gpx' => $match[$i], 'download'  => 0);
					} else {
						if(get_option('gmap_v3_gpx_downloadLink') == 1)
							$a_gpx_map = array('gpx' => $match[$i], 'download'  => 1);
						else
							$a_gpx_map = array('gpx' => $match[$i], 'download'  => 0);
					}
				}
				$maps[] = $a_gpx_map;
			}
			// sanitize tracks db
			$post_tracks = post_get_gpxfiles_only($post_id); // get db post tracks
			for ($i = 0; $i < count($post_tracks); $i++) {
				// check if track in db
				$found = false;
				$dload = false;
				// $id = -1;
				for ($j = 0; $j < count($maps); $j++) {
					if($post_tracks[$i]->item_url == $maps[$j]['gpx']){ 
						$found = true;
						if($content_post->post_status == 'publish'){ // only if published otherwise deactivate. gpx!
							if($post_tracks[$i]->download != $maps[$j]['download']) 
								update_item_download($post_tracks[$i]->id,$maps[$j]['download']);
						} else {
							update_item_download($post_tracks[$i]->id,'no');
						}
					}
				}
				if(!$found){
					post_delete_poidb_item($post_tracks[$i]->id);
				} 
			}		
		}
	}

	// delete GPX & POIs poi_db entries of this post 
	add_action( 'delete_post', 'gmv3_delete_poidb_data' );
	function gmv3_delete_poidb_data( $post_id ) {
		global $wpdb, $gmap_gpx_table_name;
		$ret = $wpdb->query( $wpdb->prepare( "DELETE FROM $gmap_gpx_table_name WHERE post_id = %d", $post_id ));
		return $ret;
	}

	// map manager save or update gpx-file to DB
	function eddi_gpx_database($post_id, $description, $startCoords, $gpxfile ) {
		global $wpdb, $gmap_gpx_table_name;
		$upload_d = wp_upload_dir();
		$baseurl = $upload_d['baseurl'];
		$basedir = $upload_d['basedir'];
		$pos = explode(",", $startCoords);
		$lat = $pos[0];
		$lng = $pos[1];

		if (file_exists($basedir."/".$gpxfile) && count($pos) > 1) {
			$data['post_id'] = $post_id;
			$data['item_type'] = "gpx_file";
			$data['item_url'] = $baseurl."/".$gpxfile;
			$data['lat'] = $lat;
			$data['lng'] = $lng;
			$data['description'] = $description;
			$id = post_get_item_url($post_id, $data['item_url']);
			if($id > 0){
				poi_update_data($id, $data);			
			} 
			else {
				poi_insert_data($data);
			}
		}	
	}

	/*
	* Database function
	*/		
	function poi_db_install() {
		global $wpdb, $gmap_gpx_table_name;

		// add charset & collate like wp core
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$charset_collate = '';
		if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}

		$sql = "CREATE TABLE $gmap_gpx_table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  post_id VARCHAR(25) DEFAULT '' NOT NULL,
	  lat FLOAT (20,12),
	  lng FLOAT (20,12),
	  item_name tinytext NOT NULL,
	  item_type VARCHAR(155) DEFAULT '' NOT NULL,
	  item_url VARCHAR(255) DEFAULT '' NOT NULL,
	  download TINYINT DEFAULT '0' NOT NULL,
	  description text NOT NULL,
	  city VARCHAR(155) DEFAULT '' NOT NULL,
	  street VARCHAR(155) DEFAULT '' NOT NULL,
	  contact VARCHAR(155) DEFAULT '' NOT NULL,
	  PRIMARY KEY  (id)
		); $charset_collate;";
		dbDelta($sql);
	}
 
	function check_data($data) {
		if ($data['item_name'] ==  __( 'Place name', GPX_GM_PLUGIN )) {
			$data['item_name'] = "";
		}			  
		if ($data['city'] ==  __( 'Zip & City', GPX_GM_PLUGIN )) {
			$data['city'] = "";
		}	
		if ($data['street'] ==  __( 'Street & No.', GPX_GM_PLUGIN )) {
			$data['street'] = "";
		}	
		if ($data['contact'] ==  __( 'Contact person, phone', GPX_GM_PLUGIN )) {
			$data['contact'] = "";
		}	
		if ($data['item_url'] ==  "http://") {
			$data['item_url'] = "";
		}	
		if ($data['description'] ==  __( 'Description', GPX_GM_PLUGIN )) {
			$data['description'] = "";
		}	
		reset($data);
		while (list($key, $val) = each($data)) {
			$data[$key] = trim($data[$key]);
		}
		return $data;
	}

	function poi_insert_data($data) {
		global $wpdb, $gmap_gpx_table_name;
		$data = check_data($data);
		return $wpdb->insert(
			$gmap_gpx_table_name, 
			array( 
				'post_id' => $data['post_id'],
				'time' => current_time('mysql'), 
				'lat' => $data['lat'],
				'lng' => $data['lng'],
				'item_name' => $data['item_name'],
				'item_type' => $data['item_type'],
				'item_url' => $data['item_url'],
				'city' => $data['city'],
				'street' => $data['street'],
				'description' => $data['description'],
				'download' => $data['download'],
				'contact' => $data['contact'] 
			)
		);
	}

	function poi_update_data($id, $data) {
		global $wpdb, $gmap_gpx_table_name;
		$data = check_data($data);
		return $wpdb->update( 
			$gmap_gpx_table_name, 
			array( 
				'post_id' => $data['post_id'],
				'time' => current_time('mysql'), 
				'lat' => $data['lat'],
				'lng' => $data['lng'],
				'item_name' => $data['item_name'],
				'item_type' => $data['item_type'],
				'item_url' => $data['item_url'],
				'city' => $data['city'],
				'street' => $data['street'],
				'description' => $data['description'],
				'download' => $data['download'],
				'contact' => $data['contact'] 
			),
			array('id' => $id)
		);
	}

	// returns all poi of post with specific item_url
	function post_get_item_url($post_id, $item_url) {
		global $wpdb, $gmap_gpx_table_name;
		$update_id = -1;
		$ret = $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name WHERE post_id = '$post_id' AND item_url = '$item_url'");
		if (count($ret) > 0)
			$update_id = $ret[0]->id;
		return $update_id;
	}

	// update download
	function update_item_download($id, $download) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->update( 
			$gmap_gpx_table_name, 
			array( 
				'download' => $download
			),
			array('id' => $id)
		);
	}	

	// returns all pois of database
	function  post_get_pois_all() {
		global $wpdb, $gmap_gpx_table_name; 
			return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name  WHERE download = '1'");
	}

	// returns all post title with gpx/pois
	function  gpx_rest_all_post_title() {
		global $wpdb, $gmap_gpx_table_name; 
			return $wpdb->get_results(
				"SELECT $wpdb->posts.ID, $wpdb->posts.post_title, $gmap_gpx_table_name.item_type, $gmap_gpx_table_name.description " .
				"FROM $wpdb->posts " .
				"JOIN $gmap_gpx_table_name " .
				"ON $wpdb->posts.ID=$gmap_gpx_table_name.post_id AND $wpdb->posts.post_status='publish' " .
				"ORDER BY $wpdb->posts.post_name"
			);
	// return $wpdb->posts;

	}

	
	// returns all pois of database within bounds
	function post_get_pois_bounds($bounds) {
		global $wpdb, $gmap_gpx_table_name; 
		if( count($bounds) == 4)
			return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name  
			WHERE lat < '$bounds[0]'
			AND lat > '$bounds[2]' 
			AND lng < '$bounds[1]' 
			AND lng > '$bounds[3]' 
			AND download = '1'");
	}

	// returns all pois of post 
	function post_get_all($post_id) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name 
		WHERE post_id = $post_id");
	}
	// returns all pois of post 
	function post_get_pois_only($post_id) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name 
		WHERE post_id = $post_id
		AND item_type != 'gpx_file' 
		AND item_type != 'gpx_bike' 
		AND item_type != 'gpx_ride' 
		AND item_type != 'gpx_hike'");
	}

	// returns all pois of post 
	function post_get_gpxfiles_only($post_id) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name 
		WHERE post_id = $post_id 
		AND (item_type = 'gpx_file' 
		OR item_type = 'gpx_bike' OR item_type = 'gpx_ride' OR item_type = 'gpx_hike')");
	}

	// returns poi by id 
	function poi_get_item_by_id($poi_id) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name WHERE id = '$poi_id'");
	}

	// returns poi from post at pos lat/lng
	function poi_find_data($post) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name WHERE post_id = '$post[post_id]' AND lat = '$post[lat]' AND lng = '$post[lng]'");
	}

	// delete poi with id
	function post_delete_poidb_item($id) {
		global $wpdb, $gmap_gpx_table_name;
		return $wpdb->query( $wpdb->prepare( "DELETE FROM $gmap_gpx_table_name WHERE id = %d", $id ));
	}
?>