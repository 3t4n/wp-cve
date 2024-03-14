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
 * Class Furgonetka_Endpoint_Post_Return_Info - manage POST /furgonetka/v1/returns endpoint
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/returns
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Endpoint_Post_Return_Info extends Furgonetka_Endpoint_Abstract
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
     * Returns Class
     *
     * @var $model
     */
    private $returns;

    /**
     * Register route, rest base, include model and collection
     */
    public function __construct()
    {
        $this->rest_base = 'returns';
        $this->include_returns();
        parent::__construct();
    }

    /**
     * Include Return class
     *
     * @return void
     */
    public function include_returns(): void
    {
        require_once WP_PLUGIN_DIR . '/furgonetka/includes/class-furgonetka-returns.php';
        $this->returns = new Furgonetka_Returns();
    }

    /**
     * Include model
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
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'callback' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );
    }

    /**
     * Check if route is exist, if not set route url.
     *
     * @param \WP_REST_Request $request - request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function callback( WP_REST_Request $request )
    {
        $data = json_decode( $request->get_body() );
        if ( $this->returns->check_if_route_exists( $data->route ) ) {
            return new WP_REST_Response(
                array(
                    'code'    => 'rest_url_route_taken',
                    'message' => "Route '{$data->route}' is already taken",
                    'data'    => array(
                        'status' => 400,
                    ),
                ),
                400
            );
        }
        $target_is_correct = strpos( $data->target, 'furgonetka.pl' );
        if ( ! $target_is_correct && is_bool( $target_is_correct ) ) {
            return new WP_REST_Response(
                array(
                    'code'    => 'invalid_target',
                    'message' => "Target '{$data->target}' shoud contain furgonetka.pl",
                    'data'    => array(
                        'status' => 400,
                    ),
                ),
                400
            );
        }
        $this->model->save_rewrite_options( $data->target, $data->route, $data->active );
        return new WP_REST_Response( array(), 200 );
    }
}
