=== SKU generator for Woocommerce by ATR ===
Contributors: yehudaT
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=T6VTA75GTS3YA
Tags: product SKU, SKU, SKU generator, woocommerce
Requires at least: 3.8
Tested up to: 6.4.2
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Adds a button to the WC edit/new product page that enables generating a SKU (catalog number) in a pre-defined format and length. It is aimed for Woocommerce shop admins who manage a shop with no pre-defined catalog numbers for products. 

When adding or editing a product it is common that you do not have a pre-set catalog number for the product. 
This plugin enables you to generate a SKU (catalog number) for the product, with specified format and length. 
It also checks that the suggested SKU does not exist in the database for any other product. 

At the same time you can use it to check a SKU number you enter.

Starting on V 1.0.1 you can choose to add automatic SKU for every new product or existing product without a SKU (no need to click a button or select).

You also can select not to generate a random SKU, but write it yourself, and the plugin will check asynchronously if the SKU already exist in the database.

> Tested up to Woocommerce Version 6.0.0

> **Important!** version 1.0.2 and up works with Woocommerce 2.6.0 and up. If you have earlier version of WC please use this plugin version 1.0.1


= Features =

1. None intrusive. The plugin adds the SKU to the SKU field in the product edit page but it saved only by you. 
2. Option to add auto prefix to the generated SKU.
3. Option to auto fill SKU for new product.
4. 3 flexible SKU formats - between min-max numbers, string (you can select any alpha numeric combination), Incremental (auto increment next SKU)


== Installation ==

Installing "ATR structured SKU generator for Woocommerce" can be done either by searching for "ATR structured SKU generator for Woocommerce" via the "Plugins > Add New" screen in your WordPress dashboard, 
or by using the following steps:

1. Upload the plugin files to the '/wp-content/plugins/atr-random-SKU-for-woocommerce' directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

Important! You must go to plugin settings page and select the options apply to your shop.

= Settings =

1. Use the Settings->ATR rand sku Woo screen to configure the plugin
2. Select the format you require for the SKU: "Use max min" or "Use string" or "Use Incremental"
"Use max min" – you want the SKU to contain only numbers between the specified numbers.
"Use string" – you want the SKU to contain only characters you specified.
"Use Incremental" - You want to increment the SKU by 1 for next product edited/created.
3. Set the relevant options for your selection.
4. If you want the plugin to auto fill new products SKU for you select the option "Check this to fill auto SKU when field is empty (also for new products)"
5. You can set a prefix that will be added to every newly generated SKU.

The plugin options allows you to select between numeric, alpha numeric or incremented SKU. 
The random SKU format will be generated according to these selections.

= Numeric string - "Use max min" =

If you select a numeric string, you should set a minimum and maximum numbers.
Setting the min/max numbers will force the random SKU generated to hold only numbers between those numbers.
That way you can also define how many digits will be in the SKU.
For example, if you select min: 10000 and max: 99999 you’ll get SKU’s with 5 digits long between these two numbers.

= Characters strings - "Use string" =

If you select a characters string, you’ll have to define
1. The characters to be used in the string
2. The length of the string
For example, if you select "abcdefghijklmnopqrstuvwxyz123456789" for the characters and "8" for the length, the SKU string will be 8 characters long and with all the letters and numbers except 0 (zero) and capital letters.

= Incremental =

1. Start increment from number - Define the start number. Default is 1. 
2. The index always incremented up as long as the "Incremental" option is selected. 
3. If you want to skip forward or backward some numbers you can change the "Start at" option to the number you want. 



== Screenshots ==

1. The edit product screen with the Auto SKU button
2. Setting for the Auto SKU format
3. The Auto SKU button and options explained

== Frequently Asked Questions ==

== Changelog ==
= 2.0.1 =
* 2017-8-5
* Fixed install problem
* Tested for WP 5.5
* Tested for Woocommerce  4.3.2

= 2.0.0 =
* 2016-8-31
* Added prefix option
* Added incremental SKU

= 1.0.2 =
* 2016-8-3
*	Moved the button to the inventory tab (after change in WooCommerce)
*	SKU_length variable is set to have no default
*	Added condition where “Use string” is selected but no string length

= 1.0.1 =
* 2016-1-23
* Added settings option to auto fill SKU for new products or products with no SKU
* Changed name of var $random_number to $random_SKU
* Set calls to ajax function as separate functions and calls


= 1.0 =
* 2015-12-28
* Initial release

== Upgrade Notice ==

= 1.0 =
* 2015-12-28
* Initial release
