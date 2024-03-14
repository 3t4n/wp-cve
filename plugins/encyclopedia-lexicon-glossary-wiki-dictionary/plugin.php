<?php

/*
Plugin Name: Encyclopedia Lite
Plugin URI: https://dennishoppe.de/en/wordpress-plugins/encyclopedia
Description: Encyclopedia enables you to create your own encyclopedia, lexicon, glossary, wiki, dictionary or knowledge base.
Version: 1.7.59
Author: Dennis Hoppe
Author URI: https://DennisHoppe.de
Text Domain: encyclopedia
Domain Path: /languages
*/

if (version_compare(PHP_VERSION, '7.4', '<')) {
    die(sprintf('Your PHP version (%s) is far too old. <a href="https://secure.php.net/supported-versions.php" target="_blank">Please upgrade immediately.</a> Then activate the plugin again.', PHP_VERSION));
}

$includeFiles = function ($pattern) {
    $arr_files = glob($pattern);
    if (is_Array($arr_files)) {
        foreach ($arr_files as $include_file) {
            include_once $include_file;
        }
    }
};

$includeFiles(__DIR__ . '/includes/*.php');
$includeFiles(__DIR__ . '/widgets/*.php');

WordPress\Plugin\Encyclopedia\Core::init(__FILE__);
