<?php

namespace GRIM_CEW\Vendor;

class Field {
	public static function load( $type, $args = array() ) {
		$field = CEW_PATH . "/templates/fields/$type.php";

		if ( file_exists( $field ) ) {
			load_template( $field, false, $args );
		}
	}
}
