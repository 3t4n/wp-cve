<?php

namespace Nelio_Content\Zod;

class LiteralSchema extends Schema {

	private $value;

	public static function make( $value ): LiteralSchema {
		$instance        = new self();
		$instance->value = $value;
		return $instance;
	}//end make()

	public function parse_value( $value ) {
		if ( $value !== $this->value ) {
			throw new \Exception(
				sprintf(
					'Expected %1$s but %2$s found.',
					$this->value,
					$value
				)
			);
		}//end if

		return $value;
	}//end parse_value()

}//end class
