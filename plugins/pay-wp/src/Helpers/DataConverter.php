<?php

namespace WPDesk\GatewayWPPay\Helpers;

class DataConverter {
	public static function simplexml_to_array( $xml_object ): array {

		$values_array = [];

		foreach ( (array) $xml_object as $index => $value ) {
			$values_array[ $index ] = ( is_object( $value ) ) ? self::simplexml_to_array( $value ) : $value;
		}

		return $values_array;
	}
}
