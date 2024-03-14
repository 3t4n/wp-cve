<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing\Traits;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;

/**
 * @deprecated 3.0.12 Trait hides from us the fact that our components use parameters. Code
 * deduplication is not worth it.
 */
trait FieldsDataAsProtectedField {

	/** @var FieldValuesBag|null */
	protected $fields_data;

	/**
	 * @deprecated 3.0.12 Use set_parameters instead.
	 */
	public function update_fields_data( ContainerInterface $data ): void {
		$this->set_parameters( FieldValuesBag::from_container( $data ) );
	}

	public function set_parameters( FieldValuesBag $parameters ): void {
		$this->fields_data = $parameters;
	}

	public function get_parameters(): FieldValuesBag {
		return $this->fields_data ?? new FieldValuesBag();
	}
}
