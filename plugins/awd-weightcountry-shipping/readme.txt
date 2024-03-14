=== AWD Weight/Country Shipping for WooCommerce ===
Contributors: Andy_P
Tags: woocommerce, commerce, ecommerce, shipping, weight, country, shop
Requires at least: 3.3
Tested up to: 3.5
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds per-country and weight based shipping cost calculation method to your WooCommerce store.  

== Description ==

If your WooCommerce store needs to calculate shipping charges based on cart weight and country of delivery this plugin is for you.

You can group countries that share same delivery costs (e.g. USA and Canada, European Union countries etc) or 
set cost on per-country basis. 

= Features =

* Set multiple shipping rates based on cart weight and delivery country
* Group countries sharing same rates and set rates once for all of them
* Unlimited groups of countries
* Unlimited rates

= Documentation =

After installation activate this shipping method. Go to WooCommerce->settings->shipping select AWD Weight/Country Shipping and thick enable box.
Rates are set based on "Country Groups". Country Groups are groups of countries (or a single country) that share same delivery rates.

For full instruction on how to use this plugin please go to [AWD Weight Country Shipping](http://www.andyswebdesign.ie/blog/free-woocommerce-weight-and-country-based-shipping-extension-plugin/) page.

== Installation ==

1. Upload 'woocommerce-shipping-awd' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Set your delivery rates in WooCommerce->settings->shipping

== Changelog ==

= 1.0.1 =
* Fixed a bug causing tax on shipping not being calculated
* Fixed a bug causing delivery being displayed as free when a country was in allowed countries list, but no rate was specified for this country.
* Fixed a bug causing delivery method being displayed to the user when no rate was specified for selected country of delivery
* Fixed some typos
* Changed some option labels to make them more clear

= 1.0 =
* First release