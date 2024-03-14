<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation;

use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\Builtin\ImitationCommonEvent;

/**
 * Event that never fires and only shows info about PRO upgrades.
 */
final class CartAdEvent extends ImitationCommonEvent {
	public function get_name(): string {
		return __( 'Cart Abandoned', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __(
			'Run automation {x} minute(s) after the cart is considered abandoned.',
			'shopmagic-for-woocommerce'
		);

	}

	public function get_group_slug(): string {
		return Groups::CART;
	}

	public function get_fields(): array {
		ob_start();
		include __DIR__ . '/templates/abandoned-carts-description.php';
		$description = ob_get_clean();
		return [
			( new ProItemInfoField() )
				->set_name('abandoned-cart-ad')
				->set_description( $description )
		];
	}
}
