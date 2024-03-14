<?php

/**
 * Fired during plugin activation
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 * @subpackage Intel/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Intel
 * @subpackage Intel/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		self::setup_database();
		self::setup_cron();
		self::setup_options();
	}

	public static function setup_options() {
		// add entity_settings for WP standard taxonomies
		$vars = array(
			'page_intent' => '',
			'track_page_terms' => 1,
			'page_attribute' => array(
				'key' => 'b',
				'title' => Intel_Df::t('Tag'),
				'title_plural' => Intel_Df::t('Tags'),
			),
			'visitor_attribute' => array(
			),
		);
		update_option('intel_entity_settings_taxonomy__post_tag', $vars);

		$vars['page_attribute']['key'] = 'c';
		$vars['page_attribute']['title'] = Intel_Df::t('Category');
		$vars['page_attribute']['title_plural'] = Intel_Df::t('Categories');
		update_option('intel_entity_settings_taxonomy__category', $vars);

		$vars['page_attribute']['key'] = 'd';
		$vars['page_attribute']['title'] = Intel_Df::t('Post format');
		$vars['page_attribute']['title_plural'] = Intel_Df::t('Post formats');
		update_option('intel_entity_settings_taxonomy__post_format', $vars);

		include_once ( INTEL_DIR . 'includes/intel.update.php' );

		// initialize schema_versions
		intel_activate_updates();

		/*
		// initialize system meta for updates
		$schema_ver = 1000;
		$updates = self::get_needed_updates();
		foreach ($updates as $i => $v) {
			if (intval($i) > $schema_ver) {
				$schema_ver = $i;
			}
		}
		$system_meta = get_option('intel_system_meta', array());
		$system_meta['schema_version'] = $schema_ver;
		$system_meta['intel_ver'] = INTEL_VER;
		//$system_meta['activated'] = time();
		update_option('intel_system_meta', $system_meta);
		*/
	}

	public static function setup_database() {
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$charset_collate = $wpdb->get_charset_collate();

		// create intl_visitor table
		$table_name = $wpdb->prefix . "intel_visitor";

		$sql = "CREATE TABLE $table_name (
		  vid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			created int(10) UNSIGNED NOT NULL DEFAULT '0',
			updated int(10) UNSIGNED NOT NULL DEFAULT '0',
			last_activity int(10) UNSIGNED NOT NULL DEFAULT '0',
			name varchar(128) NOT NULL,
			contact_created int(10) UNSIGNED NOT NULL DEFAULT '0',
			data_updated int(10) UNSIGNED NOT NULL DEFAULT '0',
			data longtext NOT NULL,
			ext_updated int(10) UNSIGNED NOT NULL DEFAULT '0',
			ext_data longtext NOT NULL,
			PRIMARY KEY (vid)
		) $charset_collate;";

		dbDelta( $sql );

		// create intl_visitor_identifier table
		$table_name = $wpdb->prefix . "intel_visitor_identifier";

		$sql = "CREATE TABLE $table_name (
  		vid int(10) UNSIGNED NOT NULL,
			type varchar(32) NOT NULL,
			delta smallint(6) NOT NULL DEFAULT '0',
			value varchar(255) NOT NULL,
			KEY vid (vid),
			KEY type (type(10)),
			KEY value (value(10))
		) $charset_collate;";

		dbDelta( $sql );

		// create intl_submission table
		$table_name = $wpdb->prefix . "intel_submission";

		$sql = "CREATE TABLE $table_name (
			sid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			vid int(10) UNSIGNED NOT NULL DEFAULT '0',
			submitted int(10) UNSIGNED NOT NULL DEFAULT '0',
			type varchar(32) NOT NULL,
			fid varchar(64) NOT NULL,
			fsid varchar(128) NOT NULL,
			submission_uri varchar(255) NOT NULL,
			response_page_uri varchar(255) NOT NULL,
			response_page_id varchar(32) NOT NULL,
			form_page_uri varchar(255) NOT NULL,
			form_page_id varchar(32) NOT NULL,
			cta_page_uri varchar(255) NOT NULL,
			cta_page_id varchar(255) NOT NULL,
			cta_id varchar(255) NOT NULL,
			data longtext NOT NULL,
			PRIMARY KEY (sid),
			KEY vid (vid),
			KEY fid (fid),
			KEY fsid (fsid),
			KEY response_page_uri (response_page_uri(32)),
			KEY response_page_id (response_page_id(8)),
			KEY form_page_uri (form_page_uri(32)),
			KEY form_page_id (form_page_id(8)),
			KEY cta_page_uri (cta_page_uri(32)),
			KEY cta_page_id (cta_page_id(8)),
			KEY cta_id (cta_id(32))
		) $charset_collate;";

		dbDelta( $sql );

		$table_name = $wpdb->prefix . "intel_entity_attr";

		$sql = "CREATE TABLE $table_name (
			entity_type varchar(64) DEFAULT '',
			entity_id int(10) UNSIGNED DEFAULT NULL,
			path varchar(255) DEFAULT '',
			alias varchar(255) DEFAULT '',
			attr_key varchar(64) NOT NULL DEFAULT '',
			vsid int(10) UNSIGNED DEFAULT NULL,
			value_num float DEFAULT NULL,
			KEY entity (entity_type, entity_id),
			KEY path (path(18)),
  		KEY alias (alias(18)),
  		KEY attr_key (attr_key(4)),
  		KEY vsid (vsid)
		) $charset_collate;";

		dbDelta( $sql );

		$table_name = $wpdb->prefix . "intel_value_str";

		$sql = "CREATE TABLE $table_name (
			vsid int(10) UNSIGNED NOT NULL,
			value_str varchar(255) NOT NULL,
			PRIMARY KEY (vsid),
      KEY value_str (value_str(16))
		) $charset_collate;";

		dbDelta( $sql );

		$table_name = $wpdb->prefix . "intel_annotation";

		$sql = "CREATE TABLE $table_name (
    aid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    uid int(11) NOT NULL DEFAULT '0',
    created int(10) UNSIGNED NOT NULL DEFAULT 0,
    updated int(10) UNSIGNED NOT NULL DEFAULT 0,
    started int(10) UNSIGNED NOT NULL DEFAULT 0,
    transient_period int(10) UNSIGNED NOT NULL DEFAULT 0,
    ended int(10) UNSIGNED NOT NULL DEFAULT 0,
    analytics_period int(10) UNSIGNED NOT NULL DEFAULT 0,
    type varchar(128) NOT NULL DEFAULT '',
    message longtext NOT NULL,
    variables longtext NOT NULL,
    data longtext NOT NULL,
    PRIMARY KEY (aid),
    KEY started (started),
    KEY analytics_period (analytics_period),
    KEY type (type(14))
    ) $charset_collate;";

		dbDelta( $sql );

	}

	public static function setup_cron() {
		// setup intel_cron_hook
		$timestamp = wp_next_scheduled( 'intel_cron_hook' );
//Intel_Df::watchdog('setup_cron cron_hook ts', $timestamp);
		if ($timestamp == FALSE) {
			wp_schedule_event( time(), 'intel_cron_interval', 'intel_cron_hook' );
		}

		// setup intel_cron_queue_hook
		$timestamp = wp_next_scheduled( 'intel_cron_queue_hook' );
//Intel_Df::watchdog('setup_cron cron_queue_hook ts', $timestamp);
		if ($timestamp == FALSE) {
			wp_schedule_event( time(), 'intel_cron_queue_interval', 'intel_cron_queue_hook' );
		}
	}

}
