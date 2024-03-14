<?php
/**
 * Class that manage admin section of the plugin.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Admin;

use SurferSEO\Surferseo;
use SurferSEO\Forms\Surfer_Form_Config_Ci;

/**
 * Controller to store admin part of WPSurfer
 */
class Surfer_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'admin_init', array( $this, 'init_filters' ) );
		add_action( 'admin_init', array( $this, 'download_debug_data' ) );

		add_action( 'admin_notices', array( $this, 'check_wordfence_application_password_protection' ) );

		add_action( 'admin_init', array( $this, 'do_admin_redirects' ) );
		add_action( 'admin_menu', array( $this, 'create_wizard_page' ) );
	}

	/**
	 * Admin init to call filters.
	 *
	 * @return void
	 */
	public function init_filters() {
		add_filter( 'views_users', array( $this, 'remove_surfer_api_role_from_users_lists' ) );
	}

	/**
	 * Register admin menu.
	 */
	public function register_settings_page() {
		add_menu_page(
			'Surfer',
			'Surfer',
			'manage_options',
			'surfer',
			array( $this, 'settings_page' ),
			'data:image/svg+xml;base64,' . base64_encode( file_get_contents( Surferseo::get_instance()->get_basedir() . '/assets/images/admin_menu_logo.svg' ) ) // @codingStandardsIgnoreLine
		);

		$gsc_is_connected = Surfer()->get_surfer()->get_gsc()->check_if_gsc_connected();

		if ( $gsc_is_connected ) {
			add_submenu_page( 'surfer', __( 'Performance Report', 'surferseo' ), __( 'Performance Report', 'surferseo' ), 'manage_options', 'surfer-performance-report', array( $this, 'performance_report_page' ) );
		}
	}

	/**
	 * Surfer wp-admin general settings page.
	 */
	public function settings_page() {
		$success = false;
		$error   = false;

		$tab = 'content-importer';

		$form = $this->choose_form_for_tab( $tab );
		$form->bind( Surferseo::get_instance()->get_surfer_settings()->get_options( $tab ) );

		if ( isset( $_POST['_surfer_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_surfer_nonce'] ) ), 'surfer_settings_save' ) ) {
			$form_is_valid = $form->validate( $_POST );
			if ( $form_is_valid ) {
				$form->bind( $_POST );
				$form->save( $tab );

				$success = true;
			} else {
				$error = true;
			}
		}

		require_once Surferseo::get_instance()->get_basedir() . '/templates/admin/settings.php';
	}

	/**
	 * Renders page for performance Report.
	 */
	public function performance_report_page() {

		Surfer()->get_surfer()->enqueue_surfer_react_apps();

		require_once Surferseo::get_instance()->get_basedir() . '/templates/admin/performance-report.php';
	}

	/**
	 * Returns proper form for selected tab.
	 *
	 * @param strign $tab - tab that is currenly open.
	 * @return mixed
	 */
	private function choose_form_for_tab( $tab ) {
		if ( 'content-importer' === $tab ) {
			return new Surfer_Form_Config_Ci();
		}

		return false;
	}

	/**
	 * Enqueue all scripts needed by plugin in wp-admin.
	 */
	public function admin_enqueue_scripts() {
		$connected        = Surfer()->get_surfer()->is_surfer_connected();
		$tracking_enabled = Surfer()->get_surfer_tracking()->is_tracking_allowed();

		wp_enqueue_script( 'surfer_connection', Surferseo::get_instance()->get_baseurl() . 'assets/js/surfer-connector.js', array( 'jquery' ), SURFER_VERSION, true );
		wp_localize_script(
			'surfer_connection',
			'surfer_connection_lang',
			array(
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'popup_block_error' => __( 'Pelease allow popup, to connect with Surfer', 'surferseo' ),
				'_surfer_nonce'     => wp_create_nonce( 'surfer-ajax-nonce' ),
				'connected'         => $connected,
			)
		);

		wp_enqueue_script( 'surfer_gsc_checker', Surferseo::get_instance()->get_baseurl() . 'assets/js/surfer-gsc-checker.js', array( 'jquery' ), SURFER_VERSION, true );
		wp_localize_script(
			'surfer_gsc_checker',
			'surfer_lang',
			array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'_surfer_nonce' => wp_create_nonce( 'surfer-ajax-nonce' ),
			)
		);

		wp_enqueue_script( 'surfer_analytics', Surferseo::get_instance()->get_baseurl() . 'assets/js/surfer-analytics.js', array( 'jquery' ), SURFER_VERSION, true );
		wp_localize_script(
			'surfer_analytics',
			'surfer_analytics_lang',
			array(
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'_surfer_nonce'    => wp_create_nonce( 'surfer-ajax-nonce' ),
				'tracking_enabled' => $tracking_enabled,
			)
		);

		// wp_enqueue_script( 'surfer_iframe', 'http://local.surferseo.com:4000/static/surfer_guidelines_1_x_x.js', array(), '1.3.1', true );
	}

	/**
	 * Hides role Surfer API in wp-admin -> Users list.
	 *
	 * @param array $views - list of views, in case of users list, list of roles.
	 * @return array
	 */
	public function remove_surfer_api_role_from_users_lists( $views ) {
		if ( ! isset( $views['surfer_api'] ) ) {
			return $views;
		}

		if ( isset( $views['all'] ) ) {
			$surfer_api_orig_s = $this->extract_view_quantity( $views['surfer_api'] );
			$surfer_api_int    = $this->extract_int( $surfer_api_orig_s );

			$all_orig_s   = $this->extract_view_quantity( $views['all'] );
			$all_orig_int = $this->extract_int( $all_orig_s );

			$all_new_int = $all_orig_int - $surfer_api_int;
			$all_new_s   = number_format_i18n( $all_new_int );

			$views['all'] = str_replace( $all_orig_s, $all_new_s, $views['all'] );
		}

		unset( $views['surfer_api'] );
		return $views;
	}

	/**
	 * Extract number from string
	 *
	 * @param string $text - Text to extract from.
	 * @return int
	 */
	private function extract_view_quantity( $text ) {
		$match  = array();
		$result = preg_match( '#\((.*?)\)#', $text, $match );
		if ( $result ) {
			$quantity = $match[1];
		} else {
			$quantity = 0;
		}

		return $quantity;
	}

	/**
	 * Convert string to simple int.
	 *
	 * @param string $str_val - string value.
	 * @return int
	 */
	private function extract_int( $str_val ) {
		$str_val1 = str_replace( ',', '', $str_val );
		$int_val  = (int) preg_replace( '/[^\-\d]*(\-?\d*).*/', '$1', $str_val1 );

		return $int_val;
	}

	/**
	 * Check if WordFence Application Password Protection is enabled.
	 *
	 * IF Disable WordPress application passwords in WordFence Brute Force Protection
	 * is enabled, REST API is not working. So it have to be disabled (unchecked).
	 *
	 * @return void
	 */
	public function check_wordfence_application_password_protection() {
		if ( ! is_plugin_active( 'wordfence/wordfence.php' ) ) {
			return;
		}

		if ( ! class_exists( 'wfConfig' ) ) {
			return;
		}

		if ( 1 === intval( \wfConfig::get( 'loginSec_disableApplicationPasswords' ) ) ) { // @codingStandardsIgnoreLine
			$class       = 'notice notice-error';
			$disable_url = admin_url( 'admin.php?page=WordfenceWAF&subpage=waf_options#wf-option-loginSec-disableApplicationPasswords-label' );

			/* translators: %s - URL to the option that should be disabled */
			$message = sprintf( __( '<b>WordFence is blocking Surfer!</b> <br/>WordFence option "Disable WordPress application passwords" is enabled. This option blocks Surfer API and you will be not able to use it. <a href="%s">Please disable this option</a>.', 'surferseo' ), $disable_url );

			$allowed_html = array(
				'b'  => array(),
				'br' => array(),
				'a'  => array( 'href' => array() ),
			);

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses( $message, $allowed_html ) );
		}
	}

	/**
	 * Handle redirects to setup/welcome page after install and updates.
	 *
	 * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
	 *
	 * @return void
	 */
	public function do_admin_redirects() {

		// Setup wizard redirect. False, because temporarly we want to disable this.
		if ( false && get_transient( '_surfer_activation_redirect' ) ) {
			$do_redirect        = true;
			$current_page       = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification
			$is_onboarding_path = ! isset( $_GET['path'] ) || '/setup-surfer-wizard' === sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

			// On these pages, or during these events, postpone the redirect.
			if ( wp_doing_ajax() || is_network_admin() || ! current_user_can( 'activate_plugins' ) ) {
				$do_redirect = false;
			}

			// On these pages, or during these events, disable the redirect.
			if (
				( 'surfer' === $current_page && $is_onboarding_path ) ||
				isset( $_GET['activate-multi'] ) // phpcs:ignore WordPress.Security.NonceVerification
			) {
				delete_transient( '_surfer_activation_redirect' );
				$do_redirect = false;
			}

			if ( $do_redirect ) {
				delete_transient( '_surfer_activation_redirect' );
				wp_safe_redirect( admin_url( 'admin.php?page=setup-surfer-wizard' ) );
				exit;
			}
		}
	}


	/**
	 * Register Setup Wizard page
	 *
	 * @return void
	 */
	public function create_wizard_page() {
		add_submenu_page(
			null,
			__( 'Surfer Setup Wizard', 'surferseo' ),
			__( 'Surfer Setup Wizard', 'surferseo' ),
			'manage_options',
			'setup-surfer-wizard',
			array( $this, 'wizard_page' ),
		);
	}

	/**
	 * Render Setup Wizard
	 *
	 * @return void
	 */
	public function wizard_page() {
		require_once Surfer()->get_basedir() . '/templates/admin/wizard.php';
	}

	/**
	 * Page to download debug data in form of a txt file.
	 */
	public function download_debug_data() {
		if ( ! isset( $_GET['page'] ) || 'surfer' !== sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		if ( ! isset( $_GET['action'] ) || 'download_debug_data' !== sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$debug_data = $this->get_debug_data();

		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="surfer_debug_data.txt"' );
		header( 'Content-Length: ' . strlen( $debug_data ) );
		header( 'Connection: close' );

		echo $debug_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		exit;
	}

	/**
	 * Prepeare debug data.
	 *
	 * @return string
	 */
	private function get_debug_data() {
		$interval = intval( Surferseo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'surfer_gsc_data_collection_interval', 7 ) );

		$content  = gmdate( 'd-m-Y H:i:s' ) . PHP_EOL . PHP_EOL;
		$content .= 'HOME URL: ' . home_url() . PHP_EOL . PHP_EOL;
		$content .= 'SITE URL: ' . site_url() . PHP_EOL . PHP_EOL;
		$content .= 'SURFER API KEY: ' . get_option( 'wpsurfer_api_access_key', false ) . PHP_EOL . PHP_EOL;
		$content .= 'SURFER ORGANIZATION: ' . print_r( get_option( 'surfer_connection_details', null ), true ) . PHP_EOL . PHP_EOL;
		$content .= 'PERMALINK STRUCTURE: ' . get_option( 'permalink_structure', false ) . PHP_EOL . PHP_EOL;
		$content .= 'GSC DATA INTERVAL: ' . $interval . PHP_EOL . PHP_EOL;
		$content .= 'LAST GSC DATA GATHERING: ' . get_option( 'surfer_last_gsc_data_update', false ) . PHP_EOL . PHP_EOL;
		$content .= 'NEXT GSC DATA GATHERING: ' . wp_next_scheduled( 'surfer_gather_drop_monitor_data' ) . PHP_EOL . PHP_EOL;
		$content .= 'E-MAIL NOTIFICATION ENABLED: ' . Surfer()->get_surfer()->get_gsc()->performance_report_email_notification_endabled() . PHP_EOL . PHP_EOL;
		$content .= 'E-MAIL SENT IN LAST 7 days: ' . get_transient( 'surfer_gsc_weekly_report_email_sent' ) . PHP_EOL . PHP_EOL;
		$content .= 'SURFER VERSION OPTION: ' . get_option( 'surfer_version', false ) . PHP_EOL . PHP_EOL;
		$content .= 'SURFER VERSION NOW: ' . SURFER_VERSION . PHP_EOL . PHP_EOL;
		$content .= 'PHP VERSION: ' . phpversion() . PHP_EOL . PHP_EOL;
		$content .= 'WordPress VERSION: ' . get_bloginfo( 'version' ) . PHP_EOL . PHP_EOL;
		$content .= 'ACTIVE PLUGINS: ' . print_r( Surfer()->get_surfer_tracking()->get_active_plugins(), true ) . PHP_EOL . PHP_EOL;

		return $content;
	}
}
