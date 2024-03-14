<?php

/**
 * The file that defines return info endpoint
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/returns
 */

/**
 * Class Furgonetka_Endpoint_Update_Return_Info - manage PUT/PATCH/POST /furgonetka/v1/returns endpoint
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/returns
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Endpoint_Update_Return_Info extends Furgonetka_Endpoint_Abstract
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
     * @var Furgonetka_Returns_Model
     */
    private $model;

    /**
     * Register route, rest base, include model and collection
     */
    public function __construct()
    {
        $this->rest_base = 'returns';
        parent::__construct();
    }

    /**
     * Register route, rest base, include model and collection
     *
     * @return void
     */
    public function include_model(): void
    {
        require_once FURGONETKA_REST_DIR . 'models/class-furgonetka-returns-model.php';
        $this->model = new Furgonetka_Returns_Model();
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
            )
        );
    }

    /**
     * Update return info
     *
     * @param \WP_REST_Request $request - request.
     *
     * @return \WP_REST_Response
     */
    public function callback( WP_REST_Request $request )
    {
        $route = get_option( $this->model->get_route_option_name() );
        $data  = json_decode( $request->get_body() );
        if ( empty( $route ) ) {
            return new WP_REST_Response(
                array(
                    'code'    => 'rest_url_route_not_set',
                    'message' => 'Route is not set',
                    'data'    => array(
                        'status' => 400,
                    ),
                ),
                400
            );
        }
        $target_is_correct = strpos( $data->target, 'https://furgonetka.pl' );
        if ( ! $target_is_correct && is_bool( $target_is_correct ) ) {
            return new WP_REST_Response(
                array(
                    'code'    => 'invalid_target',
                    'message' => "Target '{$data->target}' shoud contain https://furgonetka.pl",
                    'data'    => array(
                        'status' => 400,
                    ),
                ),
                400
            );
        }
        $this->model->save_rewrite_options( $data->target, $route, $data->active );
        return new WP_REST_Response( array(), 200 );
    }
}
