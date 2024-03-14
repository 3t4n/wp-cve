=== SoulMatch ===
Contributors: gingersoulrecords,ideag
Donate link: https://gingersoulrecords.com/
Tags: match height, equal height, equalize height
Requires at least: 4.6
Tested up to: 4.8
Stable tag: 0.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Equalize the heights of any grouped element. A plugin powered by Liam Brummitt's excellent jquery-match-height script.

== Description ==

When you're showing grouped content in rows (e.g. blog posts, products, feature sets), it's almost inevitable that variations in content lengths will throw off your layout.

SoulMatch helps you equalize the heights of your elements so that everything hangs together nicely, even when the page is resized. Simply provide a CSS selector (class, id, etc.) and the plugin will do the rest.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/soulmatch` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the `Settings -> SoulMatch` screen to configure the plugin

== Frequently Asked Questions ==

= How do I make this plugin work? =

Go to `WP Admin > Settings > SoulMatch` and define CSS selectors for the elements you want to equalize.

== Screenshots ==

1. Settings page

== Changelog ==

= 0.1.1 =
* add .filter(':visible') for better matching
* add a hook to re-match on window resize event 

= 0.1.0 =
* first version to be submitted to wordpress.org

== Upgrade Notice ==

= 0.1.0 =
Initial version
