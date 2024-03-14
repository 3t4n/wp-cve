<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\DataSharing\DataProvider;
use WPDesk\ShopMagic\Exception\ReferenceNoLongerAvailableException;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Components\ComponentIdTrait;
use WPDesk\ShopMagic\Workflow\Components\GroupableNamedComponent;
use WPDesk\ShopMagic\Workflow\Components\HasParameters;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Runner;

abstract class Event implements
	EventInterface,
	TerminableInterface,
	GroupableNamedComponent,
	DataProvider,
	HasParameters,
	LoggerAwareInterface,
	\JsonSerializable {
	use ComponentIdTrait;
	use LoggerAwareTrait;

	/** @var DataLayer */
	protected $resources;

	/** @var FieldValuesBag */
	protected $fields_data;

	/** @var Runner|null */
	private $runner;

	/**
	 * Reverse jsonSerialize. What has been serialized should be possible to unserialize.
	 *
	 * @throws ReferenceNoLongerAvailableException When object reference from json no longer points
	 *                                             to a real object.
	 * @see        self::jsonSerialize()
	 * @deprecated 3.0 Use ::denormalize() for better semantics.
	 */
	abstract public function set_from_json( array $serialized_json ): void;

	//abstract public function denormalize( array $data ): void;
	//
	//abstract public function normalize(): array;

	/**
	 * @deprecated 3.0 Event should be normalized explicitly when needed.
	 *             For this purpose use ::normalize()
	 * @note       Another reason to deprecate implicit solution. When we experience
	 *       some caught Exception and want to log it, logger may silently call
	 *       `json_encode` on class within our exception if we added stack trace to context.
	 *       This may result in another fatal error, unhandled this time.
	 */
	abstract public function jsonSerialize(): array;

	public function get_description(): string {
		return esc_html__( 'No description provided for this event.', 'shopmagic-for-woocommerce' );
	}

	/**
	 * Event can be configured with fields.
	 *
	 * @return \ShopMagicVendor\WPDesk\Forms\Field[]
	 */
	public function get_fields(): array {
		return [];
	}

	/**
	 * Each event provides some data through DataLayer, but those have to be registered before we
	 * will be able to access them. Events are filled with Automation::class resource by default
	 * during runtime. If Events exposes more domains to be accessible, overload the method.
	 *
	 * @return string[]
	 * @see DataLayer
	 */
	public function get_provided_data_domains(): array {
		return [ Automation::class ];
	}

	/**
	 * As Events are accessed through Proxy Pattern we have to release non-static resources while
	 * cloning an object.
	 *
	 * @return void
	 */
	public function __clone() {
		$this->resources   = new DataLayer();
		$this->fields_data = new FieldValuesBag();
		$this->runner      = null;
	}

	final public function trigger_automation(): void {
		$this->logger->debug(
			'Automation triggered with event {class}',
			[ 'class' => static::class ]
		);
		$this->runner->run( $this->get_provided_data() );
	}

	public function shutdown(): void {
	}

	public function get_provided_data(): DataLayer {
		return $this->resources;
	}

	public function set_runner( Runner $runner ): void {
		$this->runner = $runner;
	}

	public function set_data_layer( DataLayer $resources ): void {
		$this->resources = $resources;
	}

	public function set_parameters( FieldValuesBag $parameters ): void {
		$this->fields_data = $parameters;
	}

	public function get_parameters(): FieldValuesBag {
		return $this->fields_data;
	}

	/**
	 * @deprecated 3.0.12 Use set_parameters instead.
	 */
	public function update_fields_data( ContainerInterface $data ): void {
		$this->set_parameters( FieldValuesBag::from_container( $data ) );
	}

}
