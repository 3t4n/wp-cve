=== Swiss QR Bill ===
Contributors: swissplugins
Donate link:
Tags: woocommerce, swiss, switzerland, qr, qr bill, qr invoice, qr code, bill, billing, invoice, invoicing
Requires at least: 4.6
Tested up to: 5.8
Requires PHP: 7.0
Stable tag: 1.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Swiss QR Bill extends WooCommerce with a new payment method, allowing you to easily send automated and standardized Swiss QR bills to your clients.

== Description ==

**Swiss QR Bill for WooCommerce** introduces a new payment method for shops located in Switzerland or Liechtenstein, following the harmonization of Swiss payments based on the ISO 20022 standard in the implementation of the new Swiss QR Bill (see [paymentstandards.ch](https://www.paymentstandards.ch/en/)).

The Swiss QR bill can be used since 30 June 2020, it replaces the old Swiss "ESR" payment slips.

As a shop owner you can either use a new QR-IBAN number for your bank account, or a normal IBAN number, to send your clients valid Swiss QR bills, depending on what your bank provides you with.

You may consult this page for further information: [The Swiss QR-bill](https://www.paymentstandards.ch/en/home/companies.html).

### REQUIREMENTS

This plugin requires a working installation of **WordPress** with everybody's favorite shop plugin **WooCommerce**. It also requires you to enter a correct QR-IBAN or IBAN, depending on the version you activate. Furthermore, the PHP extension "intl" must be activated in your web hosting account. Please ask your host for yassistance if it is not activated.

### LIMITATIONS

The plugin only works for shop owners in **Switzerland and Liechtenstein**. If your shop country is not set to one of these two countries then the plugin cannot be activated.

Your clients must also reside in Switzerland and Liechtenstein to issue Swiss QR bill payments to you. The payment method will not be displayed in the checkout to users from any other countries.

The only currencies supported by Swiss QR bill payments are **CHF and EUR**. If the plugin is activated, the Swiss QR bill payment method will only be displayed in the checkout for orders with these two currencies.

### FUNCTIONALITY

1. New "Swiss QR bill" **payment method**, which you can enable and disable like any other payment method. You can either activate the version with QR-IBAN (replacing the old orange ESR), or the version with a normal IBAN (old red ESR). When activated, the payment method offers you several options (see below).
2. The plugin automatically generates a valid Swiss QR bill **PDF document** for every order that uses the Swiss QR bill payment method. The QR code section is always displayed on the bottom of the first page of the invoice PDF. If there are many order items the plugin will automatically add a second and further pages to the invoice PDF as required.
3. The PDF invoice document is automatically **attached to the order confirmation email** which is sent by WooCommerce to the user immediately upon order completion.
4. As a shop owner you can **review and download** the PDF invoice document anytime in the WooCommerce order screen.
5. If you use the QR IBAN then it will automatically be **validated for correctness** after you enter it.
6. You may optionally choose to **restrict** the Swiss QR bill payment method to registered users or to registered users who have completed at least one order previously.
7. You may also restrict the Swiss QR bill payment method by **product category**.

We have various features in mind for the next versions. We are open to your suggestions.

### PLUGIN SETTINGS

The Swiss QR bill payment method offers the following custom settings:

#### Main settings

* **QR-IBAN** or **IBAN** (enter the QR-IBAN or the IBAN provided by your bank, depending on the version of the payment method you have activated)
* **Reference number** (enter the reference number provided by your bank)

#### Invoice data

You can customize the following data on your Swiss QR bills:

* Shop Logo
* Shop Name
* Shop Street & Number
* Shop Address Line 2
* Shop Zip Code
* Shop City
* Shop telephone
* Shop VAT number

When the plugin is activated for the first time, most of these fields will be filled in automatically from your WooCommerce settings. You are free to edit them as you prefer.

#### Optional Restrictions

* **Customer Account Restriction**: If activated, the Swiss QR bill payment method will only be displayed to registered and logged in users.
* **Customer Order Restriction**: If activated, the Swiss QR bill payment method will only be displayed to logged in users who have completed at least one previous order.
* The **product category restriction** can be activated in the settings of each product category. If you disable the checkbox "Activate Swiss QR bill payments" for a category then the Swiss QR bill payment method will not be displayed if the cart contains at least one product from that category.

#### Translations

The plugin is available in English and completely translated into German and French. An Italian translation will follow.

The usage of this plugin is totally free, and the basic version will always remain free. Currently there is no limitation on the amount of Swiss QR bills to be generated and sent to your clients every month.

== Installation ==

### MINIMUM REQUIREMENTS

* WordPress 4.6 or newer (latest version recommended)
* WooCommerce 2.6 or newer (latest version recommended)
* PHP 7.0 or newer (latest version recommended, the plugin supports PHP 7.4)
* PHP extension "intl" activated in your webhosting account

### AUTOMATIC INSTALLATION

We recommend installing and activating the Swiss QR Bill for WooCommerce plugin through the WordPress backend. Please install WooCommerce before installing our plugin.

### PAYMENT METHOD ACTIVATION

After activating the plugin please navigate to WooCommerce - Settings - Payments and **enable** either the "Swiss QR Bill for WooCommerce WITH QR-IBAN" or the "Swiss QR Bill for WooCommerce WITHOUT QR-IBAN" payment method, depending on what your bank provides you with.

### PAYMENT METHOD SETUP

After enabling the payment method please click on "Manage" to **review and edit all its settings**. Please make sure to enter the correct QR-IBAN or IBAN number provided by your bank, otherwise the plugin will not work.

== Changelog ==
= 1.2.4 =
* fix: company name and address line 2 display in invoice PDF.
* fix: order item length restriction to one line.
* fix: payment instruction email text only with customer on-hold email.

= 1.2.3 =
* change: default order status to on-hold.

= 1.2.2 =
* fix: few warning fixes.

= 1.2.1 =
* fix: translation fixes

= 1.2.0 =
* add: QR bill payment method without QR-IBAN
* change: PDF invoice attachment name
* remove: language files
* add: compatibility check with WC 5.2.2

= 1.1.3 =
* fix: remove empty attachment on order email
* add: compatibility check with WC 5.1.0

= 1.1.2 =
* remove: Customer identification number required validation

= 1.1.1 =
* fix: Additional information adjusted

= 1.1.0 =
* add: shop logo relative path.
* fix: compatibility with sequential order number.
* fix: dimension fixes for QR payment invoice part .
* add: compatibility check with WC 4.9.1 .
* remove: footer text.

= 1.0.3 =
* fix: invoice logo resize cutoff.
* add: french date format in invoice.
* add: compatibility with sequential order number.
* fix: product category gateway restriction.

= 1.0.2 =
* fix: customer session check in backend.
* fix: store location country update.

= 1.0.1 =
* Text domain fixes.

= 1.0.0 =
* Initial push.
