<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
/* gettext without WordPress */
if( ! function_exists('__') ){
	if( file_exists(dirname( __FILE__ ) . '/php-gettext/Gettext.php') ){
		include_once( dirname( __FILE__ ) . '/php-gettext/Gettext.php' );
		include_once( dirname( __FILE__ ) . '/php-gettext/PHP.php' );
	}
}
if( ! function_exists('sanitize_text_field') ){
	function sanitize_text_field( $str )
	{
		return $str;
	}
}
// template
// https://github.com/ramon82/t.php
if( ! class_exists('HC_T') ){
class HC_T {
	private $blockregex = '/\\{\\{(([@!]?)(.+?))\\}\\}(([\\s\\S]+?)(\\{\\{:\\1\\}\\}([\\s\\S]+?))?)\\{\\{\\/\\1\\}\\}/';
	private $valregex = '/\\{\\{([=%])(.+?)\\}\\}/';
	private $vars = false;
	private $key = false;
	private $t = '';
	private $result = '';
	public function __construct($template){
		$this->t = $template;
	}
	public function scrub($val){
		//useful to parse messages, emoji etc
		return htmlspecialchars($val.'', ENT_QUOTES);
	}
	public function get_value($index) {            
		$index = explode('.', $index);
		return $this->search_value($index, $this->vars);
	}
	private function search_value($index, $value) {
		if(is_array($index) and
		   count($index)) {
			$current_index = array_shift($index);
		}
		if(is_array($index) and
		   count($index) and
		   is_array($value[$current_index]) and
		   count($value[$current_index])) {
			return $this->search_value($index, $value[$current_index]);
		} else {
			$val = isset($value[$current_index])?$value[$current_index]:'';
			return str_replace('{{', "{\f{", $val);
		}
	}
	public function matchTags($matches) {
		$_ = $matches[0];
		$__ = $matches[1];
		$meta = $matches[2];
		$key = $matches[3];
		$inner = $matches[4];
		$if_true = $matches[5];
		$has_else = isset($matches[6]) ? $matches[6] : NULL;
		$if_false = isset($matches[7]) ? $matches[7] : NULL;
		$val = $this->get_value($key);
		$temp = "";
		$i;
		if (!$val) {
			// handle if not
			if ($meta == '!') {
				return $this->render($inner);
			}
			// check for else
			if ($has_else) {
				return $this->render($if_false);
			}
			return "";
		}
		// regular if
		if (!$meta) {
			return $this->render($if_true);
		}
		// process array/obj iteration
		if ($meta == '@') {
			// store any previous vars
			// reuse existing vars
			$_ = $this->vars['_key'];
			$__ = $this->vars['_val'];
			
			foreach ($val as $i => $v) {
				$this->vars['_key'] = $i;
				$this->vars['_val'] = $v;
				$temp .= $this->render($inner);
			}
			$this->vars['_key'] = $_;
			$this->vars['_val'] = $__;
			
			return $temp;
		}
	}
	public function replaceTags($matches) {
		$_ = $matches[0];
		$meta = $matches[1];
		$key = $matches[2];
		$val = $this->get_value($key);
		if ($val || $val === 0) {
			return $meta == '%' ? $this->scrub($val) : $val;
		}
		return "";
	}
	private function render($fragment) {
		$matchTags = preg_replace_callback($this->blockregex, array($this, "matchTags"), $fragment);
		$replaceTags = preg_replace_callback($this->valregex, array($this, "replaceTags"), $matchTags);
		return $replaceTags;
	}
	public function parse($obj){
		$this->vars = $obj;
		return $this->render($this->t);
	}
}
}

if ( !function_exists('str_getcsv')) {
    function str_getcsv($input, $delimiter = ',', $enclosure = '"', $escape = '\\', $eol = '\n') {
        if (is_string($input) && !empty($input)) {
            $output = array();
            $tmp    = preg_split("/".$eol."/",$input);
            if (is_array($tmp) && !empty($tmp)) {
                while (list($line_num, $line) = each($tmp)) {
                    if (preg_match("/".$escape.$enclosure."/",$line)) {
                        while ($strlen = strlen($line)) {
                            $pos_delimiter       = strpos($line,$delimiter);
                            $pos_enclosure_start = strpos($line,$enclosure);
                            if (
                                is_int($pos_delimiter) && is_int($pos_enclosure_start)
                                && ($pos_enclosure_start < $pos_delimiter)
                                ) {
                                $enclosed_str = substr($line,1);
                                $pos_enclosure_end = strpos($enclosed_str,$enclosure);
                                $enclosed_str = substr($enclosed_str,0,$pos_enclosure_end);
                                $output[$line_num][] = $enclosed_str;
                                $offset = $pos_enclosure_end+3;
                            } else {
                                if (empty($pos_delimiter) && empty($pos_enclosure_start)) {
                                    $output[$line_num][] = substr($line,0);
                                    $offset = strlen($line);
                                } else {
                                    $output[$line_num][] = substr($line,0,$pos_delimiter);
                                    $offset = (
                                                !empty($pos_enclosure_start)
                                                && ($pos_enclosure_start < $pos_delimiter)
                                                )
                                                ?$pos_enclosure_start
                                                :$pos_delimiter+1;
                                }
                            }
                            $line = substr($line,$offset);
                        }
                    } else {
                        $line = preg_split("/".$delimiter."/",$line);
   
                        /*
                         * Validating against pesky extra line breaks creating false rows.
                         */
                        if (is_array($line) && !empty($line[0])) {
                            $output[$line_num] = $line;
                        } 
                    }
                }
				$output = array_shift( $output );
                return $output;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if ( ! function_exists('hc2_seems_utf8')){
	function hc2_seems_utf8( $str )
	{
		if( function_exists('mb_check_encoding') ){
			return mb_check_encoding( $str, 'UTF-8' );
		}

		$length = strlen($str);
		for ($i=0; $i < $length; $i++) {
			$c = ord($str[$i]);
			if ($c < 0x80) $n = 0; # 0bbbbbbb
			elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
			elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
			elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
			elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
			elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
			else return false; # Does not match any model
			for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
				if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
					return false;
				}
			}
		return true;
	}
}

if ( ! function_exists('hc2_get_combos')){
	function hc2_get_combos( $in )
	{
		$return = array($in);

		$keys = array_keys($in);
		reset( $keys );
		foreach( $keys as $k ){
			if( is_array($in[$k]) ){
				$return = array();

				$this_options = $in[$k];
				reset( $this_options );
				foreach( $this_options as $o ){
					$sub_in = $in;
					$sub_in[$k] = $o;

					$sub_return = hc2_get_combos( $sub_in );
					foreach( $sub_return as $sbr ){
						$return[] = $sbr;
					}
				}
				break;
			}
		}
		return $return;
	}
}

if ( ! function_exists('hc2_build_csv'))
{
	function hc2_build_csv( $array, $separator = ',' )
	{
		$processed = array();
		reset( $array );
		foreach( $array as $a ){
			if( strpos($a, '"') !== FALSE ){
				$a = str_replace( '"', '""', $a );
			}
			if( strpos($a, $separator) !== FALSE ){
				$a = '"' . $a . '"';
			}
			$processed[] = $a;
			}

		$return = join( $separator, $processed );
		return $return;
	}
}

if( ! function_exists('hc_is_serialized') ){
	function hc_is_serialized($value, &$result = null)
	{
		// Bit of a give away this one
		if (!is_string($value))
		{
			return false;
		}
		// Serialized false, return true. unserialize() returns false on an
		// invalid string or it could return false if the string is serialized
		// false, eliminate that possibility.
		if ($value === 'b:0;')
		{
			$result = false;
			return true;
		}
		$length	= strlen($value);
		$end	= '';
		switch ($value[0])
		{
			case 's':
				if ($value[$length - 2] !== '"')
				{
					return false;
				}
			case 'b':
			case 'i':
			case 'd':
				// This looks odd but it is quicker than isset()ing
				$end .= ';';
			case 'a':
			case 'O':
				$end .= '}';
				if ($value[1] !== ':')
				{
					return false;
				}
				switch ($value[2])
				{
					case 0:
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					break;
					default:
						return false;
				}
			case 'N':
				$end .= ';';
				if ($value[$length - 1] !== $end[0])
				{
					return false;
				}
			break;
			default:
				return false;
		}
		if (($result = @unserialize($value)) === false)
		{
			$result = null;
			return false;
		}
		return true;
	}
}

/**
* Determines if the current version of PHP is greater then the supplied value
*
* Since there are a few places where we conditionally test for PHP > 5
* we'll set a static variable.
*
* @access	public
* @param	string
* @return	bool	TRUE if the current version is $version or higher
*/
if ( ! function_exists('hc_is_php'))
{
	function hc_is_php($version = '5.0.0')
	{
		static $_is_php;
		$version = (string)$version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
		}

		return $_is_php[$version];
	}
}

// --------------------------------------------------------------------

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('hc_remove_invisible_characters'))
{
	function hc_remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded){
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do {
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

if( ! function_exists('hc_in_array') ){
	function hc_in_array( $needle, $haystack )
	{
		if( ! is_array($haystack) ){
			$haystack = array( $haystack );
		}
		return in_array( $needle, $haystack );
	}
}

if( ! class_exists('HCM') ){
class HCM
{
	public static $domain = 'plainware';

	static function __( $str )
	{
		if( function_exists('__') ){
			return __($str, self::$domain);
		}
		else {
			$gettext_obj = hc_get_gettext();
			if( $gettext_obj === NULL ){
				return $str;
			}
			else {
				return $gettext_obj->gettext( $str );
			}
		}
	}

	static function _x( $str, $context )
	{
		if( function_exists('_x') ){
			return _x($str, $context,  self::$domain);
		}
		else {
			$gettext_obj = hc_get_gettext();
			if( $gettext_obj === NULL ){
				return $str;
			}
			else {
				return $gettext_obj->gettext( $str );
			}
		}
	}

	static function _n( $singular, $plural, $count )
	{
		if( function_exists('_n') ){
			return _n($singular, $plural, $count, self::$domain);
		}
		else {
			$gettext_obj = hc_get_gettext();

			if( $gettext_obj === NULL ){
				return $plural;
			}
			else {
				return $gettext_obj->ngettext( $singular, $plural, $count );
			}
		}
	}
}
}

if ( ! function_exists('hc_get_gettext'))
{
	function hc_get_gettext(){
		global $NTS_GETTEXT_OBJ;
		$domain = 'shiftexec';

		if( ! isset($NTS_GETTEXT_OBJ) ){
			$locale = "it_IT";
			$locale = "";
			// $locale = "ru_RU";

			if( $locale ){
				setlocale( LC_TIME, $locale );
			}

			if( $domain == "shiftexec" ){
				$domain = "shiftcontroller";
			}

			$modir = '';
			if( isset($GLOBALS["NTS_APPPATH"]) ){
				$modir = $GLOBALS["NTS_APPPATH"] . "/../languages";
			}
			$mofile = $modir . "/" . $domain . "-" . $locale . ".mo";
			// echo "mofile = $mofile<br>";

			global $NTS_GETTEXT_OBJ;
			if( class_exists('Gettext_PHP') ){
				$NTS_GETTEXT_OBJ = new Gettext_PHP( $mofile );
			}
			else {
				$NTS_GETTEXT_OBJ = NULL;
			}
		}
		return $NTS_GETTEXT_OBJ;
	}
}

if ( ! function_exists('hc_serialize'))
{
	function hc_serialize( $array )
	{
		$return = array();

		foreach( $array as $subarray ){
			foreach( $subarray as $k => $v ){
				if( is_object($v) ){
					if( isset($v->id) ){
						$v = array( $v->id );
					}
					else {
						$v = array();
					}
				}
				elseif( is_array($v) ){
				}
				else {
					$v = array( $v );
				}

				if( ! isset($return[$k]) ){
					$return[$k] = array();
				}
				$return[$k] = array_merge( $return[$k], $v );
				$return[$k] = array_unique( $return[$k] );
			}
		}
		$return = serialize( $return );
		return $return;
	}
}

/**
 * Plural
 *
 * Takes a singular word and makes it plural
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	str
 */
if ( ! function_exists('hc_plural'))
{
	function hc_plural($str, $force = FALSE)
	{
		$result = strval($str);

		$plural_rules = array(
			'/^(ox)$/'                 => '\1\2en',     // ox
			'/([m|l])ouse$/'           => '\1ice',      // mouse, louse
			'/(matr|vert|ind)ix|ex$/'  => '\1ices',     // matrix, vertex, index
			'/(x|ch|ss|sh)$/'          => '\1es',       // search, switch, fix, box, process, address
			'/([^aeiouy]|qu)y$/'       => '\1ies',      // query, ability, agency
			'/(hive)$/'                => '\1s',        // archive, hive
			'/(?:([^f])fe|([lr])f)$/'  => '\1\2ves',    // half, safe, wife
			'/sis$/'                   => 'ses',        // basis, diagnosis
			'/([ti])um$/'              => '\1a',        // datum, medium
			'/(p)erson$/'              => '\1eople',    // person, salesperson
			'/(m)an$/'                 => '\1en',       // man, woman, spokesman
			'/(c)hild$/'               => '\1hildren',  // child
			'/(buffal|tomat)o$/'       => '\1\2oes',    // buffalo, tomato
			'/(bu|campu)s$/'           => '\1\2ses',    // bus, campus
			'/(alias|status|virus)/'   => '\1es',       // alias
			'/(octop)us$/'             => '\1i',        // octopus
			'/(ax|cris|test)is$/'      => '\1es',       // axis, crisis
			'/s$/'                     => 's',          // no change (compatibility)
			'/$/'                      => 's',
		);

		foreach ($plural_rules as $rule => $replacement){
			if (preg_match($rule, $result)){
				$result = preg_replace($rule, $replacement, $result);
				break;
			}
		}
		return $result;
	}
}

// --------------------------------------------------------------------

/**
 * Singular
 *
 * Takes a plural word and makes it singular
 *
 * @access	public
 * @param	string
 * @return	str
 */
if ( ! function_exists('hc_singular'))
{
	function hc_singular($str)
	{
		$result = strval($str);

		$singular_rules = array(
			'/(matr)ices$/'         => '\1ix',
			'/(vert|ind)ices$/'     => '\1ex',
			'/^(ox)en/'             => '\1',
			'/(alias)es$/'          => '\1',
			'/([octop|vir])i$/'     => '\1us',
			'/(cris|ax|test)es$/'   => '\1is',
			'/(shoe)s$/'            => '\1',
			'/(o)es$/'              => '\1',
			'/(bus|campus)es$/'     => '\1',
			'/([m|l])ice$/'         => '\1ouse',
			'/(x|ch|ss|sh)es$/'     => '\1',
			'/(m)ovies$/'           => '\1\2ovie',
			'/(s)eries$/'           => '\1\2eries',
			'/([^aeiouy]|qu)ies$/'  => '\1y',
			'/([lr])ves$/'          => '\1f',
			'/(tive)s$/'            => '\1',
			'/(hive)s$/'            => '\1',
			'/([^f])ves$/'          => '\1fe',
			'/(^analy)ses$/'        => '\1sis',
			'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
			'/([ti])a$/'            => '\1um',
			'/(p)eople$/'           => '\1\2erson',
			'/(m)en$/'              => '\1an',
			'/(s)tatuses$/'         => '\1\2tatus',
			'/(c)hildren$/'         => '\1\2hild',
			'/(n)ews$/'             => '\1\2ews',
			'/([^u])s$/'            => '\1',
		);

		foreach ($singular_rules as $rule => $replacement){
			if (preg_match($rule, $result)){
				$result = preg_replace($rule, $replacement, $result);
				break;
			}
		}
		return $result;
	}
}

if ( ! function_exists('hc2_array_to_string'))
{
	function hc2_array_to_string( $array )
	{
		$return = array();
		foreach( $array as $k => $v ){
			$return[] = $k;
			$this_v = is_array($v) ? $v : array($v);
			$this_v = join('|', $this_v);
			$return[] = $this_v;
		}
		$return = join('/', $return);
		return $return;
	}
}

if ( ! function_exists('hc2_string_to_array'))
{
	function hc2_string_to_array( $string )
	{
		$return = array();

		$parts = explode('/', $string);
		while( $key = array_shift($parts) ){
			$value = array_shift($parts);
			if( strpos($value, '|') !== NULL ){
				$value = explode('|', $value);
			}
			$return[$key] = $value;
		}

		return $return;
	}
}

if ( ! function_exists('hc2_parse_args'))
{
	function hc2_parse_args( $args, $multiple_values = TRUE, $persist = TRUE )
	{
		$return = array();
		if( count($args) == 1 ){
			$return['id'] = array_shift($args);
		}
		else {
			for( $ii = 0; $ii < count($args); $ii = $ii + 2 ){
				if( isset($args[$ii + 1]) ){
					$k = $args[$ii];
					if( $persist ){
						if( substr($k, 0, 3) == '---' ){
							$k = substr($k, 3);
						}
						elseif( substr($k, 0, 2) == '--' ){
							$k = substr($k, 2);
						}
						elseif( substr($k, 0, 1) == '-' ){
							$k = substr($k, 1);
						}
					}

					$v = $args[$ii + 1];
					if( $multiple_values && is_string($v) && (strpos($v, '|') !== FALSE) ){
						$v = explode('|', $v);
					}

					if( array_key_exists($k, $return) && $multiple_values ){
						if( ! is_array($return[$k]) ){
							$return[$k] = array( $return[$k] );
						}
						if( is_array($v) ){
							$return[$k] = array_merge( $return[$k], $v );
						}
						else {
							$return[$k][] = $v;
						}
					}
					else {
						$return[$k] = $v;
					}
				}
			}
		}

		return $return;
	}
}

if ( ! function_exists('_print_r'))
{
	function _print_r( $thing )
	{
		echo '<pre>';
		print_r( $thing );
		echo '</pre>';
	}
}

if ( ! function_exists('hc_show_error'))
{
	function hc_show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		echo hc_show_detailed_error( $heading . ': ' . $status_code, $message, $status_code);
		exit;
	}
	
	function hc_show_detailed_error($heading, $message, $status_code = 500)
	{
		// set_status_header($status_code);
		hc_http_status_code( $status_code );

		$show_message = array();
		if( is_array($message) ){
			foreach( $message as $m2 ){
				if( is_array($m2) ){
					foreach( $m2 as $m3 ){
						$show_message[] = $m3;
					}
				}
				else {
					$show_message[] = $m2;
				}
			}
		}
		else {
			$show_message = array($message);
		}

		// $message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		$out = array();
		$out[] = '<h1>' . $heading . '</h1>';
		$out[] = join('<br>', $show_message);
		$out = join('', $out);

		return $out;
	}
}

if ( ! function_exists('hc_http_status_code'))
{
	function hc_http_status_code( $code )
	{
		if( $code ){
			$text = '';

			$stati = array(
				200	=> 'OK',
				201	=> 'Created',
				202	=> 'Accepted',
				203	=> 'Non-Authoritative Information',
				204	=> 'No Content',
				205	=> 'Reset Content',
				206	=> 'Partial Content',

				300	=> 'Multiple Choices',
				301	=> 'Moved Permanently',
				302	=> 'Found',
				304	=> 'Not Modified',
				305	=> 'Use Proxy',
				307	=> 'Temporary Redirect',

				400	=> 'Bad Request',
				401	=> 'Unauthorized',
				403	=> 'Forbidden',
				404	=> 'Not Found',
				405	=> 'Method Not Allowed',
				406	=> 'Not Acceptable',
				407	=> 'Proxy Authentication Required',
				408	=> 'Request Timeout',
				409	=> 'Conflict',
				410	=> 'Gone',
				411	=> 'Length Required',
				412	=> 'Precondition Failed',
				413	=> 'Request Entity Too Large',
				414	=> 'Request-URI Too Long',
				415	=> 'Unsupported Media Type',
				416	=> 'Requested Range Not Satisfiable',
				417	=> 'Expectation Failed',
				422	=> 'Unprocessable Entity',

				500	=> 'Internal Server Error',
				501	=> 'Not Implemented',
				502	=> 'Bad Gateway',
				503	=> 'Service Unavailable',
				504	=> 'Gateway Timeout',
				505	=> 'HTTP Version Not Supported'
			);

			if (isset($stati[$code]) AND $text == ''){
				$text = $stati[$code];
			}

			if ($text == ''){
				echo 'No status text available.  Please check your status code number or supply your own message text.';
			}

			$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

			if (substr(php_sapi_name(), 0, 3) == 'cgi'){
				header("Status: {$code} {$text}", TRUE);
			}
			elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0'){
				header($server_protocol." {$code} {$text}", TRUE, $code);
			}
			else {
				header("HTTP/1.1 {$code} {$text}", TRUE, $code);
			}
		}
	}
}


if ( ! function_exists('hc_random'))
{
	function hc_random( $len = 8 )
	{
		$salt1 = '0123456789';
		$salt2 = 'abcdef';

//		$salt .= 'abcdefghijklmnopqrstuvxyz';
//		$salt .= 'ABCDEFGHIJKLMNOPQRSTUVXYZ';

		// srand( (double) microtime() * 1000000 );
		$return = '';
		$i = 1;
		$array = array();

		while ( $i <= ($len - 1) ){
			$num = rand() % strlen($salt1 . $salt2);
			$tmp = substr($salt1 . $salt2, $num, 1);
			$array[] = $tmp;
			$i++;
			}
		shuffle( $array );

	// first is letter
		$num = rand() % strlen($salt2);
		$tmp = substr($salt2, $num, 1);
		array_unshift($array, $tmp);

		$return = join( '', $array );
		return $return;
	}
}

class HC_Binary_Array
{
	protected $store;
	protected $qty;
	protected $chunk;

	private $_in_one;

	public function __construct( $qty, $chunk_size_bits = 8 )
	{
		$this->qty = $qty;
		$this->chunk = $chunk_size_bits;

		$this->_in_one = (PHP_INT_SIZE * 8) / $this->chunk;
		$store_qty = ceil( $qty/$this->_in_one );
		$default_store = pow(2, (PHP_INT_SIZE * 8)) - 1;
		for( $si = 0; $si < $store_qty; $si++ ){
			$this->store[] = $default_store;
		}
	}

	public function set( $i, $value )
	{
	// find store index
		$sub_store_index = $i % $this->_in_one;
		$store_index = ($i - $sub_store_index) / $this->_in_one;

		echo "STORE INDEX $i: $store_index, $sub_store_index<br>";

		$binary = $value;
		$clear_mask = 0;
		if( $sub_store_index ){
			echo "BINARY WAS: " . decbin($binary) . "<br>";
			$shift = ($this->_in_one - $sub_store_index) * $this->chunk;
			echo "SHIFT = '$shift'<br>";
			$binary = $binary << $shift;
			// $binary = $binary << 1;
			echo "BINARY NOW: " . decbin($binary) . "<br>";
			
			
		}


		$store = $this->store[$store_index];
		echo "STORE NOW = " . decbin($store) . '<br>';
	}

	public function get( $i )
	{
		
	}
}

class HC_lib2 {
	public static function array_to_string( $array, $output = 'text' )
	{
		reset( $array );
		foreach( $array as $k => $v ){
			$out[] = $k . ': ' . $v;
		}

		switch( $output ){
			case 'html':
				$join = '<br>';
				break;
			case 'text':
				$join = ', ';
				break;
		}

		$out = implode( $join, $out );
		return $out;
	}

	public static function esc_attr( $value )
	{
		if( function_exists('esc_attr') ){
			return esc_attr( $value );
		}
		else {
			$return = htmlspecialchars( $value );
			return $return;
		}
	}

	public static function array_merge_by_order( $array1, $array2 )
	{
		$return = array();

		while( $test1 = array_shift($array1) ){
			if( in_array($test1, $array2) ){
				while( $test2 = array_shift($array2) ){
					if( $test2 == $test1 ){
						break;
					}
					$return[] = $test2;
				}
			}
			$return[] = $test1;
		}
		return $return;
	}

	public static function asterisk_to_re( $string )
	{
		$regex = preg_quote($string, '/'); // escape initial string
		$regex = str_replace( preg_quote('*'), '.*?', $regex ); // replace escaped asterisk to .*?
		$regex = "/^$regex$/i"; // you have case insensitive regexp
		return $regex;
	}

	public static function array_is_assoc($array)
	{
		if( ! is_array($array) ){
			return FALSE;
		}
		return count(array_filter(array_keys($array), 'is_string')) > 0;
	}

	static function link()
	{
		$uri = HC_App::uri();
		$args = func_get_args();

		$slug = array_shift( $args );

		$params = array();
		if( ! $args ){
			$params = array();
		}
		if( count($args) > 1 ){
			$params = $args;
		}
		// only one
		elseif( count($args) == 1 ){
			if( is_array($args[0]) ){
				$params = $args[0];
			}
			else {
				$params = array($args[0]);
			}
		}

		$final_params = array( $slug, $params );
		return call_user_func_array( array($uri, 'url'), $final_params );
	}

	static function web_dir_name( $fullWebPage )
	{
		preg_match( "/(.+)\/.*$/", $fullWebPage, $matches );
		if ( isset($matches[1]) )
			$webDir = $matches[1];
		else
			$webDir = '';
		return $webDir;
	}

	static function get_combinations( $a )
	{
		$return = array();
		if( count($a) > 3 ){
			echo 'get combinations is not supported for ' . count($a) . ' entries';
			return;
		}

		// dumb one
		sort( $a );
		switch( count($a) ){
			case 3:
				$return[] = array($a[0], $a[1], $a[2]);
				$return[] = array($a[0], $a[1]);
				$return[] = array($a[0], $a[2]);
				$return[] = array($a[1], $a[2]);
				$return[] = array($a[0]);
				$return[] = array($a[1]);
				$return[] = array($a[2]);
				break;
			case 2:
				$return[] = array($a[0], $a[1]);
				$return[] = array($a[0]);
				$return[] = array($a[1]);
				break;
			case 1:
				$return = $a;
				break;
		}

		return $return;
	}

	static function build_csv( $array, $separator = ',' )
	{
		$processed = array();
		reset( $array );
		foreach( $array as $a ){
			if( strpos($a, '"') !== false ){
				$a = str_replace( '"', '""', $a );
				}
			if( strpos($a, $separator) !== false ){
				$a = '"' . $a . '"';
				}
			$processed[] = $a;
			}

		$return = join( $separator, $processed );
		return $return;
	}

	static function array_skip_after( $src, $after, $include = TRUE )
	{
		$return = array();
		foreach( $src as $k ){
			if( $k == $after ){
				if( $include )
					$return[] = $k;
				break;
			}
			$return[] = $k;
		}
		return $return;
	}

	static function array_remain_after( $src, $after, $include = TRUE )
	{
		$return = array();
		$ok = FALSE;
		foreach( $src as $k ){
			if( $k == $after ){
				$ok = TRUE;
				if( ! $include )
					continue;
			}
			if( $ok )
				$return[] = $k;
		}
		return $return;
	}

	static function array_intersect_by_key( $src, $keys )
	{
		$out = array();
		foreach( $keys as $k ){
			if( array_key_exists($k, $src) ){
				$out[ $k ] = $src[ $k ];
			}
		}
		return $out;
	}

	static function generate_rand( $len = 12, $conf = array() )
	{
		$useLetters = isset($conf['letters']) ? $conf['letters'] : TRUE;
		$useHex = isset($conf['hex']) ? $conf['hex'] : FALSE;
		$useDigits = isset($conf['digits']) ? $conf['digits'] : TRUE;
		$useCaps = isset($conf['caps']) ? $conf['caps'] : FALSE;

		$salt = '';
		if( $useHex ){
			$salt .= 'abcdef';
		}
		if( $useLetters )
			$salt .= 'abcdefghijklmnopqrstuvxyz';
		if( $useDigits ){
			// $salt .= '0123456789';
			$salt .= '123456789';
		}
		if( $useCaps ){
			$salt .= 'ABCDEFGHIJKLMNOPQRSTUVXYZ';
		}

		// srand( (double) microtime() * 1000000 );
		$return = '';
		$i = 1;
		$array = array();
		while ( $i <= $len ){
			$num = rand() % strlen($salt);
			$tmp = substr($salt, $num, 1);
			$array[] = $tmp;
			$i++;
			}
		shuffle( $array );
		$return = join( '', $array );
		return $return;
	}

	static function is_ajax()
	{
		$return = false;
		if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ){
			$return = true;
		}
		return $return;
	}

	static function is_full_url( $url )
	{
		$ret = false;

		if( is_array($url)){
			return $ret;
		}

		if( null === $url ){
			return $ret;
		}

		// $prfx = array('http://', 'https://', '//', '/');
		$prfx = array('http://', 'https://', '//');
		reset( $prfx );
		foreach( $prfx as $prf ){
			if( substr($url, 0, strlen($prf)) == $prf ){
				$ret = true;
				break;
			}
		}

		return $ret;
	}

	static function sort_array_by_array( $array, $orderArray )
	{
		$return = array();
		reset( $orderArray );
		foreach( $orderArray as $o ){
			if( in_array($o, $array) ){
				$return[] = $o;
			}
		}
		reset( $array );
		foreach( $array as $a ){
			if( ! in_array($a, $return) )
				$return[] = $a;
		}
		return $return;
	}

	static function ksort_array_by_array( $array, $orderArray )
	{
		$return = array();
		reset( $orderArray );
		foreach( $orderArray as $o ){
			if( array_key_exists($o, $array) ){
				$return[$o] = $array[$o];
			}
		}
		reset( $array );
		foreach( $array as $k => $k ){
			if( ! array_key_exists($k, $return) )
				$return[$k] = $v;
		}
		return $return;
	}

	static function get_color_brightness( $hex )
	{
		// strip off any leading #
		$hex = str_replace('#', '', $hex);

		$c_r = hexdec(substr($hex, 0, 2));
		$c_g = hexdec(substr($hex, 2, 2));
		$c_b = hexdec(substr($hex, 4, 2));

		$return = (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
		return $return;
	}
	
	static function random_html_color( $i, $bright = 0 )
	{
		$out = array(
			'#dcf174',
			'#0000dd',
			'#dd0000',
			'#7F5417',
			'#21B6A8',
			'#87907D',
			'#ec6d66',
			'#177F75',
			'#B6212D',
			'#B67721',
			'#da2d8b',
			'#FF8000',
			'#61e94c',
			'#FFAABF',
			// '#91C3DC',
			'#FFCC00',
			'#E5E0C1',
			'#68BD66',
			'#179CE8',
			// '#BBFF20',
			'#30769E',
			// '#FFE500',
			'#C8E9FC',
			'#758a09',
			'#00CCFF',
			'#FFC080',
			'#4086AA',

			'#FFAABF',
			'#0000AA',
			'#AA6363',
			'#AA9900',
			'#1A8BC0',
			'#ECF8FF',
			'#758a09',
			'#dd3100',
			'#dea04a',
			'#af2a30',
			'#EECC99',
			'#179999',
			'#a92e03',
			'#dd9cc9',
			'#f30320',
			'#579108',
			'#ce9135',
			'#acd622',
			'#e46e46',
			'#53747d',
			'#36a62a',
			'#83877e',
			'#e82385',
			'#73f2f2',
			'#cb9fa4',
			'#12c639',
			'#f51b2b',
			'#985d27',
			'#3595d5',
			'#cb9987',
			'#d52192',
			'#695faf',
			'#de2426',
			'#295d5a',
			'#824b2d',
			'#08ccf6',
			'#e82a3c',
			'#fcd11a',
			'#2b4c04',
			'#3011fd',
			'#1df37b',
			'#af2a30',
			'#c456d1',
			'#025df6',
			'#0ab24f',
			'#c0d962',
			'#62369f',
			'#73faa9',
			'#fb453c',
			'#0487a4',
			'#ce9e07',
			'#2b407e',
			'#c28551',
			);

		$out = array(
			'#dcedc8',
			'#ffcdd2',
			'#e1bee7',
			'#d1c4e9',
			'#bbdefb',
			'#b2dfdb',
			'#f0f4c3',
			'#ffe0b2',
			'#fff9c4',
			'#d7ccc8',
			'#cfd8dc',
			'#e57373',
			'#9575cd',
			'#64b5f6',
			'#81c784',
			'#ffb74d',
			'#ff8a65',
		);

		$out = array(
			'#FFB3A7',	// 1
			'#CBE86B',	// 2
			'#89C4F4',	// 3
			'#F5D76E',	// 4
			'#BE90D4',	// 5
			'#fcf13a',	// 6
			'#ffffbb',	// 7
			'#fbf',		// 8
			'#87D37C',	// 9
			'#FF8000',	// 12
			'#73faa9',	// 13
			'#C8E9FC',	// 14
			'#cb9987',	// 15
			'#cfd8dc',	// 16
			'#9b9',		// 17
			'#9bb',		// 18
			'#bbf',		// 19
			'#dcedc8',	// 20
		);

		/* filter brightness */
		if( 0 && $bright > 0 ){
			$new_out = array();
			foreach( $out as $o ){
				$this_brightness = HC_Lib2::get_color_brightness( $o );
				if( $this_brightness > $bright ){
					$new_out[] = $o;
				}
			}
			$out = $new_out;
		}

		if( $i > count($out) ){
			$i = $i % count($out);
		}

		if( $i > 0 ){
			$return = $out[$i - 1];
		}
		else {
			$return = '#bbb';
		}

		return $return;
	}

	static function adjust_color_brightness( $hex, $steps )
	{
		// Steps should be between -255 and 255. Negative = darker, positive = lighter
		$steps = max(-255, min(255, $steps));

		// Normalize into a six character long hex string
		$hex = str_replace('#', '', $hex);
		if( strlen($hex) == 3 ){
			$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
		}

		// Split into three parts: R, G and B
		$color_parts = str_split($hex, 2);
		$return = '#';

		foreach( $color_parts as $color ){
			$color = hexdec($color); // Convert to decimal
			$color = max(0,min(255,$color + $steps)); // Adjust color
			$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
		}
    return $return;
	}

	static function pick_random( $array, $many = 1 )
	{
		if( $many > 1 ){
			$return = array();
			$ids = array_rand($array, $many );
			foreach( $ids as $id )
				$return[] = $array[$id];
		}
		else {
			$id = array_rand($array);
			$return = $array[$id];
		}
		return $return;
	}

	static function list_files( $dirName, $extension = '' )
	{
		if( ! is_array($dirName) )
			$dirName = array( $dirName );

		$files = array();
		foreach( $dirName as $thisDirName ){
			if ( file_exists($thisDirName) && ($handle = opendir($thisDirName)) ){
				while ( false !== ($f = readdir($handle)) ){
					if( substr($f, 0, 1) == '.' )
						continue;
					if( is_file( $thisDirName . '/' . $f ) ){
						if( (! $extension ) || ( substr($f, - strlen($extension)) == $extension ) )
							$files[] = $f;
					}
				}
				closedir($handle);
			}
		}
		sort( $files );
		return $files;
	}

	static function list_recursive( $dirname )
	{
		$return = array();
		$this_subfolders = HC_Lib2::list_subfolders( $dirname );
		foreach( $this_subfolders as $sf ){
			$subfolder_return = HC_Lib2::list_recursive( $dirname . '/' . $sf );
			foreach( $subfolder_return as $sfr ){
				$return[] = $sf . '/' . $sfr;
			}
		}

		$this_files = HC_Lib2::list_files( $dirname );
		$return = array_merge( $return, $this_files );
		return $return;
	}

	static function list_subfolders( $dirName )
	{
		if( ! is_array($dirName) )
			$dirName = array( $dirName );

		$return = array();
		reset( $dirName );
		foreach( $dirName as $thisDirName ){
			if ( file_exists($thisDirName) && ($handle = opendir($thisDirName)) ){
				while ( false !== ($f = readdir($handle)) ){
					if( substr($f, 0, 1) == '.' )
						continue;
					if( is_dir( $thisDirName . '/' . $f ) ){
						if( ! in_array($f, $return) )
							$return[] = $f;
					}
				}
				closedir($handle);
			}
		}

		sort( $return );
		return $return;
	}

	static function format_price( $amount, $calculated_price = '' )
	{
		$app_conf = HC_App::app_conf();

		$before_sign = $app_conf->get( 'currency_sign_before' );
		$currency_format = $app_conf->get( 'currency_format' );
		list( $dec_point, $thousand_sep ) = explode( '||', $currency_format );
		$after_sign = $app_conf->get( 'currency_sign_after' );

		$amount = number_format( $amount, 2, $dec_point, $thousand_sep );
		$return = $before_sign . $amount . $after_sign;

		if( strlen($calculated_price) && ($amount != $calculated_price) ){
			$calc_format = $before_sign . number_format( $calculated_price, 2, $dec_point, $thousand_sep ) . $after_sign;
			$return = $return . ' <span style="text-decoration: line-through;">' . $calc_format . '</span>';
		}
		return $return;
	}

	static function insert_after( $what, $array, $after )
	{
		$inserted = FALSE;
		$return = array();
		foreach( $array as $e ){
			$return[] = $e;
			if( $e == $after ){
				$return[] = $what;
				$inserted = TRUE;
			}
		}
		if( ! $inserted ){
			$return[] = $what;
		}
		return $return;
	}

	static function remove_from_array( $array, $what, $all = TRUE )
	{
		$return = $array;
		for( $ii = count($return) - 1; $ii >= 0; $ii-- ){
			if( $return[$ii] == $what ){
				array_splice( $return, $ii, 1 );
				if( ! $all ){
					break;
				}
			}
		}
		return $return;
	}

	static function debug( $text )
	{
		$fname = FCPATH . '/debug.txt';
		$text = $text . "\n";
		HC_Lib2::file_set_contents( $fname, $text, TRUE );
	}

	static function file_get_contents( $fileName )
	{
		$content = join( '', file($fileName) );
		return $content;
	}

	static function file_set_contents( $fileName, $content, $append = FALSE )
	{
		$length = strlen( $content );
		$return = 1;

		if( $append ){
			if(! $fh = fopen($fileName, 'a') ){
				echo "can't open file <B>$fileName</B> for appending.";
				exit;
			}
		}
		else {
			if(! $fh = fopen($fileName, 'w') ){
				echo "can't open file <B>$fileName</B> for wrinting.";
				exit;
			}
			rewind( $fh );
		}
		$writeResult = fwrite($fh, $content, $length);
		if( $writeResult === FALSE )
			$return = 0;

		return $return;
	}

	static function parse_icon( $title, $add_fw = TRUE )
	{
		$icon_start = strpos( $title, '<i' );
		if( $icon_start !== FALSE )
		{
			$icon_end = strpos( $title, '</i>' ) + 4; 
			$link_icon = substr( $title, 0, $icon_end );
			$link_title = substr( $title, $icon_end );
		}
		else
		{
			$link_title = strip_tags( $title );
			$link_icon = '';
		}

		if( $link_icon && $add_fw )
		{
			$icon_class_start = strpos( $link_icon, 'class=' ) + 6;
			if( $icon_class_start !== FALSE )
			{
				$icon_start = substr( $link_icon, 0, $icon_class_start + 1 );
				$icon_end = substr( $link_icon, $icon_class_start + 1 );
				if( strpos($link_icon, 'fa-fw') === FALSE )
				{
					$link_icon = $icon_start . 'fa-fw ' . $icon_end;
				}
			}
		}

		$link_icon = trim( $link_icon );
		$return = array( $link_title, $link_icon );
		return $return;
	}

	static function replace_in_array( $array, $from, $to ){
		$return = array();
		foreach( $array as $item ){
			if( $item == $from )
				$return[] = $to;
			else
				$return[] = $item;
		}
		return $return;
	}
}
