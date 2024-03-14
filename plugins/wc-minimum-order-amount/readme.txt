=== WC Minimum Order Amount ===

Contributors: fernashes
Tags: woocommerce, order, order amount, order total, minimum order, cart
Requires at least: 4.5.0
Tested up to: 5.3.3
Stable tag: 1.1
WC requires at least: 3.0
WC tested up to: 5.3.3
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html
Original snippet source: https://docs.woocommerce.com/document/minimum-order-amount/

Add the option for a WooCommerce minimum order amount, as well as the options to change the notification texts for the cart. If the order amount doesn't meet the minimum, the customer cannot proceed to checkout.

== Description ==

This plugin adds the option for a minimum order amount, as well as the option change the notification text for the cart page. Customers can't proceed to checkout if the order doesn't meet the minimum order amount.

== Installation ==

1. Download the plugin and install it to your `wp-content/plugins` folder, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Navigate to **WooCommerce > Settings** and scroll down to the "Minimum order settings" to configure the settings

== Screenshots ==



== Changelog ==

= 1.1 2020-11-10 =
* Tested and bumped version numbers
* Found an issue with error validation on checkout where the error is shown but checkout could proceed; background: https://wordpress.org/support/topic/wc_add_notice-doesnt-work-on-checkout-page/
The current workaround is to stop the customer from proceeding past the cart page if order minimum is not met. Removed notification setting for checkout.

= 1.0 2018-08-11 =
 * Initial release
