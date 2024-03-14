<?php

namespace RabbitLoader\SDK;

class File
{
    private $debug = false;
    private $fp = '/';

    public function __construct($fp = '')
    {
        $this->fp  = $fp;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }


    public function fpc($fp, &$data)
    {
        if ($this->debug) {
            $file_updated = file_put_contents($fp, $data, LOCK_EX);
        } else {
            $file_updated = @file_put_contents($fp, $data, LOCK_EX);
        }
        if (!$file_updated && $this->debug) {
            throw new \Exception("could not write file $fp");
        }
        return $file_updated;
    }

    public function fac(&$data)
    {
        if ($this->debug) {
            $file_updated = file_put_contents($this->fp, $data, LOCK_EX | FILE_APPEND);
        } else {
            $file_updated = @file_put_contents($this->fp, $data, LOCK_EX | FILE_APPEND);
        }
        if (!$file_updated && $this->debug) {
            throw new \Exception("could not write file $this->fp");
        }
        return $file_updated;
    }

    public function unlink()
    {
        $file_deleted = false;
        if ($this->debug) {
            $file_deleted  = unlink($this->fp);
        } else {
            $file_deleted  = @unlink($this->fp);
        }

        return $file_deleted;
    }

    public function cleanDir($dir, $max_limit, $offsetSec)
    {
        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*';
        $isBefore = time() - $offsetSec;
        $deleted_count = 0;
        $files = glob($dir); // get all file names
        foreach ($files as $file) {
            $delete = is_file($file);
            if ($offsetSec && filemtime($file) > $isBefore) {
                $delete = false;
            }
            if ($delete && @unlink($file)) {
                ++$deleted_count;
                if ($max_limit > 0 && $deleted_count > $max_limit) {
                    break;
                }
            }
        }
        return $deleted_count;
    }

    public function lockForTime($fp, $mtime)
    {
        try {
            if (file_exists($fp) && (filemtime($fp) > $mtime)) {
                return false;
            }
            return $this->lock($fp);
        } catch (\Throwable $e) {
            Exc:: catch($e);
        } catch (\Exception $e) {
            Exc:: catch($e);
        }
    }

    public function lock($fp)
    {
        try {
            return touch($fp);
        } catch (\Throwable $e) {
            Exc:: catch($e);
        } catch (\Exception $e) {
            Exc:: catch($e);
        }
    }

    public function isLocked($fp, $mtime)
    {
        try {
            if (file_exists($fp) && filemtime($fp) > $mtime) {
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            Exc:: catch($e);
        } catch (\Exception $e) {
            Exc:: catch($e);
        }
        return false;
    }

    public function countFiles($dir)
    {
        if (!is_dir($dir)) {
            return 0;
        }
        $fi = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $fcount = iterator_count($fi) / 2; ///2 cause content and header
        return round($fcount);
    }

    public function fgc($length = 5000)
    {
        if (empty($this->fp) || !file_exists($this->fp)) {
            return '';
        }
        return file_get_contents($this->fp, false, null, 0, $length);
    }
}
