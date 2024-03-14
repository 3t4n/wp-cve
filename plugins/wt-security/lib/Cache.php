<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}
/**
 * WebTotem Cache class for Wordpress.
 */
class WebTotemCache {

	const WTOTEM_CACHE_STORAGE_TIME = 3; // cache storage time in minutes
	/**
	 * Save multiple some data to cache.
	 *
	 * @param array $data
	 *   Array of data.
	 * @param string $host_id
	 *   The data belongs to this host.
	 * @param string $storage_time
	 *   Cache storage time in minutes.
	 *
	 * @return bool
	 *   Returns TRUE after saving the data.
	 */
	public static function setData(array $data, $host_id, $storage_time = self::WTOTEM_CACHE_STORAGE_TIME) {

		$cache = json_decode(WebTotemOption::getOption('cache'), true) ?: [];

		foreach ($data as $key => $value){
			$expired = time() + ( $storage_time * 60 );
			$cache[$host_id][$key] = ['data' => $value, 'expired' => $expired];
		}

		WebTotemOption::setOptions(['cache' => $cache]);

		return TRUE;
	}

	/**
	 * Get data from cache.
	 *
	 * @param string $key
	 *   Data key.
	 * @param string $host_id
	 *   The data belongs to this host.
	 *
	 * @return array
	 *   Returns saved data by key.
	 */
	public static function getdata($key, $host_id) {

		$cache = json_decode(WebTotemOption::getOption('cache'), true) ?: [];
		if(array_key_exists($host_id, $cache) and
		   array_key_exists($key, $cache[$host_id]) and
		   $cache[$host_id][$key]['expired'] > time()) {
			return [
				'data' => $cache[$host_id][$key]['data'],
				'remained' => $cache[$host_id][$key]['expired'] - time(),
			];
		} else {
			return [];
		}

	}

    /**
     * Delete data from cache.
     *
     * @param string $key
     *   Data key.
     * @param string $host_id
     *   The data belongs to this host.
     *
     * @return bool
     */
    public static function deleteData($key, $host_id) {

        $cache = json_decode(WebTotemOption::getOption('cache'), true) ?: [];

        unset($cache[$host_id][$key]);
        WebTotemOption::setOptions(['cache' => $cache]);

        return TRUE;

    }

}
