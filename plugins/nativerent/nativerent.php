<?php
/**
 * @formatter:off
 *
 * Plugin Name:       Рекламная платформа Native Rent
 * Plugin URI:        https://wordpress.org/plugins/nativerent/
 * Description:       Релевантная реклама для ваших читателей. Рекламодатели сервиса платят в 2-3 раза больше за 1 тыс. показов страниц, чем привычные рекламные сетки. Страница выкупается полностью, на ней размещается максимум четыре рекламных блока, которые выглядят нативно в стиле сайта.
 * Version:           1.9.1
 * Requires at least: 4.9
 * Tested up to:      6.4.2
 * Requires PHP:      5.6.20
 * Author:            Native Rent
 * Author URI:        https://nativerent.ru/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cyr2lat
 * Domain Path:       /languages/
 *
 * @formatter:on
 *
 * @package           NativeRent
 * @author            Native Rent
 * @license           GPL-2.0-or-later
 * @wordpress-plugin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/nativerent-bootstrap.php';

NativeRent\Init::instance();
