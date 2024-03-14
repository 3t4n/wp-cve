<?php
/**
 * Config_Lite
 *
 * A configuration class that uses the built-in PHP config ini file
 * functionality for storage.
 *
 * Copyright(c) Schuyler W Langdon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Config_Lite
{
    protected $config = array();
    protected $path;
    protected $parseSections;

    public function __construct($path, $default = null, $verify = false, $parseSections = false)
    {
        if (false === ($config = @parse_ini_file($this->path = $path, $this->parseSections = $parseSections))) {
            if (isset($default)) {
                $this->config = $default;
            }
            $this->path = null;
            if ($verify) {
                //unfortunately if you throw an exception in the constructor the class won't load
                throw new InvalidArgumentException(sprintf('Unable to parse config ini file [%s]', $path));
            }
        } else {
            $this->config = $config;
        }
    }

    public function get($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : false;//this should return null
    }

    public function set($key, $val)
    {
        $this->config[$key] = $val;
    }

    public function remove($key)
    {
        if (isset($this->config[$key])) {
            unset($this->config[$key]);
        }
    }

    public function has($key)
    {
        return isset($this->config[$key]);
    }

    public function toArray()
    {
        return $this->config;
    }

    public function write($path = null)
    {
        if (!($usePath = isset($path)) && !isset($this->path)) {
            //fubar
            return false;
        }
        $this->out = array('<?php exit;?>');
        if ($this->parseSections) {
            //sort by sections to avoid random keys entering end sections
            uasort($this->config, array($this, 'sortSections'));
        }
        $this->format($this->config);
        $fp = @fopen($usePath ? $path : $this->path, 'w');
        if ($fp) {
            @flock($fp, LOCK_EX);
            @fwrite($fp, implode("\n", $this->out));
            @flock($fp, LOCK_UN);
            @fclose($fp);
            return true;
        }
        return false;
    }

    public function sortSections($a, $b)
    {
        if ((($isArrayA = is_array($a)) && is_array($b)) || (!is_array($b) && !$isArrayA)) {
            return 0;
        }
        return $isArrayA ? 1 : -1;//at this point isArrayB is mutually exclusive
    }

    public function save($key, $val)
    {
        //combine set and write
        $this->set($key, $val);
        return $this->write();
    }

    protected function format(array $config)
    {
        foreach ($config as $key => $val) {
            if (is_array($val)) {
                $this->out[] = '[' . $key . ']';
                $this->format($val);
            } else {
                $this->out[] = $key . ' = ' . (is_bool($val) ? (int)$val : (is_numeric($val) || ctype_digit($val) ? $val : '"' . $val . '"'));
            }
        }
    }
}
