<?php
declare( strict_types=1 );


namespace WPDesk\ShopMagic\Workflow;

class InvalidConfiguration extends \RuntimeException implements \WPDesk\ShopMagic\Exception\ShopMagicException {

	public static function missing_event( int $automation_id ): self {
		return new self(
			sprintf(
				__(
					'Automation #%d is missing an event! Without this it is impossible to run any actions.',
					'shopmagic-for-woocommerce'
				),
				$automation_id
			)
		);
	}

	public static function missing_actions( int $automation_id ): self {
		return new self(
			sprintf(
				esc_html__(
					'Automation #%d has no actions! Without those the automation cannot run.',
					'shopmagic-for-woocommerce'
				),
				$automation_id
			)
		);
	}

}
