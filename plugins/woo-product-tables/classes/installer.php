<?php
class InstallerWtbp {
	public static $update_to_version_method = '';
	private static $_firstTimeActivated = false;

	/**
	 * Install init
	 */
	public static function init() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$current_version = get_option($wpPrefix . WTBP_DB_PREF . 'db_version', 0);
		if (!$current_version) {
			self::$_firstTimeActivated = true;
		}

		/**
		 * Modules
		 */
		if (!DbWtbp::exist('@__modules')) {
			dbDelta(DbWtbp::prepareQuery("CREATE TABLE IF NOT EXISTS `@__modules` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `code` varchar(32) NOT NULL,
			  `active` tinyint(1) NOT NULL DEFAULT '0',
			  `type_id` tinyint(1) NOT NULL DEFAULT '0',
			  `label` varchar(64) DEFAULT NULL,
			  `ex_plug_dir` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE INDEX `code` (`code`)
			) DEFAULT CHARSET=utf8;"));
			DbWtbp::query("INSERT INTO `@__modules` (id, code, active, type_id, label) VALUES
				(NULL, 'adminmenu',1,1,'Admin Menu'),
				(NULL, 'options',1,1,'Options'),
				(NULL, 'user',1,1,'Users'),
				(NULL, 'pages',1,1,'Pages'),
				(NULL, 'templates',1,1,'templates'),
				(NULL, 'promo',1,1,'promo'),
				(NULL, 'admin_nav',1,1,'admin_nav'),
				(NULL, 'wootablepress',1,1,'wootablepress'),
				(NULL, 'mail',1,1,'mail');");
		}
		/**
		 * Modules type
		 */
		if (!DbWtbp::exist('@__modules_type')) {
			dbDelta(DbWtbp::prepareQuery('CREATE TABLE IF NOT EXISTS `@__modules_type` (
			  `id` smallint(3) NOT NULL AUTO_INCREMENT,
			  `label` varchar(32) NOT NULL,
			  PRIMARY KEY (`id`)
			) AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;'));
			DbWtbp::query("INSERT INTO `@__modules_type` VALUES
				(1,'system'),
				(6,'addons');");
		}
		/**
		 * Tables table
		 */
		if (!DbWtbp::exist('@__tables')) {
			dbDelta(DbWtbp::prepareQuery('CREATE TABLE IF NOT EXISTS `@__tables` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`title` VARCHAR(128) NULL DEFAULT NULL,
				`meta` TEXT NOT NULL,
				`setting_data` MEDIUMTEXT NOT NULL,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;'));
		}
		if (version_compare($current_version, '1.1.2') != 1) {
			DbWtbp::query('ALTER TABLE `@__tables` MODIFY setting_data MEDIUMTEXT;');
		}
		/**
		 * Tables columns
		 */
		DbWtbp::query('DROP TABLE IF EXISTS `@__columns`');
		if (!DbWtbp::exist('@__columns')) {
			dbDelta(DbWtbp::prepareQuery('CREATE TABLE IF NOT EXISTS `@__columns` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`columns_name` VARCHAR(128) NULL DEFAULT NULL,
				`columns_nice_name` VARCHAR(128) NULL DEFAULT NULL,
				`columns_order` smallint(3) NULL DEFAULT NULL,
				`is_default` smallint(3) NOT NULL DEFAULT 0,
				PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8;'));
			DbWtbp::query("INSERT INTO `@__columns` (id, columns_name, columns_nice_name, columns_order, is_default) VALUES
				(NULL, 'id', 'ID', 0, 0),
				(NULL, 'product_title', 'Name', 2, 1),
				(NULL, 'sku', 'SKU', 4, 0),
				(NULL, 'thumbnail', 'Thumbnail', 1, 1),
				(NULL, 'categories', 'Categories', 5, 1),
				(NULL, 'price', 'Price', 11, 1),
				(NULL, 'attribute', 'Attributes', 6, 0),
				(NULL, 'description', 'Summary', 7, 0),
				(NULL, 'short_description', 'Short description', 13, 0),
				(NULL, 'product_link', 'Product link', 17, 0),
				(NULL, 'add_to_cart', 'Buy', 12, 0),
				(NULL, 'reviews', 'Rating', 8, 0),
				(NULL, 'date', 'Date', 10, 1),
				(NULL, 'stock', 'Stock status', 9, 0),
				(NULL, 'featured', 'Featured', 3, 0),
				(NULL, 'sales', 'Sales', 14, 0),
				(NULL, 'downloads', 'Downloads', 15, 0),
				(NULL, 'sale_dates', 'Sale price dates', 16, 0),
				(NULL, 'tags', 'Tags', 17, 0),
				(NULL, 'weight', 'Weight', 18, 0),
				(NULL, 'dimensions', 'Dimensions', 19, 0)");
		}

		/**
		* Plugin usage statistwtbp
		*/
		if (!DbWtbp::exist('@__usage_stat')) {
			dbDelta(DbWtbp::prepareQuery("CREATE TABLE `@__usage_stat` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(64) NOT NULL,
			  `visits` int(11) NOT NULL DEFAULT '0',
			  `spent_time` int(11) NOT NULL DEFAULT '0',
			  `modify_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			  UNIQUE INDEX `code` (`code`),
			  PRIMARY KEY (`id`)
			) DEFAULT CHARSET=utf8"));
			DbWtbp::query("INSERT INTO `@__usage_stat` (code, visits) VALUES ('installed', 1)");
		}

		/**
		 * Plugin usage statistwtbp
		 */
		if ( ! DbWtbp::exist( '@__favorites' ) ) {
			dbDelta( DbWtbp::prepareQuery( 'CREATE TABLE `@__favorites` (
    			`user_id` bigint(20) UNSIGNED NOT NULL,
    			`product_id` bigint(20) UNSIGNED NOT NULL,
    			`from_order` tinyint(3) DEFAULT NULL,
    			KEY `user_id` (`user_id`)
			) DEFAULT CHARSET=utf8' ) );
		}

		InstallerDbUpdaterWtbp::runUpdate();
		if ($current_version && !self::$_firstTimeActivated) {
			self::setUsed();
			// For users that just updated our plugin - don't need tp show step-by-step tutorial
			update_user_meta(get_current_user_id(), WTBP_CODE . '-tour-hst', array('closed' => 1));
		}
		update_option($wpPrefix . WTBP_DB_PREF . 'db_version', WTBP_VERSION);
		add_option($wpPrefix . WTBP_DB_PREF . 'db_installed', 1);
	}
	public static function setUsed() {
		update_option(WTBP_DB_PREF . 'plug_was_used', 1);
	}
	public static function isUsed() {
		return (int) get_option(WTBP_DB_PREF . 'plug_was_used');
	}
	public static function delete() {
		self::_checkSendStat('delete');
		global $wpdb;
		$wpPrefix = $wpdb->prefix;
		$wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . esc_sql(WTBP_DB_PREF) . 'modules`');
		$wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . esc_sql(WTBP_DB_PREF) . 'modules_type`');
		$wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . esc_sql(WTBP_DB_PREF) . 'usage_stat`');
		$wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . esc_sql(WTBP_DB_PREF) . 'columns`');

		delete_option($wpPrefix . WTBP_DB_PREF . 'db_version');
		delete_option($wpPrefix . WTBP_DB_PREF . 'db_installed');
	}
	public static function deactivate() {
		self::_checkSendStat('deactivate');
	}
	private static function _checkSendStat( $statCode ) {
	}
	public static function update() {
		global $wpdb;
		$wpPrefix = $wpdb->prefix; /* add to 0.0.3 Versiom */
		$currentVersion = get_option($wpPrefix . WTBP_DB_PREF . 'db_version', 0);
		if (!$currentVersion || version_compare(WTBP_VERSION, $currentVersion, '>')) {
			self::init( true );
			update_option($wpPrefix . WTBP_DB_PREF . 'db_version', WTBP_VERSION);
		}
	}
}
