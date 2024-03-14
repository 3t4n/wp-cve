<?php

/**
 * Class WC_API_Bulkfulfillment file.
 */
defined('ABSPATH') || exit;
include_once("debugLogger.php");

class WC_API_Bulkfulfillment extends WC_API_Resource {

    protected $base = '/bulkfulfillment';
    public $logger;

    function __construct(WC_API_Server $server) {
        $this->logger = new NovaLogger("novamodule-bulkfulfillment.log");
        parent::__construct($server);
    }

    public function register_routes($routes) {
        $routes[$this->base] = array(
            array(array($this, 'edit_bulkfulfillment'), WC_API_Server::EDITABLE | WC_API_Server::ACCEPT_DATA),
        );
        return $routes;
    }

    /**
     * Create shipment record
     * @param WP_REST_Request $request
     * @return boolean|string
     */
    public function edit_bulkfulfillment($data) {
        $this->logger->debugLogger(json_encode($data));
        $this->logger->debugLogger("====================");
        $toReturn = array();

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
            if (function_exists('wc_st_add_tracking_number')) {
                if ($this->checkIfOrderExists($order_id)) {
                    $trakingDetails = wc_st_add_tracking_number($order_id, $tracking_number, $provider, $date_shipped, $custom_url);
                    $this->logger->debugLogger("========= trakingDetails ===========");
                    $this->logger->debugLogger(json_encode($trakingDetails));
                    $this->logger->debugLogger("====================");

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
        $query = $wpdb->prepare("SELECT ID FROM " . $posttable . " WHERE post_type IN ('shop_order') AND ID =%d", $post_ID);
        $results = $wpdb->get_results($query);
        $this->logger->debugLogger("====================");
        $this->logger->debugLogger($query);
        $this->logger->debugLogger("====================");
        foreach ($results as $key => $value) {
            $post_id = $value->ID;
            return $post_id;
        }
        return $post_id;
    }

}
