<?php
/**
 * Created by PhpStorm.
 * Date: 6/8/18
 * Time: 12:00 PM
 */
namespace Hfd\Woocommerce;

class Filesystem
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads';
    }

    /**
     * @param string $message
     * @param string|null $fileName
     */
    public function writeLog($message, $fileName = null)
    {
        if (!$fileName) {
            $fileName = 'log_'. date('Y_m') . '.log';
        } else {
            $fileName = $fileName .'-'. date('Y_m') . '.log';
        }

        if (!$log = $this->createFile('logs', $fileName)) {
            return;
        }

        $content = PHP_EOL . date('Y-m-d H:i:s') ." : ". $message;

        file_put_contents($log, $content, FILE_APPEND);
    }

    /**
     * @param string $content
     * @param string|null $prefix
     */
    public function writeSession($content, $prefix = null)
    {
        $sessionFile = $this->_getSessionFile($prefix);

        if (!$sessionFile = $this->createFile('sessions', $sessionFile)) {
            return;
        }

        file_put_contents($sessionFile, $content);
    }

    /**
     * @param string|null $prefix
     * @return string|null
     */
    public function readSession($prefix = null)
    {
        if ($sessionFile = $this->createFile('sessions', $this->_getSessionFile($prefix))) {
            return file_get_contents($sessionFile);
        }

        return null;
    }

    /**
     * @param string|null $prefix
     */
    public function clearSession($prefix = null)
    {
        if ($sessionFile = $this->createFile('sessions', $this->_getSessionFile($prefix))) {
            @unlink($sessionFile);
        }
    }

    /**
     * @param string|null $prefix
     * @return string
     */
    protected function _getSessionFile($prefix)
    {
        $sessionFile = wp_get_session_token();

        if ($prefix) {
            $sessionFile = $prefix .'_'. $sessionFile;
        }

        return $sessionFile;
    }

    /**
     * @param string $folder
     * @param string $fileName
     * @return bool|string
     */
    public function createFile($folder, $fileName)
    {
        if (!is_writable($this->basePath)) {
            return false;
        }

        $dir = $this->basePath . DIRECTORY_SEPARATOR . trim($folder, DIRECTORY_SEPARATOR);

        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            return false;
        }

        $file = $dir . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($file) && !touch($file)) {
            false;
        }

        return $file;
    }

    /**
     * @param string $folder
     * @param string $fileName
     * @return string
     */
    public function getFilePath($folder, $fileName)
    {
        return implode(DIRECTORY_SEPARATOR, array(
            $this->basePath,
            $folder,
            $fileName
        ));
    }
}