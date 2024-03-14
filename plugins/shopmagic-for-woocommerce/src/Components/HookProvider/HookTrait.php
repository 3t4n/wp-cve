<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\HookProvider;

/**
 * Hooks trait.
 * Allows protected and private methods to be used as hook callbacks in PHP <8.1. Since PHP 8.1
 * you are able to take advantage of first class callable and register private methods in hooks
 * without any workarounds.
 *
 * @author  John P. Bloch
 * @link    https://github.com/johnpbloch/wordpress-dev/blob/master/src/Hooks.php
 */
trait HookTrait {

	/**
	 * We need to keep track of attached hooks, as we wrap object calls in closure, what means,
	 * each time we try to talk to WordPress (i.e. attach and then remove a hook), we actually
	 * use different objects (identified by Closure's `spl_object_hash`).
	 *
	 * To mitigate this discrepancy (non-symmetrical attach/detach) we put each hook into our
	 * internal cache, being able to reproduce actual hook ID attached to WordPress.
	 *
	 * @var array<string, \Closure>
	 */
	protected $filter_map = [];

	/**
	 * Add a WordPress filter.
	 *
	 * @return true
	 */
	protected function add_filter(
		string $hook,
		callable $method,
		int $priority = 10,
		int $arg_count = 1
	): bool {
		return add_filter(
			$hook,
			$this->map_filter( $method, $arg_count ),
			$priority,
			$arg_count
		);
	}

	/**
	 * Add a WordPress action.
	 * This is an alias of add_filter().
	 *
	 * @return true
	 */
	protected function add_action(
		string $hook,
		callable $method,
		int $priority = 10,
		int $arg_count = 1
	): bool {
		return $this->add_filter( $hook, $method, $priority, $arg_count );
	}

	/**
	 * Remove a WordPress filter.
	 *
	 * @param callable $method
	 *
	 * @return bool Whether the function existed before it was removed.
	 */
	protected function remove_filter(
		string $hook,
		callable $method,
		int $priority = 10,
		int $arg_count = 1
	): bool {
		return remove_filter(
			$hook,
			$this->map_filter( $method, $arg_count ),
			$priority
		);
	}

	/**
	 * Remove a WordPress action.
	 * This is an alias of remove_filter().
	 *
	 * @return bool Whether the function is removed.
	 */
	protected function remove_action(
		string $hook,
		callable $method,
		int $priority = 10,
		int $arg_count = 1
	): bool {
		return $this->remove_filter( $hook, $method, $priority, $arg_count );
	}

	/**
	 * Map a filter to a closure that inherits the class' internal scope.
	 * This allows hooks to use protected and private methods.
	 *
	 * @return \Closure|callable The callable actually attached to a WP hook
	 */
	private function map_filter( callable $callable, int $arg_count ): callable {
		if ( is_array( $callable ) && $callable[0] instanceof $this ) {
			[ $_, $method ] = $callable;

			$idx = spl_object_hash( $this ) . $method;

			if ( empty( $this->filter_map[ $idx ] ) ) {
				$this->filter_map[ $idx ] = function () use ( $method, $arg_count ) {
					return $this->{$method}( ...array_slice( func_get_args(), 0, $arg_count ) );
				};
			}

			return $this->filter_map[ $idx ];
		}

		return $callable;
	}
}
