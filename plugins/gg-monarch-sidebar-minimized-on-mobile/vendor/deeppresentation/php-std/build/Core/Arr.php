<?php namespace MSMoMDP\Std\Core;


/**
 * Array basic operations
 */
class Arr {

	// SECTION Public

	public static function get( $dataIn, $path, $defaultVal = null, string $delimiter = '.', bool $caseInsensitive = false ) {
		if ( ( empty( $path ) && ! is_int( $path ) ) || ! $dataIn ) {
			return $defaultVal;
		}

		if ( is_array( $path ) ) {
			$pathArray = $path;
		} else {
			$pathArray = self::as_array( explode( $delimiter, $path ) );
		}

		$data = self::as_array( $dataIn );
		$temp = &$data;
		foreach ( $pathArray as $key ) {
			if ( isset( $temp ) && isset( $key ) && is_array( $temp ) ) {
				if ( $caseInsensitive ) {
					$temp = &array_change_key_case( $temp, CASE_LOWER );
					$key  = strtolower( $key );
				}
				if ( array_key_exists( $key, $temp ) ) {
					$temp = &$temp[ $key ];
				} else {
					unset( $temp );
					return $defaultVal;
				}
			} else {
				unset( $temp );
				return $defaultVal;
			}
		}
		$result = $temp;
		unset( $temp );
		return $result;
	}

	public static function sget( $dataIn, $key, $def = '', string $delimiter = '.', bool $caseInsensitive = false ) {
		return self::get( $dataIn, $key, $def, $delimiter, $caseInsensitive );
	}

	public static function set_as_path_array( &$data, array $pathArray, $value, $createIfNoExist = false ) {
		if ( empty( $pathArray ) ) {
			$data = $value;
			return;
		}
		$temp = &$data;
		foreach ( $pathArray as $key ) {
			if ( $createIfNoExist && ! array_key_exists( $key, $temp ) ) {
				$temp[ $key ] = array();
			}
			$temp = &$temp[ $key ];
		}
		$temp = $value;
		unset( $temp );
	}

	public static function set( &$data, $path, $value, $createIfNoExist = false, $delimiter = '.' ) {
		if ( empty( $path ) ) {
			$data = $value;
			return;
		}
		$pathArray = explode( $delimiter, $path );
		self::set_as_path_array( $data, $pathArray, $value, $createIfNoExist );
	}

	public static function as_array( $value ) {
		if ( isset( $value ) ) {
			return is_array( $value ) ? $value : array( $value );
		} else {
			return array();
		}
	}

	public static function as_assoc_array( $value ) {
		if ( isset( $value ) && self::is_assoc_array( $value ) ) {
			return $value;
		} else {
			return array();
		}
	}

	public static function as_array_merge( $value1, $value2 ) {
		$array1 = self::as_array( $value1 );
		$array2 = self::as_array( $value2 );
		return array_merge( $array1, $array2 );
	}

	public static function as_assoc_array_merge( $value1, $value2 ) {
		$array1 = self::as_assoc_array( $value1 );
		$array2 = self::as_assoc_array( $value2 );
		return array_merge( $array1, $array2 );
	}

	public static function is_assoc_array( $array ) {

		// bail ealry if not array
		if ( ! is_array( $array ) ) {
			return false;
		}
		// loop
		foreach ( $array as $key => $value ) {

			// bail ealry if is string
			if ( is_string( $key ) ) {
				return true;
			}
		}
		// return
		return false;
	}

	public static function as_string( $value, $glues = '' ) {
		return isset( $value ) ? ( is_array( $value ) ? self::multi_implode_glue_by_depth( $value, $glues ) : $value ) : '';
	}

	public static function transpose( $data ) {
		$retData = array();
		foreach ( $data as $row => $columns ) {
			foreach ( $columns as $row2 => $column2 ) {
				$retData[ $row2 ][ $row ] = $column2;
			}
		}
		return $retData;
	}

	public static function multi_implode_glue_by_depth( $array, $glues = array( '|', '-', ',' ) ) {
		return self::multi_implode( $array, self::as_array( $glues ), 0 );
	}

	public static function implode_assoc( $array, $glue = ';', $keyValSeparator = ':' ) {
		$res = '';
		foreach ( $array as $k => $v ) {
			$res .= $k . $keyValSeparator . $v . $glue;
		}
		return $res;
	}

	public static function explode_assoc( $str, $glue = ';', $keyValSeparator = ':' ) {
		$res      = array();
		$exploded = explode( $glue, $str );
		if ( count( $exploded ) > 0 ) {
			foreach ( $exploded as $keyValStr ) {
				$explodedKvp = explode( $keyValSeparator, $keyValStr );
				if ( count( $explodedKvp ) == 2 ) {
					$res[ $explodedKvp[0] ] = $explodedKvp[1];
				}
			}
		}
		return $res;
	}

	public static function remove_keys( $array, $keys ) {

		// array_diff_key() expected an associative array.
		$assocKeys = array();
		foreach ( $keys as $key ) {
			$assocKeys[ $key ] = true;
		}

		return array_diff_key( $array, $assocKeys );
	}

	// !SECTION End - Public


	// SECTION Private
	private static function multi_implode( $array, $glues, $depth ) {
		$ret = '';
		foreach ( $array as $item ) {
			if ( is_array( $item ) ) {
				$ret .= self::multi_implode( $item, $glues, min( $depth + 1, count( $glues ) - 1 ) ) . $glues[ $depth ];
			} else {
				$ret .= $item . $glues[ $depth ];
			}
		}
		$glueLength = strlen( $glues[ $depth ] );
		$ret        = ( $glueLength > 0 ) ? substr( $ret, 0, 0 - strlen( $glues[ $depth ] ) ) : $ret;
		return $ret;
	}
	// !SECTION End - Private
}
