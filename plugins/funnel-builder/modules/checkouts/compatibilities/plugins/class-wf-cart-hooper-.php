<?php

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WF_Cart_Hooper {
	public function __construct() {
		add_action( 'admin_head', [ $this, 'action' ], 98 );
	}

	public function action() {

		if ( ! isset( $_GET['page'] ) || ! defined( 'WFCH_SLUG' ) || $_GET['page'] != WFCH_SLUG ) {
			return;
		}
		$this->save_publish_checkout_pages_in_transient( true );

	}


	public function save_publish_checkout_pages_in_transient( $force = true, $count = '-1' ) {


		$output   = [];
		$output[] = [
			'id'   => '0',
			'name' => __( 'Default WooCommerce Checkout Page', 'woofunnels-aero-checkout' ),
			'type' => 'default',
		];
		$data     = WFACP_Common::get_saved_pages();
		if ( is_array( $data ) && count( $data ) > 0 ) {

			foreach ( $data as $v ) {
				$output[] = [
					'id'   => $v['ID'],
					'name' => $v['post_title'],
					'type' => 'wfacp',
				];
			}
		}


		if ( count( $output ) == 0 ) {

			return [];
		}

		/**
		 * @var $Woofunnel_cache_obj WooFunnels_Cache
		 */
		$Woofunnel_cache_obj     = WooFunnels_Cache::get_instance();
		$Woofunnel_transient_obj = WooFunnels_Transient::get_instance();

		$cache_key = 'wfacp_publish_posts';
		/** $force = true */
		if ( true === $force ) {
			$Woofunnel_transient_obj->set_transient( $cache_key, $output, DAY_IN_SECONDS, WFACP_SLUG );

			return $output;
		}

	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WF_Cart_Hooper(), 'wfacp-wf-cart-hooper' );