<?php
/**
 * Holds the SocialFlow Admin settings class
 *
 * @package SocialFlow
 */

/**
 *  SocialFlow_Admin.
 */
class SocialFlow_Admin {

	/**
	 * PHP5 constructor
	 *
	 * @since 2.0
	 * @access public
	 */
	public function __construct() {
		// Register setting and maybe perform authorization.
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Load admin menu classes.
		$this->admin_menu();

		// Add notices.
		add_action( 'admin_notices', array( $this, 'initial_nag' ) );

		// Include scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'load_settings_page' ) );

		// Add footer text.
		add_filter( 'admin_footer_text', array( $this, 'add_footer_text' ) );

		register_activation_hook( SF_ABSPATH . '/socialflow.php', array( 'SocialFlow_Admin', 'install' ) );
		register_uninstall_hook( SF_ABSPATH . '/socialflow.php', array( 'SocialFlow_Admin', 'uninstall' ) );
	}

	/**
	 * Registers SocialFlow settings
	 *
	 * This is used because register_setting() isn't available until the "admin_init" hook.
	 *
	 * @since 2.0
	 * @access public
	 */
	public function admin_init() {
		// Maybe connect with SocialFlow.
		$this->maybe_authorize();

		// Register our settings in the global "whitelist_settings".
		register_setting( 'socialflow', 'socialflow', array( $this, 'save_settings' ) );
	}

	/**
	 * Adds "Social Flow" to the WordPress "Settings" menu
	 *
	 * @since 2.0
	 * @access public
	 */
	public function admin_menu() {
		// Load abstract page class.
		require_once SF_ABSPATH . '/includes/settings/class-socialflow-admin-settings-page.php';
		// Load page classes.
		require_once SF_ABSPATH . '/includes/settings/class-socialflow-admin-settings-general.php';
		require_once SF_ABSPATH . '/includes/settings/class-socialflow-admin-settings-accounts.php';
		require_once SF_ABSPATH . '/includes/settings/class-socialflow-admin-settings-messages.php';
		require_once SF_ABSPATH . '/includes/settings/class-socialflow-admin-settings-categories.php';

		// Init menu classes.
		new SocialFlow_Admin_Settings_General();
		new SocialFlow_Admin_Settings_Accounts();
		// new SocialFlow_Admin_Settings_Categories.
		// new SocialFlow_Admin_Settings_Messages.
	}

	/**
	 * Outputs message to admin to visit settings page after initial plugin activation
	 *
	 * @since 2.0
	 * @access public
	 */
	public function initial_nag() {
		global $socialflow;
		$socialflow_params = filter_input_array( INPUT_GET );

		if ( ! $socialflow->is_authorized() && current_user_can( 'manage_options' ) && ! ( isset( $socialflow_params['page'] ) && 'socialflow' === $socialflow_params['page'] ) ) {
			$socialflow->render_view( 'notice/not-authorized' );
		}
	}

	/**
	 * Loads admin styles and scripts
	 *
	 * @since 2.0
	 * @access public
	 */
	public function load_settings_page() {
		global $pagenow, $socialflow;

		$allowed_page = array(
			'post.php',
			'post-new.php',
			'admin.php',
			'edit.php', // posts list.
			'options-general.php',
			'upload.php', // uploads list.
		);

		if ( in_array( $pagenow, $allowed_page, true ) ) {

			// Enqueue neccessary scripts.
			wp_enqueue_script( 'timepicker', plugins_url( 'assets/js/jquery.timepicker.js', SF_FILE ), array( 'jquery', 'jquery-ui-slider', 'jquery-ui-datepicker' ), true );
			wp_enqueue_script( 'jquery.maxlength', plugins_url( 'assets/js/jquery.maxlength.js', SF_FILE ), array( 'jquery' ), '1.0.5', true );

			wp_enqueue_script( 'socialflow-categories', plugins_url( 'assets/js/sf-categories.js', SF_FILE ), array( 'jquery' ), '2.0', true );
			wp_enqueue_script( 'twitter-text', plugins_url( 'assets/js/twitter-text.js', SF_FILE ), array( 'jquery' ), '1.0', true );

			wp_localize_script(
				'socialflow-admin', 'socialFlowData', array(
					'homeUrl' => home_url(),
				)
			);

			if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php', 'upload.php' ), true ) ) {
				wp_enqueue_script( 'socialflow-common', plugins_url( 'assets/js/angular/common.js', SF_FILE ), array( 'jquery' ), SF_VERSION, true );
				wp_enqueue_script( 'angular', plugins_url( 'assets/js/angular/lib/angular.js', SF_FILE ), array(), '1.0', true );
				wp_enqueue_script( 'socialflow-angular', plugins_url( 'assets/js/angular/module.js', SF_FILE ), array( 'angular' ), SF_VERSION, true );
				wp_localize_script( 'socialflow-angular', 'sfPostFormTmpls', SocialFlow_Post_Form_Data::get_templates() );
			}

			// Enqeue styles.
			wp_enqueue_style( 'socialflow-admin', plugins_url( 'assets/css/styles.min.css', SF_FILE ) );
			wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css', false, '1.8.1', false );

			wp_enqueue_media();

			// Thickbox scripts for compose now post action.
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

			if ( $socialflow->is_localhost() ) {
				wp_enqueue_script( 'live-reload', '//localhost:35729/livereload.js', false, '1', false );
			}
		}
	}

	/**
	 * Maybe connect to sociaflow
	 *
	 * @since 2.0
	 * @access public
	 */
	public function maybe_authorize() {
		global $socialflow;
		$socialflow_params = filter_input_array( INPUT_GET );
		// Validate token.
		if ( isset( $socialflow_params['oauth_token'] ) && $socialflow_params['oauth_token'] === $socialflow->options->get( 'oauth_token' ) ) {

			$api = $socialflow->get_api( $socialflow->options->get( 'oauth_token' ), $socialflow->options->get( 'oauth_token_secret' ) );

			// Store access tokens.
			$socialflow->options->set( 'access_token', $api->get_access_token( $socialflow_params['oauth_verifier'] ) );

			// Unset temporary token and secret.
			$socialflow->options->delete( 'oauth_token' );
			$socialflow->options->delete( 'oauth_token_secret' );

			// Get list of all user account and enable sf by default for each account.
			$accounts = $api->get_account_list();

			if ( is_wp_error( $accounts ) ) {
				wp_safe_redirect( add_query_arg( 'page', 'socialflow', admin_url( 'admin.php' ) ) );
				exit;
			}

			// Enable all publishing accounts by default.
			$enabled = array();
			foreach ( $accounts as $key => $account ) {
				if ( 'publishing' === $account['service_type'] ) {
					$enabled[] = $key;
				}
			}

			// Store all user accounts.
			$socialflow->options->set( 'accounts', $accounts );

			// Remove initial nag.
			$socialflow->options->set( 'initial_nag', 0 );

			// Set send to and visible accounts.
			$socialflow->options->set( 'show', $enabled );
			$socialflow->options->set( 'send', $enabled );

			// Save update options.
			$socialflow->options->save();

			wp_safe_redirect( add_query_arg( 'page', 'socialflow', admin_url( 'admin.php' ) ) );
			exit;

		} elseif ( isset( $socialflow_params['sf_unauthorize'] ) && current_user_can( 'manage_options' ) ) {
			// To-Do Check temporary token.
			// Remove all options.
			delete_option( 'socialflow' );

			wp_safe_redirect( add_query_arg( 'page', 'socialflow', admin_url( 'admin.php' ) ) );
			exit;
		}
	}

	/**
	 * Sanitizes SocialFlow settings
	 *
	 * This is the callback for register_setting()
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string|array $settings Settings passed in from filter.
	 * @return string|array Sanitized settings
	 */
	public function save_settings( $settings = array() ) {
		global $socialflow;
		$socialflow_params = filter_input_array( INPUT_POST );
		$settings          = empty( $settings ) ? array() : $settings;

		// Merge current settings (we need to store account information which is not stored in the fields).
		$settings = $socialflow->array_merge_recursive( $socialflow->options->options, $settings );

		// Allow plugins/modules to add/modify settings when having post request.
		$settings = isset( $socialflow_params['socialflow'] ) ? apply_filters( 'sf_save_settings', $settings ) : $settings;

		return $settings;
	}

	/**
	 * Add footer text
	 *
	 * @since 2.5
	 * @access public
	 *
	 * @param string $text text.
	 * @return string
	 */
	public function add_footer_text( $text ) {
		if ( ! function_exists( 'vip_powered_wpcom' ) ) {
			return $text;
		}

		return '<i>' . vip_powered_wpcom() . ' | </i>' . $text;
	}

	/**
	 * Installs SocialFlow
	 *
	 * @since 2.0
	 * @access private
	 */
	public function install() {
		global $socialflow;

	}

	/**
	 * Uninstalls SocialFlow
	 *
	 * @since 2.0
	 * @access private
	 */
	public function uninstall() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		delete_option( 'socialflow' );
	}
}
