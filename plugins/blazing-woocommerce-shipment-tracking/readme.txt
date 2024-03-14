=== BLAZING Shipment Tracking ===
Contributors: massoudshakeri
Tags: shipping, tracking, ups, usps, fedex, dhl, tnt, post, shipment, woocommerce, tracking number, package tracking, tracking link, carrier, courier, woocommerce shipment tracking, shipping details plugin, track, package
Requires at least: 2.9
Tested up to: 6.3.1
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds courier and tracking number to the woocommerce order, and a dedicated email to send shipment tracking info to the customer.

== Description ==

This plugin is free, and will not have a pro version or a subscription for shipment tracking. If you have no problem with sharing all of your store's shipment information with another company, you can use another paid plugin. However, this plugin is designed for those who prefer to keep their data private.

The concept is very simple: In the settings page you list all the couriers you need, along with their tracking url. Also you can modify the phrases related to Shipment tracking displayed on customer's order history page and emails. Then for every order that you enter shipment information, that information will be shown to the customer.

= How to Define you List Of Couriers =

In the settings page (Settings->BLZ-Ship-Tracking in Dashboard), there is a textbox for couriers' list. The format of the list is JSON. If you search internet, you will find many "Online JSON Validators". You can write your list in that textbox, or a text editor, then validate it in online validators. If that list has no problem, you can save it as your list of couriers.

= There are a few points to consider: =

*   For every couriers, you should introduce a slug, which is going to be the internal name of that courier. The slug must be a lower-case name without any space or special characters, other than dash ('-') or underscore ('_').
*   If you provide a url for a courier, the plugin can show a tracking button to the customer, which directs the customer to that provided url.
*   In the provided url for a courier, {tracking_number} will be replaced with the order's tracking number; {shipping_postcode} will be replaced with the order's shipping postal code (zip code); and {shipping_country} will be replaced with the order's shipping country code.
*   The number of couriers is not limited, but keeping the list short and relevant is advised.
*   One unnecessary comma, or a missing one, can invalidate json format. So please be careful in modifying the list.
*   - Make sure all quotes are ASCII double quotes or single quotes.
*   - Every item should be in one line, and do not break them in two lines
*   - After colon ‘:’ please add a space for clarity.
*   - After the last item do not add ‘,’

###Entering tracking info on order page
The plugin allows you to enter/modify shipment info on the order page. After an order is complete, simply enter the tracking number and select a courier on WooCommerce order, the same info will be displayed at customer's order history page.

### Shipment Tracking Email
If shipment info is added to the order, the "Order Complete" email will contain the shipment info as well, for customer to track the shipment. Just in case you need to modify the shipment info, or ship the order in installments, this plugin allows you to send another dedicated email for shipment tracking. It adds a button to the order page named "Email Tracking Info", which sends the tracking info to the customer, in a dedicated email. The template of the email can be modified in the Woocommerce->Settings->Emails section.

= Documentation =
The detailed and updated version of documentation can be found in this link:

http://blazingspider.com/plugins/blazing-woocommerce-shipment-tracking

== Installation ==

= From within WordPress =
1. Visit 'Plugins > Add New'
2. Search for 'BLAZING Shipment Tracking'
3. Activate the plugin from your Plugins page.
4. Go to "after activation" below.

= Manually =
1. Upload the `blazing-woocommerce-shipment-tracking` folder to the '/wp-content/plugins/' directory
2. Activate the 'BLAZING Shipment Tracking plugin through the 'Plugins' menu in WordPress
3. Go to "after activation" below.

= After activation =
1. Go to Settings->BLZ-Ship_Tracking page to setup the plugin the way you need.
2. In the settings page you list all the couriers you need, along with their tracking url..
3. You're done!

*** This plugin requires at least PHP 5.4

*** This plugin requires at least WooCommerce 2.1

== Frequently Asked Questions ==


== Screenshots ==

1. It shows where you can find the Settings page of this plugin
2. Settings page
3. Blazing Shipment Tracking Box at "Edit Order" page, where you can add shipment tracking info for the order
4. Shipment Tracking Email template in Woocommerce Settings
5. How to change settings of Shipment Tracking Email

== Changelog ==

= 2.1.0 =
Tested for Wordpress 6.3, and some input sanitization improvements

= 1.2.0 =
added {shipping_country} replacement in the courier's url

= 1.1.0 =
added {shipping_postcode} replacement in the courier's url

= 1.0.2 =
Fixed an error on settings dependencies

= 1.0.0 =
First Commit

== Upgrade Notice ==

= 1.2.0 =
added {shipping_country} replacement in the courier's url

= 1.1.0 =
added {shipping_postcode} replacement in the courier's url
