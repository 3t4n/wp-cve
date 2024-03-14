<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use ArrayAccess;
use ShopMagicVendor\Psr\Container\ContainerInterface;
use WPDesk\ShopMagic\Exception\ResourceNotFound;

final class DataLayer implements ContainerInterface, ArrayAccess {
	// implements ArrayAccess for backward compatibility.

	/** @var array<class-string, object> */
	private $storage = [];

	/** @param array<class-string, object> $resources */
	public function __construct( array $resources = [] ) {
		foreach ( $resources as $name => $resource ) {
			$this->set( $name, $resource );
		}
	}

	/**
	 * @template T of object
	 *
	 * @param class-string<T> $class_name
	 *
	 * @return T
	 */
	public function get( $class_name ) {
		if ( $this->has( $class_name ) ) {
			return $this->storage[ $class_name ];
		}

		throw new ResourceNotFound( sprintf( 'No resource has been declared for entry %s.', $class_name ) );
	}

	/**
	 * @param class-string $class_name
	 */
	public function has( $class_name ) {
		return isset( $this->storage[ $class_name ] );
	}

	/**
	 * @template T of object
	 *
	 * @param class-string<T> $class_name
	 * @param T               $resource
	 *
	 * @return void
	 */
	public function set( string $class_name, object $resource ): void {
		if ( ! $resource instanceof $class_name ) {
			throw new \InvalidArgumentException( sprintf( 'Resource must be an instance of %s, %s given.', $class_name, \get_class( $resource ) ) );
		}

		$this->storage[ $class_name ] = $resource;
	}

	/**
	 * @return class-string[]
	 */
	public function get_known_entries(): array {
		return array_keys( $this->storage );
	}

	/** @codeCoverageIgnore */
	public function offsetExists( $offset ): bool {
		return $this->has( $offset );
	}

	/** @codeCoverageIgnore */
	public function offsetGet( $offset ): object {
		return $this->get( $offset );
	}

	/** @codeCoverageIgnore */
	public function offsetSet( $offset, $value ): void {
		$this->set( $offset, $value );
	}

	/** @codeCoverageIgnore */
	public function offsetUnset( $offset ): void {
	}
}
