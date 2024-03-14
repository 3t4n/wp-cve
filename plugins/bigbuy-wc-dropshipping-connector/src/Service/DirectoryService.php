<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Enum\StatusTypes;

class DirectoryService implements DirectoryServiceInterface
{
    private const HTACCESS_FILE_CONTENT = 'Order deny,allow
                            Deny from all
                            Allow from ::1
                            Allow from localhost
                            Allow from 127.0.0.1
                            Allow from 90.161.45.249
                            Allow from 176.98.223.114';

    private const HTACCESS_FILE_NAME = '.htaccess';

    /** @var DirectoryService */
    private static $instance;

    /**
     * @return DirectoryService
     */
    public static function getInstance(): DirectoryService
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $folder
     */
    public function removeDirectory(string $folder): void
    {
        if (!is_dir($folder)) {
            return;
        }

        $dir = new \DirectoryIterator($folder);
        $loggerService = new LoggerService();

        foreach ($dir as $item) {
            if ($item->isDot()) {
                continue;
            }

            if ($item->isDir()) {
                $this->removeDirectory($item->getPathname());
                $loggerService->getInstance()->info('Removing directory '.$item->getPathname());
                rmdir($item->getPathname());

                continue;
            }

            $loggerService->getInstance()->info('Removing file '.$item->getPathname());

            try {
                unlink($item->getPathname());
            } catch (\Exception $unlinkException) {
                $loggerService->getInstance()->info('Failed executing unlink '.$unlinkException->getMessage());
            }
        }

        rmdir($folder);
    }

    public function getUploadDir()
    {
        $uploadDir = wp_upload_dir();

        return $uploadDir['basedir'];
    }

    /**
     * @return string|null
     */
    public function getVarDir(): string
    {
        return $this->getUploadDir().'/mip-connector';
    }

    /**
     * @return string|null
     */
    public function getLogDir(): string
    {
        return $this->getVarDir().'/logs';
    }

    /**
     * @return string|null
     */
    public function getImportFilesDir(): string
    {
        return $this->getVarDir().'/importFiles';
    }

    /**
     * @return string
     */
    public function getTranslationsDir(): string
    {
        return MipWcConnector::MODULE_NAME.'/app/translations';
    }

    /**
     * @return string
     */
    public function getViewsDir(): string
    {
        return $this->getModuleDir().'/app/views';
    }

    /**
     * @return string
     */
    public function getModuleDir(): string
    {
        return __DIR__.'/../..';
    }

    /**
     * @return string
     */
    public function getUploadDirByCurrentYear(): string
    {
        $year = \date('Y');

        return $this->getUploadDirByYear((int)$year);
    }

    /**
     * @param int|null $year
     * @return string
     */
    public function getUploadDirByYear(int $year): string
    {
        return $this->getUploadDir().'/'.$year;
    }

    /**
     * @return string
     */
    public function getPluginsDir(): string
    {
        return $this->getModuleDir().'/../';
    }

    /**
     * @return string
     */
    public function getUpdateDir(): string
    {
        return $this->getModuleDir().'/upgrade';
    }

    /**
     * @return string
     */
    public function getUpdateSqlDir(): string
    {
        return $this->getModuleDir().'/upgrade/sql';
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return get_home_path();
    }

    /**
     * @param string $fileName
     * @param string $fileDir
     * @param string $content
     * @param int $permissions
     * @return bool
     * @throws \Exception
     */
    public function saveFileContent(string $fileName, string $fileDir, string $content, int $permissions = 0755): bool
    {
        $this->createDirectory($fileDir);

        @file_put_contents($fileDir.'/'.$fileName, $content);

        return @chmod($fileDir.'/'.$fileName, $permissions);
    }

    /**
     * @param string $filename
     * @param string|null $fileDir
     * @throws \Exception
     */
    public function deleteFile(string $filename, ?string $fileDir = null): void
    {
        if ($fileDir) {
            $filename = $fileDir.'/'.$filename;
        }

        @unlink($filename);
    }

    /**
     * @param string $folder
     * @param int $permissions
     * @throws \Exception
     */
    public function createDirectory(string $folder, int $permissions = 0755): void
    {
        $oldPermissions = umask(0000);

        if (!@mkdir($folder, $permissions, true) && !is_dir($folder)) {
            throw new \Exception($folder.' could not be created');
        }

        @chmod($folder, $permissions);
        umask($oldPermissions);
    }

    public function createHtaccessIfNotExists()
    {
        $logDir = $this->getLogDir();
        $importFilesDir = $this->getImportFilesDir();

        if (!file_exists($logDir.'/'.self::HTACCESS_FILE_NAME)) {
            $this->createDirectory($logDir);
            file_put_contents($logDir.'/'.self::HTACCESS_FILE_NAME, self::HTACCESS_FILE_CONTENT);
        }
        @chmod($logDir.'/'.self::HTACCESS_FILE_NAME, 0664);

        if (!file_exists($importFilesDir.'/'.self::HTACCESS_FILE_NAME)) {
            $this->createDirectory($importFilesDir);
            file_put_contents($importFilesDir.'/'.self::HTACCESS_FILE_NAME, self::HTACCESS_FILE_CONTENT);
        }
        @chmod($importFilesDir.'/'.self::HTACCESS_FILE_NAME, 0664);
    }

    public function setFolderPermissionRecursively(string $folder, int $permissions = 0755)
    {
        $dir = new \DirectoryIterator($folder);

        foreach ($dir as $item) {
            if ($item->isDir() && !$item->isDot()) {
                @chmod($item->getPathname(), $permissions);
            }
        }
    }

    /**
     * @param string $path
     * @param int|null $ownerId
     * @throws \Exception
     */
    public function checkPathOwnership(string $path, int $ownerId = null): void
    {
        $existsGetMyUid = \function_exists('getmyuid');
        $existsFileOwner = \function_exists('fileowner');

        if (!$existsGetMyUid && !$existsFileOwner) {
            throw new \Exception('Can not get owner UID');
        }

        $pluginFileOwner = $existsGetMyUid === true ? (int)\getmyuid() : null;

        if ($pluginFileOwner === null) {
            $pluginFileOwner = $existsFileOwner === true ? (int)\fileowner($path) : null;
        }

        $ownerId = $ownerId ?? (int)$pluginFileOwner;
        $dir = new \DirectoryIterator($path);

        foreach ($dir as $item) {
            if ($ownerId !== $item->getOwner()) {
                throw new \Exception('Not same user');
            }

            if ($item->isDir() && !$item->isDot()) {
                $this->checkPathOwnership($item->getPathname(), $ownerId);
            }
        }
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function fileRemoteExist(string $filename): bool
    {
        if (empty($filename) || \filter_var($filename, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $fileHeaders = @get_headers($filename);

        return is_array($fileHeaders) && isset($fileHeaders[0]) && !mb_stripos($fileHeaders[0], StatusTypes::HTTP_NOT_FOUND);
    }

    /**
     * @param string $filesDir
     * @return \DirectoryIterator|null
     */
    public function getFilesByRequiredDir(string $filesDir): ?\DirectoryIterator
    {
        try {
            return new \DirectoryIterator($filesDir);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $filename
     * @param string|null $fileDir
     * @return bool
     */
    public function fileExist(string $filename, ?string $fileDir = null): bool
    {
        if ($fileDir) {
            $filename = $fileDir.'/'.$filename;
        }

        return file_exists($filename);
    }

    /**
     * @param string $filename
     * @param string|null $fileDir
     * @return string
     */
    public function getFileContent(string $filename, ?string $fileDir = null)
    {
        if ($fileDir) {
            $filename = $fileDir.'/'.$filename;
        }

        return file_get_contents($filename);
    }

    /**
     * @return bool|null
     */
    public function initializeWPFilesystem()
    {
        return WP_Filesystem();
    }

    /**
     * @param string $file
     * @param mixed $to
     * @return true|\WP_Error
     */
    public function unzipFile(string $file, string $to)
    {
        return unzip_file($file, $to);
    }

    /**
     * @param mixed $thing
     * @return bool
     */
    public function isWPError($thing): bool
    {
        return is_wp_error($thing);
    }
}