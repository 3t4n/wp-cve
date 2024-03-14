<?php

class WpSaioInit {

	private static $_instance   = null;
	private $main_menu_slug     = 'wp-support-all-in-one.php';
	private $admin_page_hookfix = '';

	public function __construct() {
		add_filter( 'plugin_action_links_' . WP_SAIO_BASE_NAME, array( $this, 'settings_link' ) );
		/*
		 * Load Text Domain
		 */
		add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );

		/*
		 * Register Enqueue
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'registerAdminEnqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'registerEnqueue' ) );

		/*
		 * Register Menu
		 */
		add_action( 'admin_menu', array( $this, 'registerAdminMenu' ) );

		/*
		 * Admin head
		 */
		add_action( 'admin_head', array( $this, 'adminHead' ) );

		/*
		 * WP Footer
		 */
		add_action( 'wp_footer', array( $this, 'wpFooter' ) );

		/*
		 * Register Shortcode
		 */
		WpSaioShortcodes::instance();

		/*
		 * Register Ajax
		 */
		WpSaioAjax::instance();

		/*
		 * Register Helper
		 */
		WpSaioHelper::instance();

		add_action( 'admin_init', array( $this, 'registerSettings' ) );
	}
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function registerAdminEnqueue( $hook_suffix ) {
		if ( $hook_suffix !== $this->admin_page_hookfix ) {
			return;
		}
		wp_register_style( 'wp-saio', WP_SAIO_URL . '/assets/admin/css/wp-saio.css' );
		wp_register_style( 'wp-saio-preview', WP_SAIO_URL . '/assets/home/css/wp-saio.css' );
		wp_register_style( 'ui-range', WP_SAIO_URL . '/assets/admin/css/ui-range.css' );
		wp_enqueue_style( 'wp-saio' );
		wp_enqueue_style( 'wp-saio-preview' );
		wp_style_add_data( 'wp-saio', 'rtl', 'replace' );
		wp_style_add_data( 'wp-saio-preview', 'rtl', 'replace' );
		wp_enqueue_style( 'ui-range' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'sortable', WP_SAIO_URL . '/assets/admin/js/Sortable.min.js' );

		wp_register_script( 'wp-saio', WP_SAIO_URL . '/assets/admin/js/admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ) );
		wp_enqueue_script( 'wp-saio' );
		wp_register_script( 'wp-saio-preview', WP_SAIO_URL . '/assets/home/js/wp-saio.min.js' );
		wp_enqueue_script( 'wp-saio-preview' );
		wp_enqueue_media();

		wp_localize_script(
			'wp-saio',
			'wp_saio_object',
			array(
				'are_you_sure'          => __( 'Are you sure you want to remove this app. All data will be erase?', WP_SAIO_LANG_PREFIX ),
				'wp_saio_html_inputs'   => json_encode( WpSaio::renderForm() ),
				'add_media_text_title'  => __( 'Choose Image', WP_SAIO_LANG_PREFIX ),
				'add_media_text_button' => __( 'Choose Image', WP_SAIO_LANG_PREFIX ),
				'style'                 => get_option( 'wpsaio_style' ),
				'ajax_url'              => admin_url( 'admin-ajax.php' ),
				'nonce'                 => wp_create_nonce( 'wpsaio_nonce' ),
			)
		);
	}
	public function registerEnqueue() {
		if ( ! $this->isActivePlugin() ) {
			return false;
		}
		wp_register_style( 'wp-saio', WP_SAIO_URL . '/assets/home/css/wp-saio.css' );
		wp_enqueue_style( 'wp-saio' );
		wp_style_add_data( 'wp-saio', 'rtl', 'replace' );

		wp_register_script( 'wp-saio', WP_SAIO_URL . '/assets/home/js/wp-saio.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'wp-saio' );

		wp_localize_script(
			'wp-saio',
			'wp_saio_object',
			array(
				'style' => get_option( 'wpsaio_style' ),
			)
		);
	}
	public function loadTextDomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}
		unload_textdomain( 'wp_saio' );
		load_textdomain( 'wp_saio', WP_SAIO_DIR . '/languages/' . $locale . '.mo' );
		load_plugin_textdomain( 'wp_saio', false, WP_SAIO_DIR . '/languages' );
	}
	public function registerAdminMenu() {
		$page_title = __( 'Support Chat All In One', WP_SAIO_LANG_PREFIX );
		$menu_title = __( 'Click to Chat', WP_SAIO_LANG_PREFIX );

		$this->admin_page_hookfix = add_menu_page( $page_title, $menu_title, 'manage_options', $this->main_menu_slug, array( $this, 'wpSaioMenuCallBack' ), WP_SAIO_URL . '/assets/admin/img/support-icon.svg' );
	}
	public function wpsaioLoadMainMenu() {
		global $plugin_page;
		$data = array();
		if ( isset( $_POST['save-wp-saio'] ) && isset( $_POST['data'] ) ) {
			$_data = WpSaioHelper::sanitize_array( $_POST['data'] );
			foreach ( $_data as $k => $v ) {
				$data[ $k ]['params'] = array();
				foreach ( $v as $k2 => $v2 ) {
					$data[ $k ]['params'][ $k2 ] = wp_unslash( trim( $v2 ) );
				}
			}
			update_option( 'njt_wp_saio', $data );

			wp_safe_redirect(
				esc_url(
					add_query_arg( array( 'page' => $this->main_menu_slug ), admin_url( 'admin.php' ) )
				)
			);
		}
	}
	public function adminHead() {
	}
	public function wpFooter() {
		if ( ! $this->isActivePlugin() ) {
			return;
		}
		$icon_bg_color = get_option( 'wpsaio_button_color', '' );
		$btn_icon      = get_option( 'wpsaio_button_icon', '' );
		$btn_image     = get_option( 'wpsaio_button_image', 'contain' );
		$data          = array(
			'buttons'       => WpSaio::generateFrontendButtons(),
			'contents'      => do_shortcode( implode( '', WpSaio::renderShortcodes() ) ),
			'icon_bg_color' => $icon_bg_color,
			'btn_icon'      => $btn_icon,
			'btn_image'     => $btn_image,
		);
		echo WpSaioView::load( 'home.main', $data );
	}
	private function isActivePlugin() {
		return ( get_option( 'wpsaio_enable_plugin' ) == 1 );
	}

	public function wpSaioMenuCallBack() {
		?>
		<div id="wpsaio" class="wpsaio wp-saio-wrap wrap">
			<h1><?php _e( 'Support Chat AIO', WP_SAIO_LANG_PREFIX ); ?></h1>
			<div class="notice notice-success settings-error is-dismissible" style="display: none">
				<div class="wpsaio__popup_notice">
					<p>
						<strong>Settings saved.
							<button class="notice-dismiss">
								<span class="screen-reader-text">Dismiss this notice.</span>
							</button>
						</strong>
					</p>
				</div>
			</div>
			<div class="wpsaio-row">
				<div class="wpsaio-col-main">
					<div class="wp-saio-tab-wrap">
						<ul class="njt-nav-tabs">
							<li class="njt-nav-item">
								<a class="njt-nav-link njt-nav-link-active" href="#saio-apps" data-njt-tab="#saio-apps">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path d="M296 32h192c13.255 0 24 10.745 24 24v160c0 13.255-10.745 24-24 24H296c-13.255 0-24-10.745-24-24V56c0-13.255 10.745-24 24-24zm-80 0H24C10.745 32 0 42.745 0 56v160c0 13.255 10.745 24 24 24h192c13.255 0 24-10.745 24-24V56c0-13.255-10.745-24-24-24zM0 296v160c0 13.255 10.745 24 24 24h192c13.255 0 24-10.745 24-24V296c0-13.255-10.745-24-24-24H24c-13.255 0-24 10.745-24 24zm296 184h192c13.255 0 24-10.745 24-24V296c0-13.255-10.745-24-24-24H296c-13.255 0-24 10.745-24 24v160c0 13.255 10.745 24 24 24z"></path>
									</svg>Choose Apps
								</a>
							</li>
							<li class="njt-nav-item">
								<a class="njt-nav-link" href="#saio-design" data-njt-tab="#saio-design">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
										<path d="M204.3 5C104.9 24.4 24.8 104.3 5.2 203.4c-37 187 131.7 326.4 258.8 306.7 41.2-6.4 61.4-54.6 42.5-91.7-23.1-45.4 9.9-98.4 60.9-98.4h79.7c35.8 0 64.8-29.6 64.9-65.3C511.5 97.1 368.1-26.9 204.3 5zM96 320c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm32-128c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm128-64c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm128 64c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"></path>
									</svg>Design
								</a>
							</li>
							<li class="njt-nav-item">
								<a class="njt-nav-link" href="#saio-display" data-njt-tab="#saio-display">
									<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="desktop" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-desktop fa-w-18 fa-2x">
										<path fill="currentColor" d="M528 0H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h192l-16 48h-72c-13.3 0-24 10.7-24 24s10.7 24 24 24h272c13.3 0 24-10.7 24-24s-10.7-24-24-24h-72l-16-48h192c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zm-16 352H64V64h448v288z" class=""></path>
									</svg>Display
								</a>
							</li>
						</ul>
						<div class="njt-tab-content">
							<div class="njt-tab-panel njt-tab-active" id="saio-apps">
								<?php WpSaio::generatePanel(); ?>
							</div>
							<div class="njt-tab-panel" id="saio-design">
								<?php require_once WP_SAIO_DIR . '/views/admin/design-settings.php'; ?>
							</div>
							<div class="njt-tab-panel" id="saio-display">
								<?php require_once WP_SAIO_DIR . '/views/admin/display-settings.php'; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="wpsaio-col-right">
					<div id="informationdiv" class="wpsaio-postbox">
						<h3>Do you need help?</h3>
						<div class="inside">
							<p>Thanks for using NinjaTeam's Products!</p>
							<p>If you have any problems or suggestions, please <a href="https://ninjateam.org/support" target="_blank">contact support</a>.</p>
							<p>Don't forget to <a href="https://wordpress.org/support/plugin/support-chat/reviews/#new-post" target="_blank">rate us</a> if this plugin is helpful for you.</p>
							<p>Best wishes,
								<br>
								Kelly from NinjaTeam
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	public function wpSaioMenuSettingsCallBack() {
		wp_enqueue_media();
		echo WpSaioView::load( 'admin.settings' );
	}
	public function registerSettings() {
		register_setting( 'wpsaio', 'wpsaio_enable_plugin' );
		register_setting( 'wpsaio', 'wpsaio_style' );
		register_setting( 'wpsaio', 'wpsaio_tooltip' );
		register_setting( 'wpsaio', 'wpsaio_widget_position' );
		register_setting( 'wpsaio', 'wpsaio_bottom_distance' );
		register_setting( 'wpsaio', 'wpsaio_button_icon' );
		register_setting( 'wpsaio', 'wpsaio_button_color' );
	}
	public static function activate() {
		$installed = get_option( 'wpsaio_enable_plugin' );

		if ( ! $installed ) {
			update_option( 'wpsaio_enable_plugin', 1 );
			update_option( 'wpsaio_style', 'redirect' );
			update_option( 'wpsaio_tooltip', 'appname' );
			update_option( 'wpsaio_widget_position', 'right' );
			update_option( 'wpsaio_button_image', 'contain' );
		}
	}

	public static function deactivate() {
	}

	public function settings_link( $link ) {
		// add custom link
		$setting_link = '<a href="admin.php?page=wp-support-all-in-one.php">Settings</a>';
		array_unshift( $link, $setting_link );
		return $link;
	}
}
