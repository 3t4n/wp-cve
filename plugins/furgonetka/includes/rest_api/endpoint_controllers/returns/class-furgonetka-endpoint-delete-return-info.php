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
 * Class Furgonetka_Endpoint_Delete_Return_Info - manage DELETE /furgonetka/v1/returns endpoint
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/returns
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Endpoint_Delete_Return_Info extends Furgonetka_Endpoint_Abstract
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
     * @var $model
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
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'callback' ),
                'permission_callback' => array( $this, 'permission_callback' ),
            )
        );
    }

    /**
     * Delete all saved data
     *
     * @param \WP_REST_Request $request - request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function callback( WP_REST_Request $request )
    {
        $this->model->delete_rewrite_options();
        flush_rewrite_rules();
        return new WP_REST_Response( array(), 200 );
    }
}
