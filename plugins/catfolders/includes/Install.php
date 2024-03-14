<?php
namespace CatFolders;

class Install {
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'checkVersion' ), 5 );
	}

	public static function install() {
		self::createTables();
		self::updatePluginVersion();
	}

	public static function createTables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( self::getSchema() );
	}

	public static function getSchema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = "
        CREATE TABLE {$wpdb->prefix}catfolders (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            title varchar(250) NOT NULL,
            parent int(11) unsigned NOT NULL DEFAULT 0,
            type varchar(20) NOT NULL DEFAULT 'attachment',
            ord int(11) unsigned NULL DEFAULT 0,
            created_by bigint(20) NOT NULL DEFAULT 0,
            PRIMARY KEY  (id)
		) $collate;
        CREATE TABLE {$wpdb->prefix}catfolders_posts (
            folder_id int(11) unsigned NOT NULL,
            post_id bigint(20) unsigned NOT NULL,
            UNIQUE KEY `folder_post` (`folder_id`,`post_id`)
		) $collate;
        ";

		return $tables;
	}

	public static function updatePluginVersion() {
		update_option( 'catf_version', CATF_VERSION );
	}

	public static function checkVersion() {
		if ( version_compare( get_option( 'catf_version' ), CATF_VERSION, '<' ) ) {
			self::install();
		}
	}
}
