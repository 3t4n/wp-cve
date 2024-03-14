<?php
/*
 * Plugin Name:       LitExtension: Shopping Carts to WooCommerce
 * Plugin URI:        https://litextension.com/
 * Description:       Litextension
 * Version:           1.2.0
 * Author:            Litextension
 * Author URI:        https://litextension.com
 * Text Domain:       lit-litextension
 * Domain Path:       /languages
 */

namespace LitExtension;

define('LIT_VERSION', '1.2.0');
define('LIT_PATH_PLUGIN', __DIR__ . '/');
define('LIT_URL_PLUGIN', plugin_dir_url(__FILE__) . '/');

require LIT_PATH_PLUGIN . 'class/LitAutoLoad.php';

LitAutoLoad::init();

add_action('init', array(__NAMESPACE__ . '\LitMain', 'init'), 21);

register_activation_hook(__FILE__, array(__NAMESPACE__ . '\LitInstaller' , 'litActivate'));

register_deactivation_hook(__FILE__, array(__NAMESPACE__ . '\LitInstaller', 'litDeactivate'));

register_uninstall_hook(__FILE__, array(__NAMESPACE__ . '\LitInstaller', 'litUninstall'));