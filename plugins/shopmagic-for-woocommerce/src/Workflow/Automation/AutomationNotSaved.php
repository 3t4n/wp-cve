<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

class AutomationNotSaved extends \RuntimeException implements \WPDesk\ShopMagic\Exception\ShopMagicException {

	public static function insufficient_permission(): self {
		return new AutomationNotSaved( 'Automation could not be saved due to insufficient user permission.' );
	}

}
