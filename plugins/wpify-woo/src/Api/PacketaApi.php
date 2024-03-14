<?php

namespace WpifyWoo\Api;

use WP_REST_Response;
use WP_REST_Server;
use WpifyWoo\Models\PacketaOrderModel;
use WpifyWoo\Plugin;
use WpifyWoo\Repositories\PacketaOrderRepository;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractRest;

/**
 * @property Plugin $plugin
 */
class PacketaApi extends AbstractRest {

	/**
	 * ExampleApi constructor.
	 */
	public function __construct() {
	}

	public function setup() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->plugin->get_api_manager()->get_rest_namespace(),
			'packeta/order-details',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'save_order_details' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_woocommerce' );
					},
				),
			)
		);
	}

	/**
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|\WP_REST_Request|\WP_REST_Response | bool
	 */
	public function save_order_details( $request ) {
		$repo = $this->plugin->get_repository(PacketaOrderRepository::class);
		/** @var PacketaOrderModel $order */
		$order = $repo->get($request->get_param('order_id'));
		$order->set_packeta_weight($request->get_param('weight'));

		$repo->save($order);

		return new WP_REST_Response( array('status' => 'ok'), 201 );
	}

	/**
	 * Check if a given request has access to create items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 *
	 * @return \WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		return true;
	}

	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed $item WordPress representation of the item.
	 * @param \WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		return array();
	}
}
