<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation;

use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\Builtin\ImitationCommonEvent;


/**
 * Event that never fires and only shows info about PRO upgrades.
 */
final class MembershipsProEvent extends ImitationCommonEvent {

	public function get_name(): string {
		return __( '[PRO] Membership Status Changed', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return '';
	}

	public function get_group_slug(): string {
		return Groups::MEMBERSHIP;
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		ob_start();
		include __DIR__ . '/templates/membership-event-description.php';
		$description = ob_get_clean();

		return [
			( new ProItemInfoField() )
				->set_name('membership-ad')
				->set_description( $description )
		];
	}
}
