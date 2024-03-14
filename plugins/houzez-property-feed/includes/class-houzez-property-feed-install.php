<?php
/**
 * Installation related functions and actions.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Houzez_Property_Feed_Install' ) ) :

/**
 * Houzez_Property_Feed_Install Class
 */
class Houzez_Property_Feed_Install {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		register_activation_hook( HOUZEZ_PROPERTY_FEED_PLUGIN_FILE, array( $this, 'install' ) );
		register_deactivation_hook( HOUZEZ_PROPERTY_FEED_PLUGIN_FILE, array( $this, 'deactivate' ) );

		add_action( 'admin_init', array( $this, 'install_actions' ) );
		add_action( 'admin_init', array( $this, 'check_version' ), 5 );
	}

	/**
	 * check_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function check_version() {
	    if ( 
	    	! defined( 'IFRAME_REQUEST' ) && 
	    	( get_option( 'houzez_property_feed_version' ) != HPF()->version || get_option( 'houzez_property_feed_db_version' ) != HPF()->version ) 
	    ) {
			$this->install();
		}
	}

	/**
	 * Install actions
	 */
	public function install_actions() {



	}

	/**
	 * Install Houzez Property Feed Plugin
	 */
	public function install() {
        
		$this->create_options();
		$this->create_cron();
		$this->create_tables();

		$current_version = get_option( 'houzez_property_feed_version', null );
		$current_db_version = get_option( 'houzez_property_feed_db_version', null );

		// No existing version set. This must be a new fresh install
        if ( is_null( $current_version ) && is_null( $current_db_version ) ) 
        {
            set_transient( '_houzez_property_feed_activation_redirect', 1, 30 );
        }
        
        update_option( 'houzez_property_feed_db_version', HPF()->version );

        // Update version
        update_option( 'houzez_property_feed_version', HPF()->version );
	}

	/**
	 * Deactivate Houzez Property Feed Plugin
	 */
	public function deactivate() {

		$timestamp = wp_next_scheduled( 'houzezpropertyfeedcronhook' );
        wp_unschedule_event($timestamp, 'houzezpropertyfeedcronhook' );
		wp_clear_scheduled_hook('houzezpropertyfeedcronhook');

	}

	/**
	 * Default options
	 *
	 * Sets up the default options used on the settings page
	 *
	 * @access public
	 */
	public function create_options() {
	    
        //add_option( 'option_name', 'yes', '', 'yes' );

    }

    /**
	 * Creates the scheduled event to run hourly
	 *
	 * @access public
	 */
    public function create_cron() {
        $timestamp = wp_next_scheduled( 'houzezpropertyfeedcronhook' );
        wp_unschedule_event($timestamp, 'houzezpropertyfeedcronhook' );
        wp_clear_scheduled_hook('houzezpropertyfeedcronhook');
        
        $next_schedule = time() - 60;
		wp_schedule_event( $next_schedule, apply_filters( 'houzez_property_feed_cron_frequency', 'every_five_minutes' ), 'houzezpropertyfeedcronhook' );

		$timestamp = wp_next_scheduled( 'houzezpropertyfeedreconcilecronhook' );
        wp_unschedule_event($timestamp, 'houzezpropertyfeedreconcilecronhook' );
        wp_clear_scheduled_hook('houzezpropertyfeedreconcilecronhook');

        $next_schedule = time() - 60;
        wp_schedule_event( $next_schedule, apply_filters( 'houzez_property_feed_reconcile_cron_frequency', 'twicedaily' ), 'houzezpropertyfeedreconcilecronhook' );
    }

    /**
	 * Set up the database tables which the plugin needs to function.
	 *
	 * Tables:
	 *		houzez_property_feed_logs_instance - Table description
	 *		houzez_property_feed_logs_instance_log - Table description
	 *
	 * @access public
	 * @return void
	 */
	private function create_tables() {

		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty($wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		// Create table to record individual feeds being ran
	   	$table_name = $wpdb->prefix . "houzez_property_feed_logs_instance";
	      
	   	$sql = "CREATE TABLE $table_name (
					id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					import_id bigint(20) UNSIGNED NOT NULL,
					start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					end_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					media tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
				  	PRIMARY KEY (id)
	    		) $collate;";
		
		$table_name = $wpdb->prefix . "houzez_property_feed_logs_instance_log";
		
		$sql .= "CREATE TABLE $table_name (
					id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					instance_id bigint(20) UNSIGNED NOT NULL,
					post_id bigint(20) UNSIGNED NOT NULL,
					crm_id varchar(255) NOT NULL,
					severity tinyint(1) UNSIGNED NOT NULL,
					entry longtext NOT NULL,
					received_data longtext,
					log_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				  	PRIMARY KEY (id)
				) $collate;";

		$table_name = $wpdb->prefix . "houzez_property_feed_media_queue";

		$sql .= "CREATE TABLE $table_name (
					id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					import_id bigint(20) UNSIGNED NOT NULL,
					post_id bigint(20) UNSIGNED NOT NULL,
					crm_id varchar(255) NOT NULL,
					media_location text NOT NULL,
					media_description varchar(255) NOT NULL,
					media_type varchar(255) NOT NULL,
					media_order smallint(1) UNSIGNED NOT NULL,
					media_compare_url text NOT NULL,
					media_modified varchar(24) NOT NULL,
					date_queued datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					PRIMARY KEY  (id)
				) $collate;";

		$table_name = $wpdb->prefix . "houzez_property_feed_export_logs_instance";
	      
	   	$sql .= "CREATE TABLE $table_name (
					id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					export_id bigint(20) UNSIGNED NOT NULL,
					start_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					end_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				  	PRIMARY KEY (id)
	    		) $collate;";
		
		$table_name = $wpdb->prefix . "houzez_property_feed_export_logs_instance_log";
		
		$sql .= "CREATE TABLE $table_name (
					id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
					instance_id bigint(20) UNSIGNED NOT NULL,
					post_id bigint(20) UNSIGNED NOT NULL,
					severity tinyint(1) UNSIGNED NOT NULL,
					entry longtext NOT NULL,
					log_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				  	PRIMARY KEY (id)
				) $collate;";

		dbDelta( $sql );

	}

}

endif;

return new Houzez_Property_Feed_Install();