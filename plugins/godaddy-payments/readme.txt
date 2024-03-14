=== GoDaddy Payments for WooCommerce ===
Contributors: godaddy
Tags: credit card, payments, checkout, e-commerce, ecommerce, woo, woocommerce
Requires at least: 5.6
Tested up to: 6.4.3
Requires PHP: 7.4
Stable tag: 1.7.3
License: GPL-2.0
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

A payment gateway plugin that enables your U.S. or Canadian business to accept credit card payments directly on your WooCommerce site.

== Description ==

[GoDaddy Payments](https://signup.payments.godaddy.com/r/woo-plugin) for WooCommerce is a payment gateway plugin that enables your U.S. or Canadian business to accept any major credit or debit card directly on your WooCommerce site.

For in-person retailers, GoDaddy Payments provides a seamless connection between your online and offline business. Allow **Buy Online, Pay In Store** to connect with your local customers &ndash; use the "Sell in person" option to seamlessly sync pickup orders with your [GoDaddy Payments Smart Terminal](https://payments.godaddy.com/in-person). You can take payment online or when the order is picked up in store, mark orders "ready" for pickup or delivery, and automatically notify customers of changes in the pickup or delivery order.

Using GoDaddy Payments to process your WooCommerce store’s credit card payments has benefits for both your store and your customers. With GoDaddy Payments your business can:

Get started quickly and manage your online store easily.

* **Start taking secure payments in minutes** with a quick & easy setup, no setup fees and no contracts.
* **Get paid faster** - receiving your funds the next business day.
* **Pay a low, simple transaction fee** of 2.3% + 30c per online transaction in the U.S.
* **Significantly reduce your PCI compliance responsibility.** GoDaddy Payments uses hosted iframes to ensure payment data never touches and is never stored on your site’s servers.
* **Dedicated support from WooCommerce experts** to help you get started processing payments.

Provide a better shopping experience.

* **Use any major credit or debit card** including Visa®, MasterCard®, American Express®, Discover®, Diner’s Club®, JCB®, and UnionPay®.
* Support **Buy Online, Pickup in Store** (BOPIS) for any WooCommerce merchant with the GoDaddy Payments smart terminal.
* **Keep customers in your online store with a seamless experience.** Your customers enter payment details directly on your site in an integrated, [mobile-friendly checkout form](https://docs.woocommerce.com/document/advanced-payment-gateway-features/#enhanced-checkout).
* **Reduce abandoned cart losses** with detailed decline messages that help customers correct mistakes at checkout.
* **Provide faster checkout on future orders** by allowing customers to [save payment methods](https://docs.woocommerce.com/document/advanced-payment-gateway-features/#section-11) easily and securely.

Benefit from a payment plugin built for WooCommerce.

* **Complete transactions quickly** by authorizing charges at checkout, then [capturing them later](https://docs.woocommerce.com/document/advanced-payment-gateway-features/#capture-charges) through the WooCommerce Orders page.
* **Enjoy faster, easier order management**, by processing refunds and voids directly through WooCommerce – with no need to log into your merchant account.
* **Sell subscriptions or accept pre-orders** with full support for [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) and [WooCommerce Pre-Orders](https://woocommerce.com/products/woocommerce-pre-orders/)!

== Screenshots ==

1. The gateway connection settings
2. The gateway feature settings
3. The payment form at checkout
4. The Get Help form in the settings page

== Frequently Asked Questions ==

**What are the requirements to use GoDaddy Payments for WooCommerce?**

Your site must meet the following requirements:

* Your business must be located in the **United States** or **Canada**.
* Your WooCommerce currency must be set to **United States (US) dollar ($)** if located in United States, or **Canadian dollar ($)** if located in Canada.
* PHP 7.4+ (you can find this under **WooCommerce > Status**)
* WordPress 5.6+
* WooCommerce 4.0+

**How do I find my credentials and connect my WooCommerce store to GoDaddy Payments?**

You can [sign up here](https://signup.payments.godaddy.com/r/woo-plugin).

You can connect to GoDaddy Payments by following these steps:

1. Login to your [GoDaddy Payments dashboard](https://poynt.godaddy.com/settings/business/contact) and go to **Advanced Tools > Business Settings > Contact Info**.
2. Copy your **Application ID** and **Private Key** in the Poynt Collect API Settings section.
3. In your WooCommerce store, go to **WooCommerce > Settings > Payments > Credit Card** and paste these credentials in the **Application ID** and **Private Key** fields.
4. Click **Save Changes**.

You may now enable the gateway and start processing payments with GoDaddy Payments!

**Does this plugin support recurring / subscription payments?**

Yes! This plugin supports tokenization, which is required for recurring payments such as those created with [WooCommerce Subscriptions](http://woocommerce.com/products/woocommerce-subscriptions/).

**Will this plugin work with my site's theme?**

This plugin should work nicely with any WooCommerce compatible theme (such as [Storefront](https://woocommerce.com/storefront/)), but some themes may require custom styling for a perfect fit. For assistance with theme customization, please visit the [WooCommerce Codex](https://docs.woocommerce.com/documentation/plugins/woocommerce/woocommerce-codex/).

**Help! I'm having trouble with this plugin.**

Having trouble? Follow these steps to make sure everything is setup correctly before sending a support request from the **Get Help** link on the settings page:

* Please ensure that your site meets the plugin requirements.
* Confirm that your credentials are correct.
* Review this documentation and other FAQs to see if they address your question.
* Enable the **Debug Mode** setting (under **WooCommerce > Settings > Payments > Credit Card**), process a test transaction, and review the error codes / messages provided by GoDaddy Payments in the logs (displayed at checkout and/or under **WooCommerce > Status > Logs**). In some cases, such as a transaction being held for review or declined, the plugin cannot change the issue, and it must be resolved in your GoDaddy Payments account. If the error code indicates an issue with the plugin, please contact our team from the **Get Help** link on the settings page!

**Where can I get support, request new features, or report bugs?**

First, please review this documentation to see if it addresses your question. If not, please get in touch with us through the **Get Help** link on the **WooCommerce > Settings > Payments > Credit Card** page.

== Changelog ==

= 2024.03.04 - version 1.7.3 =
 * Fix - Allow overriding the place order button text also when using the checkout block
 * Misc - Improve the plugin compatibility with PHP 8.2+

= 2024.02.14 - version 1.7.2 =
 * Fix - Ensure the changes from the previous release are also included in the block checkout
 * Fix - Add sanity checks in the Pay in Person gateway to prevent errors at checkout
 * Fix - Display the correct titles in the plugins' page "Configure" links for the payment methods

= 2024.02.12 - version 1.7.1 =
 * Tweak - Pass available customer information to the payment form to improve AVS check accuracy

= 2024.01.23 - version 1.7.0 =
 * Feature - Add support for the WooCommerce Checkout block for the credit card and pay in person payment methods
 * Tweak - ReCaptcha is enabled by default on the payment form and checkout block (behavior can be filterable)
 * Tweak - Pass `fr_CA` or `en_CA` to payment form if Canadian locale is detected
 * Fix - Improve the Shipping Methods selector for Pay in Person availability setting to match configured shipping zones in supported regions
 * Dev - Add filter to allow customizing the block and payment form fields appearance

= 2023.11.17 - version 1.6.2 =
 * Misc - Add admin notice to help merchants reverting to the checkout shortcode if the checkout block is used
 * Localization - Improve the plugin localization of components that are part of the underlying framework dependency

= 2023.11.08 - version 1.6.1 =
 * Fix - Display a more detailed error message to customers who try to submit a payment but the AVS check fails

= 2023.10.17 - version 1.6.0 =
 * Misc - Add support for Canadian merchants

= 2023.07.06 - version 1.5.0 =
 * Fix - Address an issue where prices without decimals were processed in cents incorrectly
 * Fix - Replace deprecated usages of `FILTER_SANITIZE_STRING` with `FILTER_SANITIZE_FULL_SPECIAL_CHARS`
 * Misc - Update Firebase PHP JWT dependency to 6.5.0
 * Misc - Add compatibility for WooCommerce High Performance Order Storage (HPOS)

= 2023.04.07 - version 1.4.2 =
 * Fix - Fix broken links in Sell in Person gateway admin

= 2023.02.02 - version 1.4.1 =
 * Fix - Resolve fatal error due to missing file

= 2023.02.02 - version 1.4.0 =
 * Fix - Partial capture support
 * Misc - Use updated GoDaddy Payments frontend script URL
 * Misc - Require PHP 7.4 and WordPress 5.6

= 2022.11.30 - version 1.3.4 =
 * Tweak - Update smart terminal product page URL

= 2022.11.07 - version 1.3.3 =
 * Fix - Improve handling of unknown country code errors at checkout

= 2022.09.15 - version 1.3.2 =
 * Fix - Bug on My Account - Payment Methods page with other gateways installed

= 2022.06.16 - version 1.3.1 =
 * Fix - Staging mode checkout form

= 2022.05.12 - version 1.3.0 =
 * Feature - You can now let customer buy online and pickup orders in store with GoDaddy Smart Terminal!
 * Feature - Adds a Sell in Person gateway type to support Buy Online, Pickup in Store (BOPIS)
 * Misc - Updated the plugin name from "Poynt - A GoDaddy Brand" to "GoDaddy Payments"

= 2022.03.10 - version 1.2.3 =
 * Misc - Update the staging API endpoint

= 2021.08.26 - version 1.2.2 =
 * Fix - Address a fatal error that occurs when attempting to save a payment method from the My Account > Add Payment Method page

= 2021.08.18 - version 1.2.1 =
 * Tweak - Add the shipping address to requests when possible to improve transaction review

= 2021.07.26 - version 1.2.0 =
 * Feature - Merchants can contact the support team directly from the plugin settings page using the "Get Help" link

= 2021.07.06 - version 1.1.3 =
 * Fix - Include the correct company state parameter in signup link
 * Misc - Update the connection settings description
 * Misc - Update the documentation URL

= 2021.06.07 - version 1.1.2 =
 * Fix - Catch additional validation errors from Poynt Collect at checkout
 * Misc - Do not send partial authentications in charge transactions
 * Misc - Parse customer name via Poynt Collect

= 2021.04.22 - version 1.1.1 =
 * Misc - Remove unneeded composer packages

= 2021.04.22 - version 1.1.0 =
 * Tweak - Append additional compliance data to business transactions for risk review
 * Tweak - Add a currency check and admin notice if it's not supported
 * Tweak - The country admin notice is now visible in any admin page
 * Misc - Remove support for Saved Card Verification
 * Dev - Hardcode API Requests' user-agent for consistency
 * Dev - Include the complete site URL protocol in the sign up CTA link URL

= 2021.04.01 - version 1.0.0 =
 * Initial release
