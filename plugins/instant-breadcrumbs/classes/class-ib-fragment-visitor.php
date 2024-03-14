<?php
/**
 * Visits the elements in a HTML text string.
 *
 * @since 1.0
 */
class IB_Fragment_Visitor {
	const WHITE  = 0;
	const SQ     = 1;
	const DQ     = 2;
	const EQUALS = 3;
	const TEXT   = 4;

	private static function parse_char( $char ) {
		switch ( $char ) {
			case "'": 	return self::SQ;
			case '"': 	return self::DQ; 
			case '=': 	return self::EQUALS;
			case ' ': 
			case "\t": 
			case "\r": 
			case "\n":	return self::WHITE;
			default:	return self::TEXT; 
		}
	}

	private static function parse_tag( $tagcontent ) {
		$tokens = array();
		$state  = self::WHITE;
		$buffer = '';
		$cursor = 0;
		$length = strlen( $tagcontent );
		while ( $cursor < $length ) {
			$char = substr( $tagcontent, $cursor++, 1 );
			$charclass = self::parse_char( $char );
			switch ( $state ) {
				case self::WHITE:
					if ( $charclass == self::EQUALS ) {
						$tokens[] = array( 'type' => 'equals' );
					} elseif ( $charclass == self::TEXT ) {
						$buffer = $char;
						$state  = $charclass;
					} elseif ( $charclass != self::WHITE ) {
						$buffer = '';
						$state  = $charclass;
					}
					break;
				case self::SQ:
				case self::DQ:
					if ( $charclass == $state ) {
						$tokens[] = array( 'type' => 'string', 'value' => $buffer );
						$buffer = '';
						$state  = self::WHITE;
					}
					else $buffer .= $char;
					break;
				case self::TEXT:
					if ( $charclass == self::TEXT ) {
						$buffer .= $char;
					} else {
						$tokens[] = array( 'type' => 'text', 'value' => $buffer );					
						if ( $charclass == self::EQUALS ) {
							$tokens[] = array( 'type' => 'equals' );
							$state = self::WHITE;
						} else {
							$buffer = '';
							$state  = $charclass;
						}
					}
					break;
			}
		}
		// there may be a last incomplete text token
		if ( $state == self::TEXT ) {
			$tokens[] = array( 'type' => 'text', 'value' => $buffer );
		}
		// tokens are parsed now, we should have text (text equals string)*
		$attributes = array();
		$tokencount = count( $tokens );
		if ( $tokencount == 0 ) return null;
		if ( $tokens[0]['type'] != 'text' ) return null;
		$tokencursor = 1;
		while ( $tokencursor <= $tokencount - 3 ) {
			if ( $tokens[ $tokencursor + 0 ]['type'] != 'text' ) return null;
			if ( $tokens[ $tokencursor + 1 ]['type'] != 'equals' ) return null;
			if ( $tokens[ $tokencursor + 2 ]['type'] != 'string' ) return null;

			$attributes[ $tokens[ $tokencursor ]['value'] ] = $tokens[ $tokencursor + 2 ]['value'] ;

			$tokencursor += 3;
		}
		if ( $tokencursor != $tokencount ) return null;
		return array( 'name' => $tokens[0]['value'], 'attributes' => $attributes );
	}

	protected function visit( $input ) {
		$cursor = 0;
		$length = strlen( $input );
		while ( $cursor < $length ) {
			$open   = strpos( $input, '<', $cursor );
			$inline = ($open === FALSE) ? ($length - $cursor) : ($open - $cursor);
			if ( $inline > 0 ) {
				if ( ! $this->on_content( substr( $input, $cursor, $inline ) ) ) return FALSE;
				$cursor += $inline;
			} else {
				// in a tag, opening or closing, or empty
				$close = strpos( $input, '>', $cursor + 1 );
				if ( $close === FALSE ) return FALSE;
				$tagcontent = substr( $input, $cursor + 1, $close - ($cursor + 1) );
				$tcl = strlen( $tagcontent );
				if ( $tcl == 0 ) return FALSE;
				$isOpen  = TRUE;
				$isClose = FALSE;
				if ( substr( $tagcontent, 0, 1 ) == '/' ) {
					$isOpen     = FALSE;
					$isClose    = TRUE;
					$tagcontent = substr( $tagcontent, 1 );
					if ( --$tcl == 0 ) return FALSE;
				} elseif ( substr( $tagcontent, $tcl - 1 ) == '/' ) {
					$isClose    = TRUE;
					$tagcontent = substr( $tagcontent, 0, $tcl - 1 );
					if ( --$tcl == 0 ) return FALSE;
				}
				$parsed = self::parse_tag( $tagcontent );
				if ( ! $parsed ) return FALSE;
				if ( ! $isOpen && (count( $parsed['attributes'] ) > 0 ) ) return FALSE;
				if ( $isOpen ) {
					if ( ! $this->on_tag_open( $parsed ) ) return FALSE;
				}
				if ( $isClose ) {
					if ( ! $this->on_tag_close( $parsed ) ) return FALSE;
				}
				$cursor = $close + 1;
			}
		}
		return TRUE;
	}
}
