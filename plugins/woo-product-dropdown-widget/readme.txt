=== Product Dropdown Widget for WooCommerce ===
Contributors: Razorfrog
Tags: woocommerce, widget, categories, products
Donate link: https://razorfrog.com/
Requires at least: 3.1
Tested up to: 6.3.2
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display and link to WooCommerce products in a dropdown menu widget. Allows for display of all products or products in individual categories as well as multiple sorting methods including price and most reviewed. 

== Description ==
This plugin creates a widget to display and link to WooCommerce products by category in a dropdown menu. Visitors to your site will have one-click access to products in your sidebar (or other widgetized areas) without valuable real estate being used up by a regular products list. 

**Plugin options include the abilities to:**

* display all products or just products in a single category
* sort by product name, price, total sales, number of reviews, date published, date last modified, or random
* limit the number of products shown

This plugin is also compatible with the [Post Types Order](https://wordpress.org/plugins/post-types-order/) plugin, so you can also sort by your own customized menu order if desired.

With these options you can use this plugin for a variety of uses. You could use multiple instances of the widget to have product dropdowns for each of your categories; you could display the top 5 most frequently bought products in a specific category; you could display the top 20 most reviewed products in your entire store or just in a specific category; and so on. 

**Note**

For sites with a very large number of products, it is not recommended that you display all products in a single dropdown because it would be both heavy on server resources and also just be difficult to use.

== Installation ==
1. Install the plugin (upload the plugin folder to the `/wp-content/plugins/` directory)
1. Activate the plugin through the \'Plugins\' menu in WordPress
1. Add the widget to a sidebar under Appearance > Widgets and configure the widget using the options provided

== Frequently Asked Questions ==
= What are some example uses of this plugin? =
The widget can be configured to show individual product categories, an \"All Products\" dropdown, or specific limited displays based on sort order such as: Top 5 Most Reviewed Products, Top 10 Most Sold Products, or 15 Random Products.

= Why are my dropdowns out of order when using the [Post Types Order](https://wordpress.org/plugins/post-types-order/) plugin? =
The Post Types Order Plugin defaults to overriding any wp_query to sort by menu order.  Visit Settings > Post Types Order and uncheck the Auto Sort option to avoid this problem. 

== Screenshots ==
1. Backend view example
2. Frontend view example

== Changelog ==

1.1.3

* Fixed undefined array keys
* WP Core compatibility update

1.1.2

* WP Core compatibility update

1.1.1

* WP Core compatibility update

1.1.0

* UI improvements 
* WP Core compatibility update

1.0.1

* WP Core compatibility update

1.0.0

* Initial plugin creation