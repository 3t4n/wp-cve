=== XML for Google Merchant Center ===
Contributors: icopydoc
Donate link: https://pay.cloudtips.ru/p/45d8ff3f
Tags: xml, google, Google Merchant Center, export, woocommerce
Requires at least: 4.5
Tested up to: 6.4.1
Stable tag: 3.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a XML-feed to upload to for Google Merchant Center.

== Description ==

Сreates a XML-feed to upload to Google Merchant Center. The plug-in Woocommerce is required!

In addition to Google Merchant Center, the plugin is also used for Facebook! (beta)

PRO version: [https://icopydoc.ru/product/plagin-xml-for-google-merchant-center-pro/](https://icopydoc.ru/product/plagin-xml-for-google-merchant-center-pro/?utm_source=wp-repository&utm_medium=organic&utm_campaign=xml-for-google-merchant-center&utm_content=readme&utm_term=pro-version)

= Format and method requirements for product data feeds =

For a better understanding of the principles of XML feed - read this:
[https://support.google.com/merchants/answer/7052112](https://support.google.com/merchants/answer/7052112) 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the entire `xml-for-google-merchant-center` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Exporter Google Merchant Center Settings screen to configure the plugin

== Frequently Asked Questions ==

= How to connect my store to Google Merchant Center? =

Read this:
[https://support.google.com/merchants/answer/7052112](https://support.google.com/merchants/answer/7052112)

= What plugin online store supported by your plugin? =

Only Woocommerce.

= How to create a XML feed? =

Detailed instructions with screenshots [here](https://icopydoc.ru/en/how-to-create-an-xml-feed-in-woocommerce-for-google-merchant-center-instruction/?utm_source=wp-repository&utm_medium=organic&utm_campaign=xml-for-google-merchant-center&utm_content=readme&utm_term=main-instruction).

Go to Exporter Google Merchant Center Settings. In the box called "Automatic file creation" select another menu entry (which differs from "off"). You can also change values in other boxes if necessary, then press "Save".
After 1-7 minutes (depending on the number of products), the feed will be generated and a link will appear instead of this message.

== Screenshots ==

1. screenshot-1.png
1. screenshot-2.png

== Changelog ==

= 3.0.8 =
* Fixed bugs
* Added support for lbs and oz

= 3.0.7 =
* Added information about compatibility with HPOS
* Added support for g:availability_date

= 3.0.6 =
* Fixed bugs

= 3.0.5 =
* Fixed bugs

= 3.0.4 =
* Fixed bugs

= 3.0.3 =
* Fixed bugs

= 3.0.2 =
* Fixed bugs

= 3.0.0 =
* Fixed bugs

= 2.9.11 =
* Fixed bugs
* Added support for g:tax

= 2.9.10 =
* Fixed bugs

= 2.9.9 =
* Fixed bugs

= 2.9.8 =
* Fixed bugs

= 2.9.7 =
* Fixed bugs

= 2.9.6 =
* Fixed bugs with interface

= 2.9.5 =
* Fixed bugs

= 2.9.4 =
* Fixed bugs

= 2.9.3 =
* Fixed compatibility with "WOOCS – Currency Switcher for WooCommerce"

= 2.9.2 =
* Fixed compatibility with "WOOCS – Currency Switcher for WooCommerce"

= 2.9.1 =
* Fixed bugs

= 2.9.0 =
* New plugin interface

= 2.8.1 =
* Fixed bugs
* The mechanism for merging temporary files has been changed

= 2.8.0 =
* New plugin core

= 2.7.1 =
* Fixes bugs

= 2.7.0 =
* Fixes bugs

= 2.6.5 =
* Fixes bugs with g:shipping_height

= 2.6.4 =
* Fixes bugs

= 2.6.3 =
* Fixes bugs

= 2.6.2 =
* Fixes bugs
* Changed export logic unit_pricing_measure and unit_pricing_base_measure

= 2.6.1 =
* Fixes bugs

= 2.6.0 =
* Fixes bugs
* Changed export logic unit_pricing_measure and unit_pricing_base_measure

= 2.5.1 =
* Fixes bugs
* Changed export logic return_rule_label

= 2.5.0 =
* Added plugin compatibility Rank Math SEO
* Added plugin compatibility WooCommerce Brands
* Added support for g:quantity, g:unit_pricing_measure and g:unit_pricing_base_measure

= 2.4.0 =
* Added the ability to set gram in g:shipping_weight
* Added support for g:store_code, g:return_rule_label

= 2.3.15 =
* Added the ability to set empty g:gtin and g:mpn

= 2.3.14 =
* Fixes bugs
* Added support for multipack
* Now g:size and g:size_type can be set on the category edit page

= 2.3.13 =
* Fixes bugs

= 2.3.12 =
* Fixed possible bug with g:id
* You can now use the SKU instead of the product ID in g:id

= 2.3.11 =
* Added the ability to remove default.png from the feed 

= 2.3.10 =
* Added support for g:availability - preorder

= 2.3.9 =
* Some changes

= 2.3.8 =
* Fix bugs

= 2.3.7 =
* Fixed logo
* Some changes

= 2.3.6 =
* Fix bugs with condition
* Some changes

= 2.3.5 =
* Fix bugs

= 2.3.4 =
* Fix bugs
* Added support for is_bundle
* Updated self-diagnostic modules

= 2.3.3 =
* Fix bugs
* Added plugin support WooCommerce Currency Switcher by PluginUs.NET. Woo Multi Currency and Woo Multi Pay

= 2.3.2 =
* Added the ability to create a feed for Facebook

= 2.3.1 =
* Some changes
* Updated self-diagnostic modules

= 2.3.0 =
* Fix bugs
* Updated self-diagnostic modules

= 2.2.11 =
* Fix bugs

= 2.2.10 =
* Fix bugs
* Added new options to 'Description of the product'
* Added the ability to change the store currency

= 2.2.9 =
* Added support for shipping

= 2.2.8 =
* Fix bug with sale_price

= 2.2.7 =
* Fix bugs

= 2.2.6 =
* Added support for min_handling_time and max_handling_time
* Now for pre-order products, establish availability equal to in_stock or out_of_stock

= 2.2.5 =
* Fix bugs

= 2.2.4 =
* Added support for shipping_label

= 2.2.3 =
* Fix bugs
* Added support for tax_category

= 2.2.2 =
* Added the ability to use post_meta for age_group

= 2.2.1 =
* Fix bugs
* Added support for product_type

= 2.2.0 =
* Fix bugs

= 2.1.0 =
* Fix bugs
* Slightly improved interface
* Added field for 'feed assignment'

= 2.0.9 =
* Added support for sale_price_effective_date
* Added sandbox
* Now you can choose custom field as a source of brand

= 2.0.8 =
* Slightly improved interface

= 2.0.7 =
* Fix bugs

= 2.0.6 =
* Fix bugs
* Added support for sale_price

= 2.0.5 =
* Fix bugs
* Slightly improved interface

= 2.0.4 =
* Fix bugs
* Added support for WooCommerce Germanized (MPN and GTIN)
* Added support for Premmerce Brands for WooCommerce
* Added support for Perfect Woocommerce Brands

= 2.0.2 =
* Added the ability to use post_meta for SKU and MPN

= 2.0.1 =
* Fix bugs

= 2.0.0 =
Meet version 2.0.0!
What's new:
* Added support for multiple XML-feeds!
* Improves stability
* Slightly improved interface
* Fix bugs
* Updated self-diagnostic modules
* Added support for definition custom_label

= 1.1.4 =
* Сompatibility bug with Yml for Yandex Market plugin fixed

= 1.1.3 =
* Fix bugs
* Added the ability for variable products to upload only the first variation

= 1.1.2 =
* Fix bugs

= 1.1.1 =
* Fix bugs

= 1.1.0 =
* Fix bugs

= 1.0.5 =
* Fix bugs

= 1.0.4 =
* Added support for Сondition attribute

= 1.0.3 =
* Fix bugs
* Added support for MPN attribute

= 1.0.2 =
* Fix bugs

= 1.0.1 =
* Fix bugs

= 1.0.0 =
* First relise

== Upgrade Notice ==

= 3.0.8 =
* Fixed bugs
* Added support for lbs and oz