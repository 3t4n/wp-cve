<?php

/*
Class Name: SALES_COUNTDOWN_TIMER_Product
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2017 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SALES_COUNTDOWN_TIMER_Admin_Product {
	protected $settings;
	protected $data;

	function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new SALES_COUNTDOWN_TIMER_Data();
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );

			add_action( 'woocommerce_process_product_meta_simple', array(
				$this,
				'woocommerce_process_product_meta_simple'
			) );
			add_action( 'woocommerce_process_product_meta_external', array(
				$this,
				'woocommerce_process_product_meta_simple',
			) );
			add_action( 'woocommerce_save_product_variation', array(
				$this,
				'woocommerce_save_product_variation'
			), 10, 2 );

			add_action( 'woocommerce_product_write_panel_tabs', array(
				$this,
				'woocommerce_product_write_panel_tabs'
			) );
			add_action( 'woocommerce_variation_options', array( $this, 'woocommerce_variation_options' ) );
			add_action( 'woocommerce_product_options_pricing', array(
				$this,
				'woocommerce_product_options_pricing'
			), 99 );
			add_action( 'woocommerce_variation_options_pricing', array(
				$this,
				'woocommerce_variation_options_pricing'
			), 10, 3 );
		}

	}

	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( $screen->id == 'product' ) {
			wp_enqueue_script( 'sales-countdown-timer-admin-product', SALES_COUNTDOWN_TIMER_JS . 'sales-countdown-timer-admin-product.js', array( 'jquery' ) );
			wp_enqueue_style( 'sales-countdown-timer-admin-product', SALES_COUNTDOWN_TIMER_CSS . 'sales-countdown-timer-admin-product.css' );
			/*update price*/
//			global $post;
//			$product_id = $post->ID;
//			$product    = wc_get_product( $product_id );
//			if ( $product ) {
//				if ( $product->is_type( 'variable' ) ) {
//					$variations = $product->get_children();
//					if ( is_array( $variations ) && count( $variations ) ) {
//						foreach ( $variations as $variation_key => $variation_id ) {
//							$variation = wc_get_product( $variation_id );
//							if ( $variation ) {
//								if ( ! $variation->get_sale_price( 'edit' ) ) {
//									continue;
//								}
//								if ( $variation->get_regular_price( 'edit' ) != $variation->get_price( 'edit' ) ) {
//									if ( $variation->get_date_on_sale_from( 'edit' ) && $variation->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $variation->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
//										update_post_meta( $variation_id, '_price', $variation->get_regular_price( 'edit' ) );
//										$variation->set_price( $variation->get_regular_price( 'edit' ) );
//									}
//								}
//								if ( $variation->get_date_on_sale_to( 'edit' ) && $variation->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $variation->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
//									update_post_meta( $variation_id, '_sale_price_old_woo_ctr', $variation->get_sale_price( 'edit' ) );
//									$regular_price = $variation->get_regular_price();
//									$variation->set_price( $regular_price );
//									$variation->set_sale_price( '' );
//									$variation->set_date_on_sale_to( '' );
//									$variation->set_date_on_sale_from( '' );
//									$variation->save();
//									delete_post_meta( $variation_id, '_woo_ctr_product_sold_quantity' );
//								}
//							}
//						}
//					}
//
//				} else {
//					if ( ! $product->get_sale_price( 'edit' ) ) {
//						return;
//					}
//					if ( $product->get_regular_price( 'edit' ) != $product->get_price( 'edit' ) ) {
//						if ( $product->get_date_on_sale_from( 'edit' ) && $product->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
//							update_post_meta( $product_id, '_price', $product->get_regular_price( 'edit' ) );
//							$product->set_price( $product->get_regular_price( 'edit' ) );
//						}
//					}
//					if ( $product->get_date_on_sale_to( 'edit' ) && $product->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $product->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
//						update_post_meta( $product_id, '_sale_price_old_woo_ctr', $product->get_sale_price( 'edit' ) );
//						$regular_price = $product->get_regular_price();
//						$product->set_price( $regular_price );
//						$product->set_sale_price( '' );
//						$product->set_date_on_sale_to( '' );
//						$product->set_date_on_sale_from( '' );
//						$product->save();
//						delete_post_meta( $product_id, '_woo_ctr_product_sold_quantity' );
//					}
//				}
//			}
		} elseif ( $screen->id == 'edit-product' ) {
			/*update price*/
			global $wp_query;
			$products = $wp_query->get_posts();
			if ( count( $products ) ) {
				foreach ( $products as $product_obj ) {
					$product_id = $product_obj->ID;
					$product    = wc_get_product( $product_id );
					if ( $product ) {
						if ( $product->is_type( 'variable' ) ) {
							$variations = $product->get_children();
							if ( is_array( $variations ) && count( $variations ) ) {
								foreach ( $variations as $variation_key => $variation_id ) {
									$variation = wc_get_product( $variation_id );
									if ( $variation ) {
										if ( ! $variation->get_sale_price( 'edit' ) ) {
											continue;
										}
										if ( $variation->get_regular_price( 'edit' ) != $variation->get_price( 'edit' ) ) {
											if ( $variation->get_date_on_sale_from( 'edit' ) && $variation->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $variation->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
												update_post_meta( $variation_id, '_price', $variation->get_regular_price( 'edit' ) );
												$variation->set_price( $variation->get_regular_price( 'edit' ) );
											}
										}
										if ( $variation->get_date_on_sale_to( 'edit' ) && $variation->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $variation->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
											update_post_meta( $variation_id, '_sale_price_old_woo_ctr', $variation->get_sale_price( 'edit' ) );
											$regular_price = $variation->get_regular_price();
											$variation->set_price( $regular_price );
											$variation->set_sale_price( '' );
											$variation->set_date_on_sale_to( '' );
											$variation->set_date_on_sale_from( '' );
											$variation->save();
											delete_post_meta( $variation_id, '_woo_ctr_product_sold_quantity' );
										}
									}
								}
							}

						} else {
							if ( ! $product->get_sale_price( 'edit' ) ) {
								continue;
							}
							if ( $product->get_regular_price( 'edit' ) != $product->get_price( 'edit' ) ) {
								if ( $product->get_date_on_sale_from( 'edit' ) && $product->get_date_on_sale_from( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) < $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
									update_post_meta( $product_id, '_price', $product->get_regular_price( 'edit' ) );
									$product->set_price( $product->get_regular_price( 'edit' ) );
								}
							}
							if ( $product->get_date_on_sale_to( 'edit' ) && $product->get_date_on_sale_to( 'edit' )->getTimestamp() && current_time( 'timestamp', true ) > $product->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
								update_post_meta( $product_id, '_sale_price_old_woo_ctr', $product->get_sale_price( 'edit' ) );
								$regular_price = $product->get_regular_price();
								$product->set_price( $regular_price );
								$product->set_sale_price( '' );
								$product->set_date_on_sale_to( '' );
								$product->set_date_on_sale_from( '' );
								$product->save();
								delete_post_meta( $product_id, '_woo_ctr_product_sold_quantity' );
							}
						}
					}

				}
			}
		}
	}

	public function woocommerce_product_write_panel_tabs() {
		ob_start();
	}

	public function woocommerce_variation_options() {
		ob_start();
	}

	public function woocommerce_product_options_pricing() {
		global $post;
		$html = ob_get_clean();
		preg_match_all( '/<p class=\"form-field sale_price_dates_fields\"(.+?)<\/p>/si', $html, $datefields );
		$html = str_replace( $datefields[0], '', $html );
		echo $html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$product_object        = wc_get_product( $post->ID );
		$sale_from             = $product_object->get_date_on_sale_from( 'edit' ) ? $product_object->get_date_on_sale_from( 'edit' )->getOffsetTimestamp() : 0;
		$sale_to               = $product_object->get_date_on_sale_to( 'edit' ) ? $product_object->get_date_on_sale_to( 'edit' )->getOffsetTimestamp() : 0;
		$sale_price_dates_from = $sale_from ? date_i18n( 'Y-m-d', $sale_from ) : '';
		$sale_price_dates_to   = $sale_to ? date_i18n( 'Y-m-d', $sale_to ) : '';
		$sale_price_time_from  = $sale_from % 86400;
		$sale_price_time_to    = $sale_to % 86400;

		echo '<p class="form-field sale_price_dates_field">
				<label for="_sale_price_dates_from">' . esc_html__( 'Sale price dates and times', 'woocommerce' ) . '</label>
				<input type="text" class="short" name="_sale_price_dates_from" id="_sale_price_dates_from" value="' . esc_attr( $sale_price_dates_from ) . '" placeholder="' . esc_html( _x( 'From&hellip;', 'placeholder', 'woocommerce' ) ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
				<input type="time" name="_sale_price_times_from" id="sale_price_times_from" value="' . woo_ctr_time_revert( $sale_price_time_from ) . '">
				<input type="text" class="short" name="_sale_price_dates_to" id="_sale_price_dates_to" value="' . esc_attr( $sale_price_dates_to ) . '" placeholder="' . esc_html( _x( 'To&hellip;', 'placeholder', 'woocommerce' ) ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
				<input type="time" name="_sale_price_times_to" id="sale_price_times_to" value="' . woo_ctr_time_revert( $sale_price_time_to ) . '">
				<a href="#" class="description cancel_sale_schedule">' . esc_html__( 'Cancel', 'woocommerce' ) . '</a>' . wc_help_tip( __( 'Dates and times value are set in your website timezone.', 'woocommerce' ) ) . '
			</p>';
		echo '<div class="woo-sctr-countdown-timer-admin-product">';
		$id      = $this->settings->get_id();
		$options = array();
		foreach ( $id as $k => $v ) {
			$options[ $v ] = $this->settings->get_names()[ $k ];
		}
		woocommerce_wp_select(
			array(
				'id'          => '_woo_ctr_select_countdown_timer',
				'value'       => $product_object->get_meta( '_woo_ctr_select_countdown_timer', true ),
				'label'       => __( 'Countdown timer profile', 'sales-countdown-timer' ),
				'options'     => $options,
				'desc_tip'    => 'true',
				'description' => __( 'Select countdown timer settings.', 'sales-countdown-timer' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'    => '_woo_ctr_enable_progress_bar',
				'label' => __( 'Enable progress bar', 'woocommerce' ),
				'value' => wc_bool_to_string( $product_object->get_meta( '_woo_ctr_enable_progress_bar', true ) ),
			)
		);
		echo '<p class="form-field"><label for="_woo_ctr_progress_bar_goal">' . esc_html__( 'Goal', 'sales-countdown-timer' ) . '</label><input type="number" value="' . ( $product_object->get_meta( '_woo_ctr_progress_bar_goal', true ) ? ( $product_object->get_meta( '_woo_ctr_progress_bar_goal', true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_goal" id="_woo_ctr_progress_bar_goal">' . wc_help_tip( esc_html__( 'Your product goal', 'sales-countdown-timer' ) ) . '</p>';
		echo '<p class="form-field"><label for="_woo_ctr_progress_bar_initial">' . esc_html__( 'Initial quantity', 'sales-countdown-timer' ) . '</label><input type="number" value="' . ( $product_object->get_meta( '_woo_ctr_progress_bar_initial', true ) ? ( $product_object->get_meta( '_woo_ctr_progress_bar_initial', true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_initial" id="_woo_ctr_progress_bar_initial">' . wc_help_tip( esc_html__( 'This is the virtual quantity of sold products', 'sales-countdown-timer' ) ) . '</p>';
		echo '</div>';
	}

	public function woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {
		$html = ob_get_clean();
		preg_match_all( '/<div class=\"form-field sale_price_dates_fields hidden\"(.+?)<\/div>/si', $html, $datefields );
		$html = str_replace( $datefields[0], '', $html );
		echo $html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$variation_object      = wc_get_product( $variation->ID );
		$sale_from             = $variation_object->get_date_on_sale_from( 'edit' ) ? $variation_object->get_date_on_sale_from( 'edit' )->getOffsetTimestamp() : 0;
		$sale_to               = $variation_object->get_date_on_sale_to( 'edit' ) ? $variation_object->get_date_on_sale_to( 'edit' )->getOffsetTimestamp() : 0;
		$sale_price_dates_from = $sale_from ? date_i18n( 'Y-m-d', $sale_from ) : '';
		$sale_price_dates_to   = $sale_to ? date_i18n( 'Y-m-d', $sale_to ) : '';
		$sale_price_time_from  = $sale_from % 86400;
		$sale_price_time_to    = $sale_to % 86400;

		echo '<div class="form-field sale_price_dates_field hidden">
					<p class="form-row form-row-first">
						<label>' . __( 'Sale start date', 'woocommerce' ) . '</label>
						<input type="text" class="sale_price_dates_from" name="variable_sale_price_dates_from[' . $loop . ']" value="' . esc_attr( $sale_price_dates_from ) . '" placeholder="' . _x( 'From&hellip;', 'placeholder', 'woocommerce' ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
						<input type="time" name="variable_sale_price_times_from[' . $loop . ']" class="variable_sale_price_times_from" value="' . woo_ctr_time_revert( $sale_price_time_from ) . '">
					</p>
					<p class="form-row form-row-last">
						<label>' . __( 'Sale end date', 'woocommerce' ) . '</label>
						<input type="text" class="sale_price_dates_to" name="variable_sale_price_dates_to[' . esc_attr( $loop ) . ']" value="' . esc_attr( $sale_price_dates_to ) . '" placeholder="' . esc_html_x( 'To&hellip;', 'placeholder', 'woocommerce' ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
						<input type="time" name="variable_sale_price_times_to[' . $loop . ']" class="variable_sale_price_times_to" value="' . woo_ctr_time_revert( $sale_price_time_to ) . '">
					</p>
				</div>';
		echo '<div class="woo-sctr-countdown-timer-admin-product">';
		$id      = $this->settings->get_id();
		$options = array();
		foreach ( $id as $k => $v ) {
			$options[ $v ] = $this->settings->get_names()[ $k ];
		}
		woocommerce_wp_select(
			array(
				'id'          => '_woo_ctr_select_countdown_timer' . $loop,
				'name'        => '_woo_ctr_select_countdown_timer[' . $loop . ']',
				'value'       => $variation_object->get_meta( '_woo_ctr_select_countdown_timer', true ),
				'label'       => __( 'Countdown timer profile', 'sales-countdown-timer' ),
				'options'     => $options,
				'desc_tip'    => 'true',
				'description' => __( 'Select countdown timer settings.', 'sales-countdown-timer' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'    => '_woo_ctr_enable_progress_bar' . $loop,
				'name'  => '_woo_ctr_enable_progress_bar[' . $loop . ']',
				'label' => __( 'Enable progress bar', 'woocommerce' ),
				'value' => wc_bool_to_string( $variation_object->get_meta( '_woo_ctr_enable_progress_bar', true ) ),
			)
		);
		echo '<p class="form-field form-row form-row-first"><label for="_woo_ctr_progress_bar_goal' . $loop . '">' . esc_html__( 'Goal', 'sales-countdown-timer' ) . '</label>' . wc_help_tip( esc_html__( 'Your product goal', 'sales-countdown-timer' ) ) . '<input type="number" value="' . ( $variation_object->get_meta( '_woo_ctr_progress_bar_goal', true ) ? ( $variation_object->get_meta( '_woo_ctr_progress_bar_goal', true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_goal[' . $loop . ']" id="_woo_ctr_progress_bar_goal' . $loop . '"></p>';
		echo '<p class="form-field form-row form-row-last"><label for="_woo_ctr_progress_bar_initial' . $loop . '">' . esc_html__( 'Initial quantity', 'sales-countdown-timer' ) . '</label>' . wc_help_tip( esc_html__( 'This is the virtual quantity of sold products', 'sales-countdown-timer' ) ) . '<input type="number" value="' . ( $variation_object->get_meta( '_woo_ctr_progress_bar_initial', true ) ? ( $variation_object->get_meta( '_woo_ctr_progress_bar_initial', true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_initial[' . $loop . ']" id="_woo_ctr_progress_bar_initial' . $loop . '"></p>';
		echo '</div>';
	}

	public function woocommerce_process_product_meta_simple( $post_id ) {
		$gmt_offset            = get_option( 'gmt_offset' );
		$date_on_sale_from     = isset( $_POST['_sale_price_dates_from'] ) ? strtotime( sanitize_text_field( $_POST['_sale_price_dates_from'] ) ) : '';
		$date_on_sale_to       = isset( $_POST['_sale_price_dates_to'] ) ? strtotime( sanitize_text_field( $_POST['_sale_price_dates_to'] ) ) : '';
		$sale_price_times_from = isset( $_POST['_sale_price_times_from'] ) ? woo_ctr_time( sanitize_text_field( $_POST['_sale_price_times_from'] ) ) : '';
		$sale_price_times_to   = isset( $_POST['_sale_price_times_to'] ) ? woo_ctr_time( sanitize_text_field( $_POST['_sale_price_times_to'] ) ) : '';
		if ( $date_on_sale_from ) {
			$date_on_sale_from += $sale_price_times_from;
		} elseif ( $date_on_sale_to ) {
			$date_on_sale_from = strtotime( date( "Y-m-d" ) );
		}
		if ( $date_on_sale_to ) {
			$date_on_sale_to += $sale_price_times_to;
		}
		update_post_meta( $post_id, '_sale_price_dates_from', ( $date_on_sale_from - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_from - $gmt_offset * 3600 ) : '' );
		update_post_meta( $post_id, '_sale_price_dates_to', ( $date_on_sale_to - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_to - $gmt_offset * 3600 ) : '' );
		update_post_meta( $post_id, '_sale_price_times_from', isset( $_POST['_sale_price_times_from'] ) ? sanitize_text_field( $_POST['_sale_price_times_from'] ) : '00:00' );
		update_post_meta( $post_id, '_sale_price_times_to', isset( $_POST['_sale_price_times_to'] ) ? sanitize_text_field( $_POST['_sale_price_times_to'] ) : '00:00' );
		update_post_meta( $post_id, '_woo_ctr_select_countdown_timer', isset( $_POST['_woo_ctr_select_countdown_timer'] ) ? sanitize_text_field( $_POST['_woo_ctr_select_countdown_timer'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_enable_progress_bar', isset( $_POST['_woo_ctr_enable_progress_bar'] ) ? sanitize_text_field( $_POST['_woo_ctr_enable_progress_bar'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_progress_bar_goal', isset( $_POST['_woo_ctr_progress_bar_goal'] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_goal'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_progress_bar_initial', isset( $_POST['_woo_ctr_progress_bar_initial'] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_initial'] ) : '' );
	}

	public function woocommerce_save_product_variation( $variation_id, $i ) {
		global $post;
		update_post_meta( $variation_id, '_woo_ctr_select_countdown_timer', isset( $_POST['_woo_ctr_select_countdown_timer'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_select_countdown_timer'][ $i ] ) : '' );
		$gmt_offset            = get_option( 'gmt_offset' );
		$date_on_sale_from     = isset( $_POST['variable_sale_price_dates_from'][ $i ] ) ? strtotime( sanitize_text_field( $_POST['variable_sale_price_dates_from'][ $i ] ) ) : '';
		$date_on_sale_to       = isset( $_POST['variable_sale_price_dates_to'][ $i ] ) ? strtotime( sanitize_text_field( $_POST['variable_sale_price_dates_to'][ $i ] ) ) : '';
		$sale_price_times_from = isset( $_POST['variable_sale_price_times_from'][ $i ] ) ? sanitize_text_field( $_POST['variable_sale_price_times_from'][ $i ] ) : '00:00';
		$sale_price_times_to   = isset( $_POST['variable_sale_price_times_to'][ $i ] ) ? sanitize_text_field( $_POST['variable_sale_price_times_to'][ $i ] ) : '00:00';
		$time_from             = woo_ctr_time( $sale_price_times_from );
		$time_to               = woo_ctr_time( $sale_price_times_to );
		if ( $date_on_sale_from ) {
			$date_on_sale_from += $time_from;
		} else {
			$date_on_sale_from     = strtotime( date( "Y-m-d" ) );
			$sale_price_times_from = '00:00';
		}
		if ( $date_on_sale_to ) {
			$date_on_sale_to += $time_to;
		}
		$expire = $date_on_sale_from - current_time( 'timestamp' );
		if ( isset( $_POST['variable_sale_price'][ $i ] ) && $_POST['variable_sale_price'][ $i ] && $date_on_sale_from && $expire > 0 ) {
			set_transient( 'woo_sctr_update_variable_price_start_sale_' . $variation_id, $date_on_sale_from, $expire );
		}
		update_post_meta( $variation_id, '_sale_price_dates_from', ( $date_on_sale_from - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_from - $gmt_offset * 3600 ) : '' );
		update_post_meta( $variation_id, '_sale_price_dates_to', ! empty( $date_on_sale_to ) && ( $date_on_sale_to - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_to - $gmt_offset * 3600 ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_select_countdown_timer', isset( $_POST['_woo_ctr_select_countdown_timer'] ) ? sanitize_text_field( $_POST['_woo_ctr_select_countdown_timer'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_sale_price_times_from', $sale_price_times_from );
		update_post_meta( $variation_id, '_sale_price_times_to', $sale_price_times_to );
		update_post_meta( $variation_id, '_woo_ctr_enable_progress_bar', isset( $_POST['_woo_ctr_enable_progress_bar'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_enable_progress_bar'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_progress_bar_goal', isset( $_POST['_woo_ctr_progress_bar_goal'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_goal'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_progress_bar_initial', isset( $_POST['_woo_ctr_progress_bar_initial'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_initial'][ $i ] ) : '' );
	}

}
