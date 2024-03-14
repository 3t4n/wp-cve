=== Payment Gateway via Valitor for WooCommerce ===
Contributors: tacticaisdev
Tags: credit card, gateway, valitor, woocommerce
Requires at least: 4.4
Tested up to: 6.4.2
WC tested up to: 8.4.0
WC requires at least: 3.2.3
Stable tag: 1.9.37
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Get paid via Valitor in your woocommerce shop.

== Description ==

The Valitor Web Payments Page is a simple and secure way to do business on the Internet, both for large online stores and for smaller merchants.

When using the Web Payments Page, Merchants do not need to ask for or handle card information on their web sites. Merchants who need to attain PCI certification can therefore reduce the scope of the certification process.

The Payment Page accepts all major credit cards (Visa, MasterCard, JCB, Diners and American Express).

This plugin is maintained and supported by Tactica

== Installation ==

1.Unzip the files and upload the folder into your plugins folder (wp-content/plugins/) overwriting old versions if they exist
1.Activate the plugin in your WordPress admin area.
1.You can also install this in your admin using the wordpress plugin repository.

== Frequently Asked Questions ==

= Which currencies are supported? =

Valitor supports EUR, USD, GBP, SEK, DKK, NOK, CAD and ISK.

== Screenshots ==

1. Valitor settings screen
2. Checkout screen
3. Valitor Payment screen

== Changelog ==

= 1.9.37 =
* Tested with WooCommerce 8.4.0
* Fixed checkout payment cards icon url
* Add payment cards icon in Checkout Block

= 1.9.36 =
* Tested with WordPress 6.4.2
* Fixed warning 'The use statement with non-compound name 'Exception''(PHP 7.4)

= 1.9.35 =
* Tested with WordPress 6.4.1 and WooCommerce 8.3.1
* Payment Method Integration for the Checkout Block

= 1.9.34 =
* Tested with WordPress 6.4 and WooCommerce 8.2.1
* Fixed 'dynamic property declaration' warnings(PHP 8.2+)

= 1.9.33 =
* Tested with Wordpress 6.3 and Woocommerce 7.9.0

= 1.9.31 =
* Tested with Wordpress 6.2 and Woocommerce 7.6.0

= 1.9.30 =
* Tested with Wordpress 6.0.3 and Woocommerce 6.7.0

= 1.9.29 =
* Tested with Wordpress 5.9.0 and Woocommerce 6.2.1

= 1.9.28 =
* Tested with Wordpress 5.8 and Woocommerce 5.6.0
* Fixed php error

= 1.9.27 =
* Prevent order double processing

= 1.9.26 =
* Added admin notice if Session Expired Timeout is wrong
* Updated order notices

= 1.9.25 =
* Added logger
* Tested with Wordpress 5.7.2 and Woocommerce 5.4.1

= 1.9.24 =
* Converted redirectText to valid string
* Tested with Woocommerce 5.2.0

= 1.9.23 =
* Tested with Wordpress 5.7 and Woocommerce 5.1.0

= 1.9.22 =
* Fixed an issue when order was processed by IPN and by redirect when it's in the same second.

= 1.9.21 =
* Tested with Wordpress 5.6

= 1.9.20 =
* Tested with new WP and WC version.
* Added user input sanitizing.

= 1.9.19 =
* Changed plugin name

= 1.9.18 =
* Tested with Wordpress 5.5 and Woocommerce 4.4.1

= 1.9.17 =
* Tested with Wordpress 5.4.2 and Woocommerce 4.2.0

= 1.9.16 =
* Changed IPN response text.

= 1.9.15 =
* Changed IPN url response.
* Tested with Wordpress 5.4.1 and Woocommerce 4.1.1

= 1.9.14 =
* Added multilang support
* Tested with Wordpress 5.4 and Woocommerce 4.0.1
* Replaced deprecated methods.

= 1.9.13 =
* Updated secure hashing algorithm
* Added ability to change some Woocommerce checkout texts

= 1.9.12 =
* Changed order line items grouping functionality

= 1.9.11 =
* Tested with Wordpress 5.3.2 and Woocommerce 4.0.1

= 1.9.10 =
* Fixed number format for calculated_total

= 1.9.9 =
* Tested with Wordpress 5.3.2 and Woocommerce 3.9.3

= 1.9.8 =
* Added discount ronding fix.
* Tested with Wordpress 5.3 and Woocommerce 3.8.0

= 1.9.7 =
* Fixed order status updating after payment if success or cancel urls were added in payment setttings.
* Tested with Wordpress 5.2.3 and Woocommerce 3.7.0

= 1.9.6 =
* Added more supported currencies:  GBP, SEK, DKK, NOK, CAD

= 1.9.5 =
* Stripped tags from markup fields
* Tested with Wordpress 5.1 and Woocommerce 3.5.5

= 1.9.4 =
* Tested with Wordpress 5.0.3 and Woocommerce 3.5.4

= 1.9.3 =
* Fixed SHA256 hashing support
* Fixed line items for orders with composite products

= 1.9.2 =
* Changed Valitor API endpoints
* Implemented SHA256 hashing support

= 1.9.1 =
* Updated to add better compatibility with WooCommerce 3.2.3 and higher
* Fixed discount calculation when prices include tax.
* Added configurable redirect urls settings
* Changed logic to use original order id

= 1.9 =
* Minor updates for 3.1 compatibility

= 1.8 =
* Updated tax inclusion

= 1.7 =
* Updated tax inclusion

= 1.6 =
* Updated rounding

= 1.5 =
* Fixed problem regarding checkhash

= 1.4 =
* Fixed fatal error problem

= 1.3 =
* Updates for compatibility with woocommerce 3.0

= 1.2 =
* Initial release in the wordpress repo

