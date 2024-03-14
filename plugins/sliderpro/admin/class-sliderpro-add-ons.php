<?php
/**
 * Handles the admin functionality of the plugin's add-ons.
 * 
 * @since 4.8.0
 */
class BQW_SliderPro_Add_Ons {

	/**
	 * Current class instance.
	 * 
	 * @since 4.8.0
	 * 
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Current class instance of the public class.
	 * 
	 * @since 4.8.0
	 * 
	 * @var object
	 */
	protected $plugin = null;

	/**
	 * Plugin slug.
	 * 
	 * @since 4.8.0
	 * 
	 * @var string
	 */
	protected $plugin_slug = '';

	/**
	 * Current class instance of the plugin admin class.
	 * 
	 * @since 4.8.0
	 * 
	 * @var object
	 */
	protected $plugin_admin = null;

	/**
	 * Screen reference of the current add-ons page.
	 * 
	 * @since 4.8.0
	 * 
	 * @var object
	 */
	protected $add_ons_screen = null;

	/**
	 * Remote API address.
	 * 
	 * @since 4.8.0
	 * 
	 * @var string
	 */
	const REMOTE_API = 'https://bqworks.net/api/';

	/**
	 * The lost of allowed statuses for the license key.
	 * If, during the license key verification, the license key gets
	 * a different status, like 'unknown', the license key data
	 * will not be stored.
	 * 
	 * @since 4.8.0
	 * 
	 * @var array
	 */
	protected $allowed_license_key_statuses = array( 'valid', 'used', 'expired', 'not_valid' );

	/**
	 * Stores the host where the plugin runs. If it's a 'staging' host (staging subdomain or localhost),
	 * it will be stored as 'staging'. If not, it will be stored in a hash.
	 * 
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	protected $hashed_host = '';

	/**
	 * Initialize the admin by registering the required actions.
	 *
	 * @since 4.8.0
	 */
	private function __construct() {
		$this->plugin = BQW_SliderPro::get_instance();
		$this->plugin_slug = $this->plugin->get_plugin_slug();

		$this->plugin_admin = BQW_SliderPro_Admin::get_instance();

		$host = parse_url( get_site_url(), PHP_URL_HOST );
		$this->hashed_host = ( str_replace( array( 'staging', 'localhost' ), '', $host ) === $host ) ? sha1( $host ) : 'staging';

		// load the admin CSS and JavaScript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// add the add-ons submenu when the plugin menu renders
		add_action( 'sliderpro_admin_menu', array( $this, 'add_admin_menu' ) );

		add_action( 'wp_ajax_sliderpro_load_add_on_more_details', array( $this, 'ajax_load_add_on_more_details' ) );
		add_action( 'wp_ajax_sliderpro_load_install_add_on', array( $this, 'ajax_load_install_add_on' ) );
		add_action( 'wp_ajax_sliderpro_load_edit_add_on_license_key', array( $this, 'ajax_load_edit_add_on_license_key' ) );
		add_action( 'wp_ajax_sliderpro_verify_add_on_license_key', array( $this, 'ajax_verify_add_on_license_key' ) );
		add_action( 'wp_ajax_sliderpro_install_add_on', array( $this, 'ajax_install_add_on' ) );
		add_action( 'wp_ajax_sliderpro_activate_add_on', array( $this, 'ajax_activate_add_on' ) );
		add_action( 'wp_ajax_sliderpro_deactivate_add_on', array( $this, 'ajax_deactivate_add_on' ) );
	}

	/**
	 * Return the current class instance.
	 *
	 * @since 4.8.0
	 * 
	 * @return object The instance of the current class.
	 */
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Create the addon submenu when the base plugin menu renders.
	 *
	 * @since 4.8.0
	 */
	public function add_admin_menu() {
		$plugin_settings = BQW_SliderPro_Settings::getPluginSettings();
		$access = get_option( 'sliderpro_access', $plugin_settings['access']['default_value'] );

		$restricted_pages = apply_filters( 'sliderpro_restricted_pages' , array() );

		if ( ! in_array( $this->plugin_slug . '-add-ons', $restricted_pages ) ) {
			$this->add_ons_screen = add_submenu_page(
				$this->plugin_slug,
				__( 'Add-ons', 'sliderpro' ),
				__( 'Add-ons', 'sliderpro' ),
				$access,
				$this->plugin_slug . '-add-ons',
				array( $this, 'render_add_ons_page' ),
				3
			);

			$this->plugin_admin->add_plugin_screen_hook_suffix( $this->add_ons_screen );
		}
	}

	/**
	 * Loads the admin CSS files for the Add-ons page.
	 *
	 * @since 4.8.0
	 */
	public function enqueue_admin_styles() {
		$screen = get_current_screen();
		
		if ( $screen->id === $this->add_ons_screen ) {
			$file = get_option( 'sliderpro_load_unminified_scripts' ) === false ? 'admin/assets/css/sliderpro-add-ons.min.css' : 'admin/assets/css/sliderpro-add-ons.css';
			wp_enqueue_style( $this->plugin_slug . '-add-ons-admin-style', plugins_url( $file, dirname( __FILE__ ) ), array( 'sliderpro-admin-style' ), BQW_SliderPro::VERSION );
		}
	}

	/**
	 * Loads the admin JavaScript files for the Add-ons page.
	 *
	 * @since 4.8.0
	 */
	public function enqueue_admin_scripts() {
		$screen = get_current_screen();

		if ( $screen->id === $this->add_ons_screen ) {
			$file = get_option( 'sliderpro_load_unminified_scripts' ) === false ? 'admin/assets/js/sliderpro-add-ons.min.js' : 'admin/assets/js/sliderpro-add-ons.js';
			wp_enqueue_script( $this->plugin_slug . '-add-ons-admin', plugins_url( $file, dirname( __FILE__ ) ), array( 'jquery', 'sliderpro-admin-script' ), BQW_SliderPro::VERSION );
			
			wp_localize_script( $this->plugin_slug . '-add-ons-admin', 'sp_add_ons_js_vars', array(
				'admin' => admin_url( 'admin.php' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'check_license_key' => __( 'Checking the license key...', 'sliderpro' ),
				'install_add_on' => __( 'Installing the add-on...', 'sliderpro' ),
				'activate_add_on' => __( 'Activating the add-on...', 'sliderpro' ),
				'updating' => __( 'Updating...', 'sliderpro' ),
				'activating' => __( 'Activating...', 'sliderpro' ),
				'activated' => __( 'Activated', 'sliderpro' ),
				'deactivating' => __( 'Deactivating...', 'sliderpro' ),
				'deactivated' => __( 'Deactivated', 'sliderpro' ),
				'try_again' => __( 'Try again', 'sliderpro' )
			));
		}
	}

	/**
	 * Renders the add-ons page.
	 * 
	 * @since 4.8.0
	 */
	public function render_add_ons_page() {
		$add_ons_data = get_transient( 'sliderpro_add_ons_cached_data' );
		$error_message = '';

		// Load the remote data if the cache is expired or if the URL contains 'cache=0'.
		if ( $add_ons_data === false || ( isset( $_GET['cache'] ) && intval( $_GET['cache'] ) === 0 ) ) {
			$request = wp_remote_post(
				self::REMOTE_API,
				array(
					'body' => array(
						'action' => 'get-add-ons',
						'slug' => $this->plugin_slug
					)
				)
			);
	
			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				$error_message = __( 'Could not retrieve add-ons\' information from the server. Please try again later.', 'sliderpro' );
			} else {
				$add_ons_data = json_decode( wp_remote_retrieve_body( $request ), true );
				
				if ( is_array( $add_ons_data ) ) {
					set_transient( 'sliderpro_add_ons_cached_data', $add_ons_data, 60 * 60 * 12 );
				} else {
					$error_message = __( 'There was an error retrieving add-ons\' information from the server. Please try again later.', 'sliderpro' );
				}
			}
		}
		
		include( 'views/add-ons/add-ons.php' );
	}

	/**
	 * Sets the stored data for the add on.
	 *
	 * @param  string $add_on_slug The slug of the add-on.
	 * @param  array  $data        An array of key-values pairs with data for the license key.
	 * @since 4.8.0
	 */
	private function set_add_on_data( $add_on_slug, $data ) {
		$add_ons = get_option( 'sliderpro_add_ons', array() );

		if ( ! isset( $add_ons[ $add_on_slug ] ) ) {
			$add_ons[ $add_on_slug ] = array();
		}

		foreach ( $data as $name => $value ) {
			$add_ons[ $add_on_slug ][ $name ] = $value;
		}

		update_option( 'sliderpro_add_ons', $add_ons );
	}

	/**
	 * Gets the stored data for the add on.
	 * 
	 * @param  string            $add_on_slug The slug of the add-on.
	 * @param  string|array      $data        A string or an array of strings that indicate which data to retrieve.
	 * @return null|string|array              Returns null if the indicated data was not found,
	 * 										  the value of the data if a single data field was retrieved,
	 *                                        or and array of key-value pairs if multiple data fields were retrieved.
	 * @since 4.8.0
	 */
	private function get_add_on_data( $add_on_slug, $data ) {
		$add_ons = get_option( 'sliderpro_add_ons' );

		// No add-ons cache
		if ( $add_ons === false ) {
			return null;
		}

		// Add-on not in the cache
		if ( ! isset( $add_ons[ $add_on_slug ] ) ) {
			return null;
		}

		// If a single data field was specified
		if ( is_string( $data ) ) {
			return isset( $add_ons[ $add_on_slug ][ $data ] ) ? $add_ons[ $add_on_slug ][ $data ] : null;
		}

		// If an array of data fields were specified
		$values = array();

		foreach ( $data as $name ) {
			$values[ $name ] = isset( $add_ons[ $add_on_slug ][ $name ] ) ? $add_ons[ $add_on_slug ][ $name ] : null;
		}

		return $values;
	}

	/**
	 * AJAX call for loading the 'more details' modal window for an add-on.
	 *
	 * It's called from the Add-ons page.
	 *
	 * @since 4.8.0
	 */
	public function ajax_load_add_on_more_details() {
		$nonce = $_POST['nonce'];
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );

		if ( ! wp_verify_nonce( $nonce, 'open-more-details-' . $add_on_slug ) ) {
			die( 'This action was stopped for security purposes.' );
		}
		
		$add_ons_data = get_transient( 'sliderpro_add_ons_cached_data' );
		$add_on_data = null;
		$error_message = '';

		// Try to get the 'more details' data from the cache.
		// If it's not set, load the remote data.
		if ( isset( $add_ons_data[ $add_on_slug ]['more_details'] ) ) {
			$add_on_data = $add_ons_data[ $add_on_slug ]['more_details'];
		} else {
			$request = wp_remote_post(
				self::REMOTE_API,
				array(
					'body' => array(
						'action' => 'more-details',
						'slug' => $add_on_slug
					)
				)
			);

			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				$error_message = __( 'Could not retrieve the add-on\'s information from the server. Please try again later.', 'sliderpro' );
			} else {
				$add_on_data = json_decode( wp_remote_retrieve_body( $request ), true );

				// Add the add-on's 'more details' data to the cache only if the add ons' cache 
				// exists. Don't create the transient here if it doesn't already exist,
				// in order to prevent creating a transient with only this particular add-on's
				// data, while missing the data for the other add-ons.
				// In general, the cache should exist, but it's possible for it to expire after
				// accessing the 'Add-ons' page and before accessing 'more details' for the add-on.
				if ( ! is_array( $add_on_data ) ) {
					$error_message = __( 'There was an error retrieving the add-on\'s information from the server. Please try again later.', 'sliderpro' );	
				} else if ( is_array( $add_ons_data ) ) {
					$add_ons_data[ $add_on_slug ]['more_details'] = $add_on_data;
					set_transient( 'sliderpro_add_ons_cached_data', $add_ons_data, 60 * 60 * 12 );
				}
			}
		}

		include( 'views/add-ons/add-on-more-details.php' );

		die();
	}

	/**
	 * Checks the server for the validity of the license key and 
	 * retrieves more data ( status, date, duration, info ) about the license key.
	 *
	 * @param  string $add_on_slug The slug of the add-on.
	 * @param  string $license_key The license key for the add-on.
	 * @return array               An array of data regarding the license key.
	 * 
	 * @since 4.8.0
	 */
	private function verify_add_on_license_key( $add_on_slug, $license_key ) {
		$request = wp_remote_post(
			self::REMOTE_API,
			array(
				'body' => array(
					'action' => 'verify-license-key',
					'slug' => $add_on_slug,
					'license_key' => $license_key,
					'host' => $this->hashed_host
				)
			)
		);

		$response = array();

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$response['status'] = 'unknown';
			$response['message'] = __( 'There was an error with the license key verification process.', 'sliderpro' );
		} else {
			$remote_response = json_decode( wp_remote_retrieve_body( $request ), true );
			$allowed_remote_html = array(
				'div' => array( 'style' ),
				'p' => array( 'style' ),
				'span' => array( 'style' ),
				'strong' => array( 'style' ),
				'em' => array( 'style' ),
				'br' => array( 'style' )
			);

			if ( $remote_response['status'] === 'valid' ) {
				$response['status'] = 'valid';
				$response['message'] = __( 'The license key is valid.', 'sliderpro' );
				$response['date'] = sanitize_text_field( $remote_response['date'] );
				$response['duration'] = sanitize_text_field( $remote_response['duration'] );
				$response['info'] = wp_kses( $remote_response['info'], $allowed_remote_html );
			} else if ( $remote_response['status'] === 'expired' ) {
				$response['status'] = 'expired';
				$response['message'] = __( 'The license key is expired.', 'sliderpro' );
				$response['date'] = sanitize_text_field( $remote_response['date'] );
				$response['duration'] = sanitize_text_field( $remote_response['duration'] );
				$response['info'] = wp_kses( $remote_response['info'], $allowed_remote_html );
			} else if ( $remote_response['status'] === 'used' ) {
				$response['status'] = 'used';
				$response['message'] = __( 'The license key is valid, but already used.', 'sliderpro' );
				$response['info'] = wp_kses( $remote_response['info'], $allowed_remote_html );
			} else {
				$response['status'] = 'not_valid';
				$response['message'] = __( 'The license key is not valid.', 'sliderpro' );
				$response['info'] = wp_kses( $remote_response['info'], $allowed_remote_html );
			}
		}

		return $response;
	}

	/**
	 * AJAX call for loading the add-on installation modal window.
	 *
	 * It's called from the Add-ons page.
	 *
	 * @since 4.8.0
	 */
	public function ajax_load_install_add_on() {
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );

		include( 'views/add-ons/install-add-on.php' );

		die();
	}

	/**
	 * AJAX call for loading the add-on license key editor.
	 *
	 * It's called from the Add-ons page.
	 *
	 * @since 4.8.0
	 */
	public function ajax_load_edit_add_on_license_key() {
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );
		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, 'open-license-key-editor-' . $add_on_slug ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		// Get the license key and the last time when the license key status was checked
		$add_on_data = $this->get_add_on_data( $add_on_slug, array( 'license_key', 'license_key_checked_time' ) );

		$license_key = is_null( $add_on_data['license_key'] ) ? '' : sanitize_text_field( $add_on_data['license_key'] );
		$license_key_checked_time = is_null( $add_on_data['license_key_checked_time'] ) ? 0 : sanitize_text_field( $add_on_data['license_key_checked_time'] );

		// If the license key status was checked more than a day ago, check it now
		// and store the response.
		if ( strtotime( '+1 day', $license_key_checked_time ) < time() ) {
			$response = $this->verify_add_on_license_key( $add_on_slug, $license_key );
			
			if ( in_array( $response['status'], $this->allowed_license_key_statuses ) ) {
				$new_add_on_data = array();
				$new_add_on_data['license_key_status'] = $response['status'];

				if ( isset( $response['info'] ) ) {
					$new_add_on_data['license_key_info'] = $response['info'];
				}
	
				// Reset the checked time
				$new_add_on_data['license_key_checked_time'] = time();
	
				// Store the new status
				$this->set_add_on_data( $add_on_slug, $new_add_on_data );
			}
		}

		// Get the current license key status
		$add_on_data = $this->get_add_on_data( $add_on_slug, array( 'license_key_status', 'license_key_info' ) );
		
		$allowed_remote_html = array(
			'div' => array( 'style' ),
			'p' => array( 'style' ),
			'span' => array( 'style' ),
			'strong' => array( 'style' ),
			'em' => array( 'style' ),
			'br' => array( 'style' )
		);

		$license_key_status = is_null( $add_on_data['license_key_status'] ) ? '' : sanitize_text_field( $add_on_data['license_key_status'] );
		$license_key_info = is_null( $add_on_data['license_key_info'] ) ? '' : wp_kses( $add_on_data['license_key_info'], $allowed_remote_html );
		$license_key_info_class = $license_key_status === 'valid' ? 'license-key-valid' : 'license-key-not-valid';

		include( 'views/add-ons/edit-license-key.php' );

		die();
	}

	/**
	 * AJAX call for verifying the license key for an add-on.
	 * It stores the license key status and details.
	 * It's called from the 'install add-on' modal window.
	 *
	 * @since 4.8.0
	 */
	public function ajax_verify_add_on_license_key() {
		$nonce = $_POST['nonce'];
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );
		$license_key = sanitize_text_field( $_POST['license_key'] );

		if ( ! wp_verify_nonce( $nonce, 'verify-add-on-license-key-' . $add_on_slug ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		$response = $this->verify_add_on_license_key( $add_on_slug, $license_key );

		if ( in_array( $response['status'], $this->allowed_license_key_statuses ) ) {

			// Construct the new add-on data and store it.
			$add_on_data = array();
			$add_on_data['license_key'] = $license_key;
			$add_on_data['license_key_checked_time'] = time();

			$add_on_data['license_key_status'] = $response['status'];

			if ( isset( $response['date'] ) ) {
				$add_on_data['license_key_date'] = $response['date'];
			}
	
			if ( isset( $response['duration'] ) ) {
				$add_on_data['license_key_duration'] = $response['duration'];
			}
	
			if ( isset( $response['info'] ) ) {
				$add_on_data['license_key_info'] = $response['info'];
			}

			$this->set_add_on_data( $add_on_slug, $add_on_data );
		}
		
		echo json_encode( $response );

		die();
	}

	/**
	 * AJAX call for verifying the license key and installing the add-on.
	 *
	 * It's called from the 'install add-on' modal window.
	 *
	 * @since 4.8.0
	 */
	public function ajax_install_add_on() {
		$nonce = $_POST['nonce'];
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );
		$license_key = sanitize_text_field( $_POST['license_key'] );
		
		if ( ! wp_verify_nonce( $nonce, 'install-add-on-' . $add_on_slug ) || ! current_user_can( 'install_plugins' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		// Construct the download link.
		$download_link = self::REMOTE_API . '?action=download&slug=' . $add_on_slug . '&license_key=' . $license_key . '&host=' . $this->hashed_host;

		// Try to install the add-on from the URL.
		$upgrader_skin = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $upgrader_skin );
		$installation_result = $upgrader->install( $download_link );

		$response = array();

		if ( $installation_result === true ) {
			$response['status'] = 'installed';
			$response['message'] = __( 'Add-on installed successfully.', 'sliderpro' );
		} else if ( is_wp_error( $installation_result ) ) {
			$response['message'] = $installation_result->get_error_message();
		} else if ( is_wp_error( $upgrader_skin->result ) ) {
			if ( in_array( 'folder_exists', $upgrader_skin->result->get_error_codes() ) ) {
				$response['status'] = 'installed';
				$response['message'] = __( 'The add-on is already installed.', 'sliderpro' );
			} else {
				$response['message'] = $upgrader_skin->result->get_error_message();
			}
		} else if ( is_null( $installation_result ) ) {
			$response['message'] = __( 'Unable to connect to the filesystem.', 'sliderpro' );
		}

		if ( $response['status'] === 'installed' ) {
			$this->set_add_on_data( $add_on_slug, array( 'status' => 'installed' ) );
		}

		echo json_encode( $response );
		
		die();
	}

	/**
	 * AJAX call for activating the add-on.
	 *
	 * It's called from the 'install add-on' modal window or the add-on panel.
	 *
	 * @since 4.8.0
	 */
	public function ajax_activate_add_on() {
		$nonce = $_POST['nonce'];
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );
		
		if ( ! wp_verify_nonce( $nonce, 'activate-add-on-' . $add_on_slug ) || ! current_user_can( 'activate_plugins' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		// Plugin reference.
		$add_on_file = $add_on_slug . '/' . $add_on_slug . '.php';

		$response = array();

		if ( is_plugin_active( $add_on_file ) ) {
			$response['status'] = 'activated';
			$response['message'] = __( 'The add-on is already activated.', 'sliderpro' );
		} else {
			// Try to activate the add-on.
			$activation_result = activate_plugin( $add_on_file );

			if ( is_null( $activation_result ) ) {
				$response['status'] = 'activated';
				$response['message'] = __( 'Add-on activated successfully.', 'sliderpro' );
			} else if ( is_wp_error( $activation_result ) ) {
				$response['message'] = __( 'An error occurred during activation.', 'sliderpro' );
			}
		}

		if ( $response['status'] === 'activated' ) {
			$this->set_add_on_data( $add_on_slug, array( 'status' => 'activated' ) );
		}

		echo json_encode( $response );
		
		die();
	}

	/**
	 * AJAX call for deactivating the add-on.
	 *
	 * It's called from the add-on panel
	 *
	 * @since 4.8.0
	 */
	public function ajax_deactivate_add_on() {
		$nonce = $_POST['nonce'];
		$add_on_slug = sanitize_text_field( $_POST['add_on_slug'] );
		
		if ( ! wp_verify_nonce( $nonce, 'deactivate-add-on-' . $add_on_slug ) || ! current_user_can( 'activate_plugins' ) ) {
			die( 'This action was stopped for security purposes.' );
		}

		// Plugin reference.
		$add_on_file = $add_on_slug . '/' . $add_on_slug . '.php';
		
		$response = array(
			'status' => 'deactivated'
		);

		if ( is_plugin_active( $add_on_file ) ) {
			deactivate_plugins( $add_on_file );
			
			$response['message'] = __( 'Add-on deactivated successfully.', 'sliderpro' );
		} else {
			$response['message'] = __( 'The add-on is already deactivated.', 'sliderpro' );
		}

		if ( $response['status'] === 'deactivated' ) {
			$this->set_add_on_data( $add_on_slug, array( 'status' => 'installed' ) );
		}

		echo json_encode( $response );
		
		die();
	}
}