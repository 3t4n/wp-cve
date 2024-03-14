<?php

/**
 * This is the base class for the MemberSpace WordPress plugin.
 *
 * This class handles all the plugin's actions and filters for both public and admin pages.
 *
 */
require_once plugin_dir_path( __FILE__ ) . 'memberspace-api.php';
require_once plugin_dir_path( __FILE__ ) . 'memberspace-rule.php';
require_once plugin_dir_path( __FILE__ ) . 'memberspace-validator.php';

class MemberSpace {

	const ADMIN_URI         = 'https://admin.memberspace.com';
	const API_BASE_URI      = 'https://app.memberspace.com/api/v2';
	const PLUGIN_NAME       = 'MemberSpace';
	const SIGNUP_URI        = 'https://www.memberspace.com/signup';
	const SUPPORT_EMAIL     = 'support@memberspace.com';
	const SUPPORT_URI       = 'https://help.memberspace.com/category/189-wordpress-guides';
	const VALID_TABS        = array( 'acccount', 'configuration', 'pages', 'support' );
	const WIDGET_ASSET_URI  = 'https://cdn.memberspace.com';

	public function run() {
		// Register the field to store our subdomain
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Automatically refresh site config
		add_action( 'admin_init', array( $this, 'auto_refresh_site_config' ) );

		add_action( 'admin_init', array( $this, 'handle_registration_complete_callback' ));

		// Add a Settings admin page under Settings -> MemberSpace
		add_action( 'admin_menu', array( $this, 'register_plugin_settings_page' ) );

		// Load the translations from translate.wordpress.org
		add_action( 'plugins_loaded', array( $this, 'load_translations' ) );

		// Handle the site config refresh button press
		add_action( 'admin_post_manual_refresh_site_config', array( $this, 'manual_refresh_site_config' ) );

		// Show activation banner unless dismissed
		add_action( 'admin_notices', array( $this, 'render_activation_banner' ) );

		// Show status notification messages (Currently used for manual sync status)
		add_action( 'admin_notices', array( $this, 'render_notification_bar' ) );

		// Add custom CSS for MemberSpace admin settings page
		add_action( 'admin_enqueue_scripts', array( $this, 'inject_admin_scripts' ) );

		// Load new site config when updating subdomain
		add_action( 'update_option_memberspace_subdomain', array( $this, 'handle_change_site_subdomain' ) );

		// Inject extra security code for protected pages (body tag)
		add_action( 'wp_body_open', array( $this, 'inject_extra_security_body' ) );

		// Inject extra security code for protected pages (head tag)
		add_action( 'wp_head', array( $this, 'inject_extra_security_head' ) );

		// Include widget script in <head> tag on pages
		add_action( 'wp_head', array( $this, 'inject_widget_script' ) );

		// Add the 'Settings' link in the plugin listing in Plugins -> Installed Plugins
		add_filter( 'plugin_action_links_memberspace/memberspace.php', array( $this, 'add_action_links' ) );
	}

	public function add_action_links($links) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=memberspace' ) . '">' . _x('Settings', 'WP plugin list MS settings button', 'memberspace') . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function render_activation_banner() {
		if ( get_option( 'memberspace_display_banner' )  ) {
			include_once( plugin_dir_path( __DIR__ ) . 'admin/partials/activation-banner.php' );

			// Only show the message once
			update_option( 'memberspace_display_banner', false );
		}
	}

	public function render_notification_bar() {
		$notification_types = array( 'error', 'warning', 'success', 'info' );

		if (
			!isset( $_GET['notification_type'] ) ||
			!isset( $_GET['notification'] ) ||
			!in_array( $_GET['notification_type'], $notification_types, true )
		) return;

		$type = $_GET['notification_type'];
		$message = $_GET['notification'];

		if ( $type === 'success' ) {
			include_once( plugin_dir_path( __DIR__ ) . 'admin/partials/notification-bar.php' );
		}

		if ( $type === 'error' ) {
      include_once( plugin_dir_path( __DIR__ ) . 'admin/partials/notification-bar.php' );
		}
	}

	public function inject_admin_scripts( $hook ) {

		// Plugin list page
		if ( $hook === 'plugins.php') {
			wp_enqueue_style( 'memberspace-custom', plugin_dir_url( __DIR__ ) . 'admin/css/plugin-list-screen.css', array( ), MEMBERSPACE_PLUGIN_BUILD_ID );
		}

		// Plugin settings page
		if ( $hook === 'settings_page_memberspace') {
			wp_enqueue_style( 'memberspace-custom', plugin_dir_url( __DIR__ ) . 'admin/css/custom.css', array( ), MEMBERSPACE_PLUGIN_BUILD_ID );
			wp_enqueue_script( 'memberspace-custom', plugin_dir_url( __DIR__ ) . 'admin/js/custom.js', array( 'jquery' ), MEMBERSPACE_PLUGIN_BUILD_ID );
		}
	}

	public function inject_extra_security_body( $hook ) {
		if ( $this->skip_scripts() ) return;

		if ( $this->is_page_protected() ) {
			include_once( plugin_dir_path( __DIR__ ) . 'public/extra-security-body.php' );
		}
	}

	public function inject_extra_security_head( $hook ) {
		if ( $this->skip_scripts() ) return;

		if ( $this->is_page_protected() ) {
			include_once( plugin_dir_path( __DIR__ ) . 'public/extra-security-head.php' );
		}
	}

	public function inject_widget_script() {
		if ( $this->skip_scripts() ) return;

		include_once( plugin_dir_path( __DIR__ ) . 'public/widget.js.php' );
	}

	public function refresh_site_config() {
		$retval = MemberSpace_Api::get_site_config();
		$body = $retval['response_body'];
		$code = $retval['response_code'];

		if ( $code == 200 ) {
      $rules = $this->sanitized_rules( $body->protectedPages );
			$public_key = sanitize_text_field( $body->pubKeys->v1->key );
			$site_contact = sanitize_email( $body->contactEmail );
			$site_ID = sanitize_text_field( $body->siteId );
			$subdomain = sanitize_text_field( $body->subdomain );

			update_option( 'memberspace_rules', $rules );
			update_option( 'memberspace_public_key', $public_key );
			update_option( 'memberspace_site_contact', $site_contact );
			update_option( 'memberspace_site_ID', $site_ID );
			update_option( 'memberspace_subdomain', $subdomain );
			update_option( 'memberspace_last_updated', time() );
			update_option( 'memberspace_last_sync_successful', true );

			return array( 'success' => true );
		}

		// TODO: no subdomain, bad subdomain, bad connection
		// error_log( 'Response: ' . print_r( $response, true ) );
		update_option( 'memberspace_last_sync_successful', false );
		error_log( 'Body: ' . print_r( $retval, true ) );
		return array(
			'success' => false,
			'error' => ($body->error ?: "Err-{$code}")
		);
	}

	public function register_plugin_settings_page() {
		add_options_page(
			$this::PLUGIN_NAME,
			$this::PLUGIN_NAME,
			'manage_options',
			'memberspace',
			array( $this, 'memberspace_admin_page' )
		);
	 }

	public function load_translations() {
		load_plugin_textdomain( 'memberspace', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function memberspace_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) return;

		$tab = $this->current_tab();

		include_once( plugin_dir_path( __DIR__ ) . 'admin/partials/settings.php' );
	}

	public function register_settings() {
		// This is to auto handle fields in the settings form
		register_setting( 'memberspace_settings', 'memberspace_extra_security' );
	}

	public function handle_registration_complete_callback() {
		if( !isset($_GET['ms-registration-complete']) || $_GET['ms-registration-complete'] !== 'true' ) return;

		$result = $this->refresh_site_config();
		if ($result['success']) {
			wp_redirect( add_query_arg( array(
				'page' => 'memberspace',
				'notification_type' => 'success',
				'notification' => urlencode( _x('The MemberSpace plugin is now connected!', 'MS backend new site registration success callback banner', 'memberspace') )
			), admin_url( "admin.php" ) ) );
		} else {
			wp_redirect( add_query_arg( array(
				'page' => 'memberspace',
				'notification_type' => 'error',
				'notification' => urlencode( $result['error'] )
			), admin_url( "admin.php" ) ) );
		}
	}

	public function manual_refresh_site_config() {
		$result = $this->refresh_site_config();
		if ($result['success']) {
			wp_redirect( add_query_arg( array(
				'page' => 'memberspace',
				'notification_type' => 'success',
				'notification' => urlencode( _x('Site configuration data updated!', 'Manual sync success banner', 'memberspace') )
			), admin_url( "admin.php" ) ) );
		} else {
			wp_redirect( add_query_arg( array(
				'page' => 'memberspace',
				'notification_type' => 'error',
				'notification' => urlencode( $result['error'] )
			), admin_url( "admin.php" ) ) );
		}
	}

	public function handle_change_site_subdomain() {
		// Clear options that are for the previous site config
		$options = array(
  		'memberspace_last_updated',
  		'memberspace_public_key',
  		'memberspace_rules',
  		'memberspace_site_contact',
  		'memberspace_site_ID',
  		'memberspace_last_sync_successful'
		);

		foreach ( $options as $option ) {
  		delete_option( $option );
		}

		$this->refresh_site_config();
	}

	public function auto_refresh_site_config() {
		// Has it been more than 1 minute since the last sync?
		$last_updated = get_option( 'memberspace_last_updated' );
		if( $last_updated && (time() - $last_updated ) <= 60 ) return;

		$this->refresh_site_config();
	}

	public function class_for_tab( $tab ) {
		$urlTab = $this->current_tab();
		return ( $tab == $urlTab ) ? 'active' : '';
	}

	public function memberspace_backend_site_url() {
		$siteSubdomain = get_option( 'memberspace_subdomain' );
		return $siteSubdomain ? MemberSpace::ADMIN_URI . "/sites/{$siteSubdomain}" : MemberSpace::ADMIN_URI;
	}

	private function current_tab() {
		$tab = 'account';

		if ( isset( $_GET['tab'] ) ) {
			$sanitized_tab = sanitize_text_field( $_GET['tab'] );

			if ( in_array( $sanitized_tab, MemberSpace::VALID_TABS ) ) {
				$tab = $sanitized_tab;
			}
		}

		return $tab;
	}

	private function is_page_protected() {
		if ( is_admin() ) return false;
		if ( current_user_can( 'edit_others_pages' ) ) return false;

		$add_extra_security = get_option( 'memberspace_extra_security' );
		$validator = new MemberSpace_Validator();

		return ( $validator->is_current_path_protected() && $add_extra_security );
	}

	private function sanitized_rules( $rules ) {
		$sanitized_rules = array();

		foreach( $rules as $rule ) {
			$sanitized_rule = new MemberSpace_Rule();
			$sanitized_rule->id = sanitize_text_field( $rule->id );
			$sanitized_rule->path = sanitize_text_field( $rule->path );
			$sanitized_rules[] = $sanitized_rule;
		}

		return $sanitized_rules;
	}

	private function skip_scripts() {
		// Don't load for admin, feed, robots or trackback pages.  We are also skipping
		if ( is_admin() || is_feed() || is_robots() || is_trackback()) {
			return true;
		};

		return false;

		// if you're logged in as a WordPress user who can edit pages for convenience.
		// current_user_can( 'edit_others_pages' )
	}
}
