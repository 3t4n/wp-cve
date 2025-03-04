=== Tilopay ===
Contributors: Tilopay
Tags: eCommerce, e-commerce, woocommerce, central america, caribbean, FAC, BAC, payment, gateway, first atlantic commerce
Requires at least: 3.9
Tested up to: 6.3.2
Stable tag: 2.1.1
Requires PHP: 7.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Accept credit and debit cards on your Central American and Caribbean WooCommerce Store

== Description ==

Accept credit and debit cards on your Central American and Caribbean WooCommerce Store

### Important update notice:
Please before update save the below info: Integration key, API user and API password,
if you already updated and didn't save it, don't worry you can get from [Tilopay admin](https://app.tilopay.com/admin/product/)

### Features And Options:
* Multi-country
* Multi-currency
* Multi-affiliate
* Partial and Full Refunds
* Partial and Full Captures
* 3D Secure
* Kount

== Installation ==

= Requieres WooCommerce =

= Modern Way: =
1. Go to the WordPress Dashboard "Add New Plugin" section.
2. Search For "Tilopay".
3. Install, then Activate it.
4. Click on tilopay settings or go to WooCommerce settings, payments, click on tilopay.
5. Follow the [Documentation on Tilopay admin for WooCommerce](https://app.tilopay.com/admin/guide/)

= Old Way: =
1. Upload `tilopay` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on tilopay settings or go to WooCommerce settings > payments > click on tilopay.
4. Follow the [Documentation on Tilopay admin for WooCommerce](https://app.tilopay.com/admin/guide/)

== Frequently Asked Questions ==

= Where can I find support =

Send your support request to sac@tilopay.com

== Screenshots ==
1. Modal Payment
2. Settings Payment


== Changelog ==
= 2024-01-08 - version 2.1.1 =
* Fix validations.
* Others notes.
= 2023-12-22 - version 2.1.0 =
* Using $_GET instead of $_REQUEST.
* Get recurrent error response.
= 2023-11-27 - version 2.0.9 =
* Validate if SDK encrypt data card.
* If not encrypt force to redirect Tilopay payment form.
= 2023-11-22 - version 2.0.8 =
* Others styles.
= 2023-11-06 - version 2.0.7 =
* Validate if SDK encrypt data card.
* Redirect to check if hash validation are no same from Tilopay.
* Add default title.
* Use own nonce validations checkout.
* Making compatibility with WOO HPOS or WP posts storage (legacy).
* Spinner on innit call.
= 2023-08-21 - version 2.0.6
* Yoda validations.
* WOO standards.
* Fix $this->get_options('key') function is deprecated, now using $this->settings['key'].
= 2023-07-20 - version 2.0.5 =
* KOUNT implemented for commerce that have it active.
* Validate modifications response, to update order notes.
* Add param hashVersion to request V2.
* Check if notes have already been added.
* Include Tilopay order id at notes.
* Tilopay SDk (V2) integration that only using JavasCript not Jquery.
* Fix custome spinner and remove js overlay.
* Fix endpoints path.
* Checkbox save card required for subscription products before create order.
* Checkbox save card auto check if have product subscription.
* Subscriptions payment is not allowed in test environment.
* Others styles.
= 2023-01-30 - version 2.0.3 =
* Fix to load SDK only for native payment WOO.
* Additional details in the payload to respond based on the website language WP.
* It includes more details to make the order hash validation.
* Some extra CSS.
= 2022-12-16 - version 2.0.2 =
* Fix to avoid wc_add_notice from admin.
* Fix default card IMG.
= 2022-12-06 - version 2.0.1 =
* Load Tilopay front scripts only at checkout and pay_for_order pages.
* Set priority 11 for load_tilopay_front_scripts to fix:
* -JS incompatibility conflicts with "WC Provincia-Canton-Distrito plugin".
* -SDK jQuery incompatibility conflicts.
= 2022-11-08 - version 2.0.0 =
* WOO Direct Gateway or redirect way.
* Implement the Tilopay SDK on chackout page.
* Show the card saved by user email.
* Allow payment with SINPE Movíl.
* Adding spinner js cdn.
* New error handler.
= 2022-08-24 - version 1.3.0 =
* If not capture set payment pending.
* Fix redirec when save url payment form.
* Allow woocommerce to sort Tilopay position.
* Logo on one row with text.
* Fix front css and remove option to customize icon.
= 2022-07-26 - version 1.2.9 =
* Add spanish translation files.
= 2022-07-07 - version 1.2.8 =
* New icons and controll what icon to show it.
* Grid and flex system.
* Webhook to update orders status.
= 2022-05-05 - version 1.2.7 =
* Fixing WOO stats conflict.
= 2022-05-05 - version 1.2.6 =
* Show message transaction is declined.
= 2022-02-21 - version 1.2.5 =
* Fix log: data was called incorrectly.
* Fix log: the get_refund_amount function is deprecated.
* Remove on init WCTilopay validations.
* Testing WooCommerce Version 6.2.0.
* Adding hash to provee is return from Tilopay server.
* Show message if user cancel the payment process.
* Show message if invalid order confirmation.
* Add order note if order con is invalid.
* Fix translation.
* Remove auto check to set defualt payment.
* Fixing the order status for recurring payments, according the payment status.
* Remove modal payment.
* For authorization and partial capture mode only show: pending payment and on hold.
= 2022-01-28 - version 1.2.4 =
* Remove URL validation.
= 2022-01-28 - version 1.2.3 =
* Make URL array to validate.
* Add global env url.
= 2022-01-21 - version 1.2.2 =
* logo update.
= 2022-01-21 - version 1.2.1 =
* fixing error space.
= 2022-01-20 - version 1.1.9 =
* fixing error space.
= 2022-01-20 - version 1.1.8 =
* fixing error space.
= 2022-01-20 - version 1.1.7 =
* fixing error space.
= 2022-01-05 - version 1.1.6 =
* adding sweet alert confirm message.
* And others changes.
= 2021-12-15 - version 1.1.5 =
* Fixing responsive CSS.
* And others changes.
= 2021-11-26 - version 1.1.4 =
* Adding options to remove icon to customize it.
* Validate if integration key are valid.
* Fixing responsive CSS.
* And others changes.
= 2021-11-07 - version 1.1.3 =
* Traslating TILOPAY, EN and ES.
* Customize payment gateway icon and title.
* And others changes.
= 2021-11-01 - version 1.1.2 =
* Using namespace.
* Updating settings.
= 2021-10-24 - version 1.1.1 =
* Adding setting link.
* Using select instead checkbox.
* Updating some validations.
* Adding text domain and security updating.
= 2021-10-21 - version 1.0.0 =
*  Initial release
