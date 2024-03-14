<?php

/**
 *  Class WC_REST_Orderstatuses_Controller file.
 */
defined('ABSPATH') || exit;

class WC_REST_Orderstatuses_Controller extends WC_REST_Controller {

    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wc/v3';

    /**
     * Route base.
     *
     * @var string
     */
    protected $restBase = 'nova-order-statuses';

    /**
     * Register routes.
     *
     * @since 3.5.0
     */
    public function register_routes() {

        register_rest_route(
                $this->namespace, '/' . $this->restBase, array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array($this, 'list_statutes'),
            'permission_callback' => array($this, 'check_salesorder_permissions_check'),
            'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::READABLE),
                )
        );
    }

    /**
     * Check Permissions
     * @param type $request
     * @return \WP_Error|boolean
     */
    public function check_salesorder_permissions_check($request) {

        if (!wc_rest_check_post_permissions("shop_order", 'read')) {
            return new WP_Error('woocommerce_rest_cannot_view', __('Sorry, you cannot view this resource.', 'woocommerce'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * List Woo Statues
     * @param WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function list_statutes(WP_REST_Request $request) {
        $wpRestServer = rest_get_server();
        $reqdata = $request->get_query_params();
        $orderStatuses = array(
            "pending" => "Pending payment",
            "processing" => "Processing",
            "on-hold" => "On hold",
            "completed" => "Completed",
            "cancelled" => "Cancelled",
            "refunded" => "Refunded",
            "failed" => "Failed"
        );

        $response = new WP_REST_Response($orderStatuses);
        if (function_exists('wc_get_order_statuses') === true) {
            $orderStatuses = array();
            foreach (wc_get_order_statuses() as $slug => $name) {
                $orderStatuses[str_replace('wc-', '', $slug)] = $name;
            }
            $response = new WP_REST_Response($orderStatuses);
        }
        $response->header('x-wp-total',count($orderStatuses));
        $response->header('x-wp-totalpages', 1);
        return $response;
    }

}
