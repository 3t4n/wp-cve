<?php
/**
 * Config_Wp
 *
 * A configuration class that uses the built-in WordPress option
 * functionality for storage.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Config_Wp
{
    protected $config = array();
    protected $key;

    public function __construct($key, array $defaults = array())
    {
        $this->config = false === ($options = get_option($this->key = $key)) ? $defaults : (array)$options + $defaults;
    }

    public function get($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : false;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function set($key, $val)
    {
        $this->config[$key] = $val;
    }

    public function setOptions(array $options)
    {
        $this->config = $options;
    }

    public function write($config = null)
    {
        if (isset($config)) {
            $this->config = $config;
        }
        $result = update_option($this->key, $this->config);
        return true;
    }

    public function save($key, $val)
    {
        //combine set and write
        $this->set($key, $val);
        return $this->write();
    }

    public function toArray()
    {
        return $this->config;
    }
}
