<?php
/**
 *	Activation
 */
class YESNO_Activation {
	/** 
	 *	Load
	 */
	public static function load() {
		$mypluginurl  = dirname( plugin_dir_url( __FILE__ ) ).'/';
		$mypluginpath = dirname( plugin_dir_path( __FILE__ ) ).'/';
		$mypluginfile = $mypluginpath.YESNO::PLUGIN_FILE;

		register_activation_hook( $mypluginfile, array('YESNO_Activation', 'activation') );
		register_deactivation_hook( $mypluginfile, array('YESNO_Activation', 'deactivation') );
		register_uninstall_hook( $mypluginfile, array('YESNO_Activation', 'uninstall') );
	}

	/** 
	 *	Activation
	 */
	public static function activation() {
		self::create_table();
	}

	/** 
	 *	Deactivation
	 */
	public static function deactivation() {
		wp_clear_scheduled_hook( YESNO::PLUGIN_ID.'_cron');
	}

	/** 
	 *	Uninstall
	 */
	public static function uninstall() {
		delete_option( YESNO::PLUGIN_ID );
		self::drop_table();
	}

	/** 
	 *	Create table
	 */
	public static function create_table() {
		global $wpdb;

		$options = get_option( YESNO::PLUGIN_ID );

		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;

		// Question Set
		$table = $prefix.'set';
		if ( $table != $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) ) {
			require_once( ABSPATH.'wp-admin/includes/upgrade.php');
			$sql = <<<EOD
CREATE TABLE IF NOT EXISTS {$table} (
  `sid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Set ID',
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Title',
  `created` datetime NOT NULL COMMENT 'Created',
  PRIMARY KEY (`sid`)
) AUTO_INCREMENT=1 ;
EOD;
			dbDelta( $sql );
			$options['version']['db'] = YESNO::DB_VERSION;
		}

		// Questions
		$table = $prefix.'question';
		if ( $table != $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) ) {
			require_once( ABSPATH.'wp-admin/includes/upgrade.php');
			$sql = <<<EOD
CREATE TABLE IF NOT EXISTS {$table} (
  `qid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Question ID',
  `sid` bigint(20) NOT NULL COMMENT 'Set ID',
  `qnum` int(11) NOT NULL COMMENT 'Question Number',
  `question` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Question',
  `choices` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Choices',
  `title` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Title',
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'URL',
  PRIMARY KEY (`qid`)
) AUTO_INCREMENT=1 ;
EOD;
			dbDelta( $sql );
			$options['version']['db'] = YESNO::DB_VERSION;
		}
		$options['version']['plugin'] = YESNO::PLUGIN_VERSION;
		update_option( YESNO::PLUGIN_ID, $options );
		return;
	}

	/** 
	 *	Drop table
	 */
	public static function drop_table() {
		global $wpdb;

		$version = get_option( YESNO::PLUGIN_ID.'_version');

		// DROP TABLE
		$prefix = $wpdb->prefix.YESNO::TABLEPREFIX;
		$table = $prefix.'set';
		if ( $table == $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) ) {
			require_once( ABSPATH.'wp-admin/includes/upgrade.php');
			$sql = "DROP TABLE {$table} ;";
			$wpdb->query( $sql );
		}

		$table = $prefix.'question';
		if ( $table == $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) ) {
			require_once( ABSPATH.'wp-admin/includes/upgrade.php');
			$sql = "DROP TABLE {$table} ;";
			$wpdb->query( $sql );
		}
		return;
	}
}
?>
