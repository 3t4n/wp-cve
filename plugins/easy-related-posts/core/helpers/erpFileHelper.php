<?php

/**
 * Easy Related Posts .
 *
 * @package Easy_Related_Posts_Helpers
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link http://example.com
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */

/**
 * File helper class
 *
 * @package Easy_related_posts_helpers
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
class erpFileHelper {

    /**
     * Scans recursivly a folder and returns its contents as assoc array
     *
     * @param string $path
     * @return array
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function dirToArrayRecursive($path) {
        $contents = array();
        // Foreach node in $path
        foreach (scandir($path) as $node) {
            // Skip link to current and parent folder
            if ($node == '.' || $node == '..') {
                continue;
            }
            // Check if it's a node or a folder
            if (is_dir($path . DIRECTORY_SEPARATOR . $node)) {
                // Add directory recursively, be sure to pass a valid path
                // to the function, not just the folder's name
                $contents [$node] = self::dirToArrayRecursive($path . DIRECTORY_SEPARATOR . $node);
            } else {
                // Add node, the keys will be updated automatically
                $contents [] = $node;
            }
        }
        // done
        return $contents;
    }

    /**
     * Scans a folder and returns its contents as array
     *
     * @param string $path
     * @return array
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function dirToArray($path) {
        $contents = array();
        // Foreach node in $path
        foreach (scandir($path) as $node) {
            // Skip link to current and parent folder
            if ($node == '.' || $node == '..')
                continue;
            // Check if it's a node or a folder
            if (is_dir($path . DIRECTORY_SEPARATOR . $node)) {
                $contents [] = $node;
            }
        }
        // done
        return $contents;
    }

    /**
     * Returns all files of a folder as an array
     *
     * @param string $path
     * @return array
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     * @since 2.0.0
     */
    public static function filesToArray($path) {
        if (empty($path)) {
            return array();
        }
        $contents = array();
        // Foreach node in $path
        foreach (scandir($path) as $node) {
            // Skip link to current and parent folder
            if ($node == '.' || $node == '..') {
                continue;
            }
            // Check if it's a node or a folder
            if (is_file($path . DIRECTORY_SEPARATOR . $node)) {
                $contents [] = $node;
            }
        }
        // done
        return $contents;
    }

}
