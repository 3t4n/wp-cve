<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Cache_Interface;
class Cache implements Cache_Interface
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var string
     */
    private $key;
    /**
     * @param string|null $key
     */
    public function __construct(string $key = null)
    {
        $this->key = $key;
    }
    public function push($value, string $key = null)
    {
        $key = $this->key ?: $key;
        $this->data[$key][] = $value;
    }
    public function set($value, string $key = null)
    {
        $key = $this->key ?: $key;
        $this->data[$key] = $value;
    }
    /**
     * @throws Exception
     */
    public function get(string $key = null)
    {
        $key = $this->key ?: $key;
        if (!isset($this->data[$key])) {
            return null;
        }
        return $this->data[$key];
    }
    public function get_single(string $key = null)
    {
        $key = $this->key ?: $key;
        if (!isset($this->data[$key])) {
            return null;
        }
        return $this->data[$key][0];
    }
    public function clear(string $key = null)
    {
        $key = $this->key ?: $key;
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }
}
