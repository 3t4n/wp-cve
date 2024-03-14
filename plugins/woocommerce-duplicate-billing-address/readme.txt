=== WooCommerce Duplicate Billing Address ===
Contributors: tabboy
Donate link: http://eversionsystems.com
Tags: woocommerce,billing,shipping,address,copy,duplicate
Requires at least: 3.0.1
Tested up to: 4.75
Stable tag: 1.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a checkbox to the user profile edit screen in the dashboard to enable the duplicating of the WooCommerce billing address to shipping address.

== Description ==

Add a checkbox to the user profile edit screen in the dashboard to enable the duplicating of the WooCommerce billing address to shipping address.  Also adds a button to the WooCommerce My Account page to copy the billing address to the shipping address.


== Installation ==

1. Upload the entire `woocommerce-duplicate-billing-address` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

None

== Screenshots ==

1. This is where the checkbox for duplicating the address is located
2. The WooCommerce settings page where you can enable the address duplication when an order is placed.
1. An example of what the duplicate button looks like on the My Account page.

== Changelog ==

= 1.0 =
* Initial build

= 1.1 =
* Added support for the new predictive text combobox for country and state selection

= 1.11 =
* Added a check to only display duplicate combobox is WooCommerce plugin is active

= 1.12 =
* Missing include to enable call to function is_plugin_active in dashboard

= 1.13 =
* Added a button on the my account page to allow front end users to copy their billing to shipping address

= 1.14 =
* Moved the duplicate address button to the addresses tab introduced in WoooCommerce v2.6
* Fixed an issue with defaulting combobox country and state fields

= 1.15 =
* Support for WoooCommerce 3.0
* Rebuilt entire plugin using WordPress Boilerplate

= 1.16 =
* Add an option to duplicate addresses when an order is placed in WooCommerce

== Upgrade Notice ==

= 1.14 =
Added support for WooCommerce 2.6 tabbed My Account page
