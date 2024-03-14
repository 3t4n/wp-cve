<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Form;

use ShopMagicVendor\WPDesk\Forms\Field;
use ShopMagicVendor\WPDesk\Forms\Field\BasicField;
use WPDesk\ShopMagic\Helper\StableSort;

class FieldsCollection extends BasicField {

	/** @var Field[] */
	private $fields = [];

	public function __construct( array $fields ) {
		$this->register_fields( $fields );
	}

	private function register_fields( array $fields ): void {
		foreach ( $fields as $field ) {
			$this->register_field( $field );
		}
	}

	private function register_field( Field $field ): void {
		$this->fields[ $this->get_field_name( $field ) ] = $field;
	}

	private function get_field_name( Field $field ): string {
		return $this->encode( $field->get_name() );
	}

	private function encode( string $name ): string {
		return str_replace(
			'.',
			'~2',
			$name
		);
	}

	public function get_fields(): array {
		$fields = $this->fields;
		StableSort::uasort( $fields, static function ( Field $a, Field $b ) {
			return $a->get_priority() <=> $b->get_priority();
		} );

		return $fields;
	}

	/** @return Field[] */
	public function get_required_fields(): array {
		return array_filter(
			$this->fields,
			static function ( $field ) {
				return $field->is_required();
			}
		);
	}

	public function get_template_name(): string {
		return 'collection';
	}
}
