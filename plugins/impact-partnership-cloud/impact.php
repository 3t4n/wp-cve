<?php
/**
 * Plugin Name:       Impact: Partnership Cloud
 * Description:       Partnership cloud app plugin for Woocomerce that tracks every conversion made trough one of Impact's referral links.
 * Version:           1.0.26
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Impact
 * Author URI:        https://www.impact.com
 *
 * WC requires at least: 4.7
 * WC tested up to: 8
 *
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package impact_partnership_cloud
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
/**
 * ImpactPlugin
 */
class ImpactPlugin {

	/**
	 * Current version of the plugin
	 *
	 * @var string
	 */
	private $version = '1.0.26';
	/**
	 * Singleton instance of the plugin
	 *
	 * @var mixed
	 */
	private static $instance = false;

	private $middleman_url = 'https://woocommerce-integration.impact.com';

	/**
	 * Construct
	 *
	 * Load all the actions in the plugin
	 */
	public function __construct() {
		// declares compability with WooCommerce HPOS
		add_action( 'before_woocommerce_init', function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} );
		add_option( 'impact_existing_user', 'false' );
		add_action( 'rest_api_init', array( $this, 'impact_post_endpoint' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_impact_scripts' ) );
		add_action( 'admin_menu', array( $this, 'impact_settings_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'impact_settings_page_init' ) );
		add_action( 'add_option_impact_settings_option_name', array( $this, 'impact_hook_existing_user' ), 10, 2 );
		add_action( 'update_option_impact_settings_option_name', array( $this, 'impact_hook_save_options' ), 10, 2 );
		add_action( 'woocommerce_before_order_object_save', array( $this, 'add_metadata_before_order_save' ), 10, 2 );
		add_action( 'activated_plugin', array( $this, 'impact_activate' ) );
		add_action( 'wp_head', array( $this, 'impact_enqueue_default_script' ) );
	}

	/**
	 * Get Instance
	 * In order to have only one instance of the class at all time.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Impact post endpoint
	 * Register the callback url for the woocommerce authentication,
	 * works with the rest_api_init hook
	 */
	public function impact_post_endpoint() {
		register_rest_route(
			'impact/v1',
			'/callback',
			array(
				'methods'             => 'POST',
				'callback'            => array(
					$this,
					'impact_handle_callback',
				),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'impact/v1/',
			'impact-settings',
			array(
				'methods'             => 'GET',
				'callback'            => array(
					$this,
					'impact_handle_get_impact_settings_callback',
				),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Impact handle callback
	 *
	 * Get the auth data from woocommerce and register the shop
	 * with saasler
	 *
	 * @param Objetc $request response from the API call.
	 */
	public function impact_handle_callback( $request ) {
		$post_data = file_get_contents( 'php://input' );
		if ( empty( $post_data ) ) {
			http_response_code( 400 );
			die;
		}

		$post_data = json_decode( $post_data );

		$user_id         = sanitize_user( $post_data->user_id );
		$key_id          = sanitize_text_field( $post_data->key_id );
		$consumer_key    = sanitize_text_field( $post_data->consumer_key );
		$consumer_secret = sanitize_text_field( $post_data->consumer_secret );

		$current_user = get_user_by( 'login', $post_data->user_id );
		$user_email   = null;

		if ( $current_user instanceof WP_User ) {
			$user_email = $current_user->user_email;
		}

		$data = array(
			'shop' => array(
				'woocommerce_domain' => home_url(),
				'email'              => $user_email,
				'key_id'             => $key_id,
				'user_id'            => $user_id,
				'consumer_key'       => $consumer_key,
				'consumer_secret'    => $consumer_secret,
				'key_permissions'    => 'read_write',
				'version'            => $this->version,
			),
		);

		$response = wp_remote_post(
			$this->middleman_url . '/shops',
			array(
				'headers' => array(
					'content-type' => 'application/json',
				),
				'body'    => wp_json_encode( $data ),
			)
		);

		if ( 422 === intval( $response['response']['code'] ) ) {
			add_settings_error(
				'impact_settings_option_name',
				'Authentication error',
				'The store is already authenticated. ',
				'error'
			);
		}
		if ( 201 === intval( $response['response']['code'] ) ) {
			update_option( 'impact_existing_user', 'true' );
			update_option( 'impact_request_value', $consumer_key );
		}
	}

	/**
	 * Impact handle get impact settings callback
	 *
	 * Method that handles woocommerce grant authentication
	 *
	 * @param Object $request woocommerce acceptance.
	 */
	public function impact_handle_get_impact_settings_callback( $request ) {
		if ( ! isset( $request['consumer_key'] ) ) {
			wp_send_json( 'request is missing consumer_key', 422 );
			die;
		}
		$bearer       = get_option( 'impact_request_value' );
		$consumer_key = sanitize_text_field( $request['consumer_key'] );
		if ( $bearer !== $consumer_key ) {
			wp_send_json( 'consumer_key is not valid', 401 );
			die;
		}
		$impact_settings = get_option( 'impact_settings_option_name' );
		$response        = array(
			'version'         => $this->version,
			'impact_settings' => $impact_settings,
		);
		wp_send_json( $response, 200 );
	}

	/**
	 * Add impact scripts
	 *
	 * Function call by the admin_enqueue_scripts hook, add scripts to the admin
	 */
	public function add_impact_scripts() {
		$pagename = null;
		if ( isset( $_GET['page'] ) ) {
			$pagename = sanitize_text_field( wp_unslash( $_GET['page'] ) );
		}
		if ( 'impact-settings' === $pagename ) {
			wp_register_style( 'impact-form-css', plugins_url( '/css/css.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'impact-form-css' );
			wp_register_style( 'impact-css', plugins_url( '/css/impact.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'impact-css' );
			wp_enqueue_script( 'impact-js', plugins_url( '/js/impact.js', __FILE__ ), array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
			wp_register_style( 'impact-landing-page-css', plugins_url( '/css/stylesheets/landing-page.css', __FILE__ ), array(), $this->version );
		}
		if ( 'impact-settings-delete' === $pagename ) {
			wp_register_style( 'impact-form-css', plugins_url( '/css/css.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'impact-form-css' );
			wp_register_style( 'impact-delete-integration-page-css', plugins_url( '/css/delete-page.css', __FILE__ ), array(), $this->version );
			wp_enqueue_style( 'impact-delete-integration-page-css' );
		}
	}

	/**
	 * Impact settings add plugin page
	 *
	 * Function call by the admin_menu, creates the setings page
	 */
	public function impact_settings_add_plugin_page() {

		add_submenu_page(
			'woocommerce',
			'Impact settings',
			'Impact settings',
			'manage_options',
			'impact-settings',
			array( $this, 'impact_settings_create_admin_page' ),
			3
		);

		add_submenu_page(
			'impact-setttings',
			'Delete Impact Integration',
			'Impact settings',
			'manage_options',
			'impact-settings-delete',
			array( $this, 'impact_delete_integration_page' ),
			5
		);
	}

	/**
	 * Impact settings create admin page
	 *
	 * This function creates the HTML for the impact settings page
	 */
	public function impact_settings_create_admin_page() {
		if ( isset( $_GET['page'] ) &&  'impact-settings' === $_GET['page'] ) {
			wp_enqueue_style( 'impact-landing-page-css' );
			include plugin_dir_path( __FILE__ ) . 'includes/impact_settings_page.php';
		}
	}

	/**
	 * Impact delete integration page
	 *
	 * This function creates the HTML for the impact delete integration page
	 */
	public function impact_delete_integration_page() {
		$impact_request_value = get_option('impact_request_value');
		if ( !$impact_request_value ) {
			wp_safe_redirect( home_url() . '/wp-admin/admin.php?page=impact-settings');
			exit;
		}
		if ( isset( $_GET['page'] ) && 'impact-settings-delete' === $_GET['page'] ) {
			include plugin_dir_path( __FILE__ ) . 'includes/impact_delete_integration.php';
		}
	}

	/**
	 * Impact settings page init
	 *
	 * Function that register the settings and form fields in the
	 * impact settings page.
	 */
	public function impact_settings_page_init() {
		$this->impact_settings_form();
		$this->impact_integration_delete();
	}

	/**
	 * Impact integration delete
	 *
	 * Registers the form for the integration credentials deletion
	 */
	public function impact_integration_delete() {
		register_setting(
			'impact_integration_delete_option_group',
			'impact_integration_delete_option_name',
			array( $this, 'impact_integration_delete_sanitize' )
		);
		add_settings_section(
			'impact_integration_delete_section',
			'',
			array( $this, 'impact_integration_delete_section_info' ),
			'impact-integration-delete'
		);
		add_settings_field(
			'delete_confirmation',
			'Delete confirmation',
			array( $this, 'delete_confirmation_field_callback' ),
			'impact-integration-delete',
			'impact_integration_delete_section'
		);
	}

	/**
	 * Impact settings sanitize
	 *
	 * Function for sanitize the values in the delete integratino form
	 *
	 * @param array $input form input.
	 * @return array $sanitary_options, input values sanitized
	 */
	public function impact_integration_delete_sanitize( $input ) {
		if ( get_settings_errors('impact_integration_delete_option_name') ) {
			return null;
		}
		if ( 'POST' === $_SERVER['REQUEST_METHOD']) {
			$post_data = $_POST;
			if ( 'yes' !== $post_data['delete_confirmation'] ) {
				add_settings_error(
					'impact_integration_delete_option_name',
					'Confirmation error;',
					'Delete confirmation needs to be checked',
					'error'
				);
				return null;
			}
			$this->deactivate();
			wp_safe_redirect( home_url() . '/wp-admin/admin.php?page=impact-settings' );
		}
		return $input;
	}

	/**
	 * Impact settings page init
	 *
	 * Function that register the settings and form fields in the
	 * impact settings page.
	 */
	private function impact_settings_form() {
		register_setting(
			'impact_settings_option_group',
			'impact_settings_option_name',
			array( $this, 'impact_settings_sanitize' )
		);

		add_settings_section(
			'impact_settings_setting_section',
			'',
			array( $this, 'impact_settings_section_info' ),
			'impact-settings-admin'
		);

		add_settings_field(
			'impact_account_sid_0',
			'Impact Account SID',
			array( $this, 'impact_account_sid_0_callback' ),
			'impact-settings-admin',
			'impact_settings_setting_section'
		);

		add_settings_field(
			'impact_auth_token_1',
			'Auth Token',
			array( $this, 'impact_auth_token_1_callback' ),
			'impact-settings-admin',
			'impact_settings_setting_section'
		);

		add_settings_field(
			'program_id_2',
			'Program ID',
			array( $this, 'program_id_2_callback' ),
			'impact-settings-admin',
			'impact_settings_setting_section'
		);

		add_settings_field(
			'event_type_id_3',
			'Event Type ID',
			array( $this, 'event_type_id_3_callback' ),
			'impact-settings-admin',
			'impact_settings_setting_section'
		);

		add_settings_field(
			'custom_script_5',
			'Universal Tracking Tag',
			array( $this, 'custom_script_5_callback' ),
			'impact-settings-admin',
			'impact_settings_setting_section'
		);
	}

	/**
	 * Impact settings sanitize
	 *
	 * Function for sanitize the values in the form fields
	 *
	 * @param array $input form input.
	 * @return array $sanitary_options, input values sanitized
	 */
	public function impact_settings_sanitize( $input ) {
		$options         = get_option( 'impact_settings_option_name' );
		$sanitary_values = array();
		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) ) {
				$sanitary_values[ $key ] = wp_strip_all_tags( stripslashes( $input[ $key ] ) );
			}
			if ( 'custom_script_5' === $key ) {
				$sanitary_values[ $key ] = $input[ $key ];
			}
			if ( empty( $value ) ) {
				$e_value = $key;
				switch ( $key ) {
					case 'impact_account_sid_0':
						$e_value = 'Impact Account SID';
						break;
					case 'impact_auth_token_1':
						$e_value = 'Auth Token';
						break;
					case 'program_id_2':
						$e_value = 'Program ID';
						break;
					case 'event_type_id_3':
						$e_value = 'Event Type ID';
						break;
					case 'custom_script_5':
						$e_value = 'UTT Script';
						break;
				}

				add_settings_error(
					'impact_settings_option_name',
					'Missing value error',
					$e_value . ": field can't be empty.",
					'error'
				);
				return $options;
			}
		}
		return $sanitary_values;
	}

	/**
	 * Impact settings section info
	 */
	public function impact_settings_section_info() {
	}


	/**
	 * Impact integration delete section info
	 */
	public function impact_integration_delete_section_info() {
	}

	/**
	 * Impact integration delete callbakc sid callback
	 *
	 * Creates the HTML code for the Delete confirmation field in the Impact integration delete form
	 */
	public function delete_confirmation_field_callback() {
		printf(
			'<label for="delete_confirmation">
			<input type="checkbox" id="delete_confirmation" name="delete_confirmation" value="yes">
			By checking this box I confirm I wish to delete the Impact credentials from this application.</label><br>'
		);
	}

	/**
	 * Impact account sid callback
	 *
	 * Creates the HTML code for the Impact Account SID field in the settings form
	 */
	public function impact_account_sid_0_callback() {
		$value = get_option( 'impact_settings_option_name' );
		printf(
			'<input class="regular-text impact-placeholder" type="text" name="impact_settings_option_name[impact_account_sid_0]" id="impact_account_sid_0" placeholder="%s" required>
			<small id="passwordHelpBlock" class="form-text text-muted">
			You can find this in your Impact account > Settings > API
			</small>',
			isset( $value['impact_account_sid_0'] ) ? esc_attr( '********' . substr( $value['impact_account_sid_0'], -4 ) ) : 'Account SID'
		);
	}

	/**
	 * Impact auth token callback
	 *
	 * Creates the HTML code for the Impact Auth Token field in the settings form
	 */
	public function impact_auth_token_1_callback() {
		$value = get_option( 'impact_settings_option_name' );
		printf(
			'<input class="regular-text impact-placeholder" type="text" name="impact_settings_option_name[impact_auth_token_1]" id="impact_auth_token_1" placeholder="%s" required><small id="passwordHelpBlock" class="form-text text-muted">
			You can find this in your Impact account > Settings > API
			</small>',
			isset( $value['impact_auth_token_1'] ) ? esc_attr( '********' . substr( $value['impact_auth_token_1'], -4 ) ) : 'Auth Token'
		);
	}

	/**
	 * Impact program id callback
	 *
	 * Creates the HTML code for the Impact Program ID field in the settings form
	 */
	public function program_id_2_callback() {
		$value = get_option( 'impact_settings_option_name' );
		printf(
			'<input class="regular-text impact-placeholder" type="text" name="impact_settings_option_name[program_id_2]" id="program_id_2" value="%s" placeholder="Program ID" required><small class="form-text text-muted">
			You can find this in your Impact account. At the top left, click the Program Name > Programs
			</small>',
			isset( $value['program_id_2'] ) ? esc_attr( $value['program_id_2'] ) : ''
		);
	}

	/**
	 * Impact event type id callback
	 *
	 * Creates the HTML code for the Impact Event Type field in the settings form
	 */
	public function event_type_id_3_callback() {
		$value = get_option( 'impact_settings_option_name' );
		printf(
			'<input class="regular-text impact-placeholder" type="text" name="impact_settings_option_name[event_type_id_3]" id="event_type_id_3" value="%s" placeholder="Event Type ID" required><small id="passwordHelpBlock" class="form-text text-muted">
			You can find this in your Impact account > Settings > Tracking > Event Types
			</small>',
			isset( $value['event_type_id_3'] ) ? esc_attr( $value['event_type_id_3'] ) : ''
		);
	}

	/**
	 * Impact custom script callback
	 *
	 * Creates the HTML code for the Impact Universal Traking Tag field in the settings form
	 */
	public function custom_script_5_callback() {
		$value = get_option( 'impact_settings_option_name' );
		printf(
			'<textarea class="large-text impact-placeholder" rows="5" name="impact_settings_option_name[custom_script_5]" id="custom_script_5" placeholder="Universal Tracker Tag" required>%s</textarea><small class="form-text text-muted">
			You can find this in your Impact Account > Settings > Tracking > General > Universal Tracking Tag field
			</small>',
			isset( $value['custom_script_5'] ) ? esc_attr( $value['custom_script_5'] ) : ''
		);
	}

	/**
	 * Impact enqueue default script
	 *
	 * Function that decides the script that must be loaded for the user
	 */
	public function impact_enqueue_default_script() {
		wp_enqueue_script( 'impact-utt-script', plugins_url( '/js/scripts/impact-user-agent-script.js', __FILE__ ), array( 'jquery' ), $this->version, true );
		$options = get_option( 'impact_settings_option_name' );
		if ( ! $options || ( strlen( $options['custom_script_5'] ) < 5 ) ) {
			$this->enqueue_default_script();
		} else {
			$this->enqueue_custom_script( $options['custom_script_5'] );
		}
	}

	/**
	 * Impact enqueue custom script
	 *
	 * Function that injects the correct javascripts scripts into the page
	 *
	 * @param mixed $custom_script_5 utt script submitted by user.
	 */
	public function enqueue_custom_script( $custom_script_5 ) {
		wp_dequeue_script( plugins_url( '/js/scripts/impact-default-script.js', __FILE__ ) );
		$impact_url = $this->extract_url_from_custom_script_5( $custom_script_5 );
		if ( $impact_url ) {
			$this->enqueue_custom_script_with_impact_url( $impact_url );
		} else {
			$this->enqueue_default_script();
		}
	}

	/**
	 * Impact enqueu_custom_script_with_impact_url
	 *
	 * Function that injects loads the utt script to the page
	 *
	 * @param mixed $impact_url url extracted from utt script.
	 */
	public function enqueue_custom_script_with_impact_url( $impact_url ) {
		include plugin_dir_path( __FILE__ ) . 'includes/utt-script.php';
		if ( is_checkout() ) {
			wp_enqueue_script(
				'impact-utt-script-getclickid',
				plugins_url( '/js/scripts/impact-utt-getclickid.js', __FILE__ ),
				array(),
				$this->version,
				true
			);
		}
	}

	/**
	 * Impact enqueue_default_script
	 *
	 * Function that enqueues default scripts in page
	 */
	public function enqueue_default_script() {
		wp_dequeue_script( plugins_url( '/js/scripts/impact-custom-script.js', __FILE__ ) );
		wp_enqueue_script( 'impact-default-script', plugins_url( '/js/scripts/impact-default-script.js', __FILE__ ), array(), $this->version, true );
	}

	/**
	 * Impact extract_url_from_custom_script_5
	 *
	 * Function that extracts url from the user's submitted utt script
	 *
	 * @param mixed $custom_script_5 url extracted from utt script.
	 */
	public function extract_url_from_custom_script_5( $custom_script_5 ) {
		$pattern = '/(https{0,1}:\/\/(?:.*)\/[[a-zA-Z0-9-]*\.js)/';
		preg_match_all( $pattern, $custom_script_5, $matches );
		if ( ! $matches[0] ) {
			return false;
		}
		return $matches[1][0];
	}

	/**
	 * Impact activate
	 *
	 * On plugin activation fires the call to the woocommerce API in order to get
	 * auth credentials
	 *
	 * @param mixed $plugin url extracted from utt script.
	 */
	public function impact_activate( $plugin ) {
		$arr = explode( '/', $plugin );
		if ( count( $arr ) >= 1 && strpos( $arr[ count( $arr ) - 1 ], 'impact' ) !== false ) {
			$store_url = home_url();
			$path      = '/wp-admin/admin.php?page=impact-settings';
			$url       = $store_url . $path;
			wp_safe_redirect( $url );
			exit;
			global $user;
			global $wpdb;

			$store_url    = home_url();
			$user         = wp_get_current_user();
			$endpoint     = '/wc-auth/v1/authorize';
			$params       = array(
				'app_name'     => 'Impact',
				'scope'        => 'read_write',
				'user_id'      => $user->user_login,
				'return_url'   => home_url() . '/wp-admin/admin.php?page=impact-settings',
				'callback_url' => home_url() . '/wp-json/impact/v1/callback',
			);
			$query_string = http_build_query( $params );
			$url          = $store_url . $endpoint . '?' . $query_string;
			wp_safe_redirect( $url );
			exit;
		}
	}

	/**
	 * Impact hook existing user
	 *
	 * If the user is registered on impact then the value is saved in the options table
	 *
	 * @param mixed $old_value old value of the record.
	 * @param mixed $new_value new value of the record.
	 */
	public function impact_hook_existing_user( $old_value, $new_value ) {
		update_option( 'impact_existing_user', 'true' );
		$this->impact_hook_save_options( $old_value, $new_value );
	}

	/**
	 * Impact hook save options
	 *
	 * Function that is fire by the update_option_impact_settings_option_name,
	 * register the store data in the saasler app.
	 *
	 * @param mixed $old_value old value of the record.
	 * @param mixed $new_value new value of the record.
	 */
	public function impact_hook_save_options( $old_value, $new_value ) {
		if ( strlen( $new_value['impact_account_sid_0'] ) === 4 && strlen( $new_value['impact_auth_token_1'] ) === 4 ) {
			return $new_value;
		}
		$bearer = get_option( 'impact_request_value' );

		$data = array(
			'username'          => $new_value['impact_account_sid_0'],
			'password'          => $new_value['impact_auth_token_1'],
			'campaign_id'       => $new_value['program_id_2'],
			'action_tracker_id' => $new_value['event_type_id_3'],
			'consumer_key'      => $bearer,
		);

		$response = wp_remote_request(
			$this->middleman_url . '/integration_setting',
			array(
				'method'  => 'PUT',
				'headers' => array(
					'content-type' => 'application/json',
				),
				'body'    => wp_json_encode( $data ),
			)
		);

		// Check for wp_error here.
		if ( ( $response instanceof WP_Error ) || ( 201 !== $response['response']['code'] ) ) {
			if ( 422 === $response['response']['code'] ) {
				add_settings_error(
					'impact_settings_option_name',
					'Authentication error',
					json_decode($response['body'])->error,
					'error'
				);
			} else {
				add_settings_error(
					'impact_settings_option_name',
					'Authentication error',
					"The store couldn't be authenticated by Saasler, please try again. ",
					'error'
				);
			}
		}

		$new_value['impact_account_sid_0'] = substr( $new_value['impact_account_sid_0'], -4 );
		$new_value['impact_auth_token_1']  = substr( $new_value['impact_auth_token_1'], -4 );
		update_option( 'impact_settings_option_name', $new_value );
		return $new_value;
	}

	/**
	 * Deactivate
	 *
	 * Function that fires on deactivate plugin, removes the shop from saasler app and
	 * removes all the variables from the options table.
	 */
	public function deactivate() {
		global $wpdb;
		$bearer = get_option( 'impact_request_value' );

		wp_remote_request(
			$this->middleman_url . '/uninstall',
			array(
				'method'  => 'DELETE',
				'headers' => array(
					'content-type' => 'application/json',
				),
				'body'    => wp_json_encode(
					array(
						'consumer_key' => $bearer,
					)
				),
			)
		);
		wp_dequeue_script( plugins_url( '/js/scripts/impact-custom-script.js', __FILE__ ) );
		wp_dequeue_script( plugins_url( '/js/scripts/impact-default-script.js', __FILE__ ) );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'impact_%'" );
	}

	/**
	 * Before checkout create order
	 *
	 * Get the value of the cookies and add it to the order as a meta-tag value.
	 *
	 * @param mixed $order order to be saved.
	 * @param mixed $data data of the order.
	 */
	public function before_checkout_create_order( $order, $data ) {
		$cookies = array( 'irclickid', 'customer_user_agent', 'customer_ip_address' );
		foreach ( $cookies as $cookie ) {
			$order->update_meta_data( $cookie, $this->sanitize_cookie( $cookie ) );
		}
	}

	/**
	 * Before processing payment add extra data to order
	 *
	 * Get the value of the cookies and add it to the order as a meta-tag value.
	 *
	 * @param mixed $order order to be saved.
	 */
	public function add_metadata_before_order_save( $order ) {
		$this->before_checkout_create_order( $order, null );
	}

	/**
	 * Sanitizes cookie value
	 *
	 * Sanitizes cookie value
	 *
	 * @param string $cookie_name cookie name input.
	 */
	private function sanitize_cookie( $cookie_name ) {
		$value = null;
		if ( isset( $_COOKIE[ $cookie_name ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_COOKIE[ $cookie_name ] ) );
		}
		return $value;
	}

	/**
	 * Uninstall
	 */
	public function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		check_admin_referer( 'bulk-plugins' );
		if ( __FILE__ !== WP_UNISTALL_PLUGIN ) {
			return;
		}
	}
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

	if ( class_exists( 'ImpactPlugin' ) ) {
		$impact_plugin = ImpactPlugin::get_instance();
	}
}
