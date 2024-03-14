<?php
/**
 * Gator Object Cache
 *
 * Class for caching WordPress objects within the WP object cache.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @note should contain these functions https://developer.wordpress.org/reference/files/wp-includes/cache.php/
 */ 
class GatorObjectCache
{
    protected $blogId;
    protected $storage;
    protected $lastKey;
    protected $cache = array();
    protected $global_groups = array();
    protected $ttls;
    protected $wins = 0;
    protected $hits = 0;
    protected $miss = 0;
    protected $misses = array();
    protected $transients = array();

    const GROUP = 'gcdata';

    public function __construct($storageEngine, $active = true) 
    {
        /*for now just inject the file storage
         * if(empty($storageEngine) || !($storageEngine instanceof Reo\Cache\StoreInterface)){
            $storageEngine = GatorCache::getFileStorage();
        }*/
        $this->storage = $storageEngine;
        $this->ttls = $this->initTtls();
        $this->blogId = is_multisite() ? get_current_blog_id() : false;
        if ($active) {
            register_shutdown_function(array($this, 'stats'));
        }
    }

    public function flush()
    {
        $this->cache = array();
        // @note do not use the storage flush since it will flush the htaccess
        // return $this->storage->flush();
        if (!is_dir($dir = $this->storage->cacheDir)) {
            return true;
        }
        $objects = scandir($dir); 
        foreach ($objects as $object) { 
            if ('.' !== $object && '..' !== $object && is_dir($key = $dir . '/' . $object)) {
                self::flushDir($key);
            }
        }
        return true;
    }

    public static function flushDir($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        $objects = scandir($dir); 
        foreach ($objects as $object) { 
            if ('.' !== $object && '..' !== $object) { 
                if (is_dir($key = $dir . '/' . $object)) {
                    self::flushDir($key);
                }
                else {
                    unlink($key);
                }
            } 
        }
        rmdir($dir);
    }

    public function get($key, $group = '', $force = false, &$found = null)
    {
        if ('' === $group || empty($group)){
            $group = 'default';
        }

        if (isset($this->cache[$group][$this->lastKey = (false !== $this->blogId && !isset($this->global_groups[$group])) ? $this->blogId . ':' . $key : $key])) {
            //@note not sure min php version to support object operator from array
            $this->hits++;
            if(!($this->cache[$group][$this->lastKey] instanceof GatorCacheObject)){
                unset($this->cache[$group][$this->lastKey]);
                return false;
            }
            $found = true;
            return $this->cache[$group][$this->lastKey]->getData();
        }

        // get the data from the persistent cache
        if (null === ($data = $this->storage->get($this->lastKey, $group))) {
            $this->miss++;
            $this->misses[$group][] = $this->lastKey;
            /*if ('transient' === $group) {
                // some plugins like GF continue to call the transients for non-existent values, so temp cache the no-hit, -1 ttl will keep it from persisting
                $this->cache[$group][$this->lastKey] = new GatorCacheObject(false, -1);
            }*/
            $found = false;
            return false; //should be null, but this is what WP expects
        }
        // unserialize data
        set_error_handler('GatorObjectCache::errorHandler');
        try{
            $data = unserialize($data);
        }
        catch (Exception $e) {
            restore_error_handler();
            $this->storage->remove($this->lastKey, $group);
            $this->miss++;
            $found = false;
            return false;
        }
        restore_error_handler();
        $this->hits++;
        $this->wins++;
        $this->cache[$group][$this->lastKey] = new GatorCacheObject($data, isset($this->ttls[$group][$this->lastKey]) ? $this->ttls[$group][$this->lastKey] : 0);
        $found = true;
        return $this->cache[$group][$this->lastKey]->getData();
    }

/**
 * add
 * 
 * @note: Only adds if entry does not exist
 */ 
    public function add($key, $data, $group = '', $expire = 0)
    {
        if ('' === $group || empty($group)){
            $group = 'default';
        }

        if (isset($this->cache[$group][$id = (false !== $this->blogId && !isset($this->global_groups[$group])) ? $this->blogId . ':' . $key : $key])
          || null !== $this->storage->get($id, $group)) {
            return false;
        }

        $this->cache[$group][$id] = new GatorCacheObject($data, $expire);
        if (0 !== $expire) {
            $this->ttls[$group][$id] = $expire;
        }
        return true;
	}

/**
 * replace
 * 
 * @note: Only replace entry if it exists
 */ 
    public function replace($key, $data, $group = '', $expire = 0) 
    {
        if (false === $this->get($key,  $group = ('' === $group || empty($group)) ? 'default' : $group)) {
            return false;
        }

        $this->cache[$group][$this->lastKey]->setTtl($expire)->setData($data);
        if (0 !== $expire) {
            $this->ttls[$group][$this->lastKey] = $expire;
        }
        return true;
    }

/**
 * set
 * 
 * @note: set the cache entry
 */
    public function set($key, $data, $group = '', $expire = 0)
    {
        if ('' === $group || empty($group)){
            $group = 'default';
        }

		$this->cache[$group][$id = (false !== $this->blogId && !isset($this->global_groups[$group])) ? $this->blogId . ':' . $key : $key] = new GatorCacheObject($data, $expire);
        if (0 !== $expire) {
            $this->ttls[$group][$id] = $expire;
        }
        if('transient' === $group){
            $this->transients[] = $id;
        }
		return true;
	}

    public function remove($key, $group = '')
    {
        if ('' === $group || empty($group)){
            $group = 'default';
        }

		if (!($isTemp = isset($this->cache[$group][$id = (false !== $this->blogId && !isset($this->global_groups[$group])) ? $this->blogId . ':' . $key : $key]))
          && null === $this->storage->get($id, $group)) {
            return false;
        }

        if ($isTemp) {
            unset($this->cache[$group][$id]);
        }
        if (isset($this->ttls[$group][$id])) {
            unset($this->ttls[$group][$id]);
        }
		$this->storage->remove($id, $group);
		return true;
    }

    public function addGlobalGroups($groups)
    {
		$groups = array_fill_keys((array)$groups, true);
		$this->global_groups = $groups + $this->global_groups;
	}

    public function decrement($key, $offset = 1, $group = '')
    {
		if (false === ($value = $this->get($key,  $group = ('' === $group || empty($group)) ? 'default' : $group))) {
            return false;
        }
		$value = is_numeric($value) ? (int)$value : 0; 
		$value -= $offset;
		if ($value < 0 ) {
			$value = 0;
        }
        // @note uses lastKey since with multisite the key will contain blog id info
        $this->cache[$group][$this->lastKey]->setData($value);
        return $value;
	}

    public function increment($key, $offset = 1, $group = '')
    {
		if (false === ($value = $this->get($key, $group = ('' === $group || empty($group)) ? 'default' : $group))) {
            return false;
        }
		$value = is_numeric($value) ? (int)$value : 0; 
		$value += $offset;
		if ($value < 0 ) {
			$value = 0;
        }
        // @note uses lastKey since with multisite the key will contain blog id info
        $this->cache[$group][$this->lastKey]->setData($value);
        return $value;
	}

    public function switchToBlog($blogId)
    {
        if(!isset($this->blogId) || false !== $this->blogId){
            $this->blogId = $blogId;
        }
    }

    protected function saveData()
    {
		foreach ($this->cache as $group => $cache) {
            foreach ($cache as $id => $item) {
                //if (($ttl = $item->getTtl()) > -1) {
                    $this->storage->set($id, serialize($item->getData()), $item->getTtl(), $group);
                //}
            }
		}
        //var_dump($this->storage->get('alloptions', 'options'));
        // var_dump($this->ttls);
		$this->storage->set('ttls', $this->ttls, 0, self::GROUP);
	}

    public function stats() {
		$this->saveData();
        if ((defined('DOING_AJAX') && DOING_AJAX) || (defined('DOING_CRON') && DOING_CRON) || 'cli' == php_sapi_name()
          || (false === ($config = GatorCache::getConfig(ABSPATH . 'gc-config.ini.php'))) || !$config->get('debug')) {
            // no debug
            return;
        }
        $msg = sprintf('<!-- Cache Hits: %d | Cache Misses: %d | Cache Disk Hits: %d -->', $this->hits, $this->miss, $this->wins);
		echo $msg;
        return;
        $total = 0;
        foreach ($this->cache as $group => $cache) {
			$msg .= '<li><strong>Group:</strong> ' . $group . ' - ( ' . ($ct = count($cache)) . ' items )</li>';
			//$msg .= '<li><strong>Group:</strong> ' . $group . ' - ( ' . number_format( strlen( serialize( $cache ) ) / 1024, 2 ) . 'k )</li>';
            $total += $ct;
        }
		$msg .= '</ul>' . '<p>Total objects cached: ' . $total . '</p>';
        
        echo $msg;
        var_dump($this->misses);
        echo "Transients<br/>\n";
        var_dump( $this->transients);
	}

/**
 * @note: These is for BC, even WP(4.2.2) still has code that directly accesses the object cache
 */ 
    public function __isset($name)
    {
		return isset($this->{$name});
	}

    public function __get($name){
        if (isset($this->{$name})) {
            return $this->{$name};
        }
        return null;
    }

/**
 * @note: The the only setter in the WP codebase as of 4.2.2 is cache_enabled
 */
    public function __set($name, $value){
        if ('cache_enabled' === $name) {
            $this->cache_enabled = (bool) value;
        }
        // do not allow setter access to any other properties
    }

    public function initTtls() {
        if (null === ($data = $this->storage->get('ttls', self::GROUP))) {
            return array();
        }
        set_error_handler('GatorObjectCache::errorHandler');
        try{
            $data = unserialize($data);
        }
        catch (Exception $e) {
            restore_error_handler();
            $this->storage->remove('ttls', self::GROUP);
            return array();
        }
        restore_error_handler();
        return $data;
    }

    public static function errorHandler($errno, $errstr, $errfile, $errline) 
    {
        throw new RuntimeException('Value could not be unserialized');
    }
}

class GatorCacheObject
{
    private $data;
    private $ttl;

    public function __construct($data, $ttl)
    {
        $this->data = $data;
        $this->ttl = (int) $ttl;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getTtl()
    {
        return $this->ttl;
        return $this;
    }
}
