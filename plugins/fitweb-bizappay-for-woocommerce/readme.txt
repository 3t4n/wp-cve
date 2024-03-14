=== Plugin Name ===
Contributors: 	fitweb
Tags: payment gateway, Malaysia, online banking, ecommerce
Requires at least: 4.3
Tested up to: 6.2.2
Stable tag: 3.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

FITWEB Integration of Bizappay for WooCommerce. 

== Description ==

FITWEB Integration of Bizappay for WooCommerce . This plugin enable online payment using online banking (for Malaysian banks only). This plugin is only available for online businesses that reside in Malaysia.

== Installation ==

1. Make sure that you already have WooCommerce plugin installed and activated.
2. From your Wordpress admin dashboard, go to menu 'Plugins' and 'Add New'.
3. Key in 'FITWEB' in the 'Search Plugins' field and press enter.
4. It will display the plugin and press intall.
5. Activate the plugin through the 'Plugins' screen in WordPress.
6. Go to menu WooCommerce > settings > Payments and fill in your BIZAPPAY's secret key. You can retrieve the secret key from BIZAPPAY Dashboard at https://www.bizappay.com 
7. Make sure the 'Enable this payment gateway' is ticked. Click on 'Save changes' button.
8. In BIZAPPAY Woocommerce Configuration page, make sure you key in your return URL and callback URL as http://your_domain/checkout and finally press Submit.

== Frequently Asked Questions ==

= Do I need to sign up with Bizappay in order to use this plugin? =

Yes, we require info such as secret key that is only available after you sign up with Bizappay.

= Can I use this plugin without using WooCommerce? =

No.

= What currency does it support? =

Currently this plugin and Bizappay only support Malaysian Ringgit (RM).

= What if I have some other question related to this plugin? =

Please open a ticket by by sending an email to business@fitweb.my

== Changelog ==

= 1.0.0 =
* initial release

= 1.0.1 =
* support multisite in 1 Bizappay account

= 1.0.2 =
* Enable sandbox mode

= 1.0.3 =
* Security update and compatibility update with wordpress and woocommerce version

= 1.0.4 =
* Fix callback issue

= 1.0.5 =
* Fix checkout issue

= 1.0.6 =
* Additional feature to support Bizapp for Woocommerce

= 1.0.7 =
* Additional feature to support Woocommerce

= 1.0.8 =
* Pointing to new domain (bizappay.my) with more upcoming features. 

= 1.0.9 =
* New compatibility version. 