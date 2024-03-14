=== metaps PAYMENT for WooCommerce ===
Contributors: artisan-workshop-1, shohei.tanaka
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=info@artws.info&item_name=Donation+for+Artisan&currency_code=JPY
Tags: woocommerce, ecommerce, e-commerce, Japanese
Requires at least: 5.0.0
Tested up to: 6.2.0
Stable tag: 1.3.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

metaps PAYMENT gateway payment for WooCommerce.

== Description ==

This plugin extends the WooCommerce shop plugin for metaps PAYMENT (before PAYDESIGN) in Japan.

Notice: If "Lawson/Ministop" is checked in the convenience store payment column, please also check "Seicomart". You cannot use "Seicomart" unless you check the box.

= Key Features =

1. Credit Card Payment. ( include Subscription )
2. Credit Card Payment with TOKEN.
3. Convenience Store Payment.
4. Payeasy Payment.

== Installation ==

= Minimum Requirements =

* WordPress 4.5 or greater
* WooCommerce 2.5 or greater
* PHP version 7 or greater
* MySQL version 5.6 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Woo PAYDESIGN, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Woo PAYDESIGN” and click Search Plugins. Once you’ve found our eCommerce plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =
The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application.

== Frequently Asked Questions ==
Q: Do you have the forum of this plugin in Japanese?<br />
A: Here<br />
<a href="https://wordpress.org/support/plugin/woo-paydesign/">(wordpress.org Support Forum)https://wordpress.org/support/plugin/woo-paydesign/</a>

== Screenshots ==
1. Admin setting page for Paydesign
2. Admin setting page at Credit Token

== Changelog ==

= 1.3.0 - 2023-05-15 =
* Dev - Changed CVS payment to New status.

= 1.2.0 - 2022-03-09 =
* Dev - Changed the directory structure according to the WooCommerce coding rules.
* Update - JP4WC Framework 2.0.12
* Fixed - Bug that inventory decreases twice when user ID payment is performed.
* Fixed - Inventory adjustment bug when canceling after moving the link destination of linked payment.
