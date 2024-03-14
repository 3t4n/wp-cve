<?php

namespace RabbitLoader\SDK;

/**
 * Polyfills
 */
if (!defined('JSON_INVALID_UTF8_IGNORE')) {
    define('JSON_INVALID_UTF8_IGNORE', 0); //@since PHP 7.2
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

class RabbitLoader
{
    private $storageDirectory = '';
    private $debug = false;
    private $request;

    public function __construct($licenseKey, $storageDirectory = '/tmp/rabbitloader')
    {
        $this->storageDirectory = $storageDirectory;
        $this->request = new Request($licenseKey, $this->storageDirectory);
        Exc::setFile($this->storageDirectory, false);
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
        $this->request->setDebug($this->debug);
        Exc::setFile($this->storageDirectory, $this->debug);
    }

    /**
     * Pass array of param names to ignore for caching. The cache will be served by ignoring the parameters
     */
    public function ignoreParams($paramNames)
    {
        $this->request->ignoreParams($paramNames);
    }

    /**
     * Pass array of cookie names. If any cookie is found, the cache will be skipped
     */
    public function skipForCookies($cookieNames)
    {
        $this->request->skipForCookies($cookieNames);
    }

    /**
     * Pass array of path patterns. If any path match is found, the cache will be skipped
     */
    public function skipForPaths($pathPatterns)
    {
        $this->request->skipForPaths($pathPatterns);
    }

    /**
     * To skip optimization and caching of current request. For example, later in a page you discover the page does not exist or it's a temporary search results page that should not be optimized, call this method.
     * Optionally you can pass a reason that will reflect in page headers
     */
    public function ignoreRequest($reason = '')
    {
        $this->request->ignoreRequest($reason);
    }

    public function process()
    {
        $this->request->process();
    }

    /**
     * When a page is modified, call this. It is responsibility of the caller to call this multiple times if the changes impacts other URLs, for example, change on a page may trigger home page content refresh as well.
     * @param string $url - The page for which content os changed
     * @param string $variant - The variant (same as used by setVariant())
     */
    public function onContentChange($url, $variant = [])
    {
        $cacheFile = new Cache($url, $this->storageDirectory);
        $cacheFile->setDebug($this->debug);
        $cacheFile->setVariant($variant);
        return $cacheFile->invalidate();
    }

    /**
     * Purge external systems when RabbitLoader updates a page
     * Example - $cb = function($url){//do purge}
     */
    public function registerPurgeCallback($cb)
    {
        $this->request->registerPurgeCallback($cb);
    }

    /**
     * Delete cached file if exists for a given URL
     * @return int deleted cache count
     */
    public function delete($url)
    {
        $cacheFile = new Cache($url, $this->storageDirectory);
        $cacheFile->setDebug($this->debug);
        return $cacheFile->delete(Cache::TTL_LONG);
    }

    /**
     * Delete all cached files and returns the count
     * @return int deleted cache count
     */
    public function deleteAll()
    {
        $cacheFile = new Cache('', $this->storageDirectory);
        $cacheFile->setDebug($this->debug);
        return $cacheFile->deleteAll();
    }

    /**
     * setVariant sets additional keys to the cached file. For example, if the website shows different content based on country code, country code can be one of the variant to set here.
     * The more keys is set here, cache hit ratio will reduce.
     * If a page has two variants, based on currency and viewer device, this can be set - setVariant(["currency"=>"USD", "screen"=>"MOBILE"]) or setVariant(["currency"=>"GBP", "screen"=>"DESKTOP"])
     * setVariant must be called before process()
     */
    public function setVariant($variant)
    {
        $this->request->setVariant($variant);
    }

    /**
     * Returns the number of URLs for which cache exists
     */
    public function getCacheCount()
    {
        $cacheFile = new Cache('', $this->storageDirectory);
        $cacheFile->setDebug($this->debug);
        return $cacheFile->getCacheCount();
    }

    /**
     * Returns if the page was warmup
     */
    public function isWarmUp()
    {
        return $this->request->isWarmUp();
    }

    /**
     * Activate ME mode
     */
    public function setMeMode()
    {
        return $this->request->setMeMode();
    }

    /**
     * Set exc catch
     */
    public function excCatch($e, $data = [], $limit = 8)
    {
        Exc:: catch($e, $data, $limit);
    }

    public function setPlatform($data)
    {
        return $this->request->setPlatform($data);
    }
}
