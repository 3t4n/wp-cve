<?php
/**
 * Plugin Name:     Divi Content Restrictor
 * Plugin URI:      https://wptools.app
 * Description:     Conditionally restrict access to partial content on divi page. Divi visual builder compatible
 * Author:          WP Tools
 * Text Domain:     divi-content-restrictor
 * Domain Path:     /languages
 * Version:         1.5.0
 *
  * @package         Divi_Content_Restrictor
 */

require_once __DIR__ . "/freemius.php";
require_once __DIR__ . "/vendor/autoload.php";

$loader                   = \WPT\RestrictContent\Loader::getInstance();
$loader["plugin_name"]    = "Divi Content Restrictor";
$loader["plugin_version"] = "1.5.0";
$loader["plugin_dir"]     = __DIR__;
$loader["plugin_slug"]    = basename(__DIR__);
$loader["plugin_url"]     = plugins_url("/" . $loader["plugin_slug"]);
$loader["plugin_file"]    = __FILE__;

$loader->run();
