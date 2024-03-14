=== SumUp Payment Gateway For WooCommerce ===
Contributors: sumup
Tags: sumup, payment gateway, woocommerce, payments, ecommerce
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 2.4.2
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Grow your business by accepting payments through SumUp in your WooCommerce store.

The SumUp plugin for WooCommerce offers consumers a seamless payment experience with their favourite payment methods in just a few steps.  The payments are processed through the SumUp payment platform, so you can see them alongside your in-store sales. It's affordable, easy to set up and use, and simply a better way to get paid.

= TAKE PAYMENTS =
* [No fixed costs. No binding contracts. Just a small % per transaction](https://sumup.co.uk/credit-card-processing-pricing/)
* Receive secure payments to your bank account within 3 days
* Find everything in one place in the SumUp Dashboard and App

= SUPPORTED PAYMENT METHODS =
* Accept different debit and credit cards: Visa, VPay, Mastercard, American Express, Diners Club, Discover
* Accept alternative payment methods: Bancontact, Boleto, iDeal, Sofort
* [Request access to Alternative Payment Methods here](https://cloud.crm.sumup.com/sumup-developers-contact-form)

= STAY SECURE =
SumUp is authorised as a Payment Institution by the Financial Conduct Authority and is Europay, Mastercard and Visa (EMV) and PCI-DSS certified.
This ensures that payments are processed in accordance with the highest security standards.

= BE FLEXIBLE =
* SumUp processes in [11 currencies](https://developer.sumup.com/rest-api/#tag/Checkouts/paths/~1checkouts/post)
* SumUp supports 22 languages: Bulgarian, Czech, Danish, Dutch, English, Estonian, Finnish, French, German, Greek, Hungarian, Italian, Latvian, Lithuanian, Norwegian, Polish, Portuguese, Romanian, Slovak, Slovenian, Spanish and Swedish

**Want to try it?**

= GET STARTED =
* Download the plugin
* Create a [free account](https://buy.sumup.com/en-gb/signup/create-account) or use [your existing one](https://me.sumup.com/)
* Verify your account and connect the plugin by adding the requested information
* [Contact our support team](https://cloud.crm.sumup.com/sumup-developers-contact-form) for a test account or to enable necessary scopes when you are ready to accept payments

You're ready to go.

== Screenshots ==

1. The settings panel used to configure the gateway
2. A checkout with SumUp

== Installation ==

= Automated installation =

Automatic installation is the easiest option, as WordPress will handle the file transfer and you won�t need to leave your web browser.
Note: Ensure the [WooCommerce plugin](https://wordpress.org/plugins/woocommerce/) is pre-installed prior to initiating the steps in this guide.

1. Install the plugin via the "Plugins" section in the Dashboard
1.1. Click on "Add new" and search for "SumUp Payment Gateway for WooCommerce"
1.2. Then click on the "Install Now" button
1.3. Click "Activate" to active the plugin
2. Enter your Credentials from your SumUp account (client ID, client secret and email) and configure any settings needed

= Manual Installation =

The manual installation method involves downloading our plugin and uploading it to your web server via your favorite FTP application. WordPress contains [instructions on how to do this](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

== Frequently Asked Questions ==

= Does it work with debit and credit card? =

Yes. You'll be able to accept Visa, VPay, Mastercard, American Express, Diners Club, Discover cards.

= What currencies does the plugin support? =

We support 11 currencies with more being added. See all the currencies [here](https://developer.sumup.com/rest-api/#tag/Checkouts/paths/~1checkouts/post).

= Which Alternative Payment Methods (APMs) are supported? =

At SumUp you can process online payments with Boleto, Bancontact, iDeal & Sofort. Read more about our APMs in our [official developer documentation](https://developer.sumup.com/docs/apms/).

= How can I enable Alternative Payment Methods (APMs)? =

Our Support team will enable the APMs that are relevant to your business location. Reach out to us through [our contact form](https://cloud.crm.sumup.com/sumup-developers-contact-form) for assistance.

= Where can I find documentation? =

You can find all the information you'll need on how to set up your plugin [here](https://developer.sumup.com/docs/sumup-woocommerce-plugin/).

= Where can I get support if needed? =

If you have any questions, please get in contact with us through our [contact form](https://cloud.crm.sumup.com/sumup-developers-contact-form).

= Does this support both production mode and sandbox mode for testing? =

Yes. If you need a testing environment, please contact us through our [contact form](https://cloud.crm.sumup.com/sumup-developers-contact-form).

== Changelog ==

= 2.4.2 =
* Fixed: In some flows order status can be updated two times.
* Fixed: error to get country from checkout.
* Fixed: validation of credentials on settings.
* Improvements: add more details to logs.
* Improvements: compatibility with WordPress 6.4.

= 2.4.1 =
* Improvements: error message during setup.

= 2.4 =
* Improvements: do not hide the card widget on submit if has any invalid data.
* Improvements: flow to validate payments with redirect (like 3Ds).

= 2.3 =
* Improvements: credentials validation on plugin settings.

= 2.2 =
* Improvements: Update order status to cancelled when 3Ds validation failed.
* Improvements: Logs during checkout.

= 2.1 =
* Fixed: 3Ds payments redirect.
* Fixed: webhook order confirmation.
* Fixed: card widget close when clicked on it (modal disabled).

= 2.0 =
* New: Accept payments with alternative payment methods (Follow guides for enabling in your account)
* New: Accept card payments with installments in BR.
* New: Accept payments with Apple Pay.
* New: Support for WooCommerce stock management feature
* New: New user experience configuration: merchant can choose to open the payment option in a pop-up instead of the checkout page.
* Improvements: Display WooCommerce order Id on SumUp Sales History.
* Improvements: Added transaction code to order description on WooCommerce
* Improvements: Added checkout_id in order notes to improve customer support
* Improvements: New settings screen for easier setup
* Improvements: Multiple code maintenance improvements.
* Improvements: Support for Wordpress 6.3
* Improvements: Require PHP version 7.2 or greater.
* Fixed: Errors during checkout that caused duplicated payment.
* Fixed: Issues loading payment methods on checkout.
* Fixed: Issue with customer creation during checkout that caused duplicated payment.

= 1.2 =
* Changed: Checkout improvement.
* Changed: WooCommerce order id in description.

= 1.1 =
* New: Added new currencies.
* New: Checkout-id on payment form.
* Changed: Rephrase Error messages.

= 1.0 =
* Initial release.
