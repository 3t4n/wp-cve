<?php

namespace Nelio_Content\Zod;

class NumberSchema extends Schema {

	private $min;
	private $max;

	public static function make(): NumberSchema {
		return new self();
	}//end make()

	public function positive(): NumberSchema {
		$this->min = 1;
		return $this;
	}//end positive()

	public function nonpositive(): NumberSchema {
		$this->max = 0;
		return $this;
	}//end nonpositive()

	public function negative(): NumberSchema {
		$this->max = -1;
		return $this;
	}//end negative()

	public function nonnegative(): NumberSchema {
		$this->min = 0;
		return $this;
	}//end nonnegative()

	public function min( int $min ): NumberSchema {
		$this->min = $min;
		return $this;
	}//end min()

	public function max( int $max ): NumberSchema {
		$this->max = $max;
		return $this;
	}//end max()

	public function parse_value( $value ) {
		if ( ! is_numeric( $value ) ) {
			throw new \Exception(
				sprintf(
					'Expected a number, but %s found.',
					gettype( $value )
				)
			);
		}//end if

		if ( ! is_null( $this->min ) && $value < $this->min ) {
			throw new \Exception(
				sprintf(
					'Expected a number greater than or equal to %1$s, but %2$s found.',
					$this->min,
					$value
				)
			);
		}//end if

		if ( ! is_null( $this->max ) && $this->max < $value ) {
			throw new \Exception(
				sprintf(
					'Expected a number less than or equal to %1$s, but %2$s found.',
					$this->max,
					$value
				)
			);
		}//end if

		return $value;
	}//end parse_value()

}//end class
