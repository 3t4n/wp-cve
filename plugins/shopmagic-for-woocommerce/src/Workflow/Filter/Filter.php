<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Filter;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\DataSharing\Traits\DataReceiverAsProtectedField;
use WPDesk\ShopMagic\Workflow\Components\ComponentIdTrait;
use WPDesk\ShopMagic\Workflow\Components\GroupableNamedComponent;
use WPDesk\ShopMagic\Workflow\Components\HasParameters;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;

abstract class Filter implements
	FilterLogic,
	GroupableNamedComponent,
	HasParameters,
	LoggerAwareInterface {
	use DataReceiverAsProtectedField;
	use LoggerAwareTrait;
	use ComponentIdTrait;

	/** @var FieldValuesBag */
	protected $fields_data;

	public function __clone() {
		$this->fields_data = new FieldValuesBag();
	}

	/**
	 * @deprecated 3.0.12 Use set_parameters()
	 */
	public function update_fields_data( ContainerInterface $data ): void {
		$this->set_parameters( FieldValuesBag::from_container( $data ) );
	}

	public function set_parameters( FieldValuesBag $parameters ): void {
		$this->fields_data = $parameters;
	}

	public function get_parameters(): FieldValuesBag {
		return $this->fields_data;
	}

	public function get_description(): string {
		return '';
	}

}
