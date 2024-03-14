<?php
/**
 * Plugin Name: Featured Image Plus
 * Plugin URI: https://featuredimageplugin.com/
 * Description: Optimize your WordPress workflow by rapidly managing featured images on Posts and Pages with our enhancements to the bulk and quick edit actions.
 * Version: 1.4.7
 * Author: Krasen Slavov
 * Author URI: https://developry.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: featured-image-plus
 * Domain Path: /lang
 *
 * Copyright (c) 2018 - 2024 Developry Ltd. (email: contact@developry.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

define( __NAMESPACE__ . '\FIP_ENV', 'prod' ); // prod, dev

define( __NAMESPACE__ . '\FIP_MIN_PHP_VERSION', '7.2' );
define( __NAMESPACE__ . '\FIP_MIN_WP_VERSION', '5.0' );

define( __NAMESPACE__ . '\FIP_PLUGIN_UUID', 'fip' );
define( __NAMESPACE__ . '\FIP_PLUGIN_TEXTDOMAIN', 'featured-image-plus' );
define( __NAMESPACE__ . '\FIP_PLUGIN_NAME', esc_html__( 'Featured Image Plus', 'featured-image-plus' ) );
define( __NAMESPACE__ . '\FIP_PLUGIN_VERSION', '1.4.7' );
define( __NAMESPACE__ . '\FIP_PLUGIN_DOMAIN', 'featuredimageplugin.com' );
define( __NAMESPACE__ . '\FIP_PLUGIN_DOCS', 'https://featuredimageplugin.com/help' );

define( __NAMESPACE__ . '\FIP_PLUGIN_WPORG_SUPPORT', 'https://wordpress.org/support/plugin/featured-image-plus/#new-topic' );
define( __NAMESPACE__ . '\FIP_PLUGIN_WPORG_RATE', 'https://wordpress.org/support/plugin/featured-image-plus/reviews/#new-post' );

define( __NAMESPACE__ . '\FIP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( __NAMESPACE__ . '\FIP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( __NAMESPACE__ . '\FIP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

define(
	__NAMESPACE__ . '\FIP_PLUGIN_ALLOWED_HTML_ARR',
	json_encode(
		array(
			'br'     => array(),
			'strong' => array(),
			'em'     => array(),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
				'name'   => array(),
			),
		)
	)
);

// URL for dev/prod for image folder.
if ( 'dev' === FIP_ENV ) {
	define( __NAMESPACE__ . '\FIP_PLUGIN_IMG_URL', FIP_PLUGIN_DIR_URL . 'assets/dev/images/' );
} else {
	define( __NAMESPACE__ . '\FIP_PLUGIN_IMG_URL', FIP_PLUGIN_DIR_URL . 'assets/dist/img/' );
}

require_once FIP_PLUGIN_DIR_PATH . 'inc/admin/admin.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/library/class-fip-admin.php';
require_once FIP_PLUGIN_DIR_PATH . 'inc/library/class-featured-image-plus.php';
