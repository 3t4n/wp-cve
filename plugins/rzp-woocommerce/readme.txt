=== Razorpay Payment Links for WooCommerce ===
Contributors: knitpay, gautammkgarg, infosatech
Tags: razorpay, qrcode, upi, woocommerce, payments,
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 1.2.2
Requires PHP: 5.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

The easiest and most secure solution to collect payments with WooCommerce. Allow customers to securely pay via Razorpay (Credit/Debit Cards, NetBanking, UPI, Wallets, QR Code).

== Description ==

### Razorpay Payment Links for WooCommerce

This is the Razorpay Payment Gateway plugin for WooCommerce based on [Razorpay Payment Links](https://razorpay.com/payment-links/). Allows you to accept Credit Cards, Debit Cards, Netbanking, Wallets, and UPI Payments with the WooCommerce plugin.

It uses a Razorpay's Payment Link API integration, allowing the customer to pay on your website being redirected to Razorpay's Secure Payment Page. This allows for refunds, works across all browsers, and is compatible with the latest WooCommerce.

#### Plugin Features

* WooCommerce High-Performance Order Storage (HPOS) Support.
* Collect Payments using Razorpay Payment Links.
* Ability to send Payment Links via SMS and Email notification to customers.
* One time Payment for your website.
* Customized "Order Received" message.
* Mode of transaction Live and Test Mode.
* Reference Order ID & Transaction ID.
* Auto Refund from WooCommerce Order Details Section. Instant Refund supported.
* Ability to set payment link expiry time.
* Easily Collect Gateway Fees from Customer.
* Ability to send Payment Reminder automatically.
* Secure Payment Capture Mechanism.
* 94 [Razorpay Currency](https://razorpay.com/docs/international-payments/#supported-currencies) Support.
* Order note for every Transaction related process.
* Detailed Payment process Log via WooCommerce Logger.
* Lots of filters available to customize the output.

Like Razorpay Payment Links for WooCommerce plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/rzp-woocommerce/reviews/?rate=5#new-post).

#### Compatibility

* This plugin is fully compatible with WordPress Version 4.6 and beyond and also compatible with any WordPress theme.

#### Support
* Community support via the [support forums](https://wordpress.org/support/plugin/rzp-woocommerce) at WordPress.org.

#### Contribute
* Active development of this plugin is handled [on GitHub](https://github.com/knit-pay/rzp-woocommerce).
* Feel free to [fork the project on GitHub](https://github.com/knit-pay/rzp-woocommerce) and submit your contributions via pull request.

== Installation ==

1. Visit 'Plugins > Add New'.
1. Search for 'Razorpay Payment Links for WooCommerce' and install it.
1. Or you can upload the `rzp-woocommerce` folder to the `/wp-content/plugins/` directory manually.
1. Activate Razorpay Payment Links for WooCommerce from your Plugins page.
1. After activation go to 'WooCommerce > Settings > Payments > Razorpay Payment Gateway'.
1. Enable options and save changes.

== Frequently Asked Questions ==

= Is there any admin interface for this plugin? =

Yes. You can access this from 'WooCommerce > Settings > Payments > Razorpay Payment Gateway'.

= How to use this plugin? =

Go to 'WooCommerce > Settings > Payments > Razorpay Payment Gateway', enable/disable options as per your need and save changes.

= How to use webhook? What webhooks are supported? =

Go to Razorpay 'Dashboard > Settings > Webhooks'. Enter the URL from plugin settings page and create and copy webhook secret key and paste it to plugin settings and save changes. By Default this plugin supports only these two Webhooks: "payment.authorized" and "refund.created". If you want more webhooks supported, please feel free to contact us at https://www.knitpay.org/contact-us/ as it needs custom development. 

= How to send automatic payment reminder to customer, if customer does not make payment after initiating the payment procedure? =

It needs custom developement. Please contact us at https://www.knitpay.org/contact-us/.

= I want to use Razorpay Web Integration like Automatic Checkout/Manual Checkout (On site Checkout - No Redirection) with webhooks? =

It needs custom developement. Please contact us at https://www.knitpay.org/contact-us/.

= I want to customize the look of the default Razorpay Gateway like colors/text etc. How can I get this? =

It needs custom developement. Please contact us at https://www.knitpay.org/contact-us/.

= Is this plugin compatible with any themes? =

Yes, this plugin is compatible with any theme.

= The plugin isn't working or have a bug? =

Post detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/rzp-woocommerce) and we will work to fix it.

== Screenshots ==

1. Admin Dashboard
2. Checkout Page
3. Payment Page
4. Payment Success Page
5. Order History
6. Refund Area

== Changelog ==

If you like Razorpay Payment Links for WooCommerce, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/rzp-woocommerce/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!

= 1.2.2 =
Release Date: March 13, 2024

* Plugin ownership transferred from Sayan Datta to Knit Pay (Logic Bridge Techno Mart LLP).

= 1.2.1 =
Release Date: January 8, 2024

* Tweak: PHP 8.3 Support.

= 1.2.0 =
Release Date: January 6, 2024

* Added: Support for WooCommerce Block-based checkout.
* WordPress tested up to v6.4.
* WC Tested up to v8.6.

= 1.1.9 =
Release Date: July 22, 2023

* WordPress tested up to v6.3.
* WC Tested up to v7.9.

= 1.1.8 =
Release Date: June 10, 2023

* WordPress tested up to v6.2.
* WC Tested up to v7.8.

= 1.1.7 =
Release Date: December 10, 2022

* Tweak: Default API is now set as Standard Mode.
* WordPress tested up to v6.1.
* WC Tested up to v7.1.

= 1.1.6 =
Release Date: July 15, 2022

* Improved: Code Quality.
* WordPress tested up to v6.0.
* WC Tested up to v6.7.

= 1.1.5 =
Release Date: July 25, 2021

* Improved: Localhost check.
* Support for old WC Version (2.0 to 3.1)
* WC Tested up to v5.5 and v5.6.

= 1.1.4 =
Release Date: June 16, 2021

* Added: Order Number instead of Order ID.
* WC Tested up to v5.4.

= 1.1.3 =
Release Date: May 23, 2021

* Added: Auto Enable Webhook on plugin settings save.
* Fixed: Bug related to new API.

= 1.1.2 =
Release Date: January 11, 2021

* Added: Option to switch between Standard and Legacy Payment Links API. If your Razorpay Account was created on or after 1 September 2020 and Legacy API is not working for you, please use Standrard API. Legacy API will be officially deprecated by Razorpay on 31st March, 2021.

= 1.1.1 =
Release Date: December 14, 2020

* WordPress tested up to v5.6.
* WC Tested up to v4.8.

= 1.1.0 =
Release Date: November 7, 2020

* Fixed: Partial refund from Razorpay Dashbaord causing multiple refund events.
* WC Tested up to v4.6.

= 1.0.9 =
Release Date: August 15, 2020

* Fixed: Webhook Issue.
* WC Tested up to v4.4.

= 1.0.8 =
Release Date: July 24, 2020

* Fixed: Redirection issue due to recent Razorpay API Chnages.

= 1.0.7 =
Release Date: July 20, 2020

* WC Tested up to v4.3.

= 1.0.6 =
Release Date: June 9, 2020

* Tweak: Cart will not be cleared if payment is not actually made.
* Fixed: Redirection issue.
* WC Tested up to v4.2.0

= 1.0.5 =
Release Date: May 24, 2020

* Improved: Webhook Handler.

= 1.0.4 =
Release Date: May 18, 2020

* Improved: Payment verfication mechanism.
* Improved: Webhook mechanism.
* Tweak: It is now possible to use any other payment method to make payment when payment initialized at first through the Razoypay Gateway method.

= 1.0.3 =
Release Date: May 12, 2020

* NEW: Added Razorpay Webhook feature.
* NEW: Added Instant Refund option.
* Fixed: Email notification is not working properly.
* Improved: Reduced icon image size to adapt the width properly.
* Compatibilty with WooCommerce 4.1.

= 1.0.2 =
Release Date: April 26, 2020

* Compatibilty with WordPress 5.4 and WooCommerce 4.0.

= 1.0.1 =
Release Date: January 30, 2020

* Added: A filter `rzpwc_charge_custom_tax_amount` to set custom tax amount on cart total.
* Added: A filter `rzpwc_payment_success_redirect` to set custom redirect url after successful payment verification.
* Improved: Payment verfication mechanism.
* Tweak: API Secret Key fields are now password type fields.
* Fixed: Minor bugs.

= 1.0.0 =
Release Date: January 25, 2020

* Initial release.