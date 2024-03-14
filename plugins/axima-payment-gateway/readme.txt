=== Pays – WooCommerce Payment Gateway ===
Contributors: marekgach, pavoocek, anickafil
Donate link:
Tags: payment, gate, mobile, sms, eet, pays, pays.cz
Requires at least: 4.5
Tested up to: 6.1
Stable tag: 2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Payment gateway, supporting EUR, USD and CZK. Contains all common payment methods, including mobile and SMS payments. Support for EET included.

== Description ==

Pays is a free payment gateway plugin for merchants, store owners and freelancers primarily located in the Czech and Slovak Republic. Pays.cz is a Czech payment gateway fully licensed by the Czech national bank to provide payment services.

To be able to use this plugin, you need to have [an account with Pays.cz](https://www.pays.cz/). You can register for free on our website, where you pay a one-time activation fee 600 CZK (around 24 EUR). There are no monthly fees, or hidden costs with Pays WooCommerce plugin: you are only charged when you make money.

= SUPPORTED PAYMENT METHODS VIA PAYS.CZ ARE: =

* Card Payments (3D secure payments, VISA, MasterCard)
* QR, Bank transfer
* Google Pay (Apple Pay coming soon)
* Mobile / SMS payments suitable for low charges (20, 50, 100, 200 CZK).

= AVAILABLE CURRENCIES: =
* CZK, USD, EUR

= WHY USE PAYS – PAYMENT GATEWAY WOOCOMMERCE PLUGIN: =
* Easy account set up on our website (currently available in Czech)
* Customer support 24/7
* There are no hidden costs or fees, and the pricing is simple and transparent
* We provide free updates when new features and payment methods become available
* Secure payments

Please note:
If you decide to uninstall WooCommerce, be sure to uninstall the Pays plugin first to avoid possible complications in WordPress administration.

== Installation ==

Please [follow this link for plugin description and installation information in Czech.](https://www.pays.cz/blog-woocommerce-platebni-brana.asp)

= PAYMENT GATEWAY ACTIVATION IN PAYS PLUGIN =
Please follow the standard WordPress plugin installation procedure. Once your plugin has been installed, you need to ensure that your Pays.cz is set in WooCommerce properly.

After activating your Pays plugin in WordPress administration:

1. Go to Pays.cz plugin on the left and open tab Settings.
2. Fill in MerchantID, ShopID and API password, you can find these details after logging in to your account on Pays.cz and clicking on settings.
3. Set payments URLs and inform Pays.cz technical support accordingly by submitting the light blue button, which will send an automated email.

= PAYS ACTIVATION IN WOOCOMMERCE =
1. Click on "Settings" in WooCommerce plugin.
2. Select "Payments" tab.
3. Make sure that Pays.cz option is activated.
4. Click on "Manage".

Once you click on "Manage", you can enter your own description for this payment option and ensure that payment via Pays.cz is enabled.

You can check this installation process in screenshots at the bottom [of the Details tab](/plugins/axima-payment-gateway/#screenshots).

== Screenshots ==
1. Payment gateway activation on plugin settings page
2. Pays payment method activation in WooCommerce
3. Change payment description screen
4. Customer's checkout page

== Changelog ==

= 2.4 =
* Fix email param encoding in Pays redirect address

= 2.3 =
* Use WooCommerce thank you and payment retry page by default
* Settings page changes

= 2.2 =
* Fix of warnings
* Add link for payment detail
* Check if price or currency was not changed in gateway during payment

= 2.1 =
* Action to send setting via e-mail
* Confirmation action changes
* Setting page tune up

= 2.0 =
* Czech translation

= 1.0 =
* Released plugin
