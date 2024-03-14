<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Trackship_Install {
	
	public $table;
	public $shipment_table;
	public $shipment_table_meta;
	public $log_table;

	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		global $wpdb;
		$this->table = $wpdb->prefix . 'trackship_shipping_provider';
		$this->shipment_table = $wpdb->prefix . 'trackship_shipment';
		$this->shipment_table_meta = $wpdb->prefix . 'trackship_shipment_meta';
		$this->log_table = $wpdb->prefix . 'zorem_email_sms_log';

		$this->init();
	}

	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	 */
	private static $instance;
	
	/**
	 * Get the class instance
	 *
	 * @return WC_Trackship_Install
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	* init from parent mail class
	*/
	public function init() {
		add_action( 'admin_init', array( $this, 'update_database_check' ) );
		add_action( 'wp_ajax_update_trackship_providers', array( $this, 'update_trackship_providers' ) );
	}
	
	/*
	* database update
	*/
	public function update_database_check() {
			
		if ( version_compare( get_option( 'trackship_db' ), '1.0', '<' ) ) {
			update_option( 'trackship_db', '1.0' );

			$this->create_shipping_provider_table();
			$this->create_shipment_table();
			$this->create_shipment_meta_table();
			$this->create_email_log_table();
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.8', '<' ) ) {
			$delivered_settings = get_option( 'wcast_delivered_email_settings' );
			update_option( 'wcast_delivered_status_email_settings', $delivered_settings );
			delete_option( 'wcast_delivered_email_settings' );
			update_option( 'trackship_db', '1.8' );
		}

		if ( wp_next_scheduled( 'ast_late_shipments_cron_hook' ) ) {
			$Late_Shipments = new WC_TrackShip_Late_Shipments();
			$Late_Shipments->remove_cron();
			$Late_Shipments->setup_cron();
		}
		if ( version_compare( get_option( 'trackship_db' ), '1.13', '<' ) ) {
			// migration to change api key name 
			$trackship_apikey = get_option( 'wc_ast_api_key' );			
			update_option( 'trackship_apikey', $trackship_apikey );
			delete_option( 'wc_ast_api_enabled' );
			
			update_option( 'trackship_db', '1.13' );
		}

		global $wpdb;
		$shipment_table = $this->shipment_table;
		if ( version_compare( get_option( 'trackship_db' ), '1.14', '<' ) ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}trackship_shipment CHANGE shipping_date shipping_date DATE NULL DEFAULT CURRENT_TIMESTAMP" );
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}trackship_shipment ADD INDEX last_event (last_event);");
			
			update_option( 'trackship_db', '1.14' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.16', '<' ) ) {
			$result = $wpdb->get_col(
				"SELECT t.order_id FROM {$wpdb->prefix}trackship_shipment t
				LEFT JOIN {$wpdb->prefix}trackship_shipment_meta m  
				ON t.id = m.meta_id
				WHERE (m.tracking_events IS NULL OR m.tracking_events = '')
					AND t.shipping_date >= DATE_SUB(NOW(), INTERVAL 60 DAY)
				GROUP BY t.order_id
				LIMIT 2000"
			);
			if ( $result ) {
				update_trackship_settings( 'old_user', true );
			}
			update_option( 'trackship_db', '1.16' );
		}
		
		if ( version_compare( get_option( 'trackship_db' ), '1.18', '<' ) ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}trackship_shipment_meta MODIFY COLUMN shipping_service varchar(60);" );
			update_option( 'trackship_db', '1.18' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.19', '<' ) ) {
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}trackship_shipment MODIFY COLUMN order_number varchar(40);" );
			$wpdb->query( "ALTER TABLE {$wpdb->prefix}zorem_email_sms_log MODIFY COLUMN order_number varchar(40);" );

			update_trackship_settings( 'trackship_db', '1.19' );
			update_option( 'trackship_db', '1.19' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.20', '<' ) ) {
			$valid_order_statuses = get_option( 'trackship_trigger_order_statuses', ['completed', 'partial-shipped', 'shipped'] );
			if ( $valid_order_statuses ) {
				update_trackship_settings( 'trackship_trigger_order_statuses', $valid_order_statuses );
			}

			$wc_ts_shipment_status_filter = get_option( 'wc_ast_show_shipment_status_filter' );
			if ( $wc_ts_shipment_status_filter ) {
				update_trackship_settings( 'wc_ts_shipment_status_filter', $wc_ts_shipment_status_filter );
			}

			$enable_email_widget = get_option( 'enable_email_widget' );
			if ( $enable_email_widget ) {
				update_trackship_settings( 'enable_email_widget', $enable_email_widget );
			}

			$enable_notification_for_amazon_order = get_option( 'enable_notification_for_amazon_order', 1 );
			if ( $enable_notification_for_amazon_order ) {
				update_trackship_settings( 'enable_notification_for_amazon_order', $enable_notification_for_amazon_order );
			}

			$late_shipments_days = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('late_shipments_email_settings', 'wcast_late_shipments_days', 7 );
			if ( $late_shipments_days ) {
				update_trackship_settings( 'late_shipments_days', $late_shipments_days );
			}

			$wc_ast_use_tracking_page = get_option( 'wc_ast_use_tracking_page', '' );
			if ( $wc_ast_use_tracking_page ) {
				update_trackship_settings( 'wc_ast_use_tracking_page', $wc_ast_use_tracking_page );
			}

			$wc_ast_trackship_page_id = get_option( 'wc_ast_trackship_page_id', '' );
			if ( $wc_ast_trackship_page_id ) {
				update_trackship_settings( 'wc_ast_trackship_page_id', $wc_ast_trackship_page_id );
			}

			$wc_ast_trackship_other_page = get_option( 'wc_ast_trackship_other_page', '' );
			if ( $wc_ast_trackship_other_page ) {
				update_trackship_settings( 'wc_ast_trackship_other_page', $wc_ast_trackship_other_page );
			}

			delete_option( 'trackship_trigger_order_statuses' );
			delete_option( 'wc_ast_show_shipment_status_filter' );
			delete_option( 'enable_email_widget' );
			delete_option( 'enable_notification_for_amazon_order' );
			delete_option( 'wc_ast_use_tracking_page' );
			delete_option( 'wc_ast_trackship_page_id' );
			delete_option( 'wc_ast_trackship_other_page' );
			$late_shipments_settings = get_option( 'late_shipments_email_settings', [] );
			unset($late_shipments_settings['wcast_late_shipments_days']);
			update_option( 'late_shipments_email_settings', $late_shipments_settings );

			$columns = $wpdb->get_row( "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}trackship_shipment' AND COLUMN_NAME = 'new_shipping_provider' ", ARRAY_A );
			if ( $columns ) {
				$wpdb->query( "ALTER TABLE {$wpdb->prefix}trackship_shipment DROP COLUMN pending_status;" );
			}

			$this->update_shipping_providers();
			$this->check_column_exists();

			update_trackship_settings( 'trackship_db', '1.20' );
			update_option( 'trackship_db', '1.20' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.21', '<' ) ) {
			$late_email_enable = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('late_shipments_email_settings', 'wcast_enable_late_shipments_admin_email', '' );
			if ( $late_email_enable ) {
				update_trackship_settings( 'late_shipments_email_enable', $late_email_enable );
			}
			$late_email_to = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('late_shipments_email_settings', 'wcast_late_shipments_email_to', '' );
			if ( $late_email_to ) {
				update_trackship_settings( 'late_shipments_email_to', $late_email_to );
			}
			$late_digest_time = trackship_for_woocommerce()->ts_actions->get_option_value_from_array('late_shipments_email_settings', 'wcast_late_shipments_daily_digest_time', '' );
			if ( $late_digest_time ) {
				update_trackship_settings( 'late_shipments_digest_time', $late_digest_time );
			}
			delete_option( 'late_shipments_email_settings' );
			update_option( 'trackship_db', '1.21' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.22', '<' ) ) {
			$status = get_trackship_settings( 'trackship_trigger_order_statuses' );
			if ( !$status ) {
				update_trackship_settings( 'trackship_trigger_order_statuses', ['completed', 'partial-shipped', 'shipped'] );
			}

			update_trackship_settings( 'trackship_db', '1.22' );
			update_option( 'trackship_db', '1.22' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.23', '<' ) ) {
			$email_trackship_branding = trackship_for_woocommerce()->ts_actions->get_option_value_from_array( 'shipment_email_settings', 'email_trackship_branding', 1 );
			$tp_trackship_branding = get_option( 'wc_ast_remove_trackship_branding', 0 );
			$value = 1;
			if ( 1 != $email_trackship_branding || $tp_trackship_branding ) {
				$value = 0;
			}
			$option_data = get_option( 'shipment_email_settings', array() );
			unset( $option_data['email_trackship_branding'] );
			$option_data['show_trackship_branding'] = $value;
			update_option( 'shipment_email_settings', $option_data );
			delete_option( 'wc_ast_remove_trackship_branding' );

			update_trackship_settings( 'trackship_db', '1.23' );
			update_option( 'trackship_db', '1.23' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.24', '<' ) ) {

			$this->create_shipping_provider_table();
			$this->update_shipping_providers();
			$this->check_column_exists();

			$Exception_Shipments = new WC_TrackShip_Exception_Shipments();
			$Exception_Shipments->remove_cron();
			$Exception_Shipments->setup_cron();

			$On_Hold_Shipments = new WC_TrackShip_On_Hold_Shipments();
			$On_Hold_Shipments->remove_cron();
			$On_Hold_Shipments->setup_cron();

			update_trackship_settings( 'trackship_db', '1.24' );
			update_option( 'trackship_db', '1.24' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.25', '<' ) ) {
			$wc_ast_select_bg_color = get_option( 'wc_ast_select_bg_color' );
			$wc_ast_select_font_color = get_option( 'wc_ast_select_font_color' );
			$wc_ast_select_border_color = get_option( 'wc_ast_select_border_color' );
			$wc_ast_select_border_radius = get_option( 'wc_ast_select_border_radius' );
			$wc_ast_select_link_color = get_option( 'wc_ast_select_link_color' );
			$tracking_page_type = get_option( 'tracking_page_type' );
			$wc_ast_hide_tracking_events = get_option( 'wc_ast_hide_tracking_events' );
			$wc_ast_select_tracking_page_layout = get_option( 'wc_ast_select_tracking_page_layout' );
			$wc_ast_link_to_shipping_provider = get_option( 'wc_ast_link_to_shipping_provider' );
			$wc_ast_hide_tracking_provider_image = get_option( 'wc_ast_hide_tracking_provider_image' );
			$wc_ast_hide_from_to = get_option( 'wc_ast_hide_from_to' );
			$wc_ast_hide_list_mile_tracking = get_option( 'wc_ast_hide_list_mile_tracking' );

			update_trackship_settings( 'wc_ts_bg_color', $wc_ast_select_bg_color );
			update_trackship_settings( 'wc_ts_font_color', $wc_ast_select_font_color );
			update_trackship_settings( 'wc_ts_border_color', $wc_ast_select_border_color );
			update_trackship_settings( 'wc_ts_border_radius', $wc_ast_select_border_radius );
			update_trackship_settings( 'wc_ts_link_color', $wc_ast_select_link_color );
			update_trackship_settings( 'tracking_page_type', $tracking_page_type );
			update_trackship_settings( 'ts_tracking_events', $wc_ast_hide_tracking_events );
			update_trackship_settings( 'ts_tracking_page_layout', $wc_ast_select_tracking_page_layout );
			update_trackship_settings( 'ts_link_to_carrier', $wc_ast_link_to_shipping_provider );
			update_trackship_settings( 'hide_provider_image', $wc_ast_hide_tracking_provider_image );
			update_trackship_settings( 'ts_hide_from_to', $wc_ast_hide_from_to );
			update_trackship_settings( 'ts_hide_list_mile_tracking', $wc_ast_hide_list_mile_tracking );

			delete_option( 'wc_ast_select_bg_color' );
			delete_option( 'wc_ast_select_font_color' );
			delete_option( 'wc_ast_select_border_color' );
			delete_option( 'wc_ast_select_border_radius' );
			delete_option( 'wc_ast_select_link_color' );
			delete_option( 'tracking_page_type' );
			delete_option( 'wc_ast_hide_tracking_events' );
			delete_option( 'wc_ast_select_tracking_page_layout' );
			delete_option( 'wc_ast_link_to_shipping_provider' );
			delete_option( 'wc_ast_hide_tracking_provider_image' );
			delete_option( 'wc_ast_hide_from_to' );
			delete_option( 'wc_ast_hide_list_mile_tracking' );
			update_option( 'trackship_db', '1.25' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.26', '<' ) ) {

			if ( $wpdb->get_var( "SHOW COLUMNS FROM {$wpdb->prefix}trackship_shipment LIKE 'updated_date';" ) ) {
				$wpdb->query( "ALTER TABLE {$wpdb->prefix}trackship_shipment CHANGE COLUMN updated_date ship_length_updated DATE;" );
			}

			$this->create_shipping_provider_table();
			$this->update_shipping_providers();
			$this->check_column_exists();
			update_trackship_settings( 'trackship_db', '1.26' );
			update_option( 'trackship_db', '1.26' );
		}

		if ( version_compare( get_option( 'trackship_db' ), '1.27', '<' ) ) {

			delete_trackship_settings( 'review_notice_ignore' );
			delete_trackship_settings( 'trackship_upgrade_ignore' );
			delete_trackship_settings( 'klaviyo_notice_ignore' );

			update_trackship_settings( 'trackship_db', '1.27' );
			update_option( 'trackship_db', '1.27' );
		}
	}

	public function update_trackship_providers() {
		if ( check_ajax_referer( 'nonce_trackship_provider', 'security' ) ) {
			$this->create_shipping_provider_table();
			$this->update_shipping_providers();
			wp_send_json( array('success' => 'true') );
		}
	}
	
	/**
	 * Create TrackShip Shipping provider table
	*/
	public function create_shipping_provider_table() {
		global $wpdb;
		$woo_ts_shipment_table_name = $this->table;
		if ( !$wpdb->query( $wpdb->prepare( 'show tables like %s', $woo_ts_shipment_table_name ) ) ) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$wpdb->prefix}trackship_shipping_provider (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				provider_name varchar(500) DEFAULT '' NOT NULL,
				ts_slug text NULL DEFAULT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Get providers list from trackship and update providers in database
	*/
	public function update_shipping_providers() {
		global $wpdb;
		// added in version 1.7.6
		$url = 'https://api.trackship.com/v1/shipping_carriers/supported';
		$resp = wp_remote_get( $url );
		
		if ( is_array( $resp ) && ! is_wp_error( $resp ) ) {
		
			$providers = json_decode($resp['body'], true );
			
			$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}trackship_shipping_provider" );
			foreach ( $providers['data'] as $provider ) {
				$data_array = array(
					'provider_name' => $provider['label'],
					'ts_slug' => $provider['slug'],
				);
				$wpdb->insert( $this->table, $data_array );
			}
		}
	}
	
	/**
	 * Create TrackShip notifications logs table
	*/
	public function create_email_log_table() {
		global $wpdb;
		$log_table = $this->log_table;
		if ( !$wpdb->query( $wpdb->prepare( 'show tables like %s', $log_table ) ) ) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$wpdb->prefix}zorem_email_sms_log (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
				`order_id` BIGINT(20) ,
				`order_number` VARCHAR(40) ,
				`user_id` BIGINT(20) ,
				`tracking_number` VARCHAR(50) ,
				`date` DATETIME NOT NULL,
				`to` VARCHAR(50) ,
				`shipment_status` VARCHAR(30) ,
				`status` LONGTEXT ,
				`status_msg` varchar(500),
				`type` VARCHAR(20) ,
				`sms_type` VARCHAR(30) ,
				PRIMARY KEY (`id`),
				INDEX `order_id` (`order_id`),
				INDEX `order_number` (`order_number`),
				INDEX `date` (`date`),
				INDEX `to` (`to`),
				INDEX `shipment_status` (`shipment_status`),
				INDEX `type` (`type`),
				INDEX `sms_type` (`sms_type`)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Create TrackShip Shipment table
	*/
	public function create_shipment_table() {
		global $wpdb;
		$woo_trackship_shipment = $this->shipment_table;
		if ( !$wpdb->query( $wpdb->prepare( 'show tables like %s', $woo_trackship_shipment ) ) ) {
			
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$wpdb->prefix}trackship_shipment (
				`id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
				`order_id` BIGINT(20) ,
				`order_number` VARCHAR(40) ,
				`tracking_number` VARCHAR(80) ,
				`shipping_provider` VARCHAR(50) ,
				`shipment_status` VARCHAR(30) ,
				`pending_status` VARCHAR(30) ,
				`shipping_date` date ,
				`shipping_country` TEXT ,
				`shipping_length` VARCHAR(10) ,
				`ship_length_updated` DATE ,
				`late_shipment_email` TINYINT DEFAULT 0,
				`exception_email` TINYINT DEFAULT 0,
				`on_hold_email` TINYINT DEFAULT 0,
				`est_delivery_date` DATE,
				`last_event` LONGTEXT ,
				`last_event_time` DATETIME ,
				`updated_at` DATETIME ,
				PRIMARY KEY (`id`),
				INDEX `shipping_date` (`shipping_date`),
				INDEX `updated_at` (`updated_at`),
				INDEX `status` (`shipment_status`),
				INDEX `tracking_number` (`tracking_number`),
				INDEX `shipping_length` (`shipping_length`),
				INDEX `order_id` (`order_id`),
				INDEX `order_id_tracking_number` (`order_id`,`tracking_number`),
				INDEX `ship_length_updated` (`ship_length_updated`),
				INDEX `late_shipment_email` (`late_shipment_email`),
				INDEX `on_hold_email` (`on_hold_email`),
				INDEX `exception_email` (`exception_email`),
				INDEX `est_delivery_date` (`est_delivery_date`)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Create TrackShip Shipment meta table
	*/
	public function create_shipment_meta_table() {
		global $wpdb;
		$table = $this->shipment_table_meta;
		if ( !$wpdb->query( $wpdb->prepare( 'show tables like %s', $table ) ) ) {
			$charset_collate = $wpdb->get_charset_collate();			
			$sql = "CREATE TABLE {$wpdb->prefix}trackship_shipment_meta (
				`meta_id` BIGINT(20),
				`origin_country` VARCHAR(20) ,
				`destination_country` VARCHAR(20) ,
				`delivery_number` VARCHAR(80) ,
				`delivery_provider` VARCHAR(30) ,
				`shipping_service` VARCHAR(60) ,
				`tracking_events` LONGTEXT ,
				`destination_events` LONGTEXT ,
				`destination_state` VARCHAR(40) ,
				`destination_city` VARCHAR(40) ,
				PRIMARY KEY (`meta_id`),
				INDEX `meta_id` (`meta_id`)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}

	/**
	 * Check column exists in TrackShip table
	*/
	public function check_column_exists() {
		global $wpdb;

		$shipment_table = array(
			'id'					=> ' BIGINT(20) NOT NULL AUTO_INCREMENT',
			'order_id'				=> ' BIGINT(20)',
			'order_number'			=> ' VARCHAR(40)',
			'tracking_number'		=> ' VARCHAR(80)',
			'shipping_provider'		=> ' VARCHAR(50)',
			'shipment_status'		=> ' VARCHAR(30)',
			'pending_status'		=> ' VARCHAR(30)',
			'shipping_date'			=> ' DATE NOT NULL CURRENT_TIMESTAMP',
			'shipping_country'		=> ' TEXT',
			'shipping_length'		=> ' VARCHAR(10)',
			'ship_length_updated'	=> ' DATE',
			'late_shipment_email'	=> ' TINYINT DEFAULT 0',
			'exception_email'=> ' TINYINT DEFAULT 0',
			'on_hold_email'=> ' TINYINT DEFAULT 0',
			'est_delivery_date'		=> ' DATE',
			'last_event'			=> ' LONGTEXT',
			'last_event_time'		=> ' DATETIME',
			'updated_at'			=> ' DATETIME',
		);
		foreach ( $shipment_table as $column_name => $type ) {
			$columns = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}trackship_shipment' AND COLUMN_NAME = '%s' ", $column_name ), ARRAY_A );
			if ( ! $columns ) {
				$wpdb->query( $wpdb->prepare( "ALTER TABLE {$wpdb->prefix}trackship_shipment ADD %1s %2s", $column_name, $type ) );
			}
		}

		$shipment_table_meta = array( 
			'meta_id'				=> ' BIGINT(20)',
			'origin_country'		=> ' VARCHAR(20)',
			'destination_country'	=> ' VARCHAR(20)',
			'delivery_number'		=> ' VARCHAR(80)',
			'delivery_provider'		=> ' VARCHAR(30)',
			'shipping_service'		=> ' VARCHAR(60)',
			'tracking_events'		=> ' LONGTEXT',
			'destination_events'	=> ' LONGTEXT',
			'destination_state'		=> ' VARCHAR(40)',
			'destination_city'		=> ' VARCHAR(40)',
		);
		foreach ( $shipment_table_meta as $column_name => $type ) {
			$columns = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}trackship_shipment_meta' AND COLUMN_NAME = '%s' ", $column_name ), ARRAY_A );
			if ( ! $columns ) {
				$wpdb->query( $wpdb->prepare( "ALTER TABLE {$wpdb->prefix}trackship_shipment_meta ADD %1s %2s", $column_name, $type ) );
			}
		}

		$log_table = array( 
			'id' => ' BIGINT(20) NOT NULL AUTO_INCREMENT',
			'order_id' => ' BIGINT(20)',
			'order_number' => ' VARCHAR(40)',
			'user_id' => ' BIGINT(20)',
			'tracking_number' => ' VARCHAR(50)',
			'date' => ' DATETIME NOT NULL',
			'to' => ' VARCHAR(50)',
			'shipment_status' => ' VARCHAR(30)',
			'status' => ' LONGTEXT',
			'status_msg' => ' varchar(500)',
			'type' => ' VARCHAR(20)',
			'sms_type' => ' VARCHAR(30)',
		);
		foreach ( $log_table as $column_name => $type ) {
			$columns = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$wpdb->prefix}zorem_email_sms_log' AND COLUMN_NAME = '%s' ", $column_name ), ARRAY_A );
			if ( ! $columns ) {
				$wpdb->query( $wpdb->prepare( "ALTER TABLE {$wpdb->prefix}zorem_email_sms_log ADD %1s %2s", $column_name, $type ) );
			}
		}
	}
}
