<?php

/**
 * Compatibility for create order by order-pay in admin
 */
#[AllowDynamicProperties] 

  class WFACP_WC_Order_Pay {
	public function __construct() {
		add_action( 'woocommerce_before_pay_action', [ $this, 'update_aero_id' ], 10, 1 );
		add_action( 'before_woocommerce_pay', [ $this, 'update_checkout_views' ] );
		add_filter( 'woocommerce_payment_successful_result', [ $this, 'update_checkout_reporting' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	/**
	 * add aero id in order meta
	 *
	 * @param $order WC_Order
	 *
	 * @return void
	 */
	public function update_aero_id( $order ) {
		if ( ! isset( $_POST['_wfacp_post_id'] ) || ! isset( $_POST['woocommerce_pay'] ) ) {
			return;
		}

		$wfacp_id = absint( $_POST['_wfacp_post_id'] );
		if ( $wfacp_id > 0 ) {
			$order->update_meta_data( '_wfacp_post_id', $wfacp_id );
			$order->save();
		}
	}

	/**
	 * update checkout views reporting data
	 * @return void
	 */
	public function update_checkout_views() {
		if ( isset( $_GET['pay_for_order'] ) && "true" === $_GET['pay_for_order'] ) {
			$wfacp_id = WFACP_Common::get_id();
			if ( $wfacp_id > 0 ) {
				$str = '_wfacp_post_id=' . $wfacp_id;
				WFACP_Core()->reporting->update_order_review( $str );
			}
		}
	}

	/**
	 * update checkout reporting meta
	 *
	 * @param $result
	 * @param $order_id
	 *
	 * @return mixed
	 */
	public function update_checkout_reporting( $result, $order_id ) {
		$order    = wc_get_order( $order_id );
		$wfacp_id = $order->get_meta( '_wfacp_post_id' );
		if ( empty( $wfacp_id ) ) {
			return $result;
		}

		$posted_data = array(
			'wfacp_post_id'         => $wfacp_id,
			'wfacp_woocommerce_pay' => true
		);

		WFACP_Core()->reporting->update_reporting_data_in_meta( $order, $posted_data );

		return $result;
	}

	public function internal_css() {
		if ( ! WFACP_Core()->pay->is_order_pay() || ! function_exists( 'wfacp_template' ) ) {
			return;
		}

		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body.woocommerce-order-pay ";

		if ( 'pre_built' !== $instance->get_template_type() ) {

			$bodyClass = "body.woocommerce-order-pay #wfacp-e-form ";
		}


		$cssHtml = "<style>";

		$cssHtml .= $bodyClass . ".woocommerce ul.order_details:after{content: '';display: table;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details:before{content: '';display: table;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details:after{clear:both;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details li{float: left;margin-right: 2em;text-transform: uppercase;font-size: .715em;line-height: 1.5;border-right: 1px dashed #d3ced2;padding-right: 2em;margin-left: 0;padding-left: 0;list-style-type: none;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details li strong{display: block;font-size: 1.4em;text-transform: none;line-height: 1.5;    word-break: break-all;}";
		$cssHtml .= $bodyClass . ".woocommerce button{display: inline-block;font-weight: 400;color: inherit;text-align: center;white-space: nowrap;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-color: transparent;border: 1px solid #ededed;padding: 0.5rem 1rem;font-size: 1rem;border-radius: 8px;-webkit-transition: all .3s;-o-transition: all .3s;transition: all .3s;}";

		$cssHtml .= "@media (min-width: 768px) {";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details{margin: 0 0 3em;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details li:last-child{padding:0;margin:0;border:none;}";
		$cssHtml .= "}";


		$cssHtml .= "@media (max-width: 767px) {";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details li{width: 50%;margin: 0 0 30px;padding: 0 15px 0 0;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details li:nth-child(2n) {border: none;padding: 0 0 0 15px;    text-align: right;}";
		$cssHtml .= $bodyClass . ".woocommerce ul.order_details ul.order_details{margin: 0;}";
		$cssHtml .= "}";
		$cssHtml .= "</style>";

		echo $cssHtml;


	}


}

new WFACP_WC_Order_Pay();
