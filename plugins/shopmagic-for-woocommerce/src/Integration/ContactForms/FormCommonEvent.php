<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\ContactForms;

use Throwable;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerProvider;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;
use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareInterface;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareTrait;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Event\Event;

abstract class FormCommonEvent extends Event implements CustomerAwareInterface {
	use CustomerAwareTrait;

	/** @var string */
	public const FIELD_ID_FORM = 'form';

	/** @var CustomerProvider */
	protected $customer_provider;

	public function __construct( CustomerProvider $customer_provider ) {
		$this->customer_provider = $customer_provider;
	}

	final public function get_group_slug(): string {
		return Groups::FORM;
	}

	/** @return string[] */
	public function get_provided_data_domains(): array {
		return array_merge(
			parent::get_provided_data_domains(),
			[
				FormEntry::class,
				Customer::class,
			]
		);
	}

	public function get_provided_data(): DataLayer {
		try {
			$this->resources->set( Customer::class, $this->get_customer() );
		} catch ( Throwable $throwable ) {
		}

		return $this->resources;
	}

	final protected function get_customer(): Customer {
		if ( $this->resources->has( Customer::class ) ) {
			return $this->resources->get( Customer::class );
		}
		$this->resources->set( Customer::class, $this->customer_provider->get_customer() );

		return $this->resources->get( Customer::class );
	}

	/** @return \ShopMagicVendor\WPDesk\Forms\Field[] */
	final public function get_fields(): array {
		$options = $this->get_forms_as_options();
		$select_field = ( new SelectField() )
			->set_name( self::FIELD_ID_FORM )
			->set_label( __( 'Contact Form:', 'shopmagic-for-woocommerce' ) )
			->set_required()
			->set_options( $options );

		if ( count( $options ) === 1 ) {
			$select_field->set_default_value( (string) array_keys( $options )[0] );
		}

		return [ $select_field ];
	}

	/** @return string[] */
	abstract protected function get_forms_as_options(): array;

	/** @return array{form_data: FormEntry, customer_id?: string} */
	public function jsonSerialize(): array {
		$data = [ 'form_data' => $this->resources->get( FormEntry::class ) ];

		try {
			$data['customer_id'] = $this->get_customer()->get_id();
		} catch ( CannotProvideCustomerException $e ) {
		}

		return $data;
	}

	public function set_from_json( array $serialized_json ): void {
		if ( isset( $serialized_json['customer_id'] ) ) {
			$this->resources->set(
				Customer::class,
				$this->customer_repository->find( $serialized_json['customer_id'] )
			);
		}
	}

}
