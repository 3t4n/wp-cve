<?php
/**
 * CacheWrapper
 *
 * Page Cache Wrapper class for the Reo Classic CacheLite component.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CacheWrapper
{
    protected $cacheTtls = array();
    protected $cache;
    protected $cacheWarmer;
    protected $config = array(
        'lifetime' => 0, 'cache_dir' => '/tmp', 'enabled' => true, 'cache_warm' => false,
        'last_modified' => false, 'pingback' => false, 'skip_ssl' => true
    );
    const DIRECTORY_INDEX = 'index.html';
    const DIRECTORY_GZIP = 'index.gz';

    public function __construct(array $config = null)
    {
        if (!@class_exists('Reo_Classic_CacheLite', false)) {
            require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Reo/Classic/CacheLite.php');
        }
        if (isset($config)) {
            $this->config = $config + $this->config;
        }
        $this->config['lifetime'] = (int)$this->config['lifetime'];
        if (!empty($this->config['sys_load'])) {
            $load = sys_getloadavg();
            if (((int) $this->config['sys_load']) <= $load[0]) {
                $this->config['lifetime'] = 0;// do not expire pages when load is high
            }
        }
        $this->cache = new Reo_Classic_CacheLite($this->config['cache_dir'], array(
            'lifeTime'         => $this->config['lifetime'],
            'debug'            => false, //$this->config['debug'] this will only throw exceptions when purging non-existent stuff
            'readControl'      => false,
            'hashedDirectoryUmask' => 0755,
            'fileNameHashMode' => 'apache'
        ));
        $this->config['gzip'] = extension_loaded('zlib') ? !empty($this->config['gzip']) : false;
    }

    public function save($id, $data, $group = 'page', $ttl = null)
    {
        if (!$this->config['enabled']) {
            return false;
        }

        $result = $this->cache->save($key = $this->getKeyForUri($id), $data, $group);
        if ($result && $this->config['gzip'] && strstr($key, self::DIRECTORY_INDEX) && false !== ($data = gzencode($data, 6))) {
            $this->cache->save(str_replace(self::DIRECTORY_INDEX, self::DIRECTORY_GZIP, $key), $data, $group);//save gzipped
        }
        return $result;
    }

    public function get($id, $group = 'page')
    {
        if (!$this->config['enabled']) {
            return false;
        }
        return $this->cache->get($key = $this->getKeyForUri($id), $group);
    }

    public function has($id, $group = 'page')
    {
        if (!$this->config['enabled']) {
            return false;
        }
        return $this->cache->has($this->getKeyForUri($id), $group);
    }

    public function purge($group = 'page', $path = null)
    {
        return $this->cache->clean($group, $path);
    }

/**
 * flush
 * 
 * Delete the entire cache
 */ 
    public function flush()
    {
        return $this->cache->flush();
    }

    public function remove($id, $group = 'page', $check = false)
    {
        //pretty urls
        $id = trim($id, '/');

        if ($this->config['gzip']) {
            $this->cache->remove($id . '/index.gz', $group, $check);
        }
        $result = $this->cache->remove($id . '/index.html', $group, $check);

        //zap any pagination
        $this->cache->clean($pag = $group . '/' . $id  . '/page');
        return $result;
    }

/**
 * removeGroups
 *
 * Remove id from multiple groups
 */
    public function removeGroups($id, array $groups, $check = false)
    {
        foreach ($groups as $group) {
            $result = $this->remove($id, $group, $check);
        }

        if ($this->config['cache_warm']) {
            $this->getCacheWarmer()->warmUri($id);
            if ($this->config['jp_mobile_cache']) {
                $this->getCacheWarmer()->warmUri($id, true);
            }
        }

        return $result;
    }

/**
 * purgeGroups
 *
 * Purge multiple groups
 */
    public function purgeGroups(array $groups)
    {
        foreach ($groups as $group) {
            $result = $this->purge($group);
        }
        return $result;
    }

/**
 * warm
 * 
 * Warms the cache for a given url, eg get_permalink() for a given post
 */ 
    public function warm($url, $check = false)
    {
        $group = $this->config['group'];
        // secure url
        if (0 === strpos($url, 'https')) {
            if ($this->config['skip_ssl']) {
                return false;
            }
            $group = 'ssl@' . $group;
        }

        if (false !== ($path = parse_url($url, PHP_URL_PATH)) && !$this->has($path, $group)) {
            if ($check) {
                // just check the url, returns true if it needs to be warmed
                //var_dump($this->cache->getFileName());
                return true;
            }
            $this->getCacheWarmer()->warmUrl($url);
            if ($this->config['jp_mobile_cache']) {
                $this->getCacheWarmer()->warmUrl($url);
            }
            return true;
        }
        return false;
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function hasCache($group = 'page')
    {
        return is_dir($this->config['cache_dir'] . DIRECTORY_SEPARATOR . $group);
    }

    public function hasCacheGroups(array $groups)
    {
        foreach ($groups as $group) {
            if ($this->hasCache($group)) {
                return true;
                break;
            }
        }
        return false;
    }

    public function getCacheWarmer()
    {
        if (!isset($this->cacheWarmer)) {
            if (!@class_exists('GatorCacheWarmer', false)) {
                require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'GatorCacheWarmer.php');
            }
            $this->cacheWarmer = new GatorCacheWarmer(site_url());
        }
        return $this->cacheWarmer;
    }

    protected function getKeyForUri($uri)
    {
        //It's not known in uri mapping if a path is file or dir, so if it isn't a file name assume a dir
        $paths = array_filter(explode('/', $uri), array($this->cache, 'filterPaths'));
        if (empty($paths)) {
            return self::DIRECTORY_INDEX;
        }
        
        if (false === strpos(end($paths), '.')) {
            $paths[] = self::DIRECTORY_INDEX;
        }
        return implode('/', $paths);
    }
}
