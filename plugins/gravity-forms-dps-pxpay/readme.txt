# GF Windcave Free
Contributors: webaware
Plugin Name: GF Windcave Free
Plugin URI: https://wordpress.org/plugins/gravity-forms-dps-pxpay/
Author: WebAware
Author URI: https://shop.webaware.com.au/
Donate link: https://shop.webaware.com.au/donations/?donation_for=Gravity+Forms+DPS+PxPay
Tags: gravity forms, windcave, dps, payment express, pxpay, donations, payment, payment gateway, ecommerce
Requires at least: 4.9
Tested up to: 6.4
Stable tag: 2.5.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily create online payment forms with Gravity Forms and Windcave (DPS Payment Express) PxPay

## Description

Easily create online payment forms with Gravity Forms and Windcave (DPS Payment Express) PxPay

GF Windcave Free integrates the [Windcave hosted payment page](https://www.windcave.com/merchant-ecommerce-hpp) (PxPay 2.0) with [Gravity Forms](https://webaware.com.au/get-gravity-forms) advanced form builder.

* build online donation forms
* build online booking forms
* build simple Buy Now forms

> NB: this plugin extends Gravity Forms; you still need to install and activate [Gravity Forms](https://webaware.com.au/get-gravity-forms)!

### Sponsorships

* creation of this plugin was generously sponsored by [IstanbulMMV](https://profiles.wordpress.org/IstanbulMMV/profile/)

Thanks for sponsoring new features on GF Windcave Free!

### Requirements

* Install the [Gravity Forms](https://webaware.com.au/get-gravity-forms) plugin
* [Create an account with Windcave](https://sec.windcave.com/pxmi3/signup) for the hosted payment page (PxPay 2.0)

### Privacy

Information gathered for processing a credit card transaction is transmitted to Windcave for processing, and in turn, Windcave passes that information on to your bank. Please review [Windcave's Privacy Policy](https://sec.windcave.com/pxmi3/privacy-policy) for information about how that affects your website's privacy policy. By using this plugin, you are agreeing to the terms of use for Windcave.

## Frequently Asked Questions

### What is Windcave PxPay?

Windcave PxPay 2.0 is a hosted Credit Card payment gateway, accepting payments around the world including New Zealand, Australia, North America, United Kingdom, Ireland, and Singapore.

### Windcave, Payment Express, DPS?

In 2019, Payment Express (DPS) became Windcave. Read the [Windcave rebranding announcement](https://www.windcave.com/rebranding-details) for more information.

### Will this plugin work without installing Gravity Forms?

No. This plugin adds a Windcave PxPay payment gateway to Gravity Forms so that you can add online payments to your forms. You must purchase and install a copy of the [Gravity Forms](https://webaware.com.au/get-gravity-forms) plugin too.

### What Gravity Forms license do I need?

Any Gravity Forms license will do. You can use this plugin with the Basic, Pro, or Elite licenses.

### How do I build a form with credit card payments?

* add one or more Product fields or a Total field to your form. The plugin will automatically detect the values assigned to these pricing fields
* add customer name and contact information fields to your form. These fields can be mapped when creating a Windcave feed
* add a Windcave feed, mapping your form fields to Windcave transaction fields

### What is the difference between Normal and Testing (Sandbox) mode?

GF Windcave Free enables you to store two pairs of User ID and User Key credentials. When you first signup for a PxPay account with Windcave you will likely be issued development or testing credentials. Later, when you want to go live with your site, you will need to request a new User ID and User Key from Windcave. Sandbox mode enables you to switch between your live and test credentials. If you only have testing credentials, both your User ID and Test ID and User Key and Test Key should be identical. In this instance, Sandbox mode can be switched either On or Off.

Sandbox mode enables you to run tests without using real credit cards or bank accounts. You must use special test credit card details when using the test environment.

### Where can I find dummy Credit Card details for testing purposes?

The [Windcave eCommerce test details](https://www.windcave.com/support-merchant-frequently-asked-questions-testing-details) page has card numbers that can be used when testing.

### Where will the customer be directed after they complete their Windcave Credit Card transaction?

Standard Gravity Forms submission logic applies. The customer will either be shown your chosen confirmation message, directed to a nominated page on your website, or sent to a custom URL.

### Where do I find the Windcave transaction number?

Successful transaction details including the Windcave transaction number and bank authcode are shown in the Info box when you view the details of a form entry in the WordPress admin.

### How do I add a confirmed payment amount and transaction number to my Gravity Forms admin or customer email?

Browse to your Gravity Form, select [Notifications](https://www.gravityhelp.com/documentation/article/configuring-notifications-in-gravity-forms/) and use the Insert Merge Tag dropdown (Payment Amount, Transaction Number, Surcharge, and Auth Code will appear under Custom at the very bottom of the dropdown list).

NB: these custom merge tags will only work for notifications triggered by Payment Completed and Payment Failed events.

### How do I change my currency type?

Use your Gravity Forms Settings page to select the currency type to pass to Windcave. Please ensure your currency type is supported by Windcave.

### Capture or Authorize?

Windcave PxPay supports two transaction types - Purchase and Auth. The GF Windcave Free plugin calls them Capture and Authorize, terminology used in most other payment integrations.

Capture processes the payment immediately. Authorize holds the amount on the customer's card for processing later.

Authorize transactions can be completed manually in Payline. Perform a transaction search, and look for its Complete button.

### Can I do recurring payments?

Not yet.

### Can I use Account2Account?

Account2Account debits a bank account directly, and Windcave have told me that they cannot provide a full test environment for me to test in. Merchants have told me that it works, but I have not tested it.

### I get an SSL error when my form attempts to connect with Windcave

This is a common problem in local testing environments. Read how to [fix your website SSL configuration](https://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/).

### Can I use this plugin on any shared-hosting environment?

The plugin will run in shared hosting environments, but requires the following PHP modules enabled (talk to your host). Both are typically available because they are enabled by default in PHP, but may be disabled on some shared hosts.

* XMLWriter
* SimpleXML

### Are there any filter hooks?

Developers can use these filter hooks to modify some invoice properties. Each filter receives a string for the field value, and the Gravity Forms form array.

* `gfdpspxpay_invoice_ref` for modifying the invoice reference
* `gfdpspxpay_invoice_trans_number` for modifying the invoice transaction reference; NB: must be unique for PxPay account!
* `gfdpspxpay_invoice_txndata1` for setting the TxnData1 field
* `gfdpspxpay_invoice_txndata2` for setting the TxnData2 field
* `gfdpspxpay_invoice_txndata3` for setting the TxnData3 field

Developers can run processes on these actions (e.g. load classes required to handle invoice presentation):

* `gfdpspxpay_process_confirmation`
* `gfdpspxpay_process_confirmation_parsed`
* `gfdpspxpay_process_approved`
* `gfdpspxpay_process_failed`

## Screenshots

1. Options screen
2. A sample donation form
3. A list of Windcave feeds for a form
4. A Windcave feed (mapping form fields to Windcave)
5. The sample donation form as it appears on a page
6. A successful entry in Gravity Forms admin

## Upgrade Notice

### 2.5.0

minimum required PHP version is now 7.4; replace deprecated paymentexpress.com endpoints with windcave.com endpoints; added filter hook for timeout option

## Changelog

[The full changelog can be found on GitHub](https://github.com/webaware/gravity-forms-dps-pxpay/blob/master/changelog.md). Recent entries:

### 2.5.0

Released 2024-02-02

* changed: minimum required PHP version is now 7.4; recommended version is PHP 8.1 or higher
* fixed: replace deprecated paymentexpress.com endpoints with windcave.com endpoints
* added: filter `gfdpspxpay_options` for setting timeout for hosted page
