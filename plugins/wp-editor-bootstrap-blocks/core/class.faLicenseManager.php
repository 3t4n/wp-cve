<?php
/**
 * FA Product class.
 *
 * @version 1.0.1
 *
 * @package FA WP
 * @author  Virgial Berveling
 * @updated 2019-01-26
 * 
 */
if ( ! class_exists( 'FA_LicenseManager', false ) ) {

	class FA_LicenseManager {

		/**
		 * @const VERSION The version number of the License_Manager class
		 */
		const VERSION = 1;

		/**
		 * @var FA_License The license
		 */
		protected $product;

		/**
		 * @var boolean True if remote license activation just failed
		 */
		private $remote_license_activation_failed = false;

		/**
		 * @var array Array of license related options
		 */
		private $options = array();

		/**
		 * @var string Used to prefix ID's, option names, etc..
		 */
		protected $prefix;

		/**
		 * @var bool Boolean indicating whether this plugin is network activated
		 */
		protected $is_network_activated = false;

		/** function for fixing an deprecated error */
		static function is_network_activated()
		{
			return false;
		}
		/**
		 * Constructor
		 */
		public function __construct( $product ) {

			// Set the license
			$this->product = $product;

			// set prefix
			$this->prefix = sanitize_title_with_dashes( $this->product->get_item_name() . '_', null, 'save' );

		}

		/**
		 * Setup hooks
		 *
		 */
		public function setup_hooks() {

			// show admin notice if license is not active
			add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );

			// catch POST requests from license form
			add_action( 'admin_init', array( $this, 'check_post_license' ) );

			// setup item type (plugin|theme) specific hooks
			$this->specific_hooks();

			// setup the auto updater
			$this->setup_auto_updater();

		}

		/**
		 * Display license specific admin notices, namely:
		 *
		 * - License for the product isn't activated
		 * - External requests are blocked through WP_HTTP_BLOCK_EXTERNAL
		 */
		public function display_admin_notices() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// show notice if license is invalid
			if ( ! $this->license_is_valid()  ) {
				if ( $this->get_license_key() == '' ) {
					$message = __( '<b>Warning!</b> You didn\'t set your %s license key yet, which means you\'re missing out on updates and support! <a href="%s">Enter your license key</a> or <a href="%s" target="_blank">get a license here</a>.' );
				} else {
					$message = __( '<b>Warning!</b> Your %s license is inactive which means you\'re missing out on updates and support! <a href="%s">Activate your license</a> or <a href="%s" target="_blank">get a license here</a>.' );
				}

				$message = sprintf( __( $message, $this->product->get_text_domain() ), $this->product->get_item_name(), $this->product->get_license_page_url(), $this->product->get_tracking_url( 'activate-license-notice' ) );
				fa_add_notice( $message, 'error' );
			}

			// show notice if external requests are blocked through the WP_HTTP_BLOCK_EXTERNAL constant
			if ( defined( "WP_HTTP_BLOCK_EXTERNAL" ) && WP_HTTP_BLOCK_EXTERNAL === true ) {

				// check if our API endpoint is in the allowed hosts
				$host = parse_url( $this->product->get_api_url(), PHP_URL_HOST );

				if ( ! defined( "WP_ACCESSIBLE_HOSTS" ) || stristr( WP_ACCESSIBLE_HOSTS, $host ) === false ) {
					
					$message = sprintf( __( '<b>Warning!</b> You\'re blocking external requests which means you won\'t be able to get %s updates. Please add %s to %s.', $this->product->get_text_domain() ), $this->product->get_item_name(), '<strong>' . $host . '</strong>', '<code>WP_ACCESSIBLE_HOSTS</code>' );
					fa_add_notice($message,'error');
				}
			}
		}


		/**
		 * Remotely activate License
		 * @return boolean True if the license is now activated, false if not
		 */
		public function activate_license()
		{
			$result = $this->call_license_api( 'activate' );

			if ( $result ) {

				// story expiry date
				$exp_time = false;
				if ( isset( $result->expiration ) ) {
					$exp_time = strtotime( $result->expiration );
					if ($exp_time > 0 && $exp_time < time())
					{
						$this->set_license_expiry_date( $result->expiration );
					}
				}

				// show success notice if license is valid
				if ( !empty($result->success) && $result->code === 200 ) {

					$message = sprintf( __( "Your %s license has been activated. ", $this->product->get_text_domain() ), $this->product->get_item_name() );

					// show a custom notice if users have an unlimited license
					if ( isset($result->custom_fields->active_licenses) ) {
						if ( $result->custom_fields->active_licenses=== 1 )
						{
							$message .= sprintf( __( "You have used %d activation. ", $this->product->get_text_domain() ), $result->custom_fields->active_licenses );
						}else{
							$message .= sprintf( __( "You have used %d activations. ", $this->product->get_text_domain() ), $result->custom_fields->active_licenses );
						}
					}

					if ( !empty($exp_time) && $exp_time > 0 && $exp_time < strtotime( "+1 month" ) ) {
						$days_left = round( ( $exp_time - strtotime( "now" ) ) / 86400 );
						$message .= sprintf( __( '<a href="%s">Your license is expiring in %d days, would you like to extend it?</a>', $this->product->get_text_domain() ), $this->product->get_extension_url( 'license-expiring-notice' ), $days_left );
					}

					fa_add_notice( $message, 'success' );

				} else {
					/** Error codes */
					 
					$errormessages = array(
						'c401'=> __('Connection error', $this->product->get_text_domain()),
						'c404'=> __('License not found', $this->product->get_text_domain()),
						'c410'=> __('Invalid request. Missing arguments', $this->product->get_text_domain()),
						'c412'=> __('Maximum used domains reached', $this->product->get_text_domain()),
						'c420'=> __('License expired', $this->product->get_text_domain()),
						'c429'=> __('Api limits reached', $this->product->get_text_domain()),
						'c480'=> __('serial_reset_issue', $this->product->get_text_domain()),
						'c490'=> __('Issue detected with license', $this->product->get_text_domain()),
						'c601'=> __('Database error', $this->product->get_text_domain()),
						'c620'=> __('Charge backed', $this->product->get_text_domain()),
						'c630'=> __('Subscription cancelled at', $this->product->get_text_domain()),
						'c640'=> __('Subscription failed at', $this->product->get_text_domain()),
						'c999'=> __('Unknow problem', $this->product->get_text_domain())
					);
									

					// show notice if user is at their activation limit
					$message = isset($errormessages['c'.$result->code])?$errormessages['c'.$result->code]:(empty($result->message)?$errormessages['c999']:$result->message);
					fa_add_notice( __( 'License issue', $this->product->get_text_domain()).': '.$message.'<br/><em>code '.$result->code.'</em>', 'error'  );
					$this->remote_license_activation_failed = true;
				}

				$status = empty($result->status)?'unknown':$result->status;
				$this->set_license_status( $status );
			}

			return $this->license_is_valid();
		}

		/**
		 * Remotely deactivate License
		 * @return boolean True if the license is now deactivated, false if not
		 */
		public function deactivate_license() {

			$result = $this->call_license_api( 'remove' );

			if ( $result ) {
				// show notice if license is deactivated
				if ( isset($result->success ) && $result->success && $result->status === 'domain_removed' ) {
					fa_add_notice( sprintf( __( "Your %s license has been deactivated.", $this->product->get_text_domain() ), $this->product->get_item_name() ), 'warning');
				} else {
					fa_add_notice( sprintf( __( "Failed to deactivate your %s license.", $this->product->get_text_domain() ), $this->product->get_item_name() ), 'error' );
				}

				$this->set_license_status( 'deactivated' );
			}

			return ( $this->get_license_status() === 'deactivated' );
		}

		/**
		 * @param string $action activate|deactivate
		 *
		 * @return mixed
		 */
		protected function call_license_api( $action ) {

			// don't make a request if license key is empty
			if ( $this->get_license_key() === '' ) {
				$message = __( '<b>Warning!</b> You didn\'t set your license key yet.' );
				fa_add_notice( $message, 'warning' );
	
				return false;
			}

			// data to send in our API request

			$wp_http = new WP_Http();
			$bad_response = (object) array("success"=>false,"status"=>"unknown","code"=>500,"message"=>"Issues with license server. Bad response.");

			$request = $wp_http->request($this->product->get_api_url(),
			array(
				'method' => 'POST',
				'sslverify' => false,
				'body'	=> array(
					'action' => $action,
					'locale' => get_locale(),
					'serial'    => $this->get_license_key(),
					'item_name'  => urlencode( trim( $this->product->get_item_name() ) ),
					'sku'		=> $this->product->get_sku(),
					'domain' => str_replace(array('http://','https://'),'',site_url())
				)
			));

			if ( is_wp_error( $request ) ) {
				return $bad_response;
			}
			

			$results = (object) array("success"=>false,"status"=>"unknown","code"=>999);
			if (isset($request['body'])):
				try {
					$results = json_decode($request['body']);

				}catch(Exception $e)
				{
					return $bad_response;
				}
			endif;



			return $results;
		}


		/**
		 * Set the license status
		 *
		 * @param string $license_status
		 */
		public function set_license_status( $license_status ) {
			$this->set_option( 'status', $license_status );
		}

		/**
		 * Get the license status
		 *
		 * @return string $license_status;
		 */
		public function get_license_status() {
			$license_status = $this->get_option( 'status' );

			return trim( $license_status );
		}

		/**
		 * Set the license key
		 *
		 * @param string $license_key
		 */
		public function set_license_key( $license_key ) {
			$this->set_option( 'key', $license_key );
		}

		/**
		 * Gets the license key from constant or option
		 *
		 * @return string $license_key
		 */
		public function get_license_key() {
			$license_key = $this->get_option( 'key' );

			return trim( $license_key );
		}

		/**
		 * Set the license key
		 *
		 * @param string $license_key
		 */
		public function set_sku( $sku ) {
			$this->set_option( 'sku', $sku );
		}

		/**
		 * Gets the license key from constant or option
		 *
		 * @return string $license_key
		 */
		public function get_sku() {
			$sku = $this->get_option( 'sku' );

			return trim( $sku );
		}

		/**
		 * Gets the license expiry date
		 *
		 * @return string
		 */
		public function get_license_expiry_date() {
			return $this->get_option( 'expiry_date' );
		}

		/**
		 * Stores the license expiry date
		 */
		public function set_license_expiry_date( $expiry_date ) {
			$this->set_option( 'expiry_date', $expiry_date );
		}

		/**
		 * Checks whether the license status is active
		 *
		 * @return boolean True if license is active
		 */
		public function license_is_valid() {
			return ( $this->get_license_status() === 'active' );
		}

		/**
		 * Get all license related options
		 *
		 * @return array Array of license options
		 */
		protected function get_options() {

			// create option name
			$option_name = $this->prefix . 'fa_license';

			// get array of options from db
			if ( $this->is_network_activated ) {
				$options = get_site_option( $option_name, array() );
			} else {
				$options = get_option( $option_name, array() );
			}

			// setup array of defaults
			$defaults = array(
				'key'         => '',
				'status'      => '',
				'expiry_date' => ''
			);

			// merge options with defaults
			$this->options = wp_parse_args( $options, $defaults );

			return $this->options;
		}

		/**
		 * Set license related options
		 *
		 * @param array $options Array of new license options
		 */
		protected function set_options( array $options ) {
			// create option name
			$option_name = $this->prefix . 'fa_license';

			// update db
			if ( $this->is_network_activated ) {
				update_site_option( $option_name, $options );
			} else {
				update_option( $option_name, $options );
			}

		}

		/**
		 * Gets a license related option
		 *
		 * @param string $name The option name
		 *
		 * @return mixed The option value
		 */
		protected function get_option( $name ) {
			$options = $this->get_options();

			return $options[$name];
		}

		/**
		 * Set a license related option
		 *
		 * @param string $name  The option name
		 * @param mixed  $value The option value
		 */
		protected function set_option( $name, $value ) {
			// get options
			$options = $this->get_options();

			// update option
			$options[$name] = $value;

			// save options
			$this->set_options( $options );
		}

		/**
		 * Get the API availability information
		 *
		 * @return array
		 */
		public function get_api_availability(){
			return array(
				'url'          => $this->product->get_api_url(),
				'availability' => $this->check_api_host_availability(),
				'curl_version' => $this->get_curl_version(),
			);
		}

		/**
		 * Check if the API host address is available from this server
		 *
		 * @return bool
		 */
		private function check_api_host_availability() {
			$wp_http = new WP_Http();
			if ( $wp_http->block_request( $this->product->get_api_url() ) === false ) {
				return true;
			}

			return false;
		}

		/**
		 * Get the current curl version, or false
		 *
		 * @return mixed
		 */
		protected function get_curl_version() {
			if ( function_exists( 'curl_version' ) ) {
				$curl_version = curl_version();

				if ( isset( $curl_version['version'] ) ) {
					return $curl_version['version'];
				}
			}

			return false;
		}


		public function start_update()
		{
			if (  $this->license_is_valid()  ) {
				die('START UPDATING');
			}
		}

	}

}
