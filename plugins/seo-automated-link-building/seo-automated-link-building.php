<?php
/*
Plugin Name: Internal Links Manager
Description: Build internal links easily.
Version:     2.4.2
Author:      webraketen
Author URI:  https://www.webraketen.io
License:     GPLv2 or later
Text Domain: seo-automated-link-building
Domain Path: /lang
*/

require_once __DIR__ . '/vendor/autoload.php';

use SeoAutomatedLinkBuilding\Plugin;

$name = plugin_basename(__FILE__);

// start plugin actions
new Plugin($name);
