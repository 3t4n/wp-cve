<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Customer;

use WPDesk\ShopMagic\Workflow\Event\Builtin\OptCommonEvent;


final class CustomerOptedOut extends OptCommonEvent {
	public function get_id(): string {
		return 'shopmagic_customer_optout_event';
	}

	public function get_name(): string {
		return __( 'Customer Opted Out', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when a customer unsubscribes from a selected list.', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		$this->add_action(
			'shopmagic/core/event/manual/optout',
			[ $this, 'process_event' ],
			10,
			2
		);
	}


}
