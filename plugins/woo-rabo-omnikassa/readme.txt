=== Rabo Smart Pay for WooCommerce ===
Contributors: codebrainbv
Donate link: https://www.ideal-checkout.nl/over-ons/donatie
Tags: omnikassa, smartpay, smart pay, rabobank, payment, ideal, woocommerce
Requires at least: 5.0.7
Tested up to: 6.4.2
Stable tag: /trunk/
Requires PHP: 7.4.30
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One of the best integrated and easy to use Payment Method plug-in for Rabo Smart Pay in WooCommerce.

== Description ==

One dashboard for all your payment solutions

What is Rabo Smart Pay?
Always in control and able to manage your products yourself: that is Rabo Smart Pay. You receive all payments within 1 day on your business account, 365 days per year. You can check and manage everything yourself in the dashboard. That gives peace, space and time to be able to continue working on your business.

How does Rabo Smart Pay work?
With Rabo Smart Pay you have both debit card payments and online payments together in one overview. Useful to keep track of your various commercial units, your physical store(s) or your webshops. If you want to expand, you can easily add additional products through the dashboard. For instance when you use Rabo SmartPin on location, but also want to sell your articles in a webshop. This way you can work in a future-oriented manner.

What do you need?
* A Rabo Smart Pay agreement, which you can simply request online.
* The Wordpress Smart Pay 2.0 plug-in.

== Installation ==

1. Upload the zip file in the wordpress backend plugin uploader.
2. Activate the plug-in via the 'plugins' screen in WordPress
3. Navigate to WooCommerce/Settings -> Payments and configure the plug-in/payment methods

== Frequently Asked Questions ==

= Who can I contact when i have a question? =

For any technical questions you can get in contact with iDEAL-Checkout via phone or email.
You can find our contact information via the following link:  https://www.ideal-checkout.nl/contact

If you have any questions about Rabo Smart Pay you can contact Smart Pay via phone or email.
You can find their contact information via the following link: https://www.rabobank.nl/smartpay

= Transactions aren't updating? =

There can be several reasons why the transactions aren't updating, all of them are related to the webhook. From the 1st of december there will be an update to the webhook security that could prevent transactions from updating as well. The list below will be issues from common to least common.

1. The webhook is not configured on the Rabo Smart Pay dashboard
2. Handshake fails or firewall blocks the request. The accesslogs/usagelogs of the server will also indicate if the previous mentioned could be an issue.
3. iThemes Security plugin is active. The iThemes plugin has an option to enable HackRepair.com’s ban list feature (WordPress admin » Security » Banned Users » Configure setting button), which blocks user agents that contain “Java” and Smart Pay includes “Java” in their user agent identification for webhook requests. Disabling this feature resolved webhook requests being processed. (Thank you Reüel of Pronamic for this information).
4. Something is going wrong in the plug-in, please contact us.


Update from 1st of december 2020:
A cipher suite is removed from the allowed list, that is:
- TLS_RSA_WITH_AES_256_CBC_SHA256

The ciphers that are supported are the following:
- TLS_ECDHE_RSA_WITH_AES_256_CBC_SHA384,
- TLS_ECDHE_ECDSA_WITH_AES_256_GCM_SHA384,
- TLS_ECDHE_ECDSA_WITH_AES_256_CBC_SHA384,
- TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384.

Update as of the 1st of july 2021
The following ciphers are also added to the webhook:

- TLS_ECDHE_ECDSA_WITH_AES_128_GCM_SHA256
- TLS_ECDHE_RSA_WITH_AES_128_GCM_SHA256
- TLS_DHE_RSA_WITH_AES_256_GCM_SHA384
- TLS_DHE_RSA_WITH_AES_128_GCM_SHA256

You can check the supported ciphers on your website by going to: https://www.ssllabs.com/ssltest/


= Update on the ciphers =

The Rabobank will be changing the accepted ciphers for outgoing calls on your webserver.
This included transaction announcements and gatekeeper calls.

Provide the list below to your server manager, they need to check the openSSL library and configuration;

Current accepted ciphers:

TLS-AES-256-GCM-SHA384
TLS-CHACHA20-POLY1305-SHA256
TLS-AES-128-GCM-SHA256  
ECDHE-RSA-AES256-GCM-SHA384
ECDHE-RSA-AES128-GCM-SHA256
ECDHE-RSA-CHACHA20-POLY1305
TLS_AES_128_CCM_8_SHA256
TLS_AES_128_CCM_SHA256
TLS_ECDHE_RSA_WITH_AES_256_CBC_SHA384
TLS_ECDHE_RSA_WITH_AES_128_CBC_SHA256
TLS_ECDHE_RSA_WITH_AES_256_CBC_SHA
TLS_ECDHE_RSA_WITH_AES_128_CBC_SHA

Updated list of accepted ciphers:

TLS-AES-256-GCM-SHA384
TLS-CHACHA20-POLY1305-SHA256
TLS-AES-128-GCM-SHA256  
ECDHE-RSA-AES256-GCM-SHA384
ECDHE-RSA-AES128-GCM-SHA256
ECDHE-RSA-CHACHA20-POLY1305



== Screenshots ==

1. Payment window in Rabo Smart Pay using the iDEAL method.
2. Payment methods in Rabo Smart Pay

== Changelog ==

= 2.2.9.4 =
* Changed the way methods are loaded and made the installation smaller.
* Added compatibility with the block checkout which is default with new installations.
* Checked compatibility with Wordpress 6.4.2 and Woocommerce 8.4.0, 8.5.0 and 8.5.1.
* Removed iDEAL bank/issuer option, since this will be removed when iDEAL 2 hits.
* Removed Afterpay data, since this is now removed from the Smart Pay, will return once Billink is implemented on their side.

= 2.2.9.3 =
* Checked compatibility with Woocommerce 8.3.0.

= 2.2.9.2 =
* Updated the way the accesstoken and issuers are cached.
* Made a change to the return of payments, through webhook and customer return.
* Checked compatibility with Woocommerce 8.1.1.

= 2.2.9.1 =
* Changed the signature validation of the webhook.
If a payment is cancelled there is data missing from the payload, but it is used for the signature validation.
We have implemented a fix to add the missing data, so that the signature validation is done correctly.

= 2.2.9 =
* Changed the credticard method to also indicate Apple Pay support.
* Orders update through the webhook, now also with the payment method used.
* FORCE_ALWAYS changed to FORCE_ONCE.
* Checked compatibility with Wordpress 6.3 and Woocommece 8.0.2.


= 2.2.8.5 =
* Fixed an issue where some Afterpay/Riverty transactions were declined because of an JSON encoding issue.
* Checked compatibility with WooCommerce 7.8.2.

= 2.2.8.4 =
* Added an additional check for Afterpay/Riverty data containing non alfanumeric characters, which caused Afterpay/Riverty to decline the request.
* Checked compatibility with Wordpress 6.2.2 and WooCommerce 7.7.2.


= 2.2.8.3 =
* Fixed a translation error, reported by Robbert (Thank you).


= 2.2.8.2 =
* Checked compatibility with Woocommerce 7.3


= 2.2.8.1 =
* Checked compatibility with Woocommerce 7.1
* Checked compatibility with Wordpress 6.1

= 2.2.8 =
* Checked compatibility with Woocommerce 6.9.0
* Added Sofort as a payment method
* Added quicklinks to the plug-in overview page
* Changed the OmniKassa name to Smart Pay, this is now the new name.
See Rabobank website for more information: https://www.rabobank.nl/bedrijven/betalen/klanten-laten-betalen/rabo-omnikassa/naamswijzigingsmartpay


= 2.2.7 =
* Checked compatibility with Woocommerce 6.8.1.
* Fixed a small inconsistancy error with the transaction ID in the notes.
The transaction ID noted for starting is not the actual transaction ID but the Smart Pay Order Id.
Refunds using this Transaction ID will come in a future update (2.3.0)

= 2.2.6 =
* Checked compatibility with Woocommerce 6.7.0.
* Integrated Webhook 2.0 of the Rabo Smart Pay.
This will be used in a future update, where the refunds will be integrated.

= 2.2.5 =
* Checked compatibility with Woocommerce 6.6.0.
* Fixed an issue where the Smart Pay settings wouldnt show.

= 2.2.4 =
* Added fullName to customerInformation, this way the customer name will be shown on the Rabo Smart Pay dashboard
* Checked compatibility with WooCommerce 6.4.1

= 2.2.3 =
* Improved the Afterpay experience, data was not passed on correctly.
* Improved the error messages shown to customers when the Rabobank wasnt providing the correct response.
In case of a Rabobank malfunction HTML was returned instead of json, this could break the issuer list.
* Tested on latest WooCommerce version

= 2.2.2 =
* Updated deprecated functions
* Tested on latest WooCommerce version

= 2.2.1 =
* Made width and height smaller for the SVG images.

= 2.2.0 =
* Added a new feature; It is now possible to let users choose a bank on the checkout page.
* Tested for 5.7.2

= 2.1.2 =
* Fixed an issue where the plug-in wouldnt load properly on Windows servers.
* Tested the plugin on the latest version of Wordpress and WooCommerce.

= 2.1.1 =
* Small translation updates
* Merging of major patches

= 2.0.10 =
* Payment status updates have changed, order now also updates when customer returns.

= 2.0.9 =
* Order notes weren't shown when an order wasnt paid.
This happens when a payment was Cancelled or Expired.

= 2.0.8 =
* Fixed an issue with case insensitive hash comparison.
* Tested plug-in on Wordpress 5.5.3 and WooCommerce 4.7.0

= 2.0.7 =
* Deprecated functions in PHP (7.4.0) removed and replaced by default Wordpress functions.

= 2.0.6 =
* All Smart Pay payment methods are given a prefix to prevent issues with other payment methods
- Check after updating to this version please if the Payment Methods are still active, these can be automatically deactivated!

= 2.0.5 =
* Fixed PayPal not working correctly.

= 2.0.2 =
* Fix implemented for the webhook changing orders where it wasn't supposed to.
* PayPal bug fix, so that it will not overwrite the original paypal plug-in.
* Added extra translations.

= 2.0.1 =
* First release

== Upgrade Notice ==

= 2.0.2 =
* None

== Arbitrary section ==


**Features**

* Payment Methods: iDEAL, Afterpay, Bancontact, Maestro, MasterCard, Paypal, Visa en VPay
* Easy to use dashboard
* Automatic webhook messages for processing transactions/orders
* Every Payment Method is optional.
* Use Smart Pay in different languages: Dutch, English, French and German.
* Configurable title and description
* Let users choose a bank from a list or overview.

**Security**

* Uses Rabo Smart Pay SHA512 encryption method
* PCI Compliant! No creditcard data saved locally!
* SSL supported
* Signs and checks every message to and from Rabo Smart Pay
* Secure webhook supported



