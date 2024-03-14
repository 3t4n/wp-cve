<?php
/**
 * Plugin Name: Auto Clean URL for SEO
 * Plugin URI: http://apasionados.es
 * Description: This plugin removes STOP WORDS from the WordPress Slugs in ENGLISH, SPANISH and GERMAN. For all languages it removes HTML entities and anything that is not a letter, digit, space or apostrophe.
 * Version: 1.6
 * Author: Apasionados.es
 * Author URI: http://apasionados.es
 * License: GPL2
 * Text Domain: autocleanurlforseo
 */
 
 /*  Copyright 2014  Apasionados.es  (email: info@apasionados.es)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$plugin_header_translate = array( __('Auto Clean URL for SEO', 'autocleanurlforseo'), __('This plugin removes STOP WORDS from the WordPress Slugs in ENGLISH, SPANISH and GERMAN. For all languages it removes HTML entities and anything that is not a letter, digit, space or apostrophe.', 'autocleanurlforseo') );

add_action( 'admin_init', 'autocleanurlforseo_load_language' );
function autocleanurlforseo_load_language() {
	load_plugin_textdomain( 'autocleanurlforseo', false,  dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'wp_ajax_sample-permalink', 'ajax_seo_slugs',0);
function ajax_seo_slugs($data) {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $post_name = isset($_POST['new_slug'])? $_POST['new_slug'] : null;
	$new_title = isset($_POST['new_title'])? $_POST['new_title'] : null;
	$seo_slug = strtolower(stripslashes($new_title));
	$seo_slug = preg_replace('/&.+?;/', '', $seo_slug); // Kill HTML entities
	$seo_slug_with_stopwords = $seo_slug;
	$seo_language = strtolower( substr( get_bloginfo ( 'language' ), 0, 2 ) ); 	// Check the language; we only want the first two letters
	if ( $seo_language == 'en' ) { // Check if blog language is English (en)
		$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_en()); // Turn it to an array and strip common/stop word by comparing against ENGLISH array
		$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
	} elseif ( $seo_language == 'es' ) { // Check if blog language is Spanish (es)
		$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_es()); // Turn it to an array and strip common/stop word by comparing against SPANISH array
		$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
	} elseif ( $seo_language == 'de' ) { // Check if blog language is German (de)
		$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_de()); // Turn it to an array and strip common/stop word by comparing against GERMAN array
		$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
	} elseif ( $seo_language == 'fr' ) { // Check if blog language is German (de)
		$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_fr()); // Turn it to an array and strip common/stop word by comparing against GERMAN array
		$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
	}
	$seo_slug = preg_replace ("/[^a-zA-Z0-9 \']-/", "", $seo_slug); // Kill anything that is not a letter, digit, space or apostrophe
	// Turn it to an array to count left words. If less than 3 words left, use original slug.
	// $clean_slug_array = explode( '-', $seo_slug );
	// if ( count( $clean_slug_array ) < 3 ) {
	//		$seo_slug = $seo_slug_with_stopwords;
	// }
	if (empty($post_name)) { $_POST['new_slug'] = $seo_slug; } // We don't want to change an existing slug
}

add_filter('name_save_pre', 'seo_slugs', 0);
function seo_slugs($slug) {
	if ($slug) return $slug; // We don't want to change an existing slug
	global $wpdb;
	if ( !empty($_POST['post_title']) ) {
		$seo_slug = strtolower(stripslashes($_POST['post_title']));
		$seo_slug = preg_replace('/&.+?;/', '', $seo_slug); // Kill HTML entities
		$seo_slug_with_stopwords = $seo_slug;
		$seo_language = strtolower( substr( get_bloginfo ( 'language' ), 0, 2 ) ); 	// Check the language; we only want the first two letters
		if ( $seo_language == 'en' ) { // Check if blog language is English (en)
			$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_en()); // Turn it to an array and strip common/stop word by comparing against ENGLISH array
			$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
		} elseif ( $seo_language == 'es' ) { // Check if blog language is Spanish (es)
			$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_es()); // Turn it to an array and strip common/stop word by comparing against SPANISH array
			$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
		} elseif ( $seo_language == 'de' ) { // Check if blog language is German (de)
			$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_de()); // Turn it to an array and strip common/stop word by comparing against GERMAN array
			$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
		} elseif ( $seo_language == 'fr' ) { // Check if blog language is German (de)
			$seo_slug_array = array_diff (explode(" ", $seo_slug), seo_slugs_stop_words_fr()); // Turn it to an array and strip common/stop word by comparing against GERMAN array
			$seo_slug = join("-", $seo_slug_array);	// Turn the sanitized array into a string
		}
		$seo_slug = preg_replace ("/[^a-zA-Z0-9 \']-/", "", $seo_slug); // Kill anything that is not a letter, digit, space or apostrophe
		// Turn it to an array to count left words. If less than 3 words left, use original slug.
		// $clean_slug_array = explode( '-', $seo_slug );
		// if ( count( $clean_slug_array ) < 3 ) {
		//		$seo_slug = $seo_slug_with_stopwords;
		// }
		return $seo_slug;
	}
}

function seo_slugs_stop_words_en () {
	   return array ("a", "able", "about", "above", "abroad", "according", "accordingly", "across", "actually", "adj", "after", "afterwards", "again", "against", "ago", "ahead", "ain't", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "a's", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "b", "back", "backward", "backwards", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "c", "came", "can", "cannot", "cant", "can't", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c'mon", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "c's", "currently", "d", "dare", "daren't", "definitely", "described", "despite", "did", "didn't", "different", "directly", "do", "does", "doesn't", "doing", "done", "don't", "down", "downwards", "during", "e", "each", "edu", "eg", "eight", "eighty", "either", "else", "elsewhere", "end", "ending", "enough", "entirely", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "f", "fairly", "far", "farther", "few", "fewer", "fifth", "first", "five", "followed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forward", "found", "four", "from", "further", "furthermore", "g", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "h", "had", "hadn't", "half", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "hello", "help", "hence", "her", "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "herself", "he's", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "hundred", "i", "i'd", "ie", "if", "ignored", "i'll", "i'm", "immediate", "in", "inasmuch", "inc", "inc.", "indeed", "indicate", "indicated", "indicates", "inner", "inside", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "its", "it's", "itself", "i've", "j", "just", "k", "keep", "keeps", "kept", "know", "known", "knows", "l", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "likewise", "little", "look", "looking", "looks", "low", "lower", "ltd", "m", "made", "mainly", "make", "makes", "many", "may", "maybe", "mayn't", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn't", "mine", "minus", "miss", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "mustn't", "my", "myself", "n", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needn't", "needs", "neither", "never", "neverf", "neverless", "nevertheless", "new", "next", "nine", "ninety", "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "o", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "one's", "only", "onto", "opposite", "or", "other", "others", "otherwise", "ought", "oughtn't", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "p", "particular", "particularly", "past", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provided", "provides", "q", "que", "quite", "qv", "r", "rather", "rd", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "respectively", "right", "round", "s", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "since", "six", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "t", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "thats", "that's", "that've", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there'd", "therefore", "therein", "there'll", "there're", "theres", "there's", "thereupon", "there've", "these", "they", "they'd", "they'll", "they're", "they've", "thing", "things", "think", "third", "thirty", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "till", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "t's", "twice", "two", "u", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "v", "value", "various", "versus", "very", "via", "viz", "vs", "w", "want", "wants", "was", "wasn't", "way", "we", "we'd", "welcome", "well", "we'll", "went", "were", "we're", "weren't", "we've", "what", "whatever", "what'll", "what's", "what've", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "whoever", "whole", "who'll", "whom", "whomever", "who's", "whose", "why", "will", "willing", "wish", "with", "within", "without", "wonder", "won't", "would", "wouldn't", "x", "y", "yes", "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've", "z", "zero");
}
function seo_slugs_stop_words_es () {
	   return array ("a", "algún", "alguna", "algunas", "alguno", "algunos", "ambos", "ampleamos", "ante", "antes", "aquel", "aquellas", "aquellos", "aqui", "arriba", "atras", "b", "bajo", "bastante", "bien", "c", "cada", "cierta", "ciertas", "ciertos", "como", "con", "conseguimos", "conseguir", "consigo", "consigue", "consiguen", "consigues", "cual", "cuando", "d", "dentro", "donde", "dos", "e", "el", "ellas", "ellos", "empleais", "emplean", "emplear", "empleas", "empleo", "en", "encima", "entonces", "entre", "era", "eramos", "eran", "eras", "eres", "es", "esta", "estaba", "estado", "estais", "estamos", "estan", "estoy", "f", "fin", "fue", "fueron", "fui", "fuimos", "g", "gueno", "h", "ha", "hace", "haceis", "hacemos", "hacen", "hacer", "haces", "hago", "i", "incluso", "intenta", "intentais", "intentamos", "intentan", "intentar", "intentas", "intento", "ir", "j", "k", "l", "la", "largo", "las", "lo", "los", "m", "mientras", "mio", "modo", "muchos", "muy", "n", "nos", "nosotros", "o", "otro", "p", "para", "pero", "podeis", "podemos", "poder", "podria", "podriais", "podriamos", "podrian", "podrias", "por qué", "por", "porque", "primero desde", "puede", "pueden", "puedo", "q", "quien", "r", "s", "sabe", "sabeis", "sabemos", "saben", "saber", "sabes", "ser", "si", "siendo", "sin", "sobre", "sois", "solamente", "solo", "somos", "soy", "su", "sus", "t", "también", "teneis", "tenemos", "tener", "tengo", "tiempo", "tiene", "tienen", "todo", "trabaja", "trabajais", "trabajamos", "trabajan", "trabajar", "trabajas", "trabajo", "tras", "tuyo", "u", "ultimo", "un", "una", "unas", "uno", "unos", "usa", "usais", "usamos", "usan", "usar", "usas", "uso", "v", "va", "vais", "valor", "vamos", "van", "vaya", "verdad", "verdadera cierto", "verdadero", "vosotras", "vosotros", "voy", "w", "x", "y", "yo", "z");
} // Stop word list from: http://www.ranks.nl/stopwords/spanish
function seo_slugs_stop_words_de () {
	   return array (
"a", "aber", "als", "am", "an", "auch", "auf", "aus", "b", "bei", "bin", "bis", "bist", "c", "d", "da", "dadurch", "daher", "darum", "das", "daß", "dass", "dein", "deine", "dem", "den", "der", "des", "deshalb", "dessen", "die", "dies", "dieser", "dieses", "doch", "dort", "du", "durch", "e", "ein", "eine", "einem", "einen", "einer", "eines", "er", "es", "euer", "eure", "f", "für", "g", "h", "hatte", "hatten", "hattest", "hattet", "hier hinter", "i", "ich", "ihr", "ihre", "im", "in", "ist", "j", "ja", "jede", "jedem", "jeden", "jeder", "jedes", "jener", "jenes", "jetzt", "k", "kann", "kannst", "können", "könnt", "l", "m", "machen", "mein", "meine", "mit", "muß", "müssen", "mußt", "musst", "müßt", "n", "nach", "nachdem", "nein", "nicht", "nun", "o", "oder", "p", "q", "r", "s", "seid", "sein", "seine", "sich", "sie", "sind", "soll", "sollen", "sollst", "sollt", "sonst", "soweit", "sowie", "t", "u", "über", "und", "unser unsere", "unter", "v", "vom", "von", "vor", "w", "wann", "warum", "was", "weiter", "weitere", "wenn", "wer", "werde", "werden", "werdet", "weshalb", "wie", "wieder", "wieso", "wir", "wird", "wirst", "wo", "woher", "wohin", "x", "y", "z", "zu", "zum", "zur");
} // Stop word list from: http://www.ranks.nl/stopwords/german
function seo_slugs_stop_words_fr () {
	   return array (
"alors", "au", "aucuns", "aussi", "autre", "avant", "avec", "avoir", "bon", "car", "ce", "cela", "ces", "ceux", "chaque", "ci", "comme", "comment", "dans", "des", "du", "dedans", "dehors", "depuis", "deux", "devrait", "doit", "donc", "dos", "droite", "début", "elle", "elles", "en", "encore", "essai", "est", "et", "eu", "fait", "faites", "fois", "font", "force", "haut", "hors", "ici", "il", "ils", "je juste", "la", "le", "les", "leur", "là", "ma", "maintenant", "mais", "mes", "mine", "moins", "mon", "mot", "même", "ni", "nommés", "notre", "nous", "nouveaux", "ou", "où", "par", "parce", "parole", "pas", "personnes", "peut", "peu", "pièce", "plupart", "pour", "pourquoi", "quand", "que", "quel", "quelle", "quelles", "quels", "qui", "sa", "sans", "ses", "seulement", "si", "sien", "son", "sont", "sous", "soyez sujet", "sur", "ta", "tandis", "tellement", "tels", "tes", "ton", "tous", "tout", "trop", "très", "tu", "valeur", "voie", "voient", "vont", "votre", "vous", "vu", "ça", "étaient", "état", "étions", "été", "être");
} // Stop word list from: http://www.ranks.nl/stopwords/french
?>