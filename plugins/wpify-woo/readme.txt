=== WPify Woo Czech ===
Contributors: wpify, vasikgreif, mejta, martinsvoboda
Tags: WooCommerce, Czech, WPify, Zásilkovna, Heureka, IČ DIČ
Requires at least: 6.2
Tested up to: 6.4
Requires PHP: 8.0
Stable tag: 4.0.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A free plugin that adds (mainly) Czech and Slovak specific features to WooCommerce. The free version includes Packeta Shipping, Heureka Ověřeno Zákazníky, Extra CRN and VAT fields on checkout, Notification to get free shipping, Asynchronous emails sending and QR Code payments.

== Description ==

A free plugin that adds (mainly) Czech and Slovak specific features to WooCommerce. The free plugin includes:

* Packeta Shipping
* Heureka XML Feed
* Heureka Ověřeno Zákazníky
* Extra CRN and VAT fields on checkout
* Notification to get free shipping
* Emails vocative
* Asynchronous emails sending.
* QR Code Payment
* Sklik retargeting
* Zbozi.cz/Sklik Conversions Limited
* Template
* Email attachments
* Prices
* Prices log
* Comments
* Delivery dates

There are also premium modules available:

* [Gopay](https://wpify.io/cs/produkt/wpify-woo-gopay/) - Add Gopay payment gateway to your store, with payment method selection and specific gateways for every Gopay payment methof
* [Comgate](https://wpify.io/cs/produkt/wpify-woo-comgate/) - Add Comgate payment gateway to your store, with support for automatic recurring payments using WooCommerce Subscriptions
* [ThePay](https://wpify.io/cs/produkt/wpify-woo-thepay/) - Add ThePay payment gateway to your store. Works with ThePay 2.0!
* [Fakturoid](https://wpify.io/cs/produkt/wpify-woo-fakturoid/) - Automatically generate Fakturoid proforma invoices and invoices
* [Conditional shipping](https://wpify.io/cs/produkt/wpify-woo-conditional-shipping/) - Adjust shipping rates prices and visibility with rules like cart amount, products in cart, currency, user role etc.
* [DPD](https://wpify.io/cs/produkt/wpify-woo-dpd/) - Complete DPD and WooCommerce integration - create packages, print labels and add tracking links directly from admin.
* [GLS](https://wpify.io/cs/produkt/wpify-woo-gls/) - Add GLS ParcelShops selection to checkout.
* [Balíkovna](https://wpify.io/cs/produkt/wpify-woo-balikovna/) - Add Balíkovna shipping method to your store
* [Na poštu](https://wpify.io/cs/produkt/wpify-woo-balikovna/) - Add Na poštu shipping method to your store
* [Podání Online](https://wpify.io/cs/produkt/wpify-woo-podani-online/) - Batch export orders to Podání online directly from the Orders dashboard
* [Feeds](https://wpify.io/cs/produkt/wpify-woo-feeds/) - Feed generation for Google, Heureka and Zboží.cz
* [Phone validation](https://wpify.io/cs/produkt/wpify-woo-validace-telefonu/) - Display prefix selector and validate entered phone on checkout
* [Benefit Plus](https://wpify.io/cs/produkt/wpify-woo-benefit-plus/) - Add Benefit plus payment gateway to your store
* [Benefity CZ](https://wpify.io/cs/produkt/wpify-woo-benefity-cz/) - Add Benefity CZ payment gateway to your store!
* [Gallery Beta](https://wpify.io/cs/produkt/wpify-woo-gallery-beta/) - Add Gallery Beta payment gateway to your store!
* [Sodexo](https://wpify.io/cs/produkt/wpify-woo-sodexo/) - Add Sodexo payment gateway to your store
* [Smartform](https://wpify.io/cs/produkt/wpify-woo-smartform-cz/) - It whispers and auto-completes the postal address.
* [Zbozi.cz conversion tracking](https://wpify.io/cs/produkt/wpify-woo-konverze-zbozi-cz/) - Track Zbozi.cz conversions
* [Vivnetworks affiliate tracking](https://wpify.io/cs/produkt/wpify-woo-vivnetworks-affiliate/) - Tracking for Vivnetworks Affiliate
* [SmartEmailing](https://wpify.io/cs/produkt/wpify-woo-smartemailing/) - Connection to the newletter service with the possibility of subscribe and tracing

The plugin is built for speed. Only the enabled modules are loaded, scripts are lazy-loaded only on the needed pages, and the number of database queries is limited to the bare minimum.

The plugin is brought to you by Václav Greif and Daniel Mejta, the WordPress and WooCommerce experts at [wpify.io](https://wpify.io).

## Features

The plugin includes the following modules:

### Packeta Shipping

* A new shipping method Packeta shipping.
* Select the Packeta branch on checkout.
* Automatically display branches by the selected country on checkout.
* Send orders to Packeta directly from the order admin.
* Batch generate Packeta labels.
* Replace the shipping address with Packeta branch address.
* Select the payment gateways that you want to charge COD payment for.

### Heureka XML Feed

* Generate valid Heureka XML feed
* Map WooCommerce Categories to Heureka categories
* Choose delivery methods and prices
* Choose delivery date
* Cron URL to re-generate the feed
* Generates feed in chunks to prevent issues with memory limit and server timeouts

### Heureka Ověřeno zákazníky

* Automatically send order to Heureka Ověřeno zákazníky.
* Optionally show optout checkbox at checkout.
* Show Heureka Ověřeno zákazníky widget.

### Heureka Měření konverzí

* Ad Heureka Měření konverzí to thank you page.

### Extra CRN and VAT fields on checkout

* Add CRN and VAT number fields to the checkout, WooCommerce admin and emails.
* Validate the entered CRN using ARES database.
* Validate the entered VAT no using VIES database.
* Autofill the company details by the entered CRN number from ARES.
* Move the Company field to the end of the form.
* Move the VAT fields under the Company field at the top of the checkout form.
* Show checkbox "Enter company details" to reveal the company fields only if needed.
* Option to display narrowed VAT fields side by side.
* Option to require an Company field when the "Enter company details" is checked.
* Option to require an identification number when purchasing for a company (In the case of a Slovak company, the VAT number field is also required).
* Show prices without VAT for subjects with valid VAT number from selected countries.

### Notification to get free shipping

* Display notification "Buy for xxx more to get free shipping" at various places in the store.
* Change the message as you need.
* A shortcode to display the widget anywhere.
* Change background and text colours.
* Display a progress bar and change its colour.
* Option to load min amount from WooCommerce Free shipping settings.

### Emails vocative

* Automatically change the salutation in emails to use correct Czech vocative

### Asynchronous emails sending

* Send WooCommerce emails asynchronously using Action Scheduler to speed up the checkout processing.

### QR Code Payment

* Display the QR code on the selected position on the thank you page.
* Display the QR code in the selected email notification.
* QR codes according to the QR Platba standard for CZ and Pay By Square pr SK.
* Rendering QR using an integrated library or external API.
* Supports any payment method.
* Supports any currency.
* Option to insert a note into the QR payment with the order number and store name.
* Option to add any text before and after the QR code.
* Option to limit QR code display by billing country.

### Sklik retargeting

* Option to sending data on the basis of allowing marketing cookies.
* Option to add an e-shop offer identifier also from a custom field.
* Option to add a category identifier from a custom field or automatically loaded from the premium [WPify Woo Feeds](https://wpify.io/cs/produkt/wpify-woo-feeds/) plug-in.

### Zbozi.cz/Sklik Conversions Limited

* Adding frontend conversion code for Sklik or Zboží.cz or both together.
* Option to restrict the data sent by allowing marketing cookies.

### Template

* Option to change the label of the button to send the order.
* Add custom notifications to cart, checkout or any place in the template.

### Email attachments

* Add any attachments to any Woocommerce emails.
* Add any attachments to emails for individual products.
* Option to restrict attachments to specific countries only.

### Prices

* Add info to default prices with the possibility of conditions according to stock status.
* Add custom prices with label, more info, label for default price and product badge.
* Multicurrency from plugin WooCommerce Multilingual & Multicurrency supported.

### Prices log

* Log history of product prices whenever they change.
* Display the lowest price recorded in the last 30 days below the product price field.

### Comments

* Add option to set custom labels to product reviews.

### Delivery dates

* Add option to display delivery dates in product detail.
* Add option to display available payment methods in product detail.
* Possibility to set days for different stock states.
* Option to change settings for each product.
* Option to insert multiple delivery dates.
* Option to add more information on the delivery date.
* Option to display specific shipping methods for delivery dates.


== Installation ==

1. Upload `wpify-woo` folder to the `/wp-content/plugins/` directory or install the plugin from the WordPress plugin repository.
1. Activate the plugin through the "Plugins" menu in WordPress.
1. Go to the administration area > WooCommerce > Settings > WPify Woo.
1. Enable and configure modules.

If you have problems installing, activating or setting up modules, please refer to our [documentation](https://wpify.io/cs/knowledge-base/wpify-woo/).

== Frequently Asked Questions ==

= Do you have documentation for the plugin? =

Yes, the full documentation for the WPify Woo plugin is available on the website [wpify.io](https://wpify.io/cs/knowledge-base/wpify-woo/)

= Why did you create this plugin? =

Our plugin's functionality is (mostly) covered by other plugins, but during the years using these we encountered many issues and bugs.

That's why decided to write our own, highly optimized plugin and offer the basic version with the essential features for free.

= Why do you offer this for free? =

We believe it shouldn't be hard to get WooCommerce store up and running in the Czech environment. For that, we offer the essential features in this free plugin, and also provide premium addons for even more functionality.

= Why do you support WordPress 6.2+ only? =

We take advantage of the new WP features, and we strive to use modern development practices, which was not possible in the previous versions of WordPress.

= Why do you support PHP 8.0 and higher only? =

We support only actively supported versions to be sure, that our code is secure from the bottom up. It's also essential to have the PHP version regularly updated, co you can be sure that your e-shop is safe and fast.

= I need feature XYZ, what should I do? =

We are continually working on adding new modules - we will add some of them to the basic version, some will be available as paid addons. You can also use the framework to write your features, or [contact us](https://wpify.io) to write the module for you.

= I found a bug, what should I do? =

Drop us a message in the support section, or feel free to submit a pull request in the [plugin repository](https://gitlab.com/wpify/wpify-woo).

= Who is behind the plugin? =

This plugin is brought to you by the WordPress and WooCommerce experts at [wpify.io](https://wpify.io).

== Changelog ==
= 4.0.9 =
* Add capability check to `maybe_send_to_packeta` method

= 4.0.8 =
* Fix display delivery dates without customer data

= 4.0.7 =
* Fix VIES validation for SK

= 4.0.6 =
* Add filter for adjusting the Ares details

= 4.0.5 =
* Add setting for custom Ares text

= 4.0.4 =
* Update dependencies

= 4.0.3 =
* Trigger JS event after Ares autofill to allow other plugins to react

= 4.0.2 =
* Fix display Ares button without "I'm shopping for a company" checkbox

= 4.0.1 =
* Fix IČ validation only for czech billing address
* Fix Free shipping notice after cart update

= 4.0.0 =
* Require PHP 8
* Require WordPress >= 6.2
* Require WooCommerce >= 7.0
* Add new Ares implementation

= 3.9.19 =
* Fix Free shipping notice if Free shipping min amount is 0
* Add woocommerce_after_variations_form hook to Delivery dates

= 3.9.18 =
* Fix hiding company fields
* Declare HPOS support

= 3.9.17 =
* VAT validation fix.

= 3.9.16 =
* Prices log fix.

= 3.9.15 =
* IČO and DIČ validation JavaScript refactoring.

= 3.9.14 =
* Fix render QR code shortcode

= 3.9.13 =
* Add option to select position of QR code in emails
* Fix individual delivery date settings in products

= 3.9.12 =
* Fix DIČ validation

= 3.9.11 =
* Fix DIČ validation

= 3.9.10 =
* Add info label option for each QR code
* Fix currency check for multiple accounts to generate QR codes

= 3.9.9 =
* Validate the DIČ / IČ DPH number after finish typing in the field.
* Fix Price log warning when creating new product

= 3.9.8 =
* Update models

= 3.9.7 =
* Add automatic correction of the DIČ number format

= 3.9.6 =
* Add creating database table if not exists before saving price log

= 3.9.5 =
* Fix validation DIČ number format in checkout

= 3.9.4 =
* Add removal of unwanted characters in IČ DIČ numbers
* Add spinner when validating IČ in ARES or DIČ in VIES
* Add [wpify_woo_delivery_dates] shortcode for displaying Delivery dates
* Fix trigger validation IČ DPH in VIES after changing country
* Fix showing payment message in Delivery date
* Fix get translated date format in Delivery date

= 3.9.3 =
* Add `wpify_woo_delivery_dates_payments_data` filter for payment message and more info text
* Add option to validate IČ DIČ number format in checkout
* Fix load customer IČ DIČ in admin
* Fix Packeta admin notice as dismissible
* Fix Free shipping notice if cart is empty

= 3.9.2 =
* Fix duplicate data upload to Heureka overeno zakazniky by saving meta to order

= 3.9.1 =
* Fix converting data in delivery date settings

= 3.9.0 =
* Add Price module
* Change the data storage of the delivery date settings for products. Data needs to be converted.
* Add option to add title for delivery dates block
* Add option to add label for country selector in delivery dates
* Edit hooks for the position of the delivery date

= 3.8.5 =
* Fix delivery dates on backorder stock status

= 3.8.4 =
* Add option hide delivery date adding "-" into field
* Add option insert HTML into Order button text
* Fix error display log on new product

= 3.8.3 =
* Add order note about agreeing to send the Heureka satisfaction questionnaire
* Add Wpify Woo News
* Fix WPML default language for Heureka category
* Don't render delivery date line if message is empty

= 3.8.2 =
* Fix time by timezone in delivery date
* Fix PHP 7.4 compatibility

= 3.8.1 =
* Fix display shipping methods if isn't set more info text in delivery date
* Fix display delivery dates if isn't set default delivery country
* Fix display delivery dates if isn't any methods set
* Fix skip weekends after bridging time to next day in delivery date

= 3.8.0 =
* Add Prices log module
* Add Comments module
* Add Delivery dates module

= 3.7.5 =
* Change Enter company details button label to I'm shopping for a company

= 3.7.4 =
* Fix render QR core in emails

= 3.7.3 =
* Fix error with Free shipping notice rendering by shortcode
* Fix error with QR core rendering in emails
* Fix render QR core just for specific payment method in emails

= 3.7.2 =
* Fix get attachments from product meta

= 3.7.1 =
* Add option to sending to Heureka asynchronously
* Better sending data to Heureka using wp_request

= 3.7.0 =
* Add Template module
* Add Email attachments module
* Add QR code alt text
* Add option to load min amount from WooCommerce Free shipping settings for Notification to get free shipping.

= 3.6.2 =
* Fix checkout VAT exempt

= 3.6.1 =
* Fix VAT exempt in IČ DIČ module

= 3.6.0 =
* Add Sklik retargeting module
* Add Zbozi.cz/Sklik Conversions Limited module
* Add variables {order} and {total} into texts before and after QR code
* Improve - Checking the checkbox Enter company details if the VAT number is filled in

= 3.5.18 =
* Fix load Packeta metabox if module is not active

= 3.5.17 =
* Fix QR amount in compatibility mode

= 3.5.16 =
* Fix product item ID in Heureka Mereni Konverzi

= 3.5.15 =
* Fix Qr codes in compatibility mode

= 3.5.14 =
* Fix loading settings in IC DIC module
* Fix - Don't render QR code if is not set payment methods

= 3.5.13 =
* Fix loading settings
* Fix set default values in settings

= 3.5.12 =
* Add support to set IBAN with spaces in QR payment module
* Fix Fatal error on frontend and add logging errors in QR payment module

= 3.5.11 =
* Remove link to the cancelled plugin Email Builder from Vocative module

= 3.5.10 =
* Fix Error of set_is_vat_exempt()

= 3.5.9 =
* Fix vat exempt if VAT number is valid

= 3.5.8 =
* Add notice about removing Packeta module.

= 3.5.7 =
* Security fix.

= 3.5.6 =
* Fix displaying separate heading with empty Packeta address.

= 3.5.5 =
* Fix Heureka category selector
* Fix IC DIC module collision with WooCommerce Stripe Gateway plugin.

= 3.5.4 =
* Revert fix loading Heureka categories in settings due to Allowed memory exhausted

= 3.5.3 =
* Add filter `wpify_woo_add_ic_dic_to_address` to option to disable data replacement in the address
* Fix label field in Heureka Conversion settings

= 3.5.2 =
* Add option to replace placeholder for VAT fields with example of how to fill the field
* Fix inserting QR code to email after save as image
* Fix - Do not insert QR in plain text emails

= 3.5.1 =
* Fix loading Heureka categories in settings

= 3.5.0 =
* Update custom fields
* Fix error with GuzzleHtttp
* Fix duplicate display of VAT fields in order detail
* Fix warning: Undefined array key “post_type” on PHP 8.0
* Fix label for Search in ARES button after IC field

= 3.4.5 =
* Fix update free shipping notice on checkout page.

= 3.4.4 =
* Fix QR payment if note is empty
* Fix display of Packeta address on thank you page
* Fix duplicate display of Packeta address when delivery address is replaced

= 3.4.3 =
* Add CSS classes to QR elements
* Add the ability to choose the position of the QR code on the thank you page.
* Add the option to choose in which emails the QR code will be displayed.
* Add option to limit QR code display by billing country.
* Add option save the QR code as an image and link to it instead of base64.
* Add the option to allow the order to be sent even if the VAT number does not pass the VIES validation, but does not apply zero VAT.
* Remove of required DIČ field for SK due to conflict with non-profit organizations.
* Fix error if zero tax countries is empty on PHP 8.1

= 3.4.2 =
* Add the ability to insert a note into the QR payment with the order number and store name.
* Fix warning if bank code for QR is not set.

= 3.4.1 =
* Add hook for rendering QR code - `do_action('wpify_woo_render_qr_code', $order_id)`

= 3.4.0 =
* New feature - Generate payment QR code embedded in thank you page and email.
* Fix VAT fields error if company field is hidden in WooCommerce.

= 3.3.52 =
* Add filter `wpify_woo_free_shipping_render_notice` for possible conditional free shipping notice rendering
* Add filter `wpify_woo_heureka_add_optout` for possible conditional Heureka opt-out display
* Add filter `wpify_woo_heureka_render_widget` for possible conditioning of Heureka widget rendering
* Fix in validation of VAT fields

= 3.3.51 =
* Fix error on checkout if "Require entering identification number field" is not set.
* Fix identification number marking as required only in selected cases.
* Fix hide VAT fields if checkbox is unchecked on load checkout.
* Add - If an identification number is required, a DIC number is also required for Slovak companies.
* Add option to require an Company field when the "Enter company details" is checked.

= 3.3.50 =
* Fix show IČ DPH for SK when switch countries.
* Add option to display Vat fields under the Company field at the top of the checkout form.
* Add option to display narrowed VAT fields side by side in the checkout.
* Add option to require an identification number when purchasing for a company.

= 3.3.49 =
* Fix dismiss the notice

= 3.3.48 =
* Removing sentry dependency
* Add Packeta logo and icon
* Move the logo display settings. Now the logo is selected in the shipping method settings for each shipping zone.
* Update external shipping methods to the customer's address.

= 3.3.47 =
* Fix in Core - get VAT rate for Fakturoid

= 3.3.46 =
* Add class vat-extempt to order lines on backend

= 3.3.45 =
* Fix checking active WooCommerce plugin within multisite

= 3.3.44 =
* Add option to choose from which shopping cart amount is calculated for free shipping notice.

= 3.3.43 =
* Add editable in vat billing fields in user profile
* Fix - Check box Company details is checked in checkout by default if the user has already pre-filled Identification no.
* Fix - Automatic deletion of company data if the check box Company details in the checkout is unchecked.

= 3.3.42 =
* Free Shipping notice compatibility with Woo Currency Switcher

= 3.3.41 =
* Change when an order is sent to Heureka Ověřeno zákazníky

= 3.3.40 =
* Fix of the link to the detail of the Packeta branch in order admin. Link to open in a new window.
* Fix hiding Packeta branch address when editing delivery address in order admin.

= 3.3.39 =
* Add editable in vat billing fields in order admin
* The minimum supported PHP version is 7.4

= 3.3.38 =
* Fix PHP 7.3 compatibility

= 3.3.37 =
* Update Packeta SDK, add method for uploading a document

= 3.3.36 =
* Add non-generation of DELIVERY_PRICE_COD tag in Heureka XML feed if it is not filled in delivery method.

= 3.3.35 =
* Update allowed carriers for Heureka XML feed

= 3.3.34 =
* Add option round up the total amount sent to Packeta for COD payment method
* Add documentation links

= 3.3.33 =
* Fix update the checkout after filling IN VAT no. field.

= 3.3.32 =
* Add house number to Packeta

= 3.3.31 =
* Add attributes support for Packeta

= 3.3.30 =
* Fix in core

= 3.3.29 =
* Fix vat rate calculation

= 3.3.28 =
* Fix license on some environments

= 3.3.27 =
* Add filter for number of generated XML products per one run

= 3.3.26 =
* Add better compatibility with Wpify Woo Core

= 3.3.25 =
* Add compatibility with Wpify Woo Core

= 3.3.24 =
* Fix address substitution for delivery to an address

= 3.3.23 =
* Fix Packeta form displaying

= 3.3.22 =
* Fix packeta metabox id

= 3.3.21 =
* Fix undefined notice

= 3.3.20 =
* Added Packeta information and actions to order list
+ Added option to adjust weight before sending shipment to Packeta

= 3.3.19 =
* Fix PHP8 PSR Log

= 3.3.18 =
* Temporarily Remove PHP8 compatibility

= 3.3.17 =
* Fix loading of settings assets

= 3.3.16 =
* Fix of the License option type

= 3.3.15 =
* Set Packeta price to float

= 3.3.14 =
* Upgrade WPify Custom Fields

= 3.3.13 =
* Fix the autofill of company details on checkout

= 3.3.12 =
* Upgrade WPify Custom Fields

= 3.3.11 =
* Add support for multi-domain WPML license setup

= 3.3.10 =
* Upgrade WPify Custom Fields to fix the problems with browser cache

= 3.3.9 =
* Add compatibility with sentry

= 3.3.8 =
* Fix order model

= 3.3.7 =
* Fix options two levels groups

= 3.3.6 =
* Remove Packeta widget v5 support
* Add WP CLI

= 3.3.5 =
* Add wpify_woo_amount_for_free_shipping shortcode

= 3.3.4 =
* Fix setting of the colors in free shipping notice

= 3.3.3 =
* Fix support for Billing DIC SK

= 3.3.2 =
* Add automatic sending to Packeta on order status change

= 3.3.1 =
* Add premium modules

= 3.3.0 =
* Add default package value if order price is zero to Packeta
* Fix the Zasilkovna label in order detail

= 3.2.11 =
* Exclude backorder products from Heureka XML feed.

= 3.2.10 =
* Fix compatibility

= 3.2.9 =
* Send orders to Heureka Overeno Zakazniky right away, not in cron

= 3.2.8 =
* Implement vocative exceptions

= 3.2.7 =
* Add "After IC" position for autofill button from Ares

= 3.2.6 =
* Show free shipping message if the cart amount is equal to free shipping amount

= 3.2.5 =
* Add error handling when printing Packeta labels

= 3.2.4 =
* Add download log link

= 3.2.3 =
* Fixed ARES validation at checkout for countries other than the Czech Republic

= 3.2.2 =
* Heureka XML - get description for variation from parent product when the variation description is empty

= 3.2.1 =
* DPH fields fix - labels not shown in certain situations

= 3.2.0 =
* Slovakia IN DPH field on checkout
* Asynchronous loading of javascripts on backend

= 3.1.7 =
* Add possibility to delete Heureka category

= 3.1.6 =
* More XML Heureka categories fixes

= 3.1.5 =
* Fix of XML Heureka categories

= 3.1.4 =
* Update contributors list
* Logo update

= 3.1.3 =
* Fix - loading of heureka categories - memory exhausted

= 3.1.2 =
* Fix - Save packeta branch info to session

= 3.1.1 =
* Fix lists

= 3.1.0 =
* Fix async list types in XML Feed Heureka
* New custom fields implementation (https://gitlab.com/wpify/wpify-custom-fields/)

= 3.0.6 =
* Set backorder items as out of stock in Heureka XML

= 3.0.5 =
* Added support for SK Heureka tracking

= 3.0.4 =
* Heureka XML - Fix price including VAT

= 3.0.3 =
* Added ThePay and DPD addons

= 3.0.2 =
* Add encoding flag to XML feed

= 3.0.1 =
* Exclude variations from Heureka XML if set up

= 3.0.0 =
* Add SK delivery to Heureka XML
* Bug Fixes

= 2.9.9 =
* Add CURL fallback for downloading Heureka XML

= 2.9.8 =
* Add setting to always show the cart notification bar

= 2.9.7 =
* Add support for Heureka categories for product variations

= 2.9.6 =
* Add DPD premium addon

= 2.9.5 =
* Fix build

= 2.9.4 =
* Change save options capability to `manage_woocommerce`

= 2.9.3 =
* Added support for last name and full name in the Vocative module
* Switched to heureka/inflection library in the Vocative module

= 2.9.2 =
* Added Email builder addon

= 2.9.1 =
* Setting and filter for logo type

= 2.9.0 =
* Packeta widget v6 support

= 2.8.10 =
* Add support for Packeta API v6

= 2.8.9 =
* Add basic support for WPML and Heureka Categories

= 2.8.8 =
* Fix the update checker

= 2.8.7 =
* Add option to set free shipping notice if any of the shipping methods is free

= 2.8.6 =
* Add Comgate to plugins with compatibility check

= 2.8.5 =
* Add Packeta table style checkout compatibility

= 2.8.4 =
* Fix composer platform requirements

= 2.8.3 =
* Fix filter name

= 2.8.2 =
* Add Heureka SK categories
* Add filter `wpify_woo_settings` for getting a setting

= 2.8.1 =
* Fix composer platform requirements

= 2.8.0 =
* The plugin dependencies are scoped so it doesn't collide with other plugins.
* New plugin logo

= 2.7.3 =
* Improve select of the long options list in settings

= 2.7.2 =
* Add option to display Packeta logo

= 2.7.1 =
* Fix memory leak

= 2.7.0 =
* Add external carriers for Packeta
* Minor bug fixes

= 2.6.5 =
* Support no shipping country on Packeta checkout

= 2.6.4 =
* Fix Heureka Feed category

= 2.6.3 =
* Bug fixes

= 2.6.2 =
* Catch SoapFault Exception in Vies

= 2.6.1 =
* Migrate licensing and addonds from wphelp.cz to wpify.io

= 2.6.0 =
* Add `wpify_woo_free_shipping_amount` filter

= 2.5.9 =
* Clear Packeta details if no branch selected on checkout

= 2.5.8 =
* Fix Heureka XML Cron generation

= 2.5.7 =
* Raise timeout for licence checking

= 2.5.6 =
* Add possibility to export logs from admin

= 2.5.5 =
* Add check for unique ID to the Packeta Feed

= 2.5.4 =
* Fix Packeta Feed chunk generation
* Add logging to license revalidation

= 2.5.3 =
* Fix Packeta JS error on checkout on specific installations

= 2.5.2 =
* Fix Packeta Feed

= 2.5.1 =
* Add Comgate premium addon
* Fix PHP notices and warnings

= 2.5.0 =
* Add temporary solution for custom fields for Heureka XML

= 2.4.9 =
* Add weight setting for Packeta
* Send order weight to Packeta

= 2.4.8 =
* Fix Packeta fatal error on create order screen

= 2.4.7 =
* Added more premium add-ons details
* Bug fixes

= 2.4.6 =
* Async Emails bug fixes

= 2.4.5 =
* Add details to Packeta log

= 2.4.3 =
* Move Phone validation to separate plugin because of PHP8 conflict

= 2.4.2 =
* Fix Packeta bulk generate
* Fix Packeta filename

= 2.4.1 =
* Fix undefined notice on new post screen

= 2.4.0 =
* New feature - Phone validation
* Fixes

= 2.3.4 =
* Fix Heureka XML ITEMGROUP_ID typo

= 2.3.3 =
* Add more Heureka XML Feed settings
* Heureka XML Feed fixes

= 2.3.2 =
* Fix API callback PHP notice

= 2.3.1 =
* Move settings in Heureka feeds

= 2.3.0 =
* Added abstraction for XML feeds
* Added Heureka XML Feed

= 2.2.7 =
* Add setting for minimum cart price to display the shipping notice

= 2.2.6 =
* Fix sender name and email when using Async emails

= 2.2.5 =
* Optimize saving of Packeta details

= 2.2.4 =
* Add Packeta pickup point link to emails and admin

= 2.2.3 =
* Fix Packeta widget language
* Update readme

= 2.2.2 =
* Update WooCommerce compatibility to 5.0

= 2.2.1 =
* Fix move company IC / DIC checkbox

= 2.2.0 =
* CI fix, version bump

= 1.2.21 =
* Fix JS Build

= 1.2.20 =
* Fix JS Build

= 1.2.19 =
* Fix IC validation on checkout

= 1.2.18 =
Fix Packeta on SK shops

= 1.2.17 =
* Update Settings page

= 1.2.16 =
* Fix Packeta translations

= 1.2.12 =
* Added plugin icon

= 1.2.11 =
* Added vocative module - automatically change the salutation in emails to use correct Czech vocative

= 1.2.10 =
* Added group field option in settings (internal change for usage by WPify Woo and its extensions)

= 1.2.9 =
* Fix Packeta API

= 1.2.8 =
* Added Heureka Měření konverzí

= 1.2.7 =
* Fix loading of Packeta settings

= 1.2.6 =
* Add React select for settings

= 1.2.5 =
* Fix of assets paths

= 1.2.4 =
* VAT extempt fix

= 1.2.0 =
* Added VAT validations using VIES

= 1.1.3 =
* Readme update

= 1.1.2 =
* ARES reimplementation

= 1.1.1 =
* Bug fixes

= 1.1.0 =
* Bug fixes

= 1.0.0 =
* Initial version
