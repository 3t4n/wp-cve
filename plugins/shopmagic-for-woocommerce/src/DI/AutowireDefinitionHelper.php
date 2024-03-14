<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\DI;

use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\DI\Definition\Definition;
use ShopMagicVendor\DI\Definition\ObjectDefinition\MethodInjection;

use function ShopMagicVendor\DI\get;

class AutowireDefinitionHelper extends \ShopMagicVendor\DI\Definition\Helper\AutowireDefinitionHelper {

	public function getDefinition( string $entryName ): Definition {
		$definition = parent::getDefinition( $entryName );
		$class_name = $definition->getClassName();
		if ( is_a( $class_name, LoggerAwareInterface::class, true ) ) {
			$definition->addMethodInjection(
				new MethodInjection( 'setLogger', [ get( LoggerInterface::class ) ] )
			);
		}

		return $definition;
	}

}
