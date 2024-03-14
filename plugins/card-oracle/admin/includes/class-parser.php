<?php
/**
 * Parser.
 *
 * @package Card_Oracle.
 */

namespace SimpleHtmlToText;

/**
 * Parser class.
 */
class Parser {
	/**
	 * Html.
	 *
	 * @var  string
	 */
	private $html;

	/**
	 * Text.
	 *
	 * @var  string
	 */
	private $text;

	/**
	 * Parsing rules array.
	 *
	 * @var array
	 */
	private $parse_rules = array(
		'/\s+/'                                    => ' ', // Remove HTML's whitespaces.
		'/<(img)\b[^>]*alt=\"([^>"]+)\"[^>]*>/Uis' => '($2)', // Parse image tags with alt.
		'/<(img)\b[^>][^>]*>/Uis'                  => '', // Remove image tags without alt.
		'/<a(.*)href=[\'"](.*)[\'"]>(.*)<\/a>/Uis' => '$3 ($2)', // Parse links.
		'/<hr(.*)>/Uis'                            => "\n==================================\n", // Parse lines.
		'/<br(.*)>/Uis'                            => "\n", // Parse breaklines.
		'/<(.*)br>/Uis'                            => "\n", // Parse broken breaklines.
		'/<p(.*)>(.*)<\/p>/Uis'                    => "\n$2\n", // Parse alineas.

		// Lists.
		'/(<ul\b[^>]*>|<\/ul>)/i'                  => "\n\n",
		'/(<ol\b[^>]*>|<\/ol>)/i'                  => "\n\n",
		'/(<dl\b[^>]*>|<\/dl>)/i'                  => "\n\n",

		'/<li\b[^>]*>(.*?)<\/li>/i'                => "\t* $1\n",
		'/<dd\b[^>]*>(.*?)<\/dd>/i'                => "$1\n",
		'/<dt\b[^>]*>(.*?)<\/dt>/i'                => "\t* $1",
		'/<li\b[^>]*>/i'                           => "\n\t* ",

		// Parse table columns.
		'/<tr>(.*)<\/tr>/Uis'                      => '\n$1',
		'/<td>(.*)<\/td>/Uis'                      => '$1\t',
		'/<th>(.*)<\/th>/Uis'                      => '$1\t',
		// Parse markedup text.
		'/<em\b[^>]*>(.*?)<\/em>/i'                => '$2',
		'/<b>(.*)<\/b>/Uis'                        => '**$1**',
		'/<strong(.*)>(.*)<\/strong>/Uis'          => '**$2**',
		'/<i>(.*)<\/i>/Uis'                        => '*$1*',
		'/<u>(.*)<\/u>/Uis'                        => '_$1_',
		// Headers.
		'/<h1(.*)>(.*)<\/h1>/Uis'                  => "\n### $2 ###\n",
		'/<h2(.*)>(.*)<\/h2>/Uis'                  => "\n## $2 ##\n",
		'/<h3(.*)>(.*)<\/h3>/Uis'                  => "\n## $2 ##\n",
		'/<h4(.*)>(.*)<\/h4>/Uis'                  => "\n## $2 ##\n",
		'/<h5(.*)>(.*)<\/h5>/Uis'                  => "\n# $2 #\n",
		'/<h6(.*)>(.*)<\/h6>/Uis'                  => "\n# $2 #\n",
		// Surround tables with newlines.
		'/<table(.*)>(.*)<\/table>/Uis'            => "\n$2\n",
	);

	/**
	 * Set the rule.
	 *
	 * @param string $rule Rule.
	 * @param string $value Value.
	 */
	public function set_parse_rule( $rule, $value ) {
		$this->parse_rules[ $rule ] = $value;
	}

	/**
	 * Remove the rule.
	 *
	 * @param string $rule Rule.
	 */
	public function remove_parse_rule( $rule ) {
		if ( array_key_exists( $rule, $this->parse_rules ) ) {
			unset( $this->parse_rules[ $rule ] );
		}
	}

	/**
	 * Parse text.
	 *
	 * @param string $string text to parse.
	 * @return string
	 */
	public function parse_string( $string ) {
		$this->set_html( $string );
		$this->parse();
		return $this->get_text();
	}

	/**
	 * Parse the HTML and put it into the text variable.
	 */
	private function parse() {
		$string = $this->get_html();

		foreach ( $this->parse_rules as $rule => $output ) {
			$string = preg_replace( $rule, $output, $string );
		}

		$string = html_entity_decode( $string );

		// Strip remaining tags.
		$string = wp_strip_all_tags( $string );

		// Fix double whitespaces.
		$string = preg_replace( '/(  *)/', ' ', $string );

		// Newlines with a space behind it - don't need that. (except in some cases, in which you'll miss 1 whitespace.
		// Well, too bad for you. File a PR <3.
		$string = preg_replace( '/\n /', "\n", $string );
		$string = preg_replace( '/ \n/', "\n", $string );

		// Remove tabs before newlines.
		$string = preg_replace( '/\t /', "\t", $string );
		$string = preg_replace( '/\t \n/', "\n", $string );
		$string = preg_replace( '/\t\n/', "\n", $string );

		// Replace all \n with \r\n because some clients prefer that.
		$string = preg_replace( '/\n/', "\r\n", $string );

		$this->set_text( $string );
	}

	/**
	 * Get html.
	 *
	 * @return string
	 */
	private function get_html() {
		return $this->html;
	}

	/**
	 * Set html.
	 *
	 * @param string $string Text to set.
	 * @return $this
	 */
	private function set_html( $string ) {
		$this->html = $string;
		return $this;
	}

	/**
	 * Get text.
	 *
	 * @return string
	 */
	private function get_text() {
		return $this->text;
	}

	/**
	 * Set text.
	 *
	 * @param string $string text to set.
	 * @return $this
	 */
	private function set_text( $string ) {
		$this->text = $string;
		return $this;
	}
}
