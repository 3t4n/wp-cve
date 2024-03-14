<?php namespace MSMoMDP\Std\Html;

use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Std\Core\Str;


class Html {

	// SECTION Public Static
	public static function get_str( $elementName, $classes = null, $styleAssocArray = null, $content = null, $addAttrsAsoc = null, $hasClosing = true ) {
		if ( ! empty( $elementName ) ) {
			$htmlString        = '<' . $elementName;
			$htmlAttr          = Arr::as_array( $addAttrsAsoc );
			$htmlAttr['class'] = ( array_key_exists( 'class', $htmlAttr ) ) ? Arr::as_array_merge( $htmlAttr['class'], $classes ) : Arr::as_array( $classes );
			$htmlAttr['style'] = ( array_key_exists( 'style', $htmlAttr ) ) ? Arr::as_assoc_array_merge( Arr::explode_assoc( $htmlAttr['style'] ), $styleAssocArray ) : Arr::as_array( $styleAssocArray );
			$htmlString       .= Html::get_attr_str( $htmlAttr );
			$htmlString       .= '>';
			if ( ! $hasClosing ) {
				return $htmlString;
			}
			$htmlString .= Arr::as_string( $content ) . '</' . $elementName . '>';
			return $htmlString;
		}
		return '';
	}

	public static function render( $elementName, $classes = null, $styleAssocArray = null, $content = null, $addAttrsAsoc = null, $hasClosing = true ) {
		echo self::get_str( $elementName, $classes, $styleAssocArray, $content, $addAttrsAsoc, $hasClosing );
	}

	public static function get_attr_str( $htmlAttr ) {
		// TODO Create object over
		if ( isset( $htmlAttr ) && count( $htmlAttr ) > 0 ) {
			$result = '';
			foreach ( $htmlAttr as $key => $val ) {
				if ( isset( $val ) ) {
					if ( is_array( $val ) && ! empty( $key ) ) {
						if ( count( $val ) > 0 ) {
							if ( $key == 'style' ) {
								$styleStr = self::get_style_str( $val );
								if ( ! empty( $styleStr ) ) {
									$result .= ' ' . $key . '="' . $styleStr . '"';
								}
							} else //if ($key == 'class')
							{
								$result .= ' ' . $key . '="' . implode( ' ', $val ) . '"';
							}
						}
					} elseif ( ! empty( $key ) ) {
						if ( Str::starts_with( $key, '$' ) ) {
							$result .= ' ' . $val;
						} else {
							$result .= ' ' . $key . '="' . $val . '"';
						}
					} else {
						$result .= '"' . $val . '"';
					}
				}
			}
			return $result;
		}
		return '';
	}

	public static function get_style_str( array $assocStyles ) {
		if ( count( $assocStyles ) > 0 ) {
			$res = '';
			foreach ( $assocStyles as $key => $val ) {
				$res .= $key . ':' . $val . ';';
			}
			return $res;
		}
		return '';
	}

	public static function render_color_text( string $textToEcho, $color ) {
		echo self::get_color_text_str( $textToEcho, $color );
	}

	public static function get_color_text_str( string $textToEcho, $color ) {
		return '<span style="color:' . $color . ';">' . $textToEcho . '</span>';
	}




	// !SECTION End - Public Static


}
