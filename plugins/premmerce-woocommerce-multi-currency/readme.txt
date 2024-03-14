=== Premmerce Multi-currency for Woocommerce ===
Contributors: premmerce, freemius
Tags: currency switcher, multi-currency, currencies, converter,  switcher, woocommerce, exchange
Requires at least: 4.8
Tested up to: 6.4
Stable tag: 2.3.5
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Premmerce Multi-currency for Woocommerce plugin allows you to start a multi-currency store with flexible settings and a number of additional unique features.

== Description ==
The Premmerce Multi-currency for Woocommerce plugin allows you to start a multi-currency store with flexible settings and a number of additional unique features.

[youtube https://www.youtube.com/watch?v=2B8xNyfb4tQ]

== The main plugin features and its key advantages: ==
This plugin differs from the existing on the market solutions and allows you to work with currencies at a more professional level. You’ll definitely like its key features:


**The ability to display a product price in different currencies (Premium)**
The most popular plugins, that are available today, work on the following principle – you can specify the product price in only one main currency, and when switching the currencies use the conversion at a given rate.

This approach isn’t very flexible, because it doesn’t solve the following problems:

* For importing several price lists from different international suppliers in different currencies and maintaining product prices updated, it’s best to run import with the prices specified in the original price list.
* Avoid losses from the exchange rates fluctuations when selling products that you’ve purchased abroad in a foreign currency, it’s best to display a product price in the purchased currency and to change the exchange rate in the store dashboard.

Premmerce Multi-currency for Woocommerce solves these cases as it allows you to set the price for each product in different currencies.



**The ability to create two identical currencies with different exchange rates**
It will enable import of several price lists with different rates of the same currency from different suppliers.



**Automatic updating currency rates (Premium)**
We’ve added automatic currency rates updating from servers like Currencylayer and Free Currency Converter.



**Supporting geolocation (Premium)**
Automatic currency selection depending on the customer’s country improves the usability of the site.



**Creating an internal currency**
It gives the ability to display only the currency needed for sale thus making currency management more flexible.



**Importing products in different currencies**
Updating product prices in different currencies will reduce your losses when exchange rates fluctuate. Your prices will always be actual and updated. The correct products importing in different currencies need the following plugins: WP All Import Pro, WP All Import – WooCommerce Add-On Pro



**Additionally, this plugin supports the following standard multi-currency features:**
* Adding currency rates and automatic conversion
* Displaying the currency switch widget
* Unlimited number of currencies
* Formatting a price display on the frontend
* Displaying a currency symbol, for example, ‘UAH’ for the Ukrainian hryvnia (the standard symbol – ₴)
* The shortcode [multicurrency]
* Supporting caching plugins


**The plugin is tested for compatibility and it adds the functionality:**
* WooCommerce – all core features for working with products and prices
* [Premmerce WooСommerce Wholesale Pricing](https://premmerce.com/woocommerce-wholesale-pricing/)
* [WooСommerce SEO Addon](https://premmerce.com/premmerce-woocommerce-seo-addon-yoast/)



Read more about: [Premmerce WooCommerce Product Filter Premium](https://premmerce.com/woocommerce-multi-currency/)

== Screenshots ==
1. The plugin settings in the administrative area.
2. The currencies list in the administrative area.
3. The currencies switcher displaying on the frontend.

== Frequently Asked Questions ==

= Documentation =
Full documentation is available here: [Premmerce Multi-currency for Woocommerce](https://premmerce.com/premmerce-woocommerce-multi-currency/)

= Installation Instructions =
Go to Plugins -> Add New section from your admin account and search for Premmerce Multi-currency for Woocommerce.

You can also install this plugin manually:

* Download the plugin’s ZIP archive and unzip it.
* Copy the unzipped premmerce-woocommerce-multi-currency folder to the /wp-content/plugins/ directory.
* Activate the plugin through the ‘Plugins’ menu in WordPress

= Where do I report security bugs found in this plugin? =

Please report security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/premmerce-woocommerce-multi-currency/). The Patchstack team will assist you with verification, CVE assignment, and notify the developers of this plugin.

== Changelog ==

= 2.3.5 (21st July 2023) =

* Security: Freemius SDK updated to 2.5.10

= 2.3.4 (Jan 28, 2023) =

* Improvement: PHP 8.1 compatibility
* Improvement: Freemius SDK updated to 2.5.3
* Fix: CurrencyLayer.com API URL now correct
* Fix: PHP Fatal error: Uncaught TypeError: array_map():
* Fix: PHP Warning: Undefined array key
* Fix: Currency label for a subscription in the edit product admin now renders HTML correctly

= 2.3.3 (Oct 23, 2022) =

* Fix: Currency cache now flushed after adding a new currency when using an object cache
* Improvement: Tested up to WooCommerce 7.0
* Improvement: Freemius SDK to 2.4.5

= 2.3.2 (Mar 22, 2022) =

* Updated Freemius SDK to 2.4.3

= 2.3.1 (Dec 24, 2020) =

* Updated Freemius SDK to 2.4.1

= 2.3 (Sep 24, 2019) =

= 2.2.1 (May 16, 2019) =

* Fix API files including
* New API methods added
* Fixed product sale price after quick editing
* Fixed saving product without price
* Fixed zero in sale price field when product has no sale price
* Fixed displaying products sale price in admin products table
* Fixed displaying user total spent
* Fixed displaying max price in active filter
* Fixed saving sale price (check if it not bigger than regular)
* Fixed bulk prices editing
* Fixed saving variable products when variations form not expanded
* Fixed price recalculation for variable products
* Fixed product prices quick saving
* Fixed managing Woocommerce prices hash
* Updated Freemius SDK to 2.3.0
* Woocommerce tested up to updated to 3.7

= 2.2 (May 10, 2019) =

* Updated WooCommerce compatibility version to 3.6
* Updated SDK version
* Fix displaying currency symbol in admin area
* Fix product price filtering with Woocommerce 3.6
* Fix variable products prices displaying with Woocommerce 3.6

= 2.1.1 (Apr 10, 2019) =

* Removed geolocation check from free version

= 2.1 (Apr 9, 2019) =

* Added Free Currency Converter Authorization
* Updated currency switcher option names
* Fixed issues with WPML
* Fixed mini cart displaying issue
* Fixed bug with order currencies in admin dashboard
* Fixed bugs with Premmerce Frequently Bought Together
* Fixed bugs with Premmerce Woocommerce Wholesale Pricing
* Fixed bugs with Premmerce WooCommerce Product Filter

= 2.0.1 (Mar 1, 2019) =

* Security fix
* Added OceanWP integration

= 2.0 (Nov 26, 2018) =

* Updated freemius version to 2.1.3
* Added WooCommerce 3.5 support
* Added rates auto update to premium version
* Added geo location support
* Added new hooks
* Fixed woocommerce reports
* Fixed wpml support

= 1.1 (Aug 2, 2018) =

* Added wholesale pricing compatibility
* Added price filter compatibility
* Updated freemius sdk
* Updated translations

= 1.0 (Jan 24, 2018) =

* Initial release
