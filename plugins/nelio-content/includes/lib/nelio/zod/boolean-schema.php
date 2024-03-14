<?php

namespace Nelio_Content\Zod;

class BooleanSchema extends Schema {

	public static function make(): BooleanSchema {
		return new self();
	}//end make()

	public function parse_value( $value ) {
		if ( ! in_array( $value, array( true, false ), true ) ) {
			throw new \Exception( 'Expected boolean value.' );
		}//end if

		return true === $value;
	}//end parse_value()

}//end class
