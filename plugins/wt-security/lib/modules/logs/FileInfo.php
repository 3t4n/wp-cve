<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

/**
 * WebTotem file info class for Wordpress.
 */
class WebTotemFileInfo{

     /**
     * Checks if the file scanner is usable.
     *
     * @link https://www.php.net/manual/en/class.splfileobject.php
     *
     * @return bool True if PHP class "SplFileObject" is available.
     */
    public static function isSplAvailable()
    {
        return (bool) (
            class_exists('SplFileObject')
            && class_exists('FilesystemIterator')
            && class_exists('RecursiveIteratorIterator')
            && class_exists('RecursiveDirectoryIterator')
        );
    }

    /**
     * Reads a directory and retrieves all its files.
     *
     * @param  string $directory Where to execute the scanner.
     * @param  string $filterby  Either "file" or "directory".
     * @return array             List of files in the specified directory.
     */
    public function getDirectoryTree($directory = '', $filterby = 'file', $recursively = true)
    {
        $files = [];

        if (is_dir($directory) && self::isSplAvailable()) {
            $objects = [];

            // @codeCoverageIgnoreStart
            try {
                if ($recursively) {
                    $flags = FilesystemIterator::KEY_AS_PATHNAME;
                    $flags |= FilesystemIterator::CURRENT_AS_FILEINFO;
                    $flags |= FilesystemIterator::SKIP_DOTS;
                    $flags |= FilesystemIterator::UNIX_PATHS;
                    $objects = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($directory, $flags),
                        RecursiveIteratorIterator::SELF_FIRST,
                        RecursiveIteratorIterator::CATCH_GET_CHILD
                    );
                } else {
                    $objects = new DirectoryIterator($directory);
                }
            } catch (RuntimeException $exception) {
                /* ignore failure */
            }
            // @codeCoverageIgnoreEnd

            foreach ($objects as $fifo) {
                $filepath = $fifo->getRealPath();

                /* check files and directories */
                if ($this->isIgnoredPath($filepath)) {
                    continue;
                }

                try {
                    /* check only files */
                    if ($fifo->isFile()
                        && $filterby === 'file'
                    ) {
                        $files[] = $filepath;
                        continue;
                    }

                    /* check only directories */
                    if ($fifo->isDir()
                        && $filterby === 'directory'
                    ) {
                        $files[] = $filepath;
                        continue;
                    }
                } catch (RuntimeException $e) {
                    // Skip failure
                }
            }

            sort($files);
        }

        return $files;
    }

    /**
     * Ignores files specified by the admins.
     *
     * @param  string $path Path to the file or directory.
     * @return bool         True if the path has to be ignored.
     */
    private function isIgnoredPath($path)
    {
        $shouldBeIgnored = false;
        $ignored_directories = [
            '/wt-security/lib/modules/logs/Scan.php'
        ];

        foreach ($ignored_directories as $ignored) {
            if (strpos($path, $ignored) !== false) {
                $shouldBeIgnored = true;
                break;
            }
        }

        return $shouldBeIgnored;
    }

    /**
     * Returns the content of a file.
     *
     * If the file does not exists or is not readable the method will return
     * false. Make sure that you double check this with a condition using triple
     * equals in order to avoid ambiguous results when the file exists, is
     * readable, but is empty.
     *
     * @param  string $path Relative or absolute path of the file.
     * @return string       Content of the file, false if not accessible.
     */
    public static function fileContent($path = '')
    {
        return (string) (is_readable($path) ? @file_get_contents($path) : '');
    }

}
