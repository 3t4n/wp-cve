<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Us_Checkout {
	protected $settings, $frontend, $table;
	
	public function __construct() {
		$this->settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		if ( ! $this->settings->enable( 'us_' ) ) {
			return;
		}
		$this->upsell_funnel = 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Upsell_Funnel';
		$this->table         = 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table';
		
		//add custom field to checkout form
		add_action( 'woocommerce_checkout_after_order_review', array( $this, 'viwcuf_us_woocommerce_checkout_after_order_review' ) );
		
		// check is popup or redirect page and remove session
//		add_action( 'woocommerce_after_checkout_validation', array( $this, 'viwcuf_us_woocommerce_after_checkout_validation' ), PHP_INT_MAX, 2 );
		add_action( 'woocommerce_checkout_update_user_meta', array( $this, 'viwcuf_us_checkout_validation' ), PHP_INT_MAX, 2 );
		
		//save funnel data
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'viwcuf_us_woocommerce_checkout_create_order_line_item' ), 10, 4 );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'viwcuf_us_woocommerce_checkout_update_order_meta' ), 10, 2 );
	}
	
	public function viwcuf_us_woocommerce_checkout_after_order_review() {
		if ( in_array( $this->upsell_funnel::$position, array( '0', 'footer' ) ) ) {
			if ( $this->upsell_funnel::$position === 'footer' && class_exists( 'WC_Gateway_Twocheckout_Inline' ) ) {
				return;
			}
			echo sprintf( '<input type="hidden" name="viwcuf_us_position" class="viwcuf_us_position" value="%s">', $this->upsell_funnel::$position );
		}
	}
	
	public function viwcuf_us_woocommerce_after_checkout_validation( $data, $errors ) {
		$errors_t    = $errors;
		$count_error = $errors_t->get_error_messages();
		if ( ! empty( $count_error ) ) {
			return;
		}
		$position = isset( $_POST['viwcuf_us_position'] ) ? sanitize_text_field( $_POST['viwcuf_us_position'] ) : '';
		if ( ! in_array( $position, array( '0', 'footer' ) ) ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$shipping = ! empty( $_POST['ship_to_different_address'] ) && ! wc_ship_to_billing_address_only();
		$rule_id  = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::get_rules( 'us_', $shipping );
		if ( ! $rule_id ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$ids   = $this->settings->get_params( 'us_ids' );
		$index = array_search( $rule_id, $ids );
		if ( $index != 0 ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$product_ids = $this->upsell_funnel::get_us_product_ids( $rule_id, $this->settings );
		if ( empty( $product_ids ) ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$product_ids = implode( ',', $product_ids );
		WC()->session->set( 'viwcuf_us_recommend_pd_ids', $product_ids );
		WC()->session->set( 'viwcuf_us_rule_info', array(
			'discount_type'   => $this->settings->get_current_setting( 'us_discount_type', $index ),
			'discount_amount' => $this->settings->get_current_setting( 'us_discount_amount', $index ),
			'quantity_limit'  => 1,
		) );
		$shortcode = do_shortcode( '[viwcuf_checkout_upsell_funnel rule="' . $rule_id . '"  position="' . $position . '" product_ids="' . esc_attr( $product_ids ) . '"]' );
		if ( empty( $shortcode ) ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		if ( $position === '0' ) {
			if ( ! $this->settings->get_params( 'us_redirect_page_endpoint' ) ) {
				$this->upsell_funnel::remove_session();
				
				return;
			}
			$redirect_url = wc_get_endpoint_url( 'viwcuf_us_endpoint', '', wc_get_checkout_url() );
			if ( ! $redirect_url ) {
				$this->upsell_funnel::remove_session();
				
				return;
			}
			if ( ! wp_doing_ajax() ) {
				exit( wp_redirect( $redirect_url ) );
			} else {
				$viwcuf_array = array(
					'is_popup'     => $position ?: '',
					'rule'         => $rule_id,
					'position'     => $position,
					'product_ids'  => $product_ids,
					'popup_html'   => '',
					'redirect_url' => $redirect_url,
				);
			}
		} else {
			$viwcuf_array = array(
				'is_popup'     => $position ?: '',
				'rule'         => $rule_id,
				'position'     => $position,
				'product_ids'  => $product_ids,
				'popup_html'   => $shortcode,
				'redirect_url' => '',
			);
		}
		$viwcuf_html = wp_json_encode( $viwcuf_array );
		$viwcuf_html = function_exists( 'wc_esc_json' ) ? wc_esc_json( $viwcuf_html ) : _wp_specialchars( $viwcuf_html, ENT_QUOTES, 'UTF-8', true );
		$viwcuf_html = '<div class="vi-wcuf-disable" data-viwcufenable="yes" data-viwcufdata="' . $viwcuf_html . '"></div>';
		$errors->add( 'viwcufdata', $viwcuf_html );
		
		return;
	}
	
	public function viwcuf_us_checkout_validation( $customer_id, $data ) {
		$position = isset( $_POST['viwcuf_us_position'] ) ? sanitize_text_field( $_POST['viwcuf_us_position'] ) : '';
		if ( ! in_array( $position, array( '0', 'footer' ) ) ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$shipping = ! empty( $_POST['ship_to_different_address'] ) && ! wc_ship_to_billing_address_only();
		$rule_id  = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Frontend_Frontend::get_rules( 'us_', $shipping );
		if ( ! $rule_id ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$ids   = $this->settings->get_params( 'us_ids' );
		$index = array_search( $rule_id, $ids );
		if ( $index === false ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$product_ids = $this->upsell_funnel::get_us_product_ids( $rule_id, $this->settings );
		if ( empty( $product_ids ) ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		$product_ids = implode( ',', $product_ids );
		WC()->session->set( 'viwcuf_us_recommend_pd_ids', $product_ids );
		WC()->session->set( 'viwcuf_us_rule_info', array(
			'discount_type'   => $this->settings->get_current_setting( 'us_discount_type', $index ),
			'discount_amount' => $this->settings->get_current_setting( 'us_discount_amount', $index ),
			'quantity_limit'  => $this->settings->get_current_setting( 'us_product_qty', $index ),
		) );
		$shortcode = do_shortcode( '[viwcuf_checkout_upsell_funnel rule="' . $rule_id . '"  position="' . $position . '" product_ids="' . esc_attr( $product_ids ) . '"]' );
		if ( empty( $shortcode ) ) {
			$this->upsell_funnel::remove_session();
			
			return;
		}
		if ( $position === '0' ) {
			if ( ! $this->settings->get_params( 'us_redirect_page_endpoint' ) ) {
				$this->upsell_funnel::remove_session();
				
				return;
			}
			$redirect_url = wc_get_endpoint_url( 'viwcuf_us_endpoint', '', wc_get_checkout_url() );
			if ( ! $redirect_url ) {
				$this->upsell_funnel::remove_session();
				
				return;
			}
			if ( ! wp_doing_ajax() ) {
				exit( wp_redirect( $redirect_url ) );
			} else {
				$viwcuf_array = array(
					'is_popup'     => $position ?: '',
					'rule'         => $rule_id,
					'position'     => $position,
					'product_ids'  => $product_ids,
					'popup_html'   => '',
					'redirect_url' => $redirect_url,
				);
			}
		} else {
			$viwcuf_array = array(
				'is_popup'                  => $position ?: '',
				'wc_process_checkout_nonce' => wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', true, false ),
				'rule'                      => $rule_id,
				'position'                  => $position,
				'product_ids'               => $product_ids,
				'popup_html'                => $shortcode,
				'redirect_url'              => '',
			);
		}
		$viwcuf_html = wp_json_encode( $viwcuf_array );
		$viwcuf_html = function_exists( 'wc_esc_json' ) ? wc_esc_json( $viwcuf_html ) : _wp_specialchars( $viwcuf_html, ENT_QUOTES, 'UTF-8', true );
		$viwcuf_html = '<div class="vi-wcuf-disable" data-viwcufenable="yes" data-viwcufdata="' . $viwcuf_html . '"></div>';
		$args        = array(
			'result'   => 'failure',
			'messages' => $viwcuf_html,
			'refresh'  => '',
			'reload'   => '',
		);
		wp_send_json( $args );
		die();
	}
	
	public function viwcuf_us_woocommerce_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
		if ( ! empty( $values['viwcuf_us_product'] ) ) {
			$item->add_meta_data( '_vi_wcuf_us_info', 1 );
			$added   = WC()->session->get( 'viwcuf_us_added_products', array() );
			$added[] = array(
				'product_id'   => $values['product_id'] ?? '',
				'variation_id' => $values['variation_id'] ?? '',
			);
			WC()->session->set( 'viwcuf_us_added_products', $added );
		}
	}
	
	public function viwcuf_us_woocommerce_checkout_update_order_meta( $order_id, $data ) {
		$added_product = WC()->session->get( 'viwcuf_us_added_products', '' );
		if ( ! empty( $added_product ) ) {
			$arg             = WC()->session->get( 'viwcuf_us_rule_info', array() );//[discount_type,discount_amount,quantity_limit,products]
			$arg['products'] = $added_product;
			$us_info         = json_encode( $arg );
			if ( $this->table::get_row_by_order_id( $order_id ) ) {
				$this->table::update_by_order_id( $order_id, array( 'us_info' => $us_info ) );
			} else {
				$this->table::insert( $order_id, $data['billing_email'] ?? '', date( 'Y-m-d' ), get_current_user_id(), $us_info, '' );
			}
		}
		$this->upsell_funnel::remove_session();
	}
}