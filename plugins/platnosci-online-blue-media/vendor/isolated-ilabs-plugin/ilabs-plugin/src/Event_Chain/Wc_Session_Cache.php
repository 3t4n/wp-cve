<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Cache_Interface;
use WC_Session;
class Wc_Session_Cache implements Cache_Interface
{
    /**
     * @var WC_Session
     */
    private $wc_session;
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
        $stack = $this->get_wc_session()->get($key);
        if (!\is_array($stack)) {
            $stack = [];
        }
        $stack[] = $value;
        $this->get_wc_session()->set($key, $stack);
        //var_dump($key);die;
    }
    /**
     * @throws Exception
     */
    public function get(string $key = null)
    {
        $key = $this->key ?: $key;
        return $this->get_wc_session()->get($key);
    }
    /**
     * @return WC_Session
     */
    private function get_wc_session() : WC_Session
    {
        if (!$this->wc_session) {
            $this->wc_session = WC()->session;
        }
        return $this->wc_session;
    }
    public function set($value, string $key = null)
    {
        $key = $this->key ?: $key;
        $this->get_wc_session()->set($key, $value);
    }
    public function get_single(string $key = null)
    {
        $key = $this->key ?: $key;
        $value = $this->get_wc_session()->get($key);
        if (!\is_array($value)) {
            return null;
        }
        return $value[$key][0];
    }
    /**
     * @throws Exception
     */
    public function clear(string $key = null)
    {
        $key = $this->key ?: $key;
        if ($this->get($key)) {
            $this->get_wc_session()->__unset($key);
        }
    }
}
