=== Country Based Restrictions for WooCommerce ===
Contributors: zorem,kuldipzorem,gaurav1092
Donate link: 
Tags: woocommerce, country restrictions
Requires at least: 5.3
Requires PHP: 7.0
Tested up to: 6.4
Stable tag: 5.1.1
License: GPLv2 
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Restrict your WooCommerce shop products to be purchasable only to specific countries!

== Description ==

If you have products that you want to be available to purchase only to customers to specific countries? Do you have products that you Do Not want to be available to purchase to specific countries?  The country based restrictions plugin by zorem works by the WooCommerce Geolocation or the shipping country added by the customer and allows you to restrict products on your store to sell or not to sell to specific countries 

== Key Features ==

* Restricted products to not be show completely 
* Hide restricted products completely from the shop and search but let users enter them from a direct link.
* Keep restricted products visible on the shop and search (but not purchasable)
* Choose for each product the restriction rule (include or exclude) and choose the countries to apply the rule

== PRO Features ==

* Bulk restrict products by Category, Tags, Attributes, Shipping class, Global(All Products).
* Bulk restrict products by CSV import
* Disable Payment methods by restected countries.
* Hide Product Price for Restricted Products
* Remove Single product rule by Bulk Action.

[Get the Country Based Restrictions Pro >](https://www.zorem.com/product/country-based-restriction-pro/)

[Documentation](https://docs.zorem.com/docs/country-based-restrictions-pro/)

[Click Here Purchase License](https://www.zorem.com/products/country-based-restriction-pro/)

== How it works ==

* Go to plugin settings and set up the general visibility options
* For each product in your catalog you can set if to allow or disallow to a list of countries.
* WooCommerce shipping country is used to determine what country the visitor is from a country which is restricted for a product, if a shipping country is not set, WooCommerce Geolocation is used.

You will need WooCommerce 3.0 or newer.
Does support translation.

== Frequently Asked Questions == 
= Can I sell a product in a specific country? =
Yes
= Can I restrict a product to sell in a specific country? =
Yes

== Installation ==

1. Upload the folder 'woo-product-country-base-restrictions` to the `/wp-content/plugins/` folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure you have set up "shipping countries" in WooCommerce general settings.
4. Edit a product to see your new options


== Changelog ==

= 3.6.1 =
* Dev - WC Compatibility added upto 8.5.2
* Fix - Patched a vulnerability concerning nonces in admin notices

= 3.6 =
* Dev - Added Compatibility with Wholesale for Woocommerce plugin
* Dev - WC Compatibility added upto 8.4.0
* Dev - WP tested upto 6.4

= 3.5 =
* Dev - Added Compatibility with PHP 8.2
* Dev - WC Compatibility added upto 8.2.1
* Dev - WP tested upto 6.4

= 3.4 =
* Dev - WC Compatibility with HPOS
* Dev - WC Compatibility added upto 7.8.1
* Dev - WP tested upto 6.2

= 3.3 =
* Dev - WC Compatibility added upto 6.8
* Dev - WP tested upto 6.0

= 3.2 =
* Dev - WC Compatibility added upto 6.3
* Dev - WP tested upto 5.9
* Enhancement - Added compatibility with Customer Reviews for WooCommerce
* Enhancement - Added Docs and Review link on plugins page
* Tweak - Updated the settings page design
* Fix - bug on checkout when billing country change

= 3.1 =
* Dev - WC Compatibility added upto 5.6

= 3.0 =
* Dev - WC Compatibility added upto 5.5.2

= 2.9.1 =
* Dev - WP Compatibility added upto 5.8

= 2.9.0 =
* Fix - Error - Warning: in_array() expects parameter 2 to be array, null given
* Fix - Fix the issue of Subscription variation restriction

= 2.8.9 =
* weak - updated settings design.
* Dev - WC Compatibility added upto 5.1

= 2.8.8 =
* weak - updated settings design.
* Dev - WP Compatibility added upto 5.7

= 2.8.7 =
* Dev - WC Compatibility added upto 5.0

= 2.8.6 =
* Fix - Tool Bar /debug mode CRITICAL Bug
* Fix - Related Products / WC products widgets - BUG

= 2.8.5 =
* Fix - Hide Completely - select a page to redirect BUG

= 2.8.4 =
* Fix - Fixed country and state dropdown slection issue in checkout page

= 2.8.3 =
* Tweak - updated settings design.
* Enhancement - Free plugin not run if PRO is activated.

= 2.8.2 =
* Tweak - updated settings design.

= 2.8.1 =
* Fix - Issues with geo location / widget detectors.

= 2.8.0 =
* Dev - WC Compatibility added upto 4.8
* Dev - WP Compatibility added upto 5.6

= 2.7.9 =
* Tweak - updated settings tab design.
* Enhancement - Added addons tab.

= 2.7.8 =
* Tweak - updated settings tab design.

= 2.7.7 =
* Tweak - changed label of option.
* Enhancement - Added options(Pro) for Country detection widget customize.
* Enhancement - Added CBR widget(Pro) for customer.

= 2.7.6 =
* Fix - css issue in settings.
* Tweak - updated settings tab design.
* Tweak - changed label of option.
* Enhancement - Added cart message option(Pro) for changes cart restriction message.

= 2.7.5 =
* Fix - css issue in settings design.
* Fix - issue of countries list dropdown option.

= 2.7.4 =
* Fix - css issue in settings design.

= 2.7.3 =
* Dev - WC Compatibility added upto 4.5
* Dev - WP Compatibility added upto 5.5
* Fix - css issue in settings design.
* Fix - issue of subscription variation product
* Tweak - design UI/UX.
* Tweak - changed label of option.
* Tweak - input textarea valid for Html tag/class.

= 2.7.2 =
* Fix - Invalid argument supplied for foreach().

= 2.7.1 =
* Dev - WC Compatibility added upto 4.3
* Dev - Added Compatibility with Visual composer

= 2.7 =
* Enhancement - Added Bulk Action option(Pro) for remove single product rule.
* Tweak - optimized Wp query to improve site speed.

= 2.6.9 =
* Enhancement - Added new Pro option of Global(All Products) in Bulk restriction setting.
* Fix - issue of WPML competibility.

= 2.6.8 =
* Enhancement - Added new Pro option of hide restricted product price.

= 2.6.7 =
* Fix - css issue in settings design.
* Tweak - updated settings tab design.
* Tweak - changed label of option.

= 2.6.6 =
* Fix - issue of redirect 404 error page.
* Tweak - design and changed label of option.
* Tweak - input textarea valid for Html tag/class.

= 2.6.5 =
* Tweak - design and changed label of option.

= 2.6.4 =
* Tweak - PRO tab design.
* Enhancement - Added option of 404 error page redirect to shop page in setting.

= 2.6.3 =
* Tweak - setting design.

= 2.6.2 =
* Tweak - design UI/UX.

= 2.6.1 =
* Fix - A black bar will appear at the top of the site.
* Fix - js issue on email customize.
* Fix - error.

= 2.6 =
* Enhancement - Added Pro option of 404 error page redirect to shop page in setting.
* Tweak - Improved setting design.

= 2.5.4 =
* Dev - WC Compatibility added upto 4.0
* Dev - WP tested upto 5.4

= 2.5.3 = 
* Enhancement - Added Pro tab in setting.
* Tweak - Updated setting design.

= 2.5.2 =
* Fix - bug fix.

= 2.5.1 =
* Enhancement - Added option of Hide Variation Products in Setting.

= 2.5 =
* Dev - CBR pro compatibility added.

[For the complete changelog](https://www.zorem.com/docs/country-based-restrictions-for-woocommerce/changelog/)
