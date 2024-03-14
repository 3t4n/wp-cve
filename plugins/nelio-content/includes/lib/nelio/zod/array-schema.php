<?php

namespace Nelio_Content\Zod;

class ArraySchema extends Schema {

	private $min;
	private $max;
	private Schema $schema;

	public static function make( Schema $schema ): ArraySchema {
		$instance         = new self();
		$instance->schema = $schema;
		return $instance;
	}//end make()

	public function min( int $min ): ArraySchema {
		$this->min = $min;
		return $this;
	}//end min()

	public function max( int $max ): ArraySchema {
		$this->max = $max;
		return $this;
	}//end max()

	public function nonempty(): ArraySchema {
		$this->min = 1;
		return $this;
	}//end nonempty()

	public function length( int $length ): ArraySchema {
		$this->min = $length;
		$this->max = $length;
		return $this;
	}//end length()

	public function parse_value( $value ) {
		if ( ! is_array( $value ) ) {
			throw new \Exception(
				sprintf(
					'Expected an array, but %s found.',
					gettype( $value )
				)
			);
		}//end if

		if (
			! is_null( $this->min ) &&
			$this->min === $this->max &&
			count( $value ) !== $this->min
		) {
			throw new \Exception(
				sprintf(
					'Expected an array of %1$s elements, but array has %2$s elements.',
					$this->min,
					count( $value )
				)
			);
		}//end if

		if ( ! is_null( $this->min ) && count( $value ) < $this->min ) {
			throw new \Exception(
				sprintf(
					'Expected an array with at least %1$s elements, but array has %2$s elements.',
					$this->min,
					count( $value )
				)
			);
		}//end if

		if ( ! is_null( $this->max ) && $this->max < count( $value ) ) {
			throw new \Exception(
				sprintf(
					'Expected an array with up to %1$s elements, but array has %2$s elements.',
					$this->max,
					count( $value )
				)
			);
		}//end if

		$result = array();
		foreach ( $value as $item ) {
			$result[] = $this->schema->parse( $item );
		}//end foreach
		return $result;
	}//end parse_value()

}//end class
