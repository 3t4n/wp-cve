=== WooCommerce Google Ads Dynamic Remarketing ===
Contributors: alekv, welovesweetcode
Tags: woocommerce, google ads, dynamic remarketing, dynamic retargeting
Requires at least: 3.1
Tested up to: 5.7
Stable tag: 1.8.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates the Google Ads Dynamic Remarketing Tracking pixel with customized ecommerce variables in a WooCommerce shop.

== Description ==

> **This plugin is deprecated. All its features and much more has been merged into our main plugin, the** [Pixel Manager for WooCommerce](https://wordpress.org/plugins/woocommerce-google-adwords-conversion-tracking-tag/).

Do you have a WooCommerce shop and want to run dynamic remarketing campaigns with Google Ads? This plugin will insert the customized remarketing pixel on all your shop pages. Google Ads will then be able to collect customer behaviour data (product viewers, buyers, order value, cart abandoners, etc). Based on this data you will be able to run targeted remarketing campaigns.

<strong>Requirements</strong>

* WooCommerce
* WooCommerce Google Product Feed plugin or something similar to upload the products to the Google Merchant Center
* Google Merchant Center Account with all products uploaded
* Google Ads account with a configured remarketing tag

<strong>Highlights of this plugin</strong>

* Easy to install
* Accurate. Several methods have been build in to avoid tracking of shop managers, deduplication of purchases, etc.

<strong>Cookie Consent Management</strong>

The plugin uses data from several Cookie Consent Management plugins to avoid injecting the tracking pixel, in case a visitor doesn't want to be tracked by third party pixels.

It works with the following Cookie Consent Management plugins:

* [Cookie Notice](https://wordpress.org/plugins/cookie-notice/)
* [Cookie Law Info](https://wordpress.org/plugins/cookie-law-info/)
* [GDPR Cookie Compliance](https://wordpress.org/plugins/gdpr-cookie-compliance/)

It is also possible for developers of Cookie Consent Management plugins to deactivate our plugin with a filter, in case a visitor opts out of third party pixel tracking. Simply use the following code:

`add_filter( 'wgdr_third_party_cookie_prevention', '__return_true' );`

<strong>Installation support</strong>

Installing the plugin is pretty simple. Just activate it and enter the conversion ID and if necessary the product prefix.

If you also need to to set up the Google Merchant Center first the entire setup becomes more complex. If you would like us to do the setup for you please contact us for an offer: support@sweetcode.com

<strong>Similar plugins</strong>

If you like this plugin, have a look at our other Google Ads related plugin: [WooCommerce Google Ads Conversion Tracking](https://wordpress.org/plugins/woocommerce-google-adwords-conversion-tracking-tag/)

<strong>Support Info</strong>

We will only support installations which run the most current versions of WordPress and WooCommerce.

<strong>More information</strong>

Please find more information about Google Ads remarketing on following pages:

[Dynamic Display Ads](http://www.google.com/ads/innovations/dynamicdisplayads.html)<br>
[Dynamic Remarketing](https://www.thinkwithgoogle.com/products/dynamic-remarketing.html)<br>


== Installation ==

1. Upload the WGDR plugin directory into your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Get the Google Ads conversion ID and the conversion label. You will find both values in the Google Ads remarketing tag. [Get the conversion ID (ignore the conversion label)](https://www.youtube.com/watch?v=p9gY3JSrNHU)
4. In the WordpPress admin panel go to WooCommerce and then into the 'Google Ads Dynamic Retargeting' menu. Please enter the conversion ID and the conversion label into their respective fields.
5. Also add the Google Merchant Center prefix which is "woocommerce_gpf_" if you use the Google Product Feed plugin to upload your products the the Google Merchant Center.
6. Use the Google Tag Assistant browser plugin to test the tracking code. Bear in mind that the code is only visible if you are not logged in as admin or shop manager. You will have to log out first.

== Frequently Asked Questions ==

= Why does Google Ads report an error? =

Give Google Ads time to pick up the code. It can take up to 48 hours.

= How do I check if the plugin is working properly? =

Download the Google Tag Assistant browser plugin. It is a powerful tool to validate all Google tags on your pages. Bear in mind that validating the Merchant Center Feed only works on product pages.

= The plugin messes up my theme. What is happening? =

It has been reported in several occasions that minifaction plugins mess up the Javascript code which lead to errors in the theme. The Javascript code for the tracking needs to be placed exactly as Google requires it. To solve this issue turn off the minification plugin.

= Does the plugin support variations? =

Yes.

The tracking pixel matches regular feeds and also feeds with variations, as long as the variations are uploaded with the correct item_group_id (which is basically the id of the parent product): [Dynamic Remarketing with Group ID](https://www.en.advertisercommunity.com/t5/Basics-for-New-Advertisers/Dynamic-Remarketing-with-Group-ID-s/td-p/213653)

And will the dynamic ad show exactly the variations the visitor has visited? And the answer to that is not necessarily. But, don’t worry about specific variations too much. Even if the visitor is only being served the parent product he has visited (not the variation), the dynamic banner will lead to a much higher click-through-rate already.

Also look at this answer in the Google Ads Community (coming from a Google developer): [Dynamic Remarketing Tag and Variations](https://www.en.advertisercommunity.com/t5/Advanced-Features/Dynamic-Remarketing-Tag-amp-Multiple-Product-Options/td-p/336966#)

= I am getting following error: "We haven’t detected the Google Analytics remarketing functionality on your website." What can I do? =

This plugin doesn't support Google Analytics Remarketing, only Google Google Ads Remarketing (as the plugin name says).

= I am getting following error: "There are problems with some of your custom parameters for Retail." What can I do? =

Give Google Ads time to pick up the code. It can take up to 48 hours.

= I am getting following error: "Only 20 of 50 page visits that have passed an ID (or 40%) match IDs in your Merchant Center feed." What can I do? =

It indicates that not all products have been uploaded to the Google Merchant Center. As long as you don't upload all of them you will see this error.

Also it could be that you haven't waited the 48 hours time period until Google Ads has processed everything properly.

= Where can I report a bug or suggest improvements? =

Please report your bugs and support requests in the support forum for this plugin: [Plugin Support Forum](http://wordpress.org/support/plugin/woocommerce-google-dynamic-retargeting-tag)

We will need some data to be able to help you.

* Website address
* WordPress version
* WooCommerce version
* WooCommerce theme and version of the theme
* The Google Ads remarketing tags conversion ID and conversion label

(Most of this information is publicly viewable on your webpage. If you still want to keep it private don't hesitate to send us a support request to support@support.com)

= Why does Google Ads report that the ecomm_prodid are not found? =

* After installation wait a few days. It can take that long until all is processed correctly.
* You need the prefix only if you use the WooThemes Google Product Feed plugin.
* Check if you really have loaded all products into the feed and into the Google Merchant Center.



== Screenshots ==
1. Simple configuration
2. Validate the configuration of the plugin with the Google Tag Assistant

== Changelog ==

= 1.8.1 =

* Tweak: Bumped up version
* Tweak: Updated readme.txt

= 1.8.0 =

* Tweak: Deprecation notice

= 1.7.18 =

* Tweak: Removed uninstall.php

= 1.7.18 =

* Tweak: Improved deactivation filter

= 1.7.17 =

* Tweak: Removed freemius

= 1.7.16 =

* Info: Tested up to WP 5.6
* Tweak: Added gtag config instruction if gtag insertion is disabled
* Tweak: Disabled ratings request

= 1.7.15 =
* Info: Tested up to WP 5.3
= 1.7.14 =
* Tweak: Improved the output on product pages in case a product is missing a price
= 1.7.13 =
* Fix: Fixed a spelling error in ecomm_totalvalue
= 1.7.12 =
* Fix: Fixed a semicolon in the product output (which was automatically inserted by the code cleanup of my IDE)
= 1.7.11 =
* Tweak: Added options validation and cleaning
= 1.7.10 =
* Fix: Fixed function that adds settings link to plugin overview page
= 1.7.9 =
* Fix: Fixed a semicolon in the product output
= 1.7.8 =
* Tweak: Improved compatibility with some newer PHP versions
= 1.7.7 =
* Info: Tested up to WP 5.1
* Tweak: Some syntax adjustments
= 1.7.6 =
* Tweak: Adding the ecomm_prodid parameter to all pages, even non product pages, as this has become a requirement for all pages now.
= 1.7.5 =
* Info: Changing name from AdWords to Google Ads
= 1.7.4 =
* Info: Tested up to WC 3.5.2
= 1.7.3 =
* Fix: Fixed a syntactic error on the product page
= 1.7.2 =
* Tweak: Added the conversion id parameter to the event tag
= 1.7.1 =
* Tweak: Removed a debugging message
= 1.7 =
* New: Option to switch off the insertion of the gtag.js tag
= 1.6 =
* Tweak: Switched to gtag pixel
= 1.5 =
* New: Integrated with several Cookie Consent Management plugins
= 1.4.7 =
* Tweak: Adjusted minimum WC version
= 1.4.6 =
* Tweak: Added discounts to the value calculation
= 1.4.5 =
* Tweak: Hiding the white space at the bottom of some themes by adding a new css sheet
= 1.4.4 =
* Tweak: Catch warning if a product has no categories set
* Tweak: Catch error and log it in debug.log if is_product() returns true, but wc_get_product() can't retrieve a product object
= 1.4.3 =
* Tweak: The campaign URL parameters have been removed
= 1.4.2 =
* Tweak: json_encode conversion_id output to prevent JavaScript errors in edge cases
= 1.4.1 =
* Tweak: Replacing deprecated $order->id with $order->get_order_number()
= 1.4 =
* Tweak: Code cleanup
= 1.3.7 =
* New: Admin notice asking to leave a rating
* Tweak: Better options update routine
* Tweak: Switching order_total to order_subtotal (no taxes and shipping cost)
= 1.3.6 =
* Tweak: Code cleanup
= 1.3.5 =
* Fix: Avoid 'undefined index' for product_identifier
= 1.3.4 =
* New: Choose product ID or SKU as product identifier
= 1.3.3 =
* Tweak: Refurbishment of the settings page
= 1.3.2 =
* Fix: Version check with new function logic to make it work with older PHP versions
= 1.3 =
* Tweak: Options table upgrade
= 1.2.1 =
* New: Uninstall routine
= 1.2 =
* New: Exclusion for the Autoptimize plugin
= 1.1.2 =
* New: Added filter capability for products and categories
= 1.1.1 =
* Tweak: Code cleanup
= 1.0.9 =
* Tweak: Code cleanup
* Tweak: To avoid overreporting only insert the retargeting code for visitors, not shop managers and admins
= 1.0.8 =
* Tweak: Encoding all JavaScript variables with json_encode
= 1.0.7 =
* Tweak: Switching single pixel function from transient to post meta
= 1.0.6 =
* Fix: Adding session handling to avoid duplications
= 1.0.5 =
* Fix: Implement different logic to exclude failed orders as the old one is too restrictive
= 1.0.4 =
* Fix: Exclude orders where the payment has failed
= 1.0.3 =
* Fix: Minor fix to the code to avoid an invalid argument error which happens in rare cases.
= 1.0.2 =
* Update: New translation into Serbian
* Update: Change of plugin name
* New: Plugin banner and icon
= 1.0.1 =
* Update: Minor update to the code to make it cleaner and easier to read
= 1.0 =
* New: Internationalization (German)
* New: Category support
= 0.1.4 =
* Update: Increase plugin security
* Update: Moved the settings to the submenu of WooCommerce
* Update: Improved DB handling of orders on the thankyou page
* Update: Code cleanup
* Update: Removed the conversion label. It is not necessary.
= 0.1.3 =
* Added settings field to the plugin page.
= 0.1.2 =
* The code reflects now that the conversion_label field is optional.
= 0.1.1 =
* Changed the woo_foot hook to wp_footer to avoid problems with some themes. This should be more compatible with most themes as long as they use the wp_footer hook.
= 0.1 =
* This is the initial release of the plugin.
