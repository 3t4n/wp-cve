<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Loader
{
    /**
     * The array of actions registered with WordPress.
     *
     * @since  1.0.0
     * @access protected
     * @var    array    $actions    The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since  1.0.0
     * @access protected
     * @var    array    $filters    The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->actions = array(
            array(
                'hook'          => 'woocommerce_after_add_to_cart_form',
                'component'     => 'Furgonetka_Public',
                'callback'      => 'add_checkout_button_product',
                'priority'      => 20,
                'accepted_args' => 1
            ),
            array(
                'hook'          => 'woocommerce_widget_shopping_cart_before_buttons',
                'component'     => 'Furgonetka_Public',
                'callback'      => 'add_checkout_button_shopping_minicart_widget',
                'priority'      => 10,
                'accepted_args' => 1
            ),
            array(
                'hook'          => 'woocommerce_widget_order',
                'component'     => 'Furgonetka_Public',
                'callback'      => 'add_checkout_button_order',
                'priority'      => 10,
                'accepted_args' => 1
            ),
            array(
                'hook'          => 'woocommerce_proceed_to_checkout',
                'component'     => 'Furgonetka_Public',
                'callback'      => 'add_checkout_button_shopping_cart_widget',
                'priority'      => 10,
                'accepted_args' => 1
            ),
            array(
                'hook'          => 'wp_footer',
                'component'     => 'Furgonetka_Public',
                'callback'      => 'add_hidden_container_for_cart_widget',
                'priority'      => 10,
                'accepted_args' => 1
            )
        );

        $this->filters = array(
            array(
                'hook'          => 'woocommerce_rest_prepare_shop_order_object',
                'component'     => 'Furgonetka_Order',
                'callback'      => 'addLinkToResponse',
                'priority'      => 10,
                'accepted_args' => 1
            ),
            array(
                'hook'          => 'woocommerce_rest_pre_insert_shop_order_object',
                'component'     => 'Furgonetka_Order',
                'callback'      => 'rest_pre_insert_shop_order_object',
                'priority'      => 10,
                'accepted_args' => 3
            )
        );
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @since 1.0.0
     * @param string $hook          The name of the WordPress action that is being registered.
     * @param object $component     A reference to the instance of the object on which the action is defined.
     * @param string $callback      The name of the function definition on the $component.
     * @param int    $priority      Optional. The priority at which the function should be fired. Default is 10.
     * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
    {
        $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @since 1.0.0
     * @param string $hook          The name of the WordPress filter that is being registered.
     * @param object $component     A reference to the instance of the object on which the filter is defined.
     * @param string $callback      The name of the function definition on the $component.
     * @param int    $priority      Optional. The priority at which the function should be fired. Default is 10.
     * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
     */
    public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
    {
        $this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * Collection.
     *
     * @since  1.0.0
     * @access private
     * @param  array  $hooks         The collection of hooks that is being registered (that is, actions or filters).
     * @param  string $hook          The name of the WordPress filter that is being registered.
     * @param  object $component     A reference to the instance of the object on which the filter is defined.
     * @param  string $callback      The name of the function definition on the $component.
     * @param  int    $priority      The priority at which the function should be fired.
     * @param  int    $accepted_args The number of arguments that should be passed to the $callback.
     * @return array                 The collection of actions and filters registered with WordPress.
     */
    private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args )
    {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );

        return $hooks;
    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {
        foreach ( $this->filters as $hook ) {
            add_filter(
                $hook['hook'],
                array( $hook['component'], $hook['callback'] ),
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ( $this->actions as $hook ) {
            add_action(
                $hook['hook'],
                array( $hook['component'], $hook['callback'] ),
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
