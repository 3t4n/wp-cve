=== Best Selling Products for WooCommerce ===
Contributors: blazeconcepts, arapps92
Tags: woocommerce, best selling, products, categories, category, widget, filter, list, sidebar
Donate link: https://www.paypal.me/blazeconcepts
Requires at least: 4.9
Tested up to: 5.5.3
Requires PHP: 5.6
Stable tag: 1.3.1
WC requires at least: 3.0.0
WC tested up to: 4.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A widget and shortcode displaying your best selling WooCommerce products, with thumbnail, title, price, star rating and link to the product.

== Description ==
Display a list of your best selling WooCommerce products in a widget or shortcode. Simply add the widget to a sidebar or widget area, customise the settings and your list is created.

= Widget Options =
**Title** - Add a title to your product list.
**Category** - Choose one or multiple from a dynamically loaded drop down list of all WooCommerce product categories available on your website. Choose to include or exclude specific categories.
**Products Shown** - Show all products or only show a certain number.
**Thumbnail** - Show the product thumbnail.
**Star Rating** - NEW! Show product average star rating.

= Shortcode Options =
Simply include the `[woobsp]` shortcode into your posts or pages to show your best sellers. Options include:
* `category='shirts'` - Which categories to show product from. You can insert multiple categories by separating with a comma e.g. shirts,hats,shoes. Accepts the category slug or ID (Default: All Categories)
* `posts='2'` - How many products to show in your list (Default: 3)
* `thumbnail='yes'` - Show the product thumbnail in the list (Default: No)
* **NEW!** `stars='yes'` - Show the product's average star rating (Default: No)

Full shortcode example: `[woobsp category='shirts,shoes' posts='5' thumbnail='yes' stars='yes']`

**COMING SOON** - Choose to exclude specific categories using the shortcode.

== Installation ==
**Best Selling Products for WooCommerce** requires the [WooCommerce](https://wordpress.org/plugins/woocommerce/ "WooCommerce") plugin (at least version 3.0.0) to be installed.

= Via WordPress =
1. From the WordPress Dashboard, go to Plugins > Add New
2. Search for 'Best Selling Products for WooCommerce' and click Install. Then click Activate.
3. Go to Appearance > Widgets, and add the 'Best Selling Products Widget for WooCommerce' widget to a widget area.
4. Customise the settings: Title, Category, Products Shown, Thumbnail.

= Manual =
1. Upload the folder /woo-best-selling-products/ to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Appearance > Widgets, and add the 'Best Selling Products Widget for WooCommerce' widget to a widget area.
4. Customise the settings: Title, Category, Products Shown and Thumbnail.

== Screenshots ==
1. Widget settings
2. Product list in website sidebar
3. Shortcode example

== Frequently Asked Questions ==
= What does the widget/shortcode do? =
The Best Selling Products for WooCommerce plugin shows a list of your store's best selling WooCommerce products. You choose what categories to include or exclude, how many products should be shown, and if you want the product thumbnail and average star rating shown or not. Simple.

= How do I use the shortcode? =
Include the `[woobsp]` shortcode into your post or page. Parameters include:
* `category='shirts'` - Only show products from specific categories. You can insert multiple categories by separating with a comma e.g. shirts,hats,shoes. Accepts the category slug or category ID. (Do not include to use the default setting: Show products from all categories)
* `posts='2'` - How many products to show in your list. (Do not include to use the default setting: 3 products)
* `thumbnail='yes'` - Show the product thumbnail in the list. (Do not include to use the default setting: Don't show thumbnails)
* `stars='yes'` - Show the product's average star rating (Default: No)

Full shortcode example: `[woobsp category='shirts,shoes' posts='5' thumbnail='yes' stars='yes']`

= Can I use the shortcode in template files? =
Yes, simply use the special WordPress `do_shortcode()` function and add your parameters if required.

For example: `echo do_shortcode( '[woobsp]' );`

== Changelog ==
= 1.3.1 =
* Fix for removal of dev line left in

= 1.3.0 =
* Plugin name update
* Settings issue fix
* Compatibility version updates

= 1.2.0 =
* Category list now shows all hierarchy levels
* Added product count to category list
* Updated deprecated woocommerce placeholder image function

= 1.1.0 =
* Added option to add the product's average star rating to the (Widget and Shortcode)
* Added option to now include or exclude selected categories (Widget only. Shortcode coming soon!)
* Added ability to select multiple categories in the widget
* Updated WooCommerce version
* Fixed several spelling mistakes

= 1.0.0 =
* Initial release
