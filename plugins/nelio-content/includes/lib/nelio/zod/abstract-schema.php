<?php

namespace Nelio_Content\Zod;

abstract class Schema {

	protected bool $is_optional = false;
	protected $default_value    = null;
	protected $transformation   = null;

	public function optional() {
		$this->is_optional = true;
		return $this;
	}//end optional()

	public function required() {
		$this->is_optional = false;
		return $this;
	}//end required()

	public function default( $value ) {
		$this->default_value = $value;
		return $this;
	}//end default()

	public function transform( callable $transformation ) {
		$this->transformation = $transformation;
		return $this;
	}//end transform()

	public function safe_parse( $value = null ) {
		try {
			$result = $this->parse( $value );
			return array(
				'success' => true,
				'data'    => $result,
			);
		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
			);
		}//end try
	}//end safe_parse()

	public function parse( $value = null ) {
		if ( $this->is_optional && is_null( $value ) ) {
			$result = $this->default_value;
		} else {
			$result = $this->parse_value( $value );
		}//end if

		return is_callable( $this->transformation )
			? call_user_func( $this->transformation, $result )
			: $result;
	}//end parse()

	abstract protected function parse_value( $value );

}//end class
