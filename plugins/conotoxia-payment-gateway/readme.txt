=== Conotoxia Pay Payment Gateway ===
Contributors: conotoxia
Tags: conotoxia, conotoxiapay, payments, payment, cinkciarz, woocommerce, payment gateway, credit card, apple pay, google pay, ideal, blik
Requires at least: 5.4
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 1.31.25
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Conotoxia Pay payment module for WooCommerce

== Description ==
Gain thanks to Conotoxia Pay!

Conotoxia Pay is an excellent tool to handle payments, making shopping in your online shop even easier and more pleasant.

Online stores customers using the Conotoxia Pay e-commerce payment system have a wide range of currencies in their hands - in addition to the Polish zloty, there are 27 other currencies. Conotoxia's offer for business is highly appreciated by merchants who not only care about cost efficiency but also lead international expansion and want their customers from different countries to pay in their local currency. Thanks to Conotoxia Pay, a store can flexibly accept payments in the currency of its choice (e.g. in euro) while simultaneously offering buyers the chance to pay in their card or bank account currency, e.g. in the US dollars.
Currently, Conotoxia Pay offers fast online bank transfers, BLIK, cards payments (Visa, Mastercard, Diners Club and others), PayPal, Apple Pay, Google Pay, Trustly, Skrill, Rapid Transfer or iDEAL.

*Why it is worth choosing Conotoxia Pay:*

* 27 currencies on offer. Favourable exchange rates for you and your customers.
* Low commissions: BLIK 0.9%, online transfers 0.9%, cards from 1.1%.
* Discounts for our partners.
* No activation fee.
* Wide range of payment methods: choose one or all available payment methods. Selected payment methods will be available to your customers paying with Conotoxia Pay.
* Instant payments.
* Encrypted connections.
* Strong customer authentication (SCA): enter an email code, a text message or use a mobile authentication.
* Straightforward integration.
* User-friendly payment service.

More information about Conotoxia Pay and available payment methods can be found at https://conotoxia.com/payments.

== Installation ==

*BUSINESS REQUIREMENTS*

* Create a business account at Conotoxia.com
* From the 'Merchant Panel' menu
    * add first shop and point of sale (POS),
    * generate access data (API client ID, API client password).

*TECHNICAL REQUIREMENTS*

* WordPress 5.4 - 6.4
* WooCommerce 4.2 - 8.2
* PHP 7.2 - 8.1
* License: GPLv2 or later
* PHP extensions:
    * curl
    * json
    * openssl
    * readline

*MANUAL PLUGIN INSTALLATION*

* Download the WooCommerce plugin.
* Unpack the zip file in the wp-content/plugins folder on your server.
* Enable the plugin in 'Plugins → Installed plugins'.
* Configure the plugin.
* Make a test payment (by yourself or with the help of your account manager).

[Contact us](https://conotoxia.com/contact-us/business) to check if the integration was successful.

= Updating =

We will ensure backwards compatibility for minor version update. For major version upgrades (e.g. 1.0.0 to 2.0.0) make sure you have a backup and use our sandbox to test

== Frequently Asked Questions ==

= Do I have to open an account with Conotoxia.com to use the plugin? =
Yes. If you want to integrate the Conotoxia Pay payment system, you must have a business account at the Conotoxia.com web portal. A business account can be created by a customer who has a registered business activity.
Details on how to integrate your shop can be found [here](https://conotoxia.com/help/payments/how-to-integrate-a-store).

= Are transactions made through Conotoxia.com web portal safe? =
Safety of transactions and protection of our customers' funds are our top priority.
All transactions are carried out online, eliminating the risks associated with cash transactions.
We also use encrypted connections, which comply with current standards.
Moreover, key processes on the Conotoxia.com web portal require strong customer authentication. With strong authentication, your passwords, finances and personal data are fully secured and inaccessible to others.
Web portal operations are transparent and reliable, which is confirmed by the reviews of external auditors, including
the international consulting company KPMG. More about security can be found at [here](https://conotoxia.com/about-us/security).

= Where can I seek support to integrate my store? =
If you need support to integrate your store or want to verify whether the integration has been successful, please contact us via email: payments@conotoxia.com.

= Are instructions for using the plugin available? =
Yes. Instructions are available [here](https://docs.cinkciarz.pl/platnosci/wtyczki/woocommerce/index.html#wstep).

= Can I test the integration in a test environment? =
Yes. We provide an environment that is used to test the plugin integration with us.

= Can I request a refund from the WooCommerce plugin for payments with Cinkciarz Pay? =
There is a possibility to order the refund from the plugin level as well as from the merchant panel in Conotoxia.com web portal.

== Screenshots ==

1. Configuration of the Conotoxia Pay payment plugin for WooCommerce.
2. Choosing the Conotoxia Pay payment method.
3. Ordering a payment transaction from Conotoxia Pay.
4. Payment confirmation - the payment has been successfully ordered.

== Changelog ==

= 1.31.19 =

* Plugin updated to be compatible with WordPress 6.4.

= 1.31.13 =

* Plugin updated to be compatible with WooCommerce 8.2.

= 1.31.5 =

* Plugin updated to be compatible with WooCommerce 8.1.

= 1.30.9 =

* Plugin updated to be compatible with WordPress 6.3.0 and WooCommerce 7.9.0.

= 1.29.6 =

* Plugin updated to be compatible with WordPress 6.2.2 and WooCommerce 7.8.2.

= 1.27.0 =

* Added the ability to make BLIK payments without leaving the Store (BLIK Level 0).

= 1.26.25 =

* Plugin updated to be compatible with WordPress 6.2 and WooCommerce 7.5.1.

= 1.26.5 =

* Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 7.2.3).

= 1.25.0 =

* Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 7.0.0).

= 1.21.0 =

* Plugin updated to be compatible with WordPress 6.0 and PHP 8.1.

= 1.20.1 =

* Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.9.1).

= 1.20.0 =

* Added Vipps icon.

= 1.19.0 =

* Added OnlineTransfer icon.

= 1.18.0 =

* Added option to set Conotoxia Pay as default selected payment method.

= 1.17.0 =

* Added option to select visible payment method icons on the payment method selection screen.

= 1.15.0 =

* Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.8.0).

= 1.14.0 =

* Added information about the requirement for public key activation

= 1.13.0 =

* Added support for refund CANCELLED status

= 1.11.0 =

* Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.4.2).

= 1.10.1 =

* Removing invalid characters and communication protocol from order description and refund reason

= 1.8.0 =

* Changes to allow support for custom order identifiers.

= 1.6.0 =

* Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.1.0).

= 1.4.0 =

* Updated WordPress and WooCommerce (up to 4.9.2)

= 1.1.0 =

* Added a possibility to hide the icon on the payment selection list.

= 0.9.0 =

* Added possibility to generate public and private keys during configuration.

= 0.8.0 =

* Added a sandbox mode that allows you to make payments on a test environment.

= 0.4.5 =

* Added payment identifier on payment summary page.