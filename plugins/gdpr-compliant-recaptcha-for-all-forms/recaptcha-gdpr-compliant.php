<?php
	
	/**
	 * 
	 * Plugin Name: GDPR Compliant ReCaptcha for all forms
	 * Plugin URI: https://programmiere.de/
	 * Description: This plugin protects all forms and logins against spam and brute-force attacks. Invisible, GDPR compliant and user input is not required.
	 * Version: 3.6.7
	 * Requires at least: 4.8+
	 * Requires PHP: PHP-Version 5.6+
	 * Author: Matthias Nordwig
	 * Author URI: https://programmiere.de
	 * Text Domain: wordpress.org/plugins/gdpr-compliant-recaptcha-for-all-forms
	 * License: GPLv2
	 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
	 * 
	 */

	namespace VENDOR\RECAPTCHA_GDPR_COMPLIANT;

	defined( 'ABSPATH' ) or die( 'Are you ok?' );

	/** Class Core
	 * 
	 */
	class RCM_Main
	{
		/** Current version of the plugin */
		private $version = '3.6.7';

		/** Holding the instance of this class */
		public static $instance;

		/** Holding an instance of the class Message_Page */
		private $instance_message_page;

		/** Holding an instance of the class Stamp */
		private $instance_stamp;

		/** Holding an instance of the class Scoring */
		private $instance_scoring;

		/** Holding an instance of the class Dashboard_Widget */
		private $dashboard_widget;

		/** Holding an instance of the class Analysis */
		private $instance_analysis;

		/** Holding an instance of the class Settings_Menu */
		private $instance_settings_menu;

		/** An array of options in order to control the plugin */
		private $options;

		/** Get an instance of the class
		 * 
		 */
		public static function getInstance()
		{
			require_once dirname( __FILE__ ) . '/includes/class-option.php';
			require_once dirname( __FILE__ ) . '/includes/class-message-page.php';
			require_once dirname( __FILE__ ) . '/includes/class-stamp.php';
			require_once dirname( __FILE__ ) . '/includes/class-settings-menu.php';
			require_once dirname( __FILE__ ) . '/includes/class-dashboard-widget.php';
			require_once dirname( __FILE__ ) . '/includes/class-analysis.php';

			if ( ! self::$instance instanceof self ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/** Constructor of the class
		 */
		private function __construct()
		{
			$this->activate();
			$this->instance_stamp = new Stamp();
			add_action( 'admin_init', [ $this, 'gdpr_compliant_recaptcha_state_assets'], 10, 1 );
			add_action( 'activated_plugin', [ $this, 'activated' ] );
			$this->instance_message_page = new Message_Page();
			$this->instance_settings_menu = new Settings_Menu();
			$this->dashboard_widget = new Dashboard_Widget();
			if( get_option( Option::POW_DIRECT_ANALYSIS_MODE ) )
				$this->instance_analysis = new Analysis();
			if ( ! defined( 'GDPR_COMPLIANT_RECAPTCHA' ) ) {
				// in main plugin file 
				define( 'GDPR_COMPLIANT_RECAPTCHA', plugin_basename( __FILE__ ) );
			}
			//add_action('plugins_loaded', [$this, 'localization']);
			add_action('wp', [ $this, 'schedule_message_deletion' ]);
			// Hook the function to the scheduled event with parameters
			add_action('delete_old_messages_event', [ $this, 'delete_old_messages' ], 10);
		}

		function localization() {
			load_plugin_textdomain( 'gdpr-compliant-recaptcha-for-all-forms', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		/** Include the Javascript for proof of work calculation on the client-side
		 */
		public function gdpr_compliant_recaptcha_state_assets() {
			global $pagenow;

			if ( $pagenow === 'plugins.php' ) {
				wp_enqueue_script('wp-deactivation-message', plugins_url('/scripts/recaptcha-gdpr-pro-state.js', __FILE__ ), [], '1.0.0', true );
			}
		}

		// Function to delete old messages based on days to keep and rgm_type
		public function delete_old_messages() {
			global $wpdb;
			$days_to_keep[ 0 ] = 0;
			$days_to_keep[ 1 ] = get_option( Option::POW_CRON_DELETE_INBOX );
			$days_to_keep[ 2 ] = get_option( Option::POW_CRON_DELETE_SPAM );
			$days_to_keep[ 3 ] = get_option( Option::POW_CRON_DELETE_TRASH );
			
			foreach( $days_to_keep as $key => $value ){
				if( $value ){
					global $wpdb;
					$rgm_type = $key;
					$days = $days_to_keep[ $key ];
					// Calculate the date threshold (older than X days)
					$threshold_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

					// Define the table names
					$message_table = $wpdb->prefix . 'recaptcha_gdpr_message_rgm';
					$details_table = $wpdb->prefix . 'recaptcha_gdpr_details_rgd';

					// Get message IDs based on the WHERE condition
					$message_ids = $wpdb->get_col($wpdb->prepare("
						SELECT rgm_id FROM $message_table WHERE rgm_date < %s AND rgm_type = %d
					", $threshold_date, $rgm_type));

					if (!empty($message_ids)) {
						// Convert message IDs array to a comma-separated string for the SQL query
						$message_ids_str = implode(',', $message_ids);

						// Delete related details from the details table
						$wpdb->query($wpdb->prepare("
							DELETE FROM $details_table WHERE rgm_id IN ($message_ids_str)
						"));

						// Delete old messages from the main message table
						$wpdb->query($wpdb->prepare("
							DELETE FROM $message_table WHERE rgm_id IN ($message_ids_str)
						"));
					}
				}
			}
		}

		// Schedule the function to check for messages that shall be deleted
		function schedule_message_deletion() {
			if ( !wp_next_scheduled( 'delete_old_messages_event' ) ) {
				wp_schedule_event( time(), 'daily', 'delete_old_messages_event' );
			}
		}

		/** Activation of the plugin */
		public function activate(){

			$current_version = get_option( Option::POW_VERSION );

			// Check the plugin version
			if ( ! $current_version || version_compare( $this->version, $current_version, '>' ) ){

				//Create tables to save messages
				global $wpdb;
				//Table for message with standard metainformation
				$table_name_mail = $wpdb->prefix . 'recaptcha_gdpr_message_rgm';
				//Table for details for flexible amount of information
				$table_name_details = $wpdb->prefix . 'recaptcha_gdpr_details_rgd';
				//Table for the spam-check-stamps
				$table_name_stamp = $wpdb->prefix . 'recaptcha_gdpr_stamp_rgs';

				$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_mail ) );
				$table_exists = $wpdb->get_var( $query );
				//Check whether the table exists already
				if ( ! $table_exists == $table_name_mail ) {

					$results = $wpdb->query( "
						CREATE TABLE " . $table_name_mail . " (
						rgm_id INT AUTO_INCREMENT NOT NULL
						, rgm_type INT
						, rgm_date DATETIME
						, rgm_title VARCHAR(21844)
						, rgm_ip VARCHAR(255)
						, rgm_site VARCHAR(21844)
						, rgm_ajax INT
						, rgm_action VARCHAR(500)
						, rgm_pattern VARCHAR(1000)
						, PRIMARY KEY (rgm_id)
						) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
					");

				}

				$index_exists_query = $wpdb->prepare( "
					SELECT COUNT(*)
					FROM information_schema.statistics
					WHERE table_name = %s
					AND index_name = 'idx_rgm_type_attribute_value_date'
				", $table_name_mail );
			
				$index_exists = $wpdb->get_var( $index_exists_query );
			
				// Create the index if it doesn't exist
				if ( ! $index_exists ) {
					$wpdb->query( "
						CREATE INDEX idx_rgm_type_attribute_value_date
						ON $table_name_mail (rgm_type, rgm_date)
					" );
				}
				
				$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_details ) );
				//Check whether the table exists already
				if ( ! $wpdb->get_var( $query ) == $table_name_details ) {

					$results = $wpdb->query( "
						CREATE TABLE " . $table_name_details . " (
						rgd_id INT AUTO_INCREMENT NOT NULL
						, rgm_id INT
						, rgd_original_attribute VARCHAR(21844)
						, rgd_attribute VARCHAR(21844)
						, rgd_value VARCHAR(21844)
						, rgm_posted INT
						, PRIMARY KEY (rgd_id)
						) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
					");

				}

				$index_exists_query = $wpdb->prepare( "
					SELECT COUNT(*)
					FROM information_schema.statistics
					WHERE table_name = %s
					AND index_name = 'idx_rgd_attribute_value'
				", $table_name_details );
			
				$index_exists = $wpdb->get_var( $index_exists_query );
			
				// Create the index if it doesn't exist
				if ( ! $index_exists ) {
					$wpdb->query( "
						CREATE INDEX idx_rgd_attribute_value
						ON $table_name_details (rgd_attribute(255), rgd_value(255));
					" );
				}

				$index_exists_query = $wpdb->prepare( "
					SELECT COUNT(*)
					FROM information_schema.statistics
					WHERE table_name = %s
					AND index_name = 'idx_rgd_rgm_id'
				", $table_name_details );
			
				$index_exists = $wpdb->get_var( $index_exists_query );
			
				// Create the index if it doesn't exist
				if ( ! $index_exists ) {
					$wpdb->query( "
						CREATE INDEX idx_rgd_rgm_id
						ON $table_name_details (rgm_id);
					" );
				}
				
				$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_stamp ) );
				//Check whether the table exists already
				if ( ! $wpdb->get_var( $query ) == $table_name_stamp ) {

					$results = $wpdb->query( "
						CREATE TABLE " . $table_name_stamp . " (
						rgs_id INT AUTO_INCREMENT NOT NULL
						, rgs_ip VARCHAR(255)
						, rgs_stamp VARCHAR(255)
						, rgs_time DATETIME
						, PRIMARY KEY (rgs_id)
						) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
					");

				}

				//Update tables for older versions
				$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_mail ) );
				//Check whether the table exists already
				if ( $wpdb->get_var( $query ) == $table_name_mail ) {
					$query = $wpdb->prepare( 'SHOW COLUMNS FROM ' . $table_name_mail . ' LIKE %s', 'rgm_ajax' );
					if ( $wpdb->get_var( $query ) !== 'rgm_ajax' ) {
						$results = $wpdb->query( "
							ALTER TABLE " . $table_name_mail . "
							ADD COLUMN rgm_ajax INT,
							ADD COLUMN rgm_action VARCHAR(500);
						");
					}
				}

				//Update tables for older versions
				$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_details ) );
				//Check whether the table exists already
				if ( $wpdb->get_var( $query ) == $table_name_details ) {
					$query = $wpdb->prepare( 'SHOW COLUMNS FROM ' . $table_name_details . ' LIKE %s','rgm_posted' );
					if ( $wpdb->get_var( $query ) !== 'rgm_posted' ) {
						$results = $wpdb->query( "
							ALTER TABLE " . $table_name_details . "
							ADD COLUMN rgm_posted INT;
						");
					}
				}
				$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name_mail ) );
				//Check whether the table exists already
				if ( $wpdb->get_var( $query ) == $table_name_mail ) {
					$query = $wpdb->prepare( 'SHOW COLUMNS FROM ' . $table_name_mail . ' LIKE %s','rgm_pattern' );
					if ( $wpdb->get_var( $query ) !== 'rgm_pattern' ) {
						$results = $wpdb->query( "
							ALTER TABLE " . $table_name_mail . "
							ADD COLUMN rgm_pattern VARCHAR(1000);
						");
					}
					$query = $wpdb->prepare( 'SHOW COLUMNS FROM ' . $table_name_mail . ' LIKE %s','rgm_ip' );
					if ( $wpdb->get_var( $query ) !== 'rgm_ip' ) {
						$results = $wpdb->query( "
							ALTER TABLE " . $table_name_mail . "
							ADD COLUMN rgm_ip VARCHAR(255);
						");
					}
					$query = $wpdb->prepare( 'SHOW COLUMNS FROM ' . $table_name_mail . ' LIKE %s','rgm_site' );
					if ( $wpdb->get_var( $query ) !== 'rgm_site' ) {
						$results = $wpdb->query( "
							ALTER TABLE " . $table_name_mail . "
							ADD COLUMN rgm_site VARCHAR(21844);
						");
					}
				}
			
				update_option( Option::POW_VERSION, $this->version );
			}

		}

		/** Deactivation of the plugin */
		public function deactivate(){
			// Unschedule the event and remove the hooks
			wp_clear_scheduled_hook( 'delete_old_messages_event' );
			remove_action('delete_old_messages_event', [ $this, 'delete_old_messages' ], 10);
		}

		/** On activation go to settings menu*/
		public function activated( string $plugin ){
			/** On activation */
			if ( $plugin === plugin_basename( __FILE__ ) ) {
				$admin_Url = admin_url('options-general.php' . Option::PAGE_QUERY);
				exit( wp_redirect( $admin_Url ) );
			}
		}

	}

	$start_register = RCM_Main::getInstance();

	register_activation_hook( __FILE__, [ $start_register, 'activate' ] );
	register_deactivation_hook( __FILE__, [ $start_register, 'deactivate' ] );

?>