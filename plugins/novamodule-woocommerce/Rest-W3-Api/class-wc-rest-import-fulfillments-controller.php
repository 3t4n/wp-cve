<?php
/**
 * Class WC_REST_Orderimportfulfillments_Controller file.
 */
defined('ABSPATH') || exit;

class WC_REST_Orderimportfulfillments_Controller extends WC_REST_Controller {

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
    protected $restBase = 'nova-order-import-fulfillments';

    /**
     * Register routes.
     *
     * @since 3.5.0
     */
    public function register_routes() {


        register_rest_route(
                $this->namespace, '/' . $this->restBase, array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'edit_orderimport_status'),
            'permission_callback' => array($this, 'check_order_permissions_check'),
            'args' => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
                )
        );
    }

    /**
     * Check Permissions
     * @param type $request
     * @return \WP_Error|boolean
     */
    public function check_order_permissions_check($request) {


        if (!wc_rest_check_post_permissions("shop_order", 'create')) {
            return new WP_Error('woocommerce_rest_cannot_create', __('Sorry, you are not allowed to create resources.', 'woocommerce'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Create shipment record
     * @param WP_REST_Request $request
     * @return boolean|string
     */
    public function edit_orderimport_status(WP_REST_Request $request) {
        try {
            $wpRestServer = rest_get_server();
            $reqdata_data = $request->get_params();
            $toReturn = array();
            $data = $reqdata_data ['fulillments'];
            foreach ($data as $key => $value) {
                $order_id = "";
                $tracking_number = "";
                $provider = "";
                $date_shipped = "";
                $custom_url = "";
                $netSuiteOrderId = "";
                if (isset($value['ns_id'])) {
                    $netSuiteOrderId = $value['ns_id'];
                }
                if (isset($value['order_id'])) {
                    $order_id = $value['order_id'];
                }
                if (isset($value['tracking_number'])) {
                    $tracking_number = $value['tracking_number'];
                }
                if (isset($value['provider'])) {
                    $provider = $value['provider'];
                }
                if (isset($value['date_shipped'])) {
                    $date_shipped = $value['date_shipped'];
                }
                if (isset($value['custom_url'])) {
                    $custom_url = $value['custom_url'];
                }
                if (isset($value ['meta_data'])) {
                    $meta_data = $value ['meta_data'];
                    foreach ($meta_data as $meta_key => $meta_value) {
                        if (isset($meta_value["key"]) && isset($meta_value["value"])) {
                            update_post_meta($order_id, $meta_value["key"], $meta_value["value"]);
							
							$order = new WC_Order( $order_id );
							$order->update_meta_data( $meta_value["key"], $meta_value["value"] );
							$order->save_meta_data();
			
                        }
                    }
                }
				if(isset($value['ignore_shipment_update']) && $value['ignore_shipment_update'] == 1) {
					 
					$toReturn[$key] = array("statusCode" => 200, "id" => $order_id);
					continue;
				}
                if (function_exists('wc_st_add_tracking_number')) {
                    if ($this->checkIfOrderExists($order_id)) {
						if($date_shipped != "") {
						   $date_shipped = strtotime($date_shipped);
						} 
                        $trakingDetails = wc_st_add_tracking_number($order_id, $tracking_number, $provider, $date_shipped, $custom_url);
                        $toReturn[$key] = array("statusCode" => 200, "id" => $order_id);
                    } else {
                        $toReturn[$key] = array("statusCode" => 422);
                        $toReturn[$key]["errors"] = array();
                        $toReturn[$key]["errors"][] = array("code" => "173", "message" => "Error:: Order with ID (" . $order_id . ") does not exist");
                    }
                } else {
                    $toReturn[$key] = array("statusCode" => 422);
                    $toReturn[$key]["errors"] = array();
                    $toReturn[$key]["errors"][] = array("code" => "172", "message" => "Unexpected Error:: Invalid Setup");
                }
            }
            return $toReturn;
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Check existing Order
     * @global type $wpdb
     * @param type $post_ID
     * @return boolean
     */
    public function checkIfOrderExists($post_ID = '') {
        if (!$post_ID) {
            return false;
        }
        $post_ID = (int) $post_ID;
        global $wpdb;
        $post_id = false;
        $posttable = $wpdb->prefix . "posts";
        $query = $wpdb->prepare("SELECT ID FROM " . $posttable . " WHERE ID =%d", $post_ID);
        $results = $wpdb->get_results($query);

        foreach ($results as $key => $value) {
            $post_id = $value->ID;
            return $post_id;
        }
        return $post_id;
    }

}
