<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

class StableSort {

	static public function uasort( array &$array, $value_compare_func ) {
		$index = 0;
		foreach ( $array as &$item ) {
			$item = [ $index ++, $item ];
		}

		$result = uasort( $array, function ( $a, $b ) use ( $value_compare_func ) {
			$result = $value_compare_func( $a[1], $b[1] );

			return $result == 0 ? $a[0] - $b[0] : $result;
		} );

		foreach ( $array as &$item ) {
			$item = $item[1];
		}

		return $result;
	}

}
