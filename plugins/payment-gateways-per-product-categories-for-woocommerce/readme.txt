=== Payment Methods by Product & Country for WooCommerce ===
Contributors: wpcodefactory, omardabbas, karzin, anbinder, algoritmika, kousikmukherjeeli
Tags: woocommerce, payment gateway, /conditional-payments, payment by product, payment by country
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 1.7.9
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Use products and countries conditional rules to show/hide gateways, increase profit margins & optimize operations for your products by restricting payment methods in WooCommerce checkout page

== Description ==

Every payment gateway has its own advantages/disadvantages, they are not equal when it comes to fees, adaptability from customers, and even in security as some gateways are known for larger fraud cases than others.

Using conditional/custom payment methods for your store to restrict what gateways appear for specific products comes handy here, where you will be able to show/hide payment gateways based on what's in the cart.

For most stores, PayPal is considered an expensive payment gateway, and when you're selling expensive products (hundreds or probably thousands), you want to prevent users from checking out using PayPal and instead, use wire transfers or even local payment gateways that offer competitive rates, where you can keep your profit margins higher.

**Restricting/Customizing Gateway by Country**

**Pro Features:** Same applies for countries, for cross-border transactions, some payment gateways might charge you differently based on country, which is our newest addition to the plugin (restrict payment method by country).
That’s why it might be the case for some stores, profit margins for specific products or categories are so minimal, therefore they want to limit selling these products using a specific payment gateway that has reasonable transaction fees.
Other stores that sell abroad want to offer their international customers payment gateways that are more convenient for them, so a French-based store that sells to Italy might want to show Italian customers local payment gateways that only appear for them, and not for other countries.
This is exactly what this plugin does, allows you to control what payment gateways to show (or hide) based on product, product category, product tags, or even countries.
== The plugin works in 2 modes: ==
It lets you select what payment gateways to show if a product category or tag is added (meaning hide all other gateways in this case).
Second, lets you select what gateways to hide when a selected product category or product is in the cart (i.e. all other gateways will appear).

=== Examples: ===

Category A is sold using all payment gateways, no restrictions.

Category B is sold using all gateways except PayPal.

Category C is sold only using wire transfer (very high price).

You can configure the plugin to reflect the above 3 cases like the following:

Category A: untouched, won't be included/excluded from the plugin settings.

Category B: Under PayPal gateway, we insert category B on the "Excluded" section.

Category C: Add it to the "Excluded" section of all other gateways.

Note: Adding category C to the "Included" section of wire transfer will hide this gateway from all other categories, so you have to be either "allow all except" or "hide all except"

== Intuitive & easy to use interface ==

By default, the plugin doesn't change anything on installation & activation, once you decide what gateways to show/hide for product categories or tags, go to WooCommerce >> Settings >> Payment Gateways per Products" and under desired tab (category or tag), start including/excluding categories on respective gateways you've set.

== What payment gateways this plugin supports? ==

In short, ALL of them, any gateway (standard or customized) that is installed & enabled on Woo >> Settings >> Payments will be supported, and appear on plugin settings, where you will be able to conditionally control what product categories or tags appear on each gateway.

== Where this plugin can be useful? ==

1. Expensive products: This might be the most use case for this plugin, you want to restrict customers buying expensive items to pay using wire transfer only.

2. Cheap products: Imagine you have to deal with a wire transfer or cash on delivery for an $7 item, does that make sense to your business operations? The plugin can restrict gateways based on products of your choice.

3. Subscription products: when you sell products that need monthly/yearly renewal, you can't/shouldn't allow checking out on gateways that don't support automatic renewals (like CoD), instead, here you can restrict users to checkout using PayPal for example.

4. Products with very low margins: Some products (even sold at good price points) might have low margins (couple of dollars) because of the competition, in such conditions, you might want to limit the allowed payment methods to those who offer very low fees.

== Pro Version ==

The plugin will allow you to control almost everything you need with its standard version, but if you want to go further and use the Pro version, you will get these options as well:

1. Allow hiding/showing payment methods based on product level (not a category or tag, but by product), you can even use it on variation level!

2. Choose fallback payment method: If two contradicting rules are in cart (mixed products from different rules), resulting in not showing any method in cart, this option will allow you to select a fallback gateway in such cases.

3. Restrict payment gateways by country, you can hide/show payment method based on billing country field in checkout. 

4. You don't need any of the above, but you're just enjoying the free version and want to support us :)

= Demo Store =

If you want to try the plugin features, play around with its settings before installing it on your live website, feel free to do so on this demo store:
URL: https://wpwhale.com/demo/wp-admin/
User: demo
Password: G6_32e!r@

= Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Please visit [Payment Gateways per Products for WooCommerce plugin page](https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/).

== Screenshots ==

1. Main Page
2. Specify settings per category
3. Specify settings per tag

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Payment Gateways per Products".

== Changelog ==

= 1.7.9 - 12/02/2024 =
* WC tested up to: 8.5.
* Tested up to: 6.4.
* New - multiple payment by country restrictions.

= 1.7.8 - 09/11/2023 =
* Declare HPOS compatibility.
* WC tested up to: 8.1.

= 1.7.7 - 21/09/2023 =
* Update logo.

= 1.7.6 - 21/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.

= 1.7.5 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.7.4 - 31/03/2023 =
* Fix plugin name.
* Move to WPFactory.

= 1.7.3.1 - 15/02/2023 =
* Bug fix appeared in version 1.7.3 causing PHP fatal error
* Verified compatibility with WooCommerce 7.4

= 1.7.3 - 13/02/2023 =
* New feature (Pro): enable/disable payment method by country
* Verified compatibility with WooCommerce 7.3

= 1.7.2 - 04/11/2022 =
* Verified compatibily with WordPress 6.1 & WooCommerce 7.0

= 1.7.1 - 12/06/2022 =
* Verified compatibily with WordPress 6.0 & WooCommerce 6.5

= 1.7 - 18/04/2022 =
* Fixed an uncaught error related to a JS file (select2)
* Fixed a bug in client area where gateways were hidden without products in cart
* Verified compatibily with WooCommerce 6.4

= 1.6.4 - 28/01/2022 =
* Allowed mixing includes/excludes while giving priority to product-defined settings over category/attribute
* Verified compatibily with WooCommerce 6.2 

= 1.6.3 - 28/01/2022 =
* Verified compatibily with WordPress 5.9 & WooCommerce 6.1

= 1.6.2 - 10/11/2021 =
* Fixed a bug in WPML compatibility when switching between languages settings were lost
* Verified compatibility with WooCommerce 5.9

= 1.6.1 - 29/10/2021 =
* Fixed a bug in showing category IDs instead of names for some users after 1.4.5

= 1.6 - 26/10/2021 =
* Fixed a bug in 1.4.5 preventing Pro users from using Pro features
* Verified compatibility with WooCommerce 5.8

= 1.4.5 - 19/10/2021 =
* Fixed a bug showing category IDs instead of category names
* Allowed choosing payment method from product edit page directly

= 1.4.4 - 16/10/2021 =
* Fixed multiple issues (error 500) for stores with thousands of products
* Verified compatibility with WooCommerce 5.7

= 1.4.3 - 30/08/2021 =
* Checked & verified compatibility with WooCommerce 5.6

= 1.4.2 - 16/08/2021 =
* Fixed a bug not showing specific custom gateways
* Added an integration to manually added orders emails to show/hide gateways as in store

= 1.4.1 - 25/07/2021 =
* Tested compatibilty with WC 5.5 & WP 5.8

= 1.4 - 16/05/2021 =
* New feature: Fallback gateway to show a selected gateway if mixed products (with different gateways) are in cart.
* Verified compatibility with WooCommerce 5.3

= 1.3.4 - 20/04/2021 =
* Tested compatibilty with WC 5.1 & WP 5.7

= 1.3.3 - 28/02/2021 =
* Tested compatibilty with WC 5.0

= 1.3.2 - 27/01/2021 =
* Tested compatibility with WP 5.6 & WC 4.9

= 1.3.1 - 21/11/2020 =
* Tested compatibility with WC 4.7

= 1.3 - 15/08/2020 =
* Tested compatibility with WP 5.5
* Tested compatibility with WC 4.3

= 1.2.1 - 20/11/2019 =
* Dev - Code refactoring.
* WC tested up to: 3.8.
* Tested up to: 5.3.
* Plugin author changed.

= 1.2.0 - 12/07/2019 =
* Dev - Advanced Options - Add filter - Default value set to `On "init" action`.
* Dev - Per Products - Adding product ID to the list of products in settings.
* Dev - Code refactoring.

= 1.1.1 - 24/05/2019 =
* Dev - Admin Settings - "Your settings have been reset" notice added.
* Tested up to: 5.2.
* WC tested up to: 3.6.

= 1.1.0 - 29/11/2018 =
* Fix - Text domain fixed.
* Dev - Products - "Add variations" option added.
* Dev - Admin settings restyled: divided into separate ("Categories", "Tags" and "Products") sections (and "Enable section" options added).
* Dev - Plugin renamed from "Payment Gateways per Product Categories for WooCommerce" to "Payment Gateways per Products for WooCommerce".
* Dev - Advanced Options - "Add filter" option added.
* Dev - Code refactoring.
* Dev - Plugin URI updated.

= 1.0.0 - 28/08/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
