=== Holded integration ===
Contributors: holded
Tags: woocommerce, erp, holded
Requires at least: 4.9
Tested up to: 6.4
Stable tag: 1.2
WC requires at least: 3.0
WC tested up to: 6.4
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Holded service integration with WooCommerce

== Description ==

Official Holded Plugin in order to manage all your orders and sales from WooCommerce without ever leaving Holded.

* The integration between Holded and WooCommerce allows you to import and synchronise all the data about your orders, products, stock levels and warehouses from one platform to another automatically, saving time and effort.

* Import your orders from WooCommerce to Holded, convert them into sales documents and centralise your billing information and management.

* Import the products from your online store so you don’t need to create them manually in Holded.

* Synchronise stock levels so that each stock adjustment is updated at the same time in Holded and in your online store.

* Select which warehouse in Holded corresponds to your location in WooCommerce.

== Installation ==

1. Upload `holded-integration` folder to the `/wp-content/plugins/` directory
2. Activate the Holded integration plugin through the 'Plugins' menu in WordPress
3. That's it. :)

== Frequently Asked Questions ==

= How do I launch the integration? =

1. Set up your Holded account
2. Activate integration with WooCommerce
3. From Holded, download the WooCommerce plugin
4. In Holded create an API key
5. In WooCommerce upload the plugin folder
6. Enter the Holded API key in your WooCommerce panel and save

= If I delete a product SKU in Holded, will it be deleted in WooCommerce too? =

No, product integration works only from WooCommerce to Holded, therefore any modification made in your products in Holded won’t be automatically reflected in WooCommerce.

= If I modify my stock level in Holded, will it affect my stock in WooCommerce? =

Yes, stock integration is bidirectional meaning that any changes in the stock will be reflected in both platforms.

= Why are my orders not imported? =

Check if your order status in WooCommerce is labelled as Completed.

= Why my imported orders do not discount the stock level in Holded? =

Always check the SKU of your products: in order to modify your stock level, the product SKU in Holded and WooCommerce has to match.
A quick way to know if a product SKU is related is to create a Holded Document and check if a Box icon appears right next to your item.

== Screenshots ==

1. Holded Store
2. WooCommerce Settings in Holded
3. Store settings in Holded: Orders
4. Store settings in Holded: Products
5. Store settings in Holded: Stock
6. Store settings in Holded: Warehouses
7. Sales invoices

== Changelog ==

= 3.4.8 =
* Solve notice "The REST API route definition for my-ns/echo is missing the required permission_callback argument. For REST API routes that are intended to be public, use __return_true as the permission callback."

= 3.4.7 =
* Fix WooCommerce order status showing as draft

= 3.4.6 =
* Add extra fields from [WC - APG NIF/CIF/NIE Field](https://wordpress.org/plugins/wc-apg-nifcifnie-field/) plugin

= 3.4.5 =
* Fix error on tax rates with multiple taxes

= 3.4.4 =
* Fix error on orders with multiple taxes

= 3.4.3 =
* Prevent WC_Product Uncaught TypeError on ProductService

= 3.4.2 =
* Update symfony/http-client library
* Show logs in textarea

= 3.4.1 =
* Add disable plugin endpoint
* Rename installation zip file
* WooCommerce [High-Performance Order Storage (HPOS)](https://woocommerce.com/document/high-performance-order-storage/) compatibility

= 3.4.0 =
* Add logs for failing requests
* Avoid call with http 1.0

= 3.3.5 =
* Fixed public assets path when installing this plugin from wordpress.org repository

= 3.3.4 =
* Sort orders by order ID in ascending mode when syncing to Holded
* Added products sku cleaning to ensure only products with sku are sent to Holded

= 3.3.3 =
* Updated last tested WordPress version

= 3.3.2 =
* Fix error importing orders with removed products

= 3.3.1 =
* Fix error removing plugin. Only for PHP 8.

= 3.3.0 =
* Send cost with [Cost of Goods](https://wordpress.org/plugins/cost-of-goods-for-woocommerce/) plugin.

= 3.2.0 =
* Add payment method order field.
* Add payment methods endpoint.

= 3.0.2 =
* Fix error trying rounding string. Only for PHP 8.

= 3.0.1 =
* Fix problem on install when the plugin folder is not the default.

= 3.0.0 =
* Orders sync: Now we work in async ways increasing the speed of processing. Implemented JSON endpoint allowing bigger orders
* Now send order date and order number
* Fix warnings.
* Avoid send products without sku.

= 2.0.1 =
* Send product price without taxes when woocommerce prices include tax option is enabled.
* Add version control tests.
* Add version control documentation.

= 2.0.0 =
* Stock sync: Now you can activate this synchronization so that Holded sets the stock of your WooCommerce products when you modify them at Holded.
* Products sync: Now you can activate this synchronization so that Holded can receive all the product from the shop.

= 1.1.6 =
*Release Date - 8 Jul 2020*

* Readme update

= 1.1.5 =
*Release Date - 5 Jun 2020*

* Getting API Key from Holded legacy plugin.

= 1.1.0 =
*Release Date - 1 Jun 2020*

* Adaptation to WordPress.org repository

= 1.0.0 =
* Initial version

= 0.1.0 =
This version adds holded to WooCommerce link, API Key should be saved again.

== Upgrade Notice ==