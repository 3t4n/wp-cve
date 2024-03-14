<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- cannot change main file's name
/**
 * Plugin Name: miniOrange SSO using SAML 2.0
 * Plugin URI: https://miniorange.com/
 * Description: miniOrange SAML plugin allows sso/login using Azure, Azure B2C, Okta, ADFS, Keycloak, Onelogin, Salesforce, Google Apps (Gsuite), Salesforce, Shibboleth, Centrify, Ping, Auth0 and other Identity Providers. It acts as a SAML Service Provider which can be configured to establish a trust between the plugin and IDP to securely authenticate and login the user to WordPress site.
 * Version: 5.1.3
 * Author: miniOrange
 * Author URI: https://miniorange.com/
 * License: MIT/Expat
 * License URI: https://docs.miniorange.com/mit-license
 * Text Domain: miniorange-saml-20-single-sign-on
 *
 * @package miniorange-saml-20-single-sign-on
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MO_SAML_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once 'handlers/class-mo-saml-base-handler.php';
require_once 'class-mo-saml-idp-metadata-reader.php';
require_once 'class-mo-saml-login-widget.php';
require_once 'class-mo-saml-customer.php';
require_once 'class-mo-saml-logger.php';
require_once 'mo-saml-settings-page.php';
require_once 'class-mo-saml-utilities.php';
require_once 'class-mo-saml-wp-config-editor.php';

/**
 * The Main class of the miniOrange SAML SSO Plugin.
 */
class Saml_Mo_Login {

	/**
	 * The Constructor for the main class. This takes care of initializing all the hooks used by the plugin.
	 */
	public function __construct() {
		add_action( 'activated_plugin', array( $this, 'mo_saml_redirect_after_activation' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_style' ) );
		add_action( 'admin_init', array( Mo_SAML_Base_Handler::class, 'mo_saml_save_settings_handler' ) );
		add_action( 'admin_init', array( 'Mo_SAML_Logger', 'mo_saml_admin_notices' ) );
		add_action( 'admin_footer', array( $this, 'feedback_request' ) );
		add_action( 'admin_menu', array( $this, 'miniorange_sso_menu' ) );
		add_action( 'admin_notices', array( $this, 'mo_saml_idp_notice' ) );
		add_action( 'login_form', array( $this, 'mo_saml_modify_login_form' ) );
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'mo_saml_plugin_action_links' ) );
		add_action( 'plugins_loaded', array( $this, 'mo_saml_load_translations' ) );
		add_action( 'wp_authenticate', array( $this, 'mo_saml_authenticate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'mo_saml_deactivate' ) );
		register_shutdown_function( array( $this, 'log_errors' ) );
		remove_action( 'admin_notices', array( Mo_SAML_Utilities::class, 'mo_saml_error_message' ) );
		remove_action( 'admin_notices', array( Mo_SAML_Utilities::class, 'mo_saml_success_message' ) );
	}

	/**
	 * This function is responsible for redirecting the admin to the SAML SSO plugin settings after the plugin is activated.
	 *
	 * @param string $plugin The activated plugin's name.
	 * @param bool   $network_wide If the plugin was activated network wide or not.
	 * @return void
	 */
	public function mo_saml_redirect_after_activation( $plugin, $network_wide ) {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking if multiple plugins were activated, doesn't require nonce verification. 
		if ( ! isset( $_GET['activate-multi'] ) && 'miniorange-saml-20-single-sign-on/login.php' === $plugin && ! $network_wide ) {
			wp_safe_redirect( self_admin_url() . 'admin.php?page=mo_saml_settings' );
			exit;
		}
	}

	/**
	 * Ensures fatal errors are logged so they can be picked up in the status report.
	 *
	 * @since 4.9.09
	 */
	public function log_errors() {
		Mo_SAML_Logger::mo_saml_log_critical_errors();
	}

	/**
	 * Takes care of loading the pot files for translations.
	 *
	 * @return void
	 */
	public function mo_saml_load_translations() {
		load_plugin_textdomain( 'miniorange-saml-20-single-sign-on', false, dirname( plugin_basename( __FILE__ ) ) . '/resources/lang/' );
	}


	/**
	 * Displays the feedback form upon plugin deactivation.
	 *
	 * @return void
	 */
	public function feedback_request() {
		mo_saml_display_saml_feedback_form();
	}

	/**
	 * The callback function for the plugin menu page. This is the starting point for displaying the plugin settings.
	 *
	 * @return void
	 */
	public function mo_login_widget_saml_options() {
		global $wpdb;

		mo_saml_register_saml_sso();
	}

	/**
	 * Takes care of cleaning some logger constant and welcome page flag once the plugin is deactivated.
	 *
	 * @return void
	 */
	public function mo_saml_deactivate() {
		delete_option( Mo_Saml_Options_Enum::NEW_USER );
		if ( ! Mo_SAML_Utilities::mo_saml_is_curl_installed() ) {
			return;
		}

		$site_home_path   = ABSPATH;
		$wp_config_path   = $site_home_path . 'wp-config.php';
		$wp_config_editor = new Mo_SAML_WP_Config_Editor( $wp_config_path );

		if ( is_writeable( $wp_config_path ) ) {
			$wp_config_editor->mo_saml_wp_config_update( 'MO_SAML_LOGGING', 'false' );
		}
		wp_safe_redirect( 'plugins.php' );
	}

	/**
	 * Enqueues all the css files required by the plugin
	 *
	 * @param string $page Contains the value of the page parameter from the URL along with its level and is used to make sure the css is loaded only where it is required.
	 * @return void
	 */
	public function plugin_settings_style( $page ) {
		wp_enqueue_style( 'mo_saml_notice_style', plugins_url( 'includes/css/notice.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
		if ( 'toplevel_page_mo_saml_settings' !== $page && 'miniorange-saml-2-0-sso_page_mo_saml_enable_debug_logs' !== $page ) {
			return;
		} else {
			wp_enqueue_style( 'mo_saml_bootstrap_css', plugins_url( 'includes/css/bootstrap/mo-saml-bootstrap.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
			wp_enqueue_style( 'mo_saml_jquery_ui_style', plugins_url( 'includes/css/jquery-ui.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
			wp_enqueue_style( 'mo_saml_admin_settings_style', plugins_url( 'includes/css/style_settings.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
			wp_enqueue_style( 'mo_saml_admin_settings_phone_style', plugins_url( 'includes/css/phone.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
			wp_enqueue_style( 'mo_saml_time_settings_style', plugins_url( 'includes/css/datetime-style-settings.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
			wp_enqueue_style( 'mo_saml_wpb-fa', plugins_url( 'includes/css/style-icon.min.css', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, 'all' );
		}
	}

	/**
	 * Enqueues all the js files required by the plugin
	 *
	 * @param string $page Contains the value of the page parameter from the URL along with its level and is used to make sure the js is loaded only where it is required.
	 * @return void
	 */
	public function plugin_settings_script( $page ) {
		if ( 'toplevel_page_mo_saml_settings' === $page || 'miniorange-saml-2-0-sso_page_mo_saml_enable_debug_logs' === $page ) {
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'mo_fmo_saml_selected_idp_divct2_script', plugins_url( 'includes/js/select2.min.js', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, false );
			wp_enqueue_script( 'mo_saml_timepicker_script', plugins_url( 'includes/js/jquery.timepicker.min.js', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, false );
			wp_enqueue_script( 'mo_saml_admin_settings_script', plugins_url( 'includes/js/settings.min.js', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, false );
			wp_enqueue_script( 'mo_saml_admin_settings_phone_script', plugins_url( 'includes/js/phone.min.js', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, false );
		}
		wp_enqueue_script( 'mo_saml_notice_script', plugins_url( 'includes/js/notice.min.js', __FILE__ ), array(), Mo_Saml_Options_Plugin_Constants::VERSION, false );
	}

	/**
	 * This function is responsible for adding the SSO button on the WordPress login page.
	 *
	 * @return void
	 */
	public function mo_saml_modify_login_form() {
		$sso_button = get_option( Mo_Saml_Options_Enum_Sso_Login::SSO_BUTTON );
		if ( 'false' !== $sso_button && Mo_SAML_Utilities::mo_saml_is_sp_configured() ) {
			$this->mo_saml_add_sso_button();
		}
	}

	/**
	 * Renders the SSO button for the configured IDP.
	 *
	 * @return void
	 */
	public function mo_saml_add_sso_button() {
		if ( ! is_user_logged_in() ) {
			$saml_idp_name      = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_NAME );
			$custom_button_text = isset( $saml_idp_name ) ? 'Login with ' . $saml_idp_name : 'Login with SSO';
			echo '
                <script>
                window.onload = function() {
	                var target_btn = document.getElementById("mo_saml_button");
	                var before_element = document.querySelector("#loginform p");
	                before_element.before(target_btn);
                };                  
                    function loginWithSSOButton(id) {
                        if( id === "mo_saml_login_sso_button")
                            document.getElementById("saml_user_login_input").value = "saml_user_login";
                        document.getElementById("loginform").submit();
                    }
				</script>
                <input id="saml_user_login_input" type="hidden" name="option" value="">
                <div id="mo_saml_button" style="height:88px;">
                	<div id="mo_saml_login_sso_button" onclick="loginWithSSOButton(this.id)" style="width:100%;display:flex;justify-content:center;align-items:center;font-size:14px;margin-bottom:1.3rem" class="button button-primary">
                    <img style="width:20px;height:15px;padding-right:1px" src="' . esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ) . 'images/lock-icon.webp">' . esc_html( $custom_button_text ) . '
                	</div>
                	<div style="padding:5px;font-size:14px;height:20px;text-align:center"><b>OR</b></div>
            	</div>';
		}
	}

	/**
	 * Takes care of displaying IDP specific solutions and add-ons in the admin notice bar.
	 *
	 * @return void
	 */
	public function mo_saml_idp_notice() {
		$mo_date_current_notice = gmdate( 'Y-m-d' );

		if ( isset( $_POST['mo_idp_close_notice'] ) && check_admin_referer( 'mo_idp_close_notice' ) ) {
			$mo_date_expire_notice = gmdate( 'Y-m-d', strtotime( $mo_date_current_notice . '+7 day' ) );
			update_option( Mo_Saml_Sso_Constants::MO_SAML_EXPIRE_NOTICE, $mo_date_expire_notice );
			update_option( Mo_Saml_Sso_Constants::MO_SAML_CLOSE_NOTICE, 1 );
		}
		$mo_saml_identity_provider_identifier_name = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME );?>
		<input type="hidden" name="mo_saml_identity_provider_identifier" id="mo_saml_identity_provider_identifier" value="<?php echo esc_attr( $mo_saml_identity_provider_identifier_name ); ?>" />
		<input type="hidden" name="idp_specific" id="idp_specific" value='<?php echo esc_attr( wp_json_encode( Mo_Saml_Options_Plugin_Idp::$idp_list ) ); ?>' />
		<?php
		$display = 'none';
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking tab name, doesn't require nonce verification.
		if ( ! empty( Mo_Saml_Options_Plugin_Idp::$idp_list[ $mo_saml_identity_provider_identifier_name ] ) && ( ! isset( $_GET['tab'] ) || 'addons' !== $_GET['tab'] ) ) {
			$display = 'block';
		}
		if ( $mo_date_current_notice < get_option( Mo_Saml_Sso_Constants::MO_SAML_EXPIRE_NOTICE ) && ! empty( get_option( Mo_Saml_Sso_Constants::MO_SAML_CLOSE_NOTICE ) ) && get_option( Mo_Saml_Sso_Constants::MO_SAML_CLOSE_NOTICE ) ) {
			$display = 'none';
		}
		if ( current_user_can( 'manage_options' ) ) {
			mo_saml_display_plugin_notice( $display );
		}
	}

	/**
	 * Adds the menu and submenu for the miniOrange SAML SSO plugin.
	 *
	 * @return void
	 */
	public function miniorange_sso_menu() {
		$slug = 'mo_saml_settings';
		add_menu_page(
			'MO SAML Settings ' . __( 'Configure SAML Identity Provider for SSO', 'miniorange-saml-20-single-sign-on' ),
			'miniOrange SAML 2.0 SSO',
			'administrator',
			$slug,
			array(
				$this,
				'mo_login_widget_saml_options',
			),
			Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/miniorange.webp'
		);
		add_submenu_page(
			$slug,
			'miniOrange SAML 2.0 SSO',
			__( 'Plugin Configuration', 'miniorange-saml-20-single-sign-on' ),
			'manage_options',
			'mo_saml_settings',
			array( $this, 'mo_login_widget_saml_options' )
		);
		add_submenu_page(
			$slug,
			'miniOrange SAML 2.0 SSO',
			'<div style="color:orange" id="mo_saml_pricing_menu"><img src="' . Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/premium_plans_icon.webp" style="height:10px;width:12px"> ' . __( 'Premium Plans', 'miniorange-saml-20-single-sign-on' ) . '</div>',
			'manage_options',
			'mo_saml_settings',
			array( $this, 'mo_login_widget_saml_options' )
		);
		add_submenu_page(
			$slug,
			'miniOrange SAML 2.0 SSO',
			'<div id="mo_saml_addons_submenu">' . __( 'Add-Ons', 'miniorange-saml-20-single-sign-on' ) . '</div>',
			'manage_options',
			'mo_saml_settings&tab=addons',
			array( $this, 'mo_login_widget_saml_options' )
		);
		add_submenu_page(
			$slug,
			'miniOrange SAML 2.0 SSO',
			'<div id="mo_saml_troubleshoot">' . __( 'Troubleshoot', 'miniorange-saml-20-single-sign-on' ) . '</div>',
			'manage_options',
			'mo_saml_enable_debug_logs',
			array( 'Mo_SAML_Logger', 'mo_saml_log_page' )
		);
	}

	/**
	 * Handles redirection of a logged in user.
	 *
	 * @return void
	 */
	public function mo_saml_authenticate() {
		$redirect_to = '';
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the redirect_to parameter, doesn't require nonce verification.
		if ( isset( $_GET['redirect_to'] ) ) {
			//phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameter from the URL for checking the redirect_to parameter, doesn't require nonce verification.
			$redirect_to = esc_url_raw( wp_unslash( $_GET['redirect_to'] ) );
		}

		if ( is_user_logged_in() ) {
			$this->mo_saml_login_redirect( $redirect_to );
		}
	}

	/**
	 * Redirects the user to the redirect_to parameter after SSO.
	 *
	 * @param string $redirect_to The redirect_to query parameter from the URL.
	 * @return void
	 */
	public function mo_saml_login_redirect( $redirect_to ) {
		$is_admin_url = false;

		if ( strcmp( admin_url(), $redirect_to ) === 0 || strcmp( wp_login_url(), $redirect_to ) === 0 ) {
			$is_admin_url = true;
		}

		if ( ! empty( $redirect_to ) && ! $is_admin_url ) {
			wp_safe_redirect( $redirect_to );
		} else {
			wp_safe_redirect( site_url() );
		}
		exit();
	}

	/**
	 * Provides additional links for Settings and Premium Plans for the plugin listed under the Installed Plugins section.
	 *
	 * @param array $links The default links provided by WordPress for Settings and Deactivate.
	 * @return array
	 */
	public function mo_saml_plugin_action_links( $links ) : array {

		$settings_link = array( '<a href="' . esc_url( admin_url( 'admin.php?page=mo_saml_settings' ) ) . '">' . __( 'Settings', 'miniorange-saml-20-single-sign-on' ) . '</a>' );
		$license_link  = '<a href="' . Mo_Saml_External_Links::PRICING_PAGE . '" target="_blank">' . esc_html__( 'Premium Plans', 'miniorange-saml-20-single-sign-on' ) . '</a>';

		$links = array_merge( $settings_link, $links );

		array_push(
			$links,
			$license_link
		);
		return $links;
	}
}
new Saml_Mo_Login();
