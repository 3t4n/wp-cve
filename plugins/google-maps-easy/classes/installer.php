<?php
#[AllowDynamicProperties]
class installerGmp {
	static public $update_to_version_method = '';
	static public function init() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$current_version = get_option($wpPrefix. GMP_DB_PREF. 'db_version', 0);
		$installed = (int) get_option($wpPrefix. GMP_DB_PREF. 'db_installed', 0);
		/**
		 * modules
		 */
		if (!dbGmp::exist("gmp_modules")) {

			$charset_collate = $wpdb->get_charset_collate();
			$table_name = $wpdb->prefix . 'gmp_modules';
			$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
	 			 `code` varchar(64) NOT NULL,
	 			 `active` tinyint(1) NOT NULL DEFAULT '0',
	 			 `type_id` smallint(3) NOT NULL DEFAULT '0',
	 			 `params` text,
	 			 `has_tab` tinyint(1) NOT NULL DEFAULT '0',
	 			 `label` varchar(128) DEFAULT NULL,
	 			 `description` text,
	 			 `ex_plug_dir` varchar(255) DEFAULT NULL,
	 			 PRIMARY KEY (`id`),
	 			 UNIQUE INDEX `code` (`code`)
			) $charset_collate";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			$tableName = $wpdb->prefix . "gmp_modules";

			$wpdb->insert($tableName, array(
					'code' => 'adminmenu',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 0,
					'label' => 'Admin Menu',
					'description' => '',
			));
			$wpdb->insert($tableName, array(
					'code' => 'options',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 1,
					'label' => 'Options',
					'description' => '',
			));
			$wpdb->insert($tableName, array(
					'code' => 'user',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 1,
					'label' => 'Users',
					'description' => '',
			));
			$wpdb->insert($tableName, array(
					'code' => 'templates',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 1,
					'label' => 'Templates for Plugin',
					'description' => '',
			));
			$wpdb->insert($tableName, array(
						'code' => 'shortcodes',
						'active' => 1,
						'type_id' => 6,
						'params' => '',
						'has_tab' => 0,
						'label' => 'Shortcodes',
						'description' => 'Shortcodes data',
			));
			$wpdb->insert($tableName, array(
					'code' => 'gmap',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 1,
					'label' => 'Gmap',
					'description' => 'Gmap',
			));
			$wpdb->insert($tableName, array(
					'code' => 'marker',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 0,
					'label' => 'Markers',
					'description' => 'Maps Markers',
			));
			$wpdb->insert($tableName, array(
					'code' => 'marker_groups',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 0,
					'label' => 'Marker Groups',
					'description' => 'Marker Groups',
			));
			$wpdb->insert($tableName, array(
					'code' => 'supsystic_promo',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 0,
					'label' => 'Promo',
					'description' => 'Promo',
			));
			$wpdb->insert($tableName, array(
					'code' => 'icons',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 1,
					'label' => 'Marker Icons',
					'description' => 'Marker Icons',
			));
			$wpdb->insert($tableName, array(
					'code' => 'mail',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 1,
					'label' => 'mail',
					'description' => 'mail',
			));
		}
		/**
		 *  modules_type
		 */
		 if(!dbGmp::exist("gmp_modules_type")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_modules_type` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `label` varchar(64) NOT NULL,
			  PRIMARY KEY (`id`)
			) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");
			$tableName = $wpdb->prefix . "gmp_modules_type";
			$wpdb->insert($tableName, array(
					'id' => 1,
					'label' => 'system',
			));
			$wpdb->insert($tableName, array(
					'id' => 4,
					'label' => 'widget',
			));
			$wpdb->insert($tableName, array(
					'id' => 6,
					'label' => 'addons',
			));
			$wpdb->insert($tableName, array(
					'id' => 7,
					'label' => 'template',
			));
		}
		/**
		 * options
		 */
		 if(!dbGmp::exist("gmp_options")) {
 			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_options` (
 			  `id` int(11) NOT NULL AUTO_INCREMENT,
 			  `code` varchar(64) CHARACTER SET latin1 NOT NULL,
 			  `value` text NULL,
 			  `label` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
 			  `description` text CHARACTER SET latin1,
 			  `htmltype_id` smallint(2) NOT NULL DEFAULT '1',
 			  `params` text NULL,
 			  `cat_id` mediumint(3) DEFAULT '0',
 			  `sort_order` mediumint(3) DEFAULT '0',
 			  `value_type` varchar(16) CHARACTER SET latin1 DEFAULT NULL,
 			  PRIMARY KEY (`id`),
 			  KEY `id` (`id`),
 			  UNIQUE INDEX `code` (`code`)
 			) DEFAULT CHARSET=utf8");

 			$tableName = $wpdb->prefix . "gmp_options";
 			$wpdb->insert($tableName, array(
 					'code' => 'save_statistic',
 					'value' => '0',
 					'label' => 'Send statistic',
 			));
 			$wpdb->insert($tableName, array(
 					'code' => 'infowindow_size',
 					'value' => utilsGmp::serialize(array('width'=>'100','height'=>'100')),
 					'label' => 'Info Window Size',
 			));
 		}

		/* options categories */
		if(!dbGmp::exist("gmp_options_categories")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_options_categories` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `label` varchar(128) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id` (`id`)
			) DEFAULT CHARSET=utf8");

			$tableName = $wpdb->prefix . "gmp_options_categories";
			if (empty($wpdb->get_var("SELECT ID FROM {$wpdb->prefix}gmp_options_categories WHERE label = 'General'"))) {
				$wpdb->insert($tableName, array(
						'id' => 1,
						'label' => 'General',
				));
			}
			if (empty($wpdb->get_var("SELECT ID FROM {$wpdb->prefix}gmp_options_categories WHERE label = 'Template'"))) {
				$wpdb->insert($tableName, array(
						'id' => 2,
						'label' => 'Template',
				));
			}
			if (empty($wpdb->get_var("SELECT ID FROM {$wpdb->prefix}gmp_options_categories WHERE label = 'Subscribe'"))) {
				$wpdb->insert($tableName, array(
						'id' => 3,
						'label' => 'Subscribe',
				));
			}
			if (empty($wpdb->get_var("SELECT ID FROM {$wpdb->prefix}gmp_options_categories WHERE label = 'Social'"))) {
				$wpdb->insert($tableName, array(
						'id' => 4,
						'label' => 'Social',
				));
			}
		}

		/*
		* Create table for map
		*/
    if(!dbGmp::exist("gmp_maps")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_maps` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(125) CHARACTER SET utf8  NOT NULL,
				`description` text CHARACTER SET utf8 NULL,
				`params` text NULL,
				`html_options` text NOT NULL,
				`create_date` datetime,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `id` (`id`)
			  ) DEFAULT CHARSET=utf8");
		}
		/**
		 * Create table for markers
		 */
		if(!dbGmp::exist("gmp_markers")) {
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_markers"."` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`title` varchar(125) CHARACTER SET utf8 NOT NULL,
					`description` text CHARACTER SET utf8 NULL,
					`coord_x` varchar(30) CHARACTER SET utf8 NOT NULL,
					`coord_y` varchar(30) CHARACTER SET utf8 NOT NULL,
					`icon` int(11),
					`map_id` int(11),
					`marker_group_id` int(11),
					`address` text CHARACTER SET utf8,
					`animation` int(1),
					`create_date` datetime,
					`params` text  CHARACTER SET utf8 NOT NULL,
					`sort_order` smallint(1) NOT NULL DEFAULT '0',
					`user_id` bigint(20),
					PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8");
		}
		// if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."markers", 'sort_order')) {
		// 	dbGmp::query("ALTER TABLE `@__markers` ADD COLUMN `sort_order` smallint(1) NOT NULL DEFAULT '0';");
		// }
		// if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."markers", 'user_id')) {
		// 	dbGmp::query("ALTER TABLE `@__markers` ADD COLUMN `user_id` bigint(20);");
		// }
		/**
		 * Create table for marker Icons
		 */
		if(!dbGmp::exist("gmp_icons")){
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_icons"."` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`title` varchar(100) CHARACTER SET utf8,
				`description` text CHARACTER SET utf8,
				`width` MEDIUMINT(5) NOT NULL DEFAULT '0',
				`height` MEDIUMINT(5) NOT NULL DEFAULT '0',
				`path` varchar(250) CHARACTER SET utf8,
				`is_def` tinyint(1) NOT NULL DEFAULT '0',
				 PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8");
		} else {
			 global $wpdb;
			 $result = $wpdb->get_row("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}gmp_icons' AND COLUMN_NAME = 'width'");
			 if(!$result) {
				 $wpdb->query("ALTER TABLE {$wpdb->prefix}gmp_icons ADD COLUMN `width` MEDIUMINT");
			 }
			 $result = $wpdb->get_row("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}gmp_icons' AND COLUMN_NAME = 'height'");
			 if(!$result) {
				 $wpdb->query("ALTER TABLE {$wpdb->prefix}gmp_icons ADD COLUMN `height` MEDIUMINT");
			 }
			 $result = $wpdb->get_row("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}gmp_icons' AND COLUMN_NAME = 'is_def'");
			 if(!$result) {
				 $wpdb->query("ALTER TABLE {$wpdb->prefix}gmp_icons ADD COLUMN `is_def` tinyint");
			 }
		 }


		/**
		 * Create table for marker groups
		 */
		if(!dbGmp::exist("gmp_marker_groups")){
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_marker_groups"."` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`title` varchar(250) CHARACTER SET utf8,
					`description` text CHARACTER SET utf8,
					`params` text CHARACTER SET utf8,
					`parent` tinyint(1) NOT NULL DEFAULT '0',
					`sort_order` smallint(1) NOT NULL DEFAULT '0',
				 PRIMARY KEY (`id`)
				  ) DEFAULT CHARSET=utf8");
		}
		// if(!dbGmp::exist($wpPrefix."gmp_marker_groups", 'params')) {
		// 	dbGmp::query("ALTER TABLE @__marker_groups ADD params text CHARACTER SET utf8;");
		// }
		// if(!dbGmp::exist($wpPrefix."gmp_marker_groups", 'sort_order')) {
		// 	dbGmp::query("ALTER TABLE @__marker_groups ADD sort_order smallint(1) NOT NULL DEFAULT '0';");
		// }
		// if(!dbGmp::exist($wpPrefix.GMP_DB_PREF."marker_groups", 'parent')) {
		// 	dbGmp::query("ALTER TABLE @__marker_groups ADD parent tinyint(1) NOT NULL DEFAULT '0' AFTER params;");
		// }
		// $markerGroupsClearedInvalid = get_option($wpPrefix. GMP_DB_PREF. 'mg_cleared_inv', 0);
		// if(!$markerGroupsClearedInvalid) {
		// 	dbGmp::query('UPDATE @__markers SET marker_group_id = 0 WHERE marker_group_id = 1');	// This was wrong update in markers table before - fix this one time before update plugin
		// 	update_option($wpPrefix. GMP_DB_PREF. 'mg_cleared_inv', 1);
		// }
		/**
		* Plugin usage statistics
		*/
		if(!dbGmp::exist("gmp_usage_stat")) {
			dbDelta("CREATE TABLE `".$wpPrefix."gmp_usage_stat` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) NOT NULL,
			  `visits` int(11) NOT NULL DEFAULT '0',
			  `spent_time` int(11) NOT NULL DEFAULT '0',
			  `modify_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE INDEX `code` (`code`),
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8");
			$tableName = $wpdb->prefix . "gmp_usage_stat";
			$wpdb->insert($tableName, array(
					'code' => 'installed',
					'visits' => 1,
			));
		}
		/**
		 * Create table for marker groups
		 */
		if(!dbGmp::exist("gmp_marker_groups_relation")){
			dbDelta("CREATE TABLE IF NOT EXISTS `".$wpPrefix."gmp_marker_groups_relation"."` (
					`id` int(11) NOT NULL AUTO_INCREMENT,
					`marker_id` int(11) NOT NULL,
					`groups_id` int(11) NOT NULL,
				 PRIMARY KEY (`id`)
				  ) DEFAULT CHARSET=utf8");
		}

		if( !get_option($wpPrefix. 'gmp_markers_groups_multiple_updated') ){

			$markersData = $wpdb->get_results("SELECT id, marker_group_id FROM gmp_markers", ARRAY_A);
			if($markersData){
				foreach ($markersData as $marker) {
					$wpdb->insert('gmp_marker_groups_relation', array('marker_id' => $marker['id'], 'groups_id' => $marker['marker_group_id']));
				}
				update_option($wpPrefix. 'gmp_markers_groups_multiple_updated', 1);
			}

		}
    update_option($wpPrefix. 'gmp_db_version', GMP_VERSION_PLUGIN);
		add_option($wpPrefix. 'gmp_db_installed', 1);

        installerDbUpdaterGmp::runUpdate();
	}
	static public function setUsed() {
		update_option('gmp_plug_was_used', 1);
	}
	static public function isUsed() {
		// No welcome page for now
		return true;
		return (bool)get_option('gmp_plug_was_used');
	}
	/**
	 * Create pages for plugin usage
	 */
	static public function createPages() {
		return false;
	}

	/**
	 * Return page data from given array, searched by title, used in self::createPages()
	 * @return mixed page data object if success, else - false
	 */
	static private function _getPageByTitle($title, $pageArr) {
		foreach($pageArr as $p) {
			if($p->title == $title)
				return $p;
		}
		return false;
	}
	static public function delete() {
		self::_checkSendStat('delete');
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$deleteOptions = false;
		if((bool)$deleteOptions){
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_modules`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_icons`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_maps`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_options`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_htmltype`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_markers`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_marker_groups`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_marker_groups_relation`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_options_categories`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_modules_type`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_usage_stat`");
		   $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}gmp_membership_presets`");

		   delete_option('gmp_def_icons_installed');
		   delete_option('gmp_db_version');
		   delete_option($wpPrefix.'gmp_db_installed');
		   //delete_option(GMP_DB_PREF. 'plug_was_used');
		}
	}
	static public function deactivate() {
		self::_checkSendStat('deactivate');
	}
	static private function _checkSendStat($statCode) {
		if(class_exists('frameGmp')
			&& frameGmp::_()->getModule('supsystic_promo')
			&& frameGmp::_()->getModule('options')
		) {
			frameGmp::_()->getModule('supsystic_promo')->getModel()->saveUsageStat( $statCode );
			frameGmp::_()->getModule('supsystic_promo')->getModel()->checkAndSend( true );
		}
	}
	static public function update() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$currentVersion = get_option($wpPrefix. 'gmp_db_version', 0);
		$installed = (int) get_option($wpPrefix. 'gmp_db_installed', 0);
		if(!$currentVersion || version_compare(GMP_VERSION_PLUGIN, $currentVersion, '>')) {
			self::init();
			update_option($wpPrefix. 'gmp_db_version', GMP_VERSION_PLUGIN);
		}
	}
	static public function updateIcon() {
		global $wpdb;
		if ($icons = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}gmp_icons WHERE title IN ('marker', 'flag', 'pin', 'star')", ARRAY_A)) {
			 if (count($icons) < 45) {
				 frameGmp::_()->getModule('icons')->getModel()->setDefaultIcons();
			 }
		 } else {
			 frameGmp::_()->getModule('icons')->getModel()->setDefaultIcons();
		 }
	}
}
