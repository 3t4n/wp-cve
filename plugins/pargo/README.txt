=== Pargo Smart Logistics Solutions ===
Plugin Name: Pargo Smart Logistics Solutions
Contributors: pargo engineering
Tags: courier, delivery, order delivery, courier service, courier app, delivery app, logistics, logistics services, logistics app, last mile, pargo
Requires at least: 5.8
Tested up to: 6.3.2
Stable tag: 3.4.5
Requires PHP: 7.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Pargo now offers Home Delivery and Click & Collect through our latest plugin update, giving your customers even more freedom when choosing their preferred delivery method.

== Description ==

No more missed deliveries with the leading click-and-collect solution and highest rated delivery service in Africa.

Pargo offers Click & Collect and Home Delivery through our WooCommerce plugin giving your customers even more freedom when choosing their preferred delivery method.

Thanks to full integration with the myPargo portal, you can enjoy the simplicity of tracking and managing all your orders through a single portal.


== How Pargo Works ==
**CHECKOUT WITH PARGO**: customers shop online and choose their preferred Pargo delivery method.

**PARGO DELIVERS PARCELS**: Pargo collects the parcels from the supplier and delivers them to their destination.

**CUSTOMERS NOTIFIED**: Pargo notifies customers of their parcel’s progress until the point of collection or delivery.

**CUSTOMERS SHARE GREAT EXPERIENCES**: customers share their experiences online, boosting your online reputation.

= Features =

* Easily download the Pargo plugin from WordPress marketplace and configure the plugin using Pargo’s installation guides.
* Reach more customers with the Pargo Click & Collect network of 4000+ Pickup Points nationwide.
* Benefit from reliable, efficient delivery, with an unmatched success rate on first delivery attempts to Pargo Points and homes.
* Let Pargo handle your customer delivery SMS and email notifications, with easy links for customers to track and trace their parcel.
* Orders reflect directly on your myPargo dashboard where you can track and trace parcels, make final edits before printing waybills and manifests, and confirm your order for courier collection.
* Add a custom map token for greater customisation.
* Improved customer service with assistance from our support team.
* Access to easy drop-off returns at participating Pargo Points! Simply get in touch with us to activate this service.

== Installation ==

= Requirements =

* PHP: 7.4 (same as WordPress 5.8)
* Wordpress: 5.8+
* WooCommerce: 5.9+
* Install the Pargo Plugin via Wordpress marketplace

= Configuration From Pargo (contact us) =
* myPargo login credentials (to configure the plugin’s account settings).
* Install the Pargo plugin by downloading it from WordPress marketplace.
* Configure the plugin shipping methods in the left side panel of your WordPress dashboard, where the Pargo logo will be visible after download.
* Add the configured Pargo shipping methods to your shipping zones in WooCommerce settings.

== Frequently Asked Questions ==

Q: Is it necessary to complete all the rate fields on the plugin?
A: Yes, the plugin requires values in all rates fields. These can be set in a manner that assists the store in passing the costs onto customers, or the values can all be kept the same.
Q: Will the plugin work for multi-site configurations?
A: Yes, the plugin has been tested for multi-site WordPress configurations.
Q: Will the plugin work with all themes?
A: This cannot be guaranteed, the plugin has been tested successfully for the storefront default themes.
Q: Does the plugin work for all payment methods?
A: The plugin has been successfully tested with Payfast, Paystack, Yoco, and Peach Payments.
Q: Do I need to make any changes to Shipping Zones?
A: If you are using the Pargo plugin, or the home delivery method for the first time, it will be necessary to set your shipping zones.
Q: Items larger than 15kg, or total baskets weighing more than 15kg do not seem to work with Pargo’s plugin, is this intentional?
A: Yes, Pargo pickup points have a maximum weight limit of 15kg, larger parcels cannot be handled by the Pickup Points.
Q: Which option should I select for the map display?
A: Pargo recommends showing the map as a modal - leave the config unticked.
Q: The Pickup Point address seems to be changing to the customers home address, what should I do?
A: Go to Woocommerce > Shipping > Shipping options: make sure the shipping destination is nNOT set to “default to customer shipping address”.
Q: Why is the checkout page freezing?
A: Certain caching and optimise plugins may interfere with the Pargo plugin, these should be disabled if possible. It may also be that you have the “static widget”  map display enabled, switch to the modal option.
Q: Will the plugin work with all themes?
A: This cannot be guaranteed, the plugin has been tested successfully for the Astra, Hello Elementor and Divi themes.

== Screenshots ==

1. Pargo settings inside the admin area
2. Pargo process shipment from order screen
3. Front end selecting of Pargo Pickup Point
4. Pargo map where clients select a Pargo Pickup Point
5. Display of selected Pargo Pickup Point
6. Cart once point has been selected

== Upgrade notice ==
1. This upgrade includes a new shipping method, for home delivery. You will need to set the shipping zones for home delivery the first time you make use of this upgrade.
2. Pargo recommends checking the rate settings and configurations of your plugin after each upgrade.

== Changelog ==

= 3.3.6 =
New toggle option for shipping settings / zones to choose whether you want to send your orders to Pargo automatically or disable backend shipping.

Users who did not complete setup by adding a map token, can now see the map.

Fix added to prevent blank phone numbers being sent to the API for those who have disabled the phone number validation at checkout.

Pargo Point location fields are now split into individual fields on orders.

= 3.2.0 =
Our latest Woocommerce release includes a status writeback from myPargo to Woocommerce. Gone are the days of updating Woocommerce orders separately from myPargo; orders created and confirmed on myPargo will now push to your Woocommerce dashboard.

We're committed to delivering the best plugin experience possible. By utilising analytics tracking, we can gather valuable insights to optimise performance and continually improve your experience. Not keen? Simply opt out in your advanced settings.

We’ve made it so easy to sign up for a myPargo account directly from the plugin configuration screen. If you don’t yet have a myPargo account, simply click the link on the set up screen to get started.

= 3.1.14 =
Ensure "ZA" or "EG" is passed as country code to Pargo API dynamically.
Minor Bugfixes

= 3.1.1 =
New Feature: Shipping Rates by Zone
We are excited to announce a new feature in our WooCommerce shipping plugin that allows you to set shipping rates for specific shipping zones. With this feature, you can now offer more flexible and customized shipping options to your customers based on their location.

Here are some highlights of this feature:

Create unlimited shipping zones: You can create as many shipping zones as you need, based on countries, states, or zip codes.
Set shipping rates by zone: For each shipping zone, you can set different shipping rates based on the shipping method, weight, price, or a combination of these factors.
Override default rates: You can override the default shipping rates for certain shipping zones.
To get started with this feature, simply go to the Shipping Zones tab in your WooCommerce settings, and add or edit a shipping zone. You can then select the shipping methods you want to offer, and set the rates and conditions for each method.

= 3.0.9 =
* Addition of home delivery method.
* Configurable free shipping per method.
* Addition of new customer centric Pickup Point Map.
* Improved order notes for better order handling.
* Overall upgrades for compatibility with latest WordPress, Woocommerce and PHP.
= 2.5.9 =
* Hotfix: On some installations the "Oh snap! You need to choose a pickup point" popup was not triggered if Pargo was the selected shipping
          method and the user had not selected a pickup point.
= 2.5.8 =
* Fixed the issue that prevented new users from setting the myPargo API URL in the Pargo Accounts page.
* The issue that allowed users to make a purchase without having selected a Pickup Point has been resolved.
= 2.5.7 =
* Fixed the excessive admin-ajax.php api calls
* Fixed Console error
* Orders submit to myPargo if the merchant has a verified myPargo account.
* Fixed the issue with the Pargo Pickup Point selection
* Pargo waybill numbers are successfully saved to the order.
= 2.5.6 =
* Fixed an exception when cart weight is not returned properly by woocommerce
= 2.5.5 =
* Fixed: JavaScript error when using the Pargo plugin with WooCommerce Shipping that caused an infinite loop on the checkout page.
= 2.5.4 =
* Fixed: Pargo Pickup Point orders not populating in myPargo
* Fixed main.js file throwing 404 on admin pages
= 2.5.2 =
* Fixed wait_until_exists.js file not found
* Fixed pargo orders not being sent to Pargo
= 2.5.1 =
* Removed ajax-pick-up-point.php and added proper wordpress hook
* Moved the order placement from thank_you page hook to the order status change
= 2.5.0 =
* Added warehouse to door delivery functionality
* Stability changes and cleanup
* Fixed all known warnings the plugin was causing
* Fixed issue where changing from one shipping method to another away from Pargo would lose its state
* Enabled ability to switch between maps more easily
* Began namespacing code to improve compatibility with other plugins (More to come)
* Removed unused/dead code from the code-base
* Simplified the rate calculation logic to make it more maintainable
= 2.4.15 =
* Replaced all uses of $_SESSION with WC()->session to conform with standards
= 2.4.14 =
* Defect fix for non-pargo shipping methods submitting API orders
= 2.4.13 =
* Added informative Woocommerce order notes
* Improved execption handling
= 2.4.12 =
* Tested up to Wordpress v5.7
= 2.4.9 =
* Tested up to Wordpress v5.6.
* Updated and removed all cURL scripts and using standard Wordpress HTTP API.
* Removed all scripts that was calling files remotely. These file include jquery and bootstrap files.
* Ensured that all the data was Sanitized, Escaped and Validated.
* Removed forced session on all pages and only implementing these sessions on the required pages.
* Ensured that all NameSpaces met Wordpress standards.
= 2.4.0 =
* Minor styling fixes
= 2.3.8 =
* Fix Oder ID warning
= 2.3.7 =
* Fix jQuery Reference
= 2.3.6 =
* Remove jQuery include
= 2.3.5 =
* Remove diable of order button
= 2.3.4 =
* Remove testcode
= 2.3.3 =
* Fix js issue
= 2.3.2 =
* Fix versioning issue
= 2.3.1 =
* Map widget issue resolved
= 2.0.9 =
* HTPPS issue resolved
= 2.0.8 =
* Inline or map popup options added
* API backend shipment bug fix
* Changes insurance bug fix
= 2.0.7 =
* Pargo insurance option added
= 2.0.6 =
* Pargo info popup link added
= 2.0.5 =
* You can now send orders directly from the admin panel
= 2.0.4 =
* We have added the functionality to log your plugin information to improve our plugin
= 2.0.3 =
* More dynamic handling
= 2.0.2 =
* Responsive Firefox issue sorted
= 2.0.1 =
* Rewritten code base
* Added custom merchant map token feature and setting
* Added no weight shipping cost setting
* Added more and improved Pargo Pickup point details to be saved to custom fields
* Improved compatibility with Woocommerce 3.3+
* Added usage tracking opt-in feature
* Added more front-end styling options
* Fixed pup number being correctly saved to custom field
* Added selected pickup point address to save to custom field
