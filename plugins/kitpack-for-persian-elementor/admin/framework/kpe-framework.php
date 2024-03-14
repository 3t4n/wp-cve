<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * @package   Kpe Framework - WordPress Options Framework
 * @author    Kpe <info@kpethemes.com>
 * @link      http://kpeframework.com
 * @copyright 2015-2022 Kpe
 *
 *
 * Plugin Name: Kpe Framework
 * Plugin URI: http://kpeframework.com/
 * Author: Kpe
 * Author URI: http://kpethemes.com/
 * Version: 2.2.8
 * Description: A Simple and Lightweight WordPress Option Framework for Themes and Plugins
 * Text Domain: kpe
 * Domain Path: /languages
 *
 */
require_once plugin_dir_path( __FILE__ ) .'classes/setup.class.php';

if ( ! class_exists( 'KPE' ) ) {
    class KPE extends CSF{}
  }