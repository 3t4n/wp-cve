<?php

namespace Codemanas\InactiveLogout;

/**
 * Class Bootstrap
 * @package Codemanas\InactiveLogout
 */
class Bootstrap {

	private $settings;

	public $message;

	/**
	 * Bootstrap constructor.
	 */
	public function __construct() {
		if ( version_compare( INACTIVE_LOGOUT_REQUIRED_PHP, PHP_VERSION, '>' ) ) {
			$this->message = sprintf(
				esc_html__( 'PHP version %1$s or greater is required for %2$s. Please update your PHP version in order to use the plugin.', 'inactive-logout' ),
				INACTIVE_LOGOUT_REQUIRED_PHP,
				esc_html__( 'Inactive Logout', 'inactive-logout' )
			);

			add_action( 'admin_notices', [ $this, 'admin_notice' ] );
		} else {
			add_action( 'plugins_loaded', [ $this, 'loaded' ] );
			add_action( 'init', [ $this, 'init' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'scripts' ] );
			add_filter( 'plugin_action_links', [ $this, 'action_link' ], 10, 2 );
			add_filter( 'auth_cookie_expiration', [ $this, 'auth_expiration' ], 10, 3 );
		}

		add_action( 'in_plugin_update_message-' . INACTIVE_LOGOUT_ABS_NAME, function ( $plugin_data ) {
			$this->version_update_warning( INACTIVE_LOGOUT_VERSION, $plugin_data['new_version'] );
		} );
	}

	/**
	 * Major Version Upgrade Notice
	 *
	 * @param $current_version
	 * @param $new_version
	 *
	 * @since 3.0.0
	 * @author Deepen
	 */
	public function version_update_warning( $current_version, $new_version ) {
		$current_version_minor_part = explode( '.', $current_version )[0];
		$new_version_minor_part     = explode( '.', $new_version )[0];

		if ( $current_version_minor_part === $new_version_minor_part ) {
			return;
		}
		?>
        <hr class="ina-major-update-warning__separator"/>
        <div class="ina-major-update-warning">
            <div class="ina-major-update-warning__icon">
                <span class="dashicons dashicons-info-outline"></span>
            </div>
            <div class="ina-major-update-warning_wrapper">
                <div class="ina-major-update-warning__title">
					<?php esc_html_e( 'Heads up, Please backup before upgrade!', 'inactive-logout' ); ?>
                </div>
                <div class="ina-major-update-warning__message">
					<?php
					esc_html_e( 'The latest update includes some substantial changes across different areas of the plugin. We highly recommend you backup your site before upgrading, and make sure you first update in a staging environment', 'inactive-logout' );
					?>
                </div>
            </div>
        </div>
		<?php
	}

	public function admin_notice() {
		printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $this->message );
	}

	/**
	 * Init the methods
	 *
	 * @since 3.0.0
	 * @author Deepen
	 */
	public function loaded() {
		$this->settings = Helpers::getInactiveSettingsData();
		$this->loadDependencies();

		load_plugin_textdomain( 'inactive-logout', false, trailingslashit( basename( dirname( __DIR__ ) ) ) . 'lang/' );
	}

	public function init() {
		$this->settings = Helpers::getInactiveSettingsData();
	}

	/**
	 * Load the dependencies
	 *
	 * @since 3.0.0
	 * @author Deepen
	 */
	public function loadDependencies() {
		$disable_feature = ! empty( $this->settings->advanced['disabled_feature'] ) ? $this->settings->advanced['disabled_feature'] : false;
		if ( ! $disable_feature && is_user_logged_in() ) {
			Modal::instance();
		}

		AdminFunctions::instance();
		Ajax::instance();

		if ( ( ! empty( $this->settings->advanced ) && ! empty( $this->settings->advanced['disabled_concurrent_login'] ) ) || ! empty( $this->settings->concurrent_enabled ) ) {
			ConcurrentLogin::get_instance();
		}

		if ( ( ! empty( $this->settings->advanced ) && ! empty( $this->settings->advanced['redirect_page'] ) ) || ! empty( $this->settings->enabled_redirect ) ) {
			LogoutHandler::instance();
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @param $hook
	 *
	 * @author Deepen
	 *
	 * @since 3.0.0
	 */
	public function scripts( $hook ) {
		if ( is_user_logged_in() ) {
			if ( $hook == "settings_page_inactive-logout" || $hook == "plugins.php" || $hook == "toplevel_page_inactive-logout" ) {
				wp_enqueue_style( 'inactive-logout-admin', INACTIVE_LOGOUT_DIR_URI . 'public/scripts/admin.css', false, INACTIVE_LOGOUT_VERSION );
			}

			if ( $hook == "settings_page_inactive-logout" || $hook == "toplevel_page_inactive-logout" ) {
				//Remove all admin notices from inactive settings screen page.
				remove_all_actions( 'admin_notices' );

                //Enqueue editor
				wp_enqueue_editor();

				//Vendor scripts
				wp_enqueue_script( 'inactive-logout-select2', INACTIVE_LOGOUT_DIR_URI . 'public/vendor/select2/js/select2.full.min.js', [ 'jquery' ], INACTIVE_LOGOUT_VERSION, true );
				wp_enqueue_style( 'inactive-logout-select2', INACTIVE_LOGOUT_DIR_URI . 'public/vendor/select2/css/select2.min.css', false, INACTIVE_LOGOUT_VERSION );
				wp_enqueue_script( 'inactive-logout-admin', INACTIVE_LOGOUT_DIR_URI . 'public/scripts/admin.js', [], INACTIVE_LOGOUT_VERSION, true );
				wp_localize_script( 'inactive-logout-admin', 'inactive_logout', [
					'wp_version'  => get_bloginfo( 'version' ),
					'ina_version' => INACTIVE_LOGOUT_VERSION,
					'security' => wp_create_nonce( '_ina_security_nonce' ),
				] );
			}

			$disable_feature = false;
			$ina_logout_time = $this->settings->logout_time;
			//if advanced options
			if ( ! empty( $this->settings->advanced ) ) {
				$ina_logout_time = ! empty( $this->settings->advanced['timeout'] ) ? $this->settings->advanced['timeout'] * 60 : 15 * 60;
				$disable_feature = ! empty( $this->settings->advanced['disabled_feature'] ) ? $this->settings->advanced['disabled_feature'] : false;
			}

			//Message content
			if ( ! empty( $this->settings->warn_only_enable ) ) {
				$message_content  = Helpers::get_option( '__ina_warn_message' );
				$replaced_content = str_replace( '{wakup_timout}', Helpers::convertToMinutes( $ina_logout_time ), $message_content );
				if ( function_exists( 'icl_register_string' ) ) {
					icl_register_string( 'inactive-logout', 'inactive_logout_dynamic_wakeup_text', esc_html( $replaced_content ) );
					$message_content = wpautop( icl_t( 'inactive-logout', 'inactive_logout_dynamic_wakeup_text', $replaced_content ) );
				} else {
					$message_content = wpautop( $replaced_content );
				}
			} else {
				if ( function_exists( 'icl_register_string' ) ) {
					icl_register_string( 'inactive-logout', 'inactive_logout_dynamic_popup_text', esc_html( Helpers::get_option( '__ina_logout_message' ) ) );
					icl_register_string( 'inactive-logout', 'inactive_logout_dynamic_after_logout_text', esc_html( Helpers::get_option( '__ina_after_logout_message' ) ) );
					$message_content      = wpautop( icl_t( 'inactive-logout', 'inactive_logout_dynamic_popup_text', Helpers::get_option( '__ina_logout_message' ) ) );
					$after_logout_content = wpautop( icl_t( 'inactive-logout', 'inactive_logout_dynamic_popup_text', Helpers::get_option( '__ina_after_logout_message' ) ) );
				} else {
					$message_content      = wpautop( Helpers::get_option( '__ina_logout_message' ) );
					$after_logout_content = wpautop( Helpers::get_option( '__ina_after_logout_message' ) );
				}
			}

			if ( ! $disable_feature ) {
				$ina_popup_modal = Helpers::get_option( '__ina_logout_popup_localizations' );
				$data            = [
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'modal'    => apply_filters( 'ina_popup_modal_text', [
						'ok'                           => ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['text_ok'] ) ? $ina_popup_modal['text_ok'] : __( 'Ok', 'inactive-logout' ),
						'close'                        => ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['text_close'] ) ? $ina_popup_modal['text_close'] : __( 'Close without Reloading', 'inactive-logout' ),
						'login_wait'                   => __( 'Logging in. Please wait', 'inactive-logout' ),
						'continue'                     => ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['continue_browsing_text'] ) ? $ina_popup_modal['continue_browsing_text'] : __( 'Continue Browsing', 'inactive-logout' ),
						'wakeup_continue'              => ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['wakeup_cta'] ) ? $ina_popup_modal['wakeup_cta'] : __( 'Ok', 'inactive-logout' ),
						'headerText'                   => ! empty( $ina_popup_modal ) && ! empty( $ina_popup_modal['popup_heading_text'] ) ? $ina_popup_modal['popup_heading_text'] : __( 'Session Timeout', 'inactive-logout' ),
						'message'                      => $message_content,
						'logout_message'               => ! empty( $after_logout_content ) ? $after_logout_content : apply_filters( 'ina__logout_message', '<p>' . esc_html__( 'You have been logged out because of inactivity.', 'inactive-logout' ) . '</p>' ),
						'disableCloseWithoutReloadBtn' => ! empty( Helpers::get_option( '__ina_disable_close_without_reload' ) ) ? Helpers::get_option( '__ina_disable_close_without_reload' ) : ''
					] ),
					'settings' => [
						'timeout'                    => ! empty( $ina_logout_time ) ? absint( $ina_logout_time ) : 15 * 60,
						'disable_countdown'          => ! empty( $this->settings->disable_prompt_timer ),
						'warn_message_enabled'       => ! empty( $this->settings->warn_only_enable ),
						'countdown_timeout'          => ! empty( $this->settings->prompt_countdown_timer ) ? absint( $this->settings->prompt_countdown_timer ) : 10,
						'disable_automatic_redirect' => ! empty( $this->settings->enabled_redirect ) && ! empty( $this->settings->automatic_redirect )
					],
					'security' => wp_create_nonce( '_inaajax' ),
				];

				$data['settings']['redirect'] = Helpers::getLogoutRedirectPage( $this->settings );
				if ( $this->settings->debugger ) {
					$data['settings']['debug_js']                 = true;
					$data['settings']['debug_msg']['logout']      = __( 'You will be logged out in', 'inactive-logout' );
					$data['settings']['debug_msg']['last_active'] = __( 'You were last active on', 'inactive-logout' );
					$data['settings']['debug_msg']['active']      = __( 'Tracking activity!', 'inactive-logout' );
				}

				$dependencies = require_once INACTIVE_LOGOUT_DIR_PATH . 'build/index.asset.php';
				$dependencies = ! empty( $dependencies ) ? $dependencies['dependencies'] : [];

				wp_enqueue_style( 'inactive-logout', INACTIVE_LOGOUT_BUILD_URI . '/index.css', false, INACTIVE_LOGOUT_VERSION );
				wp_enqueue_script( 'inactive-logout', INACTIVE_LOGOUT_BUILD_URI . '/index.js', $dependencies, INACTIVE_LOGOUT_VERSION, true );
				wp_localize_script( 'inactive-logout', 'inactiveLogout', $data );
			}
		}
	}

	/**
	 * Show configure link in main plugins page.
	 *
	 * @param $actions
	 * @param $plugin_file
	 *
	 * @return array
	 * @author Deepen
	 *
	 * @since 2.0.0
	 */
	function action_link( $actions, $plugin_file ) {
		static $plugin;

		if ( ! isset( $plugin ) ) {
			$plugin = INACTIVE_LOGOUT_ABS_NAME;
		}

		if ( $plugin == $plugin_file ) {
			$settings = array( 'settings' => '<a href="options-general.php?page=inactive-logout">' . __( 'Settings', 'inactive-logout' ) . '</a>' );

			$actions = array_merge( $settings, $actions );
		}

		return $actions;
	}

	/**
	 * Set Default WordPress authencation Cookie Time
	 *
	 * @param $expiration
	 * @param $user_id
	 * @param $remember
	 *
	 * @return int
	 * @author Deepen
	 *
	 * @since 2.0.0
	 */
	public function auth_expiration( $expiration, $user_id, $remember ) {
		if ( ! $remember ) {
			$expiration = apply_filters( 'ina_change_login_exp_time', 90000 ); //1 day and 1 hour
		}

		return $expiration;
	}

	/**
	 * Saving options for multisite.
	 */
	protected function _activate_multisite() {
		$logout_time = get_option( '__ina_logout_time' );
		if ( empty( $logout_time ) ) {
			$time = 15 * 60; // 15 Minutes
			update_option( '__ina_logout_time', $time );
		}

		$logout_message = get_option( '__ina_logout_message' );
		if ( empty( $logout_message ) ) {
			update_option( '__ina_logout_message', '<p>You are being timed-out out due to inactivity. Please choose to stay signed in or to logoff.</p><p>Otherwise, you will be logged off automatically.</p>' );
		}

		$warn_message = get_option( '__ina_warn_message' );
		if ( empty( $warn_message ) ) {
			update_option( '__ina_warn_message', '<h3>Wakeup!</h3><p>You have been inactive for {wakup_timout}. Press continue to continue browsing.</p>' );
		}

		$after_logout_message = get_option( '__ina_after_logout_message' );
		if ( empty( $after_logout_message ) ) {
			update_option( '__ina_after_logout_message', '<p>You have been logged out because of inactivity.</p>' );
		}
	}

	/**
	 * Managing things when plugin is deactivated.
	 */
	public static function deactivate() {

	}

	/**
	 * Plugin activation callback.
	 *
	 * @see register_deactivation_hook()
	 */
	public static function activate() {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			global $wpdb;
			$old_blog = $wpdb->blogid;

			// Get all blog ids.
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ); // WPCS: db call ok, cache ok.
			foreach ( $blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::instance()->_activate_multisite();
			}
			switch_to_blog( $old_blog );

			return;
		} else {
			self::instance()->_activate_multisite();
		}
	}

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

Bootstrap::instance();