<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_IW_TCS {

	/**
	 * @var $iw_freebe IW_TCS_Freebie
	 */
	private $iw_freebe = null;

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_ajax' ] );
		add_action( 'wfacp_get_fragments', [ $this, 'actions' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $this, 'update_order_review' ] );
		add_action( 'wfacp_before_add_to_cart', [ $this, 'actions' ] );
		add_action( 'wfacp_after_add_to_cart', [ $this, 'clear_session' ] );
		add_action( 'woocommerce_update_order_review_fragments', [ $this, 'fragments' ] );

	}

	public function remove_ajax() {
		remove_all_actions( 'wp_ajax_iw_tcs_b4_checkout' );
		remove_all_actions( 'wp_ajax_nopriv_iw_tcs_b4_checkout' );
	}

	public function update_order_review( $postdata ) {
		$post_data = [];
		parse_str( $postdata, $post_data );
		if ( isset( $post_data['_wfacp_post_id'] ) ) {
			$this->actions();
		}
	}

	public function actions() {
		if ( class_exists( 'IW_TCS_Freebie' ) ) {
			global $wp_filter;
			foreach ( $wp_filter['woocommerce_add_to_cart']->callbacks as $key => $val ) {
				foreach ( $val as $innerkey => $innerval ) {
					if ( isset( $innerval['function'] ) && is_array( $innerval['function'] ) ) {
						if ( is_a( $innerval['function']['0'], 'IW_TCS_Freebie' ) ) {
							$mk_customizer   = $innerval['function']['0'];
							$this->iw_freebe = $mk_customizer;
							//                          $this->clear_session();
							break;
						}
					}
				}
			}
		}
	}

	public function clear_session() {
		if ( class_exists( 'IW_TCS_Freebie' ) ) {
			if ( $this->iw_freebe instanceof IW_TCS_Freebie ) {
				$this->iw_freebe->freebies = get_option( 'iw_tcs_settings', array() );
				$this->iw_freebe->clear_session();
				$this->iw_freebe->monitor_add_to_cart();
			}
		}

	}

	public function fragments( $fragments ) {
		if ( class_exists( 'IW_TCS_Freebie' ) && $this->iw_freebe instanceof IW_TCS_Freebie ) {
			$this->iw_freebe->freebies = get_option( 'iw_tcs_settings', array() );
			$this->iw_freebe->clear_session();
			$this->iw_freebe->monitor_add_to_cart();

			ob_start();
			$this->iw_freebe->b4_checkout_details();
			$html                             = ob_get_clean();
			$fragments['.iw-tcs-b4-checkout'] = $html;

		}

		return $fragments;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_IW_TCS(), 'iw_tcs' );
