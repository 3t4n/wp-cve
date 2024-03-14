<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       www.redefiningtheweb.com
 * @since      1.0.0
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/includes
 * @author     RedefiningTheWeb <developer@redefiningtheweb.com>
 */
class Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $rtwwdpdl_actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $rtwwdpdl_actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $rtwwdpdl_filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $rtwwdpdl_filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->rtwwdpdl_actions = array();
		$this->rtwwdpdl_filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $rtwwdpdl_hook             The name of the WordPress action that is being registered.
	 * @param    object               $rtwwdpdl_component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $rtwwdpdl_callback         The name of the function definition on the $rtwwdpdl_component.
	 * @param    int                  $rtwwdpdl_priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $rtwwdpdl_accepted_args    Optional. The number of arguments that should be passed to the $rtwwdpdl_callback. Default is 1.
	 */
	public function rtwwdpdl_add_action( $rtwwdpdl_hook, $rtwwdpdl_component, $rtwwdpdl_callback, $rtwwdpdl_priority = 10, $rtwwdpdl_accepted_args = 1 ) {
		$this->rtwwdpdl_actions = $this->rtwwdpdl_add( $this->rtwwdpdl_actions, $rtwwdpdl_hook, $rtwwdpdl_component, $rtwwdpdl_callback, $rtwwdpdl_priority, $rtwwdpdl_accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $rtwwdpdl_hook             The name of the WordPress filter that is being registered.
	 * @param    object               $rtwwdpdl_component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $rtwwdpdl_callback         The name of the function definition on the $rtwwdpdl_component.
	 * @param    int                  $rtwwdpdl_priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $rtwwdpdl_accepted_args    Optional. The number of arguments that should be passed to the $rtwwdpdl_callback. Default is 1
	 */
	public function rtwwdpdl_add_filter( $rtwwdpdl_hook, $rtwwdpdl_component, $rtwwdpdl_callback, $rtwwdpdl_priority = 10, $rtwwdpdl_accepted_args = 1 ) {
		$this->rtwwdpdl_filters = $this->rtwwdpdl_add( $this->rtwwdpdl_filters, $rtwwdpdl_hook, $rtwwdpdl_component, $rtwwdpdl_callback, $rtwwdpdl_priority, $rtwwdpdl_accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $rtwwdpdl_hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $rtwwdpdl_hook             The name of the WordPress filter that is being registered.
	 * @param    object               $rtwwdpdl_component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $rtwwdpdl_callback         The name of the function definition on the $component.
	 * @param    int                  $rtwwdpdl_priority         The priority at which the function should be fired.
	 * @param    int                  $rtwwdpdl_accepted_args    The number of arguments that should be passed to the $rtwwdpdl_callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function rtwwdpdl_add( $rtwwdpdl_hooks, $rtwwdpdl_hook, $rtwwdpdl_component, $rtwwdpdl_callback, $rtwwdpdl_priority, $rtwwdpdl_accepted_args ) {

		$rtwwdpdl_hooks[] = array(
			'hook'          => $rtwwdpdl_hook,
			'component'     => $rtwwdpdl_component,
			'callback'      => $rtwwdpdl_callback,
			'priority'      => $rtwwdpdl_priority,
			'accepted_args' => $rtwwdpdl_accepted_args
		);

		return $rtwwdpdl_hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_run() {

		foreach ( $this->rtwwdpdl_filters as $rtwwdpdl_hook ) {
			add_filter( $rtwwdpdl_hook['hook'], array( $rtwwdpdl_hook['component'], $rtwwdpdl_hook['callback'] ), $rtwwdpdl_hook['priority'], $rtwwdpdl_hook['accepted_args'] );
		}

		foreach ( $this->rtwwdpdl_actions as $rtwwdpdl_hook ) {
			add_action( $rtwwdpdl_hook['hook'], array( $rtwwdpdl_hook['component'], $rtwwdpdl_hook['callback'] ), $rtwwdpdl_hook['priority'], $rtwwdpdl_hook['accepted_args'] );
		}

	}

}
