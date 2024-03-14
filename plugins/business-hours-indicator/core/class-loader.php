<?php

namespace MABEL_BHI_LITE\Core
{

	class Loader
	{

		protected $actions;
		protected $filters;

		public function __construct()
		{
			$this->reset();
		}

		public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
		{
			$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
		}


		public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
		{
			$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
		}

		private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args )
		{
			$hooks[] = [
				'hook'          => $hook,
				'component'     => $component,
				'callback'      => $callback,
				'priority'      => $priority,
				'accepted_args' => $accepted_args
			];
			return $hooks;
		}

		public function run()
		{

			foreach ( $this->filters as $hook ) {
				if(! method_exists($hook['component'],$hook['callback'])){
					throw new \Exception("Can't add filter. Method ". $hook['callback'] . " doesn't exist.");
				}
				add_filter( $hook['hook'], ($hook['component'] === null? $hook['callback'] : [ $hook['component'], $hook['callback'] ] ), $hook['priority'], $hook['accepted_args'] );
			}

			foreach ( $this->actions as $hook ) {
				if(! method_exists($hook['component'],$hook['callback'])){
					throw new \Exception("Can't add action. Method ". $hook['callback'] . "doesn't exist.");
				}
				add_action( $hook['hook'], ($hook['component'] === null? $hook['callback'] : [ $hook['component'], $hook['callback'] ] ), $hook['priority'], $hook['accepted_args'] );
			}

			$this->reset();

		}

		private function reset()
		{

			$this->filters = [];
			$this->actions = [];

		}

	}

}