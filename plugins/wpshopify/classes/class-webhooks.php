<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Messages;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks
{
    public $plugin_settings;
    public $Template_Loader;

    public function __construct($plugin_settings, $Template_Loader)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Template_Loader = $Template_Loader;
    }

}
