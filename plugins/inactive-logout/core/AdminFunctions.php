<?php

namespace Codemanas\InactiveLogout;

/**
 * Admin Functions Class
 *
 * @since   1.0.0
 * @author  Deepen
 * @package inactive-logout
 */
class AdminFunctions {

	public static $message = '';

	public $settings;

	/**
	 * Inactive_Logout_Admin_Views constructor.
	 */
	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'inactiveLogoutMenu' ) );

		// Add Menu for multisite network.
		add_action( 'network_admin_menu', array( $this, 'multisiteMenu' ) );

		add_action( 'ina_before_settings_wrapper', array( $this, 'ina_before_settings_wrap' ) );
		add_action( 'ina_after_settings_wrapper', array( $this, 'ina_after_settings_wrap' ) );

		//Saving the settings page
		add_action( 'wp_ajax_ina_save_settings', array( $this, 'saveRoleBasedSettings' ) );
	}

	/**
	 * Add a Menu Option in settings
	 */
	public function inactiveLogoutMenu() {
		if ( is_multisite() ) {
			$idle_overrideby_multisite_setting = get_site_option( '__ina_overrideby_multisite_setting' );
			if ( empty( $idle_overrideby_multisite_setting ) ) {
				add_options_page( __( 'Inactive User Logout Settings', 'inactive-logout' ), __( 'Inactive Logout', 'inactive-logout' ), 'manage_options', 'inactive-logout', array(
					$this,
					'render_options'
				) );
			}
		} else {
			add_options_page( __( 'Inactive User Logout Settings', 'inactive-logout' ), __( 'Inactive Logout', 'inactive-logout' ), 'manage_options', 'inactive-logout', array(
				$this,
				'render_options'
			) );
		}
	}

	/**
	 * Add menu page.
	 */
	public function multisiteMenu() {
		add_menu_page( __( 'Inactive User Logout Settings', 'inactive-logout' ), __( 'Inactive Logout', 'inactive-logout' ), 'manage_options', 'inactive-logout', array(
			$this,
			'render_options'
		), 'dashicons-lock' );
	}

	/**
	 * Rendering the output.
	 */
	public function render_options() {
		//Enqueue Admin Scripts
		wp_enqueue_script( "ina-logout-inactive-logoutonly-js" );
		wp_enqueue_script( "ina-logout-inactive-select-js" );
		wp_enqueue_style( "ina-logout-inactive-select" );

		$adv_submit = filter_input( INPUT_POST, 'adv_submit' );
		if ( isset( $adv_submit ) ) {
			$this->ina__process_adv_settings();
		}

		// Css rules for Color Picker.
		wp_enqueue_style( 'wp-color-picker' );
		$tab        = filter_input( INPUT_GET, 'tab' );
		$active_tab = isset( $tab ) ? $tab : 'ina-basic';

		// Include Template.
		do_action( 'ina_before_settings_wrapper' );
		require_once INACTIVE_LOGOUT_VIEWS . '/tpl-inactive-logout-settings.php';
		if ( 'ina-basic' === $active_tab ) {
			require_once INACTIVE_LOGOUT_VIEWS . '/tabs/tpl-inactive-logout-basic.php';
		} elseif ( 'ina-support' === $active_tab ) {
			require_once INACTIVE_LOGOUT_VIEWS . '/tabs/tpl-inactive-logout-support.php';
		} elseif ( 'ina-advanced' === $active_tab ) {
			// ADVANCED.
			$ina_multiuser_timeout_enabled = Helpers::get_option( '__ina_enable_timeout_multiusers' );
			if ( $ina_multiuser_timeout_enabled ) {
				$ina_multiuser_settings = Helpers::get_option( '__ina_multiusers_settings' );
			}

			require_once INACTIVE_LOGOUT_VIEWS . '/tabs/tpl-inactive-logout-advanced.php';
		}

		do_action( 'ina_after_settings_wrapper', $active_tab );
	}

	/**
	 * Save basic settings
	 *
	 * @return bool|void
	 */
	public function saveRoleBasedSettings() {
		check_ajax_referer( '_nonce_action_save_timeout_settings', '_save_timeout_settings' );

		$idle_timeout                         = filter_input( INPUT_POST, 'idle_timeout', FILTER_SANITIZE_NUMBER_INT );
		$idle_timeout_message                 = wp_kses_post( filter_input( INPUT_POST, 'idle_message_text' ) );
		$after_timeout_message                = wp_kses_post( filter_input( INPUT_POST, 'after_session_logout_message' ) );
		$idle_disable_countdown               = filter_input( INPUT_POST, 'idle_disable_countdown', FILTER_SANITIZE_NUMBER_INT );
		$countdown_timeout                    = filter_input( INPUT_POST, 'idle_countdown_timeout', FILTER_SANITIZE_NUMBER_INT );
		$ina_show_warn_message_only           = filter_input( INPUT_POST, 'ina_show_warn_message_only', FILTER_SANITIZE_NUMBER_INT );
		$ina_show_warn_message                = wp_kses_post( filter_input( INPUT_POST, 'ina_show_warn_message' ) );
		$ina_disable_multiple_login           = filter_input( INPUT_POST, 'ina_disable_multiple_login', FILTER_SANITIZE_NUMBER_INT );
		$ina_enable_redirect_link             = filter_input( INPUT_POST, 'ina_enable_redirect_link', FILTER_SANITIZE_NUMBER_INT );
		$ina_redirect_page                    = filter_input( INPUT_POST, 'ina_redirect_page' );
		$ina_enable_debugger                  = filter_input( INPUT_POST, 'ina_enable_debugger' );
		$ina_disable_close_without_reload_btn = filter_input( INPUT_POST, 'popup_modal_close_without_reload_hide' );
		$ina_disable_automatic_redirect       = filter_input( INPUT_POST, 'ina_disable_automatic_redirect' );

		//localization settings
		$ina_popup_localizations = [
			'text_close'             => filter_input( INPUT_POST, 'popup_modal_text_close' ),
			'text_ok'                => filter_input( INPUT_POST, 'popup_modal_text_ok' ),
			'continue_browsing_text' => filter_input( INPUT_POST, 'popup_modal_text_continue_browsing' ),
			'popup_heading_text'     => filter_input( INPUT_POST, 'popup_modal_text_popup_heading' ),
			'wakeup_cta'             => filter_input( INPUT_POST, 'popup_modal_wakeup_continue_btn' ),
		];
		Helpers::update_option( '__ina_logout_popup_localizations', $ina_popup_localizations );

		$ina_custom_redirect_text_field = ! empty( $ina_redirect_page ) && 'custom-page-redirect' === $ina_redirect_page ? filter_input( INPUT_POST, 'custom_redirect_text_field' ) : false;

		do_action( 'ina_before_update_basic_settings' );

		// If Mulisite is Active then Add these settings to mulsite option table as well.
		if ( get_current_blog_id() == get_main_network_id() && is_multisite() ) {
			$idle_overrideby_multisite_setting = filter_input( INPUT_POST, 'idle_overrideby_multisite_setting', FILTER_SANITIZE_NUMBER_INT );
			update_site_option( '__ina_overrideby_multisite_setting', $idle_overrideby_multisite_setting );
		}

		$save_minutes = ( $idle_timeout > 1440 ) ? 1440 : $idle_timeout * 60; // 60 minutes
		if ( $idle_timeout ) {
			Helpers::update_option( '__ina_logout_time', $save_minutes );
			Helpers::update_option( '__ina_logout_message', $idle_timeout_message );
			Helpers::update_option( '__ina_after_logout_message', $after_timeout_message );
			Helpers::update_option( '__ina_disable_countdown', $idle_disable_countdown );
			Helpers::update_option( '__ina_countdown_timeout', absint( $countdown_timeout ) );
			Helpers::update_option( '__ina_warn_message_enabled', $ina_show_warn_message_only );
			Helpers::update_option( '__ina_warn_message', $ina_show_warn_message );
			Helpers::update_option( '__ina_concurrent_login', $ina_disable_multiple_login );
			Helpers::update_option( '__ina_enable_redirect', $ina_enable_redirect_link );
			Helpers::update_option( '__ina_redirect_page_link', $ina_redirect_page );
			Helpers::update_option( '__ina_enable_debugger', $ina_enable_debugger );
			Helpers::update_option( '__ina_disable_close_without_reload', $ina_disable_close_without_reload_btn );
			Helpers::update_option( '__ina_disable_automatic_redirect_on_logout', $ina_disable_automatic_redirect );

			if ( 'custom-page-redirect' === $ina_redirect_page ) {
				Helpers::update_option( '__ina_custom_redirect_text_field', $ina_custom_redirect_text_field );
			}
		}

		do_action( 'ina_after_update_basic_settings' );

		Helpers::update_option( '__ina_saved_options', __( 'General Settings saved.', 'inactive-logout' ) );

		wp_send_json_success();
	}

	/**
	 * Manages Advance settings.
	 *
	 * @return bool|void
	 */
	public function ina__process_adv_settings() {
		$sm_nonce   = filter_input( INPUT_POST, '_save_timeout_adv_settings' );
		$nonce      = $sm_nonce ?? '';
		$adv_submit = filter_input( INPUT_POST, 'adv_submit' );

		if ( isset( $adv_submit ) && ! wp_verify_nonce( $nonce, '_nonce_action_save_timeout_adv_settings' ) ) {
			wp_die( 'Not Allowed' );
		}

		$ina_enable_different_role_timeout     = filter_input( INPUT_POST, 'ina_enable_different_role_timeout' );
		$ina_multiuser_roles                   = filter_input( INPUT_POST, 'ina_multiuser_roles', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$ina_individual_user_timeout           = filter_input( INPUT_POST, 'ina_individual_user_timeout', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$ina_redirect_page_individual_user     = filter_input( INPUT_POST, 'ina_redirect_page_individual_user', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$ina_disable_inactive_logout           = filter_input( INPUT_POST, 'ina_disable_inactive_logout', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$ina_disable_inactive_concurrent_login = filter_input( INPUT_POST, 'ina_disable_inactive_concurrent_login', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$container_multi_user_arr = array();
		if ( $ina_multiuser_roles ) {
			foreach ( $ina_multiuser_roles as $k => $ina_multiuser_role ) {
				$user_timeout_minutes              = ! empty( $ina_individual_user_timeout[ $k ] ) ? absint( $ina_individual_user_timeout[ $k ] ) : 15;
				$multi_userredirect_page_link      = ! empty( $ina_redirect_page_individual_user[ $ina_multiuser_role ] ) ? $ina_redirect_page_individual_user[ $ina_multiuser_role ] : null;
				$disabled_for_user                 = ! empty( $ina_disable_inactive_logout[ $ina_multiuser_role ] ) ? 1 : null;
				$disabled_for_user_concurent_login = ! empty( $ina_disable_inactive_concurrent_login[ $ina_multiuser_role ] ) ? 1 : null;

				//Validate URL
				if ( filter_var( $multi_userredirect_page_link, FILTER_VALIDATE_URL ) === false ) {
					$multi_userredirect_page_link = false;
				}

				$container_multi_user_arr[] = array(
					'role'                      => $ina_multiuser_role,
					'timeout'                   => min( $user_timeout_minutes, 1440 ),
					'redirect_page'             => $multi_userredirect_page_link,
					'disabled_feature'          => $disabled_for_user,
					'disabled_concurrent_login' => $disabled_for_user_concurent_login,
				);
			}
		}

		do_action( 'ina_before_update_adv_settings', $container_multi_user_arr );

		Helpers::update_option( '__ina_enable_timeout_multiusers', $ina_enable_different_role_timeout );
		if ( $ina_enable_different_role_timeout ) {
			Helpers::update_option( '__ina_multiusers_settings', $container_multi_user_arr );
		}

		do_action( 'ina_after_update_adv_settings', $container_multi_user_arr );

		Helpers::set_message( __( 'Role based settings saved.', 'inactive-logout' ) );
	}

	/**
	 * Settings wrapper html element.
	 */
	public function ina_before_settings_wrap() {
		echo '<div class="wrap ina-settings-wrapper">';
	}

	/**
	 * Settings wrapper html element.
	 */
	public function ina_after_settings_wrap() {
		echo '</div>';
	}

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}
