=== Helcim Commerce for WooCommerce ===
Contributors: Helcim
Tags: helcim, helcim commerce, woocommerce, woocommerce payments, woocommerce payment gateway, payment gateway, shopping cart, wordpress payment gateway, woocommerce shopping cart, wordpress shopping cart, payment gateway for woocommerce, payments, credit cards, accept credit cards, recurring billing, woocommerce gateway, woocommerce payment plugin, woocommerce credit cards, accept credit cards woocommerce, credit card gateway woocommerce, accept credit cards on woocommerce, process payments, process credit cards, accept visa, accept mastercard,checkout, accept payments, merchant account, merchant services
Requires at least: 4.7.3
Tested up to: 6.4.2
PHP Version: 7.4 or higher

== Description ==

**[Helcim](https://www.helcim.com "Credit Card Processing") Payment Module for WooCommerce**
Start accepting credit card payments online. This plugin integrates Helcim with your WooCommerce store. Thanks to Helcim.js, customers never leave your website during the checkout process.

**LEGACY MERCHANTS - IMPORTANT**
Please note that this plugin is the new Helcim platform. Merchants using our legacy Helcim Gateway/Virtual Terminal should be using the [Payment Gateway for WooCommerce - Helcim plugin](https://wordpress.org/plugins/payment-gateway-for-woocommerce-by-helcim/) instead.

**WHY HELCIM:**
       - Enjoy affordable interchange plus pricing in your WooCommerce store.
       - Sign up is fast and easy for businesses in Canada or the USA.
       - Accept Visa, Mastercard, American Express, Unionpay, Discover Network and more.

**REQUIREMENTS:**
       - WooCommerce 2.6.14 to 8.5.1
       - [Helcim account](https://hub.helcim.com/signup/register/)


== Installation ==

1. For detailed instructions, please visit: https://support.helcim.com/article/helcim-commerce-new-ui-integrations-woocommerce-integration-woocommerce/
2. Install the plugin, or download it and extract it to: /wp-contents/plugins/ directory
3. Configure the module in WooCommerce->Settings->Check Out then click 'Helcim'
4. Click the Enable/Disable box to enable this gateway
5. Select either "Test Mode" or leave it empty for live transaction.
6. Enter the title to display on the payment selection portion of the checkout page (required)
7. Select the payment integration method you wish to use (either Helcim.js or Direct Integration)
8. If using Helcim.js, enter the Helcim.js token
9. If using Direct Integration, enter the Helcim Commerce Account ID, API Token, and Terminal ID
10. If using Direct Integration, choose the transaction type such as "Purchase" or "Pre-Auth"
11. Enter the description your customers will see on the checkout page for this payment option (required field or WooCommerce will not work)
12. Choose whether you would like to display the Helcim logo on the checkout page
13. Process a test transaction to ensure that the plugin is working before switching to live production.



==  Frequently Asked Questions ==

**How can I get a Helcim account?**
------------------------------------------------
Please visit the [Helcim Website](https://www.helcim.com/) for information on signing up for a Helcim account.


**Should I use Helcim.js or Direct Integration?**
------------------------------------------------
Helcim.js is recommended as it greatly reduces your security and PCI compliance scope. The credit card tokenization takes place between your client's web browser and Helcim's servers, removing your server from having to touch full credit card information. However, Helcim.js does not work with some older browsers and can conflict with other plugins installed on your WordPress website. The direct integration providers a more straight-forward payment integration, but brings your server into the full scope of PCI compliance. Merchants should choose their integration mode based on their security practices.


**Where can I find my Helcim.js configuration token?**
------------------------------------------------
Login to Helcim Commerce, click on "Payment Pages" -> "Helcim.js" and create a new configuration.


**Where can I find my API Token?**
------------------------------------------------
Login to Helcim, click on "Settings" -> "API Access" and create a new API access token.


== Screenshots ==

1. Helcim Commerce for WooCommerce - Payment gateway list page
2. Helcim Commerce for WooCommerce - Configuration page
3. Helcim Commerce for WooCommerce - Checkout page example #1
4. Helcim Commerce for WooCommerce - Checkout page example #2


== Changelog ==

= 4.0.3 =
* Bug fix regarding empty phone number
* Tested upto WordPress 6.4.2
* Tested upto woocommerce 8.5.1

= 4.0.2 =
* Bug fix regarding countries
* Tested upto WordPress 6.4.1
* Tested upto woocommerce 8.2.2

= 4.0.1 =
* Optimized transaction processing
* Bug fix regarding taxes
* Tested upto WordPress 6.3
* Tested upto woocommerce v8.0

= 4.0.0 =
* Optimized transaction processing
* Tested upto WordPress 6.2
* Tested upto woocommerce v7.9

= 3.0.8 =
* Tested upto WordPress 6.2
* Tested upto woocommerce v7.8

= 3.0.7 =
* Tested upto WordPress 6.1
* Tested upto woocommerce v6.4

= 3.0.6 =
* Tested upto WordPress 6.1
* Tested upto woocommerce v6.4

= 3.0.5 =
* Allow customers to choose card expiry year that is 5 years from now
* Tested upto WordPress 5.8
* Tested upto woocommerce v6.3

= 3.0.4 =
* Allow customers to choose card expiry year that is 5 years from now
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 3.0.3 =
* Fix UI bug with expiry dates
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 3.0.2 =
* Fix Refunds
* Fix Approval Code not showing
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 3.0.1 =
* UI Fix
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 3.0.0 =
* Bug Fix - Duplicate Transaction when checkout fails
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 2.0.6 =
* Bug Fix - Void/Reverse Approved Transactions when checkout validation fails
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 2.0.5 =
* Bug Fix - Void/Reverse Approved Transactions when checkout fails
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.7

= 2.0.4 =
* Bug Fix - transactions expiring after 1min from approval time
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.6

= 2.0.3 =
* Added Refund Capability
* bug fixes
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.6

= 2.0.2 =
* Added Refund Capability
* bug fixes
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.6

= 2.0.1 =
* Added Refund Capability
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.6

= 2.0.0 =
* Added Refund Capability
* Tested upto WordPress 5.8
* Tested upto woocommerce v5.6

= 1.2.8 =
* Tested upto WordPress 5.8

= 1.2.7 =
* Optimized Plugin
* Added AVS fields when paying for Orders
* Tested upto woocommerce v5.4

= 1.2.6 =
* Optimized Plugin
* Added AVS fields when paying for Orders
* Tested upto woocommerce v5.4

= 1.2.5 =
* Optimized Plugin
* Added AVS fields when paying for Orders
* Tested upto woocommerce v5.3

= 1.2.4 =
* Optimized Plugin
* Updated plugin to work with Create an Account on Checkout
* Tested upto woocommerce v5.1.0

= 1.2.3 =
* Optimized Plugin
* Updated plugin to work with Create an Account on Checkout
* Tested upto woocommerce v5.1.0

= 1.2.2 =
* Optimized Plugin
* Updated plugin to work with Create an Account on Checkout
* Tested upto woocommerce v5.1.0

= 1.2.1 =
* Optimized Plugin
* Updated plugin to work with Create an Account on Checkout
* Tested upto woocommerce v5.0.0

= 1.2.0 =
* Optimized Plugin
* Updated plugin to work with Create an Account on Checkout
* Tested upto woocommerce v5.0.0

= 1.1.10 =
* Optimized Plugin
* Tested upto wordpress v5.6

= 1.1.9 =
* Optimized Plugin
* Register and Checkout feature is now available
* Google reCAPTCHA v3 now available for Helcim.js payment method

= 1.1.8 =
* Optimized Plugin
* Register and Checkout feature is now available
* Google reCAPTCHA v3 now available for Helcim.js payment method

= 1.1.7 =
* Optimized Plugin
* Register and Checkout feature is now available
* Google reCAPTCHA v3 now available for Helcim.js payment method

= 1.1.6 =
* Optimized Plugin
* Register and Checkout feature is now available

= 1.1.5 =
* Optimized Plugin
* Bug fix for ip address

= 1.1.4 =
* Optimized Plugin
* Bug fix for invalid expiry error

= 1.1.3 =
* Optimized Plugin
* Updated for latest version of woocommerce (v3.6.0)

= 1.1.2 =
* Optimized Plugin

= 1.1.1 =
* Fixed Shipping Info Not Saving In Commerce
* Added Payment Method(Direct Integration)
* Helcim.js Method will only tokenize your card

= 1.1.0 =
* Fixed Shipping Info Not Saving In Commerce
* Added Payment Method(Direct Integration)
* Helcim.js Method will only tokenize your card

= 1.0.5 =
* Added Test Field in Plugin Configuration
* Added Transaction Logs
* Fixed Order Notes not showing in Commerce

= 1.0.4 =
* Fixed Error When Country Dropdown is changed to Input Field.

= 1.0.3 =
* Fixed button to toggle between payment gateways.

= 1.0.2 =
* Fixed bug - can now be used with other payment gateways

= 1.0.1 =
* Fixed title
* Fixed plugin naming to avoid clashes with old Helcim plugin
* Fixed bug - can now be used with other payment gateways
* Feature - can be used for previously generated order

= 1.0.0 =
* Initial release.
