<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DI;

class NotFoundException extends \RuntimeException implements \ShopMagicVendor\Psr\Container\NotFoundExceptionInterface {
	protected static $template = 'There is no entry found in the container for the identifier "{id}".';

	public static function fromPrevious( $id, \Exception $prev = null ) {
		$message = \strtr( static::$template,
			[
				'{id}'    => \is_string( $id ) || \method_exists( $id, '__toString' ) ? $id : '?',
				'{error}' => $prev ? \get_class( $prev ) : 'error',
			] );
		if ( $prev ) {
			$message .= ' Message: ' . $prev->getMessage();
		}

		return new self( $message, 0, $prev );
	}
}
