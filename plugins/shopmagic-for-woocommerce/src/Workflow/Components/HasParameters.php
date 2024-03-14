<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Components;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Forms\FieldProvider;
use ShopMagicVendor\WPDesk\Forms\FieldsDataReceiver;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;

/**
 * Some automation components can have additional configuration through parameters.
 * This interface also supersedes FieldsDataReceiver and FieldProvider interfaces.
 */
interface HasParameters extends FieldsDataReceiver, FieldProvider {

	/**
	 * @param ContainerInterface|FieldValuesBag $data
	 *
	 * @return void
	 * @deprecated 3.0.12
	 */
	public function update_fields_data( ContainerInterface $data ): void;

	/**
	 * @return \ShopMagicVendor\WPDesk\Forms\Field[]
	 */
	public function get_fields(): array;

	/**
	 * Field parameters should be publicly accessible.
	 *
	 * For backward compatibility reasons get_parameters methods MUST return ContainerInterface
	 * instance. However, most of the time we will be dealing with rich FieldValuesBag object.
	 *
	 * @return FieldValuesBag
	 */
	public function get_parameters(): FieldValuesBag;

	public function set_parameters( FieldValuesBag $parameters ): void;

}
