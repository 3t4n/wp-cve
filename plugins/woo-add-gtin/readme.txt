=== WooCommerce UPC, EAN, and ISBN ===

Contributors: scottopolis
Tags: woocommerce, upc, ean, gtin, isbn
Requires at least: 4.5
Tested up to: 5.8
Stable tag: 0.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add GTIN codes like UPC, EAN, and ISBN to your WooCommerce products.

== Description ==

GTINs (Global Trade Item Numbers) including UPC, EAN, and ISBN are globally recognized unique identifiers for products.

They are required to list your product on Google and Amazon, this plugin allows you to add your GTIN and display it on your product page.

Supports:

* Simple products
* Variable products
* Composite products
* Product bundles

Features:

* Add GTIN for main product
* Add unique GTINs for product variations
* Option to display GTIN on the single product page
* Change GTIN to any other text
* Add GTIN to structured product data
* Works with product feeds like Google Shopping and more (requires a feed plugin)

== Installation ==

* Install and activate the plugin.
* Create a product, visit the "Inventory" tab, and fill out the GTIN field.
* For variable products, add a GTIN for each variation if necessary.
* To hide the GTIN, visit WooCommerce settings => Products => Inventory, scroll down to GTIN settings. Check "Hide GTIN on single product pages?" and save.
* To change GTIN label text, visit WooCommerce settings => Products => Inventory, scroll down to GTIN settings. Enter new label, such as EAN, and save.

== Changelog ==

= 0.5.0 =

* Add structured data
* Test with newest versions of WordPress and WooCommerce

= 0.4.0 =

* Support for product bundles
* Fix < PHP 5.4 error

= 0.3.1 =

* Fix fatal error from is_composite_product() missing function

= 0.3.0 =

* Support for composite products
* Fixed a bug where variable and composite products couldn't be purchased

= 0.2.0 =

* Add GTIN label text setting. To change label, visit WooCommerce settings => Products => Inventory, and scroll down to GTIN settings.

= 0.1.1 = 

* Couple of fixes

= 0.1.0 = 

* Beta release