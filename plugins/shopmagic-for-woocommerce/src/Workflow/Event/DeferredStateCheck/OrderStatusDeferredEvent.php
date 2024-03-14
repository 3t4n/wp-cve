<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\Workflow\Event\Builtin\OrderCommonEvent;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareInterface;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Runner;

/**
 * Decorates OrderCommonEvent by adding deferred status checks.
 */
final class OrderStatusDeferredEvent extends Event implements SupportsDeferredCheck, CustomerAwareInterface {

	/** @var FieldValuesBag */
	protected $fields_data;
	/** @var OrderCommonEvent */
	private $event;
	/** @var string */
	private $status;

	public function __construct( OrderCommonEvent $event, $status ) {
		$this->event  = $event;
		$this->status = $status;
	}

	public function get_id(): string {
		return $this->event->get_id();
	}

	public function set_id( $id ): void {
		$this->event->set_id( $id );
	}

	public function __clone() {
		$this->event       = clone $this->event;
		$this->fields_data = new FieldValuesBag();
	}

	public function set_customer_repository( CustomerRepository $customer_repository ): void {
		$this->event->set_customer_repository( $customer_repository );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->event->setLogger( $logger );
	}

	/**
	 * @return \ShopMagicVendor\WPDesk\Forms\Field[]|\WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck\DefferedCheckField[]
	 */
	public function get_fields(): array {
		return array_merge( $this->event->get_fields(), [ new DefferedCheckField() ] );
	}

	public function is_event_still_valid(): bool {
		if ( ! $this->fields_data->has( DefferedCheckField::NAME ) ) {
			return true;
		}
		if ( $this->fields_data->get( DefferedCheckField::NAME ) === CheckboxField::VALUE_FALSE ) {
			return true;
		}
		$provided_data = $this->event->get_provided_data();

		return $provided_data[ \WC_Order::class ]->has_status( $this->status );
	}

	public function get_provided_data(): DataLayer {
		return $this->event->get_provided_data();
	}

	/**
	 * @return string[]
	 */
	public function get_provided_data_domains(): array {
		return $this->event->get_provided_data_domains();
	}

	public function get_name(): string {
		return $this->event->get_name();
	}

	public function get_group_slug(): string {
		return $this->event->get_group_slug();
	}

	public function get_description(): string {
		return $this->event->get_description();
	}

	public function initialize(): void {
		$this->event->initialize();
	}

	public function set_data_layer( DataLayer $resources ): void {
		$this->event->set_data_layer( $resources );
	}

	public function set_runner( Runner $runner ): void {
		$this->event->set_runner( $runner );
	}

	/**
	 * @return array{order_id: int|string}
	 */
	public function jsonSerialize(): array {
		return $this->event->jsonSerialize();
	}

	public function set_from_json( array $serialized_json ): void {
		$this->event->set_from_json( $serialized_json );
	}

	public function set_parameters( FieldValuesBag $parameters ): void {
		$this->fields_data = $parameters;
		$this->event->set_parameters( $parameters );
	}

	public function get_parameters(): FieldValuesBag {
		return $this->event->get_parameters();
	}
}
