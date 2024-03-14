<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Order;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Helper\WooCommerceStatusHelper;
use WPDesk\ShopMagic\Workflow\Event\Builtin\OrderCommonEvent;
use WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck\DefferedCheckField;
use WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck\SupportsDeferredCheck;

final class OrderStatusChanged extends OrderCommonEvent implements SupportsDeferredCheck {

	/** @var string */
	private const PARAM_STATUS_FROM = 'order_status_from';

	/** @var string */
	private const PARAM_STATUS_TO = 'order_status_to';

	public function get_id(): string {
		return 'shopmagic_order_status_changed';
	}

	public function get_name(): string {
		return __( 'Order Status Changed', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when an order status changes.', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		$options = array_merge(
			[ '' => __( 'Any status', 'shopmagic-for-woocommerce' ) ],
			$this->get_order_statuses()
		);
		return [
			( new SelectField() )
				->set_label( __( 'Status changes from', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PARAM_STATUS_FROM )
				->set_placeholder( __( 'Any status', 'shopmagic-for-woocommerce' ) )
				->set_options( $options ),
			( new SelectField() )
				->set_label( __( 'Status changes to', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PARAM_STATUS_TO )
				->set_placeholder( __( 'Any status', 'shopmagic-for-woocommerce' ) )
				->set_options( $options ),
			new DefferedCheckField(),
		];
	}

	private function get_order_statuses(): array {
		return wc_get_order_statuses();
	}

	public function initialize(): void {
		add_action(
			'woocommerce_order_status_changed',
			function ( int $order_id, string $old_status, string $new_status, \WC_Order $order ) {
				$this->status_changed( $order_id, $old_status, $new_status, $order );
			},
			10,
			4
		);
	}

	/**
	 * Check valid statuses and run actions.
	 */
	public function status_changed( int $order_id, string $old_status, string $new_status, \WC_Order $order ): void {
		$this->resources->set( \WC_Order::class, $order );
		$this->resources->set( Customer::class, $this->get_customer( $order ) );

		$order_status_from = $this->fields_data->get( self::PARAM_STATUS_FROM );
		$order_status_to   = $this->fields_data->get( self::PARAM_STATUS_TO );
		if ( ! empty( $order_status_from ) && ! WooCommerceStatusHelper::validate_status_field( $order_status_from, $old_status ) ) {
			return;
		}
		if ( ! empty( $order_status_to ) && ! WooCommerceStatusHelper::validate_status_field( $order_status_to, $new_status ) ) {
			return;
		}
		$this->trigger_automation();
	}

	public function is_event_still_valid(): bool {
		if ( ! $this->fields_data->has( DefferedCheckField::NAME ) ) {
			return true;
		}
		if ( $this->fields_data->get( DefferedCheckField::NAME ) === CheckboxField::VALUE_FALSE ) {
			return true;
		}
		$required_status = $this->fields_data->get( self::PARAM_STATUS_TO );

		return empty( $required_status ) || WooCommerceStatusHelper::validate_status_field( $required_status, $this->get_order()->get_status() );
	}
}
