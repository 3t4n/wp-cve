<?php

namespace Nelio_Content\Zod;

class ObjectSchema extends Schema {

	protected array $schema;

	public static function make( array $schema ): ObjectSchema {
		$instance         = new self();
		$instance->schema = $schema;
		return $instance;
	}//end make()

	public function partial(): ObjectSchema {
		$this->schema = array_map(
			fn( $s ) => $s->optional(),
			$this->schema
		);
		return $this;
	}//end partial()

	public function required(): ObjectSchema {
		$this->schema = array_map(
			fn( $s ) => $s->required(),
			$this->schema
		);
		return $this;
	}//end required()

	public function parse_value( $value ) {
		if ( is_object( $value ) ) {
			$value = get_object_vars( $value );
		}//end if

		if ( ! is_array( $value ) ) {
			throw new \Exception(
				sprintf(
					'Expected an object, but %s found.',
					gettype( $value )
				)
			);
		}//end if

		$result = array();
		foreach ( $this->schema as $prop => $schema ) {
			$result[ $prop ] = $schema->parse( isset( $value[ $prop ] ) ? $value[ $prop ] : null );
		}//end foreach
		return array_filter( $result, fn( $p ) => ! is_null( $p ) );
	}//end parse_value()

}//end class
