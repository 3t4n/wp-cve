<?php

/**
 * The file that defines class for managing rest api
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/models
 */

/**
 * Class Furgonetka_rest_api - manage REST API
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/models
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_rest_api
{
    /**
     * Version
     *
     * @var string
     */
    private $version;

    /**
     * Furgonetka_rest_api constructor.
     * Set API version, register constans and endpoints
     */
    public function __construct()
    {
        $this->version = '1';
        $this->define_constans();
        $this->load_helper();
        $this->register_endpoints();
    }

    /**
     * Define constans
     *
     * @return void
     */
    private function define_constans()
    {
        define( 'FURGONETKA_REST_NAMESPACE', 'furgonetka/v' . $this->version );
        define( 'FURGONETKA_REST_DIR', plugin_dir_path( __FILE__ ) );
    }

    /**
     * Register endpoints
     *
     * @return void
     */
    private function register_endpoints()
    {
        $endpoint_controllers = FURGONETKA_REST_DIR . 'endpoint_controllers/';
        /**
         * Abstract endpoint controller
         */
        require_once $endpoint_controllers . 'class-furgonetka-endpoint-abstract.php';
        /**
         * Update single order
         */
        require_once $endpoint_controllers . 'class-furgonetka-endpoint-update-order-information.php';
        new Furgonetka_Endpoint_Update_Order_Information();
        /**
         * Update multiple orders
         */
        require_once $endpoint_controllers . 'class-furgonetka-endpoint-update-orders-information.php';
        new Furgonetka_Endpoint_Update_Orders_Information();
        /**
         * Returns
         */
        require_once $endpoint_controllers . 'returns/class-furgonetka-endpoint-get-return-info.php';
        require_once $endpoint_controllers . 'returns/class-furgonetka-endpoint-post-return-info.php';
        require_once $endpoint_controllers . 'returns/class-furgonetka-endpoint-delete-return-info.php';
        require_once $endpoint_controllers . 'returns/class-furgonetka-endpoint-update-return-info.php';
        new Furgonetka_Endpoint_Get_Return_Info();
        new Furgonetka_Endpoint_Post_Return_Info();
        new Furgonetka_Endpoint_Delete_Return_Info();
        new Furgonetka_Endpoint_Update_Return_Info();
    }

    /**
     * Load helper class
     *
     * @return void
     */
    private function load_helper()
    {
        $helper_path = FURGONETKA_REST_DIR . 'helper/';

        require_once $helper_path . 'class-furgonetka-rest-helper.php';
    }
}
