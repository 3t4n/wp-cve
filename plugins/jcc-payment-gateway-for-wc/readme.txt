=== JCC Payment Gateway for Woocommerce ===

Contributors: jccpaymentsystems
Tags: payment,jcc,payment gateway,jcc cyprus,payment,plugin payment
Description: A plugin for adding the JCCgateway as a payment option in WooCommerce.
Author: JCC
Version: 1.3.7
License: GPLv2
Stable tag: 1.3.7
Requires at least: 5.4
Requires PHP: 5.6
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Donate link: https://notavailable.com
Tested up to:  6.4.1
WC tested up to: 6.5.1

== Description ==

JCC’s payment gateway offers real-time and batch payment processing. It uses all available security measures to prevent fraudulent transactions and ensure data safety yet it’s easy to integrate with merchants’ systems. 
In addition, it allows merchants to review and manage transactions, prepare reports, etc. through a user-friendly, intuitive administration interface.Another feature the plugin offers, is the ability for the merchant to define a prefix value that will be appended in the order id that is sent to JCCgateway.

All orders sent to JCC for processing by the e-shop will have that prefix.
It can be used when logging in to merchant admin console of JCC to identify from which e-shop does the order come, when merchant has multiple e-shops. The current plugin supports making payment via HTTP Post redirect to JCC payment gateway and also refunds via JCC Web Services\'s endpoint, called Financial Service.

**Note: After the payment, order's status updates as suggested by Woocommerce:**
 -*If items in the order are all physical , the order is processing until changed – Order Status = PROCESSING*
 -*If items in the order are all downloadable / virtual, the order is completed – Order Status = COMPLETED*
 -*If items are physical and downloadable / virtual, the order is processing until changed – Order Status = PROCESSING*

Supported Currencies
 - EUR (Euro)
 - USD (United States Dollar)
 - GBP (British Pound)
 - CHF (Swiss Franc)
 - RUB (Russian Rouble)

== Guide ==

**Installation:**
1. Install as plugin
2. Activate
	 
**Configuration:**
1. Access plugin settings either through:
**Plugins > JCC Payment Gateway for WooCommerce > Settings**
**WooCommerce > Settings > Payments > JCC Payment Gateway**
2. Click on JCC Payment Gateway or Manage button
3. Enter credentials for test and production environment
- To run in test tick box Test Mode – Enable Test Mode
- In the Test Merchant ID field enter the merchant ID of your JCC test account, as provided by JCC
- In the Test Password field enter the the test password of your JCC test account, as provided by JCC
- In the Production Merchant ID field enter merchant id of your JCC production account, as provided by JCC
- In the Production Password field enter the the test password of your JCC production account, as provided by JCC
- Optionally, in the Merchant Order ID Prefix enter an alphanumeric prefix up to 10 characters. This prefix will be appended to the order id which will be sent to JCC
- To allow the plugin to send  billing, shipping and general info to the Issuing Bank in order to perform a real-time risk scoring of the transaction according to EMV 3DS, check the corresponding checkboxes
- Check all set fields with the JCC Payment Gateway – Developer’s Guide to confirm them  
4. Save Changes
5. In Payments enable JCC Payment Gateway plugin

== Frequently Asked Questions == 
Please contact ecom.admins@jcc.com.cy for any enquires about the Plugin or the transactions.

== Upgrade Notice == 
Latest version 1.2.4, Handling page for displaying a clear page when using invalid credentials. 

== Screenshots ==
1. Section is used by Administrators to select Test or Production configuration.If Test Mode is enabled you can only use Test Credentials and if it is diasbled you can only use the Production Credentials.
2. This screenshot shows JCC Gateway Checkout where users can procced to Gateway for payment.
3. The screenshot shows WooCommerce > Settings > Payments > JCC Payment Gateway where you can Enable the Plugin and Manage it.
4. JCC Gateway Payment screenshot. The screen that users will see when they are about to pay.

== Changelog ==
= 1.3.7 - Feb 12, 2024 =
* Bug Fix:
-Minor bug fixes and enhancments
 
= 1.3.6 – Jun 07, 2022 =
* Bug Fix:
-Fix related to the payment gateway toggle button (Woocommerce -> Settings -> Payments) not working as intended.

= 1.3.5 – May 17, 2022 =
* Bug Fix:
-Fixes related to the plugin not working on a multisite network, fixes have been made to properly check for single/multisite setup and install accordingly

= 1.3.4 – Feb 21, 2022 =
* Bug Fix:
-Grammar fixes.

= 1.3.2 – Jul 21, 2021 =
* Bug Fix:
-Fixes related to the feature introduced on version 1.3.0. More specifically, fixes have been made to escape special characters included on the newly introduced fields before sending them to JCC Payment Gateway.

= 1.3.1 – Jul 21, 2021 =
* Bug Fix:
-Fixes related to the feature introduced on version 1.3.0. More specifically, fixes have been made to escape special characters included on the newly introduced fields before sending them to JCC Payment Gateway.

= 1.3.0 – Feb 25, 2021 =
* New Feature:
-Allow the merchant to decide whether additional info will be sent to the Issuing Bank in order to perform a real-time risk scoring of the transaction according to EMV 3DS, through the Settings tab of the plugin.

= 1.2.6 – Feb 02, 2021 =
* Bug Fix:
-Handling of failed order due to invalid user credentials bug resolved. Bug was resolved in the past but cam up again after last change.

= 1.2.5 – Nov 27, 2020 =
* Bug Fix:
-Removing the extra step of order's status validation that was added in version 1.2.3 since it is now handled on JCC Payment Gateway's side.

= 1.2.4 – Nov 11, 2020 =
* Bug Fix:
-Handling of failed order due to signature validation bug resolved.
-Enhcance the validity of transaction by applying the following:
	1.Go to WooCommerce -> Settings
	2.Choose the "Products" tab
	3.Choose the Category "Inventory"
	4.In the Manage Stock settings remove any value that is present in the Hold Stock (minutes) field

= 1.2.3 – Sep 07, 2020 =
* Adding an extra step of order's status validation. More specifically, when getting an error response on a payment request for a specific orderId, the actual status of the transaction on JCC's side is checked (using Query Service) and the order's status is updated accordingly.

= 1.2.1 – Aug 17, 2020 =
* Bug Fix:
-Payment orders bug issue resolved when expired session or duplicate order ID to JCC

= 1.2.0 – Aug 03, 2020 =
* Bug Fix:
-Minor bug fixes.

= 1.1.3 – Jul 29, 2020 =
* Bug Fix:
-Validating that data transferred between classes are set.

= 1.1.1 – Jul 03, 2020 =
* Bug Fix:
-Updating the way order key is set and saved

= 1.1.0 – Jun 29, 2020 =
* Adding an option for the user to choose the format of the Merchant Order ID from below options: 
-Alphanumeric staring with the prefix "wc_order_"
-Alphanumeric
-Numeric, given by woocomerce (matches the Order # found in the Orders section of admin's page)

= 1.0.0 – Jun 25, 2020 =
* Release