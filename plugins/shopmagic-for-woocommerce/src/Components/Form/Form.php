<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Form;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\WPDesk\Forms\Field;
use WPDesk\ShopMagic\Helper\StableSort;

class Form {
	/** @var \ArrayObject<string, mixed> */
	private $updated_data;
	/** @var Field[] */
	private $fields;

	public function __construct( array $fields ) {
		$this->fields = $fields;
		$this->updated_data = new \ArrayObject();
	}

	public function is_valid(): bool {
		foreach ( $this->fields as $field ) {
			$field_value = $this->updated_data[ $field->get_name() ] ?? $field->get_default_value();
			$field_validator = $field->get_validator();
			if ( ! $field_validator->is_valid( $field_value ) ) {
				return \false;
			}
		}

		return \true;
	}

	public function set_data( ContainerInterface $data ): void {
		foreach ( $this->fields as $field ) {
			$data_key = $field->get_name();
			if ( $data->has( $data_key ) ) {
				$this->updated_data[ $data_key ] = $field->get_sanitizer()->sanitize( $data->get( $data_key ) );
			}
		}
	}

	public function get_data(): array {
		if ( empty( $this->fields ) ) {
			return [];
		}
		$data = $this->updated_data->getArrayCopy();
		foreach ( $this->fields as $field ) {
			$data_key = $field->get_name();
			if ( ! isset( $data[ $data_key ] ) ) {
				$data[ $data_key ] = $field->get_default_value();
			}
		}

		return $data;
	}

	public function get_fields(): array {
		$fields = $this->fields;
		StableSort::uasort(
			$fields,
			static function ( Field $a, Field $b ) {
				return $a->get_priority() <=> $b->get_priority();
			}
		);

		return $fields;
	}
}
