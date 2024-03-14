<?php
/*
Plugin Name: WPC Product Timer for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Product Timer helps you add many actions for the product based on the conditionals of the time.
Version: 5.1.3
Author: WPClever
Author URI: https://wpclever.net
Text Domain: woo-product-timer
Domain Path: /languages/
Requires Plugins: woocommerce
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.6
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOPT_VERSION' ) && define( 'WOOPT_VERSION', '5.1.3' );
! defined( 'WOOPT_LITE' ) && define( 'WOOPT_LITE', __FILE__ );
! defined( 'WOOPT_FILE' ) && define( 'WOOPT_FILE', __FILE__ );
! defined( 'WOOPT_URI' ) && define( 'WOOPT_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOPT_DIR' ) && define( 'WOOPT_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'WOOPT_DOCS' ) && define( 'WOOPT_DOCS', 'https://doc.wpclever.net/woopt/' );
! defined( 'WOOPT_SUPPORT' ) && define( 'WOOPT_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=woopt&utm_campaign=wporg' );
! defined( 'WOOPT_REVIEWS' ) && define( 'WOOPT_REVIEWS', 'https://wordpress.org/support/plugin/woo-product-timer/reviews/?filter=5' );
! defined( 'WOOPT_CHANGELOG' ) && define( 'WOOPT_CHANGELOG', 'https://wordpress.org/plugins/woo-product-timer/#developers' );
! defined( 'WOOPT_DISCUSSION' ) && define( 'WOOPT_DISCUSSION', 'https://wordpress.org/support/plugin/woo-product-timer' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOPT_URI );

include 'includes/dashboard/wpc-dashboard.php';
include 'includes/kit/wpc-kit.php';
include 'includes/hpos.php';

if ( ! function_exists( 'woopt_init' ) ) {
	add_action( 'plugins_loaded', 'woopt_init', 11 );

	function woopt_init() {
		// load text-domain
		load_plugin_textdomain( 'woo-product-timer', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'woopt_notice_wc' );

			return null;
		}

		if ( ! class_exists( 'WPCleverWoopt' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWoopt {
				public static $global_actions = [];
				public static $features = [];
				protected static $instance = null;

				public static function instance() {
					if ( is_null( self::$instance ) ) {
						self::$instance = new self();
					}

					return self::$instance;
				}

				function __construct() {
					self::$global_actions = (array) get_option( 'woopt_actions', [] );
					self::$features       = (array) get_option( 'woopt_features', [] );

					// Settings
					add_action( 'admin_init', [ $this, 'register_settings' ] );
					add_action( 'admin_menu', [ $this, 'admin_menu' ] );

					// Enqueue backend scripts
					add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

					// Product data tabs
					add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );

					// Product data panels
					add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
					add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );

					// Add settings link
					add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
					add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

					// AJAX
					add_action( 'wp_ajax_woopt_save_actions', [ $this, 'ajax_save_actions' ] );
					add_action( 'wp_ajax_woopt_add_time', [ $this, 'ajax_add_time' ] );
					add_action( 'wp_ajax_woopt_add_apply_combination', [ $this, 'ajax_add_apply_combination' ] );
					add_action( 'wp_ajax_woopt_search_term', [ $this, 'ajax_search_term' ] );

					// Product class
					add_filter( 'woocommerce_post_class', [ $this, 'woopt_post_class' ], 99, 2 );

					// Features
					if ( empty( self::$features ) || in_array( 'stock', self::$features ) ) {
						add_filter( 'woocommerce_product_is_in_stock', [ $this, 'woopt_is_in_stock' ], 99, 2 );
					}

					if ( empty( self::$features ) || in_array( 'visibility', self::$features ) ) {
						add_filter( 'woocommerce_product_is_visible', [ $this, 'woopt_is_visible' ], 99, 2 );
						add_filter( 'woocommerce_variation_is_visible', [ $this, 'woopt_is_visible' ], 99, 2 );
						add_filter( 'woocommerce_variation_is_active', [ $this, 'woopt_is_visible' ], 99, 2 );
					}

					if ( empty( self::$features ) || in_array( 'featured', self::$features ) ) {
						add_filter( 'woocommerce_product_get_featured', [ $this, 'woopt_is_featured' ], 99, 2 );
					}

					if ( empty( self::$features ) || in_array( 'purchasable', self::$features ) ) {
						add_filter( 'woocommerce_is_purchasable', [ $this, 'woopt_is_purchasable' ], 99, 2 );
					}

					if ( empty( self::$features ) || in_array( 'individually', self::$features ) ) {
						add_filter( 'woocommerce_is_sold_individually', [ $this, 'woopt_sold_individually' ], 99, 2 );
					}

					if ( empty( self::$features ) || in_array( 'price', self::$features ) ) {
						add_filter( 'woocommerce_product_get_regular_price', [
							$this,
							'woopt_get_regular_price'
						], 99, 2 );
						add_filter( 'woocommerce_product_get_sale_price', [ $this, 'woopt_get_sale_price' ], 99, 2 );
						add_filter( 'woocommerce_product_get_price', [ $this, 'woopt_get_price' ], 99, 2 );

						// Variation
						add_filter( 'woocommerce_product_variation_get_regular_price', [
							$this,
							'woopt_get_regular_price'
						], 99, 2 );
						add_filter( 'woocommerce_product_variation_get_sale_price', [
							$this,
							'woopt_get_sale_price'
						], 99, 2 );
						add_filter( 'woocommerce_product_variation_get_price', [ $this, 'woopt_get_price' ], 99, 2 );

						// Variations
						add_filter( 'woocommerce_variation_prices_regular_price', [
							$this,
							'woopt_get_regular_price'
						], 99, 2 );
						add_filter( 'woocommerce_variation_prices_sale_price', [
							$this,
							'woopt_get_sale_price'
						], 99, 2 );
						add_filter( 'woocommerce_variation_prices_price', [ $this, 'woopt_get_price' ], 99, 2 );
						add_filter( 'woocommerce_get_variation_prices_hash', [ $this, 'variation_prices_hash' ], 99 );
					}

					// Product columns
					add_filter( 'manage_edit-product_columns', [ $this, 'product_columns' ], 10 );
					add_action( 'manage_product_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );

					// Ajax edit
					add_action( 'wp_ajax_woopt_edit', [ $this, 'ajax_edit_timer' ] );
					add_action( 'wp_ajax_woopt_edit_save', [ $this, 'ajax_save_timer' ] );

					// Ajax import / export
					add_action( 'wp_ajax_woopt_import_export', [ $this, 'ajax_import_export' ] );
					add_action( 'wp_ajax_woopt_import_export_save', [ $this, 'ajax_import_export_save' ] );

					// Export
					add_filter( 'woocommerce_product_export_column_names', [ $this, 'export_columns' ] );
					add_filter( 'woocommerce_product_export_product_default_columns', [ $this, 'export_columns' ] );
					add_filter( 'woocommerce_product_export_product_column_woopt_actions', [
						$this,
						'export_data'
					], 10, 2 );

					// Import
					add_filter( 'woocommerce_csv_product_import_mapping_options', [ $this, 'import_options' ] );
					add_filter( 'woocommerce_csv_product_import_mapping_default_columns', [ $this, 'import_columns' ] );
					add_filter( 'woocommerce_product_import_pre_insert_product_object', [
						$this,
						'import_process'
					], 10, 2 );

					// WPML
					if ( function_exists( 'wpml_loaded' ) ) {
						add_filter( 'woopt_apply_terms', [ $this, 'wpml_apply_terms' ], 99, 2 );
					}
				}

				public static function woopt_action_data( $action ) {
					$action_data = [];
					$action_df   = [
						'name'       => '',
						'type'       => '',
						'apply'      => '',
						'apply_val'  => [],
						'action'     => '',
						'action_val' => [],
						'timer'      => [],
						'roles'      => [],
					];

					if ( ! empty( $action ) ) {
						if ( is_array( $action ) ) {
							// new version 5.0
							$action_data = $action;
						} elseif ( is_string( $action ) ) {
							$action_arr = explode( '|', $action );

							if ( strpos( $action, 'apply_' ) === false ) {
								// product
								$action_data = [
									'type'       => 'product',
									'action'     => isset( $action_arr[0] ) ? $action_arr[0] : '',
									'action_val' => isset( $action_arr[1] ) ? [
										'value' => (float) $action_arr[1],
										'base'  => strpos( $action_arr[1], '%' ) !== false ? 'ps' : 'fa',
									] : [ 'value' => '', 'base' => 'fa' ],
									'timer'      => isset( $action_arr[2] ) ? self::woopt_timer_data( $action_arr[2] ) : [],
									'roles'      => isset( $action_arr[3] ) ? explode( ',', (string) $action_arr[3] ) : [],
								];
							} else {
								// global
								$apply     = ! empty( $action_arr[0] ) ? $action_arr[0] : 'apply_all';
								$apply_val = [];

								if ( $apply === 'apply_combination' ) {
									// combined
									$apply_val['combined'] = self::woopt_apply_combination_data( isset( $action_arr[1] ) ? $action_arr[1] : [] );
								} elseif ( $apply === 'apply_product' ) {
									// products
									$apply_val['products'] = explode( ',', ( isset( $action_arr[1] ) ? $action_arr[1] : '' ) );
								} else {
									// terms
									$apply_val['terms'] = explode( ',', ( isset( $action_arr[1] ) ? $action_arr[1] : '' ) );
								}

								$action_data = [
									'type'       => 'global',
									'apply'      => $apply,
									'apply_val'  => $apply_val,
									'action'     => isset( $action_arr[2] ) ? $action_arr[2] : '',
									'action_val' => isset( $action_arr[3] ) ? [
										'value' => (float) $action_arr[3],
										'base'  => strpos( $action_arr[3], '%' ) !== false ? 'ps' : 'fa',
									] : [ 'value' => '', 'base' => 'fa' ],
									'timer'      => isset( $action_arr[4] ) ? self::woopt_timer_data( $action_arr[4] ) : [],
									'roles'      => isset( $action_arr[5] ) ? explode( ',', (string) $action_arr[5] ) : [],
								];
							}
						}
					}

					return array_merge( $action_df, $action_data );
				}

				public static function woopt_apply_combination_data( $combination ) {
					$combination_data    = [];
					$combination_item_df = [
						'type' => '',
						'val'  => []
					];

					if ( ! empty( $combination ) ) {
						if ( is_array( $combination ) ) {
							// new version 5.0
							$combination_data = $combination;
						} elseif ( is_string( $combination ) ) {
							$combination_arr = explode( '&', $combination );

							if ( is_array( $combination_arr ) && ( count( $combination_arr ) > 0 ) ) {
								foreach ( $combination_arr as $combination_key => $combination_item ) {
									if ( empty( $combination_key ) || is_numeric( $combination_key ) ) {
										$combination_key = self::generate_key();
									}

									$combination_item_arr  = explode( '>', $combination_item );
									$combination_item_type = isset( $combination_item_arr[0] ) ? trim( $combination_item_arr[0] ) : '';
									$combination_item_val  = isset( $combination_item_arr[1] ) ? trim( $combination_item_arr[1] ) : '';
									$combination_item_data = [
										'type' => $combination_item_type,
										'val'  => array_map( 'trim', explode( ',', $combination_item_val ) ),
									];

									$combination_data[ $combination_key ] = array_merge( $combination_item_df, $combination_item_data );
								}
							}
						} else {
							$combination_new_key                      = self::generate_key();
							$combination_data[ $combination_new_key ] = $combination_item_df;
						}
					} else {
						$combination_new_key                      = self::generate_key();
						$combination_data[ $combination_new_key ] = $combination_item_df;
					}

					return $combination_data;
				}

				public static function woopt_timer_data( $timer ) {
					$timer_data = [];
					$time_df    = [
						'type' => '',
						'val'  => ''
					];

					if ( ! empty( $timer ) ) {
						if ( is_array( $timer ) ) {
							// new version 5.0
							$timer_data = $timer;
						} elseif ( is_string( $timer ) ) {
							$timer_arr = explode( '&', $timer );

							if ( is_array( $timer_arr ) && ( count( $timer_arr ) > 0 ) ) {
								foreach ( $timer_arr as $key => $time ) {
									if ( empty( $key ) || is_numeric( $key ) ) {
										$key = self::generate_key();
									}

									$time_arr  = explode( '>', $time );
									$time_type = trim( isset( $time_arr[0] ) ? $time_arr[0] : '' );
									$time_val  = trim( isset( $time_arr[1] ) ? $time_arr[1] : '' );

									$time_data = [
										'type' => $time_type,
										'val'  => $time_val
									];

									$timer_data[ $key ] = array_merge( $time_df, $time_data );
								}
							}
						}
					} else {
						$key                = self::generate_key();
						$timer_data[ $key ] = $time_df;
					}

					return $timer_data;
				}

				public static function woopt_check_apply( $product, $apply, $apply_val, $is_variation = false ) {
					$product_id = 0;

					if ( is_numeric( $product ) ) {
						$product_id = $product;
					} elseif ( is_a( $product, 'WC_Product' ) ) {
						$product_id = $product->get_id();
					}

					if ( ! $product_id ) {
						return false;
					}

					switch ( $apply ) {
						case 'apply_all':
							return true;
						case 'apply_variation':
							if ( $is_variation ) {
								return true;
							}

							return false;
						case 'apply_not_variation':
							if ( ! $is_variation ) {
								return true;
							}

							return false;
						case 'apply_product':
							if ( ! empty( $apply_val['products'] ) ) {
								if ( in_array( $product_id, $apply_val['products'] ) ) {
									return true;
								}
							}

							return false;
						case 'apply_combination':
							if ( ! empty( $apply_val['combined'] ) ) {
								$match_all = true;

								foreach ( $apply_val['combined'] as $conditional_item ) {
									$match                 = false;
									$conditional_item_type = isset( $conditional_item['type'] ) ? $conditional_item['type'] : '';
									$conditional_item_val  = isset( $conditional_item['val'] ) ? (array) $conditional_item['val'] : [];

									if ( $conditional_item_type === 'variation' ) {
										if ( $is_variation ) {
											$match = true;
										}
									} elseif ( $conditional_item_type === 'not_variation' ) {
										if ( ! $is_variation ) {
											$match = true;
										}
									} else {
										if ( $is_variation ) {
											$variation = wc_get_product( $product_id );
											$attrs     = $variation->get_attributes();

											if ( ! empty( $attrs[ $conditional_item_type ] ) ) {
												if ( in_array( $attrs[ $conditional_item_type ], $conditional_item_val ) ) {
													$match = true;
												}
											}
										} else {
											$conditional_item_terms = apply_filters( 'woopt_apply_terms', $conditional_item_val, $conditional_item_type );

											if ( has_term( $conditional_item_terms, $conditional_item_type, $product_id ) ) {
												$match = true;
											}
										}
									}

									$match_all &= $match;
								}

								return $match_all;
							}

							return false;
						default:
							if ( ! empty( $apply_val['terms'] ) ) {
								$taxonomy = substr( $apply, 6 ); // trim from $apply

								if ( $is_variation ) {
									$variation = wc_get_product( $product_id );
									$attrs     = $variation->get_attributes();

									if ( ! empty( $attrs[ $taxonomy ] ) ) {
										if ( in_array( $attrs[ $taxonomy ], $apply_val['terms'] ) ) {
											return true;
										}
									}
								} else {
									$apply_terms = apply_filters( 'woopt_apply_terms', $apply_val['terms'], $taxonomy );

									if ( has_term( $apply_terms, $taxonomy, $product_id ) ) {
										return true;
									}
								}
							}

							return false;
					}
				}

				function wpml_apply_terms( $terms, $taxonomy ) {
					$apply_terms = [];

					if ( is_string( $terms ) ) {
						$terms = explode( ',', $terms );
					}

					if ( is_array( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$apply_term    = get_term_by( 'slug', $term, $taxonomy );
							$apply_terms[] = apply_filters( 'wpml_object_id', $apply_term->term_id, $taxonomy );
						}
					}

					return $apply_terms;
				}

				public static function woopt_check_conditional( $timer, $product_id = null ) {
					// old version before 5.0
					return self::woopt_check_timer( $timer, $product_id );
				}

				public static function woopt_check_timer( $timer, $product_id = null ) {
					$check = true;

					if ( ! empty( $timer ) ) {
						foreach ( $timer as $time ) {
							$check_item = false;
							$time_type  = isset( $time['type'] ) ? trim( $time['type'] ) : '';
							$time_value = isset( $time['val'] ) ? trim( $time['val'] ) : '';

							switch ( $time_type ) {
								case 'date_range':
									$date_range = array_map( 'trim', explode( '-', $time_value ) );

									if ( count( $date_range ) === 2 ) {
										$date_range_start = trim( $date_range[0] );
										$date_range_end   = trim( $date_range[1] );
										$current_date     = strtotime( current_time( 'm/d/Y' ) );

										if ( $current_date >= strtotime( $date_range_start ) && $current_date <= strtotime( $date_range_end ) ) {
											$check_item = true;
										}
									} elseif ( count( $date_range ) === 1 ) {
										$date_range_start = trim( $date_range[0] );

										if ( strtotime( current_time( 'm/d/Y' ) ) === strtotime( $date_range_start ) ) {
											$check_item = true;
										}
									}

									break;
								case 'date_multi':
									$multiple_dates_arr = array_map( 'trim', explode( ', ', $time_value ) );

									if ( in_array( current_time( 'm/d/Y' ), $multiple_dates_arr ) ) {
										$check_item = true;
									}

									break;
								case 'date_even':
									if ( (int) current_time( 'd' ) % 2 === 0 ) {
										$check_item = true;
									}

									break;
								case 'date_odd':
									if ( (int) current_time( 'd' ) % 2 !== 0 ) {
										$check_item = true;
									}

									break;
								case 'date_on':
									if ( strtotime( current_time( 'm/d/Y' ) ) === strtotime( $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'date_before':
									if ( strtotime( current_time( 'm/d/Y' ) ) < strtotime( $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'date_after':
									if ( strtotime( current_time( 'm/d/Y' ) ) > strtotime( $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'date_time_before':
									$current_time = current_time( 'm/d/Y h:i a' );

									if ( strtotime( $current_time ) < strtotime( $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'date_time_after':
									$current_time = current_time( 'm/d/Y h:i a' );

									if ( strtotime( $current_time ) > strtotime( $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'time_range':
									$time_range = array_map( 'trim', explode( '-', $time_value ) );

									if ( count( $time_range ) === 2 ) {
										$current_time     = strtotime( current_time( 'm/d/Y h:i a' ) );
										$current_date     = current_time( 'm/d/Y' );
										$time_range_start = $current_date . ' ' . $time_range[0];
										$time_range_end   = $current_date . ' ' . $time_range[1];

										if ( $current_time >= strtotime( $time_range_start ) && $current_time <= strtotime( $time_range_end ) ) {
											$check_item = true;
										}
									}

									break;
								case 'time_before':
									$current_time = current_time( 'm/d/Y h:i a' );
									$current_date = current_time( 'm/d/Y' );

									if ( strtotime( $current_time ) < strtotime( $current_date . ' ' . $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'time_after':
									$current_time = current_time( 'm/d/Y h:i a' );
									$current_date = current_time( 'm/d/Y' );

									if ( strtotime( $current_time ) > strtotime( $current_date . ' ' . $time_value ) ) {
										$check_item = true;
									}

									break;
								case 'weekly_every':
									if ( strtolower( current_time( 'D' ) ) === $time_value ) {
										$check_item = true;
									}

									break;
								case 'week_even':
									if ( (int) current_time( 'W' ) % 2 === 0 ) {
										$check_item = true;
									}

									break;
								case 'week_odd':
									if ( (int) current_time( 'W' ) % 2 !== 0 ) {
										$check_item = true;
									}

									break;
								case 'week_no':
									if ( (int) current_time( 'W' ) === (int) $time_value ) {
										$check_item = true;
									}

									break;
								case 'monthly_every':
									if ( strtolower( current_time( 'j' ) ) === $time_value ) {
										$check_item = true;
									}

									break;
								case 'month_no':
									if ( (int) current_time( 'm' ) === (int) $time_value ) {
										$check_item = true;
									}

									break;
								case 'days_less_published':
									$published = get_the_time( 'U', $product_id );

									if ( ( current_time( 'U' ) - $published ) < 60 * 60 * 24 * (int) $time_value ) {
										$check_item = true;
									}

									break;
								case 'days_greater_published':
									$published = get_the_time( 'U', $product_id );

									if ( ( current_time( 'U' ) - $published ) > 60 * 60 * 24 * (int) $time_value ) {
										$check_item = true;
									}

									break;
								case 'every_day':
									$check_item = true;

									break;
							}

							$check &= $check_item;
						}
					}

					return $check;
				}

				public static function woopt_check_roles( $roles ) {
					if ( is_string( $roles ) ) {
						$roles = explode( ',', $roles );
					}

					if ( empty( $roles ) || in_array( 'all', (array) $roles ) ) {
						return true;
					}

					if ( is_user_logged_in() ) {
						$current_user = wp_get_current_user();

						foreach ( $current_user->roles as $role ) {
							if ( in_array( $role, (array) $roles ) ) {
								return true;
							}
						}
					} else {
						if ( in_array( 'guest', (array) $roles ) ) {
							return true;
						}
					}

					return false;
				}

				public static function woopt_get_action_result( $result, $product, $action_true = '', $action_false = '' ) {
					$variation_id = 0;
					$product_id   = $product->get_id();

					if ( $product->is_type( 'variation' ) ) {
						$variation_id = $product_id;
						$product_id   = $product->get_parent_id();
					}

					// global actions
					if ( is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( $action_key !== $action_true && $action_key !== $action_false ) {
								continue;
							}

							if ( self::woopt_check_apply( $product_id, $action_apply, $action_apply_val ) && self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								if ( $action_key === $action_true ) {
									$result = true;
								} else {
									$result = false;
								}
							}
						}
					}

					// product actions
					$actions = get_post_meta( $product_id, 'woopt_actions', true );

					if ( is_array( $actions ) && ( count( $actions ) > 0 ) ) {
						foreach ( $actions as $action ) {
							$action_data  = self::woopt_action_data( $action );
							$action_key   = $action_data['action'];
							$action_timer = $action_data['timer'];
							$action_roles = $action_data['roles'];

							if ( $action_key !== $action_true && $action_key !== $action_false ) {
								continue;
							}

							if ( self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								if ( $action_key === $action_true ) {
									$result = true;
								} else {
									$result = false;
								}
							}
						}
					}

					// global actions for variation
					if ( $variation_id && is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( ( $action_apply !== 'apply_product' ) && ( $action_apply !== 'apply_combination' ) ) {
								continue;
							}

							if ( $action_key !== $action_true && $action_key !== $action_false ) {
								continue;
							}

							if ( self::woopt_check_apply( $variation_id, $action_apply, $action_apply_val, true ) && self::woopt_check_timer( $action_timer, $variation_id ) && self::woopt_check_roles( $action_roles ) ) {
								if ( $action_key === $action_true ) {
									$result = true;
								} else {
									$result = false;
								}
							}
						}
					}

					return $result;
				}

				public static function woopt_post_class( $classes, $product ) {
					if ( apply_filters( 'woopt_ignore', false, $product, 'post_class' ) ) {
						return $classes;
					}

					if ( $product && $product->is_type( 'variation' ) && $product->get_parent_id() ) {
						$product_id = $product->get_parent_id();
					} else {
						$product_id = $product->get_id();
					}

					// global actions
					if ( is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							if ( empty( $global_action ) ) {
								continue;
							}

							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( self::woopt_check_apply( $product_id, $action_apply, $action_apply_val ) && self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								$classes[] = 'woopt_global';
								$classes[] = 'woopt_global_' . $action_key;
							}
						}
					}

					$actions = get_post_meta( $product_id, 'woopt_actions', true );

					if ( is_array( $actions ) && ( count( $actions ) > 0 ) ) {
						foreach ( $actions as $action ) {
							$action_data  = self::woopt_action_data( $action );
							$action_key   = $action_data['action'];
							$action_timer = $action_data['timer'];
							$action_roles = $action_data['roles'];

							if ( self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								$classes[] = 'woopt';
								$classes[] = 'woopt_' . $action_key;
							}
						}
					}

					return $classes;
				}

				public static function woopt_get_regular_price( $regular_price, $product ) {
					$variation_id = 0;
					$product_id   = $product->get_id();

					if ( apply_filters( 'woopt_ignore', false, $product, 'regular_price' ) ) {
						return $regular_price;
					}

					if ( $product->is_type( 'variation' ) ) {
						$variation_id = $product_id;
						$product_id   = $product->get_parent_id();
					}

					// global actions
					if ( is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_base      = ! empty( $action_data['action_val']['base'] ) ? $action_data['action_val']['base'] : 'fa';
							$action_price     = isset( $action_data['action_val']['value'] ) ? (float) $action_data['action_val']['value'] : '';
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( ( $action_price !== '' ) && ( $action_key === 'set_regularprice' ) && self::woopt_check_apply( $product_id, $action_apply, $action_apply_val ) && self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								if ( $action_base === 'pr' || $action_base === 'ps' ) {
									// percentage
									$action_price = (float) $regular_price * $action_price / 100;
								}

								$regular_price = $action_price;
							}
						}
					}

					// product actions
					$actions = get_post_meta( $product_id, 'woopt_actions', true );

					if ( is_array( $actions ) && ( count( $actions ) > 0 ) ) {
						foreach ( $actions as $action ) {
							$action_data  = self::woopt_action_data( $action );
							$action_key   = $action_data['action'];
							$action_base  = ! empty( $action_data['action_val']['base'] ) ? $action_data['action_val']['base'] : 'fa';
							$action_price = isset( $action_data['action_val']['value'] ) ? (float) $action_data['action_val']['value'] : '';
							$action_timer = $action_data['timer'];
							$action_roles = $action_data['roles'];

							if ( ( $action_price !== '' ) && ( $action_key === 'set_regularprice' ) && self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								if ( $action_base === 'pr' || $action_base === 'ps' ) {
									// percentage
									$action_price = (float) $regular_price * $action_price / 100;
								}

								$regular_price = $action_price;
							}
						}
					}

					// global actions for variation
					if ( $variation_id && is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_base      = ! empty( $action_data['action_val']['base'] ) ? $action_data['action_val']['base'] : 'fa';
							$action_price     = isset( $action_data['action_val']['value'] ) ? (float) $action_data['action_val']['value'] : '';
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( ( $action_apply !== 'apply_product' ) && ( $action_apply !== 'apply_combination' ) ) {
								continue;
							}

							if ( ( $action_price !== '' ) && ( $action_key === 'set_regularprice' ) && self::woopt_check_apply( $variation_id, $action_apply, $action_apply_val, true ) && self::woopt_check_timer( $action_timer, $variation_id ) && self::woopt_check_roles( $action_roles ) ) {
								if ( $action_base === 'pr' || $action_base === 'ps' ) {
									// percentage
									$action_price = (float) $regular_price * $action_price / 100;
								}

								$regular_price = $action_price;
							}
						}
					}

					return apply_filters( 'woopt_get_regular_price', $regular_price, $product );
				}

				public static function woopt_get_sale_price( $sale_price, $product ) {
					$variation_id = 0;
					$product_id   = $product->get_id();

					if ( apply_filters( 'woopt_ignore', false, $product, 'sale_price' ) ) {
						return $sale_price;
					}

					if ( $product->is_type( 'variation' ) ) {
						$variation_id = $product_id;
						$product_id   = $product->get_parent_id();
					}

					$regular_price = apply_filters( 'woopt_product_regular_price', $product->get_regular_price( 'edit' ), $product );

					// global actions
					if ( is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_base      = ! empty( $action_data['action_val']['base'] ) ? $action_data['action_val']['base'] : 'fa';
							$action_price     = isset( $action_data['action_val']['value'] ) ? (float) $action_data['action_val']['value'] : '';
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( ( $action_price !== '' ) && ( $action_key === 'set_saleprice' ) && self::woopt_check_apply( $product_id, $action_apply, $action_apply_val ) && self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								switch ( $action_base ) {
									case 'ps':
										// percentage of sale price
										$action_price = (float) $sale_price * $action_price / 100;
										break;
									case 'pr':
										// percentage of regular price
										$action_price = (float) $regular_price * $action_price / 100;
										break;
								}

								$sale_price = $action_price;
							}
						}
					}

					// product actions
					$actions = get_post_meta( $product_id, 'woopt_actions', true );

					if ( is_array( $actions ) && ( count( $actions ) > 0 ) ) {
						foreach ( $actions as $action ) {
							$action_data  = self::woopt_action_data( $action );
							$action_key   = $action_data['action'];
							$action_base  = ! empty( $action_data['action_val']['base'] ) ? $action_data['action_val']['base'] : 'fa';
							$action_price = isset( $action_data['action_val']['value'] ) ? (float) $action_data['action_val']['value'] : '';
							$action_timer = $action_data['timer'];
							$action_roles = $action_data['roles'];

							if ( ( $action_price !== '' ) && ( $action_key === 'set_saleprice' ) && self::woopt_check_timer( $action_timer, $product_id ) && self::woopt_check_roles( $action_roles ) ) {
								switch ( $action_base ) {
									case 'ps':
										// percentage of sale price
										$action_price = (float) $sale_price * $action_price / 100;
										break;
									case 'pr':
										// percentage of regular price
										$action_price = (float) $regular_price * $action_price / 100;
										break;
								}

								$sale_price = $action_price;
							}
						}
					}

					// global actions for variation
					if ( $variation_id && is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
						foreach ( self::$global_actions as $global_action ) {
							$action_data      = self::woopt_action_data( $global_action );
							$action_apply     = $action_data['apply'];
							$action_apply_val = $action_data['apply_val'];
							$action_key       = $action_data['action'];
							$action_base      = ! empty( $action_data['action_val']['base'] ) ? $action_data['action_val']['base'] : 'fa';
							$action_price     = isset( $action_data['action_val']['value'] ) ? (float) $action_data['action_val']['value'] : '';
							$action_timer     = $action_data['timer'];
							$action_roles     = $action_data['roles'];

							if ( ( $action_apply !== 'apply_product' ) && ( $action_apply !== 'apply_combination' ) ) {
								continue;
							}

							if ( ( $action_price !== '' ) && ( $action_key === 'set_saleprice' ) && self::woopt_check_apply( $variation_id, $action_apply, $action_apply_val, true ) && self::woopt_check_timer( $action_timer, $variation_id ) && self::woopt_check_roles( $action_roles ) ) {
								switch ( $action_base ) {
									case 'ps':
										// percentage of sale price
										$action_price = (float) $sale_price * $action_price / 100;
										break;
									case 'pr':
										// percentage of regular price
										$action_price = (float) $regular_price * $action_price / 100;
										break;
								}

								$sale_price = $action_price;
							}
						}
					}

					return apply_filters( 'woopt_get_sale_price', $sale_price, $product );
				}

				public static function woopt_get_price( $price, $product ) {
					if ( apply_filters( 'woopt_ignore', false, $product, 'price' ) ) {
						return $price;
					}

					if ( $product->is_on_sale() ) {
						return self::woopt_get_sale_price( $price, $product );
					}

					return self::woopt_get_regular_price( $price, $product );
				}

				function variation_prices_hash( $hash ) {
					$hash[] = get_current_user_id();

					return $hash;
				}

				public static function woopt_is_in_stock( $in_stock, $product ) {
					if ( apply_filters( 'woopt_ignore', false, $product, 'in_stock' ) ) {
						return $in_stock;
					}

					$in_stock = self::woopt_get_action_result( $in_stock, $product, 'set_instock', 'set_outofstock' );

					return apply_filters( 'woopt_is_in_stock', $in_stock, $product );
				}

				public static function woopt_is_visible( $visible, $product_id ) {
					$product = wc_get_product( $product_id );

					if ( apply_filters( 'woopt_ignore', false, $product, 'visible' ) ) {
						return $visible;
					}

					$visible = self::woopt_get_action_result( $visible, $product, 'set_visible', 'set_hidden' );

					return apply_filters( 'woopt_is_visible', $visible, $product );
				}

				public static function woopt_is_featured( $featured, $product ) {
					if ( is_numeric( $product ) ) {
						$product = wc_get_product( $product );
					}

					if ( apply_filters( 'woopt_ignore', false, $product, 'featured' ) ) {
						return $featured;
					}

					$featured = self::woopt_get_action_result( $featured, $product, 'set_featured', 'set_unfeatured' );

					return apply_filters( 'woopt_is_featured', $featured, $product );
				}

				public static function woopt_is_purchasable( $purchasable, $product ) {
					if ( apply_filters( 'woopt_ignore', false, $product, 'purchasable' ) ) {
						return $purchasable;
					}

					$purchasable = self::woopt_get_action_result( $purchasable, $product, 'set_purchasable', 'set_unpurchasable' );

					return apply_filters( 'woopt_is_purchasable', $purchasable, $product );
				}

				public static function woopt_sold_individually( $sold_individually, $product ) {
					if ( apply_filters( 'woopt_ignore', false, $product, 'sold_individually' ) ) {
						return $sold_individually;
					}

					$sold_individually = self::woopt_get_action_result( $sold_individually, $product, 'enable_sold_individually', 'disable_sold_individually' );

					return apply_filters( 'woopt_sold_individually', $sold_individually, $product );
				}

				function register_settings() {
					// settings
					register_setting( 'woopt_settings', 'woopt_features' );
					register_setting( 'woopt_settings', 'woopt_actions' );
				}

				function admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Product Timer', 'woo-product-timer' ), esc_html__( 'Product Timer', 'woo-product-timer' ), 'manage_options', 'wpclever-woopt', [
						$this,
						'admin_menu_content'
					] );
				}

				function admin_menu_content() {
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'global';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Product Timer', 'woo-product-timer' ) . ' ' . WOOPT_VERSION . ' ' . ( defined( 'WOOPT_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'woo-product-timer' ) . '</span>' : '' ); ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( /* translators: %s is the stars */ esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-product-timer' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOPT_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'woo-product-timer' ); ?></a> |
                                <a href="<?php echo esc_url( WOOPT_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'woo-product-timer' ); ?></a> |
                                <a href="<?php echo esc_url( WOOPT_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'woo-product-timer' ); ?></a>
                            </p>
                        </div>
						<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php esc_html_e( 'Settings updated.', 'woo-product-timer' ); ?></p>
                            </div>
						<?php } ?>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woopt&tab=how' ); ?>" class="<?php echo esc_attr( $active_tab === 'how' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'How to use?', 'woo-product-timer' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woopt&tab=global' ); ?>" class="<?php echo esc_attr( $active_tab === 'global' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
									<?php esc_html_e( 'Global Timer', 'woo-product-timer' ); ?>
                                </a> <a href="<?php echo esc_url( WOOPT_DOCS ); ?>" class="nav-tab" target="_blank">
									<?php esc_html_e( 'Docs', 'woo-product-timer' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woopt&tab=premium' ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'woo-product-timer' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-kit' ); ?>" class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'woo-product-timer' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'how' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
										<?php esc_html_e( '1. Global timer: Switch to Global Timer tab to set the timer for all products, categories or tags.', 'woo-product-timer' ); ?>
                                    </p>
                                    <p>
										<?php esc_html_e( '2. Product basis timer: When adding/editing the product you can choose the Timer tab then add action & time conditional.', 'woo-product-timer' ); ?>
                                    </p>
                                </div>
							<?php } elseif ( $active_tab === 'global' ) {
								// delete product transients to refresh variable prices
								wc_delete_product_transients();
								?>
                                <form method="post" action="options.php">
                                    <table class="form-table">
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Performance', 'woo-product-timer' ); ?>
                                            </th>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <input type="checkbox" name="woopt_features[]" value="stock" <?php echo esc_attr( empty( self::$features ) || in_array( 'stock', self::$features ) ? 'checked' : '' ); ?>/>
														<?php esc_html_e( 'Stock (in stock, out of stock)', 'woo-product-timer' ); ?>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="woopt_features[]" value="visibility" <?php echo esc_attr( empty( self::$features ) || in_array( 'visibility', self::$features ) ? 'checked' : '' ); ?>/>
														<?php esc_html_e( 'Visibility (visible, hidden)', 'woo-product-timer' ); ?>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="woopt_features[]" value="featured" <?php echo esc_attr( empty( self::$features ) || in_array( 'featured', self::$features ) ? 'checked' : '' ); ?>/>
														<?php esc_html_e( 'Featured (featured, unfeatured)', 'woo-product-timer' ); ?>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="woopt_features[]" value="purchasable" <?php echo esc_attr( empty( self::$features ) || in_array( 'purchasable', self::$features ) ? 'checked' : '' ); ?>/>
														<?php esc_html_e( 'Purchasable (purchasable, unpurchasable)', 'woo-product-timer' ); ?>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="woopt_features[]" value="price" <?php echo esc_attr( empty( self::$features ) || in_array( 'price', self::$features ) ? 'checked' : '' ); ?>/>
														<?php esc_html_e( 'Price (regular price, sale price)', 'woo-product-timer' ); ?>
                                                    </li>
                                                    <li>
                                                        <input type="checkbox" name="woopt_features[]" value="individually" <?php echo esc_attr( empty( self::$features ) || in_array( 'individually', self::$features ) ? 'checked' : '' ); ?>/>
														<?php esc_html_e( 'Sold individually (enable, disable)', 'woo-product-timer' ); ?>
                                                    </li>
                                                </ul>
                                                <span class="description"><?php esc_html_e( 'Uncheck the feature(s) you don\'t use in all timers for better performance.', 'woo-product-timer' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Current time', 'woo-product-timer' ); ?>
                                            </th>
                                            <td>
                                                <code><?php echo current_time( 'l' ); ?></code>
                                                <code><?php echo current_time( 'm/d/Y' ); ?></code>
                                                <code><?php echo current_time( 'h:i a' ); ?></code>
                                                <code><?php echo esc_html__( 'Week No.', 'woo-product-timer' ) . ' ' . current_time( 'W' ); ?></code>
                                                <a href="<?php echo admin_url( 'options-general.php' ); ?>" target="_blank"><?php esc_html_e( 'Date/time settings', 'woo-product-timer' ); ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Actions', 'woo-product-timer' ); ?>
                                            </th>
                                            <td>
                                                <div class="woopt_actions">
													<?php
													if ( is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
														foreach ( self::$global_actions as $key => $action ) {
															self::action( $key, $action, true );
														}
													} else {
														self::action( 0, null, true, true );
													}
													?>
                                                </div>
                                                <div class="woopt_add_action">
                                                    <div>
                                                        <a href="https://wpclever.net/downloads/product-timer?utm_source=pro&utm_medium=woopt&utm_campaign=wporg" target="_blank" class="button" onclick="return confirm('This feature only available in Premium Version!\nBuy it now? Just $29')">
															<?php esc_html_e( '+ Add action', 'woo-product-timer' ); ?>
                                                        </a> <a href="#" class="woopt_expand_all">
															<?php esc_html_e( 'Expand All', 'woo-product-timer' ); ?>
                                                        </a> <a href="#" class="woopt_collapse_all">
															<?php esc_html_e( 'Collapse All', 'woo-product-timer' ); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th colspan="2"><?php esc_html_e( 'Suggestion', 'woo-product-timer' ); ?></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                To display custom engaging real-time messages on any wished positions, please install
                                                <a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC Smart Messages</a> plugin. It's free!
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Wanna save your precious time working on variations? Try our brand-new free plugin
                                                <a href="https://wordpress.org/plugins/wpc-variation-bulk-editor/" target="_blank">WPC Variation Bulk Editor</a> and
                                                <a href="https://wordpress.org/plugins/wpc-variation-duplicator/" target="_blank">WPC Variation Duplicator</a>.
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
												<?php settings_fields( 'woopt_settings' ); ?><?php submit_button(); ?>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>Get the Premium Version just $29!
                                        <a href="https://wpclever.net/downloads/product-timer?utm_source=pro&utm_medium=woopt&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/product-timer</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Add multiple actions.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function admin_enqueue_scripts( $hook ) {
					if ( apply_filters( 'woopt_ignore_backend_scripts', false, $hook ) ) {
						return null;
					}

					// wpcdpk
					wp_enqueue_style( 'wpcdpk', WOOPT_URI . 'assets/libs/wpcdpk/css/datepicker.css' );
					wp_enqueue_script( 'wpcdpk', WOOPT_URI . 'assets/libs/wpcdpk/js/datepicker.js', [ 'jquery' ], WOOPT_VERSION, true );

					// backend
					wp_enqueue_style( 'woopt-backend', WOOPT_URI . 'assets/css/backend.css', [ 'woocommerce_admin_styles' ], WOOPT_VERSION );
					wp_enqueue_script( 'woopt-backend', WOOPT_URI . 'assets/js/backend.js', [
						'jquery',
						'jquery-ui-sortable',
						'jquery-ui-dialog',
						'wc-enhanced-select',
						'selectWoo',
					], WOOPT_VERSION, true );
					wp_localize_script( 'woopt-backend', 'woopt_vars', [
						'nonce' => wp_create_nonce( 'woopt-security' )
					] );
				}

				function action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$how                  = '<a href="' . admin_url( 'admin.php?page=wpclever-woopt&tab=how' ) . '">' . esc_html__( 'How to use?', 'woo-product-timer' ) . '</a>';
						$global               = '<a href="' . admin_url( 'admin.php?page=wpclever-woopt&tab=global' ) . '">' . esc_html__( 'Global Timer', 'woo-product-timer' ) . '</a>';
						$links['wpc-premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-woopt&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'woo-product-timer' ) . '</a>';
						array_unshift( $links, $how, $global );
					}

					return (array) $links;
				}

				function row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = [
							'docs'    => '<a href="' . esc_url( WOOPT_DOCS ) . '" target="_blank">' . esc_html__( 'Docs', 'woo-product-timer' ) . '</a>',
							'support' => '<a href="' . esc_url( WOOPT_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'woo-product-timer' ) . '</a>',
						];

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function product_data_tabs( $tabs ) {
					$tabs['woopt'] = [
						'label'  => esc_html__( 'Timer', 'woo-product-timer' ),
						'target' => 'woopt_settings',
					];

					return $tabs;
				}

				function product_data_panels() {
					global $post, $thepostid, $product_object;

					if ( $product_object instanceof WC_Product ) {
						$product_id = $product_object->get_id();
					} elseif ( is_numeric( $thepostid ) ) {
						$product_id = $thepostid;
					} elseif ( $post instanceof WP_Post ) {
						$product_id = $post->ID;
					} else {
						$product_id = 0;
					}

					if ( ! $product_id ) {
						?>
                        <div id='woopt_settings' class='panel woocommerce_options_panel woopt_settings'>
                            <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'woo-product-timer' ); ?></p>
                        </div>
						<?php
						return;
					}

					$actions = get_post_meta( $product_id, 'woopt_actions', true );
					?>
                    <div id='woopt_settings' class='panel woocommerce_options_panel woopt_settings'>
                        <div class="woopt_global_timer"><span class="dashicons dashicons-admin-site"></span>
                            <a href="<?php echo admin_url( 'admin.php?page=wpclever-woopt&tab=global' ); ?>" target="_blank"><?php esc_html_e( 'Click here to configure the Global Timer', 'woo-product-timer' ); ?></a>
                        </div>
                        <div class="woopt_current_time">
							<?php esc_html_e( 'Current time', 'woo-product-timer' ); ?>
                            <code><?php echo current_time( 'l' ); ?></code>
                            <code><?php echo current_time( 'm/d/Y' ); ?></code>
                            <code><?php echo current_time( 'h:i a' ); ?></code>
                            <code><?php echo esc_html__( 'Week No.', 'woo-product-timer' ) . ' ' . current_time( 'W' ); ?></code>
                            <a href="<?php echo admin_url( 'options-general.php' ); ?>" target="_blank"><?php esc_html_e( 'Date/time settings', 'woo-product-timer' ); ?></a>
                        </div>
                        <div class="woopt_actions">
							<?php
							if ( is_array( $actions ) && ( count( $actions ) > 0 ) ) {
								foreach ( $actions as $key => $action ) {
									self::action( $key, $action );
								}
							} else {
								self::action( 0, null, false, true );
							}
							?>
                        </div>
                        <div class="woopt_add_action">
                            <div>
                                <a href="https://wpclever.net/downloads/product-timer?utm_source=pro&utm_medium=woopt&utm_campaign=wporg" target="_blank" class="button" onclick="return confirm('This feature only available in Premium Version!\nBuy it now? Just $29')">
									<?php esc_html_e( '+ Add action', 'woo-product-timer' ); ?>
                                </a> <a href="#" class="woopt_expand_all">
									<?php esc_html_e( 'Expand All', 'woo-product-timer' ); ?>
                                </a> <a href="#" class="woopt_collapse_all">
									<?php esc_html_e( 'Collapse All', 'woo-product-timer' ); ?>
                                </a>
                            </div>
                            <div>
                                <a href="#" class="woopt_save_actions button button-primary">
									<?php esc_html_e( 'Save actions', 'woo-product-timer' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function action( $key = 0, $action_val = null, $global = false, $active = false ) {
					if ( empty( $key ) || is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					$action_data = self::woopt_action_data( $action_val );
					$name        = isset( $action_data['name'] ) ? $action_data['name'] : '';
					$apply       = isset( $action_data['apply'] ) ? $action_data['apply'] : 'apply_all';
					$apply_val   = isset( $action_data['apply_val'] ) ? $action_data['apply_val'] : [];
					$action      = $action_data['action'];
					$action_val  = isset( $action_data['action_val'] ) ? $action_data['action_val'] : [];
					$base        = ! empty( $action_val['base'] ) ? $action_val['base'] : 'fa';
					$price       = isset( $action_val['value'] ) ? (float) $action_val['value'] : '';
					$conditional = $action_data['timer'];
					$roles       = $action_data['roles'];
					?>
                    <div class="woopt_action <?php echo esc_attr( $active ? 'active' : '' ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                        <div class="woopt_action_heading">
                            <span class="woopt_action_move"></span>
                            <span class="woopt_action_label"><span class="woopt_action_label_name"><?php echo esc_html( '#' . $key ); ?></span><span class="woopt_action_label_action"></span><span class="woopt_action_label_apply"></span></span>
                            <a href="https://wpclever.net/downloads/product-timer?utm_source=pro&utm_medium=woopt&utm_campaign=wporg" target="_blank" class="woopt_action_duplicate" onclick="return confirm('This feature only available in Premium Version!\nBuy it now? Just $29')">
								<?php esc_html_e( 'duplicate', 'woo-product-timer' ); ?>
                            </a>
                            <a href="#" class="woopt_action_remove"><?php esc_html_e( 'remove', 'woo-product-timer' ); ?></a>
                        </div>
                        <div class="woopt_action_content">
                            <div class="woopt_tr">
                                <div class="woopt_th"><?php esc_html_e( 'Name', 'woo-product-timer' ); ?></div>
                                <div class="woopt_td woopt_action_td">
                                    <p class="description"><?php esc_html_e( 'For management use only.', 'woo-product-timer' ); ?></p>
                                    <input type="text" class="text large-text woopt_action_name_input" name="woopt_actions[<?php echo esc_attr( $key ); ?>][name]" data-name="<?php echo esc_attr( '#' . $key ); ?>" value="<?php echo esc_attr( $name ); ?>"/>
                                </div>
                            </div>
							<?php if ( $global ) { ?>
                                <input type="hidden" name="woopt_actions[<?php echo esc_attr( $key ); ?>][type]" value="global"/>
                                <div class="woopt_tr">
                                    <div class="woopt_th"><?php esc_html_e( 'Apply for', 'woo-product-timer' ); ?></div>
                                    <div class="woopt_td woopt_action_td">
                                        <select class="woopt_apply_selector" name="woopt_actions[<?php echo esc_attr( $key ); ?>][apply]">
                                            <option value="apply_all" <?php selected( $apply, 'apply_all' ); ?>><?php esc_html_e( 'All products', 'woo-product-timer' ); ?></option>
                                            <option value="apply_variation" <?php selected( $apply, 'apply_variation' ); ?>><?php esc_html_e( 'Variations only', 'woo-product-timer' ); ?></option>
                                            <option value="apply_not_variation" <?php selected( $apply, 'apply_not_variation' ); ?>><?php esc_html_e( 'Non-variation products', 'woo-product-timer' ); ?></option>
                                            <option value="apply_product" <?php selected( $apply, 'apply_product' ); ?>><?php esc_html_e( 'Products', 'woo-product-timer' ); ?></option>
                                            <option value="apply_combination" <?php selected( $apply, 'apply_combination' ); ?>><?php esc_html_e( 'Combined sources', 'woo-product-timer' ); ?></option>
											<?php
											//$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );
											$taxonomies = get_object_taxonomies( 'product', 'objects' );

											foreach ( $taxonomies as $taxonomy ) {
												echo '<option value="apply_' . $taxonomy->name . '" ' . ( $apply === 'apply_' . $taxonomy->name ? 'selected' : '' ) . '>' . $taxonomy->label . '</option>';
											}
											?>
                                        </select>
                                    </div>
                                </div>
                                <div class="woopt_tr hide_apply show_if_apply_product">
                                    <div class="woopt_th"><?php esc_html_e( 'Products', 'woo-product-timer' ); ?></div>
                                    <div class="woopt_td woopt_action_td">
                                        <select class="wc-product-search woopt-product-search" multiple="multiple" name="woopt_actions[<?php echo esc_attr( $key ); ?>][apply_val][products][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woo-product-timer' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-val="<?php echo esc_attr( $apply === 'apply_product' ? $apply_val : '' ); ?>">
											<?php
											if ( is_string( $apply_val ) ) {
												$product_ids = explode( ',', $apply_val );
											} elseif ( is_array( $apply_val ) ) {
												$product_ids = ! empty( $apply_val['products'] ) ? $apply_val['products'] : [];
											} else {
												$product_ids = [];
											}

											foreach ( $product_ids as $product_id ) {
												if ( $product = wc_get_product( $product_id ) ) {
													echo '<option value="' . esc_attr( $product_id ) . '" selected>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
												}
											}
											?>
                                        </select>
                                    </div>
                                </div>
                                <div class="woopt_tr hide_apply show_if_apply_combination">
                                    <div class="woopt_th"><?php esc_html_e( 'Applied conditions', 'woo-product-timer' ); ?></div>
                                    <div class="woopt_td woopt_action_td">
                                        <div class="woopt_apply_combinations">
                                            <p class="description"><?php esc_html_e( '* Configure to find products that match all listed conditions.', 'woo-product-timer' ); ?></p>
											<?php
											$combined = ! empty( $apply_val['combined'] ) ? (array) $apply_val['combined'] : [];

											if ( ! empty( $combined ) ) {
												foreach ( $combined as $combination_key => $combination_item ) {
													self::apply_combination( $key, $combination_key, $combination_item );
												}
											}
											?>
                                        </div>
                                        <div class="woopt_add_apply_combination">
                                            <a class="woopt_new_apply_combination" href="#"><?php esc_attr_e( '+ Add condition', 'woo-product-timer' ); ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="woopt_tr show_apply hide_if_apply_all hide_if_apply_variation hide_if_apply_not_variation hide_if_apply_product hide_if_apply_category hide_if_apply_combination">
                                    <div class="woopt_th woopt_apply_text"><?php esc_html_e( 'Terms', 'woo-product-timer' ); ?></div>
                                    <div class="woopt_td woopt_action_td">
										<?php
										if ( is_string( $apply_val ) ) {
											$term_slugs = array_map( 'trim', explode( ',', $apply_val ) );
										} elseif ( is_array( $apply_val ) ) {
											$term_slugs = ! empty( $apply_val['terms'] ) ? $apply_val['terms'] : [];
										} else {
											$term_slugs = [];
										}
										?>
                                        <select class="woopt_terms" multiple="multiple" name="woopt_actions[<?php echo esc_attr( $key ); ?>][apply_val][terms][]" data-<?php echo esc_attr( $apply ); ?>="<?php echo esc_attr( implode( ',', $term_slugs ) ); ?>">
											<?php
											if ( ! empty( $term_slugs ) ) {
												$taxonomy = substr( $apply, 6 );

												foreach ( $term_slugs as $ts ) {
													if ( $term = get_term_by( 'slug', $ts, $taxonomy ) ) {
														echo '<option value="' . esc_attr( $ts ) . '" selected>' . esc_html( $term->name ) . '</option>';
													}
												}
											}
											?>
                                        </select>
                                    </div>
                                </div>
							<?php } else { ?>
                                <input type="hidden" name="woopt_actions[<?php echo esc_attr( $key ); ?>][type]" value="product"/>
							<?php } ?>
                            <div class="woopt_tr">
                                <div class="woopt_th"><?php esc_html_e( 'Action', 'woo-product-timer' ); ?></div>
                                <div class="woopt_td woopt_action_td">
									<span>
                                        <select class="woopt_action_selector" name="woopt_actions[<?php echo esc_attr( $key ); ?>][action]">
                                            <option value=""><?php esc_html_e( 'Choose action', 'woo-product-timer' ); ?></option>
                                            <option value="set_instock" <?php selected( $action, 'set_instock' ); ?>><?php esc_html_e( 'Set in stock', 'woo-product-timer' ); ?></option>
                                            <option value="set_outofstock" <?php selected( $action, 'set_outofstock' ); ?>><?php esc_html_e( 'Set out of stock', 'woo-product-timer' ); ?></option>
                                            <option value="set_visible" <?php selected( $action, 'set_visible' ); ?>><?php esc_html_e( 'Set visible', 'woo-product-timer' ); ?></option>
                                            <option value="set_hidden" <?php selected( $action, 'set_hidden' ); ?>><?php esc_html_e( 'Set hidden', 'woo-product-timer' ); ?></option>
                                            <option value="set_featured" <?php selected( $action, 'set_featured' ); ?>><?php esc_html_e( 'Set featured', 'woo-product-timer' ); ?></option>
                                            <option value="set_unfeatured" <?php selected( $action, 'set_unfeatured' ); ?>><?php esc_html_e( 'Set unfeatured', 'woo-product-timer' ); ?></option>
                                            <option value="set_purchasable" <?php selected( $action, 'set_purchasable' ); ?>><?php esc_html_e( 'Set purchasable', 'woo-product-timer' ); ?></option>
                                            <option value="set_unpurchasable" <?php selected( $action, 'set_unpurchasable' ); ?>><?php esc_html_e( 'Set unpurchasable', 'woo-product-timer' ); ?></option>
                                            <option value="set_regularprice" <?php selected( $action, 'set_regularprice' ); ?>><?php esc_html_e( 'Set regular price', 'woo-product-timer' ); ?></option>
                                            <option value="set_saleprice" <?php selected( $action, 'set_saleprice' ); ?>><?php esc_html_e( 'Set sale price', 'woo-product-timer' ); ?></option>
                                            <option value="enable_sold_individually" <?php selected( $action, 'enable_sold_individually' ); ?>><?php esc_html_e( 'Enable sold individually', 'woo-product-timer' ); ?></option>
                                            <option value="disable_sold_individually" <?php selected( $action, 'disable_sold_individually' ); ?>><?php esc_html_e( 'Disable sold individually', 'woo-product-timer' ); ?></option>
                                        </select>
                                    </span>
                                    <span class="woopt_hide woopt_show_if_set_regularprice woopt_show_if_set_saleprice">
                                        <input class="woopt_price" name="woopt_actions[<?php echo esc_attr( $key ); ?>][action_val][value]" value="<?php echo $price; ?>" type="number" step="any" style="width: 80px; float: left"/>
                                    </span>
                                    <span class="woopt_hide woopt_show_if_set_regularprice woopt_show_if_set_saleprice">
										<select name="woopt_actions[<?php echo esc_attr( $key ); ?>][action_val][base]" class="woopt_action_price_base">
											<option value="ps" <?php selected( 'ps', $base ); ?>><?php esc_html_e( '% of sale price', 'woo-product-timer' ); ?></option>
											<option value="pr" <?php selected( 'pr', $base ); ?> data-set_saleprice="<?php esc_attr_e( '% of regular price', 'woo-product-timer' ); ?>" data-set_regularprice="<?php esc_attr_e( '%', 'woo-product-timer' ); ?>"><?php esc_html_e( '% of regular price', 'woo-product-timer' ); ?></option>
											<option value="fa" <?php selected( 'fa', $base ); ?>><?php echo get_woocommerce_currency_symbol(); ?></option>
										</select>
									</span>
                                </div>
                            </div>
                            <div class="woopt_tr">
                                <div class="woopt_th"><?php esc_html_e( 'Time conditions', 'woo-product-timer' ); ?></div>
                                <div class="woopt_td">
                                    <div class="woopt_timer">
                                        <p class="description"><?php esc_html_e( '* Configure date and time of the action that must match all listed conditions.', 'woo-product-timer' ); ?></p>
										<?php
										if ( is_array( $conditional ) && ( count( $conditional ) > 0 ) ) {
											foreach ( $conditional as $conditional_key => $conditional_item ) {
												self::time( $key, $conditional_key, $conditional_item );
											}
										} else {
											self::time( $key );
										}
										?>
                                    </div>
                                    <div class="woopt_add_time">
                                        <a href="#" class="woopt_new_time"><?php esc_html_e( '+ Add time', 'woo-product-timer' ); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="woopt_tr">
                                <div class="woopt_th"><?php esc_html_e( 'User roles', 'woo-product-timer' ); ?></div>
                                <div class="woopt_td">
                                    <div class="woopt_user_roles">
                                        <p class="description"><?php esc_html_e( '* Configure user role(s) that apply the action.', 'woo-product-timer' ); ?></p>
										<?php
										global $wp_roles;

										if ( is_array( $roles ) ) {
											$roles_arr = $roles;
										} elseif ( is_string( $roles ) ) {
											$roles_arr = explode( ',', $roles );
										} else {
											$roles_arr = [];
										}

										if ( empty( $roles ) || in_array( 'all', $roles_arr ) ) {
											$roles_arr = [ 'all' ];
										}

										echo '<select class="woopt_user_roles_select" multiple="multiple" style="height: 120px" name="woopt_actions[' . esc_attr( $key ) . '][roles][]">';
										echo '<option value="all" ' . ( in_array( 'all', $roles_arr ) ? 'selected' : '' ) . '>' . esc_html__( 'All', 'woo-product-timer' ) . '</option>';
										echo '<option value="guest" ' . ( in_array( 'guest', $roles_arr ) ? 'selected' : '' ) . '>' . esc_html__( 'Guest (not logged in)', 'woo-product-timer' ) . '</option>';

										if ( ! empty( $wp_roles->roles ) ) {
											foreach ( $wp_roles->roles as $role => $details ) {
												echo '<option value="' . esc_attr( $role ) . '" ' . ( in_array( $role, $roles_arr ) ? 'selected' : '' ) . '>' . esc_html( $details['name'] ) . '</option>';
											}
										}

										echo '</select>';
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php
				}

				function apply_combination( $key = 0, $combination_key = 0, $combination_item = null ) {
					if ( empty( $key ) || is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					if ( empty( $combination_key ) || is_numeric( $combination_key ) ) {
						$combination_key = self::generate_key();
					}

					$combination_item_type = ! empty( $combination_item['type'] ) ? $combination_item['type'] : '';
					$combination_item_val  = ! empty( $combination_item['val'] ) ? (array) $combination_item['val'] : [];
					?>
                    <div class="woopt_apply_combination">
                        <span class="woopt_apply_combination_remove">&times;</span> <span>
                                    <select class="woopt_apply_combination_select" name="woopt_actions[<?php echo esc_attr( $key ); ?>][apply_val][combined][<?php echo esc_attr( $combination_key ); ?>][type]">
	                                    <option value="variation" <?php selected( $combination_item_type, 'variation' ); ?>><?php esc_html_e( 'Variations only', 'woo-product-timer' ); ?></option>
	                                    <option value="not_variation" <?php selected( $combination_item_type, 'not_variation' ); ?>><?php esc_html_e( 'Non-variation products', 'woo-product-timer' ); ?></option>
                                        <?php
                                        $taxonomies = get_object_taxonomies( 'product', 'objects' ); //$taxonomies = get_taxonomies( [ 'object_type' => [ 'product' ] ], 'objects' );

                                        foreach ( $taxonomies as $taxonomy ) {
	                                        echo '<option value="' . esc_attr( $taxonomy->name ) . '" ' . ( $combination_item_type === $taxonomy->name ? 'selected' : '' ) . '>' . esc_html( $taxonomy->label ) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </span> <span>
                                    <select class="woopt_apply_terms woopt_apply_combination_val" multiple="multiple" name="woopt_actions[<?php echo esc_attr( $key ); ?>][apply_val][combined][<?php echo esc_attr( $combination_key ); ?>][val][]">
                                        <?php
                                        if ( ! empty( $combination_item_val ) ) {
	                                        foreach ( $combination_item_val as $t ) {
		                                        if ( $term = get_term_by( 'slug', $t, $combination_item_type ) ) {
			                                        echo '<option value="' . esc_attr( $t ) . '" selected>' . esc_html( $term->name ) . '</option>';
		                                        }
	                                        }
                                        }
                                        ?>
                                    </select>
                                </span>
                    </div>
					<?php
				}

				function time( $key = 0, $time_key = 0, $time_data = [] ) {
					if ( empty( $key ) || is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					if ( empty( $time_key ) || is_numeric( $time_key ) ) {
						$time_key = self::generate_key();
					}

					$type = ! empty( $time_data['type'] ) ? $time_data['type'] : 'every_day';
					$val  = ! empty( $time_data['val'] ) ? $time_data['val'] : '';
					$date = $date_time = $date_multi = $date_range = $from = $to = $time = $weekday = $monthday = $weekno = $monthno = $number = '';

					switch ( $type ) {
						case 'date_on':
						case 'date_before':
						case 'date_after':
							$date = $val;
							break;
						case 'date_time_before':
						case 'date_time_after':
							$date_time = $val;
							break;
						case 'date_multi':
							$date_multi = $val;
							break;
						case 'date_range':
							$date_range = $val;
							break;
						case 'time_range':
							$time_range = array_map( 'trim', explode( '-', (string) $val ) );
							$from       = ! empty( $time_range[0] ) ? $time_range[0] : '';
							$to         = ! empty( $time_range[1] ) ? $time_range[1] : '';
							break;
						case 'time_before':
						case 'time_after':
							$time = $val;
							break;
						case 'weekly_every':
							$weekday = $val;
							break;
						case 'week_no':
							$weekno = $val;
							break;
						case 'monthly_every':
							$monthday = $val;
							break;
						case 'month_no':
							$monthno = $val;
							break;
						case 'days_less_published':
						case 'days_greater_published':
							$number = $val;
							break;
						default:
							$val = '';
					}
					?>
                    <div class="woopt_time">
                        <input type="hidden" class="woopt_time_val" name="woopt_actions[<?php echo esc_attr( $key ); ?>][timer][<?php echo esc_attr( $time_key ); ?>][val]" value="<?php echo esc_attr( $val ); ?>"/>
                        <span class="woopt_time_remove">&times;</span> <span>
							<select class="woopt_time_type" name="woopt_actions[<?php echo esc_attr( $key ); ?>][timer][<?php echo esc_attr( $time_key ); ?>][type]">
								<option value=""><?php esc_html_e( 'Choose the time', 'woo-product-timer' ); ?></option>
								<option value="date_on" data-show="date" <?php selected( $type, 'date_on' ); ?>><?php esc_html_e( 'On the date', 'woo-product-timer' ); ?></option>
                                <option value="date_time_before" data-show="date_time" <?php selected( $type, 'date_time_before' ); ?>><?php esc_html_e( 'Before date & time', 'woo-product-timer' ); ?></option>
								<option value="date_time_after" data-show="date_time" <?php selected( $type, 'date_time_after' ); ?>><?php esc_html_e( 'After date & time', 'woo-product-timer' ); ?></option>
								<option value="date_before" data-show="date" <?php selected( $type, 'date_before' ); ?>><?php esc_html_e( 'Before date', 'woo-product-timer' ); ?></option>
								<option value="date_after" data-show="date" <?php selected( $type, 'date_after' ); ?>><?php esc_html_e( 'After date', 'woo-product-timer' ); ?></option>
								<option value="date_multi" data-show="date_multi" <?php selected( $type, 'date_multi' ); ?>><?php esc_html_e( 'Multiple dates', 'woo-product-timer' ); ?></option>
								<option value="date_range" data-show="date_range" <?php selected( $type, 'date_range' ); ?>><?php esc_html_e( 'Date range', 'woo-product-timer' ); ?></option>
								<option value="date_even" data-show="none" <?php selected( $type, 'date_even' ); ?>><?php esc_html_e( 'All even dates', 'woo-product-timer' ); ?></option>
								<option value="date_odd" data-show="none" <?php selected( $type, 'date_odd' ); ?>><?php esc_html_e( 'All odd dates', 'woo-product-timer' ); ?></option>
								<option value="time_range" data-show="time_range" <?php selected( $type, 'time_range' ); ?>><?php esc_html_e( 'Daily time range', 'woo-product-timer' ); ?></option>
                                <option value="time_before" data-show="time" <?php selected( $type, 'time_before' ); ?>><?php esc_html_e( 'Daily before time', 'woo-product-timer' ); ?></option>
								<option value="time_after" data-show="time" <?php selected( $type, 'time_after' ); ?>><?php esc_html_e( 'Daily after time', 'woo-product-timer' ); ?></option>
                                <option value="weekly_every" data-show="weekday" <?php selected( $type, 'weekly_every' ); ?>><?php esc_html_e( 'Weekly on every', 'woo-product-timer' ); ?></option>
                                <option value="week_even" data-show="none" <?php selected( $type, 'week_even' ); ?>><?php esc_html_e( 'All even weeks', 'woo-product-timer' ); ?></option>
								<option value="week_odd" data-show="none" <?php selected( $type, 'week_odd' ); ?>><?php esc_html_e( 'All odd weeks', 'woo-product-timer' ); ?></option>
                                <option value="week_no" data-show="weekno" <?php selected( $type, 'week_no' ); ?>><?php esc_html_e( 'On week No.', 'woo-product-timer' ); ?></option>
                                <option value="monthly_every" data-show="monthday" <?php selected( $type, 'monthly_every' ); ?>><?php esc_html_e( 'Monthly on the', 'woo-product-timer' ); ?></option>
                                <option value="month_no" data-show="monthno" <?php selected( $type, 'month_no' ); ?>><?php esc_html_e( 'On month No.', 'woo-product-timer' ); ?></option>
                                <option value="days_less_published" data-show="number" <?php selected( $type, 'days_less_published' ); ?>><?php esc_html_e( 'Days of being published are smaller than', 'woo-product-timer' ); ?></option>
                                <option value="days_greater_published" data-show="number" <?php selected( $type, 'days_greater_published' ); ?>><?php esc_html_e( 'Days of being published are bigger than', 'woo-product-timer' ); ?></option>
                                <option value="every_day" data-show="none" <?php selected( $type, 'every_day' ); ?>><?php esc_html_e( 'Everyday', 'woo-product-timer' ); ?></option>
							</select>
						</span> <span class="woopt_hide woopt_show_if_date_time">
							<input value="<?php echo esc_attr( $date_time ); ?>" class="woopt_dpk_date_time woopt_date_time_input" type="text" readonly="readonly" style="width: 300px"/>
						</span> <span class="woopt_hide woopt_show_if_date">
							<input value="<?php echo esc_attr( $date ); ?>" class="woopt_dpk_date woopt_date_input" type="text" readonly="readonly" style="width: 300px"/>
						</span> <span class="woopt_hide woopt_show_if_date_range">
							<input value="<?php echo esc_attr( $date_range ); ?>" class="woopt_dpk_date_range woopt_date_input" type="text" readonly="readonly" style="width: 300px"/>
						</span> <span class="woopt_hide woopt_show_if_date_multi">
							<input value="<?php echo esc_attr( $date_multi ); ?>" class="woopt_dpk_date_multi woopt_date_input" type="text" readonly="readonly" style="width: 300px"/>
						</span> <span class="woopt_hide woopt_show_if_time_range">
							<input value="<?php echo esc_attr( $from ); ?>" class="woopt_dpk_time woopt_time_from woopt_time_input" type="text" readonly="readonly" style="width: 300px" placeholder="from"/>
							<input value="<?php echo esc_attr( $to ); ?>" class="woopt_dpk_time woopt_time_to woopt_time_input" type="text" readonly="readonly" style="width: 300px" placeholder="to"/>
						</span> <span class="woopt_hide woopt_show_if_time">
							<input value="<?php echo esc_attr( $time ); ?>" class="woopt_dpk_time woopt_time_on woopt_time_input" type="text" readonly="readonly" style="width: 300px"/>
						</span> <span class="woopt_hide woopt_show_if_weekday">
							<select class="woopt_weekday">
                                <option value="mon" <?php selected( $weekday, 'mon' ); ?>><?php esc_html_e( 'Monday', 'woo-product-timer' ); ?></option>
                                <option value="tue" <?php selected( $weekday, 'tue' ); ?>><?php esc_html_e( 'Tuesday', 'woo-product-timer' ); ?></option>
                                <option value="wed" <?php selected( $weekday, 'wed' ); ?>><?php esc_html_e( 'Wednesday', 'woo-product-timer' ); ?></option>
                                <option value="thu" <?php selected( $weekday, 'thu' ); ?>><?php esc_html_e( 'Thursday', 'woo-product-timer' ); ?></option>
                                <option value="fri" <?php selected( $weekday, 'fri' ); ?>><?php esc_html_e( 'Friday', 'woo-product-timer' ); ?></option>
                                <option value="sat" <?php selected( $weekday, 'sat' ); ?>><?php esc_html_e( 'Saturday', 'woo-product-timer' ); ?></option>
                                <option value="sun" <?php selected( $weekday, 'sun' ); ?>><?php esc_html_e( 'Sunday', 'woo-product-timer' ); ?></option>
                            </select>
						</span> <span class="woopt_hide woopt_show_if_monthday">
							<select class="woopt_monthday">
                                <?php for ( $i = 1; $i < 32; $i ++ ) {
	                                echo '<option value="' . esc_attr( $i ) . '" ' . ( (int) $monthday === $i ? 'selected' : '' ) . '>' . $i . '</option>';
                                } ?>
                            </select>
						</span> <span class="woopt_hide woopt_show_if_weekno">
							<select class="woopt_weekno">
                                <?php
                                for ( $i = 1; $i < 54; $i ++ ) {
	                                echo '<option value="' . esc_attr( $i ) . '" ' . ( (int) $weekno === $i ? 'selected' : '' ) . '>' . $i . '</option>';
                                }
                                ?>
                            </select>
						</span> <span class="woopt_hide woopt_show_if_monthno">
							<select class="woopt_monthno">
                                <?php
                                for ( $i = 1; $i < 13; $i ++ ) {
	                                echo '<option value="' . esc_attr( $i ) . '" ' . ( (int) $monthno === $i ? 'selected' : '' ) . '>' . $i . '</option>';
                                }
                                ?>
                            </select>
						</span> <span class="woopt_hide woopt_show_if_number">
							<input type="number" step="1" min="0" class="woopt_number" value="<?php echo esc_attr( (int) $number ); ?>"/>
						</span>
                    </div>
					<?php
				}

				function ajax_save_actions() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$pid       = $_POST['pid'];
					$form_data = $_POST['form_data'];

					if ( $pid && $form_data ) {
						$actions = [];
						parse_str( $form_data, $actions );

						if ( isset( $actions['woopt_actions'] ) ) {
							update_post_meta( $pid, 'woopt_actions', $actions['woopt_actions'] );
						}
					}

					wp_die();
				}

				function ajax_add_time() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$key = ! empty( $_POST['key'] ) && ! is_numeric( $_POST['key'] ) ? sanitize_key( $_POST['key'] ) : self::generate_key();

					self::time( $key );
					wp_die();
				}

				function ajax_add_apply_combination() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$key = ! empty( $_POST['key'] ) && ! is_numeric( $_POST['key'] ) ? sanitize_key( $_POST['key'] ) : self::generate_key();

					self::apply_combination( $key );
					wp_die();
				}

				function ajax_search_term() {
					if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$return = [];
					$args   = [
						'taxonomy'   => sanitize_text_field( $_REQUEST['taxonomy'] ),
						'orderby'    => 'id',
						'order'      => 'ASC',
						'hide_empty' => false,
						'fields'     => 'all',
						'name__like' => sanitize_text_field( $_REQUEST['q'] ),
					];
					$terms  = get_terms( $args );

					if ( count( $terms ) ) {
						foreach ( $terms as $term ) {
							$return[] = [ $term->slug, $term->name ];
						}
					}

					wp_send_json( $return );
					wp_die();
				}

				function process_product_meta( $post_id ) {
					if ( isset( $_POST['woopt_actions'] ) && is_array( $_POST['woopt_actions'] ) ) {
						update_post_meta( $post_id, 'woopt_actions', self::sanitize_array( $_POST['woopt_actions'] ) );
					} else {
						delete_post_meta( $post_id, 'woopt_actions' );
					}
				}

				function product_columns( $columns ) {
					$columns['woopt'] = esc_html__( 'Timer', 'woo-product-timer' );

					return $columns;
				}

				function custom_column( $column, $postid ) {
					if ( $column === 'woopt' ) {
						echo '<div class="woopt-icons">';

						// global actions
						if ( is_array( self::$global_actions ) && ( count( self::$global_actions ) > 0 ) ) {
							$global  = false;
							$running = false;

							foreach ( self::$global_actions as $global_action ) {
								$action_data      = self::woopt_action_data( $global_action );
								$action_apply     = $action_data['apply'];
								$action_apply_val = $action_data['apply_val'];
								$action_timer     = $action_data['timer'];

								if ( self::woopt_check_apply( $postid, $action_apply, $action_apply_val ) ) {
									$global = true;

									if ( ! empty( $action_timer ) && self::woopt_check_timer( $action_timer, $postid ) ) {
										$running = true;
									}
								}
							}

							if ( $global ) {
								if ( $running ) {
									echo '<span class="woopt-icon woopt-icon-global running"><span class="dashicons dashicons-admin-site"></span></span>';
								} else {
									echo '<span class="woopt-icon woopt-icon-global"><span class="dashicons dashicons-admin-site"></span></span>';
								}
							}
						}

						$actions = get_post_meta( $postid, 'woopt_actions', true );

						if ( is_array( $actions ) && ( count( $actions ) > 0 ) ) {
							$running = false;

							foreach ( $actions as $action ) {
								$action_data = self::woopt_action_data( $action );

								if ( ! empty( $action_data['timer'] ) && self::woopt_check_timer( $action_data['timer'], $postid ) ) {
									$running = true;
								}
							}

							if ( $running ) {
								echo '<span class="woopt-icon running"><span class="dashicons dashicons-clock"></span></span>';
							} else {
								echo '<span class="woopt-icon"><span class="dashicons dashicons-clock"></span></span>';
							}
						}

						echo '</div>';

						// edit button
						echo '<a href="#" class="woopt_edit" data-pid="' . esc_attr( $postid ) . '" data-name="' . esc_attr( get_the_title( $postid ) ) . '"><span class="dashicons dashicons-edit"></span></a>';
					}
				}

				function ajax_edit_timer() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$product_id = absint( $_POST['pid'] );

					if ( $product_id ) {
						$actions = get_post_meta( $product_id, 'woopt_actions', true );
						echo '<textarea class="woopt_edit_data" style="width: 100%; height: 200px">' . ( ! empty( $actions ) ? json_encode( $actions ) : '' ) . '</textarea>';
						echo '<div style="display: flex; align-items: center"><button class="button button-primary woopt_edit_save" data-pid="' . $product_id . '">' . esc_html__( 'Update', 'woo-product-timer' ) . '</button>';
						echo '<span class="woopt_edit_message" style="margin-left: 10px"></span></div>';
					}

					wp_die();
				}

				function ajax_save_timer() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$product_id = absint( $_POST['pid'] );
					$actions    = sanitize_textarea_field( trim( $_POST['actions'] ) );

					if ( empty( $actions ) ) {
						delete_post_meta( $product_id, 'woopt_actions' );
						esc_html_e( 'Timer was removed!', 'woo-product-timer' );
					} else {
						$actions = json_decode( stripcslashes( $actions ), true );

						if ( $actions !== null ) {
							update_post_meta( $product_id, 'woopt_actions', $actions );
							esc_html_e( 'Updated successfully!', 'woo-product-timer' );
						} else {
							esc_html_e( 'Have an error!', 'woo-product-timer' );
						}
					}

					wp_die();
				}

				function ajax_import_export() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$actions = self::$global_actions;
					echo '<textarea class="woopt_import_export_data" style="width: 100%; height: 200px">' . ( ! empty( $actions ) ? json_encode( $actions ) : '' ) . '</textarea>';
					echo '<div style="display: flex; align-items: center"><button class="button button-primary woopt-import-export-save">' . esc_html__( 'Update', 'woo-product-timer' ) . '</button>';
					echo '<span style="color: #ff4f3b; font-size: 10px; margin-left: 10px">' . esc_html__( '* All current Actions will be replaced after pressing Update!', 'woo-product-timer' ) . '</span>';
					echo '</div>';

					wp_die();
				}

				function ajax_import_export_save() {
					if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woopt-security' ) ) {
						die( 'Permissions check failed!' );
					}

					$actions = sanitize_textarea_field( trim( $_POST['actions'] ) );

					if ( ! empty( $actions ) ) {
						$actions = json_decode( stripcslashes( $actions ) );

						if ( $actions !== null ) {
							update_option( 'woopt_actions', $actions );
							echo 'Done!';
						}
					}

					wp_die();
				}

				function export_columns( $columns ) {
					$columns['woopt_actions'] = esc_html__( 'Timer', 'woo-product-timer' );

					return $columns;
				}

				function export_data( $value, $product ) {
					$value = get_post_meta( $product->get_id(), 'woopt_actions', true );

					if ( ! empty( $value ) ) {
						return json_encode( $value );
					} else {
						return '';
					}
				}

				function import_options( $options ) {
					$options['woopt_actions'] = esc_html__( 'Timer', 'woo-product-timer' );

					return $options;
				}

				function import_columns( $columns ) {
					$columns['Timer']         = 'woopt_actions';
					$columns['timer']         = 'woopt_actions';
					$columns['woopt actions'] = 'woopt_actions';

					return $columns;
				}

				function import_process( $object, $data ) {
					if ( ! empty( $data['woopt_actions'] ) ) {
						$object->update_meta_data( 'woopt_actions', json_decode( html_entity_decode( stripcslashes( $data['woopt_actions'] ) ) ) );
					}

					return $object;
				}

				function sanitize_array( $var ) {
					if ( is_array( $var ) ) {
						return array_map( [ __CLASS__, 'sanitize_array' ], $var );
					} else {
						return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
					}
				}

				public static function format_price( $price ) {
					// format price to percentage or amount
					$price = preg_replace( '/[^.%0-9]/', '', $price );

					return apply_filters( 'woopt_format_price', $price );
				}

				public static function generate_key() {
					$key         = '';
					$key_str     = apply_filters( 'woopt_key_characters', 'abcdefghijklmnopqrstuvwxyz0123456789' );
					$key_str_len = strlen( $key_str );

					for ( $i = 0; $i < apply_filters( 'woopt_key_length', 4 ); $i ++ ) {
						$key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
					}

					if ( is_numeric( $key ) ) {
						$key = self::generate_key();
					}

					return apply_filters( 'woopt_generate_key', $key );
				}
			}

			return WPCleverWoopt::instance();
		}

		return null;
	}
}

if ( ! function_exists( 'woopt_notice_wc' ) ) {
	function woopt_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Product Timer</strong> require WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}
