=== WooCommerce Ajax Cart Plugin ===
Contributors: moiseh
Tags: woocommerce, ajax, cart, shipping
Requires at least: 5.0
Tested up to: 6.0.1
Stable tag: 1.3.25
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

WooCommerce AJAX Cart is a WordPress Plugin that changes the default behavior of WooCommerte Cart Page, allowing a buyer to see the Total price calculation when change the Quantity of a product, without need to manually click on "Update cart" button.

This improves the user experience when purchasing a product. No other hacks/code/theme changes is needed, this functionality is added when the plugin is activated.

You can try and play with this plugin demonstration [clicking here](https://tastewp.com/new?pre-installed-plugin-slug=woocommerce%2Cwoocommerce-ajax-cart&redirect=plugins.php&ni=true)

[youtube https://www.youtube.com/watch?v=nXUjO2cGljs ]

Free version features:

* Automatically reload and recalculate Cart using AJAX when quantity changes
* Show -/+ buttons around item quantity on cart page
* Show item quantity as select instead numeric field
* Show user confirmation when change item quantity to zero

Premium version features:

* Allow to change/synchronize quantities in shop, minicart and single product pages [view demo](https://youtu.be/a4w8wNlZhxk)
* Make the `Add to cart` button to perform with AJAX, without full page reload [view demo](https://youtu.be/o0VPfMCIctc)
* Faster AJAX reload call when change quantities in Cart page
* Option to lock quantity inputs to allow only change using plus and minus buttons
* Update price vs quantity calculation automatically on Single Product pages

If you looking for a related plugin with more complete features maybe you can try [WooCommerce Better Usability](https://wordpress.org/plugins/woo-better-usability) plugin.

== Installation ==

1. Upload `woocommerce-ajax-cart.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Done. This plugin no requires extra configurations to work

== Screenshots ==

1. When user clicks on "+" or "-" of Quantity field, an AJAX request was made to update the prices.

== Changelog ==

= 1.3.25 =
* Added `High-Performance order storage` compatibility

= 1.3.24 =
* Reset quantity to minimum value when denying the confirmation to remove item from the cart

= 1.3.23 =
* Tested with WooCommerce 6.4.1 and WordPress 5.9.3
* Fixed reported PHP warning error at Admin panel

= 1.3.22 =
* Changed default quantity buttons CSS for better looking
* Testing with WordPress 5.8.1 and WooCommerce 5.8.0

= 1.3.21 =
* Avoid to add hide style for product quantity multiple times
* Prevent user from click on quantity buttons when disabled
* Tested with WordPress 5.8

= 1.3.20 =
* Tested with WooCommerce 5.0.0
* Fixed translation domain path

= 1.3.19 =
* Added dropdown_steps argument support for woocommerce_quantity_input_args hook

= 1.3.18 =
* Fixed fatal error on frontend when WooCommerce plugin not enabled
* Let dropdown respect min_value and step values
* Added wac_quantity_div and wac_template_file filters
* Removed old legacy migration

= 1.3.17 =
* Using document instead document.body listeners that was causing issues with some themes
* Trigger correct Update cart button in Cart for themes that using multiple layouts
* Changed HTML structure of buttons to make it more clickable

= 1.3.16 =
* Tested with latest WordPress and WooCommerce version
* Fixed critical bug when changing quantity in Cart

= 1.3.15 =
* Tested with latest WordPress and WooCommerce versions
* Removed unwanted admin notices

= 1.3.14 =
* Optimized plus and minus quantity javascript listeners

= 1.3.13 =
* Show confirmation to remove product when user empty quantity input

= 1.3.12 =
* Removing deprecated PHP short_open_tag blocks

= 1.3.11 =
* Updated template override compatibility to 4.0.0 and WC tested up to 4.2.0

= 1.3.10 =
* Reversed changelog ordering to make it more standard
* Changed plugin notices to respect the guidelines

= 1.3.9 =
* Added compatibility support with WooCommerce 4.0.1

= 1.3.8 =
* Compatibility with WooCommerce Bundled Products

= 1.3.7 =
* Hide quantity select in Cart when maximum value is same as minimum value

= 1.3.6 =
* Updated supported woocommerce version to 3.8.1

= 1.3.5 =
* Updated supported woocommerce version to 3.7.1
* Prevent page reload when adding to cart in certain conditions

= 1.3.4 =
* Changed limit from 50 to 1000 for quantity in select
* Added compatibility with Giftable for WooCommerce plugin

= 1.3.3 =
* Removing +/- buttons when product is sold individually

= 1.3.2 =
* Added compatibility with WooCommerce 3.6.4
* Standardized to `use strict` mode on frontend script, removing unused code

= 1.3.1 =
* Registering scripts using common hook `wp_enqueue_scripts`


== Frequently Asked Questions ==

== Upgrade Notice ==
