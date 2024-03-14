<?php

namespace Nelio_Content\Zod;

class UnionSchema extends Schema {

	private array $schemas;

	public static function make( array $schemas ): UnionSchema {
		$instance          = new self();
		$instance->schemas = $schemas;
		return $instance;
	}//end make()

	public function parse_value( $value ) {
		$result = array( 'success' => false );
		foreach ( $this->schemas as $schema ) {
			try {
				$result = array(
					'success' => true,
					'data'    => $schema->parse( $value ),
				);
				break;
			} catch ( \Exception $e ) { // phpcs:ignore
				// As soon as one element of the union successfully parses, there’s no need to continue.
			}//end try
		}//end foreach

		if ( empty( $result['success'] ) ) {
			throw new \Exception(
				sprintf(
					'Invalid value',
					gettype( $value )
				)
			);
		}//end if

		return $result['data'];
	}//end parse_value()

}//end class
