=== Splitit ===
Contributors: splitit
Tags: ecommerce, e-commerce, commerce, wordpress ecommerce, sales, sell, shop, shopping, checkout, payment, splitit
Requires at least: 5.6
Tested up to: 6.4.3
WC requires at least: 5.5
WC tested up to: 8.4.0
Stable tag: 4.1.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Enables offering shoppers monthly payments on their existing Visa and Master Card credit cards in WooCommerce - Level 1 PCI DSS compliant

== Description ==

Splitit – Interest-Free Monthly Payments plugin for WooCommerce<br/>
<br/>
<a href="https://www.splitit.com/">Splitit</a> is a payment method solution enabling customers to pay for purchases with an existing debit or credit card by splitting the cost into interest and fee free monthly payments, without additional registrations or applications.<br/>
Splitit enables merchants to offer their customers an easy way to pay for purchases in monthly instalments with instant approval, decreasing cart abandonment rates and increasing revenue.<br/>
Serving many of Internet Retailer’s top 500 merchants, Splitit’s global footprint extends to hundreds of merchants in countries around the world. Headquartered in New York, Splitit has an R&D center in Israel and offices in London and Australia.<br/>
<br/>
Start offering your customers **interest-free installment payments** on their existing credit cards today!<br>
The Splitit  WooCommerce plugin lets your customers pay for your goods and services via interest-free monthly installments on the Visa and Master Card credit cards they already have in their wallets.
No credit checks, applications or new credit cards.
Works as long as your customer has available credit on their card equal to the amount of the purchase.
Interest-free installments appear on their regular credit card statement, under your store name.
Your customers continue to enjoy the benefits of their credit cards such as mileage, cash back, and points with no additional billing cycle to manage.
Interest-free installment payments make great business sense!<br><br>
Ecommerce merchants that offer Splitit to their customers enjoy:<br>
-Increased sales<br>
-Higher average tickets<br>
-Increased conversion rates<br>
-A better alternative to discounts and promotions<br>
-Stronger brand value<br>
<br>
Some more good stuff to know about the Splitit plugin for WooCommerce:<br>
-Installment transactions are validated and guaranteed by the credit card issuer<br>
-Merchants pay a small processing fee and can choose to receive the payments by installment<br>
Or you can receive the total amount upfront after paying a low discount fee<br>
-We are pre-integrated with all major credit card processors and gateways and are Level 1 PCI DSS compliant.

== Installation ==
1. Requires WooCommerce extension to be installed/updated at least to 5.5 version first! 
https://wordpress.org/plugins/woocommerce/
2. Upload `splitit` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Enable the module in WooCommerce -> Settings -> Payments -> Splitit (tab) and log in to your merchant account before it appears on the checkout page.

<a href="https://merchant.splitit.com/" target=_blank>Register for Free</a><br>
<a href="https://www.splitit.com/">Read more about Splitit</a>


== Screenshots ==

1. General settings
2. Cart page
3. Splitit payment method on checkout
4. Order success page
5. Admin Order page
6. Customer charge action

== Changelog ==

= 4.1.4 - 2024-02-27 =
Added async logic for refunds
Tested compatibility with WordPress version 6.4.3 and Woocommerce version 8.4.0

= 4.1.3 - 2023-10-23 =
Some minor code improvements and bug fixes
Tested compatibility with WordPress version 6.3.2 and Woocommerce version 8.2.1

= 4.1.2 - 2023-08-07 =
Some minor code improvements and bug fixes

= 4.1.1 - 2023-07-31 =
Some minor code improvements and bug fixes

= 4.1.0 - 2023-07-19 =
Added minimum supported PHP version 7.2

= 4.0.0 - 2023-07-05 =
Added minimum supported PHP version 8.1
Implemented a new design and new logic for the plugin settings page with authorization through the Splitit Merchant Portal.
Implemented new On-Site Messaging.
Implemented new version of the SDK based on a new version of the API v.3
Tested compatibility with WordPress version 6.2.2 and Woocommerce version 7.7.2
Some minor code improvements and bug fixes

= 3.3.2 - 2023-06-28 =
Added update interruption in case of PHP version mismatch

= 3.3.1 - 2023-06-27 =
Rollback version with php-7 compatibility

= 3.3.0 - 2023-06-26 =
Implemented a new design and new logic for the plugin settings page with authorization through the Splitit Merchant Portal.
Implemented new On-Site Messaging.
Implemented new version of the SDK based on a new version of the API v.3
Tested compatibility with WordPress version 6.2.2 and Woocommerce version 7.7.2
Some minor code improvements and bug fixes

= 3.2.2 - 2022-06-23 =
Tested compatibility with WordPress version 6.0 and Woocommerce version 6.5.1
Fix a bug with incorrect displaying UM in footer on some pages
Fix a bug with generating empty cell in shop table on cart page
Some minor code improvements and bug fixes

= 3.2.2 - 2022-05-16 =
Fix the problem of canceling the plan due to incorrect VAT calculation
Fix a bug with switching payment methods on the checkout page
Added notification to the internal Splitit Slack channel and internal Splitit API about activate / deactivate the plugin
Added logic for UM with the "Enable Splitit per product" setting
Improved FlexField logic when "Enable Splitit per product" setting
Some minor code improvements and bug fixes

= 3.2.1 - 2022-03-27 =
Upstream message on checkout page
Check cart total for display upstream message on product page
Some minor code improvements and bug fixes
Improved code style quality

= 3.2.0 - 2022-02-21 =
Fix issue with order success page
Fix issue on order pay page
Fix fatal error: Cannot redeclare GuzzleHttp\describe_type()
Fix display payment if settings empty
Fix async order creation process
Compatibility with "WooCommerce TM Extra Product Options" plugin
Compatibility with "WooCommerce Multilingual" plugin
Compatibility with "Speed Booster Pack" plugin
Compatibility with "WooCommerce Avatax" plugin
Add a link to documentation (on plugin settings page)
Some minor code improvements and bug fixes
Improved code style quality

= 3.1.1 - 2021-11-22 =
Minor code improvements and bug fixes

= 3.1.0 - 2021-11-11 =
Allow merchant to choose the num of installments to divide the upstream messages
Compatibility with "WooCommerce Smart COD" plugin
Added a new Feature to enable Splitit per product
Add logo to checkout
Fix upstream messages init on the product page
Fix fatal error "Call to a member function is_type() on null"
Fix display price in UM (£NaN/month)
Fix issue with optional values in billing address
Improved code style quality

= 3.0.1 - 2021-10-01 =
Updated cart and product page upstream messages
Added custom checkout loader
Changed function for get total price for multi currency plugins
Added settings with position of the upstream messages
Some minor code improvements and bug fixes
