=== IEX Integration ===
Contributors: iexapp, plasmatiksteak, sandpatrol
Plugin URI: www.iex.dk
Tags: WooCommerce, e-conomic, Dinero, Billy, Visma, debitoor, Reviso, accounting, bookkeeping, invoicing, integration, sync
Requires at least: 3.0.1
Tested up to: 5.0.0
Stable tag: 2.2.5.11
Version: 2.2.5.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

IEX Integration automatically transfers WooCommmerce orders, products and customers to your accounting system.

== Description ==

IEX Integration will save you time and money on your accounting.

The plugin allows you to automatically transfer orders, products and customers from your WooCommerce shop to your accounting system - so you no longer have to spend your time on boring typing in your accounting system.

The plugin supports the most common accounting systems on the market. We offer a free 14 days demo of the service. To start your demo order your API-key here:

*   e-conomic: https://economic.iex.dk/
*   Debitoor: https://debitoor.iex.dk/
*   Dinero: https://dinero.iex.dk/
*   Billy: https://billy.iex.dk/
*   Visma: https://visma.iex.dk/
*	Reviso: https://reviso.iex.dk/

If you would like to continue using the service after your demo expires, just sign up for one of our monthly subscribtions.

[youtube https://www.youtube.com/watch?v=xa77BBMKTDo]

== The IEX system ==
The IEX plugin connects your WooCommerce shop to your accounting system. When ordering your IEX demo on the IEX website you get access to your own user friendly dashboard to setup your integration. The IEX system is a powerful solution and handles data transfer by putting your orders, products and customers into a queing system on the IEX server, and as soon as your accounting system is ready to receive the data we deliver it. This means that your data is transferred in real-time - normally within seconds to your accounting system.

Notice: data delivery times can vary depending on the amount of data you are transferrring and peak periods.

The following WooCommerce standard fields can be transferred out of the box with the IEX Integration plugin (depending on your ERP system):

= Standard WooCommerce fields =

*	CUSTOMER:
	*	Billing Company / Last Name
	*	Billing Last Name
	*	Billing First Name
	*	Email
	*	Billing Address 1
	*	Billing Address 2
	*	Billing Country
	*	Billing City
	*	Billing Postcode
	*	Shipping Address 1
	*	Shipping Address 2
	*	Shipping Country
	*	Shipping City
	*	Shipping Postcode
	*   VAT Zone

*	PRODUCT/ARTICLE:
	*	Product name
	*	SKU
	*   ProductID
	*	Regular Price
	*   Sale Price
	*	Description
	*	Inventory stock quantity (updated from e-conomic to WooCommerce)

*	INVOICE:
	*	Order ID (as reference)
	*	Customer number
	*	Delivery Address
	*	Delivery City
	*	Delivery Postcode
	*	Delivery Country
	*	Product Title
	*	Product Quantity
	*	Product Price
	*	Product VAT
	*	Shipping cost
	*	Currency


= Custom fields =
If your WooCommerce webshop is using custom fields, we can also transfer these to your accounting system. You just need to tell us which fields you need to transfer and we will make a small extension plugin to retrieve the custom fields and a special mapping of the fields to your accounting system. We also handle custom rules for your integration such as setting up multiple product and customer groups and selecting invoice layouts according to your needs.

If you have any questions regarding custom fields and special mappings you can direct them to support@iex.dk

*	Examples custom fields and special mappings:
	*	EAN number
	*	CVR number
	*	Invoice layout
	*	Transaction number
	*	Multiple customer groups
	*	Multiple product groups
	*	Multiple payment terms



= e-conomic stock syncronization =
If your using e-conomic and have the e-conomic stock module installed in your accounting system. It is possible to set up stock syncronization sending the stock from e-conomic to your WooCommerce shop.

= Supported Plugins: =

1. Product Bundles WooCommerce Extension.
2. Weight Based Shipping for WooCommerce.
3. WooCommerce Sequential Order Numbers.
4. WooCommerce Subscriptions.

= Backwards transfer of orders =

If you need to transfer your old orders to your accounting system, we can handle a backwards transfer of your orders. You just need to tell us from which date you will need the transfer from and we will send your old orders directly to your accounting system. This service is provided at our normal hourly rate for programming. Notice: when conducting backwards transfer of orders, the transfer can fail for various reasons such as deleted products, changed product numbers etc.

== Installation ==

1.	Install IEX Integration either via the WordPress.org plugin directory, or by uploading the files to your server
2.	Order your IEX Integration API-key to activate your demo: https://iex.dk/en/more/demo-signup
3.	You now have access to your IEX dashboard. Go to profile and find your API-key.
4.	Activate the plugin through the 'Plugins' screen in WordPress and go to IEX Integration in your WordPress admin and insert your API-key and save.
5.	Go to your IEX dashboard and setup the integration to suit your needs.
6.	And that's it, enjoy your integration and when you are ready, just order a subscription in your IEX dashboard to continue using the service.


== Frequently Asked Questions ==

= https://iex.dk/en/helpdesk =

== Screenshots ==

1.	*Connect your WooCommerce shop*

== Changelog ==
= 2.2.5.11 - 17-07-2020 =
* Added additional error handling *

= 2.2.5.9  - 28-05-2020 =
* Fixed minor bug with price calculation changes from update 2.2.5.8.

= 2.2.5.8  - 18-10-2019 =
* Fixed bug with price calculations (prices not incl tax chosen in tax settings)

= 2.2.5.7  - 18-10-2019 =
* Added variation id to orderlines for WooCommerce 3+ *
* Fixed minor message bug, where updating order status would result in error log in dashboard *

= 2.2.5.6  - 26-07-2019 =
* Added more tax information on orderlines, to better find issues regarding missing tax rates *
* Fixed a bug where products where converted to simple products *
* In addition to previous - added fix that will republish variants that were sent to trash (only on product transfer to WooCommerce) *
* Fixed a bug where product check would fail, and variants would be treated as parents and the other way around *
* Fixed bug on 2.2.5.5 update, where the debug information was not sent back correctly *

= 2.2.5.5  - 18-07-2019 =
* Added error handling on situations where parent product was not a WooCommerce product *

= 2.2.5.4  - 09-07-2019 =
* Fixed issue with products being out of stock, even if variants has stock *
* Added some specific features regarding custom GTIN fields *
* Fixed so that variants can have custom field updated *

= 2.2.5.3  - 19-06-2019 =
* Removed several warnings triggered by plugin *
* Added support for custom fields to be populated on product create *
* Fixed an issue with updating size attribute on variable products *

= 2.2.5.2  - 28-03-2019 =
* Added compatability with Altapay *

= 2.2.5.1  - 04-02-2019 =
* Added compatability with newest version of WooCommerce *
* Added stock status check on variant in product create in shop *

= 2.2.5.0  - 18-01-2018 =

= 2.2.4.4  - 10-01-2018 =
* Meta on parent product added *
* Client api changes *
* Product visibility issue *
* Shipping taxrate issue *

= 2.2.4.3  - 04-09-2017 =
* Fixed issue with allow backorder *
* Multiple integration support *
* Fixed stock status on parent product *
* Fixed order filter *
* Fixed issue with transient *

= 2.2.4.2  - 03-05-2017 =
* Fixed issue with order ID *

= 2.2.4.1  - 25-04-2017 =
* Fixed issue with variants *
* Fixed parent product transfer *

= 2.2.4.0  - 07-04-2017 =
* Compatibility with WooCommerce 3.0 *

= 2.2.3.15 - 23-12-2016 =
* Fixed url mismatch check *
* Fixed PHP Warning: Missing argument 2 for IEX_Integration::iex_term_edit()

= 2.2.3.14 - 29-12-2016 =
* Default to 2 decimal places for all numbers *
* Changed the way urls are received from IEX *

= 2.2.3.13 - 08-12-2016 =
* Compatibility with Wordpress 4.7 *
* Fixed multiple warnings with number_format *
* Removed manuel sync from plugin - Do this from the Dashboard *

= 2.2.3.12 - 09-11-2016 =
* Added category sync from shop to IEX *

= 2.2.3.11 - 28-09-2016 =
* Compatibility with Wordpress 4.6 *
* Fixed price not being updated correcly back to shop *
* Fixed stock not being set correctly if full stock is not returned from ERP *
* Fixed response on product create *
* Fixed rounding to WooCommerce decimal setting *
* Added total product stock to orderlines *

= 2.2.3.10 - 15-08-2016 =
* Fixed payment fee format *
* Fixed shippingmethods getting returned to IEX correctly *
* Changed icon to match other Wordpress icons *
