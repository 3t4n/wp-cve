<?php

namespace Wicked_Folders;

use JsonSerializable;

// Disable direct load
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

abstract class JSON_Serializable_Object implements JsonSerializable {

    public $schema = array();

	public function jsonSerialize(): array {
		$json = array();

		foreach ( $this->schema as $json_prop => $object_prop ) {
			$json[ $json_prop ] = $this->{ $object_prop };
		}

		return $json;
	}

	public function from_json( $json ) {
		foreach ( $this->schema as $json_prop => $object_prop ) {
			if ( is_array( $json) && isset( $json[ $json_prop ] ) ) {
				$this->{ $object_prop } = $json[ $json_prop ];
			}

			if ( is_object( $json ) && isset( $json->{ $json_prop } ) ) {
				$this->{ $object_prop } = $json[ $json_prop ];
			}
		} 
	}
}
