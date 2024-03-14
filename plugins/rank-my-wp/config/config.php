<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * The configuration file
 */
if (!defined('RKMW_NONCE_ID')) {
    if (defined('NONCE_KEY')) {
        define('RKMW_NONCE_ID', NONCE_KEY);
    } else {
        define('RKMW_NONCE_ID', md5(date('Y-d')));
    }
}

defined('RKMW_DEBUG') || define('RKMW_DEBUG', 0);
define('RKMW_REQUEST_TIME', microtime(true));

/* No path file? error ... */
require_once(dirname(__FILE__) . '/paths.php');

/* Define the record name in the Option and UserMeta tables */
defined('RKMW_OPTION') || define('RKMW_OPTION', 'rkmw_options');

