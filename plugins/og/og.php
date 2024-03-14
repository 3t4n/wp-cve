<?php
/**
 * OG — Better Share on Social Media
 *
 * @package           og
 * @author            Marcin Pietrzak
 * @copyright         2014-2024 Marcin Pietrzak
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       OG — Better Share on Social Media
 * Plugin URI:        https://github.com/iworks/og
 * Description:       Very tiny Open Graph plugin - add featured image as facebook image. This plugin do not have any configuration - you can check how it works looking into page source.
 * Version:           3.3.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Marcin Pietrzak
 * Author URI:        http://iworks.pl/
 * Text Domain:       og
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

require_once dirname( __FILE__ ) . '/includes/iworks/class-iworks-opengraph.php';
new iWorks_OpenGraph();

include_once dirname( __FILE__ ) . '/includes/iworks/rate/rate.php';
do_action( 'iworks-register-plugin', plugin_basename( __FILE__ ), __( 'OG', 'og' ), 'og' );

