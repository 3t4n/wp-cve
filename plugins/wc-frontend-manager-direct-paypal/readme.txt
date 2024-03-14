=== WCFM - Direct PayPal Pay for WooCommerce Multivendor Marketplace ===
Contributors: wclovers
Tags: woocommerce marketplace, paypal, vendor, wcfm, multi vendor
Donate link: https://www.paypal.me/wclovers/25usd
Requires at least: 4.4
Tested up to: 6.4.2
WC requires at least: 3.0
WC tested up to: 8.5.1
Requires PHP: 5.6
Stable tag: 2.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Direct pay in vendor's PayPal account from customer account.

== Description ==

Direct vendor payment for WCFM Marketplace using PayPal.

> It's an addon plugin for -
> [WooCommerce Multivendor Marketplace](https://wordpress.org/plugins/wc-multivendor-marketplace/)
>
> [Know more about this ...](https://wclovers.com/blog/woocommerce-multivendor-marketplace-wcfm-marketplace/)
>

= WHY DIRECT PAYMENT REQUIRED? =

Marketplace laws in some countries, do not allow platform owners to hold payment. This implies, any purchase made by the customer should go directly to the vendor account.

= INTRODUCTION =

By default, when customers made a purchase, the money goes to the marketplace owner, who then distributes it among vendors.

In case you want to enable direct payment, WCFM Marketplace offers that via Stripe. This feature is inbuilt and you don't need any add-ons for this.

In direct payment, the customer's money first gets split between the owner and vendors based on the commission settings, and then credited to their individual accounts.

= WHY PAYPAL, THEN? =

PayPal is available in more than [200 countries/regions and support 25 currencies](https://www.paypal.com/in/webapps/mpp/country-worldwide), whereas Stripe is available for businesses in [34 countries](https://stripe.com/global). Got the point?

= WHAT OPTIONS DO WE HAVE IN PAYPAL? =

PayPal Marketplace API has been implemented from the version 2.0.0 of this plugin.

= WHAT WCFM Marketplace - DIRECT PAYPAL PAY DO? =

WCFM Direct PayPal Pay enables payment to be split between admin & vendors. The commission amount goes directly to vendor's PayPal account & the admin fee also goes to admin's PayPal account. This is now implemented by PayPal Marketplace API.


= RESTRICTIONS =

👉 One order will contain products from maximum 10 vendors (due to PayPal Marketplace API restrictions)
👉 Vendor’s PayPal account should be a business account


= Feedback =

All we want is love. We are extremely responsive about support requests - so if you face a problem or find any bugs, shoot us a mail or post it in the support forum, and we will respond within 6 hours(during business days). If you get the impulse to rate the plugin low because it is not working as it should, please do wait for our response because the root cause of the problem may be something else.

It is extremely disheartening when trigger happy users downrate a plugin for no fault of the plugin.


Really proud to serve and enhance [WooCommerce](http://woocommerce.com).

Be with us ... Team [WC Lovers](https://wclovers.com)

== Installation ==

= Minimum Requirements =

* WordPress 4.7 or greater
* WooCommerce 3.0 or greater
* PHP version 5.6 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of WooCommerce Multivendor Marketplace, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Multivendor Marketplace - Direct PayPal" and click Search Plugins. Once you've found our eCommerce plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our eCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== FAQ ==

NONE.


== Changelog ==

= 2.0.1 =
*Updated - 19/01/2024*
* Enhance - WordPress 6.4+ compatibility added
* Enhance - WooCommerce 8.5.1+ & HPOS compatibility added
* Tweak - Settings added in backend to allow/disallow unbranded credit card
* Fix - Payment gateway not showing when WooCommerce Bookings plugin is activated
* Fix - Minor bug fixes

= 2.0.0 =
*Updated - 26/07/2022*
* Enhance - WordPress 6.0.1+ compatibility added
* Enhance - WooCommerce 6.7.0+ compatibility added
* Feature - PayPal Commerce Platform Split Pay feature added
* Feature - WooCommerce orders can now be split between multiple vendors

= 1.1.1 =
*Updated - 26/09/2021*

* Enhance - WordPress 5.8+ compatibility added
* Enhance - WooCommerce 5.7+ compatibility added

= 1.1.0 =
*Updated - 26/03/2021*

* Enhance - WordPress 5.7+ compatibility added
* Enhance - WooCommerce 5.1+ compatibility added

= 1.0.6 =
*Updated - 09/05/2020*

* Enhance - WooCommerce 4.1+ compatibility added
* Tweak   - If vendor not yet setup PayPal setting then PayPal payment method auto-disabled from checkout page

= 1.0.5 =
*Updated - 15/03/2020*

* Enhance - WordPress 5.4+ compatibility added
* Enhance - WooCommerce 4.0+ compatibility added

= 1.0.4 =
*Updated - 08/02/2020*

* Enhance - WooCommerce 3.9 compatibility added

= 1.0.3 =
*Updated - 18/11/2019*

* Enhance - WordPress 5.3 compatibility added
* Enhance - WooCommerce 3.8 compatibility added

= 1.0.2 =
*Updated - 04/09/2019*

* Enhance - Store Setup widget "Direct PayPal" setting support added
* Tweak   - Direct PayPal setting field "API Passowerd" and "API Signature" type change to "passowrd" type (security enhancement)

= 1.0.1 =
*Updated - 13/08/2019*

* Enhance - WC 3.7 compatibility added

= 1.0.0 =
*Updated - 07/08/2019*

* Initial version release


== Upgrade Notice ==

= 2.0.1 =
* Enhance - WordPress 6.4+ compatibility added
* Enhance - WooCommerce 8.5.1+ & HPOS compatibility added
* Tweak - Settings added in backend to allow/disallow unbranded credit card
* Fix - Payment gateway not showing when WooCommerce Bookings plugin is activated
* Fix - Minor bug fixes
