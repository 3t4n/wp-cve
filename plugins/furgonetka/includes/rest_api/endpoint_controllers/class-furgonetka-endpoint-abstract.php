<?php

/**
 * The file that defines enpoint abstractclass
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller
 */

/**
 * Class Furgonetka_Endpoint_Abstract - abstract controller for REST API endpoints
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
abstract class Furgonetka_Endpoint_Abstract
{
    /**
     * Namespace
     *
     * @var string
     */
    public $namespace;

    /**
     * Rest base
     *
     * @var string
     */
    public $rest_base;

    /**
     * Model class
     *
     * @var mixed
     */
    private $model;

    /**
     * Colection class
     *
     * @var mixed
     */
    private $collection;

    /**
     * Furgonetka_Endpoint_Abstract constructor.
     * Register route, include model and collection
     */
    public function __construct()
    {
        $this->namespace = FURGONETKA_REST_NAMESPACE;
        add_action( 'rest_api_init', array( $this, 'register_route' ) );

        $this->include_model();
        $this->include_collection();
    }
    /**
     * Include model
     *
     * @return void
     */
    abstract public function include_model();

    /**
     * Register route
     *
     * @return void
     */
    abstract public function register_route();

    /**
     * Callback
     *
     * @param WP_REST_Request $request - Wp REST request.
     *
     * @return void
     */
    abstract public function callback( WP_REST_Request $request);

    /**
     * Optional, include collection
     *
     * @return void
     */
    public function include_collection()
    {
    }

    /**
     * Manage permision callback
     *
     * @param WP_REST_Request $request - rest request.
     *
     * @return bool
     */
    public function permission_callback( \WP_REST_Request $request )
    {
        // Auth header.
        if ( ! empty( $request->get_header( 'authorization' ) ) ) {

            $auth_data = str_replace( 'Basic ', '', $request->get_header( 'authorization' ) );
            //phpcs:ignore
            $auth_array = explode( ':', base64_decode( $auth_data ) );

            $key    = $auth_array[0];
            $secret = $auth_array[1];

            // Query params.
        } else {
            $request_params = $request->get_query_params();

            $key    = $request_params['consumer_key'];
            $secret = $request_params['consumer_secret'];
        }

        if ( Furgonetka_Admin::get_rest_customer_key() === $key
            && password_verify( $secret, Furgonetka_Admin::get_rest_customer_secret() )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Add info to woocommerce logs
     *
     * @param mixed  $msg      - message.
     * @param string $log_name - log name.
     *
     * @return void
     */
    public function add_to_log( $msg, string $log_name ): void
    {
        $wc_logger = wc_get_logger();
        $wc_logger->info( wc_print_r( $msg, true ), array( 'source' => $log_name ) );
    }
}
