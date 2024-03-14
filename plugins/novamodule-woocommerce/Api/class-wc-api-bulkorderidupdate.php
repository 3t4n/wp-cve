<?php

/**
 * Class WC_API_Bulkorderidupdate file.
 */
defined('ABSPATH') || exit;
include_once("debugLogger.php");

class WC_API_Bulkorderidupdate extends WC_API_Resource {

    protected $base = '/bulkorderidupdate';
    public $logger;

    function __construct(WC_API_Server $server) {
        $this->logger = new NovaLogger("novamodule-bulkorderidupdate.log");
        parent::__construct($server);
    }

    public function register_routes($routes) {
        $routes[$this->base] = array(
            array(array($this, 'edit_bulkorderidupdate'), WC_API_Server::EDITABLE | WC_API_Server::ACCEPT_DATA),
        );
        return $routes;
    }

    /**
     * Update Order pushed status
     * @param type $data
     * @return array
     */
    public function edit_bulkorderidupdate($data) {
        $this->logger->debugLogger(json_encode($data));
        $this->logger->debugLogger("====================");
        $this->logger->debugLogger(json_encode($_REQUEST));
        $toReturn = array();
        foreach ($data["order_ids"] as $order_id) {

            update_post_meta($order_id, 'nm_ns_pushed', 1);
        }
        return $toReturn;
    }

}
