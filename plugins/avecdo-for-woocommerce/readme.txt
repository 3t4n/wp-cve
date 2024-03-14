=== avecdo for WooCommerce ===
Contributors: avecdo
Tags: feed, service, avecdo, Facebook, Google Shopping, shopping, woocommerce, kelkoo, miinto, Pricerunner, Partner-ads, ecommerce, e-commerce
Requires at least: 4.5
Tested up to: 6.3
Stable tag: 1.6.2
License: Mozilla Public License Version 2.0
License URI: https://www.mozilla.org/en-US/MPL/2.0/

Connect your WooCommerce shop with avecdo.

== Description ==

Connect your shop with channels such as Google Shopping, Pricerunner, Facebook, Kelkoo, miinto and Partner-adds without spending hours on programming and customization!

avecdo analyzes your product feed and transforms them into new feeds that are readable by the channels of your choice.

As a result, you can easily streamline the flow of information between your e-commerce platform and other popular shopping sites.

== Installation ==

This section describes how to install the plugin and get it working.

1. Search for 'avecdo for WooCommerce' in the Plugins -> Add New section of your admin panel.
2. Click the install now button, then click the activate button.
3. Navigate to 'Avecdo Connect' in the WordPress navigation bar.
4. Enter your activation key and hit the button.
5. You are now connected with avecdo.

== Frequently Asked Questions ==

= How do I connect to avecdo? =

Just paste in your activation key, press the submit button, and your shop will be connected to avecdo.

= Additional help =

If you need additional help getting set up or the plugin in general please feel free to contact us via e-mail at support@avecdo.com

== Screenshots ==

1. This is the welcome screen. If you're already signed up on avecdo go ahead and click on "connect your shop". If not, signup is only one click away.
1. This screenshot shows the activation form, where you insert your keys to connect the plugin to avecdo.
2. This screenshot shows the screen you see after successful connection.

== Changelog ==

= 1.6.2 =
* Enabled support for WooCommerce HPOS.

= 1.6.1 =
* Removed old SDK files.

= 1.6.0 =
* Updated to use composer autoloading and new opensource SDK.

= 1.5.2 =
* Tested with WordPress 6.0.

= 1.5.1 =
* Minor bug fixes related to avecdo v2.

= 1.5.0 =
* Support for avecdo v2.

= 1.4.20 =
* Support for Wordpress 5.9.

= 1.4.19 =
* Support for newer WordPress and WooCommerce versions.

= 1.4.18 =
* Added support for multiple usage of Avecdo keys
* Expanded WPML support

= 1.4.17 =
* Fixed problems with Multisite stores.
* Fixed a compatibility issue with the plugin 'Perfect Brands for WooCommerce'.
* A few internal improvements.

= 1.4.16 =
* Fixed a bug that in rare cases could cause incorrect sale prices.

= 1.4.15 =
* Fixed a bug related to Woo Discount Rules.

= 1.4.14 =
* Better support for WPML in general.
* Minor bug fixes.
* Code optimizations.

= 1.4.13 =
* Better support for WPML sale prices.
* Fixed deprecated notices.
* Major big fixes.

= 1.4.12 =
* Support for version 2.x of the plugin 'Discount Rules for WooCommerce'.
* Minor bug fix related to shipping prices.
* Tested with latest Wordpress and WooCommerce versions.

= 1.4.11 =
* Better support for shipping prices.
* Tested with latest wordpress version.

= 1.4.10 =
* Fixed some issues with EAN numbers.

= 1.4.9 =
* Fixed an issue with stock status for variable products.

= 1.4.8 =
* Tested with latest wordpress.
* Added support for plugin 'woocommerce brands'
* Fix order of images and change the way we fetch image galleries.

= 1.4.7 =
* Fix issues with saleprice if no end time were set
* Testet with latest Woocommerce and WordPress

= 1.4.6 =
* Fix issue with missing methods when checking for external plugins.

= 1.4.5 =
* Fix UPC and MPN on simple products, they got switched around when saving the the details

= 1.4.4 =
* Missing clean description.

= 1.4.3 =
* Added automatically removal of various pagebuilder tags - from title and description of products.

= 1.4.2 =
* Fix error release of version 1.4.1

= 1.4.1 =
* Fixes issues where GTIN values are overridden resulting in empty values.

= 1.4.0 =
* Better support for product attributes that are using WooCommerce pre set attributes
* Change the way we collect images from the system, has proven to be more reliable
* Added basic support for WooCommerce Multilingual and its multi-currency functions.
* Added support for Ultimate WooCommerce Brands PRO
* Added support for Ultimate WooCommerce Brands
* Added support for Perfect WooCommerce Brands

= 1.3.11 =
* Improved support for getting brand from parent product if child brand is empty.

= 1.3.10 =
* Added support for plugin https://wordpress.org/plugins/woo-add-gtin/

= 1.3.9 =
* Fixed spelling error.

= 1.3.8 =
* Minor fix to reduce load when fetching products, and calculating if the product prices are tax inclusive. Tested with WordPress 4.9.6

= 1.3.7 =
* Fix that should hide update notification after plugin is upgraded. Tested with WordPress 4.9.5 and WooCommerce 3.3.5

= 1.3.6 =
* Fix internal version number, so update notice is hidden after plugin has been updated

= 1.3.5 =
* Fix for woocommerce < 3.0.0 where function 'wc_get_price_including_tax' do not exists

= 1.3.4 =
* Make sure to check for taxable products

= 1.3.3 =
* New edit fields from WooCommerce >= 2.4.4, allowing custom Brand name and UPC, MPN, EAN and ISBN Numbers for simple and variable products

= 1.3.2 =
* Fix the order of the images we put in the final result.

= 1.3.1 =
* Authentication fix.

= 1.3.0 =
* Security fix.

= 1.2.12 =
* Added option to select what description we use when exporting products.
* Tested with WooCommerce 3.2.6.
* Fix bug that makes the update notice stay open after plugin is updated.

= 1.2.11 =
* Update that checks if the image ids from the database is 0 (zero), and skips them if they are lower or equal to 0

= 1.2.10 =
* Update SDK to version 1.2.3
* Make use of all attributes
* Basic support for brand name, EAN, ISBN, JAN and UPC by using attributes with the proper naming.
* Tested with WooCommerce 3.2.1

= 1.2.9 =
* Always use parent product id for images.

= 1.2.8 =
* lets try and other image fix.

= 1.2.7 =
* Fix the use of product description if empty and not null, we take short description if not empty
* Fix for shop where images are not in in the generated feed, this should fix this issue.

= 1.2.6 =
* Bufix product name/title on products with variations now get the correct product name isset.

= 1.2.5 =
* Add support for product combinations, exported as single products.
* Tested with WooCommerce upto 3.1.2

= 1.2.4 =
* Fix override of css class 'wrap' renamed to 'avecdowrap'
* Add update check and display notice

= 1.2.3 =
* Fixed issue on with products of type 'Variable product' where the price is not included on the main product.
* Fixed css to not override WordPress default button style.

= 1.2.2 =
* Fixed issue on PHP 5.4

= 1.2.1 =
* Hotfix on missing files.

= 1.2.0 =
* WooCommerce >=2.0.20 compatibility
* Fixed product variation created as multiple products.
* Refactored feed generation methods for optimized performance.
* Update SDK to 1.2.0
* Added more product information to feed.
* Added support for sale price incl. start / end date.

= 1.1.1 =
* Major bug fixes

= 1.1.0 =
* Initial Release

== Upgrade Notice ==

= 1.2.0 =
No notice

= 1.1.1 =
No notice

= 1.1.0 =
Initial Release


== Upgrade Notice ==
Always keep your plugin updated to ensure the latest security fixes and
compatibility with the latest changes from the individual channels
