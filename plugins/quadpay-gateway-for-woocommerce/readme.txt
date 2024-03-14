=== Zip North America Gateway for WooCommerce ===
* Contributors: quadpay, codestylist
* Tags: QuadPay, WooCommerce, payment, gateway
* Requires at least: 4.7
* Tested up to: 6.2
* Stable tag: 1.7.3
* Requires PHP: 7.0
* License: GPLv3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html

Use Zip North America as a payment gateway for WooCommerce.

== Description ==

Zip North America for WooCommerce is a powerful WooCommerce payment gateway to provide your customers to split any payment into 4 installments.

Increase your conversion rate by 20% and your average order value by up to 60%.

Please note, you need an account with Zip North America to use this plugin. You can sign up every time on [https://zip.co/us/signup-merchant](https://zip.co/us/signup-merchant).

== What's New ==

* Canada checkout support
* Zip branding

== Installation ==

Upload the files to your server and put it in your folder /plugins/.
Activate the plugin, enter the API key and check the settings page.

== Frequently Asked Questions ==

= Can I use this plugin without WooCommerce? =

No. Zip North America Gateway for WooCommerce requires WooCommerce to provide the Zip payment gateway.

= Do I need an account with Zip North America? =

Yes. Please contact our Partner Support team at partners@us.zip.co.

= Is the plugin free? =

The source code is freely available, but you need a Zip client ID and client secret to provide your customers a buy now, pay later option. Please sign up at [https://zip.co/us/signup-merchant](https://zip.co/us/signup-merchant).

= Why do you load external files? =

The plugin is loading external files on the product detail page, the cart and checkout page. This javascript file is needed to show the Zip information and is provided on the fly. In some cases Zip provides a dedicated file for the individual merchant.

= How do I request a feature? =

Please send a request to the Zip team at partners@us.zip.co.

== Screenshots ==

1. General settings.
2. Zip widget on the cart summary.
3. Zip payment widget on checkout page.

== Changelog ==
= 1.7.3 =
* Text changed on payment method display

= 1.7.2 =
* Added merchantId to widget tag if it is set in Payment settings
* Compatibility - tested up to WordPress 6.2
* Compatibility - tested up to WooCommerce 7.5

= 1.7.1 =
* Compatibility - tested up to WordPress 6.0
* Compatibility - tested up to WooCommerce 6.8

= 1.7.0 =
* Defer funds capture support
* Multiple place order and API improvements
* Compatibility - tested up to WooCommerce 6.3

= 1.6.0 =
* Added payment widget on checkout
* Compatibility - tested up to WooCommerce 6.2
* Compatibility - tested up to WordPress 5.9

= 1.5.1 =
* Minor code improvements
* MFPP attribute added to the order API call

= 1.5.0 =
* Canada checkout support
* Merchant fee for payment plan

= 1.4.0 =
* Changed to Zip branding

= 1.3.11 =
* Fix - fixed an error when trying to get the billing country
* Compatibility - tested up to WooCommerce 5.3
* Compatibility - tested up to WordPress 5.7

= 1.3.10 =
* Tweak - added new QuadPay branding
* Compatibility - tested up to WooCommerce 4.9.0

= 1.3.9 =
* Fix - fixed issue with checking QuadPay status for on hold and pending orders
* Tweak - added time settings for order check
* Tweak - added detailed log and order notes messages
* Tweak - added "Discover" as a credit card option in QuadPay checkout button

= 1.3.8 =
* Tweak - added attribute to ES5 script to maximize browser support
* Tweak - improved readme description installation
* Compatibility - tested up to WordPress 5.6.0 and WooCommerce 4.8.0

= 1.3.7 =
* Tweak - logging improvements
* Tweak - updated to QuadPay widget 2.2.6
* Tweak - making plugin compliant with WordPress plugin guidelines
* Fix - fixed issue with access token issue
* Compatibility - tested up to WordPress 5.5.1 and WooCommerce 4.5.1

== Upgrade Notice ==

= 1.3.7 =
With version 1.3.7 we have improved the logging to provide a better support in case something went wrong. And, we are proud to publish the first version to the WordPress plugin repository.
