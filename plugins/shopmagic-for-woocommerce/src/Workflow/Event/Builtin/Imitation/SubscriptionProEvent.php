<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation;

use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\Builtin\ImitationCommonEvent;


/**
 * Event that never fires and only shows info about PRO upgrades.
 */
final class SubscriptionProEvent extends ImitationCommonEvent {
	public function get_name(): string {
		return __( '[PRO] Subscription Status Changed', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return '';
	}

	public function get_group_slug(): string {
		return Groups::SUBSCRIPTION;
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		ob_start();
		include __DIR__ . '/templates/subscription-event-description.php';
		$description = ob_get_clean();

		return [
			( new ProItemInfoField() )
				->set_name('subscription-ad')
				->set_description( $description )
		];
	}
}
