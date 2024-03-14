<?php
/** Cryptocurrency Payment Gateway Setup Wizard by CryptoWoo
 *
 * Inspired by WooCommerce Onboarding Setup Wizard class OnboardingSetupWizard.
 */

/** Contains backend logic for the Setup Wizard */
class CW_Setup_Wizard {

	/** Class instance.
	 *
	 * @var CW_Setup_Wizard instance
	 */
	private static $instance = null;

	/** Setup Wizard status option name. */
	private const SETUP_WIZARD_STATUS_OPTION = 'cryptowoo_setup_wizard_status';

	/** Setup Wizard redirect transient name. */
	private const SETUP_WIZARD_REDIRECT_TRANSIENT = '_cw_setup_wizard_redirect';

	/** Setup Wizard status option value for status skipped. */
	private const SETUP_WIZARD_STATUS_SKIPPED = 'skipped';

	/** Setup Wizard status option value for status completed. */
	private const SETUP_WIZARD_STATUS_COMPLETED = 'completed';

	/**
	 * Get class instance.
	 */
	final public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Add setup wizard actions.
	 */
	public function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_init', array( $this, 'maybe_do_admin_redirects' ) );
		add_action( 'current_screen', array( $this, 'maybe_redirect_to_setup_wizard' ) );
		add_action( 'admin_init', array( $this, 'maybe_display_setup_wizard' ) );
		add_action( 'admin_init', array( $this, 'maybe_process_submitted_setup_wizard' ) );
	}

	/**
	 * Test whether the context of execution comes from async action scheduler.
	 * Note: this is a polyfill for wc_is_running_from_async_action_scheduler()
	 *       which was introduced in WC 4.0.
	 *
	 * @return bool
	 */
	private function is_running_from_async_action_scheduler() {
		if ( function_exists( '\wc_is_running_from_async_action_scheduler' ) ) {
			return \wc_is_running_from_async_action_scheduler();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return isset( $_REQUEST['action'] ) && 'as_async_request_queue_runner' === $_REQUEST['action'];
	}

	/**
	 * Handle redirects to setup/welcome page after install and updates.
	 *
	 * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
	 */
	public function maybe_do_admin_redirects() {
		// Don't run this fn from Action Scheduler requests, as it would clear _cw_setup_wizard_redirect transient.
		// That means OBW would never be shown.
		if ( $this->is_running_from_async_action_scheduler() ) {
			return;
		}

		// Setup wizard redirect.
		if ( get_transient( self::SETUP_WIZARD_REDIRECT_TRANSIENT ) && apply_filters( 'cryptowoo_enable_setup_wizard', true ) ) {
			$do_redirect = true;

			// On these pages, or during these events, postpone the redirect.
			if ( wp_doing_ajax() || is_network_admin() || ! current_user_can( 'manage_woocommerce' ) ) {
				$do_redirect = false;
			}

			// On these pages, or during these events, disable the redirect.
			if (
				$this->is_setup_wizard_path() ||
				apply_filters( 'cryptowoo_prevent_automatic_wizard_redirect', false ) ||
				isset( $_GET['activate-multi'] ) // phpcs:ignore WordPress.Security.NonceVerification
			) {
				delete_transient( self::SETUP_WIZARD_REDIRECT_TRANSIENT );
				$do_redirect = false;
			}

			if ( $do_redirect ) {
				delete_transient( self::SETUP_WIZARD_REDIRECT_TRANSIENT );
				wp_safe_redirect( admin_url( 'admin.php?page=cryptowoo&path=/setup-wizard' ) );
				exit;
			}
		}
	}

	/** Handle redirects to setup/welcome page after install and updates.*/
	public function maybe_display_setup_wizard() {

		if ( ! $this->is_setup_wizard_path() || $this->is_setup_wizard_form_submitted() || ! $this->required_php_extensions_loaded() ) {
			return;
		}

		if ( is_null( get_current_screen() ) ) {
			set_current_screen();
		}

		wp_enqueue_style(
			'cw-setup-wizard',
			cw_get_asset_url( 'css/setup-wizard.css' ),
			array( 'dashicons', 'install' ),
			CWOO_VERSION
		);
		wp_register_script(
			'cw-setup-wizard',
			cw_get_asset_url( 'js/setup-wizard.js' ),
			array( 'jquery', 'media-editor', 'mce-view' ),
			CWOO_VERSION,
			false
		);

		// disable query monitor during wizard.
		add_filter( 'qm/dispatch/html', '__return_false' );

		ob_start();
		$this->print_setup_wizard_header();
		$this->setup_wizard_content();
		exit;
	}

	/** Handle saving or skipping/exiting setup wizard form when submitted */
	public function maybe_process_submitted_setup_wizard() {
		if ( ! $this->is_setup_wizard_form_submitted() ) {
			return;
		}

		if ( $this->is_setup_wizard_form_exited() ) {
			$this->set_status_skipped();
			wp_safe_redirect( admin_url( 'admin.php?page=cryptowoo' ) );
			exit;
		} else {
			$this->process_submitted_setup_wizard();
		}
	}

	/** Handle saving setup wizard form when submitted */
	private function process_submitted_setup_wizard() {
		$addresses    = array();
		$payment_apis = array();

		// Do nonce verification and get all the data from the submitted form.
		$nonce = isset( $_POST['nonce'] ) ? sanitize_key( $_POST['nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'cw_setup_wizard_form' ) ) {
			$this->validation_error_on_submit( 'Form verification failed.' );
		}

		$enabled_coins = isset( $_POST['coins'] ) ? array_map( 'sanitize_key', $_POST['coins'] ) : array();

		if ( empty( $enabled_coins ) ) {
			$this->validation_error_on_submit( 'No cryptocurrencies have been selected. You must select at least one.' );
		}

		// Get and validate addresses for the enabled coins.
		foreach ( $enabled_coins as $coin ) {
			$coin_ticker  = $this->coin_name_to_ticker( $coin );
			$element_id   = str_replace( '_', '-', "$coin-addresses" );
			$input        = isset( $_POST[ $element_id ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $element_id ] ) ) : '';
			$address_list = preg_split( '/\r\n|\r|\n/', $input, -1, PREG_SPLIT_NO_EMPTY );

			if ( empty( $address_list ) ) {
				$this->validation_error_on_submit( "No $coin addresses have been submitted" );
			}

			// Verify that addresses are valid.
			foreach ( $address_list as $address ) {
				$validate = new CW_Validate();

				if ( 'bitcoin_cash' === $coin ) {
					$address = str_replace( 'bitcoincash:', '', CW_Formatting::format_bch_address_as_cashaddr( $address ) );
				}

				if ( ! $validate->offline_validate_address( $address, $coin_ticker ) ) {
					$this->validation_error_on_submit( "$address is not a valid $coin address" );
				}

				$addresses[ $coin ][] = $address;
			}
		}

		// Generate an array of disabled coins.
		$disabled_coins = array();
		foreach ( $this->coin_names_to_ticker() as $coin_name => $ticker ) {
			if ( ! in_array( $coin_name, $enabled_coins, true ) ) {
				$disabled_coins[] = $coin_name;
			}
		}

		// Set default payment lookup selection (block explorer apis).
		foreach ( $enabled_coins as $coin ) {
			$payment_apis[ $coin ] = 'bitcore';
		}

		$this->save_setup_wizard_data_to_settings( $enabled_coins, $disabled_coins, $addresses, $payment_apis );

		$this->save_setup_wizard_success();
	}

	/** Handle saving setup wizard form data to settings.
	 *
	 * @param string[]   $enabled_coins  Enabled coins (eg. bitcoin).
	 * @param string[]   $disabled_coins Disabled coins (eg. bitcoin).
	 * @param string[][] $addresses      List of coin addresses.
	 * @param string[]   $payment_apis   List of preferred coin payment apis (block explorer apis).
	 */
	private function save_setup_wizard_data_to_settings( $enabled_coins, $disabled_coins, $addresses, $payment_apis ) {
		foreach ( $enabled_coins as $coin_name ) {
			$coin_ticker = $this->coin_name_to_ticker( $coin_name );

			$address_list = CW_AddressList::get_address_list( $coin_ticker );
			foreach ( $addresses[ $coin_name ] as $address ) {
				if ( ! isset( $address_list[ $address ] ) ) {
					// The address is used as index to avoid duplicated addresses and address reuse.
					// The value is the associated order number for an address, for unused addresses this is 0.
					$address_list[ $address ] = 0;
				}
			}
			$max_length   = apply_filters( 'cw_address_list_max', 20, $coin_ticker );
			$address_list = array_slice( $address_list, 0, $max_length );

			$processing_api_option_id = 'processing_api_' . strtolower( $coin_ticker );
			if ( 'disabled' === cw_get_option( $processing_api_option_id, 'disabled' ) ) {
				cw_update_option( $processing_api_option_id, $payment_apis[ $coin_name ] );
			}
			CW_AddressList::save_address_list( $coin_ticker, $address_list );
		}

		// To disable a coin, the processing api is set to 'disabled'.
		foreach ( $disabled_coins as $coin_name ) {
			$coin_ticker = $this->coin_name_to_ticker( $coin_name );
			cw_update_option( 'processing_api_' . strtolower( $coin_ticker ), 'disabled' );
		}
	}

	/** Handle success saving setup wizard */
	private function save_setup_wizard_success() {
		delete_transient( self::SETUP_WIZARD_REDIRECT_TRANSIENT );
		$this->set_status_completed();

		// TODO: Better success message!
		$redirection_url = esc_url_raw( admin_url( 'admin.php?page=cryptowoo' ) );
		echo esc_textarea( 'The Cryptocurrency Payment Gateway Setup Wizard was saved successfully.' );
		echo '<br><br>Redirecting you to the CryptoWoo Settings in 5 seconds...';
		echo '<br><br> <a href="' . $redirection_url . '">Click here if you are not redirected';
		header( 'refresh:5;url=' . $redirection_url );
		exit;
	}

	/** Convert coin name (eg. bitcoin) to ticker (eg. BTC).
	 *
	 * @param string $coin_name Coin name (eg bitcoin).
	 *
	 * @return string
	 */
	private function coin_name_to_ticker( $coin_name ) {
		return $this->coin_names_to_ticker()[ $coin_name ];
	}

	/** Convert coin ticker (eg. BTC) to coin name (eg. bitcoin).
	 *
	 * @param string $coin_ticker Coin ticker (eg BTC).
	 *
	 * @return string
	 */
	private function coin_ticker_to_name( $coin_ticker ) {
		return array_flip( $this->coin_names_to_ticker() )[ $coin_ticker ];
	}

	/** Convert coin tickers (eg. BTC) to coin names (eg. bitcoin).
	 *
	 * @return string[]
	 */
	private function coin_names_to_ticker() {
		return array(
			'bitcoin'      => 'BTC',
			'bitcoin_cash' => 'BCH',
			'litecoin'     => 'LTC',
			'dogecoin'     => 'DOGE',
		);
	}

	/** Validation error occurred during submit of Setup Wizard form. This function displays an error to the user.
	 *
	 * @param string $error_message The error message to display to the user.
	 *
	 * @return void
	 */
	private function validation_error_on_submit( $error_message ) {
		// TODO: Better Error!
		$redirection_url = esc_url_raw( admin_url( 'admin.php?page=cryptowoo&path=/setup-wizard' ) );
		echo esc_textarea( $error_message );
		echo '<br><br>Redirecting you to the Setup Wizard in 5 seconds...';
		echo '<br><br> <a href="' . $redirection_url . '">Click here if you are not redirected';
		header( 'refresh:5;url=' . $redirection_url );
		exit;
	}

	/** Redirect to the setup wizard on CryptoWoo Settings page if completion is needed. */
	public function maybe_redirect_to_setup_wizard() {
		if ( ! $this->is_settings_page() || ! $this->needs_completion() ) {
			return;
		}

		wp_safe_redirect( admin_url( 'admin.php?page=cryptowoo&path=/setup-wizard' ) );
		exit;
	}

	/** Check if the current page is the CryptoWoo Settings page.
	 *
	 * @return bool
	 */
	private function is_settings_page() {
		// phpcs:ignore WordPress.Security.NonceVerification
		return isset( $_GET['page'] ) && 'cryptowoo' === $_GET['page'] && ! isset( $_GET['path'] );
	}

	/** Check if the current page is the setup wizard.
	 *
	 * @return bool
	 */
	private function is_setup_wizard_path() {
		// phpcs:disable WordPress.Security.NonceVerification
		// phpcs:disable WordPress.Security.ValidatedSanitizedInput
		$current_page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		$current_path = isset( $_GET['path'] ) ? $_GET['path'] : '';
		// phpcs:enable

		return 'cryptowoo' === $current_page && '/setup-wizard' === $current_path;
	}

	/** Check if the current page is a submitted setup wizard form.
	 *
	 * @return bool
	 */
	private function is_setup_wizard_form_submitted() {
		if ( ! $this->is_setup_wizard_path() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '';

		return 'POST' === $request_method;
	}

	/** Check if the required extensions to run the setup wizard are loaded.
	 *
	 * @return bool
	 */
	private function required_php_extensions_loaded() {
		return extension_loaded( 'gmp' );
	}

	/** Check if the current page is an exited setup wizard form.
	 *
	 * @return bool
	 */
	private function is_setup_wizard_form_exited() {
		if ( ! $this->is_setup_wizard_path() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification
		return isset( $_POST['btnExitSetup'] );
	}

	/** Check if the setup wizard needs to be completed.
	 *
	 * @return bool
	 */
	private function needs_completion() {
		return ! $this->is_completed() && ! $this->is_skipped() && ! cw_get_option( 'enabled' );
	}

	/** Check if the setup wizard is skipped.
	 *
	 * @return bool
	 */
	private function is_skipped() {
		return self::SETUP_WIZARD_STATUS_SKIPPED === $this->get_status();
	}

	/** Check if the setup wizard is completed.
	 *
	 * @return bool
	 */
	public function is_completed() {
		return self::SETUP_WIZARD_STATUS_COMPLETED === $this->get_status();
	}

	/** Get the setup wizard status.
	 *
	 * @return string
	 */
	private function get_status() {
		return get_option( self::SETUP_WIZARD_STATUS_OPTION, 'pending' );
	}

	/** Set the setup wizard status.
	 *
	 * @param string $status Setup Wizard status.
	 *
	 * @return bool True if the value was updated, false otherwise.
	 */
	private function set_status( $status ) {
		return update_option( self::SETUP_WIZARD_STATUS_OPTION, $status );
	}

	/** Set the setup wizard status to complete.
	 *
	 * @return bool True if the value was updated, false otherwise.
	 */
	private function set_status_completed() {
		return $this->set_status( self::SETUP_WIZARD_STATUS_COMPLETED );
	}

	/** Set the setup wizard status to skip.
	 *
	 * @return bool True if the value was updated, false otherwise.
	 */
	private function set_status_skipped() {
		return $this->set_status( self::SETUP_WIZARD_STATUS_SKIPPED );
	}

	/** Print Setup Wizard Header.
	 *
	 * @return void
	 */
	private function print_setup_wizard_header() {
		$template_path        = 'admin-setup-wizard/head.php';
		$template_path_filter = 'cw_setup_wizard_head_template_path';

		cryptowoo_get_template( $template_path, $template_path_filter );
	}

	/** Print Setup Wizard Content.
	 *
	 * @return void
	 */
	private function setup_wizard_content() {
		$template_path        = 'admin-setup-wizard/content.php';
		$template_path_filter = 'cw_setup_wizard_content_template_path';

		cryptowoo_get_template( $template_path, $template_path_filter );
	}
}

CW_Setup_Wizard::instance()->init();
