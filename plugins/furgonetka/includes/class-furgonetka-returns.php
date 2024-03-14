<?php

/**
 * Manage registering custom return route
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes
 */

/**
 * Class Furgonetka_Returns - Manage registering custom return route
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Returns
{
    /**
     * Model data
     *
     * @var array
     */
    private $model_data;

    /**
     * Model
     *
     * @var Furgonetka_Returns_Model
     */
    private $model;

    /**
     * Register model
     */
    public function __construct()
    {
        require_once FURGONETKA_REST_DIR . 'models/class-furgonetka-returns-model.php';
        $this->include_model();
        $this->model_data = $this->model->get_rewrite_options();
    }

    /**
     * Include Model
     *
     * @return void
     */
    public function include_model(): void
    {
        require_once FURGONETKA_REST_DIR . 'models/class-furgonetka-returns-model.php';
        $this->model = new Furgonetka_Returns_Model();
    }

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        if ( 1 === $this->model_data['active'] ) {
            add_action( 'init', array( $this, 'add_rewrite_route' ) );
            add_action( 'parse_request', array( $this, 'redirect' ) );
        }
    }

    /**
     * Redirect to url assign to option
     *
     * @return void
     */
    public function redirect(): void
    {
        global $wp;
        if ( get_option( Furgonetka_Returns_Model::get_route_option_name() ) === $wp->request ) {
            header( 'Location: ' . get_option( Furgonetka_Returns_Model::get_target_option_name() ) );
            exit();
        }
    }

    /**
     * Check if route exist
     *
     * @param  string $route_name - route name.
     * @return boolean
     */
    public function check_if_route_exists( $route_name ): bool
    {
        $rules = get_option( 'rewrite_rules' );
        $regex = "\b{$route_name}\b";

        if ( ! isset( $rules[ $regex ] ) && ! is_page( $route_name ) ) {
            return false;
        }
        return true;
    }

    /**
     * Add rewriite rulez, flush rewrite rules
     *
     * @return void
     */
    public function add_rewrite_route()
    {
        add_rewrite_rule( '\b' . get_option( Furgonetka_Returns_Model::get_route_option_name() ) . '\b', 'index.php', 'top' );
        flush_rewrite_rules();
    }
}
