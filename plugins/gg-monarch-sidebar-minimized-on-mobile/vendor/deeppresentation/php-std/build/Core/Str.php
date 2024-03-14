<?php namespace MSMoMDP\Std\Core;

class Str {

	// SECTION Public

	public static function separed_transform_ucfirst( string $str, string $srcSeparator = '-', string $dstSeparator = '' ) {
		return implode( $dstSeparator, array_map( 'ucfirst', explode( $srcSeparator, $str ) ) );
	}

	public static function separed_transform_tolower( string $str, string $srcSeparator = '', string $dstSeparator = '' ) {
		return implode( $dstSeparator, array_map( 'strtolower', explode( $srcSeparator, $str ) ) );
	}

	public static function separed_last_part( string $data, $delimiter = '.', $def = '' ) {
		$dataParts = explode( $delimiter, $data );
		$count     = count( $dataParts );
		if ( $count > 0 ) {
			return $dataParts[ $count - 1 ];
		}
		return $def;
	}

	public static function to_camel_case( $string, $separator = '-', $capitalizeFirstCharacter = false ) {
		$str = str_replace( $separator, '', ucwords( $string, $separator ) );

		if ( ! $capitalizeFirstCharacter ) {
			$str = lcfirst( $str );
		}

		return $str;
	}

	public static function separed_first_part( string $data, $delimiter = '.', $def = '' ) {
		$dataParts = explode( $delimiter, $data );
		$count     = count( $dataParts );
		if ( $count > 0 ) {
			return $dataParts[0];
		}
		return $def;
	}

	public static function get_tag( $attr, $value, $xml, $tag = null ) {
		if ( is_null( $tag ) ) {
			$tag = '\w+';
		} else {
			$tag = preg_quote( $tag );
		}

		$attr  = preg_quote( $attr );
		$value = preg_quote( $value );

		$tag_regex = '/<(' . $tag . ")[^>]*$attr\s*=\s*(['\"])$value\\2[^>]*>(.*?)<\/\\1>/";

		preg_match_all(
			$tag_regex,
			$xml,
			$matches,
			PREG_PATTERN_ORDER
		);

		return $matches[3];
	}

	public static function separed_supplement_of_last_part( string $data, $delimiter = '.', $def = '' ) {
		$pos = strrpos( $data, $delimiter );
		if ( $pos ) {
			return substr( $data, 0, $pos );
		}
		return $def;
	}

	public static function starts_with( $haystack, $needle ) {
		$length = strlen( $needle );
		return ( substr( $haystack, 0, $length ) === $needle );
	}

	public static function ends_with( $haystack, $needle ) {
		$length = strlen( $needle );
		if ( $length == 0 ) {
			return true;
		}

		return ( substr( $haystack, -$length ) === $needle );
	}

	public static function compare_versions( string $version1, string $version2 ) {
		if ( $version1 && $version2 ) {
			$arr1   = \explode( '.', $version1 );
			$arr2   = \explode( '.', $version2 );
			$count1 = count( $arr1 );
			$count2 = count( $arr2 );

			$maxLength = \max( $count1, $count2 );
			$i         = 0;
			while ( $i < $maxLength ) {
				$val1 = $i < $count1 ? intval( $arr1[ $i ] ) : 0;
				$val2 = $i < $count2 ? intval( $arr2[ $i ] ) : 0;
				if ( $val1 !== $val2 ) {
					return $val1 > $val2 ? 1 : -1;
				}
				$i++;
			}
			return 0;
		}
		return null;
	}

	public static function char_replace_neighbor_check( string $search, string $replace, string $subject ) {
		$result = '';
		for ( $i = 0; $i < strlen( $subject ); $i++ ) {
			if ( $subject[ $i ] === $search ) {
				if ( $i > 0 && $subject[ $i - 1 ] == $replace ) {
					continue;
				}
				if ( $i + 1 < strlen( $subject ) && $subject[ $i + 1 ] == $replace ) {
					continue;
				}
				$result .= $replace;
			} else {
				$result .= $subject[ $i ];
			}
		}
		return $result;
	}

	public static function classes( $args = array(), $include_class_arg_name = false ) {
		$final_class_arr = array();
		foreach ( $args as $key => $value ) {
			if ( $value ) {
				$final_class_arr[] = $key;
			}
		}
		if ( $include_class_arg_name && count( $final_class_arr ) ) {
			return 'class="' . implode( ' ', $final_class_arr ) . '"';
		} else {
			return implode( ' ', $final_class_arr );
		}
	}

	public static function classes_e( $args = array(), $include_class_arg_name = false ) {
		echo self::classes( $args, $include_class_arg_name );
	}

	public static function make_url_absolute( $url, $base ) {
		// Return base if no url
		if ( ! $url ) {
			return $base;
		}

		// Return if already absolute URL
		if ( parse_url( $url, PHP_URL_SCHEME ) != '' ) {
			return $url;
		}

		// Urls only containing query or anchor
		if ( $url[0] == '#' || $url[0] == '?' ) {
			return $base . $url;
		}

		// Parse base URL and convert to local variables: $scheme, $host, $path
		extract( parse_url( $base ) );

		// If no path, use /
		if ( ! isset( $path ) ) {
			$path = '/';
		}

		// Remove non-directory element from path
		$path = preg_replace( '#/[^/]*$#', '', $path );

		// Destroy path if relative url points to root
		if ( $url[0] == '/' ) {
			$path = '';
		}

		// Dirty absolute URL
		$abs = "$host$path/$url";

		// Replace '//' or '/./' or '/foo/../' with '/'
		$re = array( '#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#' );
		for ( $n = 1; $n > 0; $abs = preg_replace( $re, '/', $abs, -1, $n ) ) {
		}

		// Absolute URL is ready!
		return $scheme . '://' . $abs;
	}

	// !SECTION End - Public


	// SECTION Private

	// !SECTION End - Private
}
