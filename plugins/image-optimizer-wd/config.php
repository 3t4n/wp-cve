<?php
define('TENWEBIO_VERSION', '6.0.67');
if (!defined('TENWEBIO_DIR')) {
    define('TENWEBIO_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
}
if (!defined('TENWEBIO_URL')) {
    define('TENWEBIO_URL', plugins_url(plugin_basename(dirname(__FILE__))));
}
if (!defined('TENWEBIO_DIR_IMAGES')) {
    define('TENWEBIO_DIR_IMAGES', TENWEBIO_DIR . '/assets/img');
}
if (!defined('TENWEBIO_URL_IMAGES')) {
    define('TENWEBIO_URL_IMAGES', TENWEBIO_URL . '/assets/img');
}
if(!defined('TENWEB_VERSION')){
    define('TENWEB_VERSION', 'iowd_' . TENWEBIO_VERSION);
}

//TODO move definition to OUT package
if(!defined('TENWEB_IO_HOSTED_ON_10WEB')) {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $io_mu_plugins= get_mu_plugins();
    define('TENWEB_IO_HOSTED_ON_10WEB', isset($io_mu_plugins['tenweb-init.php']));
}