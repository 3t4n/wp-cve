<?php

/*
  Plugin Name: Image Hover Effects for Elementor with Lightbox and Flipbox
  Author: biplob018
  Plugin URI: https://wordpress.org/plugins/image-hover-effects-with-carousel/
  Description: Image Hover Effects for Elementor with Lightbox and Flipbox is all in one image hover effect solution at Elementor Page Builder.
  Author URI: https://oxilab.org
  Version: 3.0.2
  License: GPLv2 or later
 */
if (!defined('ABSPATH'))
    exit;


define('OXIIMAEADDONS_FILE', __FILE__);
define('OXIIMAEADDONS_BASENAME', plugin_basename(__FILE__));
define('OXIIMAEADDONS_PATH', plugin_dir_path(__FILE__));
define('OXIIMAEADDONS_URL', plugins_url('/', __FILE__));
define('OXIIMAEADDONS_PLUGIN_VERSION', '3.0.2');
define('OXIIMAEADDONS_TEXTDOMAIN', 'oxi-hover-effects-addons');

/* * S
 * Including composer autoloader globally.
 *
 * @since 9.3.0
 */
require_once OXIIMAEADDONS_PATH . 'autoloader.php';

/**
 * Run plugin after all others plugins
 *
 * @since 9.3.0
 */
add_action('plugins_loaded', function () {
    \OXIIMAEADDONS\Classes\Bootstrap::instance();
});
