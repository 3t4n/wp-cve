=== LINE Pay for WooCommerce ===
Contributors: artisan-workshop-1, shohei.tanaka
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=info@artws.info&item_name=Donation+for+Artisan&currency_code=JPY
Tags: woocommerce, ecommerce, e-commerce, japanese,payment, paidy
Requires at least: 5.0
Tested up to: 5.8.0
Stable tag: 1.1.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

LINE Pay for WooCommerce

== Description ==

This plugin extends the WooCommerce shop plugin for LINE Pay in Japan.

= About LINE Pay =

This plugin enables LINE PAY to be used with WooCommerce. It has been confirmed to work only in the Japanese environment.
* Please contact us if you would like to cooperate with the operation check such as Taiwan.

Application and examination are required to actually use payment. Please apply from the following.

[LINE Pay member store application](https://pay.line.me/merchant-apply/jp/selection/agency-login/5555ea9e193d33b4936e070ba98d82dc19548b610b8bfbdad62938)

= Key Features =

1. LINE Pay checkout.
  Without entering the shipping address information from the WooCommerce cart screen, the shipping address information registered in his LINE Profile + function in the LINE app will be applied and payment will be made. This is a function that does not require the trouble of entering an address.
2. Webhook method (shipping fee).
3. Cooperation between LINE Pay and WooCommerce.

== Installation ==

Implement postpaid payment in WooCommerce with LINE Pay.

= Minimum Requirements =

* WordPress 5.0 or greater
* WooCommerce 4.0 or greater
* PHP version 7.1 or greater
* MySQL version 5.6 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Woo sbp, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “LINE Pay for WooCommerce” and click Search Plugins. Once you’ve found our eCommerce plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =
The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application.

== Frequently Asked Questions ==

= Can I use it as soon as I install the plugin? =

No, after application, it becomes available after providing information such as exam environment. In addition, examination is necessary for production environment use.

== Screenshots ==


== Changelog ==

= 1.1.2 - 2021/10/17 =
* Dev - Available in Taiwan dollars(TWD). 

= 1.1.1 - 2021/08/29 =
* Update - JP4WC Framework 2.0.12
* Fixed - Shipping endpoint and tax bug fixed.

= 1.1.0 - 2021/08/24 =
* Fixed - Cart handling payment for New API.
* Fixed - Some bugs fixed.

= 1.0.8 - 2021/06/14 =
* Update - JP4WC Framework 2.0.9
* Fixed - Last name and first name reversal

= 1.0.7 - 2020/08/24 =
* Update - JP4WC Framework 2.0.6
* Enhancement - Compatibility fixes for WordPress 5.5

= 1.0.6 - 2020/05/10 =
Fix - for Virtual products.

= 1.0.1 & 1.0.2 & 1.0.3 - 2020/01/22 =
* Fix - Taxable product's bug.
* Fix - yomigana required bug.
* Fix - shipping phone required bug.

= 0.9.3 - 2019/12/17 =
* Fix - some bugs and Text message.

= 0.9.2 - 2019/11/19 =
* Fix - sandbox bug.
* Dev - LINE Pay Checkout.

= 0.9.1 - 2019/11/12 =
* Fix - Phone Input bug.

= 0.9.0 - 2019/10/23 =
* Dev - Minor version update.
* Dev - API setting.
