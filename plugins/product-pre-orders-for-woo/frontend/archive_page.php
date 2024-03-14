<?php

/*
Class Name: WOO_PRE_ORDER_DATA_PROCESSING_ARCHIVE_PAGE
Author: villatheme
Author URI: http://villatheme.com
Copyright 2020-2021 villatheme.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPRO_WOO_PRE_ORDER_Frontend_archive_page {
	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$enable = get_option( 'pre_order_setting_default' );
			if ( $enable['enabled'] == 'yes' ) {
				add_action( 'woocommerce_order_item_meta_end', array(
					$this,
					'pre_order_add_date_after_name_order_details'
				), 10, 2 );
				add_action( 'manage_product_posts_custom_column', array(
					$this,
					'pre_order_custom_product_list_column_content'
				), 10, 2 );
				add_filter( 'manage_edit-product_columns', array( $this, 'pre_order_add_product_column' ), 10, 1 );
				add_action( 'woocommerce_after_order_itemmeta', array(
					$this,
					'pre_order_add_text_order_detail_admin'
				), 10, 3 );
				add_filter( 'manage_edit-shop_order_columns', array( $this, 'custom_pre_order_column' ), 20 );
				add_action( 'manage_shop_order_posts_custom_column', array(
					$this,
					'custom_orders_list_column_content'
				), 20, 2 );
				add_action( 'woocommerce_product_stock_status_options', array( $this, 'pre_order_custom_filter' ) );
				add_filter( 'posts_clauses', array( $this, 'custom_filter_pre_order_product_admin' ) );
				add_filter( 'the_posts', array( $this, 'custom_filter_pre_order_variable' ) );
			}
		}
	}

	public function custom_filter_pre_order_variable( $posts ) {
		global $pagenow;
		$return = array();
		$ids    = array();
		if ( isset( $_GET['post_type'] ) ) {
			$type = sanitize_text_field( $_GET['post_type'] );
		}
		if ( $pagenow == 'edit.php' && 'product' == $type ) {
			foreach ( $posts as $key => $value ) {
				if ( $value->post_type == 'product_variation' && ! empty( $value->post_parent ) && ! in_array( $value->post_parent, $ids ) ) {
					$ids[]    = $value->post_parent;
					$return[] = get_post( $value->post_parent );
				} elseif ( $value->post_type == 'product' ) {
					$ids[]    = $value->ID;
					$return[] = $value;
				}
			}

			return $return;
		}

		return $posts;
	}

	public function custom_filter_pre_order_product_admin( $args ) {
		global $wpdb;
		if ( ! empty( $_GET['stock_status'] ) ) {
			$args['where'] = str_replace( "AND {$wpdb->posts}.post_type = 'product'", " AND {$wpdb->posts}.post_type = 'product_variation' OR {$wpdb->posts}.post_type = 'product'", $args['where'] );
			$args['where'] = " AND ( {$wpdb->postmeta}.meta_key = '_simple_preorder' AND {$wpdb->postmeta}.meta_value = 'yes' ) OR ( {$wpdb->postmeta}.meta_key = '_wpro_variable_is_preorder' AND {$wpdb->postmeta}.meta_value = 'yes' )" . $args['where'];
			$args['join']  .= " INNER JOIN {$wpdb->postmeta}  ON ($wpdb->posts.ID = {$wpdb->postmeta}.post_id) ";
		}

		return $args;
	}

	public function pre_order_custom_filter( $label ) {
		if ( isset( $_GET['post_type'] ) ) {
			$type = sanitize_text_field( $_GET['post_type'] );
			if ( 'product' == $type ) {
				$label['pre_order'] = esc_html__( 'Pre-Order', 'product-pre-orders-for-woo' );
			}
		}

		return $label;
	}

	/** Add column tab Order Admin
	 *
	 * @param $column
	 * @param $order_id
	 */
	public function custom_orders_list_column_content( $column, $order_id ) {
		if ( $column == 'pre-order' ) {
			$order      = wc_get_order( $order_id );
			$order_item = $order->get_items();
			foreach ( $order_item as $item_id => $item ) {
				$product = $item->get_product();
				if ( $product ) {
					$product_id   = $item->get_product_id();
					$variation_id = $item->get_variation_id();
					$name         = $item->get_name();
					$product_type = $product->get_type();
					$quantity     = $item->get_quantity();
					switch ( $product_type ) {
						case 'simple' :
							$is_pre_order = get_post_meta( $product_id, '_simple_preorder', true );
							if ( $is_pre_order == 'yes' ) {
								echo esc_html( $name ) . ' ' . 'x' . ' ' . esc_html( $quantity ) . '<br>';
							}
							break;
						case 'variation' :
							$is_pre_order = get_post_meta( $variation_id, '_wpro_variable_is_preorder', true );
							if ( $is_pre_order == 'yes' ) {
								echo esc_html( $name ) . ' ' . 'x' . ' ' . esc_html( $quantity ) . '<br>';
							}
							break;
					}
				}
			}
		}
	}

	/** Content column Pre-Order
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function custom_pre_order_column( $columns ) {
		$reordered_columns = array();
		foreach ( $columns as $key => $column ) {
			$reordered_columns[ $key ] = $column;
			if ( $key == 'order_status' ) {
				$reordered_columns['pre-order'] = esc_html__( 'Pre-Order Product', 'product-pre-orders-for-woo' );
			}
		}

		return $reordered_columns;
	}

	/** Add text Order Details admin
	 *
	 * @param $item_id
	 * @param $item
	 * @param $product
	 */
	public function pre_order_add_text_order_detail_admin( $item_id, $item, $product ) {
		if ( ! $item->is_type( 'line_item' ) ) {
			return;
		}
		if ( $product ) {
			$product_id   = $item->get_product_id();
			$variation_id = $item->get_variation_id();
			$product_type = $product->get_type();
			$is_pre_order = '';
			switch ( $product_type ) {
				case 'simple' :
					$is_pre_order = get_post_meta( $product_id, '_simple_preorder', true );
					break;
				case 'variation' :
					$is_pre_order = get_post_meta( $variation_id, '_wpro_variable_is_preorder', true );
					break;
			}
			if ( $is_pre_order === 'yes' ) {
				esc_html_e( apply_filters( 'PPOFW_FILTER_text_pre_order_line_items', 'Pre-Order Product' ), 'product-pre-orders-for-woo' );
			}
		}

	}

	/** Add column All Product
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function pre_order_add_product_column( $columns ) {
		$columns['pre_order_date'] = esc_html__( 'Pre-Order Date', 'product-pre-orders-for-woo' );

		return $columns;
	}

	/** Content column Pre-Order date
	 *
	 * @param $column
	 * @param $product_id
	 *
	 * @throws Exception
	 */
	public function pre_order_custom_product_list_column_content( $column, $product_id ) {
		$product  = wc_get_product( $product_id );
		$pre_date = get_post_meta( $product_id, '_wpro_date', true );
		$now_date = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
		if ( $pre_date != '' ) {
			$_date          = date_i18n( 'd-m-Y H:i:s', $pre_date );
			$gmt_offdet     = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
			$time_str       = strtotime( $_date );
			$available_date = $gmt_offdet + $time_str;
		} else {
			$available_date = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
		}
		$time_remaining = $available_date - $now_date;
		$dtF            = new DateTime( '@0' );
		$dtT            = new DateTime( "@$time_remaining" );
		$type_product   = $product->get_type();
		if ( $column == 'pre_order_date' ) {
			switch ( $type_product ) {
				case 'simple':
					$is_pre_order = get_post_meta( $product_id, '_simple_preorder', true );
					if ( $is_pre_order == 'yes' ) {
						if ( $time_remaining > 0 ) {
							if ( $time_remaining > 86400 ) {
								$simple_time = $dtF->diff( $dtT )->format( '%a days %h hours' );
								echo '<div class="wpro-all-product-page-before-day">' . esc_html( $simple_time ) . '</div>';
							} else {
								$simple_time = $dtF->diff( $dtT )->format( '%h hours %i minutes' );
								echo '<div class="wpro-all-product-page-after-day">' . esc_html( $simple_time ) . '</div>';
							}
						} elseif ( $pre_date == '' ) {
							?>
                            <div class="wpro-pre-order-all-product-no-set">
								<?php
								esc_html_e( 'No date set', 'product-pre-orders-for-woo' );
								?>
                            </div>
							<?php
						}
					}
					break;

				case 'variable':
					$variation = $product->get_children();
					foreach ( $variation as $variation_id ) {
						$product_variation = new WC_Product_Variation( $variation_id );
						$variation_attr    = implode( " / ", $product_variation->get_variation_attributes() );
						$pre_date_var      = get_post_meta( $variation_id, '_wpro_date_variable', true );
						if ( $pre_date_var != '' ) {
							$_date_var          = date_i18n( 'd-m-Y H:i:s', $pre_date_var );
							$gmt_offdet         = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
							$time_str           = strtotime( $_date_var );
							$available_date_var = $gmt_offdet + $time_str;
						} else {
							$available_date_var = strtotime( 'now', current_time( 'timestamp', true ) );
						}
						$time_remaining_var = $available_date_var - $now_date;
						$dtT_var            = new \DateTime( "@$time_remaining_var" );
						$is_pre_order       = get_post_meta( $variation_id, '_wpro_variable_is_preorder', true );
						if ( $is_pre_order == 'yes' ) {
							if ( $time_remaining_var > 0 ) {
								if ( $time_remaining_var > 86400 ) {
									$time_variable = $dtF->diff( $dtT_var )->format( '%a days %h hours' );
									echo '<div class="wpro-all-product-page-before-day">' . esc_html( $variation_attr ) . ' : ' . esc_html( $time_variable ) . '</div>';
								} else {
									$time_variable = $dtF->diff( $dtT_var )->format( '%h hours %i minutes' );
									echo '<div class="wpro-all-product-page-after-day">' . esc_html( $variation_attr ) . ' : ' . esc_html( $time_variable ) . '</div>';
								}
							} elseif ( $pre_date_var == '' ) {
								?>
                                <div class="wpro-pre-order-all-product-no-set">
									<?php
									printf( esc_html__( ' %s : No date set', 'product-pre-orders-for-woo' ), $variation_attr );
									?>
                                </div>
								<?php
							}
						}
					}
					break;
			}
		}
	}

	/** Add date order detail and after name email
	 *
	 * @param $item_id
	 * @param $item
	 */
	public function pre_order_add_date_after_name_order_details( $item_id, $item ) {
		$output       = '';
		$product_id   = $item['product_id'];
		$variation_id = $item['variation_id'];
		$product      = wc_get_product( $product_id );
		if ( empty( $product ) ) {
			return;
		}
		$get_option = get_option( 'pre_order_setting_default' );
		$date_now   = strtotime( date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );
		$gmt_offdet = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		if ( $product->is_type( 'simple' ) ) {
			$is_pre_order = get_post_meta( $product_id, '_simple_preorder', true );
			if ( $is_pre_order == 'yes' ) {
				$pre_date    = get_post_meta( $product_id, '_wpro_date', true );
				$date_time   = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$time_str    = strtotime( $date_time );
				$time_total  = $gmt_offdet + $time_str;
				$date_format = date_i18n( get_option( 'date_format' ), $time_total );
				$pre_time    = get_post_meta( $product_id, '_wpro_time', true );
				$time_format = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
				$post_date   = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
				$post_time   = str_replace( "{availability_time}", $time_format, $post_date );
				if ( ! empty( $pre_date ) ) {
					if ( $date_now < $time_total ) {
						$output .= '<br>' . esc_html( $post_time );
					}
				} else {
					$output .= '<br>' . apply_filters( 'wpro_filter_no_date_label', esc_html__( 'Pre-Order No Date', 'product-pre-orders-for-woo' ) );
				}
			}
		} elseif ( $product->is_type( 'variable' ) ) {
			$is_pre_order = get_post_meta( $variation_id, '_wpro_variable_is_preorder', true );
			if ( $is_pre_order == 'yes' ) {
				$pre_date    = get_post_meta( $variation_id, '_wpro_date_variable', true );
				$date_time   = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$time_str    = strtotime( $date_time );
				$time_total  = $gmt_offdet + $time_str;
				$date_format = date_i18n( get_option( 'date_format' ), $time_total );
				$date_time   = date_i18n( 'Y-m-d H:i:s', $pre_date );
				$pre_time    = get_post_meta( $variation_id, '_wpro_time_variable', true );
				$time_format = date_i18n( get_option( 'time_format' ), strtotime( $pre_time ) - strtotime( 'TODAY' ) );
				$time_total  = strtotime( $date_time );
				$post_date   = str_replace( "{availability_date}", $date_format, $get_option['date_text'] );
				$post_time   = str_replace( "{availability_time}", $time_format, $post_date );
				if ( ! empty( $pre_date ) ) {
					if ( $date_now < $time_total ) {
						$output .= '<br>' . esc_html( $post_time );
					}
				} else {
					$output .= '<br>' . apply_filters( 'wpro_filter_no_date_label', esc_html__( 'Pre-Order No Date', 'product-pre-orders-for-woo' ) );
				}
			}
		}
		echo wp_kses_post( $output );
	}
}
