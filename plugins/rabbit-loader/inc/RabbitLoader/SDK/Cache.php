<?php

namespace RabbitLoader\SDK;

class Cache
{

    const TTL_LONG = "long";
    const TTL_SHORT = "short";

    private $request_url = '';
    private $fp_long = '';
    private $fp_short = '';
    private $debug = false;
    private $rootDir = '';
    private $file;
    private $rlCacheRebuildFlag = '"rlCacheRebuild": "Y"';

    public function __construct($request_url, $rootDir)
    {
        $this->request_url = $request_url;
        $this->rootDir = rtrim($rootDir, DIRECTORY_SEPARATOR); //sep may or may not exist
        $this->rootDir = $this->rootDir . DIRECTORY_SEPARATOR; //ensure sep is always there
        $this->file  = new File();

        $hash = md5($this->request_url);
        $this->setPath($hash);
    }

    /**
     * @param bool $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        $this->file->setDebug($debug);
    }

    private function createDirs()
    {
        $rootDir = rtrim($this->rootDir, DIRECTORY_SEPARATOR);
        if (!is_dir($rootDir)) {
            mkdir($rootDir, 0775, true);
        }

        $rootDir = $rootDir . DIRECTORY_SEPARATOR;

        if (!is_dir($rootDir . self::TTL_LONG)) {
            if (!mkdir($rootDir . self::TTL_LONG, 0775, true)) {
                error_log("rabbitloader failed to create cache directory inside " . $rootDir);
                Util::sendHeader('x-rl-create-dirs: failed', true);
            }
        }
        if (!is_dir($rootDir . self::TTL_SHORT)) {
            if (!mkdir($rootDir . self::TTL_SHORT, 0775, true)) {
                error_log("rabbitloader failed to create cache directory inside " . $rootDir);
                Util::sendHeader('x-rl-create-dirs: failed', true);
            } else {
                //directory created successfully
                $this->addHtaccess();
            }
        }
    }

    private function addHtaccess()
    {
        $loc = $this->rootDir . ".htaccess";
        if (!file_exists($loc)) {
            $content = "deny from all";
            $this->file->fpc($loc, $content);
        }

        return file_exists($loc);
    }

    private function getPathForTTL($ttl, $fileType)
    {
        return $ttl === self::TTL_LONG ? $this->fp_long . '_' . $fileType : $this->fp_short . '_' . $fileType;
    }

    public function exists($ttl)
    {
        return file_exists($this->getPathForTTL($ttl, 'c'));
    }

    public function fresh($ttl, $ts)
    {
        $fp = $this->getPathForTTL($ttl, 'c');
        if ($this->exists($ttl) && $ts && ($ts > 631152000)) {
            $mt = filemtime($fp);
            if ($this->debug) {
                Util::sendHeader('x-rl-mtime: ' . $mt, true);
                Util::sendHeader('x-rl-fresh: ' . $mt . '>' . $ts, true);
                Util::sendHeader('x-rl-fpc: ' . $fp, false);
            }
            if ($mt && ($mt > $ts)) {
                $content = file_get_contents($fp);
                if ($content === false || str_contains($content, $this->rlCacheRebuildFlag)) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    public function delete($ttl)
    {
        $count = 0;
        $fc = $this->getPathForTTL($ttl, 'c');
        $fh = $this->getPathForTTL($ttl, 'h');

        if (is_file($fc) && (($this->debug && unlink($fc)) || @unlink($fc))) {
            $count++;
        }
        if (is_file($fh) && (($this->debug && unlink($fh)) || @unlink($fh))) {
            $count++;
        }
        return $count;
    }

    public function collectGarbage($mtime)
    {
        $this->createDirs();
        $lock = $this->rootDir . 'garbage.lock';
        if (!$this->file->lockForTime($lock, $mtime)) {
            return false;
        }
        $this->file->cleanDir($this->rootDir . self::TTL_LONG, 500, 30 * 24 * 3600);
        $this->file->cleanDir($this->rootDir . self::TTL_SHORT, 500, 1800);
        return true;
    }

    public function deleteAll()
    {
        $count = 0;
        $count += $this->file->cleanDir($this->rootDir . self::TTL_LONG, 0, 0);
        $count += $this->file->cleanDir($this->rootDir . self::TTL_SHORT, 0, 0);
        return $count;
    }

    public function invalidate()
    {
        $fp = $this->getPathForTTL(self::TTL_LONG, 'c');
        if (is_file($fp)) {
            $content = file_get_contents($fp);
            if ($content !== false) {
                $rlModified = '"rlModified": "' . date('c') . '"';
                $content = str_ireplace(['"rlCacheRebuild":"N"', '"rlCacheRebuild": "N"', '"rlModified":""', '"rlModified": ""'], [$this->rlCacheRebuildFlag, $this->rlCacheRebuildFlag, $rlModified, $rlModified], $content);
                $this->file->fpc($fp, $content);
            }
        }
    }


    public function &get($ttl, $type)
    {
        $fp = $this->getPathForTTL($ttl, $type);
        $content = '';
        if (file_exists($fp)) {
            $content = file_get_contents($fp);
        }
        return $content;
    }

    public function serve()
    {
        if ($this->exists(self::TTL_LONG)) {
            $content = file_get_contents($this->getPathForTTL(self::TTL_LONG, 'c'));
            if ($content !== false) {
                if ($this->valid($content)) {
                    if (file_exists($this->getPathForTTL(self::TTL_LONG, 'h'))) {
                        //header is optional
                        $this->sendHeaders(file_get_contents($this->getPathForTTL(self::TTL_LONG, 'h')));
                    }
                    Util::sendHeader('x-rl-cache: hit', true);
                    echo $content;
                    return true;
                }
            }
        }
        return false;
    }

    public function save($ttl, &$content, &$headers)
    {
        $count = 0;
        if (!$this->valid($content)) {
            Util::sendHeader('x-rl-save: invalid-' . $ttl, true);
            return $count;
        }
        $this->createDirs();
        $headers = json_encode($headers, JSON_INVALID_UTF8_IGNORE);
        if ($this->file->fpc($this->getPathForTTL($ttl, 'h'), $headers)) {
            $count++;
        }

        if ($this->file->fpc($this->getPathForTTL($ttl, 'c'), $content)) {
            $count++;
        }
        return $count;
    }

    private function valid(&$chunk)
    {
        if (empty($chunk)) {
            return false;
        }
        if (stripos($chunk, '</html>') !== false || stripos($chunk, '</body>') !== false) {
            return true;
        }
        return false;
    }

    private function sendHeaders($headers)
    {
        $headers_sent = 0;
        if (empty($headers)) {
            return $headers_sent;
        }
        if (!is_array($headers)) {
            $headers_decoded = json_decode($headers, true);
            if ($headers_decoded === false) {
                $e = new \Error(json_last_error_msg());
                Exc:: catch($e, $headers);
            } else {
                $headers = $headers_decoded;
            }
        }
        if (!empty($headers)) {
            foreach ($headers as $key => $values) {
                foreach ($values as $val) {
                    header($key . ':' . $val, false);
                    $headers_sent++;
                }
            }
        }
        return $headers_sent;
    }

    public function setPath($hash)
    {
        $this->fp_long =  $this->rootDir . self::TTL_LONG . DIRECTORY_SEPARATOR . $hash;
        $this->fp_short =  $this->rootDir . self::TTL_SHORT . DIRECTORY_SEPARATOR . $hash;
    }

    public function setVariant($variant)
    {
        if (empty($variant) || !is_array($variant)) {
            return;
        }
        ksort($variant);
        $hash = md5($this->request_url . json_encode($variant));
        $this->setPath($hash);
    }

    public function getCacheCount()
    {
        return $this->file->countFiles($this->rootDir . self::TTL_LONG);
    }

    public function set429()
    {
        $lock = $this->rootDir . '429.lock';
        return $this->file->lock($lock);
    }
    public function get429()
    {
        $lock = $this->rootDir . '429.lock';
        return $this->file->isLocked($lock, strtotime('-15 minutes'));
    }
}
