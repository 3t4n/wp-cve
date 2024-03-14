=== Products Restricted Users for WooCommerce ===
Contributors: carazo
Donate link: ttps://codection.com/go/donate-import-users-from-csv-with-meta
Tags: woocommerce, users, restrict, products, visibility
Requires at least: 3.4
Tested up to: 6.4.1
Stable tag: 0.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to restrict the visibility for some products. You can enable the functionality in every product you want. If you enable it, you will be able to set which users are going to be able to view and buy the product. Administrators and shop managers will always be able to view every products.

== Description ==

Sometimes you to restrict some products for a certain group of users. This plugin allows you to easily do it. In each product you will find a box to choose if you want to activate this functionality in this product. If yes, you will be able to fill a list of users which will be able to view and buy the product. The other ones, won't see it in the lists and if they try to browse to the product, they will be redirected to home page.

### **Basics**

*   Set in every product if you want to activate this functionality
*	You can choose between two restriction modes: deny to see this product, or allow to see the product but not to purchase it
*	In both cases, you will have to fill a list of users in this product, the users in the list won't suffer any restriction, all the others will suffer the restriction chosen
* 	Administrators and shop managers always won't be affected.

### **Usage**

Once the plugin is installed you can use it. Below Product data, you will find a box called "Users which can view and buy this product". There you can activate/deactivate this functionality, choose the restriction mode and fill the list of the users which can see it.

== Screenshots ==

1. Metabox with data to fill in each products

== Changelog ==

= 0.5.3 =
*   Ready for WooCommerce 8.2.2
*   Ready for Word 6.4.1
*   Ready for HPOS

= 0.5.2 =
*   Changed the way plugin classes are loaded, now we wait until all plugins are loaded to avoid problems with pluggable functions

= 0.5.1 =
*   Performance improved getting the list of users, now we only asks for users ID and nicename to generate the user list

= 0.5 =
*   Refactoring all the code

= 0.4.3 =
*   Ready for WordPress 6.1

= 0.4.2 =
*   Ready for WooCommerce 7.0
*   Branding changes

= 0.4.1 =
*   Big issue fixed, the plugin makes the products no purchasable when the users were not logged in although the plugin was not activated in the product or the product was saved as purchasable in the options of this plugin

= 0.4 =
*   Different fixes
*   Compatibility with Ultimate WooCommerce Auction Pro included to make it works also with auctions of this plugin

= 0.3.2 =
*   Description and readme improved

= 0.3.1 =
*   Description and readme improved

= 0.3 =
*   New restriction type, you can choose now between restricting accesing to products and make products non purchasable for users not selected

= 0.2.2 =
*   Plugin files sorted

= 0.2.1 =
*   We check if WooCommerce is not activated to avoid problems

= 0.2.0 =
*   Checked with latest WordPress and WooCommerce versions

= 0.1.0 =
*   First release

== Frequently Asked Questions ==

= Administrators and shop managers =

You cannot restrict the visibility of products to administrators and shop managers.

= Archive and shop pages =

Users which cannot view some products, won't see it in archive of categories, in archive of tags and also in shop page or any other list view.

= Single product page =

In this case, user will be redirected to homepage.

= Variations =

When you are working with variable product, you will set the visibility of each variable product, you cannot set it individually in each variation.

== Installation ==

### **Installation**

*   Install **WooCommerce Products Restricted Users** automatically through the WordPress Dashboard or by uploading the ZIP file in the _plugins_ directory.
*   Then, after the package is uploaded and extracted, click&nbsp;_Activate Plugin_.

Now going through the points above, you should now see the new metabox below the "Product data" of each product.

If you get any error after following through the steps above please contact us through item support comments so we can get back to you with possible helps in installing the plugin and more.
