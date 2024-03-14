<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use ShopMagicVendor\WPDesk\Forms\Field\InputTextField;
use WPDesk\ShopMagic\DataSharing\DataReceiver;
use WPDesk\ShopMagic\DataSharing\Traits\StandardWooCommerceDataProviderAccessors;
use WPDesk\ShopMagic\Workflow\Components\ComponentIdTrait;
use WPDesk\ShopMagic\Workflow\Components\HasParameters;
use WPDesk\ShopMagic\Workflow\Components\NamedComponent;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholderProcessor;

/**
 * Action is one of the major components in ShopMagic. When an event occurs the actions are
 * executed. Actions are retrieved using Prototype Pattern.
 */
abstract class Action implements
	ActionLogic,
	DataReceiver,
	HasParameters,
	LoggerAwareInterface,
	NamedComponent {
	use ComponentIdTrait;
	use LoggerAwareTrait;
	use StandardWooCommerceDataProviderAccessors;

	/** @var FieldValuesBag */
	protected $fields_data;

	/** @var PlaceholderProcessor */
	protected $placeholder_processor;

	public function get_parameters(): FieldValuesBag {
		/**
		 * Hack for backward-compatible decorators.
		 * @see shopmagic-delayed-actions
		 */
		if ( property_exists( $this, 'action' ) ) {
			return \Closure::bind(function () {
				return $this->action->get_parameters();
			}, $this, $this)();
		}

		return $this->fields_data;
	}

	public function set_parameters( FieldValuesBag $parameters ): void {
		$this->fields_data = $parameters;
	}

	/**
	 * @param ContainerInterface $data
	 *
	 * @return void
	 * @deprecated 3.0.12 Use set_parameters()
	 */
	public function update_fields_data( ContainerInterface $data ): void {
		$this->set_parameters( FieldValuesBag::from_container( $data ) );
	}

	/**
	 * Action has fields that can be shown in admin panel. Here are the values of these fields.
	 *
	 * @deprecated 3.0.12 Use get_parameters()
	 */
	public function get_fields_data(): ContainerInterface {
		return $this->get_parameters();
	}

	public function get_description(): string {
		return '';
	}

	/**
	 * Processor is required to process placeholders that can be used in action fields.
	 */
	public function set_placeholder_processor( PlaceholderProcessor $processor ): void {
		$this->placeholder_processor = $processor;
	}

	public function __clone() {
		$this->fields_data = new FieldValuesBag();
	}

	public function get_fields(): array {
		return [
			( new InputTextField() )
				->set_name( '_action_title' )
				->set_label( esc_html__( 'Description', 'shopmagic-for-woocommerce' ) )
				->set_priority( 1 ),
		];
	}

	/**
	 * @deprecated Actions are not really serialized - any of them isn't actually used when
	 *             deserializing.
	 * @codeCoverageIgnore
	 */
	public function jsonSerialize(): array {
		return [];
	}

	/**
	 * @var DataLayer|null
	 * @deprecated 3.0 Renamed to $resources
	 */
	protected $provided_data;

	/**
	 * @var DataLayer|null
	 * @deprecated 3.0.12 Rely on argument passed to execute() method.
	 * @see self::execute()
	 */
	protected $resources;

	/**
	 * @param DataLayer $resources
	 *
	 * @return void
	 * @deprecated 3.0.0 Action shouldn't rely on class instance of DataLayer -- this value is
	 * passed to execute() method.
	 * @see self::execute()
	 */
	public function set_provided_data( DataLayer $resources ): void {
		$this->provided_data = $resources;
		$this->resources     = $resources;
	}

}
