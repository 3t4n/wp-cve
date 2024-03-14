<?php

use AOP\App\Plugin;
use AOP\App\Enqueue;
use AOP\App\PluginMetaLinks;
use AOP\App\Options\OptionsPages;
use AOP\App\Admin\AdminPages\AdminPages;
use AOP\App\Admin\AdminPages\Settings\PluginSettings;

$activate = Plugin::activation();

add_action('plugins_loaded', function () {
    PluginMetaLinks::getInstance();
    Enqueue::getInstance();
    AdminPages::getInstance();
    OptionsPages::getInstance();
    Plugin::uninstall();
});
