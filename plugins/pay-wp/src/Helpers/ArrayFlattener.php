<?php

namespace WPDesk\GatewayWPPay\Helpers;

class ArrayFlattener {

	public static function flatten_array( array $array ): array {
		$result = [];

		foreach ( $array as $element ) {
			if ( is_array( $element ) ) {
				$result = array_merge( $result, self::flatten_array( $element ) );
			} else {
				$result[] = $element;
			}
		}

		return $result;
	}

}
