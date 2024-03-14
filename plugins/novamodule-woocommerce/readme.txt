=== Nova Module Woocommerce ===
Contributors: novamodule
Tags: celigo, ecommerce, integration, integration-app, ipaas, netsuite, oracle-netsuite, smart-connector, woocommerce
Requires at least: 4.7
Tested up to: 6.4.2
Stable tag: 2.4.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Built on Celigo's iPaaS, the WooCommerce + NetSuite Integration App helps retailers combine the most customizable eCommerce platform with the proven back-office features of NetSuite and keep the orders, customers, fulfillments, items and inventory levels in sync, as well as refunds data.

== Description ==
Built on Celigo's iPaaS, the WooCommerce + NetSuite Integration App helps retailers combine the most customizable eCommerce platform with the proven back-office features of NetSuite and keep the orders, customers, fulfillments, items and inventory levels in sync, as well as refunds data.

**Integration App Functionality**

* **Sales Orders and Customers** - Customers and Sales Orders are exported out of WooCommerce and imported into NetSuite. Premium edition of the integration app includes Customer and Sales Order sync in the other direction (i.e. from NetSuite to WooCommerce).
* **Sales Order Status Change** - Once a sales order is billed in NetSuite, the connector changes the status of a sales order in WooCommerce to any status deemed necessary (usually to Completed). This data flow can perform various order updates from NetSuite to WooCommerce including sales order cancellations.
* **Sales Order Fulfillment** - Fulfillment data is exported out of NetSuite and imported into WooCommerce once shipping is processed in NetSuite. The data typically includes tracking numbers, shipping methods on fulfillment, etc.
* **Sales Order Billing** - Relevant sales orders are auto-billed in NetSuite and, if needed, billing data is exported out of NetSuite and imported into WooCommerce.
* **Sales Order Refunds** - Premium edition of the integration app includes Refund export from NetSuite to WooCommerce.
* **Inventory Quantity** - Inventory quantity (typically quantity available per product) is exported out of NetSuite and imported into WooCommerce.
* **Product (Item) Data** - Product data gets exported out of NetSuite and imported into WooCommerce.
* **Add-ons** - Multi-store, One-World, and other add-ons are available as needed.
* **Integration Management** - The Celigo integration platform allows you to monitor and manage pre-built integration flows, get notified when errors occur, resolve them, and perform other related tasks using an intuitive dashboard.

**Integration Flows**

* Import Customers from WooCommerce to NetSuite
* Import Sales Orders from WooCommerce to NetSuite
* Export Fulfillments from NetSuite to WooCommerce
* Export Billing Data from NetSuite to WooCommerce
* Export Sales Order Status Change from NetSuite to WooCommerce (This flow also cancels sales orders from NetSuite to WooCommerce)
* Export Inventory Levels from NetSuite to WooCommerce
* Export Item Data from NetSuite to WooCommerce (Standard & Premium Edition Only)
* Export Customers from NetSuite to WooCommerce (Premium Edition Only)
* Export Sales Orders from NetSuite to WooCommerce (Premium Edition Only)
* Export Refunds from NetSuite to WooCommerce (Premium Edition Only)


== Installation ==

= Minimum Requirements =

* PHP 7.2 or greater is recommended
* MySQL 5.6 or greater is recommended

= Automatic installation =

Automatic installation is the easiest option -- WordPress will handles the file transfer, and you won’t need to leave your web browser. To do an automatic install of Nova Module Woocommerce, log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”
 
In the search field type “Nova Module Woocommerce,” then click “Search Plugins.” Once you’ve found us,  you can view details about it such as the point release, rating, and description. Most importantly of course, you can install it by! Click “Install Now,” and WordPress will take it from there.

= Manual installation =

Manual installation method requires downloading the WooCommerce plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Changelog ==

Version: 1.0.0
 * First release.
Version 1.1.0
 * Added support for exporting additional Order Meta Tags
 * Added support for exporting membership information for Order and Customer APIs. The membership plugin should be "woocommerce-memberships" 
Version 1.1.1
 * Added support for TimeZone difference when fetching records 
Version 1.1.2
 * Added few security patches while reading the data from API. 
Version 2.0.0
 * Added new REST API end points for products Add/Update
 * Added new REST API end points to get list skus and ids of published products (including variations)
 * Added new REST API end points for just updating the products
 * Added support for creating product custom attributes
 * Added support for creating products and product variations using single API endpoint
 * Restricting the item id map list specific to published 
Version 2.1.0
 * Added new REST API end points for products images to allow all public URLs
 * Updated the product and Inventory api to make the id available for item errors 
Version 2.2.0
 * Added new REST API end points for Fulfillments and order status
 * Including the customer information Sales Order Response. 
Version 2.2.1
 * Fixed the Customer Id accessing error on novamodule_woocommerce_order_additional_meta 
Version 2.2.2
 * Updated the Item Id update API Response 
Version 2.2.3
 * Fixed the variation Images 
Version 2.2.4
 * Support the custom Item types 
Version 2.2.5
 * Support the NM meta key strict filter 
Version 2.2.6
 * Support the Image file name 
Version 2.2.7
 * Support for product meta data serialization 
Version 2.2.8
 * Support for filter orders by modified date 
Version 2.2.9
 * Support for filter orders by Refunds Created date 
Version 2.3.0
 * Support for Get orders by Refunds Created date 
Version 2.3.1
 * Support for Get orders by Refunds Created date & Fixed issue when No Refunds found 
Version 2.3.2
 * Update the Sales Order meta when updating the fulfillments 
Version 2.3.3
 * Fixed the security related issues. 
Version 2.3.4 
 * Support for Global product attributes 
Version 2.3.5 
 * Support for Cancelled orders without refunds export 
Version 2.3.6 
 * Update the Sales Order meta and Ignore the fulfillments 
Version 2.3.7 
 * Support for Product custom Taxonomy 
 * Support for custom product type(s) 
Version 2.3.8 
 * Support the Flag for Sales Order Imports from NetSuite 
Version 2.3.9
 * Support the Flag customer role update in WooCommerce 
Version 2.4.0
 * Support the phone number filter for the customer list    
Version 2.4.1
 * Support to add tags to the products based on the tag label
 * Option to create tags If not exists
 * Option to set custom field values to the product. 

Version 2.4.2
 * Support to sales order and tracking number updates if the webstore enabled the "woocommerce custom orders table".

Version 2.4.3
 * Issue fixed with "woocommerce custom orders table".
 
 
== Upgrade Notice ==

= 2.4.3 =
2.4.3 is a major update related to customer who enabled the custom order table option in WooCommerce. Make a full site backup, update your theme and extensions, and [review update best practices](https://docs.woocommerce.com/document/how-to-update-your-site) before upgrading.

== Frequently Asked Questions ==

= Where can I get support or talk to other users? =
[Submit a ticket](https://help.novamodule.com/hc/en-us/requests/new)
[WooCommerce-NetSuite Implementation Guide](https://help.novamodule.com/hc/en-us/articles/9362030598807-Getting-started-with-the-WooCommerce-to-NetSuite-Integration)