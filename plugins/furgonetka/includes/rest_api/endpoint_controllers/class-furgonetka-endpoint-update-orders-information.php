<?php

/**
 * The file that defines update order endpoint
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controllers
 */

/**
 * Class Furgonetka_Endpoint_Update_Orders_Information - manage /furgonetka/v1/packages endpoint
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controllers
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Endpoint_Update_Orders_Information extends Furgonetka_Endpoint_Abstract
{
    /**
     * Model
     *
     * @var Furgonetka_Update_Order_Model
     */
    private $model;

    /**
     * Collection
     *
     * @var \Furgonetka_Package_Information_Collection
     */
    private $collection;

    /**
     * Register route, rest base, include model and collection
     */
    public function __construct()
    {
        $this->rest_base = 'packages';
        parent::__construct();
    }

    /**
     * Register route
     *
     * @return void
     */
    public function register_route(): void
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'callback' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );
    }

    /**
     * Include Model
     *
     * @return void
     */
    public function include_model(): void
    {
        require_once FURGONETKA_REST_DIR . 'models/class-furgonetka-update-order-model.php';
        $this->model = new Furgonetka_Update_Order_Model();
    }

    /**
     * Include Collection
     *
     * @return void
     */
    public function include_collection(): void
    {
        require_once FURGONETKA_REST_DIR . 'collections/class-furgonetka-package-information-collection.php';
        $this->collection = new Furgonetka_Package_Information_Collection();
    }

    /**
     * Update orders information
     *
     * @param \WP_REST_Request $request - response.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function callback( WP_REST_Request $request )
    {
        foreach ( json_decode( $request->get_body(), false ) as $single_update_request ) {
            $order_id = null;

            /** Order number is given, thus should be checked first */
            if ( ! empty ( $single_update_request->orderNumber ) ) {
                $order_id = $this->get_order_id_from_order_number( (string) $single_update_request->orderNumber );
            }

            /** Order number is not given nor found, use order ID instead */
            if ( ( $order_id === null ) && ( ! empty ( $single_update_request->orderId ) ) ) {
                $order_id = $single_update_request->orderId;
            }

            $order = wc_get_order( $order_id );

            if ( ! $order ) {
                $this->add_to_log( "ERROR UPDATING ORDER : Invalid order id : {$order_id}", 'furgonetka_api_update_orders' );
                $this->add_to_log( $single_update_request, 'furgonetka_api_update_orders' );
            } else {
                // Prepare item for database.
                $items_for_database = $this
                    ->collection
                    ->set_collection($single_update_request)
                    ->prepare_data_for_single_order_update()
                    ->get_results();

                // Update data in database.
                $this
                    ->model
                    ->set_data($items_for_database)
                    ->set_order_id($order_id)
                    ->save_data_to_order_metadata();
            }
        }

        return new WP_REST_Response( array(), 200 );
    }

    /**
     * Get order ID from order number
     *
     * @param string $order_number
     * @return string
     */
    private function get_order_id_from_order_number( $order_number ) {

        $args = array(
            'meta_key'      => Furgonetka_Admin::METADATA_FURGONETKA_ORDER_NUMBER,
            'meta_value'    => $order_number,
            'meta_compare'  => '=',
            'return'        => 'ids',
            'limit'         => 1,
        );

        $ordersIds = wc_get_orders( $args );

        if ( ! empty ( $ordersIds ) ) {
            return reset( $ordersIds );
        }

        return null;
    }
}
