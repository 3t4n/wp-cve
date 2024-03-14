<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdupcpAJAX' ) ) {
	/**
	 * Class to handle AJAX interactions for Ultimate Product Catalog
	 *
	 * @since 5.0.0
	 */
	class ewdupcpAJAX {

		public function __construct() { 

			add_action( 'wp_ajax_ewd_upcp_record_view', array( $this, 'record_view' ) );
			add_action( 'wp_ajax_nopriv_ewd_upcp_record_view', array( $this, 'record_view' ) );

			add_action( 'wp_ajax_ewd_upcp_update_catalog', array( $this, 'update_catalog' ) );
			add_action( 'wp_ajax_nopriv_ewd_upcp_update_catalog', array( $this, 'update_catalog' ) );

			add_action( 'wp_ajax_ewd_upcp_clear_cart', array( $this, 'clear_cart' ) );
			add_action( 'wp_ajax_nopriv_ewd_upcp_clear_cart', array( $this, 'clear_cart' ) );

			add_action('wp_ajax_ewd_upcp_add_to_cart', array( $this, 'add_to_cart' ) );
			add_action( 'wp_ajax_nopriv_ewd_upcp_add_to_cart', array( $this, 'add_to_cart' ) );
		}

		/**
		 * Record the viewing of a product
		 * @since 5.0.0
		 */
		public function record_view() {
			global $ewd_upcp_controller;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-upcp-js', 'nonce' ) ) {
				ewdupcpHelper::bad_nonce_ajax();
			}

			$product_id = intval( $_POST['product_id'] );

			update_post_meta( $product_id, 'views', get_option( $product_id, 'views', true ) + 1 );
		}

		/**
		 * Returns the output for a single order, given its tracking number and (optionally) email
		 * @since 5.0.0
		 */
		public function update_catalog() {
			global $ewd_upcp_controller;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-upcp-js', 'nonce' ) ) {
				ewdupcpHelper::bad_nonce_ajax();
			}

			$args = array(
				'sidebar'			=> 'no',
				'starting_layout'	=> '',
				'id'				=> ! empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0,
				'current_page'		=> ! empty( $_POST['current_page'] ) ? intval( $_POST['current_page'] ) : 1,
				'products_per_page'	=> ! empty( $_POST['products_per_page'] ) ? intval( $_POST['products_per_page'] ) : 0,
				'excluded_layouts'	=> ! empty( $_POST['excluded_views'] ) ? sanitize_text_field( $_POST['excluded_views'] ) : 'none',
				'ajax_url'			=> ! empty( $_POST['ajax_url'] ) ? esc_url_raw( $_POST['ajax_url'] ) : '',
				'prod_name'			=> ! empty( $_POST['product_name'] ) ? sanitize_text_field( $_POST['product_name'] ) : '',
				'min_price'			=> ! empty( $_POST['min_price'] ) ? sanitize_text_field( $_POST['min_price'] ) : '',
				'max_price'			=> ! empty( $_POST['max_price'] ) ? sanitize_text_field( $_POST['max_price'] ) : '',
				'category'			=> ! empty( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '',
				'subcategory'		=> ! empty( $_POST['subcategory'] ) ? sanitize_text_field( $_POST['subcategory'] ) : '',
				'tags'				=> ! empty( $_POST['tags'] ) ? sanitize_text_field( $_POST['tags'] ) : '',
				'custom_fields'		=> ! empty( $_POST['custom_fields'] ) ? sanitize_text_field( $_POST['custom_fields'] ) : '',
				'orderby'			=> ! empty( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : '',
				'order'				=> ! empty( $_POST['order'] ) ? strtoupper( sanitize_text_field( $_POST['order'] ) ) : '',
			);
			
			$catalog_view = new ewdupcpViewCatalog( $args );

			$catalog_view->set_variables();

			$catalog_view->set_items();

			$catalog_view->set_pagination_data();

			ob_start();

			$catalog_view->print_view( 'thumbnail' );

			$thumbnail_view = ob_get_contents();

			ob_clean();

			$catalog_view->print_view( 'list' );

			$list_view = ob_get_contents();

			ob_clean();

			$catalog_view->print_view( 'detail' );

			$detail_view = ob_get_clean();

			$filtering_data = $catalog_view->get_filtering_data();

			wp_send_json_success(
				array(
					'request_count'		=> ! empty( $_POST['request_count'] ) ? intval( $_POST['request_count'] ) : 0,
					'thumbnail_view'	=> $thumbnail_view,
					'list_view'			=> $list_view,
					'detail_view'		=> $detail_view,
					'filters'			=> $filtering_data
				)
			);

			die();
		}

		/**
		 * Clear out the inquiry and WC carts, if enabled
		 * @since 5.0.0
		 */
		public function clear_cart() {
			global $ewd_upcp_controller;
			global $woocommerce;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-upcp-js', 'nonce' ) ) {
				ewdupcpHelper::bad_nonce_ajax();
			}

			setcookie( 'upcp_cart_products', '', time() - 3600, '/' );

			if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-checkout' ) ) and ! empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-sync' ) ) and is_object( $woocommerce ) ) {
				
				$woocommerce->cart->empty_cart();
			}

			die();
		}

		/**
		 * Add products to the inquiry and WC carts, if enabled
		 * @since 5.0.0
		 */
		public function add_to_cart() {
			global $ewd_upcp_controller;
			global $woocommerce;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-upcp-js', 'nonce' ) ) {
				ewdupcpHelper::bad_nonce_ajax();
			}

			if ( ! empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-checkout' ) ) and ! empty( $ewd_upcp_controller->settings->get_setting( 'woocommerce-sync' ) ) and is_object( $woocommerce ) ) {

				$woocommerce_id = get_post_meta( intval( $_POST['product_id'] ), 'woocommerce_id', true );

				$woocommerce->cart->add_to_cart( $woocommerce_id );
			}

			$products = ! empty( $_COOKIE['upcp_cart_products'] ) ? explode( ',', sanitize_text_field( $_COOKIE['upcp_cart_products'] ) ) : array();
	
			$products[] = intval( $_POST['product_id'] );

			$products = array_unique( $products );
			
			setcookie( 'upcp_cart_products', implode( ',', $products ), time() + 3600 * 24 * 3, '/' );

			die();
		}
	}
}