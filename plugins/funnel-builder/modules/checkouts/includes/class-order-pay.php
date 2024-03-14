<?php

#[AllowDynamicProperties]

  class WFACP_Order_pay {
	private static $ins = null;
	private $is_pay_page = false;
	protected $order = null;
	protected $order_key = null;
	protected $order_id = null;

	protected function __construct() {
		add_action( 'wfacp_start_page_detection', [ $this, 'detect_order_pay' ] );
		add_filter( 'wfacp_global_checkout_page_id', [ $this, 'change_global_page_id' ] );
		add_filter( 'wfacp_skip_add_to_cart', [ $this, 'skip_add_cart' ] );
		add_filter( 'wfacp_form_template', [ $this, 'replace_form_template' ], 20 );
		add_filter( 'wfacp_disable_mini_cart', [ $this, 'disable_mini_cart' ] );
		add_filter( 'wfacp_redirect_embed_global_checkout_url', [ $this, 'do_not_redirect_to_embed_checkout' ], 10, 4 );
		add_action( 'woocommerce_before_pay_action', [ $this, 'remove_some_filter' ] );
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function detect_order_pay() {
		$this->is_pay_page = is_checkout_pay_page() && ! isset( $_REQUEST['change_payment_method'] );
		if ( $this->is_pay_page ) {
			global $wp;
			$this->order_id  = absint( $wp->query_vars['order-pay'] );
			$this->order_key = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : ''; // WPCS: input var ok, CSRF ok.
			$this->order     = wc_get_order( $this->order_id );
			if ( ! $this->order instanceof WC_Order ) {
				$this->is_pay_page = false;
			}
		}

		$this->is_pay_page = apply_filters( 'wfacp_is_order_pay_page', $this->is_pay_page, $this );

	}

	public function change_global_page_id( $page_id ) {
		if ( $this->is_pay_page && $this->order_id > 0 ) {
			// get aero page id from order meta
			$aero_post = wfacp_get_order_meta( wc_get_order( $this->order_id ), '_wfacp_post_id' );
			if ( $aero_post > 0 ) {
				return $aero_post;
			}
		}

		return $page_id;
	}

	public function is_order_pay() {
		return $this->is_pay_page;
	}

	public function disable_mini_cart() {
		return $this->is_order_pay();
	}


	public function skip_add_cart() {

		return $this->is_order_pay();
	}

	public function replace_form_template( $template ) {
		if ( $this->is_order_pay() ) {
			$template = WFACP_TEMPLATE_COMMON . '/form-order-pay.php';
		}

		return $template;
	}

	public function do_not_redirect_to_embed_checkout( $status, $ovveride_id, $may_be_post, $design_data ) {
		if ( true == $this->is_pay_page && 'embed_forms' == $design_data['selected_type'] && $ovveride_id > 0 ) {
			$status = false;
		}

		return $status;
	}

	public function get_order() {
		return $this->order;
	}

	public function get_order_id() {
		return $this->order_id;
	}

	public function get_order_key() {
		return $this->order_key;
	}

	public function remove_some_filter() {
		if ( ! is_null( WFACP_Core()->public ) ) {
			remove_filter( 'woocommerce_get_checkout_url', [ WFACP_Core()->public, 'woocommerce_get_checkout_url' ], 99999 );
		}
	}

}

if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
	WFACP_Core::register( 'pay', 'WFACP_Order_pay' );
}
