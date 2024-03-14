<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Exception;

class CannotProvideItemException extends \RuntimeException implements ShopMagicException {
	public static function create_for_persistence_gateway( string $method ): \WPDesk\ShopMagic\Exception\CannotProvideItemException {
		return new self( sprintf( 'Method %s cannot provide item.', $method ) );
	}
}
