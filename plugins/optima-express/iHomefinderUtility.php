<?php

class iHomefinderUtility {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getQueryVar($name) {
		return get_query_var($name, null);
	}

	public function getRequestVar($name) {
		$result = $this->getVarFromArray($name, $_REQUEST);
		return $result;
	}

	public function hasRequestVar($name) {
		$result = false;
		$value = $this->getVarFromArray($name, $_REQUEST);
		if(!empty($value)) {
			$result = true;
		}
		return $result;
	}

	public function getVarFromArray($key, $array) {
		$result = null;
		$key = strtolower($key);
		$array = $this->arrayKeysToLowerCase($array);
		if(array_key_exists($key, $array)) {
			$result = $array[$key];
		}
		return $result;
	}
	
	private function arrayKeysToLowerCase($array) {
		$lowerCaseKeysArray = array();
		if(is_array($array)) {
			foreach($array as $key => $value) {
				$key = strtolower($key);
				$lowerCaseKeysArray[$key] = $value;
			}
		}
		return $lowerCaseKeysArray;
	}
	
	public function appendQueryString($url, $key, $value) {
		if(isset($value, $key)) {
			if(is_bool($value)) {
				$value = ($value) ? "true" : "false";
			}
			if($value !== null) {
				if(substr($url, -1) !== "?" && substr($url, -1) !== "&") {
					$url .= "&";
				}
				$url .= $key . "=" . urlencode(trim($value));
			}
		}
		return $url;
	}
	
	public function buildUrl($url, array $parameters = null) {
		if(strpos($url, "?") === false) {
			$url .= "?";
		}
		if($parameters !== null && is_array($parameters)) {
			foreach($parameters as $key => $values) {
				$paramValue = null;
				if(is_array($values)) {
					foreach($values as $value) {
						if($paramValue !== null) {
							$paramValue .= ",";
						}
						$paramValue .= $value;
					}
				} else {
					$paramValue = $values;
				}
				$url = $this->appendQueryString($url, $key, $paramValue);
			}
		}
		return $url;
	}
	
	public function isDatabaseCached() {
		$result = false;
		$value1 = uniqid();
		update_option(iHomefinderConstants::DATABASE_CACHE_TEST, $value1);
		$value2 = get_option(iHomefinderConstants::DATABASE_CACHE_TEST, null);
		if($value1 !== $value2) {
			$result = true;
		}
		return $result;
	}
	
	public function isTruthy($value) {
		return $value === true || $value === 1 || $value === "true";
	}
	
	public function isFalsy($value) {
		return $value === false || $value === 0 || $value === "false";
	}

	public static function getKestrelBody($config) {
		foreach ($config as $key => $value) {
			if(is_null($value)) {
				unset($config[$key]);
			}	
		}
		return "
			<script>
				document.currentScript.replaceWith(ihfKestrel.render(" . json_encode($config) . "));
			</script>
		";
	}

	public static function delimitedStringToArray($string, $delimiter, $type) {
		return $array = array_map($type, explode($delimiter, $string));
	}

	public static function toIntArray($array) {
		$int_array = array();
		foreach($array as $key => $value) {
			if(is_numeric($value)) {
				array_push($int_array, (int) $value);
			}
		}
		return $int_array;
	}

	public static function stringToInt($string) {
		if(is_numeric($string)) {
			return (int) $string;
		}
	}
	
}