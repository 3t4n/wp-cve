<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules;

use ShopMagicVendor\DI\ContainerBuilder;
use WPDesk\ShopMagic\DI\ContainerAwareInterface;

interface Module extends ContainerAwareInterface {

	/**
	 * Prepare module definitions, including known dependencies and services exposed in
	 * dependency injection container.
	 * Module resembles bundle in Symfony.
	 *
	 * @param ContainerBuilder $builder
	 *
	 * @return void
	 */
	public function build( ContainerBuilder $builder ): void;

	/**
	 * Initialize module with its side effect actions like registering hooks or attaching to
	 * other components.
	 *
	 * @return void
	 */
	public function initialize(): void;

	public function get_name(): string;

}
