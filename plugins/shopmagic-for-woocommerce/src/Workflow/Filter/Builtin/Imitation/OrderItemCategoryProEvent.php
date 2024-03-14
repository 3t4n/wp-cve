<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin\Imitation;

use WPDesk\ShopMagic\Admin\Form\Fields\ProItemInfoField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\ImitationCommonFilter;


/**
 * Fake filter for pro encouragement.
 */
final class OrderItemCategoryProEvent extends ImitationCommonFilter {

	/**
	 * @return string[]
	 */
	public function get_required_data_domains(): array {
		return [ \WC_Order::class ];
	}

	public function get_group_slug(): string {
		return Groups::ORDER;
	}

	public function get_name(): string {
		return __( '[PRO] Order - Item Categories', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		ob_start();
		include __DIR__ . '/templates/order-item-description.php';
		$description = ob_get_clean();

		return [
			( new ProItemInfoField() )
				->set_description( $description )
				->add_class( 'notice' )
				->add_class( 'notice-info' ),
		];
	}

}
