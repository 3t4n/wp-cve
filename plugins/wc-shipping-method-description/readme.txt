=== Shipping Method Description for WooCommerce ===
Contributors: thomascharbit
Tags: woocommerce, shipping, description
Requires at least: 4.0
Tested up to: 6.0
Requires PHP: 5.3
Stable tag: 1.2.5
License: GPLv3 or later License
WC requires at least: 2.6
WC tested up to: 6.7

Add a description to all WooCommerce shipping methods on cart and checkout pages.

== Description ==

Add a description to all WooCommerce shipping methods on cart and checkout pages.
Compatible with WPML and Polylang for translations.

== Installation ==

1. Download the plugin & install it to your `wp-content/plugins` folder (or use the Plugins menu through the WordPress Administration section)
2. Activate the plugin
3. Navigate to **WooCommerce > Settings > Shipping**.
4. Edit a shipping zone
5. Edit a shipping method
6. You will now have an extra field available to describe the shipping method

== Changelog ==

= 1.2.6 =
* Tested up to WP 6.0.1, WC 6.7
* Fix: Add a default value to description field to avoid PHP notice 

= 1.2.5 =
* Tested up to WP 6.0, WC 6.4
* Fix: WPML description translation for each method instance

= 1.2.4 =
* Fix: Check WC is activated before using WC function

= 1.2.3 =
* Add CSS class to description HTML output

= 1.2.2 =
* Fix: WPML description translation

= 1.2.1 =
* Fix: Polylang/WPML integration not working

= 1.2.0 =
* Feature: allow HTML tags in description
* Fix: Wrong variable name when guessing field position

= 1.1.0 =
* Added Table Rate Shipping compatibility
* Fix: Function redeclaration error with Polylang integration

= 1.0.0 =
* Initial release
