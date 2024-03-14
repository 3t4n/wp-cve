<?php
/**
 * @category Betanet
 * @copyright Betanet (https://www.betanet.co.il/)
 */
namespace Hfd\Woocommerce;

class Cache
{
    protected $isLoaded = false;

    protected $cached = array();

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (!$this->isLoaded) {
            $this->loadCache();
        }

        return isset($this->cached[$key]) ? $this->cached[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Cache
     */
    public function save($key, $value)
    {
        $this->cached[$key] = $value;

        return $this->_save();
    }

    /**
     * @param string $key
     * @return Cache
     */
    public function remove($key)
    {
        if (isset($this->cached[$key])) {
            unset($this->cached[$key]);
            return $this->_save();
        }

        return $this;
    }

    /**
     * @return Cache
     */
    protected function _save()
    {
        $cachePath = $this->getCacheFile();
        if (!file_exists($cachePath)) {
            $cachePath = $this->createCacheFile();
        }

        if (!$cachePath) {
            return $this;
        }

        $data = json_encode($this->cached);
        file_put_contents($cachePath, $data);

        return $this;
    }

    /**
     * @return Cache
     */
    protected function loadCache()
    {
        $cachePath = $this->getCacheFile();

        if(!file_exists($cachePath)) {
            $cachePath = $this->createCacheFile();
        }

        if (!$cachePath) {
            $this->isLoaded = true;
            return $this;
        }

        if ($this->isExpired($cachePath)) {
            $this->cleanCache();
            $this->isLoaded = true;
            return $this;
        }

        $data = file_get_contents($cachePath);
        $this->cached = json_decode($data, true);

        return $this;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected function isExpired($file)
    {
        $creationTime = filectime($file);

        return (time() - $creationTime) > 86400;
    }

    /**
     * @return bool|string
     */
    protected function createCacheFile()
    {
        /** @var \Hfd\Woocommerce\Filesystem $fileSystem */
        $fileSystem = Container::get('Hfd\Woocommerce\Filesystem');

        return $fileSystem->createFile('cache', 'epost_spots');
    }

    /**
     * @return string
     */
    protected function getCacheFile()
    {
        /** @var \Hfd\Woocommerce\Filesystem $fileSystem */
        $fileSystem = Container::get('Hfd\Woocommerce\Filesystem');
        return $fileSystem->getFilePath('cache','epost_spots');
    }

    /**
     * @return Cache
     */
    protected function cleanCache()
    {
        $cachePath = $this->getCacheFile();

        if (file_exists($cachePath) && is_writeable($cachePath)) {
            unlink($cachePath);
        }

        return $this;
    }
}