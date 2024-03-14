<?php

/**
 * The file that defines update order endpoint
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controllers/
 */

/**
 * Class Furgonetka_Endpoint_Update_package_information - manage /furgonetka/v1/package/{id} endpoint
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/returns
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Endpoint_Update_Order_Information extends Furgonetka_Endpoint_Abstract
{
    /**
     * Rest base
     *
     * @var string
     */
    public $rest_base;

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
        $this->rest_base = 'package/(?P<id>[\d]+)';
        parent::__construct();
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
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array( $this, 'callback' ),
                'permission_callback' => array( $this, 'permission_callback' ),
                'args'                => array(
                    'id' => array(
                        'validate_callback' => function ( $param, $request, $key )
                        {
                            return is_numeric( $param );
                        },
                        'required'          => true,
                        'type'              => 'number',
                    ),
                ),
            )
        );
    }

    /**
     * Update order data
     *
     * @param \WP_REST_Request $request - response.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function callback( WP_REST_Request $request )
    {
        $order_id = $request->get_param( 'id' );
        $order    = wc_get_order( $order_id );

        if ( ! $order ) {
            return new WP_REST_Response(
                array(
                    'code'    => 'wrong_order_id',
                    'message' => 'Invalid order id',
                    'data'    => array(
                        'status' => 400,
                    ),
                ),
                400
            );
        }

        // Prepare item for database.
        $items_for_database = $this->collection
            ->set_collection( (array) json_decode( $request->get_body() ) )
            ->prepare_data_for_single_order_update()
            ->get_results();

        // Update data in database.

        $this->model->set_data( $items_for_database )
            ->set_order_id( $order_id )
            ->save_data_to_order_metadata();

        return new WP_REST_Response( array(), 200 );
    }
}
