<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Persistence;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Persistence\ElementNotExistsException;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;

class WrappingPersistentContainer implements PersistentContainer {

	/** @var PersistentContainer */
	private $main_container;

	/** @var ContainerInterface */
	private $wrapper_container;

	public function __construct(
		PersistentContainer $container
	) {
		$this->main_container = $container;
	}

	/**
	 * Wrapper container serves as readonly container.
	 *
	 * @param ContainerInterface $other_container
	 *
	 * @return void
	 */
	public function wrapContainer( ContainerInterface $other_container ) {
		$this->wrapper_container = $other_container;
	}

	/**
	 * @inheritDoc
	 */
	public function get_fallback( string $id, $fallback = null ) {
		try {
			return $this->get( $id );
		} catch ( ElementNotExistsException $e ) {
			return $fallback;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function set( string $id, $value ) {
		$this->main_container->set( $id, $value );
	}

	/**
	 * @inheritDoc
	 */
	public function delete( string $id ) {
		$this->main_container->delete( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function has( $id ): bool {
		return $this->main_container->has( $id ) || $this->wrapper_container->has( $id );
	}

	public function get( $id ) {
		if ( $this->main_container->has( $id ) ) {
			return $this->main_container->get( $id );
		}

		if ( $this->wrapper_container->has( $id ) ) {
			return $this->wrapper_container->get( $id );
		}

		throw new ElementNotExistsException( sprintf( 'Element %s not found', $id ) );
	}
}
