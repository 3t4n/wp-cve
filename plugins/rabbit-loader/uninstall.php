<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

define('RABBITLOADER_UNINSTALL_MODE', true);

include_once('autoload.php');

RabbitLoader_21_Admin::plugin_uninstall();

