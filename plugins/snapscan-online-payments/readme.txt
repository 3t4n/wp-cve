=== SnapScan Payment Gateway ===
Contributors: SnapScan
Tags: woocommerce,payments,payment gateway,qr,snapscan
Requires at least: 4.6
Tested up to: 6.2
Requires PHP: 5.6
Stable tag: 1.5.16
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A free, safe, and secure payment integration where customers can pay via SnapScan or card with automatic WooCommerce payment reconciliation.

== Minimum Requirements ==

- WordPress 4.6
- WooCommerce 2.6.0 or greater
- PHP version 5.6.20 or greater. PHP 7.2 or greater is recommended

== Description ==

This extension provides a unique combination of payment options for your customers. SnapScan allows South African customers to check out quicker and more securely using the SnapScan app, which encrypts their card details. Customers spend less time at checkout, and more time shopping.
Customers without the SnapScan app can pay using their card details. These payments will reflect alongside your SnapScan payments in your consolidated SnapScan transaction history.
Once the payment process is complete, the order will be created in the WooCommerce system and marked as "processing", so no manual payment reconciliation is needed.

== Key Features ==

* SnapScan offers a fast and easy online signup
* Simple and free payment integration
* Customers need not enter card details (unless selecting to pay via card)
* Customers scan on desktop or click a link on a mobile device; or select to pay by card
* Responsive and mobile friendly
* Payment happens onsite with a trusted mobile payment provider

== Why choose SnapScan? ==

SnapScan is the preferred way for South Africans to pay with their phones.
You may know the app from your favourite weekend market or local coffee shop, but SnapScan also provides payment solutions for larger merchants: from payments and invoicing for Pathcare laboratories to online checkout for big brands like Superbalist, OneDayOnly and Yuppiechef. SnapScan's rate starts at  3% (excl. VAT) per transaction, and can decrease every month based on the previous month's turnover.

== How do I start using SnapScan? ==

To get started with SnapScan, you need a merchant account. Head over to [www.snapscan.co.za](https://www.snapsan.co.za) to complete the online signup process. This generally takes 3-5 business days, and the sooner SnapScan gets the required info the quicker it is to get set up.
Once your account has been verified, you can request the WooCommerce integration details from their team.
When the plugin has been integrated, after a customer has paid, their order is automatically completed and the website updates. As a merchant, your order list displays confirmation of the SnapScan payment, and you can begin the delivery process.

== What happens after I have a SnapScan merchant account? ==

* Download this plugin
* Email help@snapscan.co.za and let them know you'd like to integrate with the WooCommerce SnapScan plugin
* SnapScan will send you all the relevant details and instructions for setting up the payment option on your website.

== Changelog ==

= 1.5.16 =
* Resolve issues with deprecation warnings on PHP 8. 

= 1.5.15 =
* Resolve issue with PHP 8 when polling for payment status.

= 1.5.14 =
* Resolve issue with logger class.

= 1.5.13 =
* Fixed fetching of card plugin details.

= 1.5.12 =
* Added Fetch API settings button to automatically configure the plugin.

= 1.5.11 =
* Improve handling of multiple failed payments via QR.

= 1.5.10 =
* Cater for multiple failed payments.

= 1.5.9 =
* Removed dependency on jquery.

= 1.5.8 =
* Fixed a bug on webhook failure notification for payments that timed out.

= 1.5.7 =
* Removed HPP redirect in favour lightbox for a better UX.

= 1.5.6 =
* Added downloadable logs.
* Fixed issue with non-standard order configurations.
* Removed non-standard admin notification code.

= 1.5.5 =
* Reduce non-critical admin notifications.
* Improved performance.

= 1.5.4 =
* Updated error notification when API key is incorrect.
* Fixed styling clashes with Bootstrap.

= 1.5.3 =
* Updated the "Pay using..." text to reflect Diners Club.

= 1.5.2 =
* Improvements to background order recon.

= 1.5.1 =
* Fixed webhook resolution.

= 1.5.0 =
* Compatibility fixes for Zend, nginx, etc.

= 1.4.9 =
* First public release.
