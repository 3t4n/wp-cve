<?php

if (!\defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/vendor/autoload.php';

global $wpdb;

$evt = new Ikana\EmbedVideoThumbnail\EmbedVideoThumbnail($wpdb);
$evt->uninstall();
