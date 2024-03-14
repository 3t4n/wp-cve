<?php
/**
 * Base_REST_Server class file.
 *
 * @package WooCommerce Utils
 */

namespace Oblak\WooCommerce\API;

/**
 * Base REST Server for WooCommerce plugins.
 *
 * Hooks in the `woocommerce_rest_api_get_rest_namespaces` filter to add the controllers to the rest namespaces.
 * Class is self contained and does the heavy lifting.
 *
 * You can define two types of methods:
 * - `get_{v}N_controllers` - returns an array of controllers for the versioned namespace `vN` - Registered as `namespace/vN`
 * - `get_{subspace}_controllers` - returns an array of controllers for the unversioned subspace `subspace` - Registered as `namespace-subspace`
 */
abstract class Base_REST_Server {

    /**
     * Class constructor
     */
    public function __construct() {
        add_filter( 'woocommerce_rest_api_get_rest_namespaces', array( $this, 'modify_rest_namespaces' ), 99, 1 );
    }

    /**
     * Get the base namespace for the server.
     *
     * @return string
     */
    abstract protected function get_base_namespace(): string;

    /**
     * Adds the controllers to the rest namespaces
     *
     * @param  array $namespaces The rest namespaces.
     * @return array             Modified rest namespaces
     */
    public function modify_rest_namespaces( array $namespaces ): array {
        return array_merge(
            $namespaces,
            array_filter(
                $this->collect_controllers(),
                array( $this, 'validate_controllers' ),
            )
        );
    }

    /**
     * Collects the controllers
     */
    final protected function collect_controllers() {
        return array_map(
            fn( $method ) => $this->{"$method"}(),
            $this->get_controller_methods()
        );
    }

    /**
     * Get the controller methods
     *
     * @return array
     */
    final protected function get_controller_methods(): array {
        $methods = array_filter( array_map( array( $this, 'parse_controller_method' ), ( new \ReflectionClass( $this ) )->getMethods() ) );
        return array_combine(
            array_map( array( $this, 'parse_method_namespace' ), $methods ),
            $methods,
        );
    }

    /**
     * Parses the controller method name
     *
     * @param  \ReflectionMethod $method The method to parse.
     * @return string|null               The parsed method name or null if the method name is not valid
     */
    final protected function parse_controller_method( \ReflectionMethod $method ): string|null {
        return preg_match( '/^get_(.*)_(controllers|routes)$/', $method->getName(), $matches ) > 0 ? $method->getName() : null;
    }

    /**
     * Parses the method namespace.
     *
     * Versioned namespaces are separated by a slash, while unversioned namespaces are separated by a dash.
     *
     * @param  string $method_name The method name to parse.
     * @return string              The parsed namespace.
     */
    final protected function parse_method_namespace( string $method_name ): string {
        preg_match( '/^get_(.*)_(controllers|routes)$/', $method_name, $subspace );
        $subspace  = $subspace[1];
        $connector = preg_match( '/^v(\d+)$/', $subspace, $version ) > 0 ? '/' : '-';

        return "{$this->get_base_namespace()}{$connector}{$subspace}";
    }

    /**
     * Validate the controller array
     *
     * @param  array<int, class-string> $controllers The controllers to validate.
     * @return array<int, class-string>              The validated controllers
     */
    final protected function validate_controllers( array $controllers ): array {
        return array_filter(
            $controllers,
            fn( $c ) => class_exists( $c ) && is_subclass_of( $c, \WC_REST_Controller::class ),
        );
    }
}
