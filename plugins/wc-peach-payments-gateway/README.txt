=== Peach Payments Gateway ===

Tags: woocommerce, payments, credit card, payment request
Requires at least: 4.7
Tested up to: 6.3.1
Requires PHP: 7.4
Stable tag: 3.2.3
Version: 3.2.3
License: GPLv3


A payment gateway integration between WooCommerce and Peach Payments.

== Introduction ==
Accept payments from customers using a variety of [payment methods](https://developer.peachpayments.com/docs/pp-payment-methods), including card, EFT, BNPL (buy now, pay later), QR code, wallet, voucher, mobile money, and alternative credit.

== Overview ==
Peach Payments is a leading payment service provider in multiple countries servicing some of the largest companies in Africa. Our mission is to make online payments simple and seamless across Africa.

Explore all [Peach Payments](https://www.peachpayments.com/) features and how to configure Peach Payments for [WooCommerce](https://developer.peachpayments.com/docs/woocommerce).

= Features =
* Enterprise-grade security (3-D Secure enabled and PCI compliant)
* Mobile-optimised user experience
* Industry-leading conversion rates
* Supports multiple currencies
* Secure card storage
* Fully supports WooCommerce Subscriptions, if purchased separately
* Our modern Dashboard enables you to easily connect your e-commerce platform to Peach Payments, view and refund transactions, and test integrations and payment methods using our fully isolated sandbox environment.

= It’s free, and always will be =
We are firm believers in open source, which is why we’re releasing this module for free, forever.

= Actively developed =
This module is actively developed, new features and enhancements are added based on your feedback.

= Testing =

Visit the Peach Payments documentation hub for details on how to test the payment extension [WooCommerce](https://developer.peachpayments.com/docs/woocommerce#configure-the-plugin) and for test cards and credentials [Test and go live procedure 🚀](https://developer.peachpayments.com/docs/reference-test-and-go-live).

= Sign up with Peach Payments =
Contact Peach Payments at [sales@peachpayments.com](mailto:sales@peachpayments.com) to set up a merchant account for your company or website.
Peach Payments is there to assist you in the application process with the respective banks. Note: The merchant account application process may take up to four weeks depending on the bank. Get in touch as soon as possible to avoid delays going live.


== Changelog ==

= 3.2.3 =
 * Update - More descriptive backend setting labels
 * Fix - PHP errors on WC Blocks

= 3.2.2 =

 * Enhancement - Update Card expiry validation logic for CnP
 * Enhancement - Show plugin description on front-end
 * Enhancement - Support for WooCommerce Blocks
 * Enhancement - Added MCB Juice Payment Logo

= 3.2.1 =

 * Fix - Issues with subscription Card widget payments

= 3.2.0 =

 * Fix - Backend vulnerability in ajax call when rolling back versions
 * Fix - Set a default value of the 'Checkout Option Cookie' for new Checkouts 

= 3.1.9 =

 * Enhancement - Plugin description update
 * Enhancement - receipt_page function cookie order check
 * Fix - Redirect when the "More payment methods" option is selected
 * Fix - Backend order error for payment method field
 * Fix - Error on the Standalone Card widget if the Visa and Mastercard logos aren't added to the Consolidated Payment Logo option

= 3.1.8 =
 * Feature - Setting to enable and disable consolidated payment options
 * Feature - Setting to enable and disable the standalone CARD payment option
 * Feature - Setting to remove and add logos displayed on the frontend.
 * Feature - Setting to change label text displayed on consolidated payment options
 * Feature - Added new payment method Capitec Pay
 * Enhancement - Cards stored before version 3.0.0 can now be deleted in subsequent versions.
 * Enhancements - Support for sending payment links via WooCommerce
 * Enhancements - Additional COF parameters
 * Enhancements - Rollback option to version 3.1.7
 * Fix - Auto-selecting the "Pay and Save New Card" option for logged-in users on subscription payments
 * Fix - Issue with stored card expiry data

= 3.1.7 =
 * Enhancements - Remove duplicate links in plugin readme file.
 * Enhancements - Add additional cards on the "My Cards" page.
 * Enhancements - Change/Update card for a subscription order.

= 3.1.6 =
 * Fix - Deployment error. Missing Backend Styling

= 3.1.5 =
 * Fix - Remove special characters from billing fields.
 * Enhancements - Remove duplicate links in plugin readme file.
 * Enhancements - Ability to update plugin description.

= 3.1.4 =
 * Enhancements - WPML string translations.
 * Enhancements - Blink by EMTEL: new supported payment method

= 3.1.3 =
 * Fix - Plugin default title text
 * Fix - Error when deleting stored cards

= 3.1.2 =
 * Enhancements - All Products for WooCommerce Subscriptions plugin support
 * Enhancements - Custom order status support
 * Update - Error Logging
 * Fix - Custom order notes will not be emailed to clients

= 3.1.1 =
 * Enhancements - Billing Address character limit based on new 3DS 2.0 on .JS Widget
 * Fix - Order_id reference fix on hosted payments page webhooks

= 3.1.0 =
 * Enhancements - Checkout for Woo-commerce plugin support
 * Enhancements - Mix basket processing
 * Enhancements - Additional billing parameters to support 3DS 2.0
 * Fix - Webhook handling
 * Fix - SSL handling function

= 3.0.9 =
 * Enhancements - Express Checkout for Woocommerce plugin support.
 * Fix - Undefined variable "seqOrderID"
 * Fix - Manual Orders not showing Payment Widgets

= 3.0.8 =
 * Enhancements - Access and secret token validation
 * Enhancements - Update on guests users payment flow
 * Enhancements - Stored card display in the user dashboard
 * Enhancements - Improvements in .JS widget design for stored card payments
 * Enhancements - Check when card items amount is 0
 * Fix - Code conflict causing php errors

= 3.0.7 =
 * Fix - SSL check for PHP7.4
 * Fix - Subscription payments for orders made on older version (v2) of the plugin.
 * Fix - Hosted Payments Page Webhook.
 * Enhancements - Include current Peach Payments plugin version number in API responses.
 * Enhancements - Cards saved on older version of the plugin will now be available.
 * Enhancements - CleanTalk plugin compatibility.

= 3.0.6 =
 * Enhancements - Peach Logs added to WooCommerce

= 3.0.5 =
 * Enhancements - In plugin update message.
 * Update - Webhook Order Status.
 * Update - Conditional display for card payment options.
 * Fix - Incorrect Payment Brand Saved.

= 3.0.4 =
 * Enhancements - Code improvements apon live checkout.

= 3.0.3 =
 * Fix - PHP parse error causing critical error on plugin activation.
 * Fix - Live and Sandbox result codes.

= 3.0.1 =
 * Fix - Theme Conflict - duplicate function names.

= 3.0.0 =
 * Fix - Updated payment method branding and names.
 * Fix - Elementor page builder compatibility.
 * Fix - Support for multi-currency plugins.
 * Fix - Support for Wordpress membership Plugin.
 * Enhancements - Subscription functionality.
 * Enhancements - Ability to do refunds via WordPress backend.
 * Enhancements - Plugin UI and admin re-design.
 * Enhancements - Optimisation for mobile.
 * Enhancements - Code cleanup and better coding standards.
 * Enhancements - WordPress Multi Site Support.
 * Enhancements - Version Rollback Functionality.
 * Enhancements - Support for AutomateWoo plugin.
 * Enhancements - Scrubill plugin compatibility.
 * Enhancements - Support for Woo-commerce eGift Card plugin.
 * Security - Improved way on how card details are stored and used.
 * Security - SSL Checks.