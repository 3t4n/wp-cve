=== Bosta WooCommerce ===

Contributors: Bosta
Donate link: 
Tags: Bosta, WooCommerce, Woocom, Woo Commerce, Shipping, shiping
Requires at least: 5.0
Requires PHP: 7.0
Tested up to: 6.1.1
Stable tag: 3.0.11
WC requires at least: 2.6
WC tested up to: 7.2.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

The official Bosta for WooCommerce plugin allows you to automate your e-commerce order process.

== Description ==

Bosta's official extension for WooCommerce on Wordpress.

== Features ==

1. Fast and easy **Shipping your orders**.
1. Automatically receive a **tracking code** for each order.
1. **Bulk Sending Orders** allows you to send multiple orders to bosta at once. 
1. **Bulk Printing AirWaybill** allows you to print multiple airwaybill for bosta orders at once.
1. **Fetching Latest Status From Bosta** allows you to tracking your order status from bosta.

== Installation & Configuration ==

1. Upload the downloaded plugin files to your `/wp-content/plugins/bosta-woocommerce` directory, **OR** install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Go to WooCommerce-->Settings->Shipping and select the upper Bosta unit to configure the plugin.
1. Add your business APIKEY recived from bosta Support.

== Screenshots ==

1. Configure plugin by adding APIKEY.
2. Select the orders you need to send by bosta.
3. Print AirWaybill for this orders.
4. Create Your Pickup Request.
5. Keep tracking your orders status.

== Changelog ==

= 1.0 =
* First public release

= 1.1 = 
* Adding Pickup Request Feature

= 1.2 = 
* Creating notes
* Creating Business Reference 

= 1.3 = 
* Fixing Bugs

= 1.4 = 
* Applying Right Addresses

= 1.5 = 
* Fixing Bugs

= 1.6 = 
* Updating version to match latest wordpress version

= 1.7 = 
* Fixing Bugs

= 1.8 = 
* Fixing Bugs

= 1.9 = 
* Fixing Bugs

= 2.0 = 
* Addresses new structure

= 2.1 = 
* Addresses new structure

= 2.2 = 
* Fixing Bugs

= 2.3 = 
* Add order tracking page with details and logs
* Native search (search with bosta tracking number and cutomer phone)
* Filter on Bosta status
* Configure descriptions to be showed/disappeared on AWB (Default is shown)
* Configure order reference to be showed/disappeared on AWB (Default is shown)

= 2.4 =
*  View scheduled pickups
* Add ability to choose from multiple pickuplocations in pickup creation
* Add ability to edit pickuplocation
* Add the ability to create and edit pickup locations, and select the default pickup location [Link to bosta app]

= 2.4.1 =
* Fix redirection bug

= 2.4.2 =
* Make The list of cities and zones to appear in Arabic language if user using Arabic.
* Sort cities and zones list.

= 2.4.3 =
* Handle cities network error.

= 2.5 =
* Cities and Districts separation and dependent oneach other.
* Fix place of description
* Fix number of items sent to bosta.

= 2.6 =
* Link shipping zones to bosta zones

= 2.7 =
* Fix PickupLocaion

= 2.8 =
* Handle plugin when server is down.

= 2.8.1 =
* Handle plugin when network is down.

= 2.8.2 =
* Get cities by countryId.

= 2.9 =
* Update cities/areas in KSA.

= 2.9.1 =
* Handle cities/areas when network is down.

= 2.9.2 =
* Handle cities/areas in setting.

= 2.9.3 =
* Upgrading tested by wordpress version.

= 2.9.4 =
* Security enhancements

= 2.9.5 =
* Add Allow to Open Package Option

= 2.9.6 =
* Add cash collection orders

= 2.9.7 =
* Remove receiver email from the order data

= 2.9.8 =
* caching bosta zones

= 2.9.9 =
* fixing caching country id

= 3.0.0 =
* fixing performance issue

= 3.0.1 =
* fixing uncovered zones

= 3.0.2 =
* fixing mapping issues

= 3.0.3 =
* fixing no APIKEY and no "Allow New Zones" option issues

= 3.0.4 =
* fixing KSA zones

= 3.0.5 =
* allow clear caching
* fixing missing districts

= 3.0.6 =
* Fixed refresh status timeout
* Performance enhancements

= 3.0.7 =
* Performance enhancements

= 3.0.8 =
* Bug Fixes

= 3.0.9 =
* Shippings Fees based on Cities instead of Districts

= 3.0.10 =
* Bug Fixes

= 3.0.11 =
* Bug Fixes