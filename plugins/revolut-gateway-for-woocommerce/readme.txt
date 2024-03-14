=== Revolut Gateway for WooCommerce ===
Contributors: revolutbusiness
Tags: revolut, revolut business, revolut pay, payments, gateway, payment gateway, credit card, card
Requires at least: 4.4
Tested up to: 6.3
Stable tag: 4.10.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.apache.org/licenses/LICENSE-2.0

Save on accepting payments with the Revolut Gateway for WooCommerce plugin for your store. With Revolut, you can accept card payments in up to 22 currencies, access your funds quickly with next-day settlement, and manage your business's money all in one place.

== Description ==
## What does this plugin do for you

### Features:

- Accept debit and credit card payments [at great rates](https://www.revolut.com/business/business-account-plans)
- Accept payments via Revolut Pay
- Customize the style of the card field in the checkout
- Customize the payment actions (Authorize only or Authorize and capture)
- Refund and capture payments directly from your WooCommerce admin section
- Support for [WooCommerce subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/)
- Support for manual order creation
- Support for payments with Apple Pay and Google Pay

### Revolut Pay:

- Offer your customers a 1-click, easy checkout experience with Revolut Pay and enjoy your higher acceptance rates on top of lower transaction fees
- Take advantage of cashback rewards up to £/€20 for non-Revolut customers who checkout with you using Revolut Pay
- Get your money within 24 hours for Revolut Pay transactions

### Express checkout options:

- Offer a seamless checkout experience with Revolut Pay - Express checkout: automatically collect customers' shipping and delivery details and speed up the checkout process
- Accept payments via Apple Pay and Google Pay using the Revolut Gateway, let your customers pay without a hassle using their mobile wallets

### Great pricing:

- Pocket more of what you earn with low, competitive pricing for businesses of all sizes
- Get started with processing rates of 1% + £/€0.20 for local, consumer cards, and 2.8% + £/€0.20 for all other cards
- Never pay extra for different currencies, next-day settlement, or fraud monitoring

### Improved checkout conversion:

- Complete sales more quickly and increase conversions by enabling hassle-free Revolut Pay, which lets customers check out in just one click
- Earn profit more efficiently by increasing your payment authorisation rates via Revolut Pay compared to card payments

### Power your international commerce:

- Accept payments in up to 22 currencies via card and 29 currencies via Revolut Pay using dedicated currency accounts
- Hold and exchange 30+ currencies with no hidden fees
- Manage FX market risk by getting a fixed FX rate for future exchanges

### Settlement and security:

- Get your earnings deposited directly to your Revolut Business account with next-day settlement
- Reduce your risk of fraud by relying on Revolut's array of fraud monitoring tools that can flag early signs
- Our payments product is both encrypted and PCI DSS certified

## What your customers will like

Keeping your customers happy is huge, and offering them the simplest and smoothest customer payment experience helps you do just that. With our payment gateway, customers can:

- Pay in the currency of their choice
- Access cashback rewards when they sign up for and check out with Revolut Pay
- Experience streamlined checkout
- Checkout faster with Revolut Pay using their Revolut account
- Enjoy peace of mind with our secure payment processing

== Installation ==

To use the plugin you need to have a [Revolut Business account](https://business.revolut.com/signup "Sign up for a Business account") and an active [Merchant Account](https://business.revolut.com/merchant/ "Apply for a merchant account").

If you don't have a Revolut Business account:

- Sign up for a Business account and when asked the reason for opening the account make sure to select "Receive payments from customers" as one of the reasons
- Provide a description for your business and indicate a category that most closely defines your activities
- Provide the domain of your WooCommerce website when asked about website of your business

If you already have a Revolut Business account but your Merchant Account is not active:

- Go to the Home section in the Revolut Business web portal and select the [**Merchant tab**](https://business.revolut.com/merchant/)
- Click **Get started** and follow the steps to fill in the information of your business
- When prompted, provide the domain of your WooCommerce website

That's it! As soon as you install the Revolut Gateway for WooCommerce plugin you will be ready to start accepting payments. If you want to know more about the advantages of accepting payments via Revolut, you can take a look in [our website](https://www.revolut.com/business/online-payments).

To start accepting payments from your customers at great rates, install the plugin by following [this tutorial](https://developer.revolut.com/docs/guides/accept-payments/plugins/woocommerce/installation). After successful installation, configure the plugin, following [this tutorial](https://developer.revolut.com/docs/guides/accept-payments/plugins/woocommerce/configuration).

== Screenshots ==

1. Searching for the Revolut Gateway for WooCommerce plugin
2. The Revolut Gateway plugin has been added to your Wordpress plugins
3. The general Revolut API settings page for the Revolut Gateway for WooCommerce plugin
4. The Credit card payment settings
5. The Revolut Pay Button settings

== Changelog ==
= 4.10.1 =
* Fixed card gateway issue

= 4.10.0 =
* Fixed payments capture issue on Revolut Pay webflow

= 4.9.9 =
* Fixed Fast Checkout address fields
* Fixed Revolut Pay order processing at Order Pay page

= 4.9.8 =
* Fixed PHP8 compatibility 
* Fixed loading screen issue for Order Pay page
* Fixed optional address state field for fast checkout order processing

= 4.9.7 =
* Fixed duplicated orders
* Updated Revolut Pay Fast Checkout order processing flow

= 4.9.6 =
* Added Woocommerce High-Performance Order Storage (HPOS) compatibility. 
* Added additional security improvements.

= 4.9.5 =
* Fixed creating customer objects

= 4.9.4 =
* Fixed file system compatibility issue
* Fixed Apple Pay country code issue

= 4.9.3 =
* Fixed location registration issue

= 4.9.2 =
* Updated payment gateway logos
* Fixed payment request button issue
* Fixed changing subscription payment method issue

= 4.9.1 =
* Fixed subscription renewal issue
* Improved registering address validation endpoint for Fast checkout

= 4.9.0 =
* Improved Revolut Pay mobile redirects
* Fixed saving customer ids

= 4.8.0 =
* Updated promotional banners

= 4.7.1 =
* Added Cardholder Name input for card gateway

= 4.7.0 =
* Updated plugin description

= 4.6.0 =
* Updated payment method logos

= 4.5.0 =
* Added auto cancel feature for express checkout orders
* Fixed widget initialization issue at payment method add page
* Fixed validation issue at order pay page

= 4.4.0 =
* Fixed tax issue for express checkout shipping methods
* Fixed checkout validation for payment request buttons
* Added clear button for clearing unused information
* Added title configuration for payment request buttons

= 4.3.0 =
* Enabled Apple Pay and Google Pay payment options in checkout page
* Fixed express checkout issue for mobile browsers
* Fixed subscriptions issue

= 4.2.0 =
* Added order state selection for manual capture payments
* Fixed registering webhooks

= 4.1.0 =
* Added Popup card widget
* Fixed product page issue

= 4.0.2 =
* Adjusted minimum PHP version requirement

= 4.0.1 =
* Fixed warnings from lower version of PHP
* Increased recommended version of PHP

= 4.0.0 =
* Fast checkout full launch

= 3.9.0 =
* Fixed express checkout caching
* Fixed admin notifications

= 3.8.0 =
* Added Revolut Pay Express checkout functionality

= 3.7.0 =
* Improved webhook processing
* Updated cashback currency

= 3.6.0 =
* Updated currency list
* Fixed order validation result parsing issue

= 3.5.0 =
* Improved order result processing
* Added compatibility with review plugin

= 3.4.0 =
* Fixed parse notice

= 3.3.0 =
* Fixed subscriptions issue

= 3.2.2 =
* Added payment logos for Revolut Pay method

= 3.2.1 =
* Fixed partial refund issue
* Fixed cart clearing issue
* Fixed order creation issue when card field is empty

= 3.2.0 =
* Added the new version of Revolut Pay widget

= 3.1.6 =
* Fixing compatibility issue with PHP versions

= 3.1.5 =
* Fix minor payment button reloading issue

= 3.1.4 =
* Fixing compatibility issue with the older PHP versions

= 3.1.3 =
* Refactor to adhere to WordPress conventions
* Security updates

= 3.1.2 =
* Fixing security and vulnerability issues

= 3.1.1 =
* Added compatibility for Germanized plugin
* Added size configuration options for Payment Buttons (ApplePay&GooglePay)

= 3.1.0 =
* Added feature to trigger Apple Pay setup manually
* Added feature to set Webhooks automatically
* Fixed duplicated OR labels for payment buttons

= 3.0.2 =
* Fix minor issue ajax endpoint url

= 3.0.1 =
* Fix Pay Button minor issue for out of stock products

= 3.0.0 =
* Payment Request Button (ApplePay&GooglePay) support added

= 2.5.2 =
* Fix minor issue for payment amount validation

= 2.5.1 =
* Fix saved payment methods issue after customer login

= 2.5.0 =
* Avoid duplicated payments when customer account settings is enabled

= 2.4.2 =
* Fix duplicated order status update
* Validate saved payment tokens through API

= 2.4.1 =
* Fix refund issue
* Fix webhook callback order not found issue

= 2.4.0 =
* Refresh checkout page without reloading
* Update payment amount after order creation
* Fix card widget reloading when save card checkbox is updating
* Add configuration in order enable/disable card save feature

= 2.3.3 =
* Fix order process error when create customer checkbox is enabled
* Fix setting webhook issue

= 2.3.2 =
* Minor issues refactored
* Missing dependency issue solved

= 2.3.1 =
* Fixed duplicated order issue
* Tested with the latest WordPress and WooCommerce versions

= 2.3.0 =
* Optimize checkout validation

= 2.2.9 =
* Fix manual order page stack in loading issue
* Fix API callback issue
* Localization files added
* Information about failed Payment attempts added into the order

= 2.2.8 =
* Update available Revolut order currency list
* Update documentation link

= 2.2.7 =
* Fix duplicated API order creation

= 2.2.6 =
* Fix missing parameter issue

= 2.2.5 =
* Improve Revolut Widget error reporting

= 2.2.4 =
* Fix payment process error when some checkout address fields are missing

= 2.2.3 =
* Fix checkout validation issue

= 2.2.2 =
* Minor bug fixes

= 2.2.1 =
* Hotfix for version 2.2.0 for sites that did not have the WooCommerce subscriptions plugin

= 2.2.0 =
* Support for [WooCommerce subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/)
* Support saving card information

= 2.1.0 =
* Support Multisite Wordpress installations
* Support Card Widget styling
* Support manual payments
* Support for multilanguage sites. The text inside of the Card widget will now adapt to the language of the website.

= 2.0.0 =
* Added Revolut Pay

= 1.2.5 =
* Create Woocommerce Order even if transaction failed
* Adjust create order flow
* Allow customer to update payment information at checkout
* Create Woocommerce order before verifying Revolut payment
* Handle webhook responses for different Woo order statuses
* Handle webhook received after payment

= 1.2.4 =
* Compatible with Jupiter theme

= 1.2.3 =
* Added support for refunding orders from the WooCommerce Order view
* Added support to capture orders by changing the status of the order in the WooCommerce order view
* Added webhook support. You can now setup webhooks from the plugin settings. Orders captured in the Revolut Business web portal will change the status of the WooCommerce order
* Fixed bug for mySQL versions older than 5.6.5 where "Something went wrong" was displayed instead of the card field
* Fixed code that was causing PHP notices and warnings to appear in the logs
* Fixed wording of multiple messages to improve clarity

= 1.2.1 =
* Fixed bug that created failed orders even if payment had been captured
* Added instructions in the settings page to get started quickly and easily

= 1.2.0 =
* Added support for "Authorize Only" order types
* Added option to easily switch between "Sandbox" and "Live" environments by keeping the keys saved
* Improved the Checkout widget visually to be compatible with more themes
* Fixed bug that created uncaptured transactions if the checkout form was not properly filled out by the user

= 1.1.5 =
* Minor bug fixes

= 1.0.1 =
* Fixing some compatibility issues with certain WooCommerce themes

= 1.0 =
* First stable version of the Revolut Gateway for WooCommerce plugin


== Upgrade Notice ==

= 1.0 =
* First stable version of the Revolut Gateway for WooCommerce plugin