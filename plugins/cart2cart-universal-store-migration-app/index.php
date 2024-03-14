<?php

/**
 * Plugin Name: Cart2Cart Universal Migration App
 * Plugin URI: https://app.shopping-cart-migration.com/
 * Description: WooCommerce migration with Cart2Cart will let you to import/export all your products, orders, customers, categories, reviews and other entities, preserving relations between them.
 * Version: 2.0.1
 * Author: Cart2Cart
 * Author URI: https://www.shopping-cart-migration.com/
*/

namespace Cart2cart;

define('CART2CART_PLUGIN_ROOT_DIR', __DIR__ . '/');

require __DIR__ . '/class/AutoLoad.php';
require_once __DIR__ . '/passwords/cart2cart-password-migration.php';

AutoLoad::init();

add_action('init', array(__NAMESPACE__ . '\Plugin', 'init'), 21);