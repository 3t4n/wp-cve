<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller;

interface ControllerResolver {

	/**
	 * @param array|callable $controller
	 *
	 * @return callable
	 */
	public function get_controller( $controller ): callable;

}
