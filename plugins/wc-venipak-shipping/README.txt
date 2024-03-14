=== Shipping with Venipak for WooCommerce ===
Contributors: shopup
Tags: Venipak
Requires at least: 4.4
Tested up to: 6.4.2
Stable tag: 1.20.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Venipak delivery method plugin for WooCommerce. Delivery via courier and pickup points.

== Description ==

* Delivery to customer address.
* Delivery to Venipak Pickup points and lockers. Pickup map is displayed in checkout for user convenience.
* Collection of money by cash or card with COD service.
* In order to use the extension you must have an active contract with Venipak. https://www.venipak.com/
* Additionally, you must have user credentials for API of Venipak. Please contact Venipak sales. https://www.venipak.com/

support email: hello@akadrama.com

== Installation ==

1. Install the plugin
2. Configure with your venipak details. You must have user credentials for API of Venipak. Please contact Venipak sales. https://www.venipak.com/
3. Create venipak shipping methods

== Screenshots ==

== Changelog ==

= 1.20.0 =
* Feature: HPOS support
* Feature: remember the last selected pickup point
* Fix: size restrictions for variations
* Fix: pickup list update

= 1.19.8 =
* Fix: Error log cleanup

= 1.19.7 =
* Fix: Sequence of labels

= 1.19.6 =
* Fix: Security vulnerability Cross Site Scripting (XSS)

= 1.19.5 =
* Fix: Set default products count for one label

= 1.19.4 =
* Fix: Load js and css only in cart or checkout pages

= 1.19.3 =
* Fix: Lockers list update period set to 1 day

= 1.19.2 =
* Fix: Locker weight conditions. It is possible now to create multiple locker shipping methods based on weight

= 1.19.1 =
* Fix: Multiple labels print order

= 1.19.0 =
* New Feature: Print multiple labels
* Fix: The courier method was not displayed because the minimum weight was not set
* Fix: Pickup selection aligment to the right

= 1.18.0 =
* New Feature: Return label service

= 1.17.13 =
* Fix: PHP warning

= 1.17.12 =
* Fix: Disable 30kg locker limit

= 1.17.11 =
* Fix: Pickup selector
* Fix: Shiping method title design
* Fix: Cod validation
