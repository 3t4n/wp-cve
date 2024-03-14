<?php

class iHomefinderCacheUtility {
	
	//prefix should only be up 13 character in length because cache key can only be 45 characters. prefix (13) + md5 hash (32).
	const CACHE_PREFIX = "ihf_cache_";
	const CACHE_ENABLED = true;
	
	private static $instance;
	private $logger;
	
	public function __construct() {
		$this->logger = iHomefinderLogger::getInstance();
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * @param mixed $key
	 * @return mixed
	 */
	public function getItem($key) {
		$result = null;
		if(self::CACHE_ENABLED) {
			$cacheKey = $this->getKey($key);
			$this->logger->debug("get cached version cacheKey " . $cacheKey);
			$result = get_transient($cacheKey);
			if($result === false) {
				$result = null;
			}
			$this->logger->debug($result);
		}
		return $result;
	}
	
	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @param integer $expiration
	 * @return void
	 */
	public function updateItem($key, $value, $expiration) {
		$cacheKey = $this->getKey($key);
		$this->logger->debug("updating cache cacheKey " . $cacheKey);
		set_transient($cacheKey, $value, $expiration);
	}
	
	/**
	 * @param mixed $key
	 * @return void
	 */
	public function deleteItem($key) {
		$cacheKey = $this->getKey($key);
		$this->logger->debug("deleting cache cacheKey " . $cacheKey);
		delete_transient($cacheKey);
	}
	
	/**
	 * @return void
	 */
	public function deleteItems() {
		global $wpdb;
		$optionsTableName = $wpdb->options;
		$sql = "DELETE FROM " . $optionsTableName . " WHERE `option_name` LIKE '%" . self::CACHE_PREFIX . "%'";
		$wpdb->query($sql);
	}
	
	/**
	 * @param mixed $key
	 * @return string
	 */
	private function getKey($key) {
		$keyHash = md5(serialize($key));
		$cacheKey = self::CACHE_PREFIX . $keyHash;
		return $cacheKey;
	}
	
}