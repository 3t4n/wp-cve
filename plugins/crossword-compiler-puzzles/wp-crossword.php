<?php

/*
  Plugin Name: Crossword Compiler Puzzles
  Plugin URI: https://www.crossword-compiler.com/wordpress.html
  Description: Add interactive puzzles to your pages, taking content from existing crossword.info web page or uploading from files produced by Crossword Compiler.
  Version: 4.2
  Author: WordWeb Software
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Added for debug issues
//define("CCPUZ_DEBUG",1);
define('CCPUZ_VERSION', '13.2');
define('CCPUZ_URL', plugin_dir_url(__FILE__));

function ccpuz_log($msg) {
    if (!defined("CCPUZ_DEBUG") || (isset($_REQUEST["action"]) && ($_REQUEST["action"] == "heartbeat")))
        return; // ignore heartbeat & cron ajax calls

    $log = str_replace('/', DIRECTORY_SEPARATOR, plugin_dir_path(__FILE__)) . "log.txt";
    $cur_retry = 1;
    while ($cur_retry <= 10) {
        if (@filesize($log) > 200 * 1024 * 1024)
            @unlink($log);
        $fh = fopen($log, "a");
        if (!$fh) {
            $cur_retry++;
            continue;
        }
        $s = "\n\n" . @microtime(true) . "\t" . $msg . "\r\n" . $_SERVER["REMOTE_ADDR"] . "\t" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        if ((count($_POST) > 0) && (!array_key_exists("psupsell_log_post", $GLOBALS))) {
            $s .= "\nPOST:\n" . print_r($_POST, true);
            $GLOBALS["psupsell_log_post"] = 1;
        }
        @fwrite($fh, $s);
        @fclose($fh);
        break;
    } // retry;
}

require_once('modules/meta_box.php');
require_once('modules/shortcodes.php');
require_once('modules/functions.php');
require_once('modules/hooks.php');
require_once('modules/scripts.php');
