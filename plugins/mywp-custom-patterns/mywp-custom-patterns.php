<?php
/**
 * Plugin Name: MyWP Custom Patterns
 * Plugin URI: https://www.whodunit.fr/
 * Description: Build your own block patterns in one click.
 * Version: 1.2
 * Author: Agence Whodunit
 * Author URI: https://www.whodunit.fr/
 * License: GPL-2.0
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mywp-custom-patterns
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

require __DIR__ . '/vendor/autoload.php';

\Whodunit\MywpCustomPatterns\Init\Core::get_instance( __FILE__ );
