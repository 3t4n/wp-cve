=== SKU Error Fixer for WooCommerce ===
Contributors: Wordpress Monsters
Tags: woocommerce, sku, fix, sku bug, unique sku error
Requires at least: 3.4.0
Tested up to: 4.7.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin fixes a unique SKU error of WooCommerce products.

== Description ==

When you are creating new products in your webshop using a WooCommerce plugin, you can get the unique SKU error for your product and SKU can't be saved properly. This happens because in the database there are old variations of previously created products that have the same SKU number. WooCommerce SKU Error Fixer plugin solves this problem by cleaning and/or removing old products variables and also you can setup automatic checking and fixing of non-unique SKU problem when you edit a product.

== Installation ==

Install of "SKU Error Fixer for WooCommerce" can be done either by searching for "SKU Error Fixer for WooCommerce" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Settings page - search of old variations
2. Settings page - clean SKU fields of old variations
3. Settings page - removal old variations

== Frequently Asked Questions ==

= What is the old variations? =

These are former variations of variable products when it type have been changed to Simple or another type of product not variable. WooCommerce does not remove these variations to the case you decide to change the product type on variable again. For this case variables remain intact with all fields filled in, including the SKU field.

= What's the problem of old variations? =

= Clogging the database of unnecessary data =

Old variation of products is invisible. You can change the product type with variable to another, and after some time to remove this product and forget about it, but these variations will not be removed. They are stored in the database and it can take a lot of space.

= Unique SKU problem =

A known problem of the uniqueness of the SKU number of the product. WooCommers allows you to assign only unique SKU for product. If you used any SKU for the product variation, and then changed the product type to another, you will not be able to use the same SKU for a different product. You will receive an error "Product SKU must be unique". WooCommerce SKU Error Fixer plugin eliminates this problem.

= How do I use this plugin? =

After installing the plugin you will need to go to the plugin settings page Woocommerce > SKU Error Fixer, where you can to scan your site for presence of any old variations, clean them SKU fields or remove them completely. You can also setup automatic checking and fixing not unique SKU problem when you edit a product.


== Changelog ==

= 1.0 =
* 02.05.2016
* Initial release
