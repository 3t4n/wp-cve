=== Montonio for WooCommerce ===
Version: 6.4.7
Date: 2019-09-04
Contributors: Montonio
Tags: online payment, payment, payment gateway, woocommerce, sales, hire-purchase, järelmaks, financing, credit intermediary, krediidivahendaja, montonio
Requires at least: 4.5
Tested up to: 6.4.3
Stable tag: 6.4.7
Requires PHP: 7.0
Minimum requirements: WooCommerce 3.2 or greater
License: GPLv3
License URL: http://www.gnu.org/licenses/gpl-3.0.html



== Description ==

Montonio is a complete checkout solution for online stores that includes all popular payment methods (local banks, card payments, Apple Pay, Google Pay) plus financing and shipping. Montonio offers you everything you need in your online store checkout.
 
= Payments =
The easiest way to collect payments in your online store. Montonio payment initiation service offers integrations with all major banks in Estonia, Finland, Latvia, Lithuania and Poland, additionally Apple Pay, Google Pay, Revolut (available everywhere) and Blik in Poland.
 
All funds are immediately deposited to your bank account and an overview of the transactions can be found in our [partner system](https://partner.montonio.com).

= Card Payments =
Give your customers more ways to pay. In addition to payment links, Montonio lets your users pay by credit card.

= Apple Pay, Google Pay =
Want to offer an even easier way of paying? We also have Apple Pay and Google Pay! You can add these popular mobile wallets to your online store’s checkout. Your customers can pay faster since their credit card info is stored in the digital wallet and they don’t need to enter card details with each purchase.

= Refunds =
You can do a partial or full refund with a couple of clicks in the Montonio Partner System. Just open the order, check what items your customer returned and refund the amount needed.
 
= Financing (Hire purchase) =
Montonio Financing is just the right solution for financing larger purchases. You customers can choose a payment schedule that exactly suits their needs. Shoppers pay in equal instalments but you will get the full payment amount upfront. Plus, there's no service fee for the merchant.
 
= Pay Later =
Give your visitors the most convenient ways to pay – with Montonio 'Pay later' your customers can pay later or split purchase into two or three payments. All this without any additional interest or contract fees for them. Shoppers pay in equal instalments but you will get the full payment amount upfront.
 
= Shipping =
Handle everything from one system: automatically generate, edit and print shipping labels without having to ever leave the Montonio dashboard. Labels are automatically retrieved from providers after order creation. You can start printing with just 2 clicks. With Montonio you can add order tracking codes with a link to the providers’ tracking page.
 
= How to get started =
Adding Montonio to your store is only a matter of minutes.
1. Sign up at [montonio.com](https://montonio.com)
2. Verify your identity and confirm your account with Montonio
3. Set up the plugin, insert API keys and start using Montonio. More details on how to install and set up the plugin can be found in the Installation tab.

 
= Availability = 
Montonio currently offers services in these countries:
* Payments: Estonia, Finland, Latvia, Lithuania, Poland
* Card payments: Estonia, Finland, Latvia, Lithuania, Poland
* Financing: Estonia
* Pay Later: Estonia
* Shipping: Estonia, Latvia, Lithuania
We are also working on adding new countries.

= Support =
Any questions? Just drop us an email at support@montonio.com.

= WANT TO KNOW MORE? =
More information about our solutions can be found on our [website](https://montonio.com).

== Installation ==

= Automatic installation =
1. Log in to your WordPress dashboard, navigate to the Plugins menu and click Add New. Search for "Montonio for WooCommerce" and click "Install Now", then "Activate".
2. After activating the plugin, you need to connect your Montonio API keys to your WooCommerce store. To do this, go to WooCommerce > Settings > Payments > Montonio Bank Payments (2023). Under API settings tab you can enter your API keys that are easily accessible through [https://partner.montonio.com](https://partner.montonio.com). For step-by-step instructions on where to find API keys [click here](https://help.montonio.com/en/articles/79609-how-to-find-api-keys-for-integration).
3. Save changes, and enable payment methods you want to use in your store.


= Manual installation =
1. Download the "Montonio for WooCommerce" plugin zip file from the WordPress Plugin Directory and unzip it locally.
2. Transfer the extracted folder to the wp-content/plugins directory of your WordPress site via SFTP or remote file manager.
3. From the Plugins menu in the Administration Screen, click Activate for the "Montonio for WooCommerce" plugin.
3. After activating the plugin, you need to connect your Montonio API keys to your WooCommerce store. To do this, go to WooCommerce > Settings > Payments > Montonio Bank Payments (2023). Under API settings tab you can enter your API keys that are easily accessible through [https://partner.montonio.com](https://partner.montonio.com). For step-by-step instructions on where to find API keys [click here](https://help.montonio.com/en/articles/79609-how-to-find-api-keys-for-integration).
4. Save changes, and enable payment methods you want to use in your store.


== Changelog ==
= 6.4.7 =
* Added - API key retrieval helper function and new 'wc_montonio_api_keys' filter
* Added - Pickup point dropdown in admin order view for manually created orders
* Tweak - Hide BLIK payment method if min cart amount is not reached
* Removed - Old "Financing" payment method
* Removed - Old "Pay Later" payment method
* Fix - Free shipping rate text not dispayed

= 6.4.6 =
* Tweak - Reduce webhook notification delay to 10 seconds

= 6.4.5 =
* Fix - Revert order retrieval function changes

= 6.4.4 =
* Fix - wc_get_orders() not retrieving order by meta key in some cases
* Fix - Shipping method MAX_WEIGHT constat causing error in some cases

= 6.4.3 =
* Tweak - Automatically update shipping carriers list when new shipping zone method is added
* Tweak - "Paid amount" added to order notes
* Tweak - Refactoring of the shipping method files
* Removed - "shipping_method_identifier" variable that was previously utilized to transmit the shipping method key to Montonio. Instead, it now utilizes the shipping method's ID to construct the key.
* Bugfix

= 6.4.2 =
* Added - "Pay later" custom minimum cart amount setting
* Removed - Old Financing calculator widget
* Tweak - Improved wording and translations
* Fixed - "Bulk edit" failed to create shipments in Partner System
* Bugfix

= 6.4.1 =
* Tweak - "Pay later" min required cart amount adjustment
* Tweak - Require 'paymentIntentUuid' parameter to be set, when embedded payment flow is used

= 6.4.0 =
* Added - New "Pay Later" payment method
* Added - New "Financing" payment method
* Tweak - Payment initiation bank selection UI improvements

= 6.3.1 =
* Inline Blik and Card payment bugfix

= 6.3.0 =
* Feature - Create a separate shipping label for each of the selected products
* Feature - Added support for AUTHORIZED and VOIDED payment statuses
* Tweak - Improved error handling for inline checkout methods
* Bugfix

= 6.2.1 =
* Pickup point dropdown compatibility improvements
* Bugfix

= 6.2.0 =
* HPOS compatibility improvements
* Bugfix

= 6.1.9 =
* Inline Blik and Card payment bugfix

= 6.1.8 =
* Added - Option to enable the Card fields in checkout
* Code improvements 
* Bugfix

= 6.1.7 =
* Added - Venipak shipping methods
* Added - Option to show parcel machine address in dropdown in checkout

= 6.1.6 =
* Added - Option to enable the BLIK in checkout feature
* Bugfix

= 6.1.5 =
* Added - Option to choose between displaying custom text and 0.00 price for free shipping rate methods
* Added - Notice in frontend and backend if test mode enabled
* Added - Montonio activity logger
* Added - Ability to filter orders by shipping provider
* Admin UI improvements
* Bugfix

= 6.1.4 =
* Added - "Shipping classes" support for Montonio shipping methods
* Added - Option to turn off parcel machines support per product
* Added - Update order status if payment sesion ABANDONED
* Added - Tag support for shipping cost field, use [qty] for the number of items, [cost] for the total cost of items, and [fee percent="10" min_fee="20" max_fee=""] for percentage based fees. e.g. 3.00 * [qty]
* Changed - Shipping now uses API keys from "API Settings" page
* Code improvements 

= 6.1.3 =
* Added - Make refunds for orders via Woocommerce (only for orders after this update)
* Added - Free shipping based on product quantity in the cart
* Fixed - Free shipping threshold amount now includes VAT
* Code improvements


= 6.1.2 =
* API HTTP request timeout increased to 30s (this prevents error when downloading a large number of shipping labels)
* "1. eelistus Omnivas" set as first option in dropdown for EE Omniva parcel machine
* Fixed - Pay Later not changing order status to "Completed" for virtual products after sucesfull payment
* Pickup point select style adjustments
* Gpay & Apple Pay icons added to card payments

= 6.1.1 =
* Pickup point dropdown bugfix for small screens

= 6.1.0 =
* Pickup point dropdown styling improvements
* Admin settings page UI tweaks

= 6.0.9 =
* Selected pickup point gets saved to session storage
* Replace some post meta methods with equivalent methods compatible with HPOS
* Declare compatibility with High-Performance Order Storage (HPOS)
* Bugfix

= 6.0.8 =
* Shipping SDK file_get_contents() changed to wp_remote_request() for HTTP requests
* Pickup point dropdown styling fix for small screens

= 6.0.7 =
* PHP 8.1 compatibility fix

= 6.0.6 =
* New payment method added - "Montonio Card Payments (2023)"
* New "Custom payment description" option added to Montonio Bank Payments (2023)
* Pay Later "Min order total" fix

= 6.0.5 =
* Bugfix

= 6.0.4 =
* Bugfix

= 6.0.3 =
* New admin UI for Montonio Bank Payments (2023) and Blik
* API settings moved to standalone page (for methods that use API v2)
* New option to preselect bank based on client selected billing country in checkout
* Code improvements
* Bugfix

= 6.0.2 =
* CSSTidy library removed

= 6.0.1 =
* Bugfix

= 6.0.0 =
* New payment method added - "Montonio Bank Payments (2023)" that utilizes new API
* Germany added to bank list in "Montonio Bank Payments (2023)" payment method
* Split payment rebranding
* Financing payment rebranding
* Code improvements

= 5.0.7 =
* Bugfix

= 5.0.6 =
* Bugfix

= 5.0.5 =
* Pickup point selection bugfix

= 5.0.4 =
* Bugfix

= 5.0.3 =
* Code improvements
* Set virtual product status to "Completed" after successful Financing
* Pickup point select compatibility improvements
* Blik payment method logo sizing fix

= 5.0.2 =
* Code improvements
* Pickup point selection is now a template

= 5.0.1 =
* Code improvements

= 5.0.0 =
* Introduced Montonio Blik

= 4.2.2 =
* Code improvements

= 4.2.1 =
* Reverted version

= 4.2.0 =
* Code improvements

= 4.1.9 =
* Code improvements
* Pending parcel labels now show a warning

= 4.1.8 =
* Improved parcel search dropdown styling on some themes

= 4.1.7 =
* Added Omniva Courier

= 4.1.6 =
* Added Itella Courier
* Other minor fixes and improvements

= 4.1.5 =
* Added ability to add shipping provider logos to checkout
* Made other various improvements to shipping UI in checkout
* Other minor fixes and improvements

= 4.1.4 =
* Added ability to configure minimum cart total for financing and split
* Other code improvements

= 4.1.3 =
* Code improvements

= 4.1.2 =
* Code improvements

= 4.1.1 =
* A shipment will now be created in Montonio Partner System when manually changing order to 'processing' status
* Added some advanced configuration options for Montonio Shipping
* Other minor fixes and improvements

= 4.1.0 =
* Added jQuery as dependency for checkout JS script for better coverage across stores

= 4.0.9 =
* Added ability to configure maximum weight for Montonio's shipping options
* Shipment's tracking code will now be available after it has been registered with provider
* Other code improvements and fixes

= 4.0.8 =
* Better Shipment measurements support

= 4.0.7 =
* Code improvements

= 4.0.6 =
* Code improvements

= 4.0.5 =
* Removed dependency while querying list of payment options which was causing problems on some installations

= 4.0.4 =
* Improvements to Montonio Shipping

= 4.0.3 =
* More hooks for custom solutions

= 4.0.2 =
* Code improvements

= 4.0.1 =
* Launched Montonio Shipping

= 4.0.0 =
* Introduced Montonio Shipping
* Improved support for multiple currency mode

= 3.0.9 =
* Finalized payments that were timed out by WooCommerce no longer get the on-hold status, they are set to processing instead

= 3.0.8 =
* Added support for WooCommerce Deposits plugin
* Code improvements

= 3.0.7 =
* Code improvements
* Made constraining Split by Shopping Cart total optional

= 3.0.6 =
* Updated Card Payment description field

= 3.0.5 =
* Bumped compatibility version

= 3.0.4 =
* Fixed small error on older WooCommerce systems

= 3.0.3 =
* Added option to always show description on top of the bank selection
* Payment instructions are now editable without WPML
* Code improvements

= 3.0.2 =
* Fixed ordering Split methods at checkout
* Restricted showing split at checkout when order total too low
* Code improvements

= 3.0.1 =
* Introduced Montonio Split

= 2.3.3 =
* Made Card Payment title translatable

= 2.3.2 =
* Removed callback and notification URL automatic redirect from https to http

= 2.3.1 =
* Added card payment as a separate payment option
* Added a note to the order about which bank was used to pay for the order

= 2.3.0 =
* Orders containing of only virtual products now get "Completed" status upon successful payment

= 2.2.2 =
* Added more translations

= 2.2.0 =
* Minor update: Added translations for [et, lv, lt, fi, ru]. Better WPML support. Removed iframe as a display option for Montonio Financing

= 2.1.1 =
* Bumped supported WooCommerce version to 5.0.0 

= 2.1.0 =
* Scalability updates

= 2.0.9 =
* Added Order Prefix configuration option to Montonio Financing

= 2.0.7 =
* Code Improvements

= 2.0.6 =
* Introduced country selection at checkout and better multistore support with order prefixes and other settings

= 2.0.5 =
* Code improvements

= 2.0.3 =
* Ability to configure Montonio Payments title and description

= 2.0.2 =
* Montonio Payment Initiation Service introduced 
* Overall code improvements

= 1.2.1 =
* Reliability upgrade

= 1.2.0 =
* Changed customer journey type to redirect by default.
* Overall Improvements

= 1.1.6 =
* Added option to change payment handle logo

= 1.1.5 =
* Code improvements

= 1.1.4 =
* Added a feature to loan calculator

= 1.1.3 =
* Bugfix

= 1.1.2 =
* Repaired loan calculator widget shortcode attributes

= 1.1.1 =
* Modified loan calculator widget data

= 1.1.0 =
* Added a loan calculator widget feature.

= 1.0.7 =
* Bugfixes

= 1.0.6 =
* Bugfixes

= 1.0.5 =
* Fix - Support WordPress installations in subdirectories

= 1.0.4 =
* White label payment handle style added for white-label customers

= 1.0.3 =
* Fix - Temporarily translate "Apply for hire purchase" to Estonian hardcoded

= 1.0.2 =
* Fix - Payment handle style !important

= 1.0.1 =
* Fix - Made js compatible with ES3

== Support ==
Any questions? Just drop us an email at support@montonio.com.
