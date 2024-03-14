=== Gestpay for WooCommerce ===
Contributors: easynolo
Tags: woocommerce, payment gateway, payment, credit card, gestpay, gestpay starter, gestpay pro, gestpay professional, banca sella, sella.it, easynolo, axerve, iframe, direct payment gateway
Requires at least: 4.0.1
Tested up to: 6.4.3
Stable tag: 20240307
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0
WC tested up to: 8.6.1

Axerve Free Plugin for Woocommerce extends WooCommerce providing the payment gateway Axerve.

== Description ==

Axerve Free Plugin for Woocommerce allows you to use [Axerve](https://www.axerve.com/ "Axerve Website") on your WooCommerce-powered website.

There are four operational modes in this plugin, which depends on Axerve version you are using:

* Axerve Starter
* Axerve Professional
* Axerve Professional On Site
* Axerve Professional iFrame

[Click here to read the full usage documentation on Axerve](https://docs.gestpay.it/soap/plugins/woocommerce/ "Axerve for WooCommerce - Usage Documentation").

== Actions and filters list ==

Here is a list of filters and actions used in this plugin:

= Actions =

* gestpay_before_processing_order
* gestpay_after_order_completed
* gestpay_after_order_failed
* gestpay_after_order_pending
* gestpay_before_order_settle
* gestpay_order_settle_success
* gestpay_order_settle_fail
* gestpay_before_order_refund
* gestpay_order_refund_success
* gestpay_order_refund_fail
* gestpay_before_order_delete
* gestpay_order_delete_success
* gestpay_order_delete_fail
* gestpay_after_s2s_order_failed
* gestpay_on_renewal_payment_failure
* gestpay_my_cards_template_before_table
* gestpay_my_cards_template_after_table

= Filters =

* gestpay_gateway_parameters
* gestpay_encrypt_parameters
* gestpay_settings_tab
* gestpay_my_cards_template
* gestpay_cvv_fancybox
* gestpay_gateway_cards_images
* gestpay_alter_order_id -> this can be used to add, for example, a prefix to the order ID
* gestpay_revert_order_id -> this must be used to revert back the order ID changed with the `gestpay_alter_order_id` filter
* gestpay_s2s_validate_payment_fields
* gestpay_s2s_payment_fields_error_strings


== Installation ==

1. Ensure you have the WooCommerce 3+ plugin installed
2. Search "Gestpay for WooCommerce" or upload and install the zip file, in the same way you'd install any other plugin.
3. Read the [usage documentation on Axerve](https://docs.gestpay.it/soap/plugins/woocommerce/ "Gestpay for WooCommerce - Usage Documentation").

== Changelog ==

= 20240307 =
* Security: Added nonce check to front end card manager
* Improvement: Added Paypal seller protection
* Checks: Verified compatibility with Wordpress 6.1.0, WooCommerce 7.1.0


= 20221130 =
* Improvement: Added Paypal Buy Now Pay Later button
* Improvement: Added Paypal seller protection
* Checks: Verified compatibility with Wordpress 6.1.0, WooCommerce 7.1.0

= 20220722 =
* Improvement: Added RBA fields
* Checks: Verified compatibility with Wordpress 6.0.0, WooCommerce 6.7.0

= 20220228 =
* Improvement: Fixed url for mybank payment system
* Checks: Verified compatibility with Wordpress 5.9.0, WooCommerce 6.2.1


= 20211031 =
* Improvement: Added BancomatPay payment system.
* Checks: Verified compatibility with Wordpress 5.8.1, WooCommerce 5.8.0

= 20210713 =
* Fix: Fix available_payment_gateways array warning
* Fix: wcs_order_contains_renewal missing function error
* Checks: Verified compatibility with Wordpress 5.7.2, WooCommerce 5.5.0

= 20210129 =
* Fix: iFrame Samesite Cookie
* Fix: SOAP client catch and log
* Fix: 3DS billing and shipping address up to 50 chars
* Checks: Verified compatibility with Wordpress 5.6, WooCommerce 4.9.2

= 20201212 =
* Fix: iFrame Samesite Cookie
* Fix: Link to documentation
* Fix: Update status to refunded only if is a full refund
* Fix: Added changes on how handle Tokens

= 20201018 =
* Improvement: added management of response cases XX (used with MyBank) and added the action gestpay_after_order_pending
* Improvement: Changed catch of Soap Fault Error.
* Improvement: removed "\r" from the CustomInfo parameter.
* Improvement: added actions gestpay_my_cards_template_before_table and gestpay_my_cards_template_after_table to add text before/after the list of saved card-tokens (s2s version)

= 20200811 =
* Fix: 3DS2 need authTimestamp to YYYYMMDDHHMM; removed ua informations from AuthData.
* Fix on payment method change for Subscriptions: allow to correctly change the associated token.
* Improvement: Added a second attempt if an error occurs when getting the SOAP client.

= 20200719 =
* Checks: Verified compatibility with Wordpress 5.4, WooCommerce 4.2-4.3 and WooCommerce Subscriptions 3.0.4
* New: Added ability to change the completed order status when using MOTO with separation and automatically handle the actions to be performed when the state of an order is manually changed.
* Fix: Prevent Fatal Error Call to undefined function wcs_is_subscription() when not using WooCommerce Subscriptions.
* Fix: Fixed ability to change the Gestpay multi-payments order: is_s2s must be true only when paymentType is `CREDITCARD`.
* Fix: the status of an active subscription must no change to failed if the cardholder abandons the card change.
* Improvement: Added more logging when adding 0_order_amount_fix.
* Improvement: Added action `gestpay_after_s2s_order_failed` to let developers add additional code.
* Improvement: Added validation for the S2S payment fields and a realated filters `gestpay_s2s_validate_payment_fields` and `gestpay_s2s_payment_fields_error_strings`

= 20191022 =
* Fixed return URL and message when the change of the tokenized card, related to a subscription, is failed.

= 20191012 =
* New: filters `gestpay_alter_order_id` and `gestpay_revert_order_id`
* Improvement for WooCommerce Subscriptions compatibility: added ability to change the tokenized card for an active Subscriptions: the customer will be able to change the card that will be used to pay the next recurring payment.
* Improvement for developers: tokenized cards will also have the expiry date stored on the post meta GESTPAY_META_TOKEN of the order_id.

= 20190909 =
* Feature PayPal - Added ability to retrieve a Token for Subscription payments (with external plugin WooCommerce Subscriptions).
* Added 3DS 2.0 support. [Read more](https://docs.gestpay.it/soap/3ds-2.0/how-change-integration/ "3DS 2.0")
* Fix WooCommerce 3.7.0 compatibility for the configuration page.

= 20190701 =
* Subscriptions - Fix token saving on the parent of a renewal order after is failed and is manually paid.

= 20190515 =
* Subscriptions - Added ability, for S2S and iFrame accounts, to use a second account with 3DS disabled. In this way it will be possible to use the main account with 3DS activated for the first payment and the second account (with 3DS disabled) for recurring payments.
* Added MyBank small icon in the card list
* Added filter `gestpay_gateway_cards_images`
* Cleaned up old code for WC < 3.x (which is not supported anymore)
* Checks - Verified compatibility with WooCommerce 3.6.2 and Wordpress 5.2

= 20190411 =
* Fix S2S - Show the input form for the card when tokens are disabled.
* Fix MyBank - When using MyBank on mobile devices, the bank/institute list must be shown and the Customer must select one of them before proceeding.
* Feature MyBank - Added MyBank text/logos/style to be compliant with the MyBank Style Guide requirements.
* Feature MyBank - Added an option for MyBank to be able to force also Customers on desktop devices to select a bank/institute from the website. Removed ability to change title and description for MyBank: these must be statically assigned.
* Cleaned up some of the old code for WC < 3.x (which is not supported anymore); payment types classes refactoring.

= 20190320 =
* Fix - flush rewrite rules causes issues with WPML: just flush only once, after plugin activation.
* Fix - On S2S if the customer select a default card, the new card form must be hidden.
* Fix - Changed costant name to force sending email to WC_GATEWAY_GESTPAY_FORCE_SEND_EMAIL.
* Fix - On S2S use the parent order id to handle failed recurring payments.
* Checks - Verified compatibility with WooCommerce 3.5.7 and Wordpress 5.1.1

= 20181129 =
* Feature - Added new available currencies
* Fix - Some currencies (JPY, PKR, IDR, KRW) does not allow decimals in the amount; VND allow just one decimal.
* Fix - On S2S (On-Site version) added Buyer Name field.
* Fix - Allow Google Analytics tracking (utm_nooverride)
* Checks - Verified compatibility with WooCommerce 3.5.1

= 20180927 =
* Feature - Added apiKey authentication method option
* Checks - Verified compatibility with WooCommerce 3.4.5

= 20180809 =
* Fix recurring payments with iFrame/Tokenization
* Checks - Verified compatibility with Wordpress 4.9.8, WooCommerce 3.4.4 and WooCommerce Subscriptions 2.3.3

= 20180606 =
* Fix - The JS on configuration page must distinguish between Pro and On-Site/iFrame options.
* Checks - Verified compatibility with Wordpress 4.9.6 and WooCommerce 3.4.2

= 20180516 =
* Fix - HTML slashes must be escaped inside JS.
* Fix - No need to instantiate the SOAP Client of order actions in the constructor.
* Feature - Added the ability to temporarily use unsecure Crypt URL when TLS 1.2 is not available.
* Feature - Added an option to enable On-Site merchants to set the withAuth parameter to "N".

= 20180426 =
* Fix typo in the JS of the TLS check

= 20180412 =
* Feature - Added compatibility with WC Sequential Order Numbers Pro.
* Security - Added TLS 1.2 checks for redirect and iFrame versions: prevent old and unsecure browsers to proceed with the payment.
* Fix - Show an error if required fields are not filled on the On-Site version (S2S).
* Fix - Prevent Fatal Errors if WooCommerce is inactive.
* Fix - Save transaction key on phase I
* Checks - Verified compatibility with Wordpress 4.9.4/.5 and WooCommerce 3.3.4/.5.

= 20180108 =
* Fix - Consel Merchant Pro parameter is now changed to be an input box on which the merchant can add the custom code given by Consel.

= 20171217 =
* Feature - Added help text near the CVV field (for it/en languages) for "on site" and iframe versions.
* Feature - Added Consel Customer Info parameter.

= 20171125 =
* Fix - Updated test URLs from testecomm.sella.it to sandbox.gestpay.net
* Checks - Verified compatibility with Wordpress 4.9 and WooCommerce 3.2.5

= 20170920 =
* Fix Custom Info parameter.

= 20170602 =
* Fix error "-1" that happens when using the S2S notify URL.
* Verified compatibility with WooCommerce Subscriptions 2.2.7

= 20170508 =
* Fix - Moved ini_set( 'serialize_precision', 2 ) to the Helper, to avoid rounding conflicts.
* Checks - Verified compatibility with WooCommerce v 3.0.5

= 20170502 =
* Fix - Verify if class WC_Subscriptions_Cart exists before disabling extra Gestpay payment types.

= 20170427 =
* Checks - Verified compatibility with WooCommerce version 2.6.14 and 3.0.4
* Checks - Verified compatibility with WooCommerce Subscriptions version 2.1.4 and 2.2.5
* Feature - Added support for Tokenization+Authorization (here called "On-Site") and iFrame services.
* Feature - Added support for 3D Secure and not 3D Secure payments.
* Feature - Added endpoint to handle cardholder's cards/tokens for the "On-Site" version.
* Feature - Added Refund/Settle/Delete S2S actions for transactions.
* Feature - Added more filters and actions.
* Feature - Disable extra Gestpay payment methods when paying a subscription.
* Fix - Correctly loading of plugin localization.
* Fix - Show/Hide Pro options on the configuration page.
* Fix - Removed extra payment "upmobile", which is not used anymore.

= 20170224 =
* First public release.
