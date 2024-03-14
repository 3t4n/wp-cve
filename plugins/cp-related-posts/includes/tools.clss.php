<?php
/* TOOLS */
require_once dirname( __FILE__ ) . '/porter-stemmer.clss.php';

class CPTagsExtractor {

	private $charset;
	private $lang;
	private $stop_words;
	private $range = 0.3;

	public function __construct() {
		$this->stop_words = array();
		$this->charset    = get_option( 'blog_charset' );
		$this->lang       = substr( get_bloginfo( 'language' ), 0, 2 );

		$path = dirname( __FILE__ );

		if ( file_exists( $path . '/stop-words/' . $this->lang . '.sw.txt' ) ) {
			$stopText         = file_get_contents( $path . '/stop-words/' . $this->lang . '.sw.txt' );
			$this->stop_words = mb_split( '[\s\n]+', mb_strtolower( $stopText, 'utf-8' ) );
			/*
			if( $this->lang == 'en' ){
				foreach ( $this->stop_words as $key => $word ){
					$this->stop_words[$key] = PorterStemmer::Stem( $word, true );
				}
			}
			*/
		}
	}

	public function get_tags( $text ) {
		$text = strip_shortcodes( stripcslashes( trim( $text ) ) );
		$text = preg_replace( '/\d+/', ' ', $text );

		// Convert the text to UTF-8
		$text = mb_convert_encoding( $text, 'utf-8', strtolower( $this->charset ) );

		// Strip tags
		$text = $this->strip_html_tags( $text );

		// Decode HTML character references
		$text = html_entity_decode( $text, ENT_QUOTES, 'utf-8' );

		// Strip puntuation symbols
		$text = $this->strip_punctuation( $text );

		// Strip symbols from text
		$text = $this->strip_symbols( $text );

		// Strip numbers and related symbols
		$text = $this->strip_numbers( $text );

		// Converting text to lowercase
		$text = mb_strtolower( $text, 'utf-8' );

		// Split the text in keywords
		mb_regex_encoding( 'utf-8' );
		$words = mb_split( '\s+', $text );

		/*
		// Stem the words
		if( $this->lang == 'en' ){
			foreach ( $words as $key => $word ){
				$words[$key] = PorterStemmer::Stem( $word, true );
			}
		}
		*/

		// Remove the stop words from text
		$words = array_diff( $words, $this->stop_words );

		// Create the list of keywords with the number of occurrences
		$keywordCounts = array_count_values( $words );
		arsort( $keywordCounts, SORT_NUMERIC );

		$tags = array();
		$c    = 0;

		foreach ( $keywordCounts as $word => $number ) {
			if ( ! empty( $word ) ) {
				if ( 0 == $c ) {
					$c = floor( $number * $this->range );
					$c = max( $c, 1 );
				}

				if ( $number >= $c ) {
					$tags[ $word ] = $number;
				} else {
					break;
				}
			}
		}
		return $tags;

	} // End get_tags

	private function strip_html_tags( $text ) {
		// PHP's strip_tags() function will remove tags, but it
		// doesn't remove scripts, styles, and other unwanted
		// invisible text between tags.  Also, as a prelude to
		// tokenizing the text, we need to insure that when
		// block-level tags (such as <p> or <div>) are removed,
		// neighboring words aren't joined.
		$text = preg_replace(
			array(
				// Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',

				// Add line breaks before & after blocks
				'@<((br)|(hr))@iu',
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ',
				' ',
				' ',
				' ',
				' ',
				' ',
				' ',
				' ',
				' ',
				"\n\$0",
				"\n\$0",
				"\n\$0",
				"\n\$0",
				"\n\$0",
				"\n\$0",
				"\n\$0",
				"\n\$0",
			),
			$text
		);

		// Remove all remaining tags and comments and return.
		return strip_tags( $text );
	} //End strip_html_tags

	/**
	 * Strip punctuation characters from UTF-8 text.
	 *
	 * Characters stripped from the text include characters in the following
	 * Unicode categories:
	 *
	 *  Separators
	 *  Control characters
	 *  Formatting characters
	 *  Surrogates
	 *  Open and close quotes
	 *  Open and close brackets
	 *  Dashes
	 *  Connectors
	 *  Numer separators
	 *  Spaces
	 *  Other punctuation
	 *
	 * Exceptions are made for punctuation characters that occur withn URLs
	 * (such as [ ] : ; @ & ? and others), within numbers (such as . , % # '),
	 * and within words (such as - and ').
	 *
	 * Parameters:
	 *  text        the UTF-8 text to strip
	 *
	 * Return values:
	 *  the stripped UTF-8 text.
	 */
	private function strip_punctuation( $text ) {
		$urlbrackets    = '\[\]\(\)';
		$urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
		$urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
		$urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;

		$specialquotes = '\'"\*<>';

		$fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
		$comma         = '\x{002C}\x{FE50}\x{FF0C}';
		$arabsep       = '\x{066B}\x{066C}';
		$numseparators = $fullstop . $comma . $arabsep;

		$numbersign   = '\x{0023}\x{FE5F}\x{FF03}';
		$percent      = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
		$prime        = '\x{2032}\x{2033}\x{2034}\x{2057}';
		$nummodifiers = $numbersign . $percent . $prime;

		return preg_replace(
			array(
				// Remove separator, control, formatting, surrogate,
				// open/close quotes.
					'/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
				// Remove other punctuation except special cases
					'/\p{Po}(?<![' . $specialquotes .
						$numseparators . $urlall . $nummodifiers . '])/u',
				// Remove non-URL open/close brackets, except URL brackets.
					'/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
				// Remove special quotes, dashes, connectors, number
				// separators, and URL characters followed by a space
					'/[' . $specialquotes . $numseparators . $urlspaceafter .
						'\p{Pd}\p{Pc}]+((?= )|$)/u',
				// Remove special quotes, connectors, and URL characters
				// preceded by a space
					'/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
				// Remove dashes preceded by a space, but not followed by a number
					'/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
				// Remove consecutive spaces
					'/ +/',
			),
			' ',
			$text
		);
	} // End strip_punctuation

	/**
	 * Strip symbol characters from UTF-8 text.
	 *
	 * Characters stripped from the text include characters in the following
	 * Unicode categories:
	 *
	 *  Modifier symbols
	 *  Private use symbols
	 *  Math symbols
	 *  Other symbols
	 *
	 * Exceptions are made for math symbols embedded within numbers (such as
	 * + - /), math symbols used within URLs (such as = ~), units of measure
	 * symbols, and ideograph parts.  Currency symbols are not removed.
	 *
	 * Parameters:
	 *  text        the UTF-8 text to strip
	 *
	 * Return values:
	 *  the stripped UTF-8 text.
	 */
	private function strip_symbols( $text ) {
		$plus  = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
		$minus = '\x{2012}\x{208B}\x{207B}';

		$units  = '\\x{00B0}\x{2103}\x{2109}\\x{23CD}';
		$units .= '\\x{32CC}-\\x{32CE}';
		$units .= '\\x{3300}-\\x{3357}';
		$units .= '\\x{3371}-\\x{33DF}';
		$units .= '\\x{33FF}';

		$ideo  = '\\x{2E80}-\\x{2EF3}';
		$ideo .= '\\x{2F00}-\\x{2FD5}';
		$ideo .= '\\x{2FF0}-\\x{2FFB}';
		$ideo .= '\\x{3037}-\\x{303F}';
		$ideo .= '\\x{3190}-\\x{319F}';
		$ideo .= '\\x{31C0}-\\x{31CF}';
		$ideo .= '\\x{32C0}-\\x{32CB}';
		$ideo .= '\\x{3358}-\\x{3370}';
		$ideo .= '\\x{33E0}-\\x{33FE}';
		$ideo .= '\\x{A490}-\\x{A4C6}';

		return preg_replace(
			array(
				// Remove modifier and private use symbols.
					'/[\p{Sk}\p{Co}]/u',
				// Remove math symbols except + - = ~ and fraction slash
					'/\p{Sm}(?<![' . $plus . $minus . '=~\x{2044}])/u',
				// Remove + - if space before, no number or currency after
					'/((?<= )|^)[' . $plus . $minus . ']+((?![\p{N}\p{Sc}])|$)/u',
				// Remove = if space before
					'/((?<= )|^)=+/u',
				// Remove + - = ~ if space after
					'/[' . $plus . $minus . '=~]+((?= )|$)/u',
				// Remove other symbols except units and ideograph parts
					'/\p{So}(?<![' . $units . $ideo . '])/u',
				// Remove consecutive white space
					'/ +/',
			),
			' ',
			$text
		);
	} // End strip_symbols

	/**
	 * Strip numbers and number-related characters from UTF-8 text.
	 *
	 * Characters stripped from the text include all digits, currency symbols,
	 * and periods or commas surrounded by digits.  Fractions and supercripts
	 * are removed, along with roman numerals (if they use the special Unicode
	 * characters).  Letters, punctuation, and other symbols are left as-is.
	 *
	 * Parameters:
	 *  text        the UTF-8 text to strip
	 *
	 * Return values:
	 *  the stripped UTF-8 text.
	 *
	 * See also:
	 *  http://nadeausoftware.com/articles/2007/10/php_tip_how_strip_numbers_web_page
	 */
	private function strip_numbers( $text ) {
		$urlchars  = '\.,:;\'=+\-_\*%@&\/\\\\?!#~\[\]\(\)';
		$notdelim  = '\p{L}\p{M}\p{N}\p{Pc}\p{Pd}' . $urlchars;
		$predelim  = '((?<=[^' . $notdelim . '])|^)';
		$postdelim = '((?=[^' . $notdelim . '])|$)';

		$fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
		$comma         = '\x{002C}\x{FE50}\x{FF0C}';
		$arabsep       = '\x{066B}\x{066C}';
		$numseparators = $fullstop . $comma . $arabsep;
		$plus          = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
		$minus         = '\x{2212}\x{208B}\x{207B}\p{Pd}';
		$slash         = '[\/\x{2044}]';
		$colon         = ':\x{FE55}\x{FF1A}\x{2236}';
		$units         = '%\x{FF05}\x{FE64}\x{2030}\x{2031}';
		$units        .= '\x{00B0}\x{2103}\x{2109}\x{23CD}';
		$units        .= '\x{32CC}-\x{32CE}';
		$units        .= '\x{3300}-\x{3357}';
		$units        .= '\x{3371}-\x{33DF}';
		$units        .= '\x{33FF}';
		$percents      = '%\x{FE64}\x{FF05}\x{2030}\x{2031}';
		$ampm          = '([aApP][mM])';

		$digits   = '[\p{N}' . $numseparators . ']+';
		$sign     = '[' . $plus . $minus . ']?';
		$exponent = '([eE]' . $sign . $digits . ')?';
		$prenum   = $sign . '[\p{Sc}#]?' . $sign;
		$postnum  = '([\p{Sc}' . $units . $percents . ']|' . $ampm . ')?';
		$number   = $prenum . $digits . $exponent . $postnum;
		$fraction = $number . '(' . $slash . $number . ')?';
		$numpair  = $fraction . '([' . $minus . $colon . $fullstop . ']' . $fraction . ')*';

		return preg_replace(
			array(
				// Match delimited numbers
					'/' . $predelim . $numpair . $postdelim . '/u',
				// Match consecutive white space
					'/ +/u',
			),
			' ',
			$text
		);
	} // End strip_numbers

} // End Class
