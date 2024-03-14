<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

include_once('autoload.php');
$options = \MinuteMedia\Ovp\Plugin::getPluginOptions();
foreach($options as $opt) {
    delete_option($opt);
}
