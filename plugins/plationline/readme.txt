=== PlatiOnline Payments ===
Contributors: adrianpo
Tags: ecommerce, e-commerce, store, sales, sell, shop, cart, checkout, plationline, login
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 6.3.2
Requires PHP: 5.5.0
WC requires at least: 3.0.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html


== Description ==

= Overview =

Since 2002 PlatiOnline has been one of the most efficient, cost-effective and secure Integrated Online Payments Processing Platforms for online businesses for Romanian ecommerce region. Our cloud-based integrated online payment management system provides a fast, reliable and secure trading platform which offers both dedicated online management  solutions for bank payment card and alternative online payment operations as well as customized reporting for online businesses.

PlatiOnline payment for Woocommerce allows online merchants to accept Visa, Visa Electron, MasterCard and Maestro, directly on their Woocommerce store via PlatiOnline’s API. First, you need to create a PlatiOnline merchant account to receive payment through PlatiOnline, then, you need to connect your PlatiOnline merchant account with your Woocommerce store through an API keys.

PlatiOnline will charge a fee for each authorized transaction. To see specific pricing and offers please fill out the online form. PlatiOnline will wire the settled amount to the merchant’s account on a weekly bases.

= Summary of services =

PlatiOnline integrated e-commerce merchant services include:
* Online interface with full-service management and reporting platform for online sales;
* EU companies registered;
* Multicurrency accounts EUR, USD and RON;
* SEPA payments;
* 3D secure standards (MasterCard SecureCode and Verified by Visa) reliable and secure online payment processing solutions;
* CVV2/CVC2 verification;
* proprietary powerful anti fraud engine PlatiOnline Argus;
* PCI DSS Level 1 – Merchant Services Provider certification;
* SSL Extended Validation (Secure Socket Layer) security certificates;
* PayLink & PayButton Options – fast & simple payment link creation directly from our platform/ideal for easy integration of payment buttons on any website and/or for push marketing campaign messaging;
* online credit card instalments with zero interest;
* recurring card payments;
* one click login and pay card payments;
* bank transfer processing and settlement by PlatiOnline;
* offline cash collection at Romanian Post Office & Raiffeisen Bank branches;

= Summary of features =

PlatiOnline transactions features include:
* Authorization only: Payment info is sent to customer's bank to check the owner and available fund on card. The fund is kept on customer's bank account until merchants make a request to receive payment.
* Capture (Authorized transactions): Once funds are authorized, merchants can capture their money using the online interface.
* Sale – (Authorization & Capture): The same as Authorize only, but customer’s money will be transferred immediately to merchant’s bank account so they do not to wait or capture it manually.
* Void: After the payment has been authorized, merchants can directly void the payment using the online interface so it will be released and not captured.
* Refund full and partial: Merchants can refund to customers from online interface, ensuring customers will get money back.

= Highly secured payment technologies =

PlatiOnline payment system is committed to protecting customer information and to actively combat fraud. Development of the PlatiOnline payment platform started in May 2002 with the mission to provide secure and reliable payment solutions for online merchants and their customers worldwide.

Based on our extensive experience in the field of anti-fraud protection, PlatiOnline developed the ARGUS online fraud detection and prevention system, which is permanently updated and optimized using machine learning algorithms.

Our advanced anti-fraud system ARGUS use algorithms so that the risk of rejecting valid orders due to the emergence of imperfect anti-fraud protection is almost completely eliminated.

= Worry-free shopping =

PlatiOnline has the highest level of PCI DSS certification (Level 1 PCI DSS on-site). This means outsourcing processing tasks to PlatiOnline system would reduce most of the PCI DSS scope for merchants. PlatiOnline is hosting the credit card page over SSL secure connection, so the merchants have no worry about cardholder data.

= CVV2/CVC2 online verification =

To protect merchants from payments made by fake cards or invalid cards, PlatiOnline will stop payments when the CVV2/CVC2 of a card fails to pass the verification process.

= 3D secure standards =

Verified By Visa and MasterCard SecureCode offer the cardholder the ability to set up and use a personal/random password that will confirm his identity and protect his card data against fraudulent use. That's why 3D systems are security signs for merchants and issuing banks that the user of that card is also the right holder of the card.
PlatiOnline Merchant Account have 3 options:
* Optional 3D Secure: performs 3D Secure test when it is supported by bank. If 3D Secure is not supported, the card will still be charged as usual;
* Required 3D Secure: always check for 3D Secure and stop the payment if it is not supported;
* Off: disable checking for 3D Secure support.

== Installation ==

= Minimum Requirements =

* PHP version 5.5 or greater (PHP 7.4 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)
* cURL version 7.29.0 or greater
* Woocommerce 3.0.4 or greater
* SOAP extension enabled
* cURL extension enabled
* OpenSSL version 1.0.1 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of PlatiOnline, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type PlatiOnline and click Search Plugins. Once you’ve found our payment plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our payment plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Frequently Asked Questions ==
= Where can I get support for installing and configuring the plugin? =

For help, please send an email to adrian[at]plationline[dot]eu

== Changelog ==

= 6.3.2 =
* fix Woocommerce Subscriptions subscription ITSN status

= 6.3.1 =
* fix woocommerce subscriptions status after payment
* added woocommerce_order_received_verify_known_shoppers

= 6.3.0 =
* implemented additional PlatiOnline payment method
* Woocommerce HPOS support
* updated schemas url

= 6.2.12 =
* fix for plationline_email_payment_link and plationline_email_payment_link_format_string

= 6.2.11 =
* fix for plationline_email_payment_link

= 6.2.10 =
* added plationline_email_payment_link to show retry payment button in email via order note
* updated woocommerce subscriptions renewal order management

= 6.2.9 =
* added plationline_email_payment_link to show retry payment button in email

= 6.2.8 =
* mark orders paid if status is po-incasare or po-incasata

= 6.2.7 =
* added yearly recurrence

= 6.2.6 =
* Woocommerce Subscriptions payment response update custom order status
* Woocommerce Subscriptions additional verifications
* mark orders paid if status is po-autorizata
* added processing order status in plugin admin

= 6.2.5 =
* Woocommerce Subscriptions update status for subscription after onhold
* filter payment gateways for recurrence
* added admin notice if woocommerce is not active

= 6.2.4 =
* Woocommerce Subscriptions cancel subscription on Master Transaction Recurrence cancel

= 6.2.3 =
* fix ITSN for payment links

= 6.2.2 =
* fix reccurence available payment gateways filter
* set subscription end date to 2 years if not set
* update subscription next payment and end date if payment is demoted to one time payment

= 6.2.1 =
* fix subscription on-hold if payment is declined

= 6.2.0 =
* added support for Woocommerce Subscriptions

= 6.1.3 =
* added cancel recurrence option in admin

= 6.1.2 =
* get order fees and send them as discount or additional cost

= 6.1.1 =
* send processing email when status changed from authorized to processing
* update coupon usage counts on authorized status

= 6.1.0 =
* implemented recurring payments
* added Plati.Online order status history check for authorized transactions
* fixed stock reduce or increase based on PlatiOnline transaction status
* added confirmation to Plati.Online remote actions
* added expiration days for payment links
* added option to disable retry failed payment if any product is out of stock on customer order page
* relay response page can now use order-received page if empty string or custom URL using our shortcode
* prevent order status update to Pending Settle or Settled if current status is Completed
* fallback language sent to Plati.Online to English if website language not supported
* fixes to login with Plati.Online
* added settings link

= 6.0.14 =
* fix reduce stock levels ITSN
* updated Login with Plati.Online

= 6.0.13 =
* fix currency code sent to PlatiOnline for multicurrency shops

= 6.0.12 =
* fix plugin deactivation if Woocommerce not detected

= 6.0.11 =
* fix plugin deactivation if Woocommerce not detected

= 6.0.10 =
* fix customer new order email

= 6.0.9 =
* added Posta Romana payment method
* added retry payment option for declined/error orders

= 6.0.8 =
* tracking script minor glitch fix

= 6.0.7 =
* login with Plati.Online payment token
* select authorized status for order: PO Authorized/Completed
* added JS Tracking script for PTOR relay method and authorized status
* fixed some translations

= 6.0.6 =
* fixed schema validation
* fixed firstname and lastname if they are sent in 1 field

= 6.0.5 =
fixed some links

= 6.0.4 =
fix update

= 6.0.3 =
fix update

= 6.0.2 =
fix update

= 6.0.1 =
fix readme

= 6.0.0 =
6.0.0 is the initial release. Make a full site backup before installing the plugin

== Upgrade Notice ==

= 6.0.5 =
Do not upgrade without checking with PlatiOnline to see if your account is compatible with this version!

= 6.0.4 =
fix update

= 6.0.3 =
fix update

= 6.0.2 =
fix update

= 6.0.1 =
fix readme

= 6.0.0 =
Get the latest features and security improvements


== Screenshots ==
1. PlatiOnline admin setup interface
2. PlatiOnline payment method selection
3. Login with Plati.Online
