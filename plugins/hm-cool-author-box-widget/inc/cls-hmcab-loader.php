<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * General action, hooks loader
*/
class HMCABW_Loader {

	protected $hmcabw_actions;

	protected $hmcabw_filters;

	public function __construct() {
		$this->hmcabw_actions = array();
		$this->hmcabw_filters = array();
	}

	public function add_action( $hook, $component, $callback ) {
		$this->hmcabw_actions = $this->add( $this->hmcabw_actions, $hook, $component, $callback );
	}

	public function add_filter( $hook, $component, $callback ) {
		$this->hmcabw_filters = $this->add( $this->hmcabw_filters, $hook, $component, $callback );
	}

	private function add( $hooks, $hook, $component, $callback ) {
		$hooks[] = array( 'hook' => $hook, 'component' => $component, 'callback' => $callback );
		return $hooks;
	}

	public function hmcabw_run() {
		 foreach ( $this->hmcabw_filters as $hook ) {
			 add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		 }

		 foreach ( $this->hmcabw_actions as $hook ) {
			 add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		 }
	}
	
}
?>