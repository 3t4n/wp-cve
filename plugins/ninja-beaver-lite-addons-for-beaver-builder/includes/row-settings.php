<?php
/**
 * rgba color string
 *
 * @param $color
 * @param bool $opacity
 *
 * @return string
 */
function hex2rgba( $color, $opacity = false ) {
	$default = 'rgba(0,0,0)';
	if ( empty( $color ) ) {
		return $default; //Return default if no color provided
	}
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 ); //Sanitize $color if "#" is provided
	}
	//Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}
	//Convert hexadec to rgb
	$rgb = array_map( 'hexdec', $hex );
	//Check if opacity is set(rgba or rgb)
	if ( $opacity >= 0 ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	return $output; //Return rgb(a) color string
}

/**
 * @param array $array
 * @param int|string $position
 * @param mixed $insert
 */
function array_insert( &$array, $insert, $position = 0, $offset = null ) {
	$key_name = '';
	if ( is_int( $position ) && $key_name == null ) {
		array_splice( $array, $position, 0, $insert );
	} else {
		$pos = array_search( $position, array_keys( $array ), true );
		if ( $offset ) {
			$array = array_merge(
				array_slice( $array, 0, $offset ),
				$insert,
				array_slice( $array, $offset )
			);
		} else {
			$array = array_merge(
				array_slice( $array, 0, $pos ),
				$insert,
				array_slice( $array, $pos )
			);
		}
	}
}
