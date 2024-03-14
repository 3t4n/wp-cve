<?php
/**
 * Watchful recursive file listing.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Files;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Watchful recusive file listing class.
 */
class RecursiveListing
{


    const CACHE_DURATION = 300;
    const CACHE_GROUP = 'watchful.audit.recursiveListing';

    /**
     * Get the list of all files and directories inside a given directory
     *
     * @param string $path The file path.
     * @param bool $use_cache Flag to use cache.
     *
     * @return \stdClass An object with 2 keys "files" and "dirs" being arrays of paths to the items found
     */
    public function get_structure($path, $use_cache = true)
    {
        // Try to load the structure for this path from the cache.
        if ($use_cache) {
            $cached_structure = wp_cache_get($path, self::CACHE_GROUP);

            if (is_object($cached_structure)) {
                return $cached_structure;
            }
        }

        $structure = new \stdClass();
        $structure->dirs = array();
        $structure->files = array();
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (!@is_readable($file->getPathname())) {
                continue;
            }
            if ($file->isDir()) {
                $structure->dirs[] = $file->getRealpath();
            } else {
                $structure->files[] = $file->getRealpath();
            }
        }

        $structure->files = array_unique($structure->files);
        $structure->files = array_values($structure->files);

        $structure->dirs = array_unique($structure->dirs);
        $structure->dirs = array_values($structure->dirs);

        // Save the structure in cache for next audit actions.
        wp_cache_set($path, $structure, self::CACHE_GROUP, self::CACHE_DURATION);

        return $structure;
    }

    /**
     * Get non core files.
     *
     * @param \stdClass $structure The file structure.
     * @param array $hashes The file hashes.
     *
     * @return array
     */
    public function get_non_core_files($structure, $hashes)
    {
        $hash_paths = array();
        $non_core_files = array();

        foreach ($hashes as $hash) {
            $hash_paths[] = $hash[0];
        }

        foreach ($structure->files as $file) {
            // Remove path base.
            $hash_base_path = str_replace(ABSPATH.'/', '', $file);
            if (!in_array($hash_base_path, $hash_paths, true)) {
                $non_core_files[] = $file;
            }
        }

        return $non_core_files;
    }

    /**
     * This method is a shortcut to clear the cache for a given path.
     *
     * @param string $path The file path.
     */
    public function clear_path_cache($path)
    {
        wp_cache_delete($path, self::CACHE_GROUP);
    }

}
