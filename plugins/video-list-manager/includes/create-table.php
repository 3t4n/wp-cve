<?php 
/**
 * Create Table
 * 
 * Create tables for plugins
 *
 * Author: Tung Pham
 */

	global $tnt_db_version;
	$tnt_db_version = "1.5";

	/**
	 * Create table : tnt_videos
	 */
	function tnt_install_videos_table(){
		global $wpdb;
		global $tnt_db_version;
		$tableName = $wpdb->prefix."tnt_videos";
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (
			  video_id int(11) NOT NULL AUTO_INCREMENT,
			  video_title varchar(255) NOT NULL,
			  video_link_type varchar(255) NOT NULL,
			  video_link varchar(255) NOT NULL,
			  video_cat int(11) NOT NULL DEFAULT '1',
			  video_status tinyint(4) NOT NULL DEFAULT '1',
			  video_order int(11) NOT NULL DEFAULT '100',
			  PRIMARY KEY (video_id),
			  KEY video_link_type (video_link_type),
			  KEY video_cat (video_cat),
			  KEY video_order (video_order)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql); 
	}

	/**
	 * Create table : tnt_videos_cat
	 */
	function tnt_install_videos_cat_table(){
		global $wpdb;
		global $tnt_db_version;
		$tableName = $wpdb->prefix."tnt_videos_cat";
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (
			  video_cat_id int(11) NOT NULL AUTO_INCREMENT,
			  video_cat_title varchar(255) NOT NULL,
			  video_cat_parent_id int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (video_cat_id),
			  KEY video_cat_parent_id (video_cat_parent_id)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql); 
	}

	/**
	 * Create table : tnt_videos_type
	 */
	function tnt_install_videos_type_table(){
		global $wpdb;
		global $tnt_db_version;
		$tableName = $wpdb->prefix."tnt_videos_type";
		$sql = "CREATE TABLE IF NOT EXISTS $tableName (
			  video_type_id int(11) NOT NULL AUTO_INCREMENT,
			  video_type_title varchar(255) NOT NULL,
			  PRIMARY KEY (video_type_id)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql); 
	}

	/**
	 * Check if ID exists in tableName
	 *
	 * @param 	string 		tableName 	name of table
	 * @param 	string 		fieldID 	name of field which contain ID
	 * @param 	int 		fieldValue 	value of field
	 * @return 	bool 		if ID = NULL ==> False
	 *						else True
	 */
	function tnt_check_id_exists($tableName, $fieldID, $fieldValue )
	{
		$check = true;
		global $wpdb;
		$id = $wpdb->get_var("SELECT $fieldID FROM $tableName WHERE $fieldID = $fieldValue;");
		if($id == null){
			$check = false;
		}
		return $check;
	}

	/**
	 * Check if title exists in tableName
	 *
	 * @param 	string 		tableName 	name of table
	 * @param 	string 		fieldID 	name of field which contain ID
	 * @param 	int 		fieldValue 	value of field
	 * @return 	bool 		if ID = NULL ==> False
	 *						else True
	 */
	function tnt_check_title_exists($tableName, $fieldTitle, $fieldValue)
	{
		$check = true;
		global $wpdb;
		$title = $wpdb->get_var("SELECT $fieldTitle FROM $tableName WHERE $fieldTitle like '$fieldValue';");
		if($title == null){
			$check = false;
		}
		return $check;
	}

	/**
	 * Insert data into database: tnt_videos_type
	 */
	function tnt_install_data_videos_type_table(){
		global $wpdb;
		global $tnt_db_version;
		$tableName = $wpdb->prefix."tnt_videos_type";
		$firstTitle = tnt_check_title_exists($tableName, "video_type_title", "Youtube");
		$secondTitle = tnt_check_title_exists($tableName, "video_type_title", "Vimeo");
		$thirdTitle = tnt_check_title_exists($tableName, "video_type_title", "DailyMotion");
		if($firstTitle == false)
		{
			$rows_affected = $wpdb->insert( $tableName, array( 'video_type_title' => 'Youtube') );
		}
		if($secondTitle == false)
		{
			$rows_affected = $wpdb->insert( $tableName, array( 'video_type_title' => 'Vimeo'));
		}
	}

	/**
	 * Insert data into database: tnt_videos_cat
	 */
	function tnt_install_data_videos_cat_table(){
		global $wpdb;
		$tableName = $wpdb->prefix."tnt_videos_cat";
		$cat_title = "Uncategorized";
		$cat_parent_id = 0;
		$firstID = tnt_check_id_exists($tableName, "video_cat_id", 1);
		if($firstID == false)
		{
			$rows_affected = $wpdb->insert( $tableName, array( 'video_cat_title' => $cat_title, 'video_cat_parent_id' => $cat_parent_id ) );	
		}
	}

	/**
	 * Update database and options (if needed)
	 */
	function tnt_update_databaseoption_videolistmanager(){
		global $wpdb;
		global $tnt_db_version;
		$tableName = $wpdb->prefix."tnt_videos_type";
		$installed_ver = get_option( "tnt_video_list_manager_db_version" );

		//Add vimeo
		if ($installed_ver != $tnt_db_version && tnt_check_title_exists($tableName, "video_type_title", "Vimeo") == false) {
			$rows_affected = $wpdb->insert( $tableName, array( 'video_type_title' => "Vimeo"));
		}

		//Add dailymotion
		if ($installed_ver != $tnt_db_version && tnt_check_title_exists($tableName, "video_type_title", "DailyMotion") == false) {
			$rows_affected = $wpdb->insert( $tableName, array( 'video_type_title' => "DailyMotion"));
		}

		if ($installed_ver != $tnt_db_version) {
			$tableName1 = $wpdb->prefix."tnt_videos";
			$sql = "CREATE TABLE $tableName1 (
				  video_id int(11) NOT NULL AUTO_INCREMENT,
				  video_title varchar(255) NOT NULL,
				  video_link_type varchar(255) NOT NULL,
				  video_link varchar(255) NOT NULL,
				  video_cat int(11) NOT NULL DEFAULT '1',
				  video_status tinyint(4) NOT NULL DEFAULT '1',
				  video_order int(11) NOT NULL DEFAULT '100',
				  date_created int(11) NOT NULL DEFAULT '0', 
				  date_modified int(11) NOT NULL DEFAULT '0',
				  user_id int(11) NOT NULL DEFAULT '0'
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			);";
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			update_option("tnt_video_list_manager_db_version", $tnt_db_version);
		}

		//Update options
		if ($installed_ver != $tnt_db_version) {
			$tntOptions = get_option('tntVideoManageOptions');
			$videoOptions = array(
				'limitPerPage'          => $tntOptions['limitPerPage'],
				'limitAdminPerPage'     => $tntOptions['limitAdminPerPage'],
				'columnPerRow'          => $tntOptions['columnPerRow'],
				'tntJquery'             => $tntOptions['tntJquery'],
				'tntColorbox'           => $tntOptions['tntColorbox'],
				'skinColorbox'          => $tntOptions['skinColorbox'],
				'videoWidth'            => $tntOptions['videoWidth'],
				'videoHeight'           => $tntOptions['videoHeight'],
				'videoOrder'            => $tntOptions['videoOrder'],
				'videoOrderBy'          => $tntOptions['videoOrderBy'],
				'socialFeature'         => $tntOptions['socialFeature'],
				'socialFeatureFB'       => $tntOptions['socialFeatureFB'],
				'socialFeatureTW'       => $tntOptions['socialFeatureTW'],
				'socialFeatureG'        => $tntOptions['socialFeatureG'],
				'socialFeatureP'        => $tntOptions['socialFeatureP'],
				'socialFeatureIconSize' => $tntOptions['socialFeatureIconSize']
	        );
	        update_option('tntVideoManageOptions', $videoOptions);
		}
	}
?>