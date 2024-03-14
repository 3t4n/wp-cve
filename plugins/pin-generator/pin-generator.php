<?php
/*
Plugin Name: Pin Generator
Plugin URI: https://pingenerator.com
Description: Generate Pinterest pins for your blog posts automatically.
Version: 2.0.0
Contributers: Oliver Boyers
Author: Oliver Boyers
Author URI: https://twitter.com/OliverBoyers
Licence: GPLv3
Licence URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: pin-generator
Domain Path: /languages
*/

// If this file is called directly, abort
if (!defined("WPINC")) {
  die();
}

define("PIN_GENERATOR_PLUGIN_URL", plugin_dir_url(__FILE__));
define("PIN_GENERATOR_PLUGIN_DIR", plugin_dir_path(__FILE__));

// Register activation hook - this is run when the plugin is activated
include plugin_dir_path(__FILE__) .
  "includes/pin-generator-activation-hook.php";

// Enqueue Plugin CSS
include plugin_dir_path(__FILE__) . "includes/pin-generator-styles.php";

// Create Plugin Admin Menus and Setting Pages
include plugin_dir_path(__FILE__) . "includes/pin-generator-menus.php";

// Enqueue Plugin JavaScript
include plugin_dir_path(__FILE__) . "includes/pin-generator-scripts.php";

// Enqueue add Pin Generator column to post settings
include plugin_dir_path(__FILE__) . "includes/pin-generator-post-column.php";

// Enqueue insert pins script
include plugin_dir_path(__FILE__) . "frontend/pin-generator-insert-pins.php";

// Create Plugin Options
include plugin_dir_path(__FILE__) . "includes/pin-generator-options.php";

// Create Settings Fields
include plugin_dir_path(__FILE__) .
  "includes/pin-generator-settings-fields.php";
?>
