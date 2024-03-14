<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\DI;

use ShopMagicVendor\Psr\Container\ContainerInterface;

trait ContainerAwareTrait {

	/** @var ContainerInterface|null */
	private $container;

	public function set_container( ContainerInterface $container ): void {
		$this->container = $container;
	}

}
