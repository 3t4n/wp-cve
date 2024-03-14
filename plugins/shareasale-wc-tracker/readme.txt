=== ShareASale WooCommerce Tracker ===
Contributors: rfrey2009
Tags: Affiliate, marketing, ShareASale, tracking, WooCommerce
Requires at least: 4.4
Tested up to: 6.2.0
Requires PHP: 5.6.4
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The ShareASale WooCommerce Tracker sets up the tracking pixel on a Merchant's cart, allows commission auto-edits if customers are refunded, automatic coupon uploads to ShareASale, product datafeed generation, and advanced analytics useful for sophisticated Affiliate attribution strategies. A $625 non-refundable joining fee and $125 minimum deposit is required upon app installation.

== Installation ==

1. Login to ShareASale and jot down your Merchant ID. It's printed at the top of the Merchant dashboard in ShareASale.

2. In the plugin's settings "Tracking Settings" tab (star icon on the WordPress sidebar), enter your Merchant ID and click "Save Settings." This is all that's required to setup basic ShareASale conversion tracking in your WooCommerce cart. See below for optional features. 

3. OPTIONAL:

a. If you are on ShareASale's StoresConnect system, you should have a store ID number assigned for each store/site in your Affiliate program. On the "Tracking Settings" tab, enter the store ID for the site you're installing tracking using this plugin. If you are not on ShareASale's StoresConnect, simply leave this field blank.

b. If you would like to pass ShareASale special information about each Affiliate-referred sale not normally captured in our standard tracking pixel solution, you can use the "Merchant-Defined Type" drop-down settings. 

For example, if you chose "Device Type (mobile or desktop)" ShareASale would track the customer's device type, and inside ShareASale you could setup varying commission rule adjustments based on this device type. Feel free to send us any ideas for new merchant-defined types as plugin feedback to shareasale@shareasale.com.

c. To use automatic reconciliation between your WooCommerce cart and ShareASale so Affiliate commissions are automatically edited anytime you refund customers' orders, you'll need to add a few more settings.

i. Login to ShareASale and visit Tools >> Merchant API page. If it's not enabled, click "enable API." Otherwise, find your API TOKEN and API KEY at the top of the page. Copy these so you can paste them into the plugin settings (iii below).

ii. While still on that Merchant API page, change the "IP Address" drop-down setting to "Require IP address match for versions 1.1 and lower." You can also keep the default setting ("Require IP address match for all API calls") if you know your site's hosting IP address and can enter it above the token field. In either case, press "Update Settings" when finished.

iii. In the plugin's settings page "Automate Reconciliation" tab back in WordPress, check the "Automate" box and then input your API settings (key/token) respective fields. Save your settings. If there is an error saving your settings and a red warning is at the top, contact ShareASale support (shareasale@shareasale.com) for assistance.

iv. Any automatically edited or voided sales in ShareASale will be logged in the table at the bottom of this tab for reference.

d. If you would like to create a product datafeed file for upload to ShareASale, go to the "Datafeed Generation" tab. With a few easy clicks our plugin will generate a basic product datafeed file you can upload to Creatives >> Datafeed in ShareASale. Be sure to review the errors/warnings after generating a product datafeed in case you need to make some fixes yourself. See this blog post for more information on the importance of a product datafeed.

http://blog.shareasale.com/2014/04/21/free-slide-deck-from-shareasales-datafeed-tune-up-webinar/

You can view or re-download the product datafeed files you've generated in the past 30 days in the table at the bottom of this tab.

e. You can also upload the product datafeed to ShareASale via FTP now. If you don't have an FTP account yet, contact us (shareasale@shareasale.com) with your host's IP address (shown on the settings page) to request credentials. Once you have them, turn on the FTP upload checkbox and enter the credentials into the FTP Username and Password fields, then save settings. 

This will also automatically schedule an ongoing daily FTP upload of a new product datafeed file, making your Affiliates' lives easier. :)

f. If you'd like to automatically send ShareASale your WooCommerce coupons as a coupon/deal type creative, check the "send to ShareASale?" box while adding/editing a WooCommerce coupon. Make sure to choose a coupon description.

== Changelog ==

= 1.5.4 =
* Confirmed compatibility with WordPress 6.2.0 and WooCommerce 4.4.1

= 1.5.3 =
* Bug fix for users with pre-7.3 versions of PHP causing errors

= 1.5.2 =
* Updated product category exclusion list to allow for any level depth instead of a maximum of four.

= 1.5.1 =
* Added a new option to exclude certain WooCommerce product categories and subcategories from datafeed generation.
* Bug fixes

= 1.5.0 =
* Added new tracking functionality and bug fixes

= 1.4.7 =
* Updated REST API to avoid PHP notices regarding missing required permission_callback argument
* Confirmed compatibility with WordPress 5.5.0 and WooCommerce 4.4.1

= 1.4.6 =
* Tweaked coupon syncing with ShareASale functionality to better support third-party plugins like WooRewards
* Confirmed compatibility with WordPress 5.4.1 and WooCommerce 4.1.1

= 1.4.5 =
* Added support for Awin MasterTag tracking solution - https://wiki.awin.com/index.php/Advertiser_Tracking_Guide/Standard_Implementation#Journey_Tag_.2F_Mastertag
* Added support for ShareASale admins to enable Advanced Analytics on a client-by-client basis
* General bug fixes and improvements

= 1.4.4 =
* Small efficiency change to the way new vs old customer tracking is calculated.

= 1.4.3 =
* Updated to confirm compatibility with WordPress 5.3 and WooCommerce 3.8. Replaced deprecated function calls for WooCommerce 3.7 and higher.

= 1.4.2 =
* Updated to confirm compatibility with WordPress 5.0 - 5.2.2 and WooCommerce 3.6.5. Retired Advanced Analytics feature. Made a suggested improvement to product datafeed generation efficiency.

= 1.4.1 =
* Tiny update to tracking JavaScript.

= 1.4.0 =
* Safari ITP 2.0 compliant "cookieless" tracking.
* General bug fixes and improvements.

= 1.3.8 =
* Small improvement for Merchants who have variable product types that later change back to simple product types, and the past child variations are not automatically trashed by WooCommerce as expected. These will be excluded from generated ShareASale product datafeed files.

= 1.3.7 =
* Small improvement to variation SKU tracking in advanced analytics "add to cart" events. 
* If you have a store ID entered into your Tracking Settings tab, it will now be passed in generated product datafeed files between custom5 and manufacturer columns. This is necessary for Merchants using ShareASale's StoresConnect feature, with product datafeeds separated by specific stores.

= 1.3.6 =
* Another small improvement to further filter out catalog hidden and/or private (unpublished) WooCommerce products from product datafeed generation. 
* Updated the minimum WordPress and WooCommerce compatible version requirements.

= 1.3.5 =
* Small change to filter out WooCommerce products that are hidden and/or private from product datafeed generation. 
* Bug fix for advance analytics to prevent add-to-cart events from being counted twice if the page was refreshed a certain way.

= 1.3.4 =
* Small change to remove dependency on jQuery library for the pixel, to further prevent caching and optimizing plugins from interfering with ShareASale tracking.

= 1.3.3 =
* Small change to prevent caching and optimizing plugins from interfering with ShareASale tracking.

= 1.3.2 =
* Small change to use passive (not active) mode for FTP uploads of ShareASale product datafeeds, for users having trouble with internal firewalls/hosting security settings.

= 1.3.1 =
* Maintenance release in case users are missing any required files from v1.3.

= 1.3 =
* BETA: Now supports FTP uploads and automatic daily scheduling of your product datafeed file. Contact ShareASale with your host's IP address if you do not have an FTP account yet.
* Other general improvements and bug fixes.

= 1.2.2 =
* Small fix to restore compatibility with other WooCommerce first-party plugins like WooCommerce Smart Coupons.

= 1.2.1 =
* Small change to clean HTML tags from generated product datafeed's descriptions and added short description (if WooCommerce version 3.0+) column.

= 1.2 =
* Advanced analytics improvements to better capture full cart contents on all updates and removals of items.
* Fix for Merchants using the WooCommerce Product Add-ons plugin and generating ShareASale product datafeed files.
* Improved handling of tracking pixel suppression if/when a customer revisits the receipt page after placing an order.
* Updated version from 1.'1' to 1.'2' series despite no new features, due to high amount of under-the-hood code changes.

= 1.1.3 =
* Added new default category and subcategory number options for products in the datafeed generation tab.
* Added an option to automatically send ShareASale your WooCommerce coupons so your Affiliates can promote them. Check the "send to ShareASale?" box while adding/editing a WooCommerce coupon.

= 1.1.2 =
* Minor tweaks for users with older versions of PHP (v5.3 - 5.5) or mistakenly orphaned product variations.

= 1.1.1 =
* Updated admin menu bar icon to use WordPress standard dashicons-star-filled instead of yellow ShareASale star logo.

= 1.1 =
* Second release. Compatible with WooCommerce 3.0!
* Added product datafeed generation to help Merchants create product datafeed files, a useful type of creative asset Affiliates can use to promote individual products.
* Added advanced analytics, which lets ShareASale track various pre-conversion cart events (coupon added, items added to cart, etc). Useful with the ShareASale Conversion Lines feature.
* General bug fixes and improvements under the hood.
* Send any feedback to shareasale@shareasale.com, subj: attn Ryan - tech team.

= 1.0 =
* Initial release.
* Send any feedback to shareasale@shareasale.com, subj: attn Ryan - tech team.