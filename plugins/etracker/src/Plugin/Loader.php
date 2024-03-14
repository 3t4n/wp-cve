<?php
/**
 * Register all actions and filters for the plugin
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Plugin;

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Loader {
	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var array $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var array $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * The array of shortcodes registered with WordPress.
	 *
	 * @since    2.2.0
	 *
	 * @access   protected
	 *
	 * @var array $shortcodes    The shortcodes registered with WordPress.
	 */
	protected $shortcodes;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->actions    = array();
		$this->filters    = array();
		$this->shortcodes = array();
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @param string $hook          The name of the WordPress action that is being registered.
	 * @param object $component     A reference to the instance of the object on which the action is defined.
	 * @param string $callback      The name of the function definition on the $component.
	 * @param int    $priority      Optional. The priority at which the function should be fired. Default is 10.
	 * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @param string $hook          The name of the WordPress filter that is being registered.
	 * @param object $component     A reference to the instance of the object on which the filter is defined.
	 * @param string $callback      The name of the function definition on the $component.
	 * @param int    $priority      Optional. The priority at which the function should be fired. Default is 10.
	 * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new shortcode to the collection to be registered with WordPress.
	 *
	 * @since    2.2.0
	 *
	 * @param string $hook          The name of the WordPress filter that is being registered.
	 * @param object $component     A reference to the instance of the object on which the filter is defined.
	 * @param string $callback      The name of the function definition on the $component.
	 */
	public function add_shortcode( $hook, $component, $callback ) {
		$this->shortcodes = $this->add( $this->shortcodes, $hook, $component, $callback, 10, 0 );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 *
	 * @param array  $hooks         The collection of hooks that is being registered (that is, actions or filters).
	 * @param string $hook          The name of the WordPress filter that is being registered.
	 * @param object $component     A reference to the instance of the object on which the filter is defined.
	 * @param string $callback      The name of the function definition on the $component.
	 * @param int    $priority      The priority at which the function should be fired.
	 * @param int    $accepted_args The number of arguments that should be passed to the $callback.
	 *
	 * @return array The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
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
	 * @since    1.0.0
	 */
	public function run() {
		foreach ( $this->filters as $filter ) {
			add_filter( $filter['hook'], array( $filter['component'], $filter['callback'] ), $filter['priority'], $filter['accepted_args'] );
		}

		foreach ( $this->actions as $action ) {
			add_action( $action['hook'], array( $action['component'], $action['callback'] ), $action['priority'], $action['accepted_args'] );
		}

		foreach ( $this->shortcodes as $shortcode ) {
			add_shortcode( $shortcode['hook'], array( $shortcode['component'], $shortcode['callback'] ) );
		}
	}
}
