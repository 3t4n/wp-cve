<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DI;

use ShopMagicVendor\Psr\Container\ContainerInterface;

interface ContainerAwareInterface {

	public function set_container( ContainerInterface $container );

}
