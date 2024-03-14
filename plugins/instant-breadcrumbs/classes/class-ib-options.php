<?php
class IB_Options {
	public static $cached = null;
	
	public static function get_all() {
		if ( self::$cached === null ) {
			self::$cached = get_option( 'ib-options', array() );
		}
		return self::$cached;
	}

	public static function safe_string( $name ) {
		// I would like to later rework string handling here, to allow for translations.
		// Currently this code only allows the string settings in a single language as set by the administrator.
		// It would be much better if they were translated into the user language for multilingual site support.
		$option = self::get_all();
		$value  = ( isset( $option[ $name ] ) && is_string( $option[ $name ] ) ) ? $option[ $name ] : '';
		$value  = trim( $value );
		if ( empty( $value ) ) {
			switch ( $name ) {
				case 'front':
					$value = __( 'Top', 'instant-breadcrumbs' );
					break;
				case 'pages':
					$value = __( 'Posts', 'instant-breadcrumbs' );
					break;
				case 'archive':
					$value = __( 'Archive', 'instant-breadcrumbs' );
					break;
				case 'notfound':
					$value = __( 'Not Found', 'instant-breadcrumbs' );
					break;
				case 'current':
					$value = __( 'Current Page', 'instant-breadcrumbs' );
					break;
				case 'location':
					$value = '';
				default:
					break;
				
			}
		}
		return $value;
	}

	public static function safe_boolean( $name ) {
		$option = self::get_all();
		if ( isset ( $option[ $name ] ) && is_bool( $option[ $name ] ) ) {
			return $option[ $name ];
		}
		switch ( $name ) {
			case 'strip':
				return false;
			case 'auto':
				return true;
			default:
				break;
		}
		return false;
	}

	public static function safe_select( $name ) {
		$option = self::get_all();
		$values = self::safe_option_values( $name );
		$value  = ( isset( $option [ $name ] ) && is_string( $option[ $name ] ) ) ? $option[ $name ] : '';
		// check value is a valid selection
		$index = array_search( $value, $values['list'] );
		if ( $index === FALSE ) {
			$value = $values['default'];
			$index = array_search( $value, $values['list'] );
		}
		$xmap = self::safe_option_translations( $name );
		return array( 'values' => self::safe_option_translate( $values, $xmap ), 'value' => $value, 'index' => $index );
	}

	public static function safe_selection( $name ) {
		$option = self::get_all();
		$values = self::safe_option_values( $name );
		$value  = ( isset( $option [ $name ] ) && is_string( $option[ $name ] ) ) ? $option[ $name ] : '';
		// check value is a valid selection
		if ( array_search( $value, $values['list'] ) === FALSE ) {
			$value = $values['default'];
		}
		return $value;
	}
		
	public static function safe_option_translate( $values, $xmap ) {
		$value = array();
		foreach ( $values['list'] as $key ) {
			$value[] = array( 'text' => $xmap[ $key ], 'value' => $key );
		}
		return $value;
	}
	
	public static function safe_option_translations( $name ) {
		$xmap = array();
		switch ( $name ) {
			case 'gen':
				$xmap = array(
				'builtin' => __( 'Instant Breadcrumbs Built-in', 'instant-breadcrumbs' ),
				'yoast' => __( 'Yoast (WordPress SEO)', 'instant-breadcrumbs' ),
				'navxt' => __( 'Breadcrumb NavXT', 'instant-breadcrumbs' ),
				);
				break;
			default:
				break;
		}
		return $xmap;
	}

	public static function safe_option_values( $name ) {
		$values = array( 'list' => array(), 'default' => '' );
		switch ( $name ) {
			case 'gen':
				$values['list']    = array( 'builtin', 'yoast', 'navxt' );
				$values['default'] = 'builtin';
				break;
			default:
				break;
		}
		return $values;
	}
}
