<?php
/*
Plugin Name: Defer Transictional Emails For WooCommerce
Description: It will speed up the checkout of WooCommerce by deferring the transactional emails. No settings, just install it and activate it.
Author: Jose Mortellaro
Author URI: https://josemortellaro.com
Domain Path: /languages/
Text Domain: deffer-transitional-emails-for-woocommerce
Version: 0.0.2
*/
/*  This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
defined( 'ABSPATH' ) || exit; // Exit if accessed directly


//It defers the transictional emails sent by WoooCommerce.
add_filter( 'woocommerce_defer_transactional_emails','__return_true' );
