=== Piraeus Bank WooCommerce Payment Gateway ===
Contributors: enartia,g.georgopoulos,georgekapsalakis,akatopodis
Author URI: https://www.papaki.com
Tags: ecommerce, woocommerce, payment gateway
Tested up to: 6.4.3
Requires at least: 4.0
Stable tag: 1.7.1
WC tested up to: 6.2.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds Piraeus Bank paycenter as a payment Gateway for WooCommerce

== Important Notice == 
1. Piraeus Bank has announced that it will gradually abolish the Preauthorized Payment Service for all merchants, beginning from the ones obtained MIDs from 29/1/2019 onwards.
You are highly recommended to disable the preAuthorized Payment Service as soon as possible.

2. We would like to inform you that our Plugin is compatible with the 3D Secure version 2 changes required from Piraeus bank.

== Description ==
This plugin adds Piraeus Bank paycenter as a payment gateway for WooCommerce. A contract between you and the Bank must be previously signed. Based on original plugin "Piraeus Bank Greece Payment Gateway for WooCommerce" by emspace.gr [https://wordpress.org/plugins/woo-payment-gateway-piraeus-bank-greece/]

It uses the redirect method, and SSL is not required.


Requires SOAP installed in the server / hosting.
== Features ==
Provides pre-auth transactions and free instalments.

== Installation ==

Just follow the standard [WordPress plugin installation procedure](http://codex.wordpress.org/Managing_Plugins).

Provide to Piraeus bank at epayments@piraeusbank.gr the following information, in order to provide you with test account information. 
WITH PERMALINKS SET
* Website url :  http(s)://www.yourdomain.gr/
* Referrer url : http(s)://www.yourdomain.gr/checkout/
* Success page :  http(s)://www.yourdomain.gr/wc-api/WC_Piraeusbank_Gateway?peiraeus=success
* Failure page : http(s)://www.yourdomain.gr/wc-api/WC_Piraeusbank_Gateway?peiraeus=fail
* Cancel page : http(s)://www.yourdomain.gr/wc-api/WC_Piraeusbank_Gateway?peiraeus=cancel

WITHOUT PERMALINKS (MODE=SIMPLE)
* Website url :  http(s)://www.yourdomain.gr/
* Referrer url : http(s)://www.yourdomain.gr/checkout/
* Success page :  http(s)://www.yourdomain.gr/?wc-api=WC_Piraeusbank_Gateway&peiraeus=success
* Failure page : http(s)://www.yourdomain.gr/?wc-api=WC_Piraeusbank_Gateway&peiraeus=fail
* Cancel page : http(s)://www.yourdomain.gr/?wc-api=WC_Piraeusbank_Gateway&peiraeus=cancel

Response method : GET / POST
Your's server IP Address 

=== HTTP Proxy ===
In case your server doesn't provide a static IP address for your website, you can use an HTTP Proxy for outgoing requests from the server to the bank. The following fields need to be filled for http proxying:
HTTP Proxy Hostname: Required. If empty then HTTP Proxy is not used.
HTTP Proxy Port: Required if HTTP Proxy Hostname is filled.
HTTP Proxy Login Username/Password: Optional.



== Frequently asked questions ==
= CardHolder Name Field =
According to Piraeus bank’s technical requirements related to 3D secure and SCA, the cardholder’s name must be sent before the customer is redirected to the bank’s payment environment. You choose not to show this field by uncheck the "Enable Cardholder Name Field" in plugin's settings, we will automatically send the full name inserted for the order, with the risk of having the bank refusing the transaction due to the validity of this field.
= Enable Debug Mode = 
In order to enable the debug mode, you should  add in your wp-config file (in the root folder of installation) the following lines: 
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

Then you have to enable the debug mode from plugin's settings page.
= Missing states information from a country =
Woocommerce have a list of states for each country, from where we send the information to the Piraeus Bank for the fields BillAddrState, ShipAddrState. 
But for some countries i.e. Cyprus, Germany woocommerce don't have the appropriate informations. 
In these cases you should follow the [instructions](https://docs.woocommerce.com/document/addmodify-states/# ) of Woocommerce to add/edit States.



== Changelog ==

= 1.7.1 =
Fix bug in 1.7.0

= 1.7.0 =
Fix vulnerability for sql injection

= 1.6.5.1 =
Compatibility with Woocommerce 6.2.1

= 1.6.5 =
Added technical specs needed for the bank, rendered in the settings page
Render error descriptions
Update Translations
Add option to enable/disable for the 2nd payment email with transaction details

= 1.6.4 =
Extra validations checks for phone numbers
Compatibility with Woocommerce 5.0
Add text for "without installation" option

= 1.6.3 =
Extra validations checks for phone numbers
Add Germany's states list in woo commerce

= 1.6.2 =
Add cardholder name input field in checkout
Extra validation for foreign countries state field 
Add cyprus states list in woocommerce
Add debugging mode, to log certain information
Replaced deprecated reduce_order_stock with wc_reduce_stock_levels
Fix minor php warnings

= 1.6.1 =
extra validation for country calling number
extra fallback if no shipping address available
add transaction id in order note

= 1.6.0 =
Compatibility with PSD2 (3D Secure version 2)


= 1.5.8 = 
fix an issue with proxy settings

= 1.5.7 = 
Sanitize Data
update compatibility status with WooCommerce 4.3.0

= 1.5.6 = 
update compatibility status with WooCommerce 4.1.0

= 1.5.5 = 
update compatibility status with WooCommerce 4

= 1.5.4 = 
fix release version

= 1.5.3 = 
Update translations

= 1.5.2 = 
Added max size for Logo of Piraeus Bank

= 1.5.1 = 
For downloadable products, auto mark the order as completed only if all the products are downloadable
Update translations
Added option to display or not Piraeus Bank's logo in checkout page.

= 1.5.0 = 
POST response method is now available
Added Max number of instalments based on order total
Support for English, German and Russian language in redirect page.

= 1.4.2 =
Fix issue for failed status of order but with paid transaction 

= 1.4.1 =
Bug Fixes (Pay again, after failed payment attempt)

= 1.4.0 =
New Piraeus API encryption algorithm

= 1.3 =
Added Proxy configuration option.

= 1.0.6 =
WooCommerce backwards compatible

= 1.0.4 =
WooCommerce 3.0 compatible

= 1.0.3 =
Text changed. New Title[GR]: Με κάρτα μέσω Πειραιώς

= 1.0.2 =
Bug Fixes

= 1.0.1 =
Bug Fixes

= 1.0.0 =
Initial Release

