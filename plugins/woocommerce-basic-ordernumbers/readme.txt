=== WooCommerce Basic Ordernumbers ===
Plugin Name: Ordernumbers for WooCommerce
Contributors: opentools
Tags: WooCommerce, Order numbers, orders
Requires at least: 4.0
Tested up to: 4.9.2
Stable tag: 1.4.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

Customize order numbers for WooCommerce. The order numbers can contain arbitrary text and a running counter.


== Description ==
The most flexible and complete solution for your WooCommerce webshop to customize your order numbers!

By default, WooCommerce uses the WordPress post ID of the order, which result in gaps between the order numbers. With this plugin you can configure the order numbers to have consecutive counters. Furthermore, the order number can contain the year, and the counter can be configured to reset each year.

The number format is a simple string, where # indicates the counter. 
To get order numbers like "WC-376", "WC-377", "WC-378", etc., simply set the format to "WC-#".

The plugin comes in two flavors:

*   This **free basic version**, which provides **sequential numbers** and allows arbitrary text (prefix / postfix) in the numbers
*   The **paid advanced version**, with lots of **additional features**:
	* Counter __formatting__: initial value, counter increments, number padding
	* Flexible __counter resets__
	* Lots of __variables__ to be used in the formats
		- date/time: year, month, day, hour, etc.
		- address: customer country, zip, name, etc.
		- order-specific: Number of articles, products, order total etc.
		- product categories, shipping method
	* Custom variable definitions (with conditions on available variables)
	* Multiple concurrent counters (e.g. numbering per country, per day, per ZIP, ...)
	* Different order numbers for free orders (e.g. "FREE-01" for free orders)
	* Different number format for e.g. certain IP addresses (for testing)
	* Different number format depending on products, product categories, shipping classes
	* Customize invoice numbers (only for the "WooCommerce PDF Invoices and Package Slips" plugin)
	* Different numbers depending on vendors (WC Product Vendors, WC Vendors and YITH WC Multi Vendor plugins)

For the full documentation of both the basic and the advanced ordernumbers plugin for WooCommerce, see:
http://open-tools.net/documentation/advanced-order-numbers-for-woocommerce.html



== Installation ==

1. To install the plugin, either:
	1. use WordPress' plugin manager to find it in the WordPress plugin directory and directly install it from the WP plugin manager, or
	1. use WordPress' plugin manager to upload the plugin's zip file.
1. After installation, activate the plugin through the 'Plugins' menu in WordPress
1. Enable the plugin's functionality in the WooCommerce settings (tab "Checkout" -> "Order numbers")



== Frequently Asked Questions ==

= How can I create nice order numbers for existing orders? =

This plugin is intended for future orders. You can, however, create order numbers for existing orders in the order view in the WordPress admin section. In the top right "Order Actions" box select "Assign a new order number" and click "Save Order". Notice, however, that this will create an order number as if the order was created at that very moment.

= How can I start the counter at a value higher than 1? =

The easiest way is to configure the plugin, make one test order and then modify the counter in the plugin configuration to the value you desire. 

= What about invoice numbers? =

The Advanced Ordernumbers for WooCommerce plugin supports some invoicing plugins. This functionality is not available in the free version, though.


== Screenshots ==

1. Different order and invoice numbers are possible. In the free (basic) version, only the "#" format with one running counter is possible. Custom variables like [year] etc. are only supported in the Advanced (paid) version.
2. The configuration screen of the plugin (with annotations/instructions).
3. The advanced version also allows multiple concurrent counters, like counters per customer country.
4. The configuation screen of the advanced (paid) plugin.
5. Custom variable definitions in the advanced (paid) plugin.

== Changelog ==

= 1.4.4 =
* Add Coupons varialbe
* Fix PHP warnings

= 1.4.3 =
* Delay order number creation when creating orders manually in the backend (broken with 1.4.0)

= 1.4.2 =
* Allow comparing arrays with empty value in the variable definitions

= 1.4.1 =
* Fix searching order numbers, sorting by order number in the order list

= 1.4.0 =
* Compatibility with WooCommerce 3.0
* Add variables [isSubOrder] to allow admins to use different order number formats for suborders created by the YITH WC Multivendor plugin

= 1.3.9 =
* Fix issues with the WooCommerce PDF Invoices & Packaging Slips plugin (invoice numbers were created even if disabled and before an invoice was actually generated)

= 1.3.8 =
* Add debug messages to the update system (disabled by default)

= 1.3.7 =
* Add support for PayPal payment method
* Add variables:
  * [UserRoles] (list of all WordPress user rules for the customer)
  * [Shipping] (shipping costs)
  * [ShippingMethods] (user-readable string)
  * [ShippingMethodIDs] (internal, unique IDs for shiping methods, like shipping_by_rules:31)
  * [ShippingMethodTypes] (shipping plugin names)
  * [ShippingInstanceIDs] (unique identifiers for shipping instance when using zones)

= 1.3.6 =
* Fix order number display for old orders

= 1.3.5 =
* Added variables [MonthName], [MonthName3], [Week], [WeekNumberYear], [DayOfYear], [Weekday], [WeekdayName], [WeekdayName3] (advanced version only)

= 1.3.4 =
* Fix all time variables to use the timezone configured for WordPress

= 1.3.3 =
* Fix issue with order tracking (which assumed order IDs were entered)
* Add filter woocommerce_order_id_from_number(ordernumber) that returns the order ID given the order number

= 1.2.2 =
* Fix problem in the advanced version that no counters were shown in the counter modification table

= 1.2 =
* Make random variable indicators case-insensitive
* First attempts at making the plugin multisite-enabled

= 1.1.1 =
* Renamed the plugin files
* Added icon for the plugin directory

= 1.1 =
* Some smaller bugfixes
* Removed variable definition for year

= 1.0 =
* Initial release

== Upgrade Notice ==

To install the Advanced Ordernumbers for WooCommerce package, proceed as described in the Installation section.
No upgrades yet. 
