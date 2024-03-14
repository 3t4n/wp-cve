<?php

/**
 * Provides additional functions for Search Query class
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Search_Query_Extras {

	public static $debug_msg = [];

	/**
	 * Retrieve individual search keywords (keywords) or keep them together as sentence if too long
	 *
	 * @param $kb_id
	 * @param $sanitized_raw_user_input
	 * @param bool $filter_stop_words
	 * @return array
	 */
	public static function get_search_keywords( $kb_id, $sanitized_raw_user_input, $filter_stop_words=true ) {

		$current_language = EPHD_Multilang_Utilities::get_current_language();
		//$ephd_config = ephd_get_instance()->kb_config_obj->get_kb_config( $kb_id );

		// split the individual keywords if possible
		$filtered_search_keywords = array( $sanitized_raw_user_input );

		// get individual keywords
		if ( preg_match_all( '/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', $sanitized_raw_user_input, $keywords ) == false ) {
			self::$debug_msg[] = " Error parsing keywords.";
			return $filtered_search_keywords;
		}

		// 1. filter stop words
		$filtered_search_keywords = array();
		foreach ( $keywords[0] as $keyword ) {

			$keyword = urldecode($keyword);
			$keyword = preg_match( '/^".+"$/', $keyword ) ? trim( $keyword, "\"'" ) : trim( $keyword, "\"' " );
			$keyword = EPHD_Utilities::mb_strtolower( $keyword ); // does not work with å etc.: strtolower($keyword);
			if ( strlen( $keyword ) > 30 ) {
				$keyword = substr( $keyword, 0, 30 );
			}
			$keyword = sanitize_text_field( $keyword );

			// filter stop words
			if ( $filter_stop_words && $current_language == 'en' && in_array( $keyword, self::stop_words(), true ) ) {
				self::$debug_msg[] = ' Stop word removed: ' . $keyword;
				continue;
			}

			$filtered_search_keywords[] = $keyword;
		}


		// 3. synonyms
		// TODO $filtered_search_keywords = self::replace_with_synonyms($kb_id, $filtered_search_keywords);

		// consider search keywords a sentence if it is too long or contains stop words
		if ( empty($filtered_search_keywords) || count($filtered_search_keywords) > EPHD_Search_Query::MAX_KEY_WORDS ) {
			$filtered_search_keywords = array( $sanitized_raw_user_input );
		}

		return $filtered_search_keywords;
	}

	public static function get_search_debug( $kb_id ) {
		$debug_msg_array = array_unique(self::$debug_msg);
		$debug_msg = '<br/>' . EPHD_Multilang_Utilities::get_current_language( true ) . '<br/>';
		foreach( $debug_msg_array as $line ) {
			$debug_msg .= $line . '<br/>';
		}
		return $debug_msg;
	}

	/**
	 * Words that should not be matched in search as they are fillers rather than actual keywords
	 */
	public static function stop_words() {

		/* TODO translators: This is a comma-separated list of very common words that should be excluded from a search,
		 * like a, an, and the. These are usually called "stopwords". You should not simply translate these individual
		 * words into your language. Instead, look for and provide commonly accepted stopwords in your language.
		 */
		// TODO	$words = explode( ',', _x( 'about,an,are,as,at,be,by,com,for,from,how,in,is,it,of,on,or,that,the,this,to,was,what,when,where,who,will,with', 'Comma-separated list of search stopwords in your language' ) );

		return array( "a", "an", "about", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although",
			"always","among", "amongst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as", "at",
			"back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides",
			"between", "beyond", "both", "bottom","but", "by", "can", "cannot", "cant", "could", "couldnt", "cry", "describe", "detail",
			"done", "down", "due", "during", "each", "either", "else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every",
			"everyone", "everything", "everywhere", "except", "few", "fill", "find", "for", "former", "formerly",
			"found", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "hence", "her", "here", "hereafter", "hereby",
			"herein", "hereupon", "hers", "herself", "hi", "him", "himself", "his", "how", "however", "hundred",  "in", "indeed", "interest", "into", "is", "it",
			"its", "itself", "keep", "last", "latter", "latterly", "least", "less", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more",
			"moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nobody",
			"none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise",
			"our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems",
			"serious", "several", "she", "should", "show", "side", "since", "sincere",  "some", "somehow", "someone", "something", "sometime",
			"sometimes", "somewhere", "still", "such", "system", "take", "than", "that", "the", "their", "them", "themselves", "then", "there", "thereafter",
			"thereby", "therefore", "therein", "thereupon", "these", "they",  "thin", "this", "those", "though", "through", "throughout", "thru",
			"thus", "to", "together", "too", "top", "toward", "towards", "under", "until","upon",  "very", "via", "was", "we",
			"well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether",
			"which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours",
			"yourself", "yourselves", "the" );
	}

	public static function html_css_keywords() {
		return array( "align", "background", "block", "border", "bottom", "button", "center", "class", "code", "color", "column", "content", "data", "display", "end", "family", "font",
			"form", "height", "image", "label", "layout", "left", "link", "list", "main", "media", "option", "order", "page", "placeholder", "read", "row", "section", "script", "size",
			"start", "style", "title", "width", "left", "tab", "table", "target", "top", "url", "body", "footer", "format", "grid", "header", "margin", "mask", "meta", "object", "padding",
			"picture", "progress", "position", "place", "select", "template", "value", "weight", "elementor" );
	}
}
