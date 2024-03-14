<?php
/**
 * Setup Better Admin Bar.
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl;

/**
 * Setup Better Admin Bar.
 */
class Setup {
	/**
	 * Whether or not to remove admin bar for current user.
	 *
	 * @var bool
	 */
	public static $remove_admin_bar = false;

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {
		self::$instance = new self();

		add_action( 'plugins_loaded', array( self::$instance, 'setup' ) );
	}

	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Check if we're on the Kirki settings page.
	 *
	 * @return bool
	 */
	private function is_settings_page() {
		$current_screen = get_current_screen();

		return ( 'settings_page_better-admin-bar' === $current_screen->id ? true : false );
	}

	/**
	 * Setup action & filters.
	 */
	public function setup() {
		// Stop if Better Admin Bar Pro is active.
		if ( defined( 'SWIFT_CONTROL_PRO_PLUGIN_URL' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'setup_text_domain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 20 );
		add_action( 'wp', array( $this, 'check_page' ) );
		add_action( 'wp', array( $this, 'remove_admin_bar' ) );
		add_action( 'wp_head', array( $this, 'admin_bar_wp_head' ), PHP_INT_MAX );

		// Process export-import.
		add_action( 'admin_init', array( $this, 'process_export' ) );
		add_action( 'admin_init', array( $this, 'process_import' ) );

		add_action( 'wp_ajax_swift_control_change_widgets_order', array( new Ajax\Change_Widgets_Order(), 'ajax' ) );
		add_action( 'wp_ajax_swift_control_change_widget_settings', array( new Ajax\Change_Widget_Settings(), 'ajax' ) );
		add_action( 'wp_ajax_swift_control_save_general_settings', array( new Ajax\Save_General_Settings(), 'ajax' ) );
		add_action( 'wp_ajax_swift_control_save_position', array( new Ajax\Save_Position(), 'ajax' ) );

		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 4 );

		add_action( 'admin_enqueue_scripts', array( $this, 'quick_panel_admin_enqueue' ) );
		add_action( 'admin_footer', array( $this, 'quick_panel_admin_preview' ) );

		add_action( 'admin_notices', array( $this, 'discount_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'discount_notice_script' ) );
		add_action( 'wp_ajax_sc_discount_notice_dismissal', array( $this, 'dismiss_discount_notice' ) );

	}

	/**
	 * Setup textdomain.
	 */
	public function setup_text_domain() {
		load_plugin_textdomain( 'better-admin-bar', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Admin discount notice.
	 */
	public function discount_notice() {

		// Stop here if notice has been dismissed.
		if ( ! empty( get_option( 'swift_control_discontinue_message', 0 ) ) ) {
			return;
		}

		// Stop here if current user can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>

		<div class="notice notice-info swift-control-discontinue-notice is-dismissible">

			<div class="notice-body">
				<div class="notice-icon">
					<img src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL ); ?>/assets/images/logo.png">
				</div>
				<div class="notice-content">
					<h2>
						50% Off Better Admin Bar PRO - Launch Offer 🥳
					</h2>
					<p>
						<strong>Better Admin Bar PRO</strong> is now available!<br> As a valued user of our plugin we would like to offer you a <strong style="color: #d63638;">50% discount</strong> on <strong>Better Admin Bar PRO</strong>.
					</p>
					<p><a target="_blank" href="https://betteradminbar.com/launch-offer/?utm_source=bab&utm_medium=repository&utm_campaign=launch_offer" style="font-weight: 700;" class="button button-primary">Grab the deal</a> <strong>Coupon limited to 50 users!</strong></p>
				</div>
			</div>

		</div>

		<?php

	}

	/**
	 * Script that handles discount notice dismissal.
	 */
	public function discount_notice_script() {

		// Stop here if notice has been dismissed.
		if ( ! empty( get_option( 'swift_control_discontinue_message', 0 ) ) ) {
			return;
		}

		// Stop here if current user can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_enqueue_script( 'swift-control-control-discount', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/discount-notice.js', array( 'jquery' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

		wp_localize_script(
			'swift-control-control-discount',
			'swiftControlDismissal',
			array(
				'nonces' => array(
					'dismissalNonce' => wp_create_nonce( 'Better_Admin_Bar_Dismiss_Discount_Notice' ),
				),
			)
		);

	}

	/**
	 * Dismiss discount notice.
	 */
	public function dismiss_discount_notice() {
		$nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : 0;
		$dismiss = isset( $_POST['dismiss'] ) ? absint( $_POST['dismiss'] ) : 0;

		if ( empty( $dismiss ) ) {
			wp_send_json_error( __( 'Invalid Request', 'better-admin-bar' ) );
		}

		if ( ! wp_verify_nonce( $nonce, 'Better_Admin_Bar_Dismiss_Discount_Notice' ) ) {
			wp_send_json_error( __( 'Invalid Token', 'better-admin-bar' ) );
		}

		update_option( 'swift_control_discontinue_message', 1 );
		wp_send_json_success( __( 'Discount notice has been dismissed', 'better-admin-bar' ) );
	}

	/**
	 * Enqueue admin styles & scripts.
	 */
	public function admin_scripts() {

		wp_enqueue_style( 'swift-control-discount-notice', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/discount-notice.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		$current_screen = get_current_screen();

		if ( 'settings_page_better-admin-bar' !== $current_screen->id ) {
			return;
		}

		// Font Awesome 5.
		$this->deregister_font_awesome();
		wp_enqueue_style( 'font-awesome', SWIFT_CONTROL_PLUGIN_URL . '/assets/vendor/fontawesome-free/css/all.min.css', array(), '5.14.0' );

		// Icon picker.
		wp_enqueue_style( 'swift-control-icon-picker', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/icon-picker.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		// Select2.
		wp_enqueue_style( 'select2', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/select2.min.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		// Settings page styling.
		wp_enqueue_style( 'heatbox', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/heatbox.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		// Better Admin Bar admin styling.
		wp_enqueue_style( 'swift-control-admin', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/swift-control-admin.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		// Color picker dependency.
		wp_enqueue_style( 'wp-color-picker' );

		// jQuery UI dependencies.
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Select2.
		wp_enqueue_script( 'select2', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

		// Icon picker.
		wp_enqueue_script( 'swift-control-icon-picker', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/icon-picker.js', array( 'jquery' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

		$icons = file_get_contents( SWIFT_CONTROL_PLUGIN_DIR . '/assets/json/fontawesome5.json' );
		$icons = json_decode( $icons, true );
		$icons = $icons ? $icons : array();

		wp_localize_script(
			'swift-control-icon-picker',
			'iconPickerIcons',
			$icons
		);

		// Color picker alpha.
		wp_enqueue_script( 'wp-color-picker-alpha', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker', 'wp-i18n' ), '2.1.3', true );

		wp_enqueue_script( 'swift-control-settings-page', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/settings-page.js', array( 'jquery' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

		wp_enqueue_script( 'swift-control-widget-settings', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/widget-settings.js', array( 'jquery-ui-sortable', 'swift-control-icon-picker' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

		wp_enqueue_script( 'swift-control-general-settings', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/general-settings.js', array( 'select2', 'wp-color-picker-alpha' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

		wp_localize_script(
			'swift-control-widget-settings',
			'SwiftControl',
			array(
				'nonces' => array(
					'changeWidgetsOrder'   => wp_create_nonce( 'change_widgets_order' ),
					'changeWidgetSettings' => wp_create_nonce( 'change_widget_settings' ),
					'saveGeneralSettings'  => wp_create_nonce( 'save_general_settings' ),
				),
				'labels' => array(
					'edit' => __( 'Edit', 'better-admin-bar' ),
					'save' => __( 'Save', 'better-admin-bar' ),
				),
			)
		);
	}

	/**
	 * Enqueue quick panel's styles & scripts to admin area.
	 */
	public function quick_panel_admin_enqueue() {

		if ( ! $this->is_settings_page() ) {
			return;
		}

		$this->preview_scripts();
		$this->inline_styles( true );

	}

	/**
	 * Output the quick panel preview to admin area.
	 */
	public function quick_panel_admin_preview() {

		if ( ! $this->is_settings_page() ) {
			return;
		}

		swift_control_quick_access_panel( true );

	}

	/**
	 * Add settings link to plugin list page.
	 *
	 * @param array  $actions     An array of plugin action links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string $context     The plugin context. By default this can include 'all', 'active', 'inactive',
	 *                            'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
	 *
	 * @return array The modified plugin action links.
	 */
	public function add_settings_link( $actions, $plugin_file, $plugin_data, $context ) {
		if ( SWIFT_CONTROL_PLUGIN_BASENAME === $plugin_file ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=better-admin-bar' ) ) . '">' . __( 'Settings', 'better-admin-bar' ) . '</a>';

			array_unshift( $actions, $settings_link );
		}

		return $actions;
	}

	/**
	 * Add submenu under "Settings" menu item.
	 */
	public function add_submenu_page() {
		add_options_page( __( 'Better Admin Bar', 'better-admin-bar' ), __( 'Better Admin Bar', 'better-admin-bar' ), 'delete_others_posts', 'better-admin-bar', array( $this, 'page_output' ) );
	}

	/**
	 * Better Admin Bar page output.
	 */
	public function page_output() {
		require __DIR__ . '/templates/admin-page.php';
	}

	/**
	 * Modify admin body class.
	 *
	 * @param string $classes The class names.
	 */
	public function admin_body_class( $classes ) {

		$current_user = wp_get_current_user();
		$classes     .= ' swift-control-user-' . $current_user->user_nicename;

		$roles = $current_user->roles;
		$roles = $roles ? $roles : array();

		foreach ( $roles as $role ) {
			$classes .= ' swift-control-role-' . $role;
		}

		$screens = array(
			'settings_page_better-admin-bar',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $screens, true ) ) {
			return $classes;
		}

		$classes .= ' heatbox-admin has-header';

		return $classes;

	}

	/**
	 * Whether or not to show the widgets in frontend.
	 * Only display the widgets when there's any active one.
	 */
	public function check_page() {
		// Only show to logged-in admin users.
		if ( ! is_user_logged_in() || ! current_user_can( 'delete_others_posts' ) ) {
			return;
		}

		// Stop if this is a customizer preview.
		if ( is_customize_preview() ) {
			return;
		}

		// Stop if current page is in edit mode inside page builder.
		if ( swift_control_is_inside_page_builder() ) {
			return;
		}

		// Stop if swift control doesn't have active widgets.
		if ( ! swift_control_has_active_widgets() ) {
			return;
		}

		if ( ! apply_filters( 'swift_control_frontend_display', true ) ) {
			return;
		}

		add_filter( 'body_class', array( $this, 'add_body_class' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_styles' ) );
		add_action( 'wp_footer', array( $this, 'quick_panel_output' ), 5 );
		add_action( 'wp_footer', array( $this, 'admin_bar_output' ), 15 );
	}

	/**
	 * Add a class to body_class().
	 *
	 * @param array $classes The body classes.
	 * @return array $classes The modified body classes.
	 */
	public function add_body_class( $classes ) {
		$classes[] = 'has-swift-control';

		return $classes;
	}

	/**
	 * Remove admin bar on the frontend for certain roles.
	 */
	public function remove_admin_bar() {
		$admin_bar_settings = swift_control_get_admin_bar_settings();

		if ( ! isset( $admin_bar_settings['remove_by_roles'] ) || empty( $admin_bar_settings['remove_by_roles'] ) ) {
			return;
		}

		$selected_roles = $admin_bar_settings['remove_by_roles'];

		// Backward compatibility: old value's format is int (0 / 1).
		if ( is_numeric( $selected_roles ) ) {
			self::$remove_admin_bar = true;

			add_filter( 'show_admin_bar', '__return_false' );
			return;
		}

		if ( in_array( 'all', $admin_bar_settings['remove_by_roles'], true ) ) {
			self::$remove_admin_bar = true;

			add_filter( 'show_admin_bar', '__return_false' );
			return;
		} else {
			$current_user = wp_get_current_user();

			foreach ( $current_user->roles as $role ) {
				if ( in_array( $role, $selected_roles, true ) ) {
					self::$remove_admin_bar = true;

					add_filter( 'show_admin_bar', '__return_false' );
					break;
				}
			}
		}
	}

	/**
	 * Print admin bar styles on wp_head action hook.
	 *
	 * ! Some conditions checking need to be exactly '' (empty string) checking, not just "empty()" check.
	 * ! DO NOT simply change that to "empty()" or "!" (negation) checking.
	 *
	 * The CSS outputting conditions below are already battle tested and working fine.
	 * Changing it will be risky.
	 *
	 * The following conditions need to be tested along with the available options in admin bar setting:
	 * - Testing with desktop mode and small screen size mode.
	 * - Testing with the "hide_below_screen_width" value as less than 782 and greater than 782 (782 is WordPress breakpoint).
	 * - Combination both of above.
	 *
	 * ! So in case in the future, we feel smart enough and would like to make change to the conditions, please think TWICE OR MORE!
	 * ! Don't change the outputting conditions unless you run the test cases above.
	 */
	public function admin_bar_wp_head() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( is_customize_preview() ) {
			return;
		}

		if ( self::$remove_admin_bar ) {
			return;
		}

		// The "absint" is already done in Helper.php file.
		$admin_bar_settings  = swift_control_get_admin_bar_settings();
		$inactive_opacity    = '' !== $admin_bar_settings['inactive_opacity'] ? $admin_bar_settings['inactive_opacity'] : 100;
		$active_opacity      = '' !== $admin_bar_settings['active_opacity'] ? $admin_bar_settings['active_opacity'] : 100;
		$transition_duration = '' !== $admin_bar_settings['transition_duration'] ? $admin_bar_settings['transition_duration'] : 500;

		echo '<style type="text/css">';

		/**
		 * First, output the CSS for the admin bar items overflow's fix.
		 * This won't conflict with the other options implementation.
		 */
		if ( $admin_bar_settings['fix_menu_item_overflow'] ) {
			echo file_get_contents( __DIR__ . '/assets/css/fix-items-overflow.css' );
		}

		/**
		 * And then output the transition duration option.
		 * This also won't conflict with the other options implementation.
		 */
		?>
		html #wpadminbar {
			-webkit-transition: all <?php echo esc_attr( $transition_duration / 1000 ); ?>s ease;
			transition: all <?php echo esc_attr( $transition_duration / 1000 ); ?>s ease;
		}

		body {
			-webkit-transition: all <?php echo esc_attr( $transition_duration / 1000 ); ?>s ease;
			transition: all <?php echo esc_attr( $transition_duration / 1000 ); ?>s ease;
		}

		<?php
		// Remove the top gap.
		if ( $admin_bar_settings['remove_top_gap'] ) {
			?>

			/** "remove_top_gap" is checked */

			html,
			html body,
			* html body {
				margin-top: -32px;
			}

			@media screen and (max-width: 782px) {
				html,
				html body,
				* html body {
					margin-top: -46px;
				}
			}

			<?php
		}

		/**
		 * Implement the "active_opacity".
		 *
		 * The active / hover state does not depend on the inactive state.
		 * Meaning, it can be standalone.
		 */
		if ( '' !== $admin_bar_settings['active_opacity'] ) {
			?>

			html:not(.auto-hide-admin-bar) #wpadminbar:hover {
				opacity: <?php echo esc_attr( $active_opacity / 100 ); ?>
			}

			html.auto-hide-admin-bar.show-wpadminbar #wpadminbar {
				opacity: <?php echo esc_attr( $active_opacity / 100 ); ?>;
			}

			<?php
		}

		/**
		 * Implement the "inactive_opacity" option.
		 */
		if ( '' !== $admin_bar_settings['inactive_opacity'] ) {
			?>

			html:not(.auto-hide-admin-bar) #wpadminbar {
				opacity: <?php echo esc_attr( $inactive_opacity / 100 ); ?>;
			}

			html.auto-hide-admin-bar #wpadminbar {
				opacity: <?php echo esc_attr( $inactive_opacity / 100 ); ?>;
			}

			<?php
			// Implement the "active_opacity".
			if ( '' === $admin_bar_settings['active_opacity'] ) {
				// If inactive opacity is set and active opacity is empty, then lets set the active value to use the inactive value.
				$active_opacity = $inactive_opacity;
				?>

				html:not(.auto-hide-admin-bar) #wpadminbar:hover {
					opacity: <?php echo esc_attr( $active_opacity / 100 ); ?>
				}

				html.auto-hide-admin-bar.show-wpadminbar #wpadminbar {
					opacity: <?php echo esc_attr( $active_opacity / 100 ); ?>;
				}

				<?php
			}
		}

		if ( $admin_bar_settings['auto_hide'] ) {
			?>

			/** "auto_hide" is checked */

			html,
			html body,
			* html body {
				margin-top: -32px;
			}

			html #wpadminbar {
				top: -32px;
			}

			html.show-wpadminbar,
			html.show-wpadminbar body,
			* html.show-wpadminbar body {
				margin-top: 0;
			}

			html.show-wpadminbar #wpadminbar {
				top: 0;
			}

			@media screen and (max-width: 782px) {
				html,
				html body,
				* html body {
					margin-top: -46px;
				}

				html #wpadminbar {
					top: -46px;
				}

				html.show-wpadminbar,
				html.show-wpadminbar body,
				* html.show-wpadminbar body {
					margin-top: 0;
				}

				html.show-wpadminbar #wpadminbar {
					top: 0;
				}
			}

			<?php

			if ( $admin_bar_settings['remove_top_gap'] ) {
				?>

				/** both "auto_hide" and "remove_top_gap" are checked */

				html.show-wpadminbar,
				html.show-wpadminbar body,
				* html.show-wpadminbar body {
					margin-top: -32px;
				}

				@media screen and (max-width: 782px) {
					html.show-wpadminbar,
					html.show-wpadminbar body,
					* html.show-wpadminbar body {
						margin-top: -46px;
					}
				}

				<?php
			}
		}

		/**
		 * Force hide admin bar when the screen width is smaller
		 * than the value of "hide_below_screen_width".
		 */
		if ( '' !== $admin_bar_settings['hide_below_screen_width'] ) {
			$screen_max_width = $admin_bar_settings['hide_below_screen_width'] - 1;
			?>

			/** Force hide the admin bar */

			@media (max-width: <?php echo esc_attr( $screen_max_width ); ?>px) {
				html #wpadminbar {
					padding: 0;
					height: 0;
					display: none !important;
				}
			}

			<?php
			if ( $screen_max_width <= 782 ) {
				?>

				@media (max-width: <?php echo esc_attr( $screen_max_width ); ?>px) {
					html body,
					html.show-wpadminbar body,
					* html.show-wpadminbar body {
						margin-top: -46px;
					}
				}

				<?php
			} else {
				?>

				@media (max-width: <?php echo esc_attr( $screen_max_width ); ?>px) {
					html body,
					html.show-wpadminbar body,
					* html.show-wpadminbar body {
						margin-top: -32px;
					}
				}

				@media (max-width: 782px) {
					html body,
					html.show-wpadminbar body,
					* html.show-wpadminbar body {
						margin-top: -46px;
					}
				}

				<?php
			}
			if ( $admin_bar_settings['remove_top_gap'] ) {
			}
		}

		echo '</style>';

	}

	/**
	 * Enqueue frontend assets.
	 */
	public function frontend_scripts() {
		$misc_settings       = swift_control_get_misc_settings();
		$remove_font_awesome = isset( $misc_settings['remove_font_awesome'] ) ? absint( $misc_settings['remove_font_awesome'] ) : 0;

		/**
		 * If "Don't enqueue Font Awesome" setting is un-checked (disabled),
		 * then deregister any existing Font Awesome css, and enqueue our version.
		 */
		if ( ! $remove_font_awesome ) {
			$this->deregister_font_awesome();
			wp_enqueue_style( 'font-awesome', SWIFT_CONTROL_PLUGIN_URL . '/assets/vendor/fontawesome-free/css/all.min.css', array(), '5.14.0' );
		}

		// Quick access panel styling.
		wp_enqueue_style( 'swift-control', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/swift-control.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		// Quick access panel's frontend scripts.
		wp_enqueue_script( 'interact', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/interact.min.js', array( 'jquery' ), SWIFT_CONTROL_PLUGIN_VERSION, true );
		wp_enqueue_script( 'swift-control', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/swift-control.js', array( 'jquery', 'interact' ), SWIFT_CONTROL_PLUGIN_VERSION, true );
	}

	/**
	 * Enqueue quick access panel's preview scripts.
	 */
	public function preview_scripts() {

		// Quick access panel styling.
		wp_enqueue_style( 'swift-control', SWIFT_CONTROL_PLUGIN_URL . '/assets/css/swift-control.css', array(), SWIFT_CONTROL_PLUGIN_VERSION );

		// Quick access panel's preview script.
		wp_enqueue_script( 'swift-control-preview', SWIFT_CONTROL_PLUGIN_URL . '/assets/js/swift-control-preview.js', array( 'jquery' ), SWIFT_CONTROL_PLUGIN_VERSION, true );

	}

	/**
	 * Dequeue & deregister existing Font Awesome
	 * to prevent conflict with our enqueue.
	 */
	public function deregister_font_awesome() {

		wp_dequeue_style( 'font-awesome' );
		wp_dequeue_style( 'fontawesome' );

		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'fontawesome' );

	}

	/**
	 * Output the quick access panel to frontend.
	 */
	public function quick_panel_output() {

		swift_control_quick_access_panel();

	}

	/**
	 * Output the admin bar script to frontend.
	 */
	public function admin_bar_output() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( self::$remove_admin_bar ) {
			return;
		}

		$admin_bar_settings = swift_control_get_admin_bar_settings();

		if ( ! $admin_bar_settings['auto_hide'] ) {
			return;
		}
		?>

		<script>
		var swiftControlAdminBarOpts = {
			showingIntent: <?php echo esc_attr( $admin_bar_settings['showing_intent'] ? $admin_bar_settings['showing_intent'] : 500 ); ?>,
			hidingIntent: <?php echo esc_attr( $admin_bar_settings['hiding_intent'] ? $admin_bar_settings['hiding_intent'] : 1250 ); ?>,
			transitionDelay: <?php echo esc_attr( $admin_bar_settings['hiding_transition_delay'] ? $admin_bar_settings['hiding_transition_delay'] : 1500 ); ?>
		};
		<?php echo file_get_contents( __DIR__ . '/assets/js/admin-bar.js' ); ?>
		</script>

		<?php
	}

	/**
	 * Output the inline styles to frontend.
	 *
	 * @param bool $is_preview Whether the panel is in preview mode inside admin area or not.
	 */
	public function inline_styles( $is_preview = false ) {

		$color_settings = swift_control_get_color_settings();
		$default_colors = swift_control_get_default_color_settings();
		$css            = '';

		if ( $color_settings['setting_button_bg_color'] !== $default_colors['setting_button_bg_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-setting .swift-control-widget-link, .swift-control-widgets .swift-control-widget-setting .swift-control-widget-link:hover {';
			$css .= 'background-color: ' . esc_attr( $color_settings['setting_button_bg_color'] ) . ';';
			$css .= '}';

			$css .= '.swift-control-widgets .swift-control-widget-setting::after {';
			$css .= 'color: ' . esc_attr( $color_settings['setting_button_bg_color'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['setting_button_icon_color'] !== $default_colors['setting_button_icon_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-setting a {';
			$css .= 'color: ' . esc_attr( $color_settings['setting_button_icon_color'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['widget_bg_color_hover'] !== $default_colors['widget_bg_color_hover'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-link:hover {';
			$css .= 'background-color: ' . esc_attr( $color_settings['widget_bg_color_hover'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['widget_bg_color'] !== $default_colors['widget_bg_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-link {';
			$css .= 'background-color: ' . esc_attr( $color_settings['widget_bg_color'] ) . ';';
			$css .= '}';

			$css .= '.swift-control-widgets .is-disabled .swift-control-widget-link {';
			$css .= 'background-color: ' . esc_attr( $color_settings['widget_bg_color'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['widget_icon_color'] !== $default_colors['widget_icon_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-link {';
			$css .= 'color: ' . esc_attr( $color_settings['widget_icon_color'] ) . ';';
			$css .= '}';
		}

		wp_add_inline_style( 'swift-control', $css );

	}

	/**
	 * Process widget export.
	 */
	public function process_export() {

		if ( ! isset( $_POST['swift_control_action'] ) || 'export' !== $_POST['swift_control_action'] ) {
			return;
		}

		if ( ! isset( $_POST['swift_control_export_nonce'] ) || empty( $_POST['swift_control_export_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['swift_control_export_nonce'], 'swift_control_export_widgets' ) ) {
			return;
		}

		$exporter = new Helpers\Export();

		$exporter->export();

	}

	/**
	 * Process widget import.
	 */
	public function process_import() {

		if ( ! isset( $_FILES['swift_control_import_file'] ) || empty( $_FILES['swift_control_import_file'] ) ) {
			return;
		}

		if ( ! isset( $_POST['swift_control_action'] ) || 'import' !== $_POST['swift_control_action'] ) {
			return;
		}

		if ( ! isset( $_POST['swift_control_import_nonce'] ) || empty( $_POST['swift_control_import_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['swift_control_import_nonce'], 'swift_control_import_widgets' ) ) {
			return;
		}

		$importer = new Helpers\Import();

		$importer->import();

	}

}
