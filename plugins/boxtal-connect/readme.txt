=== Boxtal - Shipping solution ===
Contributors: Boxtal
Tags: shipping, delivery, parcel, parcel point, free, Mondial Relay, Colissimo, Chronopost, DHL, UPS, Relais Colis, Colis Privé
Requires at least: 4.6
Tested up to: 6.3
Requires PHP: 5.6.0
Stable tag: 1.2.22
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Negotiated rates for all types of shipping (home, relay, express, etc.). No subscription, no hidden fees.

== Description ==

Your orders are synchronized with your Boxtal account, where you can automate shipping rules to generate your shipping labels.

Ship with all types of carriers (Colissimo, Mondial Relay, Chronopost, Colis Privé, UPS, …), with or without insurance, options, ... You benefit from negotiated rates, without volume conditions, without subscription, without hidden costs.

Tracking is automatically synchronized with your orders and is available at any time in your customer’s account pages.

A single invoice for all your shipments and a single customer service to manage all delivery issues.

Add a parcel point map to your checkout.

== Installation ==

= Minimum requirements =
* WooCommerce version: 2.6.14 or greater
* WordPress version: 4.6 or greater
* Php version: 5.6.0 or greater

= Step by step guide =

* Have a look here: https://help.boxtal.com/fr/en/article/getting-started-bc-wc

== Screenshots ==

1. Synchronize your orders, save time
2. Ship with the best carriers
3. A single invoice, a single customer service
4. A parcel point map in your checkout

== Changelog ==

= 1.2.22 =
* Parcel point address is now correctly updated on change

= 1.2.21 =
* Added subscription compatibility
* Synchronizing orders now ignore virtual products

= 1.2.20 =
* Updated readme

= 1.2.19 =
* Fixed uninstall issue for multistores

= 1.2.18 =
* Added HPOS compatibility
* Fixed Deprecation issues

= 1.2.17 =
* Fixed an error when displaying a warning notice

= 1.2.16 =
* Order notes are now sent private again (changes overwritten by 1.2.15)

= 1.2.15 =
* Fixed an issue when displaying a parcel point address while ordering
* Fixed a compatibility issue with Colissimo plugin
* Fixed an issue when saving parcel point networks for a shipping order
* Fixed an issue when synchronizing orders with variable articles
* Tested plugin with wordpress 6.2.2

= 1.2.14 =
* Order notes are now sent private
* Tested plugin with wordpress 6.0

= 1.2.13 =
* Fixed an issue when exporting orders with a missing product
* Tested plugin with wordpress 5.9

= 1.2.12 =
* Fixed an issue to display the map

= 1.2.11 =
* Fixed warnings on wordpress 5.8
* Fixed an issue causing parcel points to not be displayed correctly
* Added parcel point and tracking dev hooks
* Parcel points is now reseted if address changes 

= 1.2.10 =
* Updated tests with wordpress 5.7.1

= 1.2.9 =
* Fixed PHP8 warnings
* Updated readme

= 1.2.8 =
* Tested plugin on woocommerce 4.4.0 and wordpress 5.5.0

= 1.2.7 =
* Added missing translation

= 1.2.6 =
* Removed old translation files

= 1.2.5 =
* Renamed Boxtal Connect rate to Boxtal Connect
* Improved settings page
* Improved Boxtal Connect settings page
* Removed parcel points from local pickup shipping methods
* Fixed a translation issue on Boxtal Connect settings page
* Added a configuration endpoint for remote debugging
* Parcel point scripts are now loaded only on checkout page
* Fixed a saving issue on Boxtal Connect settings page
* Fixed a display issue on front order page

= 1.2.4 =
* Updated WordPress compatibility

= 1.2.3 =
* Fixed warnings with PHP7
* Fixed an issue when saving a shipping method rates
* Added a get-configuration endpoint for boxtal support

= 1.2.2 =
* Updated WordPress compatibility
* Fixed a display issue with the parcel point popup on mobile

= 1.2.1 =
* Added missing translation keys on pricing rules page

= 1.2.0 =
* Added selected parcelpoint infos on front and admin order page
* Fixed an issue causing the plugin to empty all the session instead of only it's own attributes

= 1.1.9 =
* fixed an issue when exporting orders with articles with variation

= 1.1.8 =
* fixed an issue when exporting orders with articles with variation (invalidated)

= 1.1.7 =
* added a quick installation guide on the settings page
* fixed an issue when changing pricing rules order

= 1.1.6 =
* corrected bad javascript compression

= 1.1.5 =
* removed limit of manageable weight decimals

= 1.1.4 =
* added order shipped and delivered events
* added feature to associate an order status to order shipped and delivered events

= 1.1.3 =
* cross browser compatibility

= 1.1.2 =
* additional authenticity check on api requests

= 1.1.1 =
* fix import bug

= 1.1.0 =
* added Boxtal Connect rate

= 1.0.12 =
* improved maps css
* fix on order modal view tracking

= 1.0.11 =
* small fix on map markers clearing

= 1.0.10 =
* hide fopen error in php library

= 1.0.9 =
* fixed bug on non woocommerce shipping methods display

= 1.0.8 =
* improved fopen healthcheck in php library

= 1.0.7 =
* hide fopen error in php library

= 1.0.6 =
* add curl support for php library

= 1.0.5 =
* fixed ajax functions naming conflicts with other plugins

= 1.0.4 =
* fixed nonce naming conflicts with other plugins
* improved pairing update process

= 1.0.3 =
* removed default limit for order retrieval

= 1.0.2 =
* improved third party shipping plugins compatibility

= 1.0.1 =
* updated rest api verbs to circumvent server limitations

= 1.0.0 =
* first stable release
