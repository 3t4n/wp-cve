<?php
if (!defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Epaka.pl
 * @since      1.0.0
 *
 * @package    Epaka
 * @subpackage Epaka/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Epaka
 * @subpackage Epaka/admin
 * @author     Epaka <bok@epaka.pl>
 */


class Epaka_Api {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
    private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
    private $version;

    private $api_controller;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
    public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->api_controller = new Epaka_Api_Controller();
    }
    
    public function  rest_api_routes(){
        $this->defineAdminRoutes();
        $this->definePublicRoutes();
    }

    private function definePublicRoutes(){

        register_rest_route('epaka-public','/map',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getMap'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('epaka-public','/map',[
            'methods' => 'GET',
            'callback' => [$this->api_controller,'getMap'],
            'permission_callback' => '__return_true',
        ]);

    } 

    private function defineAdminRoutes(){
        register_rest_route('epaka-admin','/save-profile',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'saveProfile'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/set-shipping-courier-mapping',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'setShippingCourierMapping'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-epaka-order-label',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getEpakaOrderLabel'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-epaka-order-protocol',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getEpakaOrderProtocol'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);
        register_rest_route('epaka-admin','/get-epaka-order-authorization-document',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getEpakaOrderAuthorizationDocument'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);
        register_rest_route('epaka-admin','/get-epaka-order-proforma',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getEpakaOrderProforma'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);
        
        register_rest_route('epaka-admin','/unlink-epaka-order-from-woo-order',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'unlinkEpakaOrderFromWooOrder'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/link-epaka-order-to-woo-order',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'linkEpakaOrderToWooOrder'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/cancel-epaka-order',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'cancelEpakaOrder'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-epaka-courier-tracking',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getEpakaCourierTracking'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-epaka-order-label-zebra',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getEpakaOrderLabelZebra'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/authorize',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'authorize'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-profile',[
            'methods' => 'GET',
            'callback' => [$this->api_controller,'getProfile'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/send-order',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'sendOrder'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-order-iframe',[
            'methods' => 'GET',
            'callback' => [$this->api_controller,'getOrderIframe'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/get-order-template-iframe',[
            'methods' => 'POST',
            'callback' => [$this->api_controller,'getOrderTemplateIframe'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);

        register_rest_route('epaka-admin','/logout',[
            'methods' => 'GET',
            'callback' => [$this->api_controller,'logout'],
            'permission_callback' => function () {
                return $_GET['token'] == get_option("epakaAdminToken"); 
            }
        ]);
    } 
}