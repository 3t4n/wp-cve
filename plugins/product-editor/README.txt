=== Product Editor ===
Contributors: @devhedgehog
Donate link: https://yoomoney.ru/to/4100117683416192
Tags:  woocommerce, product, products, variable product, price, sale price, edit, editor, bulk, product bulk, products bulk, bulk product, bulk edit
Stable tag: 1.0.14
Requires PHP: 5.6
Requires at least: 5.0
Tested up to: 6.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Completely free plugin for convenient bulk or individual editing of woocommerce products.
Simple, variable and external product types are supported.
It is possible to change the price, sale price and sale dates.


= Features =

* increase / decrease prices by a fixed value or percentage
* multiplying existing prices by a value
* change sale prices relative to regular prices
* change tags
* rounding prices with a required precision
* dynamic price changes
* ability to undo changes
* search by standard (category, tags, status) and custom taxonomies

I would appreciate it if you leave a review of the plugin.

If you need additional functionality or just want to financially support the development of the plugin - write to dev.hedgehog.core@gmail.com

https://www.youtube.com/watch?v=mSM_ndk2z7A

== Frequently Asked Questions ==
= If I refresh the page or an error occurs while changing items, how do I know which products have been changed and which have not? =
The plugin makes all changes transactionally. This means that either the operation will be performed completely for all goods, or it will not be performed at all.

= Why does a bulk change fail with an error after a long timeout? =
The most likely reason is that the execution process is taking longer than allowed on your server. Try increasing the allowed script execution time, or change products in smaller portions.

== Installation ==

1. Unzip the download package
1. Upload `product-editor` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1.
2.
3.
4.
5.

== Changelog ==

= 1.0.14 =
* added: custom taxonomy search feature

= 1.0.13 =
* bugfix: implicit limit on the number of products that can be changed at a time
* added: sticky table header
* added: the ability to change product tags

= 1.0.12 =
* bugfix: search did not work when the new woocommerce navigation interface option was enabled

= 1.0.11 =
* bugfix: categories are not shown in some cases
* added: search form reset button

= 1.0.10 =
* added filtering by statuses, missing categories and tags

= 1.0.9 =
* bugfix: menu item was not shown for shop manager role
* added Portuguese - BRAZIL translate

= 1.0.8 =
* added the ability to set a zero price.
* added the ability to not change products with a zero price in bulk editing.

= 1.0.7 =
* added cache reset after product changes

= 1.0.6 =
* bugfix cyrillic search

= 1.0.5 =
* added tag-search

= 1.0.4 =
* added dynamic price changes functionality
* added progress bar for bulk changes
* undo functionality

= 1.0.3 =
* bugfix fatal error
* added rounding an integer part of number

= 1.0.2 =
* added multiplying existing prices by a value
* added rounding prices with a required precision
* added external products type
* added links to product editing pages

= 1.0.1 =
* increase\decrease regular price issue fixed
* applying operations to variation parents issue fixed
* added support for decimal numbers
* extra spaces at dates columns issue fixed

== Upgrade Notice ==

= 1.0.14 =
* added: custom taxonomy search feature

= 1.0.13 =
* bugfix: implicit limit on the number of products that can be changed at a time
* added: sticky table header
* added: the ability to change product tags

= 1.0.12 =
* bugfix: search did not work when the new woocommerce navigation interface option was enabled

= 1.0.11 =
* bugfix: categories are not shown in some cases
* added: search form reset button

= 1.0.10 =
* added filtering by statuses, missing categories and tags

= 1.0.9 =
* bugfix: menu item was not shown for shop manager role
* added Portuguese - BRAZIL translate

= 1.0.8 =
* added the ability to set a zero price.
* added the ability to not change products with a zero price in bulk editing.

= 1.0.7 =
* added cache reset after product changes

= 1.0.6 =
* bugfix cyrillic search

= 1.0.5 =
* added tag-search

= 1.0.4 =
* added dynamic price changes functionality
* added progress bar for bulk changes
* undo functionality

= 1.0.3 =
* bugfix fatal error
* add rounding an integer part of number