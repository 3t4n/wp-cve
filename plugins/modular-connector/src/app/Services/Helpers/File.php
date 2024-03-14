<?php

namespace Modular\Connector\Services\Helpers;

use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use Modular\ConnectorDependencies\Symfony\Component\Finder\Finder;
use Modular\ConnectorDependencies\Symfony\Component\Finder\SplFileInfo;

class File
{
    /**
     * @param string $path
     * @param array $excluded
     * @return Finder
     */
    public static function getFinder(string $path, array $excluded): Finder
    {
        $finder = new Finder();

        return $finder
            ->followLinks()
            ->ignoreDotFiles(false)
            ->ignoreUnreadableDirs()
            ->ignoreVCS(true)
            ->in($path)
            ->filter(function (\SplFileInfo $file) use ($excluded) {
                return file_exists($file->getRealPath()) &&
                    $file->isReadable() &&
                    !static::checkIsExcluded($file, $excluded);
            });
    }

    /**
     * Check if a file has been marked as excluded
     *
     * @param SplFileInfo $item
     * @param array $excluded
     * @return bool
     */
    public static function checkIsExcluded(SplFileInfo $item, array $excluded)
    {
        $excluded = Collection::make($excluded);

        return $excluded->some(function ($excludeItem) use ($item) {
            return Str::startsWith($item->getPathname(), $excludeItem);
        });
    }

    /**
     * @param SplFileInfo $item
     * @return array
     */
    public static function mapItem(SplFileInfo $item)
    {
        /**
         * @var \SplFileInfo $item
         */
        $type = $item->getType();

        if ($item->isLink()) {
            $type = is_dir($item->getRealPath()) ? 'dir' : $type;
        }

        return [
            'name' => $item->getBasename(),
            'path' => str_ireplace(ABSPATH, '', $item->getPathname()),
            'realpath' => $item->getRealPath() ?? $item->getPathname(),
            'type' => $type,
            'size' => !$item->isDir() ? $item->getSize() : 0
        ];
    }

    /**
     * Returns an object representing the provided folder structure as an object in which keys with content value
     * represent folders and keys with 'null' content value represent files.
     *
     * @param string $path
     * @param array $excluded
     * @return Collection
     */
    public static function getTree(string $path, array $excluded = []): Collection
    {
        $files = static::getFinder($path, $excluded)
            ->sortByType()
            ->depth('== 0');

        return Collection::make($files)
            ->map(function ($item) use ($excluded) {
                return static::mapItem($item);
            })
            ->values();
    }

    /**
     * Open a zip archive
     *
     * @param string $path
     * @return \ZipArchive
     * @throws \ErrorException
     */
    public static function openZip(string $path): \ZipArchive
    {
        $zip = new \ZipArchive();

        if (!file_exists($path)) {
            $opened = $zip->open($path, \ZipArchive::CREATE);
        } else {
            $opened = $zip->open($path);
        }

        if ($opened !== true) {
            throw new \ErrorException($zip->getStatusString());
        }

        return $zip;
    }

    /**
     * Add files to given zip
     *
     * @param \ZipArchive $zip
     * @param array $item
     * @return void
     */
    public static function addToZip(\ZipArchive $zip, array $item): void
    {
        if ($item['type'] === 'dir') {
            $zip->addEmptyDir(ltrim($item['path'], DIRECTORY_SEPARATOR));
        } else {
            $zip->addFile($item['realpath'], ltrim($item['path'], DIRECTORY_SEPARATOR));
        }
    }

    /**
     * Close ZipArchive
     *
     * @param \ZipArchive $zip
     * @return bool
     * @throws \ErrorException
     */
    public static function closeZip(\ZipArchive $zip): bool
    {
        $closed = $zip->close();

        if (!$closed) {
            throw new \ErrorException($zip->getStatusString());
        }

        return $closed;
    }
}
