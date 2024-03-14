=== Portugal DPD Pickup and Lockers network for WooCommerce ===
Contributors: webdados, ptwooplugins
Tags: woocommerce, shipping, dpd, chronopost, seur, pickup, lockers, ecommerce, e-commerce, delivery, webdados
Author: PT Woo Plugins (by Webdados)
Author URI: https://ptwooplugins.com
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 3.3

Lets you deliver on the DPD Portugal Pickup network of partners or Lockers. This is not a shipping method but rather an add-on for any WooCommerce shipping method you activate it on.

== Description ==

Lets you deliver on the [DPD Portugal](https://dpd.pt/) (former Chronopost and SEUR) Pickup network of partners or Lockers. This is not a shipping method but rather an add-on for any WooCommerce shipping method you activate it on.

This is not an official DPD Portugal plugin, but their support was obtained during its development. The DPD logo and brand is copyrighted, belongs to them and is used with their permission.

= Features: =

* Lets the store client choose a DPD Portugal Pickup point or Locker for the shipping delivery;
* The DPD Portugal Pickup points option can be associated to any zone/method by the store owner;
* This plugin does not create a new WooCommerce Shipping Method and is compatible with methods that can be associated with a zone (WooCommerce 2.6 and above);
* All WooCommerce built-in shipping methods are compatible;

= Pro plugins: =

**NEW: Looking for a DPD / SEUR Pickup and Lockers solution for other countries?**

Our "[DPD / SEUR / Geopost Pickup and Lockers network for WooCommerce](https://ptwooplugins.com/product/dpd-seur-geopost-pickup-and-lockers-network-for-woocommerce/)" premium plugin allows delivery in the Geopost network of Parcelshops and Lockers in Belgium, Estonia, France, Germany, Luxembourg, Netherlands, Portugal, Spain and Switzerland.

Suitable for WooCommerce shops using DPD to ship from any European country that can deliver to Pickup Points in the countries listed above.

**Are you still issuing the shipping labels manually on the DPD website?**

You should check out our new plugin “[DPD Portugal for WooCommerce](https://www.webdados.pt/wordpress/plugins/dpd-portugal-para-woocommerce-wordpress/)”, issue the labels directly from WooCommerce, save hours and free up human resources for the really important tasks.

With the Pro plugin you can also remove the Lockers from checkout options with simple settings on the backend, instead of having to use the `cppw_hide_lockers` developer filter.

Buy it [here](https://ptwooplugins.com/product/dpd-portugal-for-woocommerce/) and use the coupon **webdados** for 10% discount!

If you buy our [Pro DPD for label issuing plugin](https://ptwooplugins.com/product/dpd-portugal-for-woocommerce/), you’ll also get premium support for this plugin.

= 3rd Party Integration: =

* [Flexible Shipping for WooCommerce](https://wordpress.org/plugins/flexible-shipping/);
* [Table Rate Shipping for WooCommerce](https://bolderelements.net/plugins/table-rate-shipping-woocommerce/) (by BolderElements);
* [WooCommerce Advanced Shipping](https://codecanyon.net/item/woocommerce-advanced-shipping/8634573);
* [Table Rate Shipping](https://woocommerce.com/products/table-rate-shipping/) (by WooCommerce);
* Additional compatibility with other plugins can be implemented with costs to be budgeted under contact;

== Installation ==

* Use the included automatic install feature on your WordPress admin panel and search for “DPD Portugal Pickup WooCommerce”.
* Go to WooCoomerce > Settings > Shipping > Shipping zones and for each zone/method you want the DPD Portugal Pickup points selection to be activated, set "Yes" on the "DPD Portugal Pickup" option.
* Mandatory if you want to show the point on a map (using Mapbox - recommended): go to the [Mapbox Acess tokens page](https://www.mapbox.com/account/access-tokens) and get either your default public token or genrerate a new one, then add it to WooCommerce > Settings > Shipping > Shipping options > DPD Pickup network in Portugal > Mapbox public token.
* Mandatory if you want to show the point on a map (using Google Maps): go to the [Google APIs Console](https://console.developers.google.com/cloud-resource-manager) and create a project, then go to the [Maps Static API](https://developers.google.com/maps/documentation/maps-static/get-api-key) documentation website and click on "Get started", choose "Maps", select your project and generate a new key, finally add it to WooCommerce > Settings > Shipping > Shipping options > DPD Pickup network in Portugal > Google Maps API Key.

== Frequently Asked Questions ==

= Is this a shipping method? =

No! This is an add-on for any method that supports "shipping zones" (WooCommerce >= 2.6).
You need to set the shipping fees using built-in or plugin installed methods and then set "Yes" on the "DPD Portugal Pickup" option on each zone/method that applies.

= Can I change the number of total and near points shown on the website? =

Yes! Go to WooCommerce > Settings > Shipping > Shipping options and tweak the settings as you like.
Always set the total points to a bigger number than the near points, or you're going to end up with the just the near points.

= I need to use this plugin with a shipping method that it's not compatible. Is it possible? =

Maybe. We have the `cppw_get_shipping_methods` filter that allows you to add other shipping methods besides the ones that are compatible. Use at your own risk.
For example, if you want to use DPD Pickup Points with [Flat Rate per State/Country/Region for WooCommerce](https://wordpress.org/plugins/flat-rate-per-countryregion-for-woocommerce/) you would do it [like this](https://gist.github.com/webdados/92b6725c29adf3f2e0ccb627ffb51245).

= Why isn't the Mapbox / Google Maps showing up? =

You need to get a Mapbox Public Token or a Google Maps API Key, as explained on the installation instructions.

= Can I change the map image size? =

Yes. Use the `cppw_map_width` and `cppw_map_height` filters [like this](https://gist.github.com/webdados/ab63fe80948af0231c6d623f5686c776).
You can also change the zoom by using the `cppw_map_zoom` filter and scale using the `cppw_map_scale` filter (1 for regular and 2 for retina).

= Can I hide the DPD Lockers from the checkout options? =

Yes. Return false to the `cppw_hide_lockers` filter.

= I'm getting the "There are no DPD points in the database" and I can't get it to work even if I force the update process in the backend. What can I do? =

This means your hosting provider is blocking the two ways that we have to update the Pickup points from DPD: webservice on port 7554 and FTP.
You should ask them to unblock HTTP requests to https://webservices.chronopost.pt:7554 and or the FTP functions on PHP.

= Is this plugin compatible with the new WooCommerce High-Performance Order Storage? =

Yes.

= Is this plugin compatible with the new WooCommerce Block-Based Checkout? =

Not yet.

= I need technical support. Who should I contact, DPD or Webdados? =

The development and support is [Webdados](https://www.webdados.pt) responsibility.
For free/standard support you should use the support forums at WordPress.org but no answer is guaranteed.
For premium/urgent support or custom developments you should contact [Webdados](https://www.webdados.pt/contactos/) directly. Charges may (and most certainly will) apply.

If you buy our [Pro DPD for label issuing plugin](https://www.webdados.pt/wordpress/plugins/dpd-portugal-para-woocommerce-wordpress/), you’ll also get premium support for this plugin.

= Where do I report security vulnerabilities found in this plugin? =  
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/portugal-chronopost-pickup-woocommerce). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

== Changelog ==

= 3.3 - 2023-10-20 =
* Information about our new premium plugin allowing [shipping to DPD and SEUR Pickup Points and Lockers in other countries]((https://ptwooplugins.com/product/dpd-seur-geopost-pickup-and-lockers-network-for-woocommerce/))
* Information about no compatibility yet with the new WooCommerce Block-Based Checkout
* Tested with WordPress 6.5-alpha-56974 and WooCommerce 8.0.2

= 3.2 - 2023-08-21 =
* Fix a bug where sometimes pickup points with exact postcode matches would not show up
* Requires WooCommerce 5.0
* Tested with WordPress 6.4-alpha-56420 and WooCommerce 8.0.2

= 3.1 - 2023-06-29 =
* Replace shipping address on the admin orders list with the DPD pickup point details
* Tested with WordPress 6.3-beta2-56100 and WooCommerce 7.9.0-beta.2

= 3.0 - 2023-01-17 =
* Tested and confirmed WooCommerce HPOS compatibility
* Removed Kaksi Media backup API endpoint
* Requires WooCommerce 4.0 or above
* Tested with WordPress 6.2-alpha-55080 and WooCommerce 7.3

= 2.7.0 - 2022-09-13 =
* Fix when country fields are removed from checkout
* Tested with WordPress 6.1-alpha-54043 and WooCommerce 6.9.0-rc.1

= 2.6.0 - 2022-06-29 =
* New brand: PT Woo Plugins 🥳
* Requires WordPress 5.0, WooCommerce 3.0 and PHP 7.0
* Tested with WordPress 6.1-alpha-53556 and WooCommerce 6.7.0-beta.2

= 2.5.0 - 2022-04-21 =
* Fix the pickup points list when the user does not enter the "-" on the postcode
* Tested with WordPress 6.0-beta2-53236 and WooCommerce 6.5.0-beta.1

= 2.4.1 - 2021-12-23 =
* Use the WordPress `wp_doing_ajax()` function instead of the WooCommerce `is_ajax()` which will be deprecated on WooCommerce 6.1.0
* Tested with WordPress 5.8-alpha-50516 and WooCommerce 5.1.0

= 2.4.0 - 2020-03-10 =
* New option to not pre-select a point in the DPD Pickup field and force the client to choose it, thus reducing situations in which the client doesn't even notice that he needed to select a point - sponsored by [Evolt](https://evolt.pt/)
* Requires WooCommerce 3.0
* Tested with WordPress 5.8-alpha-50516 and WooCommerce 5.1.0

= 2.3.0 - 2020-03-07 =
* Fix Flexible Shipping integration - Field not showing
* Tested with WordPress 5.7-RC3-50503 and WooCommerce 5.1.0-rc.1

= 2.2.0 - 2020-02-23 =
* Show DDP pickup point number on emails
* Tested with WordPress 5.7-beta3-50388 and WooCommerce 5.1.0-beta.1

= 2.1.0 =
* Integrations for the “[DPD Portugal for WooCommerce](https://www.webdados.pt/wordpress/plugins/dpd-portugal-para-woocommerce-wordpress/)” pro plugin (version 2.5.0 and up) for advanced Lockers filtering

= 2.0.1 =
* readme.txt tweaks

= 2.0.0 =
* New `cppw_hide_lockers` filter to hide the DPD Lockers from the checkout, by returning `false` to it
* Fix some PHP notices
* Requires WooCommerce 3.0 or above
* Tested with WordPress 5.6-beta1-49314 and WooCommerce 4.7.0-rc.1

= 1.8.0 =
* Bugfix when saving the pickup point for Table Rate Shipping for WooCommerce (by BolderElements)

= 1.7.2 =
* Helper on the instruction settings regarding the DPD mixed service (home + pickup)
* Tested with WordPress 5.6-alpha-48937 and WooCommerce 4.5.0-rc.3

= 1.7.1 =
* Information about the "[DPD Portugal for WooCommerce](https://www.webdados.pt/wordpress/plugins/dpd-portugal-para-woocommerce-wordpress/)" pro plugin;

= 1.7 =
* Change all Chronopost references to DPD
* Tested with WooCommerce 4.0.1

= 1.6.7 =
* New `cppw_available_points` to allow developers to filter the pickup points list before they're shown to the customer on the checkout - Sponsored by [mindthetrash.pt](https://mindthetrash.pt)
* Tested with WordPress 5.3.3-alpha-47290 and WooCommerce 4.0.0-beta.1

= 1.6.6 =
* Bugfix when loading the Checkout page and the active shipping method has Chronopost enabled
* Tested with WordPress 5.3.3-alpha-46995 and WooCommerce 3.9.0-beta.2

= 1.6.5 =
* Tested with WordPress 5.2.5-alpha and WooCommerce 3.8.0

= 1.6.4 =
* Better cron job logging 
* Tested with WooCommerce 3.6.4
* Tested with WordPress 5.2.3-alpha

= 1.6.3 =
* Fix compatibility with [WooCommerce Advanced Shipping](https://codecanyon.net/item/woocommerce-advanced-shipping/8634573) (Thanks Evolt)
* Tested with WooCommerce 3.6.3
* Tested with WordPress 5.2.1

= 1.6.2 =
* CSS compatibility with Flatsome 3.7

= 1.6.1 =
* Tested with WooCommerce 3.5
* Bumped `WC tested up` tag

= 1.6 =
* Because of the new Google Maps pricing policy, it' now possible to use Mapbox static maps (the link on the map image remains to Google Maps)
* New `cppw_map_scale` and `cppw_map_zoom` filters to allow overriding of the map image scale (default is 2, for retina displays) and zoom (default is 11 for Google Maps and 10 for Mapbox)

= 1.5 =
* [Table Rate Shipping](https://woocommerce.com/products/table-rate-shipping/) compatibility - sponsored by [Dreamsbaby](http://www.dreamsbaby.pt/)
* Fix: fatal error when enqueueing CSS and JS

= 1.4 =
* [WooCommerce Advanced Shipping](https://codecanyon.net/item/woocommerce-advanced-shipping/8634573) compatibility - sponsored by [STIVIKpro](https://stivikpro.com/)

= 1.3.2 =
* Fix: when using [Flexible Shipping for WooCommerce](https://wordpress.org/plugins/flexible-shipping/) the point was not saved with the order (thanks @alvesjc)
* Bumped `WC tested up` tag

= 1.3.1 =
* Fix: on newer WooCommerce versions the point was not saved with the order
* Bumped `WC tested up` tag

= 1.3 =
* Removed our fallback Google Maps API Key due to the [changes on the Google Maps Plaform usage policy](https://mapsplatform.googleblog.com/2018/05/introducing-google-maps-platform.html)

= 1.2.1 =
* Small fixes

= 1.2 =
* [Table Rate Shipping for WooCommerce](https://bolderelements.net/plugins/table-rate-shipping-woocommerce/) compatibility - sponsored by [Moreleads](https://moreleads.pt/)

= 1.1 =
* It's now possible to show a small instructions text under the shipping option for which the Chronopost Pickup is activated

= 1.0 =
* The Chronopost Pickup point information is also shown on the order details on the "My Account" page and on the order preview on the admin orders list table
* Code enhancements

= 0.9 =
* It's now possible to hide the Shipping Address from the order details and emails sent to the customer
* JS and CSS loaded from external assets instead of inlined on the HTML

= 0.8 =
* New `cppw_get_shipping_methods` to allow developers to add non-compatible shipping methods

= 0.7.1 =
* Fix on a string
* Bumped `WC tested up` tag

= 0.7 =
* It's now possible to show the Chronopost Pickup point information on emails sent to the customer

= 0.6 =
* It's now possible to show the pickup point phone number and opening/closing hours on the checkout (they will show up after the next sucessfull update from the webservice)
* Bumped `WC tested up` tag

= 0.5.3.1 =
* Tested with WooCommerce 3.3
* Bumped `Tested up to` tag

= 0.5.3 =
* Added the `cppw_map_width` and `cppw_map_height` filters to allow overriding of the Google Maps image size
* Bumped `WC tested up` tag

= 0.5.2 =
* Added a webservice URL fallback on Kaksi Media servers
* Random mirror order when accessing the webservice for pickup points update
* Increased the timeout when accessing the webservice for pickup points update
* Bumped `WC tested up` tag

= 0.5.1 =
* Fixed a small bug related to the Google Maps API Key
* Fixed some PHP notices
* Added a link to get a Google Maps API Key, near the field on the settings page

= 0.5 =
* Added a webservice URL fallback on Webdados servers, for pickup points update, in servers that cannot open the Chronopost webservice because of firewall rules
* Changed the FTP connection mode to passive

= 0.4.2 =
* readme.txt fix

= 0.4.1 =
* readme.txt fix

= 0.4 =
* Fixed a PHP fatal error on very weird scenarios
* Added FTP fallback, for pickup points update, in servers that cannot open the Chronopost webservice because of firewall rules
* Added a warning on WooCommerce settings screen when the pickup points haven't been loaded yet, and a tool to force the update

= 0.3 =
* Experimental [Flexible Shipping for WooCommerce](https://wordpress.org/plugins/flexible-shipping/) compatibility - Thanks [@sotnas](https://wordpress.org/support/topic/with-flexible-shipping-addon-dont-work/)
* Tested with WooCommerce 3.2.1

= 0.2 =
* Tested with WooCommerce 3.2
* Added `WC requires at least` and `WC tested up to` tags on the plugin main file
* Bumped `Tested up to` tag

= 0.1 =
* Initial release sponsored by [Tua PT Store](https://www.tuaptstore.com)
