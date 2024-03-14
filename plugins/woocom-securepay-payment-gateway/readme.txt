=== WooCom SecurePay Payment Gateway ===
Contributors:hemthapa
Donate link: https://hemthapa.com/product/woocommerce-securepay-payment-gateway-plugin/?ref=wp
Tags: woocommerce, payment, gateway, securepay, checkout, securepay payment, woocommerce securepay
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 1.8
Requires PHP: 7.1
WC tested up to: 8.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily accept credit card payment from SecurePay Gateway (Australia Post) in your WooCommerce store

== Description ==

This plugin allows WooCommerce store owners to process credit card payment via [SecurePay (Australia)](https://www.securepay.com.au/) payment gateway.

**FraudGuard Setting and Pre Authorization Payments**
This plugin doesn't support FraudGuard settings. If FraudGuard setting is enabled on your SecurePay account to minimize the fraudulent transactions or you need an option for Pre Authorization Payments, please [use this plugin](https://hemthapa.com/product/woocommerce-securepay-payment-gateway-plugin/?ref=wp) instead.

This plugin is tested up to WooCommerce Version 8.0.2

== Installation ==

1. Install the plugin directly from WP Plugin manager or download the zip file `woocom-securepay-payment-gateway.zip`, unzip it and upload all of its contents to the `/wp-content/plugins/` directory of your website.

2. Activate the plugin through the 'Plugins' menu in WordPress.

3. Configure the Payment options on WooCommerce > Settings > Payments > SecurePay payment gateway.

4. Test the payment gateway with the test account to confirm if the payment gateway is working properly.

5. If a transaction with test account succeeds, enter the live SecurePay account details and create a test order. Remember to uncheck 'Test environment' once live SecurePay account details are entered.

6. All good, you've successfully integrated SecurePay payment gateway on your online store.

7. If FraudGuard setting is enabled on your SecurePay account or you need an option of Pre Authorization Payments, please [use this plugin](https://hemthapa.com/product/woocommerce-securepay-payment-gateway-plugin/?ref=wp) instead.


== Frequently Asked Questions ==

= Prerequisite?  =

* You must have a valid [SecurePay (Australia)](https://www.securepay.com.au/) account to use this plugin.
* Your WooCommerce store should have a valid SSL certificate to secure the overall payment process.
= WooCommerce Version Support?==
This plugin is tested up to WooCommerce version 8.0.2
= Where can I find API Transaction Password?
API Transaction Password and your SecurePay user account password are different.
You can find the API transaction password from SecurePay portal under Manage > API Transaction Password menu.
= I've got 'Invalid Merchant ID' error on Live mode =
Possible reasons:

* Please make sure 7 characters long 'Transaction Merchant ID' (Eg: ABC0021) is used. If you have 3 characters long Merchant ID (Eg: ABC), please contact SecurePay to get the Transaction Merchant ID.
* once the LIVE merchant ID is entered please make sure  'Test environment' checkbox is unticked.
* Please make sure valid SSL certificate is installed on your website and your server supports TLS1.2.
* Please make sure you've downloaded and installed this (THIS :) ) plugin from WordPress official plugin manager and not from other websites/developers.
* Please try disabling all unnecessary plugins and switch to a default WordPress theme and simulate the live transaction.

If the issue persists, please feel free to contact me.
= Why my payments keep getting declined on the test environment? =
In the test environment, if the payment amount ends in 00, 08, 11, or 77 (e.g. $1.08, $1.00)  the transaction will be approved otherwise it will be declined. You can use this test environment feature to simulate approved and declined transactions.
= Test Credit Card (for test environment only) =
In the test environment, please use the following credit card to process test transactions.

Card Number: 4444 3333 2222 1111
Expiry date: Any future dates
CVV: any 3 digit number
= How do I change the payment icon on the checkout page? =
If you need to use different graphics/logo on the checkout page, please replace the default *securepay_logo.png* image located on  */wp-content/plugins/woocom-securepay-payment-gateway* directory.
= I've configured the plugin correctly but still can't complete the transaction? =
Sometimes, third party plugins or theme used on your website might cause the issue with this plugin. If the test transaction is failed, please try to disable unnecessary plugins and change the website theme to default WordPress theme and place an order. If the issue persists, please feel free to [contact the developer](https://hemthapa.com/?ref=securepay).


== Screenshots ==
1. plugin settings dialog


== Upgrade Notice ==
Support for WooCommerce version 8.0.2 is added.