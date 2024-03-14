<?php namespace MSMoMDP\Std\Core;

use MSMoMDP\Std\Core\Str;

class Path {

	public static function fix_slashes( string $filePAth, string $correctSlash = DIRECTORY_SEPARATOR ) {
		$slashCharToFix = ( $correctSlash == '/' ) ? '\\' : '/';
		return str_replace( $slashCharToFix, $correctSlash, $filePAth );
	}

	public static function combine_url( $path1, $path2 ) {
		if ( Str::starts_with( $path2, '?' ) || Str::starts_with( $path2, '&' ) ) {
			$path2 = \ltrim( $path2, '&?' );
			if ( \strpos( $path1, '?' ) !== false ) {
				return $path1 . '&' . $path2;
			} else {
				if ( ! Str::ends_with( $path1, '/' ) ) {
					$path1 .= '/';
				}
				return $path1 . '?' . $path2;
			}
		} elseif ( \strpos( $path1, '?' ) !== false ) {
			if ( Str::ends_with( $path1, '?' ) || Str::ends_with( $path1, '&' ) ) {
				return $path1 . $path2;
			} else {
				return $path1 . '&' . $path2;
			}
		} else {
			return self::combine( $path1, $path2, '/' );
		}
	}

	public static function add_params_to_url_query( $current_query_str, array $params ) {
		$res = $current_query_str ?? '';
		if ( $params ) {
			$new_query = http_build_query( $params );
			if ( $new_query ) {
				$first_sep = $current_query_str ? '&' : '';
				$res      .= ( $first_sep . $new_query );
			}
		}
		return $res;
	}

	public static function combine_unix( $path1, $path2 ) {
		return self::combine( $path1, $path2, '/' );
	}

	public static function combine( $path1, $path2, $delimiter = DIRECTORY_SEPARATOR ) {
		$path1         = self::fix_slashes( $path1, $delimiter );
		$path2         = self::fix_slashes( $path2, $delimiter );
		$completedPath = '';

		if ( ! Str::ends_with( $path1, $delimiter ) ) {//if(substr($path1, strlen($path1) - 2, strlen($path1) - 1) !== $delimiter)
			$completedPath = $path1 . $delimiter;
		} else {
			$completedPath = $path1;
		}

		if ( ! Str::starts_with( $path2, $delimiter ) ) {// substr($path2, 0, 1) !== $delimiter)
			$completedPath .= $path2;
		} else {
			$completedPath .= substr( $path2, 1, strlen( $path2 ) - 1 );
		}

		return $completedPath;
	}

	public static function get_dir_contents( $dir, &$results = array() ) {
		$files = scandir( $dir );

		foreach ( $files as $key => $value ) {
			$path = realpath( $dir . DIRECTORY_SEPARATOR . $value );
			if ( ! is_dir( $path ) ) {
				$results[] = $path;
			} elseif ( $value != '.' && $value != '..' ) {
				getDirContents( $path, $results );
				$results[] = $path;
			}
		}
		return $results;
	}
}
