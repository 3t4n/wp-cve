<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation;

use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\Builtin\ImitationCommonEvent;


/**
 * Fake event for free users.
 */
final class ManualTriggerProEvent extends ImitationCommonEvent {

	public function get_name(): string {
		return __( '[PRO] Order Manual Trigger', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return '';
	}

	public function get_group_slug(): string {
		return Groups::ORDER;
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		ob_start();
		include __DIR__ . '/templates/manual-trigger-description.php';
		$description = ob_get_clean();
		return [
			( new ProItemInfoField() )
				->set_name('manual-actions-ad')
				->set_description( $description )
		];
	}

}
