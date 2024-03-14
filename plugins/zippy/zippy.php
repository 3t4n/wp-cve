<?php
/**
 * Plugin Name: Zippy
 * Description: Incredibly easy solution to archive pages and posts as zip file and unpack them back even on the other website!
 * Version: 1.6.10
 * Author: Gesundheit Bewegt GmbH
 * Author URI: http://gesundheit-bewegt.com/
 * Text Domain: zippy
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

require_once __DIR__ . '/ZippyCore.php';

class_alias(\LoMa\ZippyCore::class, '\LoMa\Zippy'); // Back compatibility

new \LoMa\ZippyCore();
