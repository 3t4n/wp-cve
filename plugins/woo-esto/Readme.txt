=== Woocommerce ESTO ===
Contributors: rebing
Stable tag: 2.24.2
Requires at least: 4.2
Tested up to: 6.3.2
Requires PHP: 5.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds ESTO payment gateway to a Woocommerce instance.

== Description ==

Add ESTO hire-purchase payment gateway link to Woocommerce.

You can also add an approximate monthly payment amount on the product page.

== Installation ==

PAYMENT GATEWAY

1. Make sure you have the latest WooCommerce plugin
2. Upload Woocommerce ESTO plugin
3. Go to Woocommerce -> Settings -> Checkout -> Esto
4. Fill out all the fields (Shop ID and Secret Key can be found at https://partner.esto.ee/settings)
5. Enable the module

PRODUCT CALCULATOR

1. Install the plugin
2. Go to WooCommerce -> ESTO Product Calculator
3. Enable the calculator
4. If you want to add a logo beside the monthly text, add the image to '<plugin_url>/assets/images/icons/<logo>'
5. Uncomment the required line in '<plugin_url>/includes/Calculator.php'@display_calculator

== Changelog ==

= 2.24.2 =
* Fix --- Custom order numbers without HPOS active did not update status properly

= 2.24.1 =
* Fix --- Get cart item unit price from cart item instead of product

= 2.24.0 =
* Fix --- Woo HPOS compatibility

= 2.23.2 =
* Fix --- Verify nonce when saving calculator settings

= 2.23.1 =
* Fix --- Bugfix for 'is_plugin_active_for_network' function

= 2.23.0 =
* Fix --- Multisite compatibility when WooCommerce is network activated

= 2.22.0 =
* Feature --- Optional order number prefix

= 2.21.0 =
* Fix --- Compatibility for WooCommerce Google Analytics Integration plugin

= 2.20.5 =
* Fix --- Calculator price for variable product discounts

= 2.20.4 =
* Fix --- Change on-hold order status to cancelled when application is rejected. This is required to release reserved stock. Without this change, on-hold orders need manual cancelling by shop admin.

= 2.20.3 =
* Feature --- Send new order admin email after confirmation for orders with automatic on-hold status

= 2.20.2 =
* Fix --- Add enable/disable ESTO X Payments calculator functionality in the admin dashboard. Default is disabled.

= 2.20.1 =
* Fix --- Prevent margins on images in ESTO X checkout calculator

= 2.20.0 =
* Feature --- ESTO X new payments calculator shows customers the actual monthly payment in checkout under the payment method description
* Feature --- Add settings for ESTO Product Calculator url in different languages
* Fix --- Add maximum limit for showing ESTO Product Calculator

= 2.19.3 =
* Fix --- Card payment logo being oversized
* Fix --- Problems with uploading custom logos

= 2.19.2 =
* Feature --- Setting configuration allowing to position checkout payment method logos next to (default) or below the payment method title

= 2.19.1 =
* Fix --- Fallback method for loading WooCommerce countries if payment gateways are initialized too early

= 2.19.0 =
* Update --- New rebranded logos for check out

= 2.18.5 =
* Fix --- Prevent card method api request if card payments are disabled

= 2.18.4 =
* Fix --- Critical error which broke the site after updating

= 2.18.3 =
* Fix --- Order total amount rounding error

= 2.18.2 =
* Added changelog to plugin Readme.
* Fix --- Pay Later change logo setting
