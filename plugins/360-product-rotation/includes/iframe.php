<?php
/**
 * Generates the content of the iframe source page
 *
 */


/**
 * Wordpress Initialization
 *
 * @return null|string
 */
function find_wordpress_base_path()
{
    $dir = dirname(__FILE__);
    do {
        //it is possible to check for other files here
        if (file_exists($dir . "/wp-config.php")) {
            return $dir;
        }
    } while ($dir = realpath("$dir/.."));
    return null;
}

/**
 * Initialize wordpress, if not initialized
 */
if (!defined('ABSPATH')) {
    //get path
    define('BASE_PATH', find_wordpress_base_path() . "/");

    //init Wordpress
    require(BASE_PATH . 'wp-load.php');
}


echo ( YoFLA360()->Utils()->construct_iframe_content() );

