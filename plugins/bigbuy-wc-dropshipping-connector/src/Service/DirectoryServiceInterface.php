<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

interface DirectoryServiceInterface
{
    /*
     * Folder management
     */
    public function getLogDir(): string;

    public function getImportFilesDir(): string;

    public function getModuleDir(): string;

    public function getUpdateSqlDir(): string;

    public function getUpdateDir(): string;

    public function getPluginsDir(): string;

    public function getViewsDir(): string;

    public function getTranslationsDir(): string;

    public function createDirectory(string $folder, int $permissions = 0755): void;

    public function removeDirectory(string $folder): void;


    /*
     * File Management
     */
    public function deleteFile(string $filename, ?string $fileDir = null): void;

    public function getFileContent(string $filename, ?string $fileDir = null);

    public function fileExist(string $filename, ?string $fileDir = null): bool;

    public function saveFileContent(string $fileName, string $fileDir, string $content, int $permissions = 0755): bool;

    public function fileRemoteExist(string $filename): bool;
}