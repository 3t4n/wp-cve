WooCommerce Sendle Shipping Method
Contributors: softwarehtec
Donate link: http://www.softwarehtec.com/contact-us/
Tags: shipping-delivery, shipping zones, zones, woocommerce, shipping, sendle, postcode, deliver, australia, post, tracking, ordering, parcel, softwarehtec, tracking, widget, tracking widget
Requires at least: 3.0
Tested up to: 5.5
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==
<strong>Please update to the latest plugin which is using new Sendle API URL. Old API URL will be unavailable in the next few months?</strong>

<strong>The API supports the international shipping addresses if the pickup address in Sydney</strong>

Sendle delivers parcels door-to-door across Australia at flat rates cheaper than post. Send 25kg from $5.98. Save time with fast ordering & easy tracking.

Including

* Shipping Method.
* Support international shipping addresses Compatible with WooCommerce Shipping Zones.
* Backwards compatibility for methods existing before zones existed.
* Real-time tracking information.
* Parcel Tracking Widget.
* Parcel Tracking shortcode - [sendle_tracking].
* Turn On/Off taxable feature.
* PO BOX Detection.
* Parcel Lockers Detection.
* User defined plan name.
* Shipping Tracking link.
* Sendle Shipping Label Generating Button.
* Receiving tracking ID from Sendle and save with the order.
* Allow Admin download Sendle Shipping Label PDF File
* Allow Admin set default "sender pickup instructions".
* Allow Admin set default "receiver instructions".
* Allow Admin set sender's contact info.
* Allow Admin set sender's pickup address.
* Allow Admin set additional handling fee (can set negative number as the discount or free shipping for the customers).
* Minimum weight
* Debug mode with log file viewer
* Hide method if anything with a side over 120cm
* Requesting a quote with your Sendle ID and API key will return the quote for the relevant account’s plan only.

Add-ons ( Please Contact us to get files )

* Support WC Vendors
* Support Woocommerce Checkout Add Ons
* Support Over 25kg

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-shipping-sendle` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the WooCommerce->Settings->Shipping->Sendle to configure the plugin


== Frequently Asked Questions ==
= Has it been updated to allow for international shipping as yet =

The API does not yet support sending to International addresses. Sendle are expecting to release a new version before the end of the year.

= Where can I find my Sendle API key =

To obtain your Sendle API key, Please read the following article.

https://support.sendle.com/hc/en-us/articles/210798518-Sendle-API

= How to use the parcel tracking widget =

You can add the widget "Sendle Tracking" from Appearance -> Widgets for your sidebar

= How to use parcel tracking shortcode =

You can place the shortcde [sendle_tracking] on your post / page content

= How to enable Sendle Tracking widget and shortcodes

You have to fill all fields from Sendle Tracking Setting page

admin -> side menu -> WooCommerce -> Sendle Tracking Setting

= How to check debug data =

After "debug" was enabled, you can check log info from

/wp-content/plugins/woo-sendle-shipping-method/error.log

= I have enabled and configured the Sendle Shipping method but still not see it from checkout page =

Double check if you assign weight for each product and total weight of an order should bee less than 25 kg. If you assign height, width and length to each product, Sendle will only support max 0.1m3 per box

= How can I create a Sendle tracking link for customer =
1. Create a new page (e.g http://www.softwarehtec.com/sendle_tracking/) and insert the short code [sendle_tracking] to the body of the page
2. Tracking link format [ http://www.softwarehtec.com/sendle_tracking/?reference=Sendle Reference Number ]

== Changelog ==
= 1.9 =
* Requesting a quote with your Sendle ID and API key will return the quote for the relevant account’s plan only.

= 1.8.2 = 
* Fixed headers already sent issue

= 1.8.1 = 
* Tested up to 5.3

= 1.8.0 = 
* Parcel Lockers Detection

= 1.7.2 =
* Fixed No delivery instructions are allowed when booking at satchel rates error

= 1.7.1 =
* Added filters

= 1.7.0 =
* Added filters

= 1.6.9 =
* Fixed a minor issue

= 1.6.8 =
* Hide method if anything with a side over 120cm

= 1.6.7 =
* Added "Add return fee as delivery price" option
* Fixed minor issues

= 1.6.6 =
* Fixed issues with PHP 7

= 1.6.5 =
* add Add-ons

= 1.6.4 =
* add user agent

= 1.6.3 =
* fixed headers already sent error

= 1.6.2 =
* fixed postmeta issue


= 1.6.1 =
* fixed cookies issue

= 1.6.0 =
* Support International address

= 1.5.8 =
* Fixed pickup date issue

= 1.5.7 =
* added closed button for top banner.

= 1.5.6 =
* Fixed pickup business date issue.

= 1.5.5 =
* Add Minimum weight

= 1.5.4 =
* Add Sendle Shipping Label PDF url

= 1.5.3 =
* Fixed shipping zone tax issue

= 1.5.2 =
* Removed invisible characters

= 1.5.1 =
* Changing the name of Sendle Order to Sendle Shipping Label

= 1.5.0 =
* Creating Sendle Order Automatically

= 1.4.9 =
* add custom plan name for Easy,Premium and Pro.
* Accept tracking reference via url parameter 

= 1.4.8 =
*PO BOX detection feature

= 1.4.7 =
* Support free shipping when set large negative number for the extra cost

= 1.4.6 =
* Add "taxable" button

= 1.3.6 =
* Shipping price will be based on weight and volume 

= 1.3.5 =
* Add "extra fee"

= 1.3.4 =
* Add debug feature

= 1.3.3 =
* API URL was updated
* Test up to 4.8

= 1.2.3 =
* Add curl warning message

= 1.2.2 =
* Test with WordPress 4.7.5

= 1.2.1 =
* Fixed Can't use method return value in write context 

= 1.2.0 =
* Compatible with WooCommerce Shipping Zones
* Backwards commpatility for methods existing before zones existed
* Add Sendle Tracking Setting page ( WooCommerce -> Sendle Tracking Setting)

= 1.1.7 =
* add parcel tracking shortcode

= 1.1.6 =
* Enabled city field for shipping calculator

= 1.1.5 =
* Parcel Tracking Widget

= 1.1.4 =
* fixed a minor issue
* add "Mode" setting ( live , sandbox )

= 1.1.3 =
* fixed a minor compatible issue

= 1.1.2 =
* Changed to HTTP API

= 1.1.1 =
* fixed the suburb which is containing white space issue

= 1.1 =
* fixed some minor issues
* Add plan_name - Without authenticating, the API will give quotes for all publicly available plans by default. If plan_name is specified, the API will respond with a quote for just the given plan. Current available plans are Easy, Premium, and Pro. For authenticated requests, the API always returns the quote for the account¡¯s current plan and ignores plan_name.

= 1.0 =
* init version
