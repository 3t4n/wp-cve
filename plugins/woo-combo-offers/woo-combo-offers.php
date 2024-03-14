<?php
/*
 * Plugin Name: Combo Offers WooCommerce
 * Plugin URI: https://quanticedgesolutions.com/?utm-source=free-plugin&utm-medium=wooextend
 * Description: Combo Offers Woocommerce enables administrator to offer combo deals on their product!
 * Version: 3.1
 * Author: QuanticEdge
 * Author URI: https://quanticedgesolutions.com/?utm-source=free-plugin&utm-medium=wooextend
 * Text Domain: woo-combo-offers
 * Domain Path: /languages/
 * WC requires at least: 3.0
 * Tested up to: 6.1.1
 * WC tested up to: 8.4.2
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOCO_VERSION' ) && define( 'WOOCO_VERSION', '3.1' );
! defined( 'WOOCO_URI' ) && define( 'WOOCO_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOCO_REVIEWS' ) && define( 'WOOCO_REVIEWS', 'https://wordpress.org/support/plugin/woo-combo-offers/reviews/?filter=5' );
! defined( 'WOOCO_CHANGELOG' ) && define( 'WOOCO_CHANGELOG', 'https://wordpress.org/plugins/woo-combo-offers/#developers' );
! defined( 'WOOCO_DISCUSSION' ) && define( 'WOOCO_DISCUSSION', 'https://wordpress.org/support/plugin/woo-combo-offers' );

if ( ! function_exists( 'wooco_init' ) ) {
	add_action( 'plugins_loaded', 'wooco_init', 11 );

	function wooco_init() {
		// load text-domain
		load_plugin_textdomain( 'woo-combo-offers', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0.0', '>=' ) ) {
			add_action( 'admin_notices', 'wooco_notice_wc' );

			return;
		}

		if ( ! class_exists( 'WC_Product_Wooco' ) && class_exists( 'WC_Product' ) ) {
			class WC_Product_Wooco extends WC_Product {
				public function __construct( $product = 0 ) {
					$this->supports[] = 'ajax_add_to_cart';
					parent::__construct( $product );
				}

				public function get_type() {
					return 'wooco';
				}

				public function add_to_cart_url() {
					$product_id = $this->id;
					if ( $this->is_purchasable() && $this->is_in_stock() && ! $this->has_variables() && ! $this->is_optional() ) {
						$url = remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product_id ) );
					} else {
						$url = get_permalink( $product_id );
					}

					return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
				}

				public function add_to_cart_text() {
					if ( $this->is_purchasable() && $this->is_in_stock() ) {
						if ( ! $this->has_variables() && ! $this->is_optional() ) {
							$text = get_option( '_wooco_archive_button_add' );
							if ( empty( $text ) ) {
								$text = esc_html__( 'Add to cart', 'woo-combo-offers' );
							}
						} else {
							$text = get_option( '_wooco_archive_button_select' );
							if ( empty( $text ) ) {
								$text = esc_html__( 'Select options', 'woo-combo-offers' );
							}
						}
					} else {
						$text = get_option( '_wooco_archive_button_read' );
						if ( empty( $text ) ) {
							$text = esc_html__( 'Read more', 'woo-combo-offers' );
						}
					}

					return apply_filters( 'wooco_product_add_to_cart_text', $text, $this );
				}

				public function single_add_to_cart_text() {
					$text = get_option( '_wooco_single_button_add' );
					if ( empty( $text ) ) {
						$text = esc_html__( 'Add to cart', 'woo-combo-offers' );
					}

					return apply_filters( 'wooco_product_single_add_to_cart_text', $text, $this );
				}

				public function get_stock_quantity( $context = 'view' ) {
					if ( ( $wooco_items = $this->get_items() ) && ! $this->is_optional() && ! $this->is_manage_stock() ) {
						$available_qty = array();
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_product = wc_get_product( $wooco_item['id'] );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) || ( $wooco_product->get_stock_quantity() === null ) ) {
								continue;
							}
							$available_qty[] = floor( $wooco_product->get_stock_quantity() / $wooco_item['qty'] );
						}
						if ( count( $available_qty ) > 0 ) {
							sort( $available_qty );

							return (int) $available_qty[0];
						}

						return parent::get_stock_quantity( $context );
					}

					return parent::get_stock_quantity( $context );
				}

				public function get_manage_stock( $context = 'view' ) {
					if ( ( $wooco_items = $this->get_items() ) && ! $this->is_optional() && ! $this->is_manage_stock() ) {
						$manage_stock = false;
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_product = wc_get_product( $wooco_item['id'] );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) ) {
								continue;
							}
							if ( $wooco_product->get_manage_stock( $context ) === true ) {
								return true;
							}
						}

						return $manage_stock;
					}

					return parent::get_manage_stock( $context );
				}

				public function get_backorders( $context = 'view' ) {
					if ( ( $wooco_items = $this->get_items() ) && ! $this->is_optional() && ! $this->is_manage_stock() ) {
						$backorders = 'yes';
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_product = wc_get_product( $wooco_item['id'] );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) || ! $wooco_product->get_manage_stock() ) {
								continue;
							}
							if ( $wooco_product->get_backorders( $context ) === 'no' ) {
								return 'no';
							}
							if ( $wooco_product->get_backorders( $context ) === 'notify' ) {
								$backorders = 'notify';
							}
						}

						return $backorders;
					}

					return parent::get_backorders( $context );
				}

				public function get_stock_status( $context = 'view' ) {
					if ( ( $wooco_items = $this->get_items() ) && ! $this->is_optional() && ! $this->is_manage_stock() ) {
						$stock_status = 'instock';
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_product_id = $wooco_item['id'];
							$wooco_product    = wc_get_product( $wooco_product_id );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) || ! $wooco_product->get_manage_stock() ) {
								continue;
							}
							$wooco_product_qty     = $wooco_item['qty'];
							$wooco_product_qty_min = absint( get_post_meta( $wooco_product_id, 'wooco_limit_each_min', true ) ?: 0 );
							$wooco_product_qty_max = absint( get_post_meta( $wooco_product_id, 'wooco_limit_each_max', true ) ?: 1000 );
							if ( $wooco_product_qty < $wooco_product_qty_min ) {
								$wooco_product_qty = $wooco_product_qty_min;
							}
							if ( ( $wooco_product_qty_max > $wooco_product_qty_min ) && ( $wooco_product_qty > $wooco_product_qty_max ) ) {
								$wooco_product_qty = $wooco_product_qty_max;
							}
							if ( ( $wooco_product->get_stock_status( $context ) === 'outofstock' ) || ( ! $wooco_product->has_enough_stock( $wooco_product_qty ) ) ) {
								return 'outofstock';
							}
							if ( $wooco_product->get_stock_status( $context ) === 'onbackorder' ) {
								$stock_status = 'onbackorder';
							}
						}

						return $stock_status;
					}

					return parent::get_stock_status( $context );
				}

				public function get_sold_individually( $context = 'view' ) {
					if ( ( $wooco_items = $this->get_items() ) && ! $this->is_optional() ) {
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_product_id = $wooco_item['id'];
							$wooco_product    = wc_get_product( $wooco_product_id );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) ) {
								continue;
							}
							if ( $wooco_product->is_sold_individually() ) {
								return true;
							}
						}
					}

					return parent::get_sold_individually( $context );
				}

				public function is_on_sale( $context = 'view' ) {
					if ( ! $this->is_fixed_price() && ( $this->get_discount() > 0 ) ) {
						return true;
					}

					return parent::is_on_sale( $context );
				}

				public function get_sale_price( $context = 'view' ) {
					if ( ! $this->is_fixed_price() && ( $this->get_discount() > 0 ) ) {
						return (float) $this->get_regular_price() * ( 100 - $this->get_discount() ) / 100;
					}

					return parent::get_sale_price( $context );
				}

				// extra functions

				public function has_variables() {
					if ( $wooco_items = $this->get_items() ) {
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_item_product = wc_get_product( $wooco_item['id'] );
							if ( $wooco_item_product && $wooco_item_product->is_type( 'variable' ) ) {
								return true;
							}
						}
					}

					return false;
				}

				public function is_optional() {
					$product_id = $this->id;

					return get_post_meta( $product_id, 'wooco_optional_products', true ) === 'on';
				}

				public function is_manage_stock() {
					$product_id = $this->id;

					return get_post_meta( $product_id, 'wooco_manage_stock', true ) === 'on';
				}

				public function is_fixed_price() {
					$product_id = $this->id;

					return get_post_meta( $product_id, 'wooco_disable_auto_price', true ) === 'on';
				}

				public function get_discount() {
					$product_id = $this->id;

					$discount = 0;
					if ( ( $wooco_price_percent = get_post_meta( $product_id, 'wooco_price_percent', true ) ) && is_numeric( $wooco_price_percent ) && ( (float) $wooco_price_percent < 100 ) && ( (float) $wooco_price_percent > 0 ) ) {
						$discount = 100 - (float) $wooco_price_percent;
					}
					if ( ( $wooco_discount = get_post_meta( $product_id, 'wooco_discount', true ) ) && is_numeric( $wooco_discount ) && ( (float) $wooco_discount < 100 ) && ( (float) $wooco_discount > 0 ) ) {
						$discount = (float) $wooco_discount;
					}

					return $discount;
				}

				public function get_items() {
					$product_id = $this->id;
					$wooco_arr  = array();
					if ( $wooco_ids = get_post_meta( $product_id, 'wooco_ids', true ) ) {
						$wooco_items = explode( ',', $wooco_ids );
						if ( is_array( $wooco_items ) && count( $wooco_items ) > 0 ) {
							foreach ( $wooco_items as $wooco_item ) {
								$wooco_item_arr = explode( '/', $wooco_item );
								$wooco_arr[]    = array(
									'id'  => absint( isset( $wooco_item_arr[0] ) ? $wooco_item_arr[0] : 0 ),
									'qty' => absint( isset( $wooco_item_arr[1] ) ? $wooco_item_arr[1] : 1 )
								);
							}
						}
					}
					if ( count( $wooco_arr ) > 0 ) {
						return $wooco_arr;
					}

					return false;
				}
			}
		}

		if ( ! class_exists( 'WooExtendWooco' ) ) {
			class WooExtendWooco {
				function __construct() {
					// Cron jobs auto sync price
					if ( get_option( '_wooco_price_sync', 'no' ) === 'yes' ) {
						add_action( 'wp', array( $this, 'wooco_wp' ) );
						add_filter( 'cron_schedules', array( $this, 'wooco_cron_add_time' ) );
						add_action( 'wooco_cron_jobs', array( $this, 'wooco_cron_jobs_event' ) );
					}
					register_deactivation_hook( __FILE__, array( $this, 'wooco_deactivation' ) );

					// Compatibility for latest woocommerce tables
					add_action('before_woocommerce_init', array($this, 'wooco_declare_wc_features_support'));

					add_shortcode('wooco_list_products', array($this, 'wooco_list_products'), 10, 1);

					// Enqueue frontend scripts
					add_action( 'wp_enqueue_scripts', array( $this, 'wooco_wp_enqueue_scripts' ), 99 );

					// Enqueue backend scripts
					add_action( 'admin_enqueue_scripts', array( $this, 'wooco_admin_enqueue_scripts' ) );

					// Backend AJAX search
					add_action( 'wp_ajax_wooco_get_search_results', array( $this, 'wooco_get_search_results' ) );

					// Backend AJAX update price
					add_action( 'wp_ajax_wooco_update_price', array( $this, 'wooco_update_price_ajax' ) );

					// Add to selector
					add_filter( 'product_type_selector', array( $this, 'wooco_product_type_selector' ) );

					// Product data tabs
					add_filter( 'woocommerce_product_data_tabs', array( $this, 'wooco_product_data_tabs' ), 10, 1 );

					// Product tab
					if ( get_option( '_wooco_bundled_position', 'above' ) === 'tab' ) {
						add_filter( 'woocommerce_product_tabs', array( $this, 'wooco_product_tabs' ) );
					}

					// Restore cart item of combo
					add_action( 'woocommerce_cart_item_restored', array( $this, 'wooco_restore_combo'), 10, 2);

					// Product filters
					add_filter( 'woocommerce_product_filters', array( $this, 'wooco_product_filters' ) );

					// Product data panels
					add_action( 'woocommerce_product_data_panels', array( $this, 'wooco_product_data_panels' ) );
					add_action( 'woocommerce_process_product_meta_wooco', array( $this, 'wooco_save_option_field' ) );

					// Add to cart form & button
					add_action( 'woocommerce_wooco_add_to_cart', array( $this, 'wooco_add_to_cart_form' ) );
					add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'wooco_add_to_cart_button' ) );

					add_filter( 'woocommerce_product_is_in_stock', array( $this, 'wooco_check_stock_status'), 10, 2);

					// Add to cart
					add_filter( 'woocommerce_add_to_cart_validation', array(
						$this,
						'wooco_add_to_cart_validation'
					), 10, 2 );
					add_filter( 'woocommerce_add_cart_item_data', array( $this, 'wooco_add_cart_item_data' ), 10, 2 );
					add_action( 'woocommerce_add_to_cart', array( $this, 'wooco_add_to_cart' ), 10, 6 );
					add_filter( 'woocommerce_get_cart_item_from_session', array(
						$this,
						'wooco_get_cart_item_from_session'
					), 10, 2 );

					// Cart item
					add_filter( 'woocommerce_cart_item_name', array( $this, 'wooco_cart_item_name' ), 10, 2 );
					add_filter( 'woocommerce_cart_item_quantity', array( $this, 'wooco_cart_item_quantity' ), 10, 3 );
					add_filter( 'woocommerce_cart_item_remove_link', array(
						$this,
						'wooco_cart_item_remove_link'
					), 10, 2 );
					add_filter( 'woocommerce_cart_contents_count', array( $this, 'wooco_cart_contents_count' ) );
					add_action( 'woocommerce_after_cart_item_quantity_update', array(
						$this,
						'wooco_update_cart_item_quantity'
					), 1, 2 );
					add_action( 'woocommerce_before_cart_item_quantity_zero', array(
						$this,
						'wooco_update_cart_item_quantity'
					), 1 );
					add_action( 'woocommerce_cart_item_removed', array( $this, 'wooco_cart_item_removed' ), 10, 2 );
					add_filter( 'woocommerce_cart_item_price', array( $this, 'wooco_cart_item_price' ), 10, 2 );
					add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'wooco_cart_item_subtotal' ), 10, 2 );

					// Hide on cart & checkout page
					if ( get_option( '_wooco_hide_bundled', 'no' ) !== 'no' ) {
						add_filter( 'woocommerce_cart_item_visible', array( $this, 'wooco_item_visible' ), 10, 2 );
						add_filter( 'woocommerce_order_item_visible', array( $this, 'wooco_item_visible' ), 10, 2 );
						add_filter( 'woocommerce_checkout_cart_item_visible', array(
							$this,
							'wooco_item_visible'
						), 10, 2 );
					}

					// Hide on mini-cart
					if ( get_option( '_wooco_hide_bundled_mini_cart', 'no' ) === 'yes' ) {
						add_filter( 'woocommerce_widget_cart_item_visible', array(
							$this,
							'wooco_item_visible'
						), 10, 2 );
					}

					// Item class
					if ( get_option( '_wooco_hide_bundled', 'no' ) !== 'yes' ) {
						add_filter( 'woocommerce_cart_item_class', array( $this, 'wooco_item_class' ), 10, 2 );
						add_filter( 'woocommerce_mini_cart_item_class', array( $this, 'wooco_item_class' ), 10, 2 );
						add_filter( 'woocommerce_order_item_class', array( $this, 'wooco_item_class' ), 10, 2 );
					}

					// Get item data
					if ( get_option( '_wooco_hide_bundled', 'no' ) === 'yes_text' ) {
						add_filter( 'woocommerce_get_item_data', array(
							$this,
							'wooco_get_item_data'
						), 10, 2 );
						add_action( 'woocommerce_checkout_create_order_line_item', array(
							$this,
							'wooco_checkout_create_order_line_item'
						), 10, 4 );
					}

					// Order item
					add_action( 'woocommerce_checkout_create_order_line_item', array(
						$this,
						'wooco_add_order_item_meta'
					), 10, 3 );
					add_filter( 'woocommerce_order_item_name', array( $this, 'wooco_cart_item_name' ), 10, 2 );
					add_filter( 'woocommerce_order_formatted_line_subtotal', array(
						$this,
						'wooco_order_formatted_line_subtotal'
					), 10, 2 );

					// Admin order
					add_filter( 'woocommerce_hidden_order_itemmeta', array(
						$this,
						'wooco_hidden_order_item_meta'
					), 10, 1 );
					add_action( 'woocommerce_before_order_itemmeta', array(
						$this,
						'wooco_before_order_item_meta'
					), 10, 1 );

					// Add settings link
					add_filter( 'plugin_action_links', array( $this, 'wooco_action_links' ), 10, 2 );
					add_filter( 'plugin_row_meta', array( $this, 'wooco_row_meta' ), 10, 2 );

					// Loop add-to-cart
					add_filter( 'woocommerce_loop_add_to_cart_link', array(
						$this,
						'wooco_loop_add_to_cart_link'
					), 10, 2 );

					// Calculate totals
					add_action( 'woocommerce_before_calculate_totals', array(
						$this,
						'wooco_before_calculate_totals'
					), 10, 1 );
					add_action( 'woocommerce_calculate_totals', array( $this, 'wooco_calculate_totals' ), 10, 1 );

					// Shipping
					add_filter( 'woocommerce_cart_shipping_packages', array(
						$this,
						'wooco_cart_shipping_packages'
					) );

					// Price html
					add_filter( 'woocommerce_get_price_html', array( $this, 'wooco_get_price_html' ), 99, 2 );

					// Order again
					add_filter( 'woocommerce_order_again_cart_item_data', array(
						$this,
						'wooco_order_again_cart_item_data'
					), 99, 3 );
					add_action( 'woocommerce_cart_loaded_from_session', array(
						$this,
						'wooco_cart_loaded_from_session'
					) );

					// Metabox
					if ( get_option( '_wooco_price_update', 'no' ) === 'yes' ) {
						add_action( 'add_meta_boxes', array( $this, 'wooco_meta_boxes' ) );
						add_action( 'wp_ajax_wooco_metabox_update_price', array(
							$this,
							'wooco_metabox_update_price_ajax'
						) );
					}

					// Search filters
					if ( get_option( '_wooco_search_sku', 'no' ) === 'yes' ) {
						add_filter( 'pre_get_posts', array( $this, 'wooco_search_sku' ), 99 );
					}
					if ( get_option( '_wooco_search_exact', 'no' ) === 'yes' ) {
						add_action( 'pre_get_posts', array( $this, 'wooco_search_exact' ), 99 );
					}
					if ( get_option( '_wooco_search_sentence', 'no' ) === 'yes' ) {
						add_action( 'pre_get_posts', array( $this, 'wooco_search_sentence' ), 99 );
					}

					add_action('woocommerce_before_cart', array( $this, 'wooco_fix_abandoned_child'));
				}

				function wooco_declare_wc_features_support() {
				    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'woo-combo-offers/woo-combo-offers.php' );
				    }
				}

				function wooco_fix_abandoned_child() {

					foreach (WC()->cart->get_cart_contents() as $key => $value) {
						
						// lets make sure its wooco product
						if(isset($value['wooco_parent_key']) && !empty($value['wooco_parent_key'])) {

							if(!isset(WC()->cart->get_cart_contents()[$value['wooco_parent_key']])) {
								
								WC()->cart->remove_cart_item($key);
							}
						}
					}
				}

				function wooco_restore_combo( $cart_item_key, $instance ) {

					foreach (WC()->cart->get_cart_contents() as $key => $value) {
						
						if($key == $cart_item_key && isset($value['wooco_keys']) && !empty($value['wooco_keys'])) {
							
							foreach ($value['wooco_keys'] as $key_inner => $value_inner) {
								
								WC()->cart->restore_cart_item( $value_inner );
							}
						}
					}					
				}

				function wooco_list_products( $atts ) {

					global $wpdb;

					$args = array(
					    'post_type' => 'product',
					    'post_status' => 'publish',
					    'posts_per_page' => -1,
					    'tax_query' => array(
					        array(
					            'taxonomy' => 'product_type',
					            'field' => 'slug',
					            'terms' => 'wooco'
					        )
					    ),
					    'fields'	=>	'ids'
					);
					$the_query = new WP_Query( $args );

					if(isset($atts['columns']) &&!empty($atts['columns'])) {
						$columns = $atts['columns'];
					} else {
						$columns = 3;
					}
					if(isset($the_query->posts) && !empty($the_query->posts) && is_array($the_query->posts)) {

						$str_ids = implode(',', $the_query->posts);
						ob_start();

						echo do_shortcode("[products ids=" . $str_ids . " columns=" . $columns . "]");
						return ob_get_clean();
					}
					return '';
				}

				function wooco_wp() {
					if ( ! wp_next_scheduled( 'wooco_cron_jobs' ) ) {
						wp_schedule_event( time(), 'wooco_time', 'wooco_cron_jobs' );
					}
				}

				function wooco_cron_add_time( $schedules ) {
					$schedules['wooco_time'] = array(
						'interval' => 300,
						'display'  => esc_html__( 'Once Every 5 Minutes', 'woo-combo-offers' )
					);

					return $schedules;
				}

				function wooco_cron_jobs_event() {
					$this->wooco_update_price();
				}

				function wooco_update_price( $all = false, $num = - 1, $ajax = false ) {
					$count = 0;
					$time  = time() - 300;
					if ( $all ) {
						$wooco_query_args = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => $num,
							'tax_query'      => array(
								array(
									'taxonomy' => 'product_type',
									'field'    => 'slug',
									'terms'    => array( 'wooco' ),
									'operator' => 'IN',
								)
							)
						);
					} else {
						$wooco_query_args = array(
							'post_type'      => 'product',
							'post_status'    => 'publish',
							'posts_per_page' => $num,
							'tax_query'      => array(
								array(
									'taxonomy' => 'product_type',
									'field'    => 'slug',
									'terms'    => array( 'wooco' ),
									'operator' => 'IN',
								)
							),
							'meta_query'     => array(
								'relation' => 'OR',
								array(
									'key'     => 'wooco_update_price',
									'value'   => '',
									'compare' => 'NOT EXISTS',
								),
								array(
									'key'     => 'wooco_update_price',
									'value'   => $time,
									'compare' => '<=',
								)
							)
						);
					}
					$wooco_query = new WP_Query( $wooco_query_args );
					if ( $wooco_query->have_posts() ) {
						while ( $wooco_query->have_posts() ) {
							$wooco_query->the_post();
							$product_id = get_the_ID();

							// update time
							update_post_meta( $product_id, 'wooco_update_price', time() );
							$this->wooco_update_price_for_id( $product_id );

							$count ++;
						}
						wp_reset_postdata();
					}
					if ( $ajax ) {
						echo esc_attr($count);
					}
				}

				function wooco_check_stock_status( $status, $product ) {

					if($product->get_type() != 'wooco') {
						return $status;
					}
					$product_meta = get_post_meta($product->get_id(), 'wooco_ids', true);
					$wooco_ids = preg_replace( '/[^,\/0-9]/', '', $product_meta );

					if ( ! empty( $wooco_ids ) ) {
						$wooco_items = explode( ',', $wooco_ids );
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_item_data = explode( '/', $wooco_item );
							$wooco_item_id   = absint( $wooco_item_data[0] ?: 0 );
							$wooco_product   = wc_get_product( $wooco_item_id );
							if(!is_bool($wooco_product) && ($wooco_product->get_type() == 'simple' || $wooco_product->get_type() == 'variation')) {
								if(!$wooco_product->is_in_stock()) {
									return false;
								}
							}
							if(!is_bool($wooco_product) && $wooco_product->get_type() == 'variable') {
								if(count($wooco_product->get_available_variations()) == 0) {
									return false;
								}
							}
						}
					}
					return $status;
				}

				function wooco_update_price_for_id( $product_id ) {
					$product = wc_get_product( $product_id );
					if ( $product && $product->is_type( 'wooco' ) && ! $product->is_fixed_price() ) {
						// only update for auto price
						$regular_price = 0;
						$sale_price    = 0;

						$wooco_items = $this->wooco_get_items( $product_id );

						// calc regular price
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_product = wc_get_product( $wooco_item['id'] );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) ) {
								continue;
							}
							$regular_price += $wooco_product->get_price() * $wooco_item['qty'];
						}

						// calc sale price
						if ( ( $discount = $product->get_discount() ) > 0 ) {
							$sale_price = $regular_price * ( 100 - $discount ) / 100;
						}

						// update prices
						update_post_meta( $product_id, '_regular_price', $regular_price );
						if ( ( $sale_price > 0 ) && ( $sale_price < $regular_price ) ) {
							update_post_meta( $product_id, '_sale_price', $sale_price );
							update_post_meta( $product_id, '_price', $sale_price );
						} else {
							update_post_meta( $product_id, '_sale_price', '' );
							update_post_meta( $product_id, '_price', $regular_price );
						}
					}
				}

				function wooco_update_price_ajax() {
					$this->wooco_update_price( false, 100, true );
					die();
				}

				function wooco_metabox_update_price_ajax() {
					$count            = isset( $_POST['count'] ) ? (int) $_POST['count'] : 0;
					$product_id       = isset( $_POST['product_id'] ) ? (int) $_POST['product_id'] : 0;
					$product_id_str   = $product_id . '/';
					$wooco_query_args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => 1,
						'offset'         => $count,
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_type',
								'field'    => 'slug',
								'terms'    => array( 'wooco' ),
								'operator' => 'IN',
							)
						),
						'meta_query'     => array(
							array(
								'key'     => 'wooco_ids',
								'value'   => $product_id_str,
								'compare' => 'LIKE',
							)
						)
					);
					$wooco_query      = new WP_Query( $wooco_query_args );
					if ( $wooco_query->have_posts() ) {
						while ( $wooco_query->have_posts() ) {
							$wooco_query->the_post();
							$this->wooco_update_price_for_id( get_the_ID() );
							echo '<li><a href="' . get_permalink() . '" target="_blank">' . get_the_title() . '</a></li>';
						}
					} else {
						echo '0';
					}
					die();
				}

				function wooco_wp_enqueue_scripts() {
					wp_enqueue_style( 'wooco-frontend', WOOCO_URI . 'assets/css/frontend.css' );
					wp_enqueue_script( 'wooco-frontend', WOOCO_URI . 'assets/js/frontend.js', array( 'jquery' ), WOOCO_VERSION, true );
					wp_localize_script( 'wooco-frontend', 'wooco_vars', array(
							'alert_selection'          => esc_html__( 'Please select some product options before adding this combo to the cart.', 'woo-combo-offers' ),
							'alert_empty'              => esc_html__( 'Please choose at least one product before adding this combo to the cart.', 'woo-combo-offers' ),
							'alert_min'                => esc_html__( 'Please choose at least [min] in the whole products before adding this combo to the cart.', 'woo-combo-offers' ),
							'alert_max'                => esc_html__( 'Please choose maximum [max] in the whole products before adding this combo to the cart.', 'woo-combo-offers' ),
							'price_text'               => get_option( '_wooco_bundle_price_text', '' ),
							'change_image'             => get_option( '_wooco_change_image', 'yes' ),
							'price_format'             => get_woocommerce_price_format(),
							'price_decimals'           => wc_get_price_decimals(),
							'price_thousand_separator' => wc_get_price_thousand_separator(),
							'price_decimal_separator'  => wc_get_price_decimal_separator(),
							'price_saved'              => esc_html__( 'saved', 'woo-combo-offers' ),
							'currency_symbol'          => get_woocommerce_currency_symbol(),
							'ver'                      => WOOCO_VERSION
						)
					);
				}

				function wooco_admin_enqueue_scripts() {
					wp_enqueue_style( 'wooco-hint', esc_url( WOOCO_URI . 'assets/css/hint.css' ) );
					wp_enqueue_style( 'wooco-backend', esc_url( WOOCO_URI . 'assets/css/backend.css' ));
					wp_enqueue_script( 'wooco-dragarrange', esc_url( WOOCO_URI . 'assets/js/drag-arrange.js' ), array( 'jquery' ), WOOCO_VERSION, true );
					wp_enqueue_script( 'wooco-accounting', esc_url( WOOCO_URI . 'assets/js/accounting.js' ), array( 'jquery' ), WOOCO_VERSION, true );
					wp_enqueue_script( 'wooco-backend', esc_url( WOOCO_URI . 'assets/js/backend.js' ), array( 'jquery' ), WOOCO_VERSION, true );
					wp_localize_script( 'wooco-backend', 'wooco_vars', array(
							'price_decimals'           => wc_get_price_decimals(),
							'price_thousand_separator' => wc_get_price_thousand_separator(),
							'price_decimal_separator'  => wc_get_price_decimal_separator()
						)
					);
				}

				function wooco_action_links( $links, $file ) {
					static $plugin;
					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}
					if ( $plugin === $file ) {
						$links[]       = '<a href="https://www.wooextend.com/product/woocommerce-combo-offers-pro/">' . esc_html__( 'Premium Version', 'woo-combo-offers' ) . '</a>';
					}

					return (array) $links;
				}

				function wooco_row_meta( $links, $file ) {
					static $plugin;
					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}
					if ( $plugin === $file ) {
						$row_meta = array(	
							'expert' => '<a href="https://www.wooextend.com/about-me/" target="_blank">' . esc_html__( 'Woocommerce Expert', 'woo-combo-offers' ) . '</a>',
						);

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function wooco_cart_contents_count( $count ) {
					$cart_contents_count = get_option( '_wooco_cart_contents_count', 'bundle' );

					if ( $cart_contents_count !== 'both' ) {
						$cart_contents = WC()->cart->cart_contents;
						foreach ( $cart_contents as $cart_item_key => $cart_item ) {
							if ( ( $cart_contents_count === 'bundled_products' ) && ! empty( $cart_item['wooco_ids'] ) ) {
								$count -= $cart_item['quantity'];
							}
							if ( ( $cart_contents_count === 'bundle' ) && ! empty( $cart_item['wooco_parent_id'] ) ) {
								$count -= $cart_item['quantity'];
							}
						}
					}

					return $count;
				}

				function wooco_cart_item_name( $name, $item ) {
					if ( isset( $item['wooco_parent_id'] ) && ! empty( $item['wooco_parent_id'] ) && ( get_option( '_wooco_hide_bundle_name', 'no' ) === 'no' ) ) {
						if ( ( strpos( $name, '</a>' ) !== false ) && ( get_option( '_wooco_bundled_link', 'yes' ) !== 'no' ) ) {
							return '<a href="' . get_permalink( $item['wooco_parent_id'] ) . '">' . get_the_title( $item['wooco_parent_id'] ) . '</a> &rarr; ' . $name;
						} else {
							return get_the_title( $item['wooco_parent_id'] ) . ' &rarr; ' . strip_tags( $name );
						}
					} else {
						return $name;
					}
				}

				function wooco_update_cart_item_quantity( $cart_item_key, $quantity = 0 ) {
					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['wooco_keys'] ) ) {
						foreach ( WC()->cart->cart_contents[ $cart_item_key ]['wooco_keys'] as $wooco_key ) {
							if ( isset( WC()->cart->cart_contents[ $wooco_key ] ) ) {
								if ( $quantity <= 0 ) {
									$wooco_qty = 0;
								} else {
									$wooco_qty = $quantity * ( WC()->cart->cart_contents[ $wooco_key ]['wooco_qty'] ?: 1 );
								}
								WC()->cart->set_quantity( $wooco_key, $wooco_qty, false );
							}
						}
					}
				}

				function wooco_cart_item_removed( $cart_item_key, $cart ) {
					if ( isset( $cart->removed_cart_contents[ $cart_item_key ]['wooco_keys'] ) ) {
						$wooco_keys = $cart->removed_cart_contents[ $cart_item_key ]['wooco_keys'];
						foreach ( $wooco_keys as $wooco_key ) {
							WC()->cart->remove_cart_item( $wooco_key );
						}
					}
				}

				function wooco_check_in_cart( $product_id ) {
					foreach ( WC()->cart->get_cart() as $cart_item ) {
						if ( $cart_item['product_id'] === $product_id ) {
							return true;
						}
					}

					return false;
				}

				function wooco_add_to_cart_validation( $passed, $product_id ) {
					if ( $wooco_ids = get_post_meta( $product_id, 'wooco_ids', true ) ) {
						if ( isset( $_POST['wooco_ids'] ) ) {
							$wooco_ids = sanitize_text_field( $_POST['wooco_ids'] );
						}

						$wooco_ids = $this->wooco_clean_ids( $wooco_ids );
						$wooco_items = explode( ',', $wooco_ids );
						if(!is_null(WC()->cart)) {
							$arr_cart_quantities = WC()->cart->get_cart_item_quantities();

							foreach ($wooco_items as $key => $value) {
								list($prod_id, $qty) = explode('/', $value);

								// Lets check if user has already some quantity in cart
								if(isset($arr_cart_quantities[$prod_id]) && !empty($arr_cart_quantities[$prod_id])) {

									$manage_stock = get_post_meta( $prod_id, '_manage_stock', true);
									$backorders = get_post_meta( $prod_id, '_backorders', true);
									if($manage_stock == 'yes' && $backorders == 'no') {

										$stock = intval(get_post_meta( $prod_id, '_stock', true));
										$final_stock = intval($arr_cart_quantities[$prod_id]) + intval($qty);
										if($final_stock > $stock) {
											wc_add_notice( esc_html__( 'Some of the combo items are already in cart. We have insufficient stock for it and can\'t add to cart.', 'woo-combo-offers' ), 'error' );
											return false;
										}
									}
								}
							}

						}
						if ( ! empty( $wooco_ids ) ) {
							$wooco_items = explode( ',', $wooco_ids );
							foreach ( $wooco_items as $wooco_item ) {
								$wooco_item_data = explode( '/', $wooco_item );
								$wooco_item_id   = absint( $wooco_item_data[0] ?: 0 );
								$wooco_product   = wc_get_product( $wooco_item_id );

								if ( ! $wooco_product || ! $wooco_product->is_in_stock() || ! $wooco_product->is_purchasable() ) {
									$passed = false;
									wc_add_notice( esc_html__( 'Have an error when adding this combo to the cart.', 'woo-combo-offers' ), 'error' );
								}

								if ( is_object($wooco_product) && $wooco_product->is_sold_individually() && $this->wooco_check_in_cart( $wooco_item_id ) ) {
									$passed = false;
									wc_add_notice( sprintf( esc_html__( 'You cannot add another "%s" to your cart.', 'woo-combo-offers' ), esc_html( $wooco_product->get_name() ) ), 'error' );
									wc_add_notice( esc_html__( 'You cannot add this combo to your cart.', 'woo-combo-offers' ), 'error' );
								}

								if ( post_password_required( $wooco_item_id ) ) {
									$passed = false;
									wc_add_notice( sprintf( esc_html__( '"%s" is protected and cannot be purchased.', 'woo-combo-offers' ), esc_html( $wooco_product->get_name() ) ), 'error' );
									wc_add_notice( esc_html__( 'You cannot add this combo to your cart.', 'woo-combo-offers' ), 'error' );
								}
							}
						}
					}

					return $passed;
				}

				function wooco_add_cart_item_data( $cart_item_data, $product_id ) {
					if ( $wooco_ids = get_post_meta( $product_id, 'wooco_ids', true ) ) {
						// make sure that is combo
						if ( isset( $_POST['wooco_ids'] ) ) {
							$wooco_ids = sanitize_text_field( $_POST['wooco_ids'] );
							unset( $_POST['wooco_ids'] );
						}

						$wooco_ids = $this->wooco_clean_ids( $wooco_ids );
						if ( ! empty( $wooco_ids ) ) {
							$cart_item_data['wooco_ids'] = $wooco_ids;
						}
					}

					return $cart_item_data;
				}

				function wooco_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
					if ( ! empty( $cart_item_data['wooco_ids'] ) && method_exists( WC()->cart->cart_contents[ $cart_item_key ]['data'], 'is_fixed_price' ) && method_exists( WC()->cart->cart_contents[ $cart_item_key ]['data'], 'get_discount' ) ) {
						$wooco_fixed_price  = WC()->cart->cart_contents[ $cart_item_key ]['data']->is_fixed_price();
						$wooco_get_discount = WC()->cart->cart_contents[ $cart_item_key ]['data']->get_discount();

						WC()->cart->cart_contents[ $cart_item_key ]['wooco_fixed_price']  = $wooco_fixed_price;
						WC()->cart->cart_contents[ $cart_item_key ]['wooco_get_discount'] = $wooco_get_discount;

						$items = explode( ',', $cart_item_data['wooco_ids'] );

						if ( is_array( $items ) && ( count( $items ) > 0 ) ) {
							$wooco_i = 0; // for same combo product
							foreach ( $items as $item ) {
								$wooco_i ++;
								$wooco_item     = explode( '/', $item );
								$wooco_item_id  = absint( isset( $wooco_item[0] ) ? $wooco_item[0] : 0 );
								$wooco_item_qty = absint( isset( $wooco_item[1] ) ? $wooco_item[1] : 1 );

								$wooco_item_product = wc_get_product( $wooco_item_id );

								if ( ! $wooco_item_product || ( $wooco_item_qty <= 0 ) ) {
									continue;
								}

								$wooco_item_price = $wooco_item_product->get_price();

								$wooco_item_variation_id = 0;
								$wooco_item_variation    = array();

								if ( 'product_variation' === get_post_type( $wooco_item_id ) ) {
									// ensure we don't add a variation to the cart directly by variation ID
									$wooco_item_variation_id = $wooco_item_id;
									$wooco_item_id           = wp_get_post_parent_id( $wooco_item_variation_id );
									$wooco_item_variation    = $wooco_item_product->get_variation_attributes();
								}

								if ( ! $wooco_fixed_price && ( $wooco_get_discount > 0 ) ) {
									$wooco_item_price *= (float) ( 100 - $wooco_get_discount ) / 100;
									$wooco_item_price = round( $wooco_item_price, wc_get_price_decimals() );
								}

								// add to cart
								$wooco_product_qty = $wooco_item_qty * $quantity;
								$wooco_item_data   = array(
									'wooco_pos'          => $wooco_i,
									'wooco_qty'          => $wooco_item_qty,
									'wooco_price'        => $wooco_item_price,
									'wooco_parent_id'    => $product_id,
									'wooco_parent_key'   => $cart_item_key,
									'wooco_fixed_price'  => $wooco_fixed_price,
									'wooco_get_discount' => $wooco_get_discount
								);
								$wooco_cart_id     = WC()->cart->generate_cart_id( $wooco_item_id, $wooco_item_variation_id, $wooco_item_variation, $wooco_item_data );
								$wooco_item_key    = WC()->cart->find_product_in_cart( $wooco_cart_id );
								if ( empty( $wooco_item_key ) ) {
									$wooco_item_key = WC()->cart->add_to_cart( $wooco_item_id, $wooco_product_qty, $wooco_item_variation_id, $wooco_item_variation, $wooco_item_data );
								}

								// add keys
								if ( ! empty( $wooco_item_key ) && ( ! isset( WC()->cart->cart_contents[ $cart_item_key ]['wooco_keys'] ) || ! in_array( $wooco_item_key, WC()->cart->cart_contents[ $cart_item_key ]['wooco_keys'], true ) ) ) {
									WC()->cart->cart_contents[ $cart_item_key ]['wooco_keys'][] = $wooco_item_key;
								}
							} // end foreach
						}
					}
				}

				function wooco_get_cart_item_from_session( $cart_item, $item_session_values ) {
					if ( isset( $item_session_values['wooco_ids'] ) && ! empty( $item_session_values['wooco_ids'] ) ) {
						$cart_item['wooco_ids'] = $item_session_values['wooco_ids'];
					}
					if ( isset( $item_session_values['wooco_parent_id'] ) ) {
						$cart_item['wooco_parent_id']  = $item_session_values['wooco_parent_id'];
						$cart_item['wooco_parent_key'] = $item_session_values['wooco_parent_key'];
						$cart_item['wooco_qty']        = $item_session_values['wooco_qty'];
					}

					return $cart_item;
				}

				function wooco_before_calculate_totals( $cart_object ) {
					if ( ! defined( 'DOING_AJAX' ) && is_admin() ) {
						// This is necessary for WC 3.0+
						return;
					}

					foreach ( $cart_object->get_cart() as $cart_item_key => $cart_item ) {
						// combo product price
						if ( ! empty( $cart_item['wooco_parent_id'] ) ) {
							if ( isset( $cart_item['wooco_fixed_price'] ) && $cart_item['wooco_fixed_price'] ) {
								$cart_item['data']->set_price( 0 );
							} elseif ( isset( $cart_item['wooco_price'], $cart_item['wooco_get_discount'] ) && ( $cart_item['wooco_get_discount'] > 0 ) ) {
								$cart_item['data']->set_price( $cart_item['wooco_price'] );
							}
						}

						// combo price
						if ( ! empty( $cart_item['wooco_ids'] ) && isset( $cart_item['wooco_fixed_price'] ) && ! $cart_item['wooco_fixed_price'] ) {
							// set price zero, calculate after
							$cart_item['data']->set_price( 0 );
						}
					}
				}

				function wooco_calculate_totals( $cart_object ) {
					$cart_items = $cart_object->get_cart();
					foreach ( $cart_items as $cart_item_key => $cart_item ) {
						if ( ! empty( $cart_item['wooco_ids'] ) && ! empty( $cart_item['wooco_keys'] ) && isset( $cart_item['wooco_fixed_price'] ) && ! $cart_item['wooco_fixed_price'] ) {
							// only calculate for auto price
							$bundle_price = 0;
							foreach ( $cart_item['wooco_keys'] as $wooco_key ) {
								if ( isset( $cart_items[ $wooco_key ] ) ) {
									if (  ( version_compare( WC_VERSION, '4.4', '<' ) ) ? $cart_object->tax_display_cart === 'incl' : $cart_object->get_tax_price_display_mode() === 'incl' ) {
										$bundle_item_price = $cart_items[ $wooco_key ]['line_subtotal'] + wc_round_tax_total( $cart_items[ $wooco_key ]['line_subtotal_tax'] );
									} else {
										$bundle_item_price = $cart_items[ $wooco_key ]['line_subtotal'];
									}

									$bundle_price += round( $bundle_item_price, wc_get_price_decimals() );
								}
							}
							WC()->cart->cart_contents[ $cart_item_key ]['wooco_price'] = $bundle_price / $cart_item['quantity'];
						}
					}
				}

				function wooco_cart_item_price( $price, $cart_item ) {
					if ( isset( $cart_item['wooco_ids'], $cart_item['wooco_price'], $cart_item['wooco_fixed_price'] ) && ! $cart_item['wooco_fixed_price'] ) {
						return wc_price( $cart_item['wooco_price'] );
					}

					if ( isset( $cart_item['wooco_parent_id'], $cart_item['wooco_price'], $cart_item['wooco_fixed_price'] ) && $cart_item['wooco_fixed_price'] ) {
						return wc_price( $cart_item['wooco_price'] );
					}

					return $price;
				}

				function wooco_cart_item_subtotal( $subtotal, $cart_item = null ) {
					if ( isset( $cart_item['wooco_ids'], $cart_item['wooco_price'], $cart_item['wooco_fixed_price'] ) && ! $cart_item['wooco_fixed_price'] ) {
						return wc_price( $cart_item['wooco_price'] * $cart_item['quantity'] );
					}

					if ( isset( $cart_item['wooco_parent_id'], $cart_item['wooco_price'], $cart_item['wooco_fixed_price'] ) && $cart_item['wooco_fixed_price'] ) {
						return wc_price( $cart_item['wooco_price'] * $cart_item['quantity'] );
					}

					return $subtotal;
				}

				function wooco_item_visible( $visible, $item ) {
					if ( isset( $item['wooco_parent_id'] ) ) {
						return false;
					}

					return $visible;
				}

				function wooco_item_class( $class, $item ) {
					if ( isset( $item['wooco_parent_id'] ) ) {
						$class .= ' wooco-cart-item wooco-cart-child wooco-item-child';
					} elseif ( isset( $item['wooco_ids'] ) ) {
						$class .= ' wooco-cart-item wooco-cart-parent wooco-item-parent';
					}

					return $class;
				}

				function wooco_get_item_data( $item_data, $cart_item ) {
					if ( empty( $cart_item['wooco_ids'] ) ) {
						return $item_data;
					}

					$wooco_items     = explode( ',', $cart_item['wooco_ids'] );
					$wooco_items_str = '';
					if ( is_array( $wooco_items ) && count( $wooco_items ) > 0 ) {
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_item_arr  = explode( '/', $wooco_item );
							$wooco_item_id   = absint( isset( $wooco_item_arr[0] ) ? $wooco_item_arr[0] : 0 );
							$wooco_item_qty  = absint( isset( $wooco_item_arr[1] ) ? $wooco_item_arr[1] : 1 );
							$wooco_items_str .= $wooco_item_qty . ' × ' . get_the_title( $wooco_item_id ) . '; ';
						}
					}
					$wooco_items_str = trim( $wooco_items_str, '; ' );
					$item_data[]     = array(
						'key'     => esc_html__( 'Bundled products', 'woo-combo-offers' ),
						'value'   => $wooco_items_str,
						'display' => '',
					);

					return $item_data;
				}

				function wooco_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
					if ( empty( $values['wooco_ids'] ) ) {
						return;
					}
					$wooco_items     = explode( ',', $values['wooco_ids'] );
					$wooco_items_str = '';
					if ( is_array( $wooco_items ) && count( $wooco_items ) > 0 ) {
						foreach ( $wooco_items as $wooco_item ) {
							$wooco_item_arr  = explode( '/', $wooco_item );
							$wooco_item_id   = absint( isset( $wooco_item_arr[0] ) ? $wooco_item_arr[0] : 0 );
							$wooco_item_qty  = absint( isset( $wooco_item_arr[1] ) ? $wooco_item_arr[1] : 1 );
							$wooco_items_str .= $wooco_item_qty . ' × ' . get_the_title( $wooco_item_id ) . '; ';
						}
					}
					$wooco_items_str = trim( $wooco_items_str, '; ' );
					$item->add_meta_data( esc_html__( 'Bundled products', 'woo-combo-offers' ), $wooco_items_str );
				}

				function wooco_add_order_item_meta( $item, $cart_item_key, $values ) {
					if ( isset( $values['wooco_parent_id'] ) ) {
						// use _ to hide the data
						$item->update_meta_data( '_wooco_parent_id', $values['wooco_parent_id'] );
					}
					if ( isset( $values['wooco_ids'] ) ) {
						// use _ to hide the data
						$item->update_meta_data( '_wooco_ids', $values['wooco_ids'] );
					}
					if ( isset( $values['wooco_price'] ) ) {
						// use _ to hide the data
						$item->update_meta_data( '_wooco_price', $values['wooco_price'] );
					}
				}

				function wooco_hidden_order_item_meta( $hidden ) {
					return array_merge( $hidden, array(
						'_wooco_parent_id',
						'_wooco_ids',
						'_wooco_price',
						'wooco_parent_id',
						'wooco_ids',
						'wooco_price'
					) );
				}

				function wooco_before_order_item_meta( $item_id ) {
					if ( $wooco_parent_id = wc_get_order_item_meta( $item_id, '_wooco_parent_id', true ) ) {
						echo sprintf( esc_html__( '(combo in %s)', 'woo-combo-offers' ), get_the_title( $wooco_parent_id ) );
					}
				}

				function wooco_order_formatted_line_subtotal( $subtotal, $item ) {
					if ( isset( $item['_wooco_parent_id'] ) ) {
						return '';
					} elseif ( isset( $item['_wooco_ids'], $item['_wooco_price'] ) ) {
						return wc_price( $item['_wooco_price'] * $item['quantity'] );
					}

					return $subtotal;
				}

				function wooco_cart_item_remove_link( $link, $cart_item_key ) {
					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['wooco_parent_id'] ) ) {
						return '';
					}

					return $link;
				}

				function wooco_cart_item_quantity( $quantity, $cart_item_key, $cart_item ) {
					// add qty as text - not input
					if ( isset( $cart_item['wooco_parent_id'] ) ) {
						return $cart_item['quantity'];
					}

					return $quantity;
				}

				function wooco_get_search_results() {
					$keyword     = sanitize_text_field( $_POST['keyword'] );
					$ids         = $this->wooco_clean_ids( $_POST['ids'] );
					$exclude_ids = array();
					$ids_arrs    = explode( ',', $ids );

					if ( is_array( $ids_arrs ) && count( $ids_arrs ) > 2 ) {
						esc_html('<ul><span>Please use the Premium Version to add more than 3 products to the combo. Click <a href="https://www.wooextend.com/product/woocommerce-combo-offers-pro/" target="_blank">here</a> to buy, just <del>$30</del> $25!</span></ul>');
						die();
					}

					$wooco_query_args = array(
						'is_wooco'       => true,
						'post_type'      => 'product',
						'post_status'    => array( 'publish', 'private' ),
						's'              => $keyword,
						'posts_per_page' => get_option( '_wooco_search_limit', '5' ),
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_type',
								'field'    => 'slug',
								'terms'    => array( 'wooco' ),
								'operator' => 'NOT IN',
							)
						)
					);
					if ( get_option( '_wooco_search_same', 'no' ) !== 'yes' ) {
						if ( is_array( $ids_arrs ) && count( $ids_arrs ) > 0 ) {
							foreach ( $ids_arrs as $ids_arr ) {
								$ids_arr_new   = explode( '/', $ids_arr );
								$exclude_ids[] = absint( isset( $ids_arr_new[0] ) ? $ids_arr_new[0] : 0 );
							}
						}
						$wooco_query_args['post__not_in'] = $exclude_ids;
					}
					$wooco_query = new WP_Query( $wooco_query_args );
					if ( $wooco_query->have_posts() ) {
						echo '<ul>';
						while ( $wooco_query->have_posts() ) {
							$wooco_query->the_post();
							$wooco_product = wc_get_product( get_the_ID() );
							if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) ) {
								continue;
							}
							$this->wooco_product_data_li( $wooco_product, 1, true );
							if ( $wooco_product->is_type( 'variable' ) ) {
								// show all childs
								$wooco_childs = $wooco_product->get_children();
								if ( is_array( $wooco_childs ) && count( $wooco_childs ) > 0 ) {
									foreach ( $wooco_childs as $wooco_child ) {
										$wooco_product_child = wc_get_product( $wooco_child );
										$this->wooco_product_data_li( $wooco_product_child, 1, true );
									}
								}
							}
						}
						echo '</ul>';
						wp_reset_postdata();
					} else {
						echo '<ul><span>' . sprintf( esc_html__( 'No results found for "%s"', 'woo-combo-offers' ), $keyword ) . '</span></ul>';
					}
					die();
				}

				function wooco_meta_boxes() {
					add_meta_box( 'wooco_meta_box', esc_html__( 'WooExtend Combo Offers', 'woo-combo-offers' ), array(
						&$this,
						'wooco_meta_boxes_content'
					), 'product', 'side', 'high' );
				}

				function wooco_meta_boxes_content() {
					$post_id = isset( $_POST['post_ID'] ) ? intval($_POST['post_ID']) : 0;
					$post_id = isset( $_GET['post'] ) ? intval($_GET['post']) : $post_id;
					if ( $post_id > 0 ) {
						$wooco_product = wc_get_product( $post_id );
						if ( $wooco_product && ! $wooco_product->is_type( 'wooco' ) ) {
							?>
                            <p><?php esc_html_e( 'Update price for all combos contains this product. The progress time based on the number of your combos.', 'woo-combo-offers' ); ?></p>
                            <input id="wooco_meta_box_update_price" type="button" class="button"
                                   data-id="<?php echo esc_attr( $post_id ); ?>"
                                   value="<?php esc_html_e( 'Update Price', 'woo-combo-offers' ); ?>"/>
                            <ul id="wooco_meta_box_update_price_result"></ul>
							<?php
						} else { ?>
                            <p><?php esc_html_e( 'Invalid product to use this tool!', 'woo-combo-offers' ); ?></p>
						<?php }
					} else { ?>
                        <p><?php esc_html_e( 'This box content just appears after you publish the product.', 'woo-combo-offers' ); ?></p>
					<?php }
				}

				function wooco_search_sku( $query ) {
					if ( $query->is_search && isset( $query->query['is_wooco'] ) ) {
						global $wpdb;
						$sku = $query->query['s'];
						$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value = %s;", $sku ) );
						if ( ! $ids ) {
							return;
						}
						unset( $query->query['s'], $query->query_vars['s'] );
						$query->query['post__in'] = array();
						foreach ( $ids as $id ) {
							$post = get_post( $id );
							if ( $post->post_type === 'product_variation' ) {
								$query->query['post__in'][]      = $post->post_parent;
								$query->query_vars['post__in'][] = $post->post_parent;
							} else {
								$query->query_vars['post__in'][] = $post->ID;
							}
						}
					}
				}

				function wooco_search_exact( $query ) {
					if ( $query->is_search && isset( $query->query['is_wooco'] ) ) {
						$query->set( 'exact', true );
					}
				}

				function wooco_search_sentence( $query ) {
					if ( $query->is_search && isset( $query->query['is_wooco'] ) ) {
						$query->set( 'sentence', true );
					}
				}

				function wooco_product_type_selector( $types ) {
					$types['wooco'] = esc_html__( 'Combo Offer', 'woo-combo-offers' );

					return $types;
				}

				function wooco_product_data_tabs( $tabs ) {
					$tabs['wooco'] = array(
						'label'  => esc_html__( 'Combo Products', 'woo-combo-offers' ),
						'target' => 'wooco_settings',
						'class'  => array( 'show_if_wooco' ),
					);

					return $tabs;
				}

				function wooco_product_tabs( $tabs ) {
					global $product;

					if ( $product->is_type( 'wooco' ) ) {
						$tabs['wooco'] = array(
							'title'    => esc_html__( 'Combo products', 'woo-combo-offers' ),
							'priority' => 50,
							'callback' => array( $this, 'wooco_product_tab_content' )
						);
					}

					return $tabs;
				}

				function wooco_product_tab_content() {
					$this->wooco_show_items();
				}

				function wooco_product_filters( $filters ) {
					$filters = str_replace( 'Wooco', esc_html__( 'Combo offer', 'woo-combo-offers' ), $filters );

					return $filters;
				}

				function wooco_product_data_panels() {
					global $post;
					$post_id = $post->ID;
					?>
                    <div id='wooco_settings' class='panel woocommerce_options_panel wooco_table'>
                        <table>
                        	<tr><th colspan="2" style="padding-bottom:10px;">
                        		<strong><?php 
                        		echo sprintf( esc_html__( 'If you like Combo Offers please leave us a %s rating. A huge thanks in advance!', 'woo-combo-offers' ), '<a href="https://wordpress.org/support/plugin/woo-combo-offers/reviews?rate=5#new-post" target="_blank" class="wc-rating-link" aria-label="five star" data-rated="Thanks :)">★★★★★</a>' ); ?></strong>
                        	</th></tr>
                            <tr>
                                <th><?php esc_html_e( 'Select product', 'woo-combo-offers' ); ?>
                                </th>
                                <td>
                                    <div class="w100">
								<span class="loading"
                                      id="wooco_loading"><?php esc_html_e( 'searching...', 'woo-combo-offers' ); ?></span>
                                        <input type="search" id="wooco_keyword"
                                               placeholder="<?php esc_html_e( 'Type any keyword to search', 'woo-combo-offers' ); ?>"/>
                                        <div id="wooco_results" class="wooco_results"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'Selected', 'woo-combo-offers' ); ?></th>
                                <td>
                                    <div class="w100">
                                        <input type="hidden" id="wooco_ids" class="wooco_ids" name="wooco_ids"
                                               value="<?php echo esc_attr(get_post_meta( $post_id, 'wooco_ids', true )); ?>"
                                               readonly/>
                                        <div id="wooco_selected" class="wooco_selected">
                                            <ul>
												<?php
												if ( get_post_meta( $post_id, 'wooco_ids', true ) ) {
													$wooco_items = explode( ',', get_post_meta( $post_id, 'wooco_ids', true ) );
													if ( is_array( $wooco_items ) && count( $wooco_items ) > 0 ) {
														foreach ( $wooco_items as $wooco_item ) {
															$wooco_item_arr = explode( '/', $wooco_item );
															$wooco_item_id  = absint( isset( $wooco_item_arr[0] ) ? $wooco_item_arr[0] : 0 );
															$wooco_item_qty = absint( isset( $wooco_item_arr[1] ) ? $wooco_item_arr[1] : 1 );
															$wooco_product  = wc_get_product( $wooco_item_id );
															if ( ! $wooco_product || $wooco_product->is_type( 'wooco' ) ) {
																continue;
															}
															$this->wooco_product_data_li( $wooco_product, $wooco_item_qty );
														}
													}
												}
												?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php echo esc_html__( 'Regular price', 'woo-combo-offers' ) . ' (' . get_woocommerce_currency_symbol() . ')'; ?></th>
                                <td>
                                    <span id="wooco_regular_price"></span>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'Auto price', 'woo-combo-offers' ); ?></th>
                                <td>
                                    <input id="wooco_disable_auto_price" name="wooco_disable_auto_price"
                                           type="checkbox" <?php echo( get_post_meta( $post_id, 'wooco_disable_auto_price', true ) === 'on' ? 'checked' : '' ); ?>/>
                                    <label for="wooco_disable_auto_price"></label><?php esc_html_e( 'Disable auto calculate price?', 'woo-combo-offers' ); ?> <?php echo sprintf( esc_html__( 'If yes, %s click here to set price %s by manually.', 'woo-combo-offers' ), '<a id="wooco_set_regular_price">', '</a>' ); ?>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space wooco_tr_show_if_auto_price">
                                <th><?php esc_html_e( 'Discount', 'woo-combo-offers' ); ?></th>
                                <td style="vertical-align: middle; line-height: 30px; font-style: italic">
									<?php
									// only for old version has wooco_price_percent
									$wooco_discount = 0;
									if ( get_post_meta( $post_id, 'wooco_discount', true ) ) {
										$wooco_discount = get_post_meta( $post_id, 'wooco_discount', true );
									} elseif ( get_post_meta( $post_id, 'wooco_price_percent', true ) ) {
										$wooco_discount = 100 - get_post_meta( $post_id, 'wooco_price_percent', true );
									}
									?>
                                    <input id="wooco_discount" name="wooco_discount" type="number"
                                           min="0" step="0.0001"
                                           max="99.9999"
                                           value="<?php echo esc_attr( $wooco_discount ); ?>"
                                           style="width: 60px"/>%
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'Optional products', 'woo-combo-offers' ); ?></th>
                                <td>
                                    <input id="wooco_optional_products" name="wooco_optional_products"
                                           type="checkbox" <?php echo( get_post_meta( $post_id, 'wooco_optional_products', true ) === 'on' ? 'checked' : '' ); ?>/>
                                    <label for="wooco_optional_products"></label><?php esc_html_e( 'Buyer can change the quantity of combo products?', 'woo-combo-offers' ); ?>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space wooco_tr_show_if_optional_products">
                                <th><?php esc_html_e( 'Limit of each item', 'woo-combo-offers' ); ?></th>
                                <td>
                                    Min <input name="wooco_limit_each_min" type="number"
                                               min="0"
                                               value="<?php echo( get_post_meta( $post_id, 'wooco_limit_each_min', true ) ?: '' ); ?>"
                                               style="width: 60px; float: none"/> Max <input name="wooco_limit_each_max"
                                                                                             type="number" min="1"
                                                                                             value="<?php echo esc_attr( get_post_meta( $post_id, 'wooco_limit_each_max', true ) ?: '' ); ?>"
                                                                                             style="width: 60px; float: none"/>
                                    <input id="wooco_limit_each_min_default" name="wooco_limit_each_min_default"
                                           type="checkbox" <?php echo esc_attr( get_post_meta( $post_id, 'wooco_limit_each_min_default', true ) === 'on' ? 'checked' : '' ); ?>/>
                                    <label for="wooco_limit_each_min_default"></label><?php esc_html_e( 'Use default quantity as min?', 'woo-combo-offers' ); ?>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space wooco_tr_show_if_optional_products">
                                <th><?php esc_html_e( 'Limit of whole items', 'woo-combo-offers' ); ?></th>
                                <td>
                                    Min <input name="wooco_limit_whole_min" type="number"
                                               min="1"
                                               value="<?php echo esc_attr( get_post_meta( $post_id, 'wooco_limit_whole_min', true ) ?: '' ); ?>"
                                               style="width: 60px; float: none"/> Max <input
                                            name="wooco_limit_whole_max"
                                            type="number" min="1"
                                            value="<?php echo esc_attr( get_post_meta( $post_id, 'wooco_limit_whole_max', true ) ?: '' ); ?>"
                                            style="width: 60px; float: none"/>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'Shipping fee', 'woo-combo-offers' ); ?></th>
                                <td style="font-style: italic">
                                    <select id="wooco_shipping_fee" name="wooco_shipping_fee">
                                        <option value="whole" <?php echo esc_attr( get_post_meta( $post_id, 'wooco_shipping_fee', true ) === 'whole' ? 'selected' : '' ); ?>><?php esc_html_e( 'Apply to the whole combo', 'woo-combo-offers' ); ?></option>
                                        <option value="each" <?php echo esc_attr( get_post_meta( $post_id, 'wooco_shipping_fee', true ) === 'each' ? 'selected' : '' ); ?>><?php esc_html_e( 'Apply to each combo product', 'woo-combo-offers' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'Manage stock', 'woo-combo-offers' ); ?></th>
                                <td>
                                    <input id="wooco_manage_stock" name="wooco_manage_stock"
                                           type="checkbox" <?php echo esc_attr( get_post_meta( $post_id, 'wooco_manage_stock', true ) === 'on' ? 'checked' : '' ); ?>/>
                                    <label for="wooco_manage_stock"></label><?php esc_html_e( 'Enable stock management at combo level?', 'woo-combo-offers' ); ?>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'Before text', 'woo-combo-offers' ); ?></th>
                                <td>
                                    <div class="w100">
								<textarea name="wooco_before_text"
                                          placeholder="<?php esc_html_e( 'The text before combo products', 'woo-combo-offers' ); ?>"><?php echo esc_attr( get_post_meta( $post_id, 'wooco_before_text', true ) ); ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr class="wooco_tr_space">
                                <th><?php esc_html_e( 'After text', 'woo-combo-offers' ); ?></th>
                                <td>
                                    <div class="w100">
								<textarea name="wooco_after_text"
                                          placeholder="<?php esc_html_e( 'The text after combo products', 'woo-combo-offers' ); ?>"><?php echo esc_attr( get_post_meta( $post_id, 'wooco_after_text', true ) ); ?></textarea>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
					<?php
				}

				function wooco_product_data_li( $product, $qty = 1, $search = false ) {
					$product_id = $product->get_id();

					if ( $product->is_sold_individually() ) {
						$qty_input = '<input type="number" value="' . intval($qty) . '" min="0" max="1"/>';
					} else {
						$qty_input = '<input type="number" value="' . intval($qty) . '" min="0"/>';
					}

					if ( $product->is_type( 'variable' ) ) {
						$price     = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_price( 'min' ) ) );
						$price_max = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_price( 'max' ) ) );
					} else {
						$price = $price_max = wc_get_price_to_display( $product );
					}

					if ( $search ) {
						$remove_btn = '<span class="remove hint--left" aria-label="' . esc_html__( 'Add', 'woo-combo-offers' ) . '">+</span>';
					} else {
						$remove_btn = '<span class="remove hint--left" aria-label="' . esc_html__( 'Remove', 'woo-combo-offers' ) . '">×</span>';
					}

					echo '<li ' . ( ! $product->is_in_stock() ? 'class="out-of-stock"' : '' ) . ' data-id="' . esc_attr($product_id) . '" data-price="' . $price . '" data-price-max="' . $price_max . '"><span class="move"></span><span class="qty hint--right" aria-label="' . esc_html__( 'Default quantity', 'woo-combo-offers' ) . '">' . ($qty_input) . '</span> <span class="name">' . $product->get_name() . '</span> <span class="info">' . ($product->get_price_html()) . '</span> ' . ( $product->is_sold_individually() ? '<span class="info">sold individually</span> ' : '' ) . '<span class="type"><a href="' . esc_url(get_edit_post_link( $product_id )) . '" target="_blank">' . $product->get_type() . ' #' . esc_attr($product_id) . '</a></span> ' . $remove_btn . '</li>';
				}

				function wooco_save_option_field( $post_id ) {
					if ( isset( $_POST['wooco_ids'] ) ) {
						update_post_meta( $post_id, 'wooco_ids', $this->wooco_clean_ids( sanitize_text_field($_POST['wooco_ids'] )) );
					}
					if ( isset( $_POST['wooco_disable_auto_price'] ) ) {
						update_post_meta( $post_id, 'wooco_disable_auto_price', 'on' );
					} else {
						update_post_meta( $post_id, 'wooco_disable_auto_price', 'off' );
					}
					if ( isset( $_POST['wooco_discount'] ) ) {
						update_post_meta( $post_id, 'wooco_discount', sanitize_text_field( $_POST['wooco_discount'] ) );
						delete_post_meta( $post_id, 'wooco_price_percent' );

						if ( !isset( $_POST['wooco_disable_auto_price'] ) ) {

							$rp = floatval($_POST['_regular_price']);
							$disc = floatval( $_POST['_regular_price']) * (100 - floatval($_POST['wooco_discount'])) / 100;
							
							update_post_meta( $post_id, '_sale_price', $disc);
							update_post_meta( $post_id, '_price', $disc);
						}
					} else {
						update_post_meta( $post_id, 'wooco_discount', 0 );
						delete_post_meta( $post_id, 'wooco_price_percent' );
						if ( !isset( $_POST['wooco_disable_auto_price'] ) ) {

							update_post_meta( $post_id, '_sale_price', '');
							update_post_meta( $post_id, '_price', sanitize_text_field( $_POST['_regular_price']) );
						}
					}
					if ( isset( $_POST['wooco_shipping_fee'] ) ) {
						update_post_meta( $post_id, 'wooco_shipping_fee', sanitize_text_field( $_POST['wooco_shipping_fee'] ) );
					}
					if ( isset( $_POST['wooco_optional_products'] ) ) {
						update_post_meta( $post_id, 'wooco_optional_products', 'on' );
					} else {
						update_post_meta( $post_id, 'wooco_optional_products', 'off' );
					}
					if ( isset( $_POST['wooco_manage_stock'] ) ) {
						update_post_meta( $post_id, 'wooco_manage_stock', 'on' );
					} else {
						update_post_meta( $post_id, 'wooco_manage_stock', 'off' );
					}
					if ( isset( $_POST['wooco_limit_each_min'] ) ) {
						update_post_meta( $post_id, 'wooco_limit_each_min', sanitize_text_field( $_POST['wooco_limit_each_min'] ) );
					}
					if ( isset( $_POST['wooco_limit_each_max'] ) ) {
						update_post_meta( $post_id, 'wooco_limit_each_max', sanitize_text_field( $_POST['wooco_limit_each_max'] ) );
					}
					if ( isset( $_POST['wooco_limit_each_min_default'] ) ) {
						update_post_meta( $post_id, 'wooco_limit_each_min_default', 'on' );
					} else {
						update_post_meta( $post_id, 'wooco_limit_each_min_default', 'off' );
					}
					if ( isset( $_POST['wooco_limit_whole_min'] ) ) {
						update_post_meta( $post_id, 'wooco_limit_whole_min', sanitize_text_field( $_POST['wooco_limit_whole_min'] ) );
					}
					if ( isset( $_POST['wooco_limit_whole_max'] ) ) {
						update_post_meta( $post_id, 'wooco_limit_whole_max', sanitize_text_field( $_POST['wooco_limit_whole_max'] ) );
					}
					if ( isset( $_POST['wooco_before_text'] ) && ( $_POST['wooco_before_text'] !== '' ) ) {
						update_post_meta( $post_id, 'wooco_before_text', esc_attr( $_POST['wooco_before_text'] ) );
					} else {
						delete_post_meta( $post_id, 'wooco_before_text' );
					}
					if ( isset( $_POST['wooco_after_text'] ) && ( $_POST['wooco_after_text'] !== '' ) ) {
						update_post_meta( $post_id, 'wooco_after_text', esc_attr( $_POST['wooco_after_text'] ) );
					} else {
						delete_post_meta( $post_id, 'wooco_after_text' );
					}
				}

				function wooco_add_to_cart_form() {
					global $product;
					if ( $product->has_variables() ) {
						wp_enqueue_script( 'wc-add-to-cart-variation' );
					}

					if ( ( get_option( '_wooco_bundled_position', 'above' ) === 'above' ) && apply_filters( 'wooco_show_items', true, $product->get_id() ) ) {
						$this->wooco_show_items();
					}

					wc_get_template( 'single-product/add-to-cart/simple.php' );

					if ( ( get_option( '_wooco_bundled_position', 'above' ) === 'below' ) && apply_filters( 'wooco_show_items', true, $product->get_id() ) ) {
						$this->wooco_show_items();
					}
				}

				function wooco_add_to_cart_button() {
					global $product;
					if ( $product->is_type( 'wooco' ) ) {
						echo '<input name="wooco_ids" class="wooco_ids wooco-ids" type="hidden" value="' . get_post_meta( $product->get_id(), 'wooco_ids', true ) . '"/>';
					}
				}

				function wooco_loop_add_to_cart_link( $link, $product ) {
					if ( $product->is_type( 'wooco' ) && ( $product->has_variables() || $product->is_optional() ) ) {
						$link = str_replace( 'ajax_add_to_cart', '', $link );
					}

					return $link;
				}

				function wooco_cart_shipping_packages( $packages ) {
					if ( ! empty( $packages ) ) {
						foreach ( $packages as $package_key => $package ) {
							if ( ! empty( $package['contents'] ) ) {
								foreach ( $package['contents'] as $cart_item_key => $cart_item ) {
									if ( isset( $cart_item['wooco_parent_id'] ) && ( $cart_item['wooco_parent_id'] !== '' ) && ( get_post_meta( $cart_item['wooco_parent_id'], 'wooco_shipping_fee', true ) !== 'each' ) ) {
										unset( $packages[ $package_key ]['contents'][ $cart_item_key ] );
									}
									if ( isset( $cart_item['wooco_ids'] ) && ( $cart_item['wooco_ids'] !== '' ) && ( get_post_meta( $cart_item['data']->get_id(), 'wooco_shipping_fee', true ) === 'each' ) ) {
										unset( $packages[ $package_key ]['contents'][ $cart_item_key ] );
									}
								}
							}
						}
					}

					return $packages;
				}

				function wooco_get_price_html( $price, $product ) {
					$product_id = $product->get_id();
					if ( $product->is_type( 'wooco' ) && ! $product->is_fixed_price() && ( $wooco_items = $product->get_items() ) ) {
						if ( $product->is_optional() ) {
							// min price
							$prices = array();
							foreach ( $wooco_items as $wooco_item ) {
								$wooco_product = wc_get_product( $wooco_item['id'] );
								if ( $wooco_product ) {
									if ( $wooco_product->is_type( 'variable' ) ) {
										$prices[] = wc_get_price_to_display( $wooco_product, array(
											'price' => $wooco_product->get_variation_price( 'min' )
										) );
									} else {
										$prices[] = wc_get_price_to_display( $wooco_product );
									}
								}
							}
							if ( count( $prices ) > 0 ) {
								$min_price = min( $prices );
							} else {
								$min_price = 0;
							}

							// min whole
							$min_qty_whole = absint( get_post_meta( $product_id, 'wooco_limit_whole_min', true ) ?: 1 );
							if ( $min_qty_whole > 1 ) {
								$min_price *= $min_qty_whole;
							}

							// min each
							$min_qty_each = absint( get_post_meta( $product_id, 'wooco_limit_each_min', true ) ?: 0 );
							if ( $min_qty_each > 0 ) {
								$min_price = 0;
								foreach ( $prices as $pr ) {
									$min_price += absint( $pr );
								}
								$min_price *= $min_qty_each;
							}

							if ( ( $discount = $product->get_discount() ) > 0 ) {
								$min_price *= (float) ( 100 - $discount ) / 100;
							}

							switch ( get_option( '_wooco_price_format', 'from_min' ) ) {
								case 'min_only':
									return wc_price( $min_price );
									break;
								case 'from_min':
									return esc_html__( 'From', 'woo-combo-offers' ) . ' ' . wc_price( $min_price );
									break;
							}
						} elseif ( $product->has_variables() ) {
							$min_price = $max_price = 0;
							foreach ( $wooco_items as $wooco_item ) {
								$wooco_product = wc_get_product( $wooco_item['id'] );
								if ( $wooco_product ) {
									if ( $wooco_product->is_type( 'variable' ) ) {
										$min_price += wc_get_price_to_display( $wooco_product, array(
											'qty'   => $wooco_item['qty'],
											'price' => $wooco_product->get_variation_price( 'min' )
										) );
										$max_price += wc_get_price_to_display( $wooco_product, array(
											'qty'   => $wooco_item['qty'],
											'price' => $wooco_product->get_variation_price( 'max' )
										) );
									} else {
										$min_price += wc_get_price_to_display( $wooco_product, array( 'qty' => $wooco_item['qty'] ) );
										$max_price += wc_get_price_to_display( $wooco_product, array( 'qty' => $wooco_item['qty'] ) );
									}
								}
							}
							if ( ( $discount = $product->get_discount() ) > 0 ) {
								$min_price *= (float) ( 100 - $discount ) / 100;
								$max_price *= (float) ( 100 - $discount ) / 100;
							}

							switch ( get_option( '_wooco_price_format', 'from_min' ) ) {
								case 'min_only':
									return wc_price( $min_price );
									break;
								case 'min_max':
									return wc_price( $min_price ) . ' - ' . wc_price( $max_price );
									break;
								case 'from_min':
									return esc_html__( 'From', 'woo-combo-offers' ) . ' ' . wc_price( $min_price );
									break;
							}
						}
					}

					return $price;
				}

				function wooco_order_again_cart_item_data( $item_data, $item, $order ) {
					if ( isset( $item['wooco_ids'] ) ) {
						$item_data['wooco_order_again'] = 'yes';
					}

					return $item_data;
				}

				function wooco_cart_loaded_from_session() {
					foreach ( WC()->cart->cart_contents as $cart_item_key => $cart_item ) {
						if ( isset( $cart_item['wooco_order_again'] ) ) {
							WC()->cart->remove_cart_item( $cart_item_key );
							wc_add_notice( sprintf( esc_html__( 'The combo "%s" could not be added to your cart from order again button. Please buy it directly.', 'woo-combo-offers' ), $cart_item['data']->get_name() ), 'error' );
						}
					}
				}

				function wooco_show_items() {
					global $product;
					$count      = 0;
					$product_id = $product->get_id();
					if ( $wooco_items = $product->get_items() ) {
						echo '<div class="wooco_wrap wooco-wrap">';
						if ( $wooco_before_text = apply_filters( 'wooco_before_text', get_post_meta( $product_id, 'wooco_before_text', true ), $product_id ) ) {
							echo '<div class="wooco_before_text wooco-before-text wooco-text">' . do_shortcode( esc_html( $wooco_before_text ) ) . '</div>';
						}
						do_action( 'wooco_before_table', $product );
						?>
                        <div class="wooco_products wooco-table wooco-products"
                             data-discount="<?php echo $product->get_discount(); ?>"
                             data-fixed-price="<?php echo esc_attr( $product->is_fixed_price() ? 'yes' : 'no' ); ?>"
                             data-variables="<?php echo esc_attr( $product->has_variables() ? 'yes' : 'no' ); ?>"
                             data-optional="<?php echo esc_attr( $product->is_optional() ? 'yes' : 'no' ); ?>"
                             data-min="<?php echo esc_attr( get_post_meta( $product_id, 'wooco_limit_whole_min', true ) ?: 1 ); ?>"
                             data-max="<?php echo esc_attr( get_post_meta( $product_id, 'wooco_limit_whole_max', true ) ?: '' ); ?>">
							<?php foreach ( $wooco_items as $wooco_item ) {
								$wooco_product = wc_get_product( $wooco_item['id'] );
								if ( ! $wooco_product || ( $count > 2 ) ) {
									continue;
								}

								$wooco_product_qty = $wooco_item['qty'];

								if ( get_post_meta( $product_id, 'wooco_limit_each_min_default', true ) === 'on' ) {
									$wooco_product_qty_min = $wooco_product_qty;
								} else {
									$wooco_product_qty_min = absint( get_post_meta( $product_id, 'wooco_limit_each_min', true ) ?: 0 );
								}

								$wooco_product_qty_max = absint( get_post_meta( $product_id, 'wooco_limit_each_max', true ) ?: 1000 );
								if ( $wooco_product_qty < $wooco_product_qty_min ) {
									$wooco_product_qty = $wooco_product_qty_min;
								}
								if ( ( $wooco_product_qty_max > $wooco_product_qty_min ) && ( $wooco_product_qty > $wooco_product_qty_max ) ) {
									$wooco_product_qty = $wooco_product_qty_max;
								}
								if ( ! $wooco_product->is_in_stock() || ! $wooco_product->has_enough_stock( $wooco_product_qty ) ) {
									$wooco_product_qty = 0;
								}
								?>
                                <div class="wooco-product"
                                     data-id="<?php echo esc_attr( $wooco_product->is_type( 'variable' ) ? 0 : $wooco_item['id'] ); ?>"
                                     data-price="<?php echo esc_attr( wc_get_price_to_display( $wooco_product ) ); ?>"
                                     data-qty="<?php echo esc_attr( $wooco_product_qty ); ?>">
									<?php if ( get_option( '_wooco_bundled_thumb', 'yes' ) !== 'no' ) { ?>
                                        <div class="wooco-thumb">
                                            <div class="wooco-thumb-ori">
												<?php echo apply_filters( 'wooco_item_thumbnail', $wooco_product->get_image(), $wooco_product ); ?>
                                            </div>
                                            <div class="wooco-thumb-new"></div>
                                        </div>
									<?php } ?>
                                    <div class="wooco-title">
										<?php
										do_action( 'wooco_before_item_name', $wooco_product );
										echo '<div class="wooco-title-inner">';
										if ( ( get_option( '_wooco_bundled_qty', 'yes' ) === 'yes' ) && ( get_post_meta( $product_id, 'wooco_optional_products', true ) !== 'on' ) ) {
											echo apply_filters( 'wooco_item_qty', $wooco_item['qty'] . ' × ', $wooco_item['qty'], $wooco_product );
										}
										$wooco_item_name = '';
										if ( $wooco_product->is_visible() && ( get_option( '_wooco_bundled_link', 'yes' ) !== 'no' ) ) {
											$wooco_item_name .= '<a href="' . get_permalink( $wooco_item['id'] ) . '" ' . ( get_option( '_wooco_bundled_link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
										}
										if ( $wooco_product->is_in_stock() && $wooco_product->has_enough_stock( $wooco_product_qty ) ) {
											$wooco_item_name .= $wooco_product->get_name();
										} else {
											$wooco_item_name .= '<s>' . $wooco_product->get_name() . '</s>';
										}
										if ( $wooco_product->is_visible() && ( get_option( '_wooco_bundled_link', 'yes' ) !== 'no' ) ) {
											$wooco_item_name .= '</a>';
										}
										echo apply_filters( 'wooco_item_name', $wooco_item_name, $wooco_product );
										echo '</div>';
										do_action( 'wooco_after_item_name', $wooco_product );
										if ( get_option( '_wooco_bundled_description', 'no' ) === 'yes' ) {
											echo '<div class="wooco-description">' . apply_filters( 'wooco_item_description', $wooco_product->get_short_description(), $wooco_product ) . '</div>';
										}
										if ( $wooco_product->is_type( 'variable' ) ) {
											$attributes           = $wooco_product->get_variation_attributes();
											$available_variations = $wooco_product->get_available_variations();
											$variations_json      = wp_json_encode( $available_variations );
											$variations_attr      = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
											if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {
												echo '<form class="variations_form" data-product_id="' . absint( $wooco_product->get_id() ) . '" data-product_variations="' . $variations_attr . '">';
												echo '<div class="variations">';
												foreach ( $attributes as $attribute_name => $options ) { ?>
                                                    <div class="variation">
                                                        <div class="label">
															<?php echo wc_attribute_label( $attribute_name ); ?>
                                                        </div>
                                                        <div class="select">
															<?php
															$attr     = 'attribute_' . sanitize_title( $attribute_name );
															$selected = isset( $_REQUEST[ $attr ] ) ? wc_clean( esc_html( urldecode( $_REQUEST[ $attr ] ) ) ) : $wooco_product->get_variation_default_attribute( $attribute_name );
															wc_dropdown_variation_attribute_options( array(
																'options'          => $options,
																'attribute'        => $attribute_name,
																'product'          => $wooco_product,
																'selected'         => $selected,
																'show_option_none' => esc_html__( 'Choose', 'woo-combo-offers' ) . ' ' . wc_attribute_label( $attribute_name )
															) );
															?>
                                                        </div>
                                                    </div>
												<?php }
												echo '<div class="reset">' . apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woo-combo-offers' ) . '</a>' ) . '</div>';
												echo '</div>';
												echo '</form>';
												if ( get_option( '_wooco_bundled_description', 'no' ) === 'yes' ) {
													echo '<div class="wooco-variation-description"></div>';
												}
											}
											do_action( 'wooco_after_item_variations', $wooco_product );
										}
										?>
                                    </div>
									<?php if ( get_post_meta( $product_id, 'wooco_optional_products', true ) === 'on' ) {
										if ( (( $wooco_product->get_backorders() === 'no' ) && ( $wooco_product->get_stock_status() !== 'onbackorder' ) && is_int( $wooco_product->get_stock_quantity() ) && ( $wooco_product->get_stock_quantity() < $wooco_product_qty_max )) ) {
											if($wooco_product->get_manage_stock()) {
												$wooco_product_qty_max = $wooco_product->get_stock_quantity();
											}
											else {
												$wooco_product_qty_max = 99999;
											}
										}
										if ( $wooco_product->is_sold_individually() ) {
											$wooco_product_qty_max = 1;
										}
										if ( $wooco_product->is_in_stock() ) {

											$step = apply_filters( 'woocommerce_quantity_input_step', 1, $wooco_product );
											?>
                                            <div class="wooco-qty">
                                                <input type="number" class="input-text qty text"
                                                       value="<?php echo esc_attr( $wooco_product_qty ); ?>"
                                                       min="<?php echo esc_attr( $wooco_product_qty_min ); ?>"
                                                       max="<?php echo esc_attr( $wooco_product_qty_max ); ?>"
                                                       step="<?php echo esc_attr( $step ); ?>"/>
                                            </div>
											<?php
										} else { ?>
                                            <div class="wooco-qty">
                                                <input type="number" class="input-text qty text" value="0" disabled/>
                                            </div>
										<?php }
									} ?>
									<?php if ( get_option( '_wooco_bundled_price', 'html' ) !== 'no' ) { ?>
                                        <div class="wooco-price">
                                            <div class="wooco-price-ori">
												<?php
												$wooco_price = '';
												switch ( get_option( '_wooco_bundled_price', 'html' ) ) {
													case 'price':
														$wooco_price = wc_price( wc_get_price_to_display( $wooco_product ) );
														break;
													case 'html':
														$wooco_price = $wooco_product->get_price_html();
														break;
													case 'subtotal':
														$wooco_price = wc_price( wc_get_price_to_display( $wooco_product, array( 'qty' => $wooco_item['qty'] ) ) );
														break;
												}
												echo apply_filters( 'wooco_item_price', $wooco_price, $wooco_product );
												?>
                                            </div>
                                            <div class="wooco-price-new"></div>
											<?php do_action( 'wooco_after_item_price', $wooco_product ); ?>
                                        </div>
									<?php } ?>
                                </div>
								<?php
								$count ++;
							} ?>
                        </div>
						<?php
						if ( ! $product->is_fixed_price() && ( $product->has_variables() || $product->is_optional() ) ) {
							echo '<div class="wooco_total wooco-total wooco-text"></div>';
						}
						do_action( 'wooco_after_table', $product );
						if ( $wooco_after_text = apply_filters( 'wooco_after_text', get_post_meta( $product_id, 'wooco_after_text', true ), $product_id ) ) {
							echo '<div class="wooco_after_text wooco-after-text wooco-text">' . do_shortcode(html_entity_decode( $wooco_after_text )) . '</div>';
						}
						echo '</div>';
					}
				}

				function wooco_get_items( $product_id ) {
					$wooco_arr = array();
					if ( $wooco_ids = get_post_meta( $product_id, 'wooco_ids', true ) ) {
						$wooco_items = explode( ',', $wooco_ids );
						if ( is_array( $wooco_items ) && count( $wooco_items ) > 0 ) {
							foreach ( $wooco_items as $wooco_item ) {
								$wooco_item_arr = explode( '/', $wooco_item );
								$wooco_arr[]    = array(
									'id'  => absint( isset( $wooco_item_arr[0] ) ? $wooco_item_arr[0] : 0 ),
									'qty' => absint( isset( $wooco_item_arr[1] ) ? $wooco_item_arr[1] : 1 )
								);
							}
						}
					}
					if ( count( $wooco_arr ) > 0 ) {
						return $wooco_arr;
					}

					return false;
				}

				function wooco_clean_ids( $ids ) {
					$ids = preg_replace( '/[^,\/0-9]/', '', $ids );

					return $ids;
				}

				function wooco_deactivation() {
					wp_clear_scheduled_hook( 'wooco_cron_jobs' );
				}
			}

			new WooExtendWooco();
		}
	}
} else {
	add_action( 'admin_notices', 'wooco_notice_premium' );
}

if ( ! function_exists( 'wooco_notice_wc' ) ) {
	function wooco_notice_wc() {
		?>
        <div class="error">
            <p><strong>Woocommerce Combo Offers</strong> requires WooCommerce version 3.0.0 or greater.</p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'wooco_notice_premium' ) ) {
	function wooco_notice_premium() {
		?>
        <div class="error">
            <p>Seems you're using both free and premium version of <strong>Woocommerce Combo Offers</strong>. Please
                deactivate the free version when using the premium version.</p>
        </div>
		<?php
	}
}