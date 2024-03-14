=== Speed Up - Optimize CSS Delivery ===
Contributors: nigro.simone
Donate link: http://paypal.me/snwp
Tags: async, asynchronous, wp_enqueue_style, performance, seo, optimize, front-end optimization, performance, speed, web performance optimization, wordpress optimization tool
Requires at least: 3.5
Tested up to: 6.0
Stable tag: 1.0.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin load the stylesheets asynchronously and improve page load times.

== Description ==

This small plugin (5 Kb) loads the stylesheets asynchronously and improve page load times.

The recommended use of this plugin is to load your vital stylesheets synchronously and non-vital CSS files asynchronously. 
Non-vital CSS-files can be for example: fonts, icons, before the fold template-specific CSS, etc.

You can choose which files to load synchronously with a filter in your function.php, eg.:

`// exclude main and child stylesheets from delivery optimization
function exclude_from_delivery_optimization($handle){
	return in_array($handle, array('main-stylesheet', 'child-stylesheet'));
}
add_filter('speed-up-optimize-css-delivery', 'exclude_from_delivery_optimization');`

Note: this only works if your other plugins and theme add the CSS correctly.

== Installation ==

1. Upload the complete `speed-up-optimize-css-delivery` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0.11 =
* Tested up to Wordpress 6.0

= 1.0.10 =
* Tested up to Wordpress 5.9

= 1.0.9 =
* Tested up to Wordpress 5.7

= 1.0.8 =
* Tested up to Wordpress 5.5

= 1.0.7 =
* Tested up to Wordpress 5.5

= 1.0.6 =
* Tested up to Wordpress 5.3

= 1.0.5 =
* Tested up to Wordpress 5.2

= 1.0.4 =
* Fix some css are loaded two time 

= 1.0.3 =
* Update loadCSS 

= 1.0.2 =
* Tested up to Wordpress 4.9
* Update loadCSS 

= 1.0.1 =
* Fix bug css not loaded if cached.

= 1.0.0 =
* Initial release.