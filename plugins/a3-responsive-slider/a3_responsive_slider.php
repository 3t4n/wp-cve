<?php
/*
Plugin Name: a3 Responsive Slider
Description: Create unlimited robust and flexible responsive image sliders. Insert them by shortcode from the text editor on any post, custom post type or page or add widget. Auto Mobile touch swipe and a fully customizable skin.
Version: 2.3.1
Author: a3rev Software
Author URI: https://a3rev.com/
Requires at least: 6.0
Tested up to: 6.4.1
Text Domain: a3-responsive-slider
Domain Path: /languages
License: GPLv2 or later
	Copyright © 2011 A3 Revolution Software Development team
	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define('A3_RESPONSIVE_SLIDER_FILE_PATH', dirname(__FILE__));
define('A3_RESPONSIVE_SLIDER_DIR_NAME', basename(A3_RESPONSIVE_SLIDER_FILE_PATH));
define('A3_RESPONSIVE_SLIDER_FOLDER', dirname(plugin_basename(__FILE__)));
define('A3_RESPONSIVE_SLIDER_NAME', plugin_basename(__FILE__));
define('A3_RESPONSIVE_SLIDER_URL', untrailingslashit(plugins_url('/', __FILE__)));
define('A3_RESPONSIVE_SLIDER_DIR', WP_CONTENT_DIR . '/plugins/' . A3_RESPONSIVE_SLIDER_FOLDER);
define('A3_RESPONSIVE_SLIDER_JS_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/js');
define('A3_RESPONSIVE_SLIDER_EXTENSION_JS_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/js/cycle2-extensions');
define('A3_RESPONSIVE_SLIDER_CSS_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/css');
define('A3_RESPONSIVE_SLIDER_IMAGES_URL', A3_RESPONSIVE_SLIDER_URL . '/assets/images');
if (!defined("A3_RESPONSIVE_SLIDER_PRO_VERSION_URI")) define("A3_RESPONSIVE_SLIDER_PRO_VERSION_URI", "https://a3rev.com/shop/a3-responsive-slider/");

define( 'A3_RESPONSIVE_SLIDER_KEY', 'a3_responsive_slider' );
define( 'A3_RESPONSIVE_SLIDER_PREFIX', 'a3_responsive_slider_' );
define( 'A3_RESPONSIVE_SLIDER_VERSION', '2.3.1' );
define( 'A3_RESPONSIVE_SLIDER_G_FONTS', true );

use \A3Rev\RSlider\FrameWork;

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	require __DIR__ . '/vendor/autoload.php';

	/**
	 * Plugin Framework init
	 */
	$GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_interface'] = new FrameWork\Admin_Interface();

	global $a3_responsive_slider_skins_page;
	$a3_responsive_slider_skins_page = new FrameWork\Pages\Slider_Skins();

	global $a3_responsive_slider_card_skin_page;
	$a3_responsive_slider_card_skin_page = new FrameWork\Pages\Card_Skin();

	global $a3_responsive_slider_template_widget_page;
	$a3_responsive_slider_template_widget_page = new FrameWork\Pages\Template_Widget();

	global $a3_responsive_slider_template_mobile_page;
	$a3_responsive_slider_template_mobile_page = new FrameWork\Pages\Template_Mobile();

	$GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'admin_init'] = new FrameWork\Admin_Init();

	$GLOBALS[A3_RESPONSIVE_SLIDER_PREFIX.'less'] = new FrameWork\Less_Sass();

	// End - Plugin Framework init

	global $a3_rslider_shortcode;
	$a3_rslider_shortcode = new \A3Rev\RSlider\Shortcode();
	
} else {
	return;
}

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/a3-responsive-slider/a3-responsive-slider-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/a3-responsive-slider-LOCALE.mo
 * 	 	- /wp-content/plugins/a3-responsive-slider/languages/a3-responsive-slider-LOCALE.mo (which if not found falls back to)
 */
function a3_responsive_slider_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'a3-responsive-slider' );

	load_textdomain( 'a3-responsive-slider', WP_LANG_DIR . '/a3-responsive-slider/a3-responsive-slider-' . $locale . '.mo' );
	load_plugin_textdomain( 'a3-responsive-slider', false, A3_RESPONSIVE_SLIDER_FOLDER . '/languages/' );
}

include ('admin/a3-rslider-admin.php');


register_activation_hook(__FILE__, 'a3_rslider_activated');
