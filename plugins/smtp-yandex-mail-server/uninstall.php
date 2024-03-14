<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
$yandex_smtp_settings = 'yandex_smtp_settings';
 
delete_option($yandex_smtp_settings);