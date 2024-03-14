<?php

namespace CODNetwork\Services;

class CODN_File_Log_Service
{
    const DIR_LOG_NAME = 'codnetwork';

    public function getDirectoryPath(): string
    {
        $uploadDir = wp_upload_dir();
        $uploadDir = $uploadDir['basedir'];

        return sprintf('%s/%s', $uploadDir, self::DIR_LOG_NAME);
    }

    public function createPath(): string
    {
        $path = $this->getDirectoryPath();
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    public function getCurrentFillePath(): string
    {
        $path = $this->createPath();

        return sprintf('%s/%s-%s.log', $path, self::DIR_LOG_NAME, date('Y-m-d'));
    }

    /**
     * @return string
     */
    public function getCurrentFileName(): string
    {
        return sprintf('%s-%s.log', self::DIR_LOG_NAME, date('Y-m-d'));
    }

    /**
     * @param string|null $fileName
     * @return string|null
     */
    public function getLogFile(?string $fileName = null): ?string
    {
        $path = $this->getDirectoryPath();

        if (!empty($fileName)) {
            $fileName = sprintf('%s/%s', $path, $fileName);
        }

        if (empty($fileName)) {
            $fileName = sprintf('%s/%s-%s.log', $path, self::DIR_LOG_NAME, date('Y-m-d'));
        }

        if (!file_exists($fileName)) {
            return null;
        }

        if (filesize($fileName) == 0) {
            return null;
        }

        $fileOpen = fopen($fileName, 'r') or die('Unable to open file!');
        $readFile = fread($fileOpen, filesize($fileName));
        fclose($fileOpen);

        return $readFile;
    }

    public function getLogFiles(): array
    {
        $path = $this->getDirectoryPath();

        $files = array_diff(scandir($path), ['.', '..']);
        if (!is_array($files)) {
            return [];
        }

        return $files;
    }

    public function codn_delete_file_log(?string $logfile = null): bool
    {
        if ($logfile === null) {
            return false;
        }

        $existFile = $this->verifyFileExists($logfile);
        if (!$existFile) {
            return false;
        }

        $path = $this->getDirectoryPath();
        $filePath = sprintf('%s/%s', $path, $logfile);
        unlink($filePath);

        return true;
    }

    private function verifyFileExists(string $logfile): bool
    {
        $path = sprintf('%s/%s', $this->getDirectoryPath(), $logfile);

        return file_exists($path);
    }
}
