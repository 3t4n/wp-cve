=== PayJustNow for WooCommerce ===
Contributors: PayJustNow (Pty) Ltd.,WickedWeb
Tags: PayJustNow, South African Payment Gateway, WooCommerce
Requires at least: 5.6
Tested up to: 6.1.1
Requires PHP: 7.4
Stable tag: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

South Africa’s risk-free, #zerointerest payment solution.
PayJustNow offers you 3 equal interest-free instalments and gives you a rad new way to pay - today! 

== Description ==
South Africa’s risk-free, *#zerointerest* payment solution.

The PayJustNow extension for WooCommerce enables you to accept payments via one of South Africa’s newest and most exciting payment gateways.

**Why choose PayJustNow?**

PayJustNow offers your customers 3 equal interest-free instalments and gives them a rad new way to pay - today! 

Sign up at PayJustNow - https://payjustnow.com/ - a rad new way to pay!

== Installation ==
1. Download the .zip file.
2. Go to: WordPress Admin > Plugins > Add New and Upload Plugin with the file you downloaded with Choose File.
3. Install Now and Activate the extension.
4. Go to https://payjustnow.com/ and sign up as a Merchant.
5. Go to: WordPress Admin > WooCommerce > Settings > Payments > PayJustNow and update the settings.

== Frequently Asked Questions ==
= Do you require a Merchant account at PayJustNow? =
Yes, please go to https://payjustnow.com/ and sign up as a Merchant.

== Changelog ==

= 2.3 =
* Fixed duplication error on partial refund.
* Removed backwards compatible code for very old WooCommerce versions, WooCommerce version 3 and up required.
* Feature request from support forum: Transaction ID now saved on $order->payment_complete().

= 2.2 =
* Option to use Order Number added.

= 2.1 =
* Refund functionality added.

= 2.0 =
* Update compatibility with WordPress 6.0.1 and WooCommerce 6.8.0.

= 1.55 =
* Cosmetic update.

= 1.50 =
* Update compatibility with WordPress 5.9 and WooCommerce 6.
* Fixed function to check for product with no price.

= 1.40 =
* Update compatibility with WordPress 5.7 and WooCommerce 5.1.
* Added error checking for zero amounts in products.
* Product price code updates.

= 1.30 =
* Update compatibility with WordPress 5.5.x and WooCommerce 4.5.x.

= 1.21 =
* Disable the display of PayJustNow content for subscription type products (WooCommerce Subscriptions plugin) on Single Product page and Cart Totals page.
* Remove the PayJustNow payment option if a product in the Cart is a subscription type product (WooCommerce Subscriptions plugin) on the Checkout page.

= 1.20 =
* Amount updated on single product page for variable product selection.
* Classes added to elements on single product page and cart to enable style changes for merchants.
* Ability to hide the calculated amount in single product page by targeting span id in merchant style sheet.

= 1.19 =
* Text fix for variable products.

= 1.18 =
* Added error-checking for new installs. New product detail page popup design.

= 1.17 =
* Added check for pjn_key on order-resubmit.

= 1.16 =
* Updated cancel functionality.

= 1.15 =
* Removed product price restrictions.

= 1.14 =
* Added light and dark themed logos.

= 1.0 =
* Initial stable release.
