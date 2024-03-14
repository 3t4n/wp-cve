<?php

namespace Nelio_Content\Zod;

class StringSchema extends Schema {

	private $min;
	private $max;
	private $regex;
	private $should_trim = false;

	public static function make(): StringSchema {
		return new self();
	}//end make()

	public function min( int $min ): StringSchema {
		$this->min = $min;
		return $this;
	}//end min()

	public function max( int $max ): StringSchema {
		$this->max = $max;
		return $this;
	}//end max()

	public function length( int $length ): StringSchema {
		$this->min = $length;
		$this->max = $length;
		return $this;
	}//end length()

	public function regex( string $regex ): StringSchema {
		$this->regex = $regex;
		return $this;
	}//end regex()

	public function trim(): StringSchema {
		$this->should_trim = true;
		return $this;
	}//end trim()

	public function parse_value( $value ) {
		if ( ! is_string( $value ) ) {
			throw new \Exception(
				sprintf(
					'Expected a string, but %s found.',
					gettype( $value )
				)
			);
		}//end if

		if ( $this->should_trim ) {
			$value = trim( $value );
		}//end if

		if (
			! is_null( $this->min ) &&
			$this->min === $this->max &&
			mb_strlen( $value ) !== $this->min
		) {
			throw new \Exception(
				sprintf(
					'Expected a string with length %1$s, but string is %2$s characters long.',
					$this->min,
					mb_strlen( $value )
				)
			);
		}//end if

		if ( ! is_null( $this->min ) && mb_strlen( $value ) < $this->min ) {
			throw new \Exception(
				sprintf(
					'Expected a string with length greater than or equal to %1$s, but string is %2$s characters long.',
					$this->min,
					mb_strlen( $value )
				)
			);
		}//end if

		if ( ! is_null( $this->max ) && $this->max < mb_strlen( $value ) ) {
			throw new \Exception(
				sprintf(
					'Expected a string with length less than or equal to %1$s, but string is %2$s characters long.',
					$this->max,
					mb_strlen( $value )
				)
			);
		}//end if

		if ( ! is_null( $this->regex ) && 1 !== preg_match( $this->regex, $value ) ) {
			throw new \Exception(
				sprintf(
					'String doesn\'t match regex "%s"',
					$this->regex
				)
			);
		}//end if

		return $value;
	}//end parse_value()

}//end class
