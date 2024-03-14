<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Customer;

use WPDesk\ShopMagic\Workflow\Event\Builtin\OptCommonEvent;


final class CustomerOptedIn extends OptCommonEvent {
	public function get_id(): string {
		return 'shopmagic_customer_optin_event';
	}

	public function get_name(): string {
		return __( 'Customer Opted In', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when a customer subscribes to a selected list.', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		$this->add_action(
			'shopmagic/core/event/manual/optin',
			[ $this, 'process_event' ],
			10,
			2
		);
	}

}
