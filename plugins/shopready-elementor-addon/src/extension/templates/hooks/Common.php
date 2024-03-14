<?php

namespace Shop_Ready\extension\templates\hooks;

use Illuminate\Config\Repository as Shop_Ready_Repository;

/*
* WooCommerece Shop Common
* Templates
*/

class Common {


	public function register() {

		add_filter( 'body_class', array( $this, 'set_body_class' ) );
		add_filter( 'shop_ready_sr_templates_config', array( $this, 'templates_config' ), 10 );

		// Cart Details Page

		add_filter( 'woocommerce_cart_contents_total', array( $this, 'get_cart_totals' ), 20, 1 );
		add_filter( 'elementor/document/urls/preview', array( $this, 'preview_url' ), 20, 1 );
		add_filter( 'elementor/document/urls/edit', array( $this, 'preview_url' ), 20, 1 );

	}

	public function preview_url( $url ) {

		if ( isset( $_GET['sr_tpl'] ) && isset( $_GET['tpl_type'] ) ) {

			$shop_url = add_query_arg(
				array(
					'sr_tpl'   => sanitize_text_field( $_GET['sr_tpl'] ),
					'tpl_type' => sanitize_text_field( $_GET['tpl_type'] ),
				),
				$url
			);

			return $shop_url;
		}

		return $url;
	}


	public function get_cart_totals( $data ) {

		return $data;
	}



	/**
	 * | set_body_class |
	 *
	 * @author     <quomodosoft.com>
	 * @since      File available since Release 1.0
	 * @param  [string] $classes
	 * @return array | []
	 */
	public function set_body_class( $classes ) {

		$woo_clas = array( 'shopready-elementor-addon', 'woocommerce' );

		if ( isset( $_GET['sr_tpl'] ) && isset( $_GET['tpl_type'] ) ) {

			$woo_clas[] = 'woocommerce';
			return array_merge( $classes, $woo_clas );

		}

		return array_merge( $classes, $woo_clas );

	}

	public function templates_config( $templates ) {

		$availables = array(
			'single',
			'shop',
			'order_received',
			'my_account_login_register',
		);

		$old = $templates->all();

		foreach ( $old as $key => $item ) {

			if ( in_array( $key, $availables ) ) {
				$old[ $key ]['is_pro'] = false;
			} else {
				$old[ $key ]['is_pro'] = true;
			}
		}

		$return_template = new Shop_Ready_Repository( $old );

		return $return_template;
	}


}
