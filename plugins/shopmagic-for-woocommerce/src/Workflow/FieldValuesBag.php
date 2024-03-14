<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Persistence\Adapter\ArrayContainer;
use WPDesk\ShopMagic\Helper\ParameterBag;

/**
 * @final
 */
class FieldValuesBag extends ParameterBag implements ContainerInterface {

	public static function from_container( ContainerInterface $container ): self {
		if ( $container instanceof static ) {
			return $container;
		}

		if ( $container instanceof ArrayContainer ) {
			return new self( $container->get_array() );
		}

		// FIXME: this is not backward compatible as we loose all data in presentation layer.
		return new self();
	}

	/**
	 * Although we might prefer to return null value here, we need to keep compliance with
	 * ContainerInterface and throw exception. This behavior will be changed in 4.0.0 when we
	 * drop ContainerInterface implementation.
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed|string|null
	 * @TODO: return null in 4.0.0
	 */
	public function get( $key, $default = null ) {
		$value = parent::get( $key, $default );

		if ( $this->has( $key ) ) {
			return $value;
		}

		throw new ValueNotFound( "Parameter $key is not defined." );
	}
}
