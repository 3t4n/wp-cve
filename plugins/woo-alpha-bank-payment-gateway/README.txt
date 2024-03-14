=== Payment Gateway - Alpha Bank for WooCommerce ===
Contributors: enartia,georgekapsalakis,g.georgopoulos,akatopodis
Author URI: https://www.papaki.com	
Tags: ecommerce, woocommerce, payment gateway, alphabank, alpha
Tested up to: 5.9.0
Requires at least: 4.0
Stable tag: 1.3.7
WC tested up to: 6.2.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description == 

This Plugin adds Alpha Bank paycenter as a payment gateway for WooCommerce. 
A contract between you and the Bank must be previously signed.
It uses the redirect method, and SSL is not required.
Plugin is compliant with new EMV 3D Secure specifications. 


= Features =
Provides pre-auth transactions and free instalments(either deeping on order total amount or not).

== Installation ==

Just follow the standard [WordPress plugin installation procedure](http://codex.wordpress.org/Managing_Plugins).

Add the Username and password provided by the bank.

In order to enable MasterPass you should go to Woocommerce Payment methods and enable the «Alpha Bank Masterpass» payment method


== Frequently asked questions ==
= Does it work? =
Yes
= How can i enable the MasterPass? = 
In order to enable MasterPass you should go to Woocommerce Payment methods and enable the «Alpha Bank Masterpass» payment method


== Changelog ==
= 1.3.7 =
update compatibility with Woocommerce 6.2
= 1.3.6 =
Add option to enable/disable for the 2nd payment email with transaction details
Update Translations
Add debugging mode, to log certain information

= 1.3.5 =
Compatibility with Woocommerce 5.0
Add text for "without installation" option

= 1.3.4 =
Change test url for masterpass environemt

= 1.3.3 =
Exclude billing state info for 3DS if country is Greece

= 1.3.2 =
Change test url for post requests

= 1.3.1 =
Updated Texts and compatibility with Woocommerce 4.6.1

= 1.3.0 =
Update compatibility with new Alpha Bank's technical specs 
Update compatibility with WooCommerce 4.4.0
For downloadable products, don’t auto mark the order as completed, unless all the products are downloadable

= 1.2.1 =
Update compatibility with WooCommerce 4.1.0

= 1.2.0 =
Fix an issue with wc session and the id of the order 

= 1.1.2 =
Update translations

= 1.1.1 =
Fix an issue with Pre-authorized transactions

= 1.1.0 =
Added option to display or not Alpha Bank's logo in checkout page.
Added option to enable or not MasterPass as separate payment method.

= 1.0.0 =
Initial release
