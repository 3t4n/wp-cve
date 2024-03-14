<?php

namespace AppBuilder\AdvancedRestApi;

defined( 'ABSPATH' ) || exit;

use WCFM_REST_Order_Controller;

global $WCFMapi;
require_once( $WCFMapi->plugin_path . '/includes/api/class-api-order-controller.php' );

class Order extends WCFM_REST_Order_Controller {

	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $post_type = 'shop_order';

	/**
	 * Route name
	 *
	 * @var string
	 */
	protected $base = 'orders-advanced';

	protected function get_objects_from_database( $request ) {

		$data = $request->get_params();

		global $WCFM;
		$_POST["controller"] = 'wcfm-orders';
		$_POST['length']     = ! empty( $data['per_page'] ) ? intval( $data['per_page'] ) : 10;
		$_POST['start']      = ! empty( $data['page'] ) ? ( intval( $data['page'] ) - 1 ) * $_POST['length'] : 0;
//    if(empty($data['page'])){
//      $_POST['start'] = !empty($data['offset']) ? intval($data['offset']) : 0;
//    }
		$_POST['filter_date_form'] = ! empty( $data['after'] ) ? $data['after'] : '';
		$_POST['filter_date_to']   = ! empty( $data['before'] ) ? $data['before'] : '';
		$_POST['search']['value']  = ! empty( $data['search'] ) ? $data['search'] : '';
		$_POST['orderby']          = ! empty( $data['orderby'] ) ? $data['orderby'] : '';
		$_POST['order']            = ! empty( $data['order'] ) ? $data['order'] : '';

		$_POST['order_status']     = ! empty( $data['status'] ) ? $data['status'] : 'all';

		$_REQUEST['wcfm_ajax_nonce'] = wp_create_nonce( 'wcfm_ajax_nonce' );

		define( 'WCFM_REST_API_CALL', true );
		$WCFM->init();
		$orders = $WCFM->ajax->wcfm_ajax_controller();

		return $orders;
	}
}
