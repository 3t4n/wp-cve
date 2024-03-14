<?php
/**
 * Cache Lite Classic. Php 5.2 compatible.
 *
 * Fast, light and safe Cache. Class CacheLite is a fast, light and safe cache system.
 * It's optimized for file containers. It is fast and safe (because it uses file
 * locking and/or anti-corruption tests). Supports independent ttl per set key.
 *
 * Based upon the Pear Cache_Lite package.
 * @author Fabien MARTY <fab@php.net>
 *
 * Copyright (c) Schuyler W Langdon.
 *
 * For the full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
*/

class Reo_Classic_CacheLite
{
    // --- Protected properties ---

    /**
    * Directory where to put the cache files (no trailing slash)
    *
    * @var string $cacheDir
    */
    protected $cacheDir;

    /**
    * Enable / disable caching
    *
    * (can be very usefull for the debug of cached scripts)
    *
    * @var boolean $_caching
    * @depricated worst case a null cache can be stubbed
    */
    protected $_caching = true;

    /**
    * Cache lifetime (in seconds)
    *
    * If 0, the cache is valid forever.
    *
    * @var int $lifeTime
    */
    protected $lifeTime = 0;

    /**
    * Enable / disable fileLocking
    *
    * (can avoid cache corruption under bad circumstances)
    *
    * @var boolean $_fileLocking
    */
    protected $_fileLocking = true;

    /**
    * Timestamp of the last valid cache
    *
    * @var int $refreshTime
    */
    protected $refreshTime;

    /**
    * File name (with path)
    *
    * @var string $_file
    */
    protected $_file;

    /**
    * File path
    *
    * @var string $filePath
    */
    protected $filePath;

    /**
    * File name (without path)
    *
    * @var string $_fileName
    */
    protected $_fileName;

    /**
    * File ext
    * 
    * An optional file extension that may be appended to cache file names.
    *
    * @var string $fileExt
    */
    protected $fileExt;

    /**
    * Enable / disable write control (the cache is read just after writing to detect corrupt entries)
    *
    * Enable write control will lightly slow the cache writing but not the cache reading
    * Write control can detect some corrupt cache files but maybe it's not a perfect control
    *
    * @var boolean $_writeControl
    */
    protected $_writeControl = true;

    /**
    * Enable / disable read control
    *
    * If enabled, a control key is embeded in cache file and this key is compared with the one
    * calculated after the reading.
    *
    * @var boolean $readControl
    */
    protected $readControl = true;

    /**
    * Type of read control (only if read control is enabled)
    *
    * Available values are :
    * 'md5' for a md5 hash control (best but slowest)
    * 'crc32' for a crc32 hash control (lightly less safe but faster, better choice)
    * 'strlen' for a length only test (fastest)
    * 'none' provides no check, but enables ttl on a file by file basis (fastester)
    *
    * @var boolean $readControlType
    */
    protected $readControlType = 'crc32';

    /**
    * Debug flag
    *
    * @var bool $debug
    */
    protected $debug = false;
    
    /**
    * File Name hash mode
    *
    * At default level, you can use any cache id or group name
    * if set to 'none', it can be faster but cache ids and group names
    * will be used directly in cache file names so be carefull with
    * special characters...
    *
    * @var string $fileNameHashMode
    */
    protected $fileNameHashMode = 'standard';

    /**
    * Enable / disable automatic serialization
    *
    * it can be used to save directly datas which aren't strings
    * (but it's slower)
    *
    * @var boolean $_serialize
    */
    protected $_automaticSerialization = false;
    
    /**
    * Nested directory level
    *
    * Set the hashed directory structure level. 0 means "no hashed directory
    * structure", 1 means "one level of directory", 2 means "two levels"...
    * This option can speed up Cache_Lite only when you have many thousands of
    * cache file. Only specific benchs can help you to choose the perfect value
    * for you. Maybe, 1 or 2 is a good start.
    *
    * @var int $_hashedDirectoryLevel
    * @depricated for nginx style cache dirs
    */
    //var $_hashedDirectoryLevel = 0;
    
    /**
    * Umask for hashed directory structure
    *
    * @var int $hashedDirectoryUmask
    */
    protected $hashedDirectoryUmask = 0700;

    /**
     * $availableOptions
     *
     * @var array of protected properties that can be set
     */
    protected static $availableOptions = array('hashedDirectoryUmask' => true, 'automaticSerialization' => true, 'fileNameHashMode' => true, 'lifeTime' => true, 'fileExt' => true, 'fileLocking' => true, 'writeControl' => true, 'readControl' => true, 'readControlType' => true, 'debug' => true);
    
    // --- Public methods ---

    /**
    * Constructor
    *
    * $options is an assoc. Available options are :
    * $options = array(
    *     'cacheDir' => directory where to put the cache files (string),
    *     'caching' => enable / disable caching (boolean),
    *     'lifeTime' => cache lifetime in seconds (int),
    *     'fileLocking' => enable / disable fileLocking (boolean),
    *     'writeControl' => enable / disable write control (boolean),
    *     'readControl' => enable / disable read control (boolean),
    *     'readControlType' => type of read control 'crc32', 'md5', 'strlen' (string),
    *     'automaticSerialization' => enable / disable automatic serialization (boolean),
    *     'hashedDirectoryUmask' => umask for hashed directory structure (int),
    * );
    *
    * If sys_get_temp_dir() is available and the
    * 'cacheDir' option is not provided in the
    * constructor options array its output is used
    * to determine the suitable temporary directory.
    *
    * @see http://de.php.net/sys_get_temp_dir
    * @see http://pear.php.net/bugs/bug.php?id=18328
    *
    * @param array $options options
    * @access public
    */
    public function __construct($cacheDir = null, array $options = null)
    {
        if (isset($options)) {
            foreach ($options as $key => $value) {
                $this->setOption($key, $value);
            }
        }

        if (isset($cacheDir)) {
            if ('/' === substr($cacheDir, -1)) {
                $cacheDir = substr($cacheDir, 0, -1);
            }
            $this->cacheDir = $cacheDir;
        } else {
            $this->cacheDir = sys_get_temp_dir();
        }

        if (!@is_dir($this->cacheDir) && false === @mkdir($this->cacheDir, $this->hashedDirectoryUmask)) {
            throw new \InvalidArgumentException(sprintf('The cache directory [%s] does not exist and could not be created', $this->cacheDir));
        }
    }

    public function __get($name)
    {
        return isset($this->{$name}) ? $this->{$name} : null;
    }

    /**
    * Generic way to set a Cache_Lite option
    *
    * see Cache_Lite constructor for available options
    *
    * @var string $name name of the option
    * @var mixed $value value of the option
    * @access public
    */
    public function setOption($name, $value)
    {
        if (isset(self::$availableOptions[$name])) {
            $this->{$name} = $value;
        }
    }

    /**
    * Test if a cache is available and (if yes) return it
    *
    * @param string $id cache id
    * @param string $group name of the cache group
    * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
    * @return string data of the cache (else : false)
    * @access public
    */
    public function get($id, $group = null)
    {
        $this->_setRefreshTime();
        $this->_setFileName($id, $group);
        //according to php docs this is unecessary
        //clearstatcache();
        if (!@file_exists($this->_file) || 'apache' === $this->fileNameHashMode && is_dir($this->_file)) {
            return null;
        }
        //@note the ttl will be stored for readcontrol and handled in _read, check here against the global ttl or lifetime
        if (!$this->readControl) {
            //the ttl is not saved with the cache, use ttl setting
            if (0 !== $this->lifeTime && false !== ($fileTime = @filemtime($this->_file)) && $fileTime < (time() - $this->lifeTime)) {
                @unlink($this->_file);
                return null;
            }
        }

        $data = $this->_read();

        if (($this->_automaticSerialization) && (is_string($data))) {
            $data = unserialize($data);
        }
        return $data;
    }

    /**
    * Save some data in a cache file
    *
    * @param string $data data to put in cache (can be another type than strings if automaticSerialization is on)
    * @param string $id cache id
    * @param string $group name of the cache group
    * @return boolean true on success, false upon failure and throws exception in debug mode
    * @access public
    */
    public function save($id, $data, $group = null)
    {
        if ($this->_automaticSerialization) {
            $data = serialize($data);
        }
        $this->_setFileName($id, $group, true);

        if ($this->_writeControl) {
            if (!($result = $this->_writeAndControl($data))) {
                // if $res if false, we need to invalidate the cache
                @unlink($this->_file);
                return false;
            }
            return true;
        }
        return $this->_write($data);
    }

    /**
     * set, alias of save with ttl
     *
     * @param $ttl int time to live in seconds
     */
    public function set($key, $value, $ttl = 0, $group = null)
    {
        $this->setLifeTime($ttl);
        $this->save($key, $value, $group);
    }

    /**
     * put, alias of save with minutes param
     *
     * @param $minutes int ttl in minutes
     */
    public function put($key, $value, $minutes = null)
    {
        $this->setLifeTime(isset($minutes) ? 60 * $minutes : 0);
        $this->save($key, $value, null);
    }

    public function has($id, $group = null)
    {
        $this->_setRefreshTime();
        $this->_setFileName($id, $group);

        if (!@file_exists($this->_file)) {
            return false;
        }
        //check if file has expired
        if (!$this->readControl && 0 !== $this->lifeTime && (@filemtime($this->_file) > $this->refreshTime)) {
            return false;
        }
        return true;
    }

    /**
    * Remove a cache file
    *
    * @param string $id cache id
    * @param string $group name of the cache group
    * @param boolean $checkbeforeunlink check if file exists before removing it
    * @return boolean true if no problem
    * @access public
    */
    public function remove($id, $group = null, $checkbeforeunlink = false)
    {
        $this->_setFileName($id, $group);

        if ($checkbeforeunlink && !@file_exists($this->_file)) {
            return true;
        }
        return $this->_unlink($this->_file);
    }

    /**
    * Clean the cache
    *
    * if no group is specified all cache files will be destroyed
    * else only cache files of the specified group will be destroyed
    *
    * @param string $group name of the cache group
    * @param string $mode flush cache mode : 'old', 'ingroup', 'notingroup',
    *                                        'callback_myFunction'
    * @return boolean true if no problem
    * @access public
    */
    public function clean($group = false, $mode = 'ingroup')
    {
        $this->emptyDirs = array();
        $result = $this->_cleanDir($this->cacheDir, $group, $mode);
        //clean up the purged dirs
        if (!empty($this->emptyDirs)) {
            foreach (array_reverse($this->emptyDirs) as $dir) {
                @rmdir($dir);
            }
        }
        return $result;
    }

    public function flush($group = false)
    {
        $this->emptyDirs = array();
        $result = $this->_cleanDir($this->cacheDir, $group, 'ingroup');
        //clean up the purged dirs
        if (!empty($this->emptyDirs)) {
            foreach (array_reverse($this->emptyDirs) as $dir) {
                @rmdir($dir);
            }
        }
        return $result;
    }

    /**
    * Set to debug mode
    *
    * When an error is found, the script will stop and the message will be displayed
    * (in debug mode only).
    *
    * @access public
    */
    public function setDebug($debug = true)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
    * Set a new life time
    *
    * @param int $newLifeTime new life time (in seconds)
    * @access public
    */
    public function setLifeTime($newLifeTime)
    {
        $this->lifeTime = $newLifeTime;
        $this->_setRefreshTime();
    }

    /**
    * Get the life time
    *
    * @return int the life time
    * @access public
    */
    public function getLifeTime()
    {
        return $this->lifeTime;
    }

    /**
    * Returns the file name including the full path
    * from the most recent get, set or has call.
    *
    * @return string the file name
    * @access public
    */
    public function getFileName()
    {
        return isset($this->_file) ? $this->_file : null;
    }

    /**
    * Return the cache last modification time
    *
    * BE CAREFUL : THIS METHOD IS FOR HACKING ONLY !
    *
    * @return int last modification time
    */
    public function lastModified()
    {
        return @filemtime($this->_file);
    }
    
    /**
    * Trigger an exception if debug mode
    *
    * @param string $msg error message
    * @param int $code error code
    * @access public
    */
    public function raiseError($msg, $code)
    {
        if (!$this->debug) {
            return false;
        }
        throw new \RuntimeException(sprintf('Error [%s]: %s', $code, $msg));
    }
    
    /**
     * Extend the life of a valid cache file
     *
     * see http://pear.php.net/bugs/bug.php?id=6681
     *
     * @access public
     */
    public function extendLife()
    {
        @touch($this->_file);
    }

    /**
     * filterPaths
     *
     * Filter relative paths
     */
    public function filterPaths($dir)
    {
        return isset($dir) && '' !== $dir && '..' !== $dir && '.' !== $dir;// && !strstr($dir, '\\');
    }

    // --- Protected methods ---
    
    /**
    * Compute & set the refresh time
    *
    * @access private
    */
    protected function _setRefreshTime()
    {
        $this->refreshTime = (0 === $this->lifeTime) ? null : time() + $this->lifeTime;
    }
    
    /**
    * Remove a file
    *
    * @param string $file complete file path and name
    * @return boolean true if no problem
    * @access private
    */
    protected function _unlink($file)
    {
        $result = false === @unlink($file) ? !$this->debug : true;
        // remove the cache dirs for standard mode
        if ('standard' === $this->fileNameHashMode && @rmdir($this->filePath)) {
            @rmdir(dirname($this->filePath));
        }
        return $result;
    }

    /**
    * Recursive function for cleaning cache file in the given directory
    *
    * @param string $dir directory complete path (with a trailing slash)
    * @param string $group name of the cache group
    * @param string $mode flush cache mode : 'old', 'ingroup', 'notingroup', 'callback_myFunction'
    * @return boolean true if no problem
    * @access private
    */
    protected function _cleanDir($dir, $group = false, $mode = 'ingroup')
    {
        if ('none' === $this->fileNameHashMode) {
            $motif = ($group) ? $group . '_' : false;
        } elseif ('apache' === $this->fileNameHashMode) {
            if (false !== $group && $dir === $this->cacheDir) {
                //group at top level
                $dir .= '/' . $group;
                $this->emptyDirs[] = $dir;//will be emptied
            }
            $motif = false;
        } else {
            //$motif = ($group) ? 'cache_'.md5($group).'_' : 'cache_';
            $motif = false;
            if ($group) {
                $dir .= '/' . substr(($motif = md5($group)), -1) . '/' . substr($motif, -3, 2);
                $motif .= '_';
            }
        }

        if (!($dh = @opendir($dir))) {
            return $this->raiseError('Cache_Lite : Unable to open cache directory !', -4);
        }
        $result = true;
        while (($file = readdir($dh)) !== false) {
            if (($file != '.') && ($file != '..')) {
                //if (substr($file, 0, 6)=='cache_') {
                    $fileName = $dir . '/' . $file;
                    //var_dump(is_file($fileName), $fileName);exit;break 2;
                    if (is_file($fileName)) {
                        switch (substr($mode, 0, 9)) {
                            case 'old':
                                // files older than lifeTime get deleted from cache
                                if (0 !== $this->lifeTime) {
                                    if ((time() - @filemtime($fileName)) > $this->lifeTime) {
                                        $result = ($result and ($this->_unlink($fileName)));
                                    }
                                }
                                break;
                            case 'notingrou':
                                if (strpos($fileName, $motif) === false) {
                                    $result = ($result and ($this->_unlink($fileName)));
                                }
                                break;
                            case 'callback_':
                                $func = substr($mode, 9, strlen($mode) - 9);
                                if ($func($fileName, $group)) {
                                    $result = ($result and ($this->_unlink($fileName)));
                                }
                                break;
                            case 'ingroup':
                            default:
                                if (false === $motif || 0 === strpos($file, $motif)) {
                                    $result = ($result and ($this->_unlink($fileName)));
                                }
                                break;
                        }
                    } elseif ((is_dir($fileName)) && false === $motif) {
                        $result = $result && $this->_cleanDir($this->emptyDirs[] = $fileName, $group, $mode);
                    }
                //}
            }
        }
        return $result;
    }

    /**
    * Make a file name (with path)
    *
    * @param string $id cache id
    * @param string $group name of the group
    * @access private
    */
    protected function _setFileName($id, $group = null, $write = false)
    {
        $this->filePath = $this->cacheDir;
        $fullPath = false;

        if ('none' === $this->fileNameHashMode) {
            //@note this should be depricated
            $this->_fileName = (isset($group) ? $group . '_' : '') . $id;
        } elseif ('apache' === $this->fileNameHashMode) {
            //no hashing, the id will be a path
            $fullPath = (isset($group) ? $group . '/' : '') . $id;
            $paths = array_filter(explode('/', $fullPath), array($this, 'filterPaths'));
            //in apache mode our last path is the file name
            $this->_fileName = array_pop($paths);
            $fullPath = implode('/', $paths);
        } else {
            $hash = md5($id);
            if ($isGroup = isset($group)) {
                $groupHash = md5($group);
            }
            //@note depricated 0 dir level hash
            //$this->_fileName = (isset($group) ? md5($group). '_' : '' )  . md5($id);
            $this->_fileName = ($isGroup ? $groupHash . '_' : '') . $hash;
            $fullPath = substr(($key = $isGroup ? $groupHash : $hash), -1) . '/' . substr($key, -3, 2);
            $paths = explode('/', $fullPath);
        }

        if (false !== $fullPath) {
            if ($write) {
                //create dirs that may not exist
                $dir = $this->cacheDir;
                foreach ($paths as $subPath) {
                    if (!@is_dir($dir .= '/' . $subPath) && !@mkdir($dir, $this->hashedDirectoryUmask)) {
                        return $this->raiseError(sprintf('Could not create cache subPath [%s]', $dir), 100);
                    }
                }
            }
            $this->filePath .= '/' . $fullPath;
        }

        $this->_file = $this->filePath . '/' . $this->_fileName . (isset($this->fileExt) ? $this->fileExt : '');
    }
    
    /**
    * Read the cache file and return the content
    *
    * @return string content of the cache file (else : false or a PEAR_Error object)
    * @access private
    */
    protected function _read()
    {
        $fp = @fopen($this->_file, "rb");
        if ($fp) {
            if ($this->_fileLocking) {
                @flock($fp, LOCK_SH);
            }
            clearstatcache();
            $length = @filesize($this->_file);
/*@depricated function get_magic_quotes_runtime
            $mqr = get_magic_quotes_runtime();
            if ($mqr) {
                set_magic_quotes_runtime(0);
            }*/
            if ($this->readControl) {
                if ('none' === $this->readControlType) {
                    $ttl = @fread($fp, 10);
                    $length -= 10;
                    $hashControl = false;
                } else {
                    $hashControl = @fread($fp, 42);
                    $length -= 42;
                    $ttl = substr($hashControl, 0, 10);
                    $hashControl = substr($hashControl, 10);
                }
            }
            if ($length) {
                $data = @fread($fp, $length);
            } else {
                $data = '';
            }
/*            if ($mqr) {
                set_magic_quotes_runtime($mqr);
            }*/
            if ($this->_fileLocking) {
                @flock($fp, LOCK_UN);
            }
            @fclose($fp);
            if ($this->readControl) {
                if ((false !== $hashControl && $this->_hash($data, $this->readControlType) != $hashControl) || ('0000000000' !== $ttl && time() > (int) $ttl)) {
                    //clean garbage on the fly
                    @unlink($this->_file);
                    return null;
                }
            }
            return $data;
        }
        return $this->raiseError(sprintf('Cache_Lite : Unable to read cache [%s]', $this->_file), -2);
    }
    
    /**
    * Write the given data in the cache file
    *
    * @param string $data data to put in cache
    * @return boolean true if ok (a PEAR_Error object else)
    * @access private
    */
    protected function _write($data)
    {
        $fp = @fopen($this->_file, 'wb');
        if ($fp) {
            if ($this->_fileLocking) {
                @flock($fp, LOCK_EX);
            }
/*@depricated function get_magic_quotes_runtime
            if ($mqr = get_magic_quotes_runtime()) {
                set_magic_quotes_runtime(0);
            }*/
            if ($this->readControl) {
                @fwrite($fp, $prefix = (0 === $this->lifeTime ? '0000000000' : time() + $this->lifeTime) . $this->_hash($data, $this->readControlType) . $data);
            } else {
                @fwrite($fp, $data);
            }
            /*if ($mqr) {
                set_magic_quotes_runtime($mqr);
            }*/
            if ($this->_fileLocking) {
                @flock($fp, LOCK_UN);
            }
            @fclose($fp);
            return true;
        }
        return $this->raiseError(sprintf('Cache_Lite : Unable to write cache file : %s', $this->_file), -1);
    }
       
    /**
    * Write the given data in the cache file and control it just after to avoir corrupted cache entries
    *
    * @param string $data data to put in cache
    * @return boolean true if the test is ok (else : false or a PEAR_Error object)
    * @access private
    */
    public function _writeAndControl($data)
    {
        $result = $this->_write($data);
        if (!$result) {
            return $result;
        }
        $dataRead = $this->_read();

        if (false === $dataRead) {
            return $dataRead;
        }

        return $dataRead == $data;
    }
    
    /**
    * Make a control key with the string containing datas
    *
    * @param string $data data
    * @param string $controlType type of control 'none', 'md5', 'crc32' or 'strlen'
    * @return string control key
    * @access private
    */
    protected function _hash($data, $controlType)
    {
        switch ($controlType) {
        case 'none':
            return '';
        case 'md5':
            return md5($data);
        case 'crc32':
            return sprintf('% 32d', crc32($data));
        case 'strlen':
            return sprintf('% 32d', strlen($data));
        default:
            return $this->raiseError('Unknown controlType ! (available values are only \'md5\', \'crc32\', \'strlen\')', -5);
        }
    }
}
