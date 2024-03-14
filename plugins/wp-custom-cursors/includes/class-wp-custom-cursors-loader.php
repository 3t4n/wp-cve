<?php
/**
 * Loader
 * Load filters and actions
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link       https://hamidrezasepehr.com/
 * @since      2.1.0
 */

/**
 *
 * Wp_Custom_Cursors_I18n
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_Custom_Cursors_Loader {

	/**
	 * Actions
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions
	 */
	protected $actions;

	/**
	 * Filters
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters
	 */
	protected $filters;

	/**
	 * Constructor
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->actions = array();
		$this->filters = array();
	}

	/**
	 * Add actions
	 *
	 * @since    1.0.0
	 * @param    string $hook
	 * @param    object $component
	 * @param    string $callback
	 * @param    int    $priority
	 * @param    int    $accepted_args
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add filters
	 *
	 * @since    1.0.0
	 * @param    string $hook
	 * @param    object $component
	 * @param    string $callback
	 * @param    int    $priority
	 * @param    int    $accepted_args
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add hook
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array  $hooks
	 * @param    string $hook
	 * @param    object $component
	 * @param    string $callback
	 * @param    int    $priority
	 * @param    int    $accepted_args
	 * @return   array
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
	 * Run add filters and add actions
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}
}
