=== 2Checkout Payment Gateway for WooCommerce ===
Contributors: nmedia
Tags: payment gateway, 2co payment gateway, 2checkout woocommerce payment, woocommerce payment gateway
Donate link: http://www.najeebmedia.com/donate
Requires at least: 3.5
Tested up to: 6.4.2
Stable tag: 6.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It's a WooCommerce extension allow clients make payment with 2Checkout.

== Description ==
The 2Checkout Payment Gateway for WooCommerce is a plugin that allows e-commerce businesses using the WooCommerce platform to accept credit card and PayPal payments on their website without redirecting to external pages. This plugin integrates 2Checkout payment gateway with your woocommerce store, allowing your customers to make payments using credit card and PayPal, in a secure and seamless way. One of the most notable features of this plugin is the ConvertPlus feature, which provides a new and secure way to accept payments on the website without leaving the website.

= Please Note: =
From Version 6+ make sure you set Header Redirect to:
https://YOUR_SITE/wc-api/twoco_return/

= Demo (ConvertPlus) =
[Visit here and Checkout any Product](https://ppom.nmediahosting.com)

= Features =
* Itemized Checkout - will display each item with SKU/ID
* Pass all billing and shipping data to 2CO purchase page
* Enable/Disable Test Mode

= 2Checkout PRO Version 10.1 Released September 2020 =
* ConvertPlus PopUp
* Standard Checkout
* Credit Card Form

= 2Checkout Pro Features =
* Credit Card Form on Site Payment
* PayPal Direct Checkout
* Skipp Billing and Shipping Section
* Currency Conversion for Non-supported currencies with live rates
* [More detail About Pro Versoin](https://najeebmedia.com/wordpress-plugin/woocommerce-2checkout-payment-gateway-with-inline-support/)

= Getting Started with ConvertPlus =
[youtube https://www.youtube.com/watch?v=oY6om5WqNKU]

= How to Setup Account = 
[Step by step 2Checkout Account Setup Guide](https://najeebmedia.com/2018/08/08/woocommerce-2checkout-payment-gateway-setup-guide/)

== Installation ==
1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. After activation, you can set options from `WooCommerce -> Settings -> Checkout` menu

== Frequently Asked Questions ==
= How to setup my 2Checkout Account? =
[Step by step 2Checkout Account Setup Guide](https://najeebmedia.com/2018/08/08/woocommerce-2checkout-payment-gateway-setup-guide/)

= How to found my Seller/Account Number? =
[Step by step 2Checkout Account Setup Guide](https://najeebmedia.com/2018/08/08/woocommerce-2checkout-payment-gateway-setup-guide/)

= How set page redirect =
[Step by step 2Checkout Account Setup Guide](https://najeebmedia.com/2018/08/08/woocommerce-2checkout-payment-gateway-setup-guide/)

= I am new to 2Checkout, can I have some quick overview? =
[Step by step 2Checkout Account Setup Guide](https://najeebmedia.com/2018/08/08/woocommerce-2checkout-payment-gateway-setup-guide/)


== Screenshots ==
1. 2Checkout Settings
2. WooCommerce Checkout Page
3. Itemized checkout
4. Accepting Credit Cart on Site (PRO)
4. Skipping Billing and Shipping Section on 2Checkout (PRO)

== Changelog ==
= 6.2 January 19, 2023 =
* Some branding issues removed
* Code optimized
* Latest WP/WooCommerce version compatible
= 6.1 September 5, 20 =
* Bug fixed: [Pending payment issue fixed](https://wordpress.org/support/topic/repeat-issue-of-pending-payment/)
= 6.0 September 1, 20 =
* Feature: Demo/Sandbox updated to test transactions
* Feature: Optimized code to process payment after transactions
= 5.0 April 8, 20 =
* Feature: More advance approach used to process order after successful payment
= 4.1 March 11, 20 =
* Bug fixed: [Payment status issue fixed with credit card](https://clients.najeebmedia.com/forums/topic/2checkout-payment-processing-issue/)
* Tweak: WC latest version compatible check
= 3.4 September 4, 2019 =
* Bug fixed: Undefined get_rates_from_yahoo removed
= 3.3 August 27, 2019 =
* Bug fixed: [Warning removed](https://wordpress.org/support/topic/error-in-new-version-6/)
= 3.2 May 19, 2019 =
* Bug fixed: Deprecated functions/methods removed for order
= 3.1 May 13, 2019 =
* Bug fixed: [Order Status auto update when payment passed](https://wordpress.org/support/topic/order-pending-still-an-issue/)
* Bug fixed: [Download Link not sent in email, now fixed](https://wordpress.org/support/topic/digital-purchases-no-download-link/)
= 3.0 April 24, 2019 =
* Bug fixed: [Hash Mishmatch issue fixed](https://wordpress.org/support/topic/check-your-secret-word/#post-11461416)
= 2.3 September 27, 2018 =
* Bug fixed: [Shipping issue fixed](https://wordpress.org/support/topic/no-available-shipping-methods-5/)
* Bug fixed: Sometime Hash Mismatch, it's also fixed
= 2.2 August, 2018 =
* Feature: WooCommerce latest version compatible
* Feature: Add 2Checkout Order Number in Order Notes.
= 2.1 April 3, 2018 =
Bug fixed: [Zip/Postal Code and Country were not updated on checkout, now it's fixed](https://wordpress.org/support/topic/zip-code-3/)
= 2.0 =
* WooCommerce 3.0 Compatible
* Itemized Billing (Products, Tax, Shipping, Fees)
= 1.7 =
* Currency code passed to checkout
= 1.6 =
* BUG Fixed: Variable products prices were not correct, now it's fixed
* Set Product as Tangible or Intangible
* Sending Product ID to Cart Data
= 1.5 =
* fixed return url issue
= 1.4 =
* get_shipping() function is replaced with get_total_shipping
= 1.3 =
* Hash Mismatch issue fixed when client redirected to shop from 2checkout payment
= 1.2 =
* Now Secret word support added in plugin
* clear the cart once order is verified.
= 1.1 =
* fix callback url issue when payment is made.
* some labels are updated.
= 1.0 =
* It's first release

== Upgrade Notice ==
Nothing