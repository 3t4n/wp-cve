=== Plugin Name ===
Contributors: macardam
Donate link: https://www.macardam.com/donate/
Tags: random number, rng, random number generator
Requires at least: 3.0.1
Tested up to: 5.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that displays a random number on each page load via shortcode. It truly is magic.

== Description ==

The shortcode [arandomnumber] can be added to any post, page, or widget as many times as needed to display a random number. The number has a default range of 1 - 100, but can be changed using the min and max attributes, like so:

[arandomnumber min=1 max=10000]: This will output a random number between 1 and 10,000. (Like this: 8,014)
[arandomnumber min=-500 max=-1]: This will output a random number between -500 and -1. (Like this: -232)
[arandomnumber min=50000000 max=60000000]: This will output a random number between 50,000,000 and 60,000,000.  (Like this: 56,449,060) 

etc.

If you wish to disable commas, use the comma=no attribute, like so: [arandomnumber min=100000 max=999999 comma=no]. This will output a random number within the range without the commas, like this: 456245.

For full documentation, see the plugin's page: [https://www.macardam.com/a-random-number/](https://www.macardam.com/a-random-number/)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/a-random-number` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the shortcode to display your magic number.

== Frequently Asked Questions ==

= Why does this plugin exist? It seems kind of pointless? =

How dare you. It outputs a random number, so if you need a random number, there is nothing better.  This is also useful to check if a page is being cached or if your server is properly loading since the number will change on every page load.  Use it to add random parameters to forms, urls, etc.

See? That's practical.

= How do I use this plugin? =

For full documentation, see the plugin's page: [https://www.macardam.com/a-random-number/](https://www.macardam.com/a-random-number/)

== Changelog ==

= 1.1 =
* Added comma attribute to remove commas if needed.
* Released August 19, 2017

= 1.0 =
* Launched A Random Number into the Internets. It's alive!
* Released January 8, 2016

