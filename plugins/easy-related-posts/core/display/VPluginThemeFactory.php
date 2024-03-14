<?php

/*
 * Copyright (C) 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * VPluginThemeFactory.php
 *
 * @package   Easy_Related_Posts_Core_display
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
if (!class_exists('VPluginThemeFactory')) {

    /**
     * Description of VPluginThemeFactory
     * 
     * @package Easy_Related_Posts_Core_display
     * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    class VPluginThemeFactory {

        /**
         * Array of registered themes
         * @var array
         */
        public static $registeredThemes = array();

        public static function registerTheme(erpTheme $theme) {
            if (!self::isRegistered($theme)) {
                self::$registeredThemes[$theme->getUniqueID()] = $theme;
            }
        }
        
        public static function isRegistered($theme, $type = null) {
            if(is_string($theme) && is_string($type)){
                foreach (self::$registeredThemes as $key => $value) {
                    if($value->getName() == $theme && $value->getType() == $type){
                        return true;
                    }
                }
            } elseif(self::isValidTheme($theme)){
                foreach (self::$registeredThemes as $key => $value) {
                    if($value->getName() == $theme->getName() && $theme->getType() == $value->getType()){
                        return true;
                    }
                }
            }
            return false;
        }

        public static function registerThemeInPath($path, $name = null) {
            /**
             * Check if we allready have this theme by name
             */
            if (!empty($name) && is_string($name)) {
                if (self::isRegistered($name)) {
                    return;
                }
            }
            /**
             * If not search it, register it and return it
             */
            // If path is pointing to file
            if (is_file($path)) {
                $theme = self::getThemeFromFile($path, $name);
                if($theme){
                    self::registerTheme($theme);
                }
            } else {
                $files = VPluginFileHelper::filesToArray($path);
                foreach ($files as $key => $value) {
                    $absPathToFile = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $value;
                    self::registerThemeInPath($absPathToFile, $name);
                }
            }
        }
        
        private static function getThemeFromFile($filePath, $themeName = null) {
            $classesInFile = self::getClassesOfFile($filePath);
            $out = null;
            if (is_array($classesInFile) && !empty($classesInFile)) {
                ob_start();
                require_once $filePath;
                foreach ($classesInFile as $key => $value) {
                    if (class_exists($value)) {
                        $theme = new $value;
                        if (self::isValidTheme($theme) && (empty($themeName) || $theme->getName() == $themeName)) {
                            $out = $theme;
                            break;
                        }
                    }
                }
                ob_end_clean();
            }
            return $out;
        }

        public static function registerThemeInPathRecursive($path, $name = null) {
            $contents = VPluginFileHelper::dirToArrayRecursive($path);
            $absPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            foreach ((array) $contents as $key => $value) {
                if (is_array($value)) {
                    self::registerThemeInPathRecursive($absPath . $key, $name);
                } else {
                    self::registerThemeInPath($absPath . $value, $name);
                }
            }
        }

        /**
         * Get all themes of a given type
         * @param string $type
         * @return \erpTheme
         */
        public static function getAllOfType($type) {
            $out = array();
            foreach (self::$registeredThemes as $key => $value) {
                if (self::isValidTheme($value) && $value->getType() == $type) {
                    array_push($out, $value);
                }
            }
            return $out;
        }

        public static function getThemesNames($type = false) {
            $out = array();
            $themes = $type ? self::getAllOfType($type) : self::$registeredThemes;
            foreach ($themes as $key => $value) {
                $out[$value->getUniqueID()] = $value->getName();
            }
            return $out;
        }

        /**
         * 
         * @param type $name
         * @return null|erpTheme
         */
        public static function getThemeByName($name, $type = null) {
            if (is_string($name)) {
                foreach (self::$registeredThemes as $key => $value) {
                    if ($value->getName() == $name && (empty($type) || $value->getType == $type)) {
                        return $value->getNewInstance();
                    }
                }
            }
            return null;
        }

        public static function registerAllThemesInFolder($path) {
            $contents = VPluginFileHelper::filesToArray($path);
            foreach ($contents as $key => $value) {
                $absPathToFile = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $value;
                self::registerThemeInPath($absPathToFile);
            }
        }

        public static function getThemeByUniqueID($id) {
            return isset(self::$registeredThemes[$id]) ? self::$registeredThemes[$id] : null;
        }

        public static function getRegisteredThemes() {
            return self::$registeredThemes;
        }

        private static function isValidTheme($object) {
            return $object instanceof erpTheme;
        }

        private static function getClassesOfFile($filePath) {
            if (is_string($filePath) && is_file($filePath)) {
                return VPluginFileHelper::file_get_php_classes($filePath);
            }
            return array();
        }

    }

}
if (!class_exists('VPluginFileHelper')) {

    /**
     * File helper class
     *
     * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
     */
    class VPluginFileHelper {

        public static function file_get_php_classes($filepath) {
            $php_code = file_get_contents($filepath);
            $classes = self::get_php_classes($php_code);
            return $classes;
        }

        public static function get_php_classes($php_code) {
            $classes = array();
            $tokens = token_get_all($php_code);
            $count = count($tokens);
            for ($i = 2; $i < $count; $i++) {
                if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {

                    $class_name = $tokens[$i][1];
                    $classes[] = $class_name;
                }
            }
            return $classes;
        }

        /**
         * Scans recursivly a folder and returns its contents as assoc array
         *
         * @param string $path
         * @return array
         * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
         * @since 1.0.0
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
         * @since 1.0.0
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
         * @since 1.0.0
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

}