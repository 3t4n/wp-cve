<?php
/**
 * Class Tokenizer.
 *
 * @package YITH/Search/Utils
 */

/**
 * Class tokenizer
 */
class YITH_WCAS_Tokenizer {

	/**
	 * Regex to split
	 *
	 * @var string
	 */
	protected $regex = '/[^\p{L}\p{N}]+/u';


	/**
	 * Tokenize the string
	 *
	 * @param string $text String to tokenize.
	 * @param array  $stop_words  List of words to remove.
	 *
	 * @return mixed
	 */
	public function tokenize( $text, $stop_words = array() ) {
		$text  = ywcas_strtolower( $text );
		$split = preg_split( $this->get_regex(), $text, - 1, PREG_SPLIT_NO_EMPTY );
		return array_diff( $split, $stop_words );
	}

	/**
	 * Return the regex
	 *
	 * @return mixed|null
	 */
	public function get_regex() {
		return apply_filters( 'yith_wcas_tokenizer_regex', $this->regex );
	}

}
