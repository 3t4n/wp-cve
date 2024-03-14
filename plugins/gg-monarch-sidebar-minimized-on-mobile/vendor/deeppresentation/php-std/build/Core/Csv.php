<?php namespace MSMoMDP\Std\Core;

class Csv {
	public static function load( string $file, string $delimiter = ',', int $maxLineLength = 10000 ) {
		$row    = 0;
		$result = array();
		$keys   = array();
		if ( ( $handle = fopen( $file, 'r' ) ) !== false ) {

			while ( ( $data = fgetcsv( $handle, $maxLineLength, $delimiter ) ) !== false ) {
				$num = count( $data );

				if ( $row == 0 ) {
					for ( $c = 0; $c < $num; $c++ ) {
						$keys[ $c ] = $data[ $c ];

					}
				} else {
					$result[ $row ] = array();
					for ( $c = 0; $c < $num; $c++ ) {
						$result[ $row ][ $keys[ $c ] ] = $data[ $c ];
					}
				}
				$row++;
			}
			fclose( $handle );
		}
		return $result;
	}
}
