<?php

namespace WeDevs\DokanVendorDashboard;

use WeDevs\Dokan\ProductCategory\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Scripts and styles class for the plugin.
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'wp_print_styles', array( $this, 'remove_all_styles_and_scripts' ), 100 );
		add_action( 'wp_loaded', array( $this, 'replace_short_code' ), 1 );
		add_action( 'wp', [ $this, 'enqueue_assets' ] );
		add_action( 'template_redirect', array( $this, 'set_plugin_template' ) );
		add_action( 'init', array( $this, 'register_scripts_and_styles' ) );
	}

	/**
	 * Reset all styles and scripts except our vendor dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function remove_all_styles_and_scripts() {
		if ( ! $this->is_old_route() && $this->is_vendor_dashboard() ) {
			$this->reset_head_style();
		}
	}

	/**
	 * Reset all register styles from WP or Other plugins.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function reset_head_style() {
		global $wp_styles;

		$included_styles = apply_filters(
			'dokan_vendor_dashboard_allowed_styles',
			[
				'vendor-dashboard-dokan-icon-style',
				'vendor-dashboard-css',
				'dokan-fontawesome',
		        'wp-block-library', // For wp media.
		        'media-views', // For wp media.
			]
		);

		$wp_styles->queue = $included_styles;
	}

	/**
	 * Registers styles and scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_scripts_and_styles() {
		$this->register_scripts();
		$this->register_styles();
	}

	/**
	 * Replace Dokan-dashboard short-code with this plugin's short-code.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function replace_short_code() {
		// force remove admin bar
		add_filter( 'show_admin_bar', '__return_false' );

		if ( $this->is_old_route() ) {
			return;
		}

		remove_shortcode( 'dokan-dashboard' );
		add_shortcode( 'dokan-dashboard', array( $this, 'dashboard_content' ) );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		global $post;

		if ( ! $post || ! has_shortcode( $post->post_content, 'dokan-dashboard' ) ) {
			return;
		}

		// If not switched to new dashboard, don't replace with short-code.
		if ( $this->is_old_route() ) {
			wp_enqueue_style( 'vendor-dashboard-old-compatible-style' );
			wp_enqueue_style( 'vendor-dashboard-dokan-icon-style' );

			wp_enqueue_script( 'vendor-dashboard-old-compatible-script' );
			return;
		}

		wp_enqueue_script( 'vendor-dashboard-js' );
		wp_localize_script( 'vendor-dashboard-js', 'dokanDashboard', $this->get_localize_script() );

		wp_enqueue_style( 'vendor-dashboard-css' );
		wp_enqueue_style( 'vendor-dashboard-dokan-icon-style' );
		wp_enqueue_style( 'dokan-fontawesome' );
		wp_enqueue_media();

		/**
		 *  Fires after dokan vendor dashboard scripts are loaded.
		 *
		 *  @hooked dokan_vendor_dashboard_script_loaded
		 *
		 *  @since 1.0.0
		 */
		do_action( 'dokan_vendor_dashboard_script_loaded' );
	}

	/**
	 * Show Dashboard Content and enqueue.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function dashboard_content() {
		return "<div id='root'></div>";
	}

	/**
	 * Check if it is vendor dashboard page.
	 *
	 * @since 1.0.0
	 *
	 * @param $current_page int The current page id.
	 *
	 * @return bool
	 */
	public function is_vendor_dashboard( $current_page = 0 ) {
		$vendor_dashboard_pages = get_option( 'dokan_pages', array() );
		if ( ! $current_page ) {
			global $post;

			if ( ! $post ) {
				return false;
			}

			$current_page = $post->ID;
		}

		if (
			isset( $vendor_dashboard_pages['dashboard'] )
			&& isset( $current_page )
			&& $current_page === $vendor_dashboard_pages['dashboard']
		) {
			return true;
		}

		return false;
	}

	/**
	 * Get template file path for rendering new dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_template_path() {
		return 'templates/vendor-dashboard-full-width.php';
	}

	/**
	 * Set plugin page template.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_plugin_template() {
		global $post;

		if ( $this->is_vendor_dashboard() && $this->get_current_page_template() !== $this->get_template_path() ) {
			update_post_meta( $post->ID, '_wp_page_template', $this->get_template_path() );
		}
	}

	/**
	 * Get current page template.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_current_page_template() {
		global $post;

		if ( empty( $post ) ) {
			return '';
		}

		return get_post_meta(
			$post->ID,
			'_wp_page_template',
			true
		);
	}

	/**
	 * Register scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_scripts() {
		$dependency = include DOKAN_VENDOR_DASHBOARD_DIR . '/build/main.asset.php';

		wp_register_script( 'vendor-dashboard-js', DOKAN_VENDOR_DASHBOARD_BUILD . '/main.js', $dependency['dependencies'], $dependency['version'], false );
		wp_register_script( 'vendor-dashboard-old-compatible-script', DOKAN_VENDOR_DASHBOARD_ASSETS . '/js/old-compatibility.js', [ 'jquery' ], DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION, false );
	}

	/**
	 * Register styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style( 'vendor-dashboard-custom-css', DOKAN_VENDOR_DASHBOARD_BUILD . '/style-main.css', [], DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION );
		wp_register_style( 'vendor-dashboard-css', DOKAN_VENDOR_DASHBOARD_BUILD . '/main.css', [ 'vendor-dashboard-custom-css' ], DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION );
		wp_register_style( 'vendor-dashboard-dokan-icon-style', DOKAN_VENDOR_DASHBOARD_ASSETS . '/fonts/dokan-icon/style.css', [], DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION );
		wp_register_style( 'vendor-dashboard-old-compatible-style', DOKAN_VENDOR_DASHBOARD_ASSETS . '/css/old-compatibility.css', [], DOKAN_VENDOR_DASHBOARD_PLUGIN_VERSION );
	}

	/**
	 * Get localized scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_localize_script() {
		$user          = wp_get_current_user();
		$has_dokan_pro = dokan()->is_pro_exists();

        $order_url         = dokan_get_navigation_url( 'orders' );
        $order_url         = str_replace( '/#/', '/', $order_url );
        $order_details_url = wp_nonce_url( add_query_arg( [ 'order_id' => 'edit_this_as_id' ], $order_url ), 'dokan_view_order' );

		$i18n = [
			'user' => [
				'id'        => $user->ID,
				'name'      => $user->display_name,
				'username'  => $user->user_login,
				'email'     => $user->user_email,
				'avatar'    => get_avatar_url( $user->ID ),
				'adminUrl'  => admin_url( 'profile.php' ),
				'logoutUrl' => wp_logout_url(),
			],
			'site' => [
				'name'      => get_bloginfo( 'name' ),
				'url'       => get_site_url(),
				'logo'      => get_site_icon_url(),
				'store_url' => dokan_get_store_url( dokan_get_current_user_id() ),
			],
			'rest' => [
				'root'      => esc_url_raw( get_rest_url() ),
				'dokan_api' => esc_url_raw( get_rest_url() ) . '/dokan',
				'nonce'     => wp_create_nonce( 'wp_rest' ),
				'version'   => 'dokan/v1',
				'version2'   => 'dokan/v2',
			],
			'timezone' => wp_timezone_string(),
			'routes'   => $this->get_routes(),
			'isPro'    => (int) $has_dokan_pro,
			'enabled'  => (int) dokan_is_seller_enabled( $user->ID ),
			'order_statuses' => wc_get_order_statuses(),
			'libs'     => [],
			'currency' => [
				'code'              => get_woocommerce_currency(),
				'precision'         => wc_get_price_decimals(),
				'symbol'            => html_entity_decode( get_woocommerce_currency_symbol( get_woocommerce_currency() ) ),
				'symbolPosition'    => get_option( 'woocommerce_currency_pos' ),
				'decimalSeparator'  => wc_get_price_decimal_separator(),
				'thousandSeparator' => wc_get_price_thousand_separator(),
				'priceFormat'       => html_entity_decode( get_woocommerce_price_format() ),
			],
			'configurations' => [
				'product'           => dokan()->product_block->get_configurations(),
			],
			'active_modules' => $has_dokan_pro ? dokan_pro()->module->get_active_modules() : [],
			'dokan_nonce_data' => [
				'export_order' => [
					'nonce'            => wp_create_nonce( 'dokan_vendor_order_export_action' ),
					'nonce_key'        => 'dokan_vendor_order_export_nonce',
					'action'           => 'dokan_vendor_order_export_action',
					'_wp_http_referer' => '/dashboard/orders/',
				],
				'order' => [
					'details_url'    => $order_details_url,
					'complete_order' => [
						'action'   => 'dokan_change_status',
						'_wpnonce' => wp_create_nonce( 'dokan_change_status' ),
					],
				],
			],
			'settings' => [
				'canChangeOrderStatus'             => 'on'  === dokan_get_option( 'order_status_change', 'dokan_selling', 'on' ),
				'productIsSingleCategory'          => Helper::product_category_selection_is_single(),
				'anyCategorySelection'             => 'on'  === dokan_get_option( 'dokan_any_category_selection', 'dokan_selling', 'on' ),
				'productCategorySelectionIsSingle' => 'single' === dokan_get_option( 'product_category_style', 'dokan_selling', 'single' ),
			],
			'allow_shipment' => function_exists( 'dokan_get_order_shipment_current_status' ) && 'on' === dokan_get_option( 'enabled', 'dokan_shipping_status_setting', 'off' ) && 'yes' === get_option( 'woocommerce_calc_shipping', 'no' ),
			'product_notices' => $this->get_product_notices(),
			'dashboard_notices' => $this->get_dashboard_notices(),
		];

		return apply_filters( 'dvd_localize_script', $i18n );
	}

	/**
	 * Get frontend registered routes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_routes() {
		return apply_filters( 'dvd_routes', dokan_get_dashboard_nav() );
	}

	/**
	 * If is in old dashboard page or not.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_old_route() {
		$current_page = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$current_page .= isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		$pos = strpos( $current_page, '/dashboard/' );

		if ( ! empty( $pos ) ) {
			// Get the slug after the /dashboard/ of the current page.
			$slug = substr( $current_page, $pos + 11, strlen( $current_page ) );

			// remove / character from the end of the slug.
			$slug = rtrim( $slug, '/' );
		}

		if ( empty( $slug ) ) { // it would be / route or dashboard route.
			return false;
		}

		if ( ! in_array( $slug, dokan_vendor_dashboard()->menu->get_supported_menus(), true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Retrives dashboard notices.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_dashboard_notices() {
		ob_start();
		do_action( 'dokan_dashboard_content_inside_before' );
		$notices = ob_get_contents();
		ob_end_clean();

		return $notices;
	}

	/**
	 * Retrives product notices.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_product_notices() {
		ob_start();
		do_action( 'dokan_before_listing_product' );
		$notices = ob_get_contents();
		ob_end_clean();

		return $notices;
	}
}
