<?php

defined('ABSPATH') || exit;

class WC_REST_Orderimportstatuse_Controller extends WC_REST_Controller {

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
    protected $rest_base = 'nova-order-import-status';

    /**
     * Register routes.
     *
     * @since 3.5.0
     */
    public function register_routes() {

        register_rest_route(
                $this->namespace, '/' . $this->rest_base, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'edit_orderimport_status'),
            'permission_callback' => array($this, 'check_order_permissions_check'),
            'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
                )
        );
    }
    

    public function check_order_permissions_check($request) {


        if (!wc_rest_check_post_permissions("shop_order", 'create')) {
            return new WP_Error('woocommerce_rest_cannot_create', __('Sorry, you are not allowed to create resources.', 'woocommerce'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    public function edit_orderimport_status(WP_REST_Request $request) {
        try {
            $wp_rest_server = rest_get_server();
            $reqdata_data = $request->get_params();
            $reqdata = $reqdata_data["order_ids"];


            for ($i = 0; $i < count($reqdata); $i++) {
                $order_id = $reqdata[$i]['id'];
                if ($order_id > 0) {

                    update_post_meta($order_id, 'nm_ns_pushed', 1);
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
