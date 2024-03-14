<?php
/**
 * Set first leter in a string as UPPERCASE
 *
 * @param string $str string to modify
 * @return string string with first Uppercase letter
 */
if (!function_exists('strFirstUpWtbp')) {
	function strFirstUpWtbp( $str ) {
		return strtoupper(substr($str, 0, 1)) . strtolower(substr($str, 1, strlen($str)));
	}
}
/**
 * Deprecated - class must be created
 */
if (!function_exists('dateToTimestampWtbp')) {
	function dateToTimestampWtbp( $date ) {
		if (empty($a)) {
			return false;
		}
		$a = explode(WTBP_DATE_DL, $date);
		return mktime(0, 0, 0, $a[1], $a[0], $a[2]);
	}
}
/**
 * Generate random string name
 *
 * @param int $lenFrom min len
 * @param int $lenTo max len
 * @return string random string with length from $lenFrom to $lenTo
 */
if (!function_exists('getRandNameWtbp')) {
	function getRandNameWtbp( $lenFrom = 6, $lenTo = 9 ) {
		$res = '';
		$len = mt_rand($lenFrom, $lenTo);
		if ($len) {
			for ($i = 0; $i < $len; $i++) {
				$res .= chr(mt_rand(97, 122));	/*rand symbol from a to z*/
			}
		}
		return $res;
	}
}
if (!function_exists('importWtbp')) {
	function importWtbp( $path ) {
		if (file_exists($path)) {
			require($path);
			return true;
		}
		return false;
	}
}
if (!function_exists('setDefaultParamsWtbp')) {
	function setDefaultParamsWtbp( $params, $default ) {
		foreach ($default as $k => $v) {
			$params[$k] = isset($params[$k]) ? $params[$k] : $default[$k];
		}
		return $params;
	}
}
if (!function_exists('importClassWtbp')) {
	function importClassWtbp( $class, $path = '' ) {
		if (!class_exists($class)) {
			if (!$path) {
				$classFile = lcfirst($class);
				if (strpos(strtolower($classFile), WTBP_CODE) !== false) {
					$classFile = preg_replace('/' . WTBP_CODE . '/i', '', $classFile);
				}
				$path = WTBP_CLASSES_DIR . $classFile . '.php';
			}
			return importWtbp($path);
		} 
		return false;
	}
}
/**
 * Check if class name exist with prefix or not
 *
 * @param strin $class preferred class name
 * @return string existing class name
 */
if (!function_exists('toeGetClassNameWtbp')) {
	function toeGetClassNameWtbp( $class ) {
		$className = '';
		if (class_exists($class . strFirstUpWtbp(WTBP_CODE))) {
			$className = $class . strFirstUpWtbp(WTBP_CODE);
		} else if (class_exists(WTBP_CLASS_PREFIX . $class)) {
			$className = WTBP_CLASS_PREFIX . $class;
		} else {
			$className = $class;
		}
		return $className;
	} 
}
/**
 * Create object of specified class
 *
 * @param string $class class that you want to create
 * @param array $params array of arguments for class __construct function
 * @return object new object of specified class
 */
if (!function_exists('toeCreateObjWtbp')) {
	function toeCreateObjWtbp( $class, $params ) {
		$className = toeGetClassNameWtbp($class);
		$obj = null;
		if (class_exists('ReflectionClass')) {
			$reflection = new ReflectionClass($className);
			try {
				$obj = $reflection->newInstanceArgs($params);
			} catch (ReflectionException $e) {	// If class have no constructor
				$obj = $reflection->newInstanceArgs();
			}
		} else {
			$obj = new $className();
			call_user_func_array(array($obj, '__construct'), $params);
		}
		return $obj;
	}
}
/**
 * Redirect user to specified location. Be advised that it should redirect even if headers alredy sent.
 *
 * @param string $url where page must be redirected
 */
if (!function_exists('redirectWtbp')) {
	function redirectWtbp( $url ) {
		if (headers_sent()) {
			echo '<script type="text/javascript"> document.location.href = "' . esc_url($url) . '"; </script>';
		} else {
			header('Location: ' . $url);
		}
		exit();
	}
}
if (!function_exists('jsonEncodeUTFnormalWtbp')) {
	function jsonEncodeUTFnormalWtbp( $value ) {
		if (is_int($value)) {
			return (string) $value;   
		} elseif (is_string($value)) {
			$value = str_replace(array('\\', '/', '"', "\r", "\n", "\b", "\f", "\t"), 
								 array('\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'), $value);
			$convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
			$result = '';
			for ($i = strlen($value) - 1; $i >= 0; $i--) {
				$mb_char = substr($value, $i, 1);
				$result = $mb_char . $result;
			}
			return '"' . $result . '"';                
		} elseif (is_float($value)) {
			return str_replace(',', '.', $value);         
		} elseif (is_null($value)) {
			return 'null';
		} elseif (is_bool($value)) {
			return $value ? 'true' : 'false';
		} elseif (is_array($value)) {
			$with_keys = false;
			$n = count($value);
			for ($i = 0, reset($value); $i < $n; $i++, next($value)) {
				if (key($value) !== $i) {
					$with_keys = true;
					break;
				}
			}
		} elseif (is_object($value)) {
			$with_keys = true;
		} else {
			return '';
		}
		$result = array();
		if ($with_keys) {
			foreach ($value as $key => $v) {
				$result[] = jsonEncodeUTFnormalWtbp((string) $key) . ':' . jsonEncodeUTFnormalWtbp($v);    
			}
			return '{' . implode(',', $result) . '}';                
		} else {
			foreach ($value as $key => $v) {
				$result[] = jsonEncodeUTFnormalWtbp($v);    
			}
			return '[' . implode(',', $result) . ']';
		}
	} 
}
/**
 * Prepares the params values to store into db
 * 
 * @param array $d $_POST array
 * @return array
 */
if (!function_exists('prepareParamsWtbp')) {
	function prepareParamsWtbp( &$d = array(), &$options = array() ) {
		if (!empty($d['params'])) {
			if (isset($d['params']['options'])) {
				$options = $d['params']['options'];
			}
			if (is_array($d['params'])) {
				$params = UtilsWtbp::jsonEncode($d['params']);
				$params = str_replace(array('\n\r', "\n\r", '\n', "\r", '\r', "\r"), '<br />', $params);
				$params = str_replace(array('<br /><br />', '<br /><br /><br />'), '<br />', $params);
				$d['params'] = $params;
			}
		} elseif (isset($d['params'])) {
			$d['params']['attr']['class'] = '';
			$d['params']['attr']['id'] = '';
			$params = UtilsWtbp::jsonEncode($d['params']);
			$d['params'] = $params;
		}
		if (empty($options)) {
			$options = array('value' => array('EMPTY'), 'data' => array());
		}
		if (isset($d['code'])) {
			if ('' == $d['code']) {
				$d['code'] = prepareFieldCodeWtbp($d['label']) . '_' . rand(0, 9999999);
			}
		}
		return $d;
	}
}
if (!function_exists('prepareFieldCodeWtbp')) {
	function prepareFieldCodeWtbp( $string ) {   
		$string = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $string);
		$string = preg_replace('/\s+/', ' ', $string);
		$string = preg_replace('/ /', '', $string);

		$code = substr($string, 0, 8);
		$code = strtolower($code);
		if ('' == $code) {
			$code = 'field_' . gmdate('dhis');
		}
		return $code;
	}
}
/**
 * Recursive implode of array
 *
 * @param string $glue imploder
 * @param array $array array to implode
 * @return string imploded array in string
 */
if (!function_exists('recImplodeWtbp')) {
	function recImplodeWtbp( $glue, $array ) {
		$res = '';
		$i = 0;
		$count = count($array);
		foreach ($array as $el) {
			$str = '';
			if (is_array($el)) {
				$str = recImplodeWtbp('', $el);
			} else {
				$str = $el;
			}
			$res .= $str;
			if ($i < ( $count - 1 )) {
				$res .= $glue;
			}
			$i++;
		}
		return $res;
	}
}
/**
 * Twig require this function, but it is present not on all servers
 */
if (!function_exists('hash')) {
	function hash( $method, $data ) {
		return md5($data);
	}
}
if (!function_exists('ctype_alpha')) {
	function ctype_alpha( $text ) {
		return (bool) preg_match('/[^\pL]+/', $text);
	}
}
if ( ! function_exists( 'trueRequestWtbp' ) ) {
	function trueRequestWtbp() {
		$request = true;
		$uri     = ( isset( $_SERVER['REQUEST_URI'] ) && '' !== $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '';

		if ( '' === $uri ) {
			$request = false;
		} else {
			preg_match( '/^\/wp-json\/|\.png$|\.jpg$|\.ico$/', $uri, $matches );
			if ( ! empty( $matches ) ) {
				$request = false;
			}
		}

		return $request;
	}
}
