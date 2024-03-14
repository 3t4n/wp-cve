=== WooCommerce PayPal Here Payment Gateway ===
Contributors: automattic, skyverge
Tags: ecommerce, e-commerce, commerce, woocommerce, wordpress ecommerce, store, sales, sell, pos, point of sale, shop, shopping, cart, checkout, paypal
Requires at least: 4.4
Tested up to: 5.7
Requires PHP: 5.3
Stable tag: 1.1.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Accept payment in-person using PayPal Here as a point-of-sale system.

== Description ==

PayPal Here&trade; is a mobile payment solution that lets you accept multiple forms of payment simply and securely, wherever your business takes you. Using a PayPal card reader, you can accept payments in-person for WooCommerce orders.

You'll need the PayPal Here app for your iOS or Android device to use the PayPal Here point-of-sale solution. [Click here to learn more about PayPal Here](https://www.paypal.com/us/webapps/mpp/credit-card-reader).

= Powering simple in-person payments =

PayPal Here allows you to take payments for your WooCommerce store, wherever you are.

The PayPal Here plugin for WooCommerce simplifies order creation in WooCommerce on mobile devices to let you quickly create an order for PayPal Here.

1. Once this order is created, you can click a button to open the PayPal Here app to take payment.
2. Swipe a card or take payment via cash in the PayPal Here app.
3. Order details and status are automatically updated in WooCommerce behind the scenes from PayPal!

Using a desktop in-store to add or scan items? A QR code is generated for every order that needs payment. This lets you scan the QR code from a mobile device with a card reader attached, and immediately take payment for any pending or previously created order.

== Installation ==

= Minimum Requirements =

* WordPress 4.4 or greater
* WooCommerce 3.0 or greater
* PHP version 5.3 or greater
* PayPal Here app installed on a compatible iOS or Android Device
* PayPal Here-compatible card reader

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of this plugin, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type `WooCommerce PayPal Here` and click "Search Plugins". Once you’ve found our plugin, you can view details about it such as the point release, rating, and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

If on the off-chance you do encounter issues with the shop/category pages after an update you simply need to flush the permalinks by going to WordPress > Settings > Permalinks and hitting 'save'. That should return things to normal.

== Frequently Asked Questions ==

= Can online customers pay using PayPal Here? =

No, PayPal Here is a point-of-sale (POS) solution. It's designed to take payments in-person, while syncing them to your WooCommerce store.

= Does this support recurring payments, like for subscriptions? =

No - this plugin helps you connect your store to PayPal for in-person (card present) transactions. As such, saving cards to a vault for later is not supported.

= What currencies can I use? =

This plugin supports all currencies allowed by PayPal Here. Supported currencies are: USD, GBP, and AUD.

= Does this support both production mode and sandbox mode for testing? =

No. PayPal Here requires an iOS or Android app on your device to process payments when a credit or debit card is swiped. These apps do not have a sandbox or test mode available, so only live payments can be processed.

You must use a live PayPal account to test transactions as a result. We recommend testing by using the "Cash" method in the PayPal Here app, or using a small transaction if you need to test credit card payments (PayPal's minimum transaction is $1).

= Is PayPal Here compatible with all WooCommerce extensions? =

Most extensions are supported. Some plugins (Local Pickup Plus is an example) make pretty advanced changes to the "Create order" screen in WooCommerce, and therefore aren't supported. If you need to create orders that support specific plugins to pay using PayPal Here, you can:

1. Create the order by browsing your shop and adding items to the cart.
2. Create an order that needs payment by using a "pay later" gateway, such as the "Check" gateway.
3. Open that order in your store admin, and pay in PayPal Here using the PayPal Here action or QR code.

= Where can I get support or talk to other users? =

If you get stuck, you can ask for help in the Plugin Forum, or [reach out to WooCommerce support](https://woocommerce.com/my-account/create-a-ticket/) for support via email.

== Screenshots ==

1. Configure your PayPal Here settings.
2. Start a new order on mobile.
3. Simplified mobile order creation.
4. Open PayPal Here app from a mobile device.
5. Open PayPal Here via QR code.
6. PayPal Here in action!

== Changelog ==

= 2021.04.01 - version 1.1.3 =
 * Misc - Add support for WooCommerce 5.1

= 2020.05.04 - version 1.1.2 =
 * Misc - Add support for WooCommerce 4.1

= 2020.03.10 - version 1.1.1 =
 * Misc - Add support for WooCommerce 4.0

= 2020.01.09 - version 1.1.0 =
 * Misc - Add support for WooCommerce 3.9

= 2019.01.22 - version 1.0.1 =
 * Tweak - Limit overall invoice ID length to 25 characters for compatibility with PayPal API
 * Fix - Only display PayPal Here metabox for orders that require payment

= 2018.12.12 - version 1.0.0 =
 * Hello world! Initial release
