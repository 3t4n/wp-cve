=== Stripe Payment Gateway for WooCommerce ===
Contributors: amans2k, xlplugins, teamwoofunnels
Tags: stripe, woocommerce, apple pay, google pay
Requires at least: 5.4.0
Tested up to: 6.4.3
Requires PHP: 7.0
Stable tag: 1.7.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Stripe Payment Gateway for WooCommerce is an integrated solution that lets you accept payments on your online store for web and mobile.

== Description ==
Stripe Payment Gateway for WooCommerce is an integrated solution that lets you accept payments on your online store for web and mobile.

It delivers a simple, quick, and secure gateway to accept payments on your WooCommerce website.

Stripe Payment Gateway for WooCommerce accepts credit and debit card payments through Stripe, such as Visa, MasterCard, Diners Club, JCB, American Express, etc. Plus, it further integrates with express payments, such as Google Pay and Apple Pay.

It also supports local payment gateway options such as SEPA, P24, iDEAL, BanContact, and more.

Furthermore, it deeply integrates with FunnelKit's Funnel Builder and One Click Upsells for seamless payment ordering and processing.

https://www.youtube.com/watch?v=01pWZNaGGd4

== Features ==

Here are some outstanding features of Stripe Payment Gateway for WooCommerce:

=== 1. Quick Onboarding Process

Once you connect your Stripe account with Stripe Payment Gateway for WooCommerce, you don't need to go back and forth to enter your API keys manually. All your details, including the live and test keys, will be fetched here.

=== 2. Hassle-free Apple Pay and Google Pay Set-Up

By enabling one-click express payments in your store, you can provide a smooth checkout and payment experience to your customers. Our one-click express payment options include Apple Pay and Google Pay.

=== 3. Automatic Webhook Creation

Stripe Payment Gateway for WooCommerce stays one step ahead in syncing your webhooks from your Stripe account. You don't need to copy and paste your webhooks manually - it gets integrated as soon as you create one.

=== 4. Supports All Major Credit and Debit Cards

Collect payments directly from several brands of credit and debit cards, such as Visa, MasterCard, American Express, Discover, JCB, etc., via Stripe from your customers for a hassle-free checkout experience in your store that minimizes cart abandonment.

=== 5. SCA-Ensured, 3D Secure Payments

Ensure a secure two-way authentication with Strong Customer Authentication (SCA) in your WooCommerce store. It automatically detects and prevents spam transactions through its 3D secure payment gateway for both web and mobile.

=== 6. Deeply Integrates with Subscription Plugins

With Stripe Payment Gateway for WooCommerce, you can collect one-time and recurring subscription payments for subscription-based products on your WooCommerce website.

=== 7. Seamless Transition Between Live and Test Modes

With the ability to seamlessly switch between the test and live modes, this plugin is built to provide the best experience to your users. You'll be able to validate and improve your payment transactions effortlessly.

=== 8. Deep Compatibility with FunnelKit

While FunnelKit works with popular gateways, it provides deeper integration with the full FunnelKit suite. For instance, this gateway detects the upsells after checkout and shows credit card fields even if the order total is zero. This way, you'll be able to get revenue even with free products or lead magnets.

== About Us ==

Stripe Payment Gateway for WooCommerce is a part of [FunnelKit's](https://funnelkit.com/) ever-growing plugin ecosystem that is being used on hundreds of thousands of websites.

=== Here are some of our plugins:

**[FunnelKit's Funnel Builder](https://wordpress.org/plugins/funnel-builder/)** - The most flexible funnel builder for WordPress. Build profitable funnels using conversion-friendly templates, analyze performance and improve with built-in A/B testing.

**[FunnelKit Checkout](https://funnelkit.com/woocommerce-checkout-pages-aero/)** - Send your conversions through the roof with FunnelKit's optimized checkout pages. Get ready-to-use checkout templates, embed forms, one-page checkouts, and more.

**[FunnelKit One-Click Upsells](https://funnelkit.com/woocommerce-one-click-upsells-upstroke/)** - Boost your AOV by pitching hyper-relevant upsell offers and one-click order bumps right on the checkout page.

**[FunnelKit Automations](https://wordpress.org/plugins/wp-marketing-automations/)** - Engage with your customers on autopilot with automated email and SMS campaigns. Automate your abandoned cart recovery sequence, post-purchase emails, winback campaigns, and more.


== Frequently Asked Questions ==

= How do I add a Stripe payment gateway in WooCommerce? =
To add a Stripe gateway to your WooCommerce website, you must first have a Stripe account. Then, install the Stripe Payment Gateway for WooCommerce plugin to integrate with your Stripe account. Once done, all you have to do is to enable the Stripe payment gateway.

= Does it work with recurring subscription payments? =
Yes! Stripe Gateway supports official [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/).

= What happens if you switch from the existing Stripe gateway to this Stripe Payment Gateway for WooCommerce plugin? =
Switching from your existing Stripe gateway plugin to the Stripe Payment Gateway for WooCommerce will make it your primary payment gateway plugin to collect payments on your WooCommerce website. Make sure to deactivate your existing plugin after you've made the switch.

= Will my older subscriptions continue to work if I switch to this plugin? =
Yes, your subscriptions will continue to work fine. This Stripe Gateway plugin will take complete responsibility for your subscription charging even if you make the switch.

= Can I deactivate the existing Stripe plugin after switching to this plugin? =
Yes! Once you've successfully moved on to this plugin, you can deactivate and uninstall your existing Stripe plugin.

= How do I test the payments in my WooCommerce store? =
You can test your payments by making a purchase in your WooCommerce store. Make sure to enable the TEST mode under API settings and make a demo purchase.

= Can I add multiple express buttons on a single page? =
Yes, Stripe Payment Gateway for WooCommerce allows you to show multiple Express Pay options like Google Pay, Apple Pay, and even Payment Request Buttons.

= I have some questions. How do I contact your support team? =
Whatever questions you have, our support team will be happy to help you.
Either fill up this [support form](https://funnelkit.com/support/) or drop your query at [support@funnelkit.com](support@funnelkit.com)

= My express buttons are not showing. What should I do? =
If the express payment buttons are not showing up on your checkout page, please make sure to meet the guidelines of respective payment providers.
Follow this [document](https://funnelkit.com/docs/stripe-gateway-for-woocommerce/troubleshooting/express-payment-buttons-not-showing/) to set up your payment providers.

= What payment methods are supported? =
Stripe Payment Gateway for WooCommerce is continuously evolving to add new features and new gateways to its system. Currently, it supports credit and debit cards, including Visa, MasterCard, American Express, Discover, etc., express payments (Apple Pay and Google Pay), and local payments such as SEPA, P24, iDEAL, and Bancontact.

= Is there any documentation to help me get started? =
Yes, visit our complete documentation on [Stripe Payment Gateway for WooCommerce](https://funnelkit.com/docs/stripe-gateway-for-woocommerce/) here.


== Installation ==
1. Install 'Stripe Payment Gateway for WooCommerce' Plugin.
2. Activate the Plugin.
3. Go to WooCommerce -> Settings -> Stripe Credit Card
4. Start by connecting with stripe.


== Change log ==
= 1.7.2 =
* Improved: Only process webhook for credit cards in case of order placed using our gateway. (#413)
* Improved: Onboarding wizard and Connect flow improvements (#414)

= 1.7.1 =
* Fixed: Specific selling location select dropdown field was not working correctly for SEPA, iDeal,P24, and Bancontact gateways. (#407)

= 1.7.0 =
* Improved: Compatibility with FunnelKit Checkout updated. (#393)
* Improved: Condition to check valid requests during verify intent improved to avoid nonce verification errors. (#385)
* Improved: Handle webhook delivery for setup having different domains per language in WPML. (#372)
* Improved: Additional handling to listen to intent_succeeded webhook and mark order payment successful to avoid certain edge cases where order goes pending. (#376)
* Fixed: Issue causing card payments to fail when providing a Bank statement description with the `statement_descriptor` parameter.[Stripe announcement](https://support.stripe.com/questions/use-of-the-statement-descriptor-parameter-on-paymentintents-for-card-charges) (#402)
* Fixed: Stripe amount was incorrect for the currency with no decimal values like JPY. (#388)
* Fixed: Stripe Fees showing in non-decimal formatting due to WooCommerce settings. (#380)
* Fixed: Handle error while deleting a payment method from my account if attached to a subscription in some cases. (#373)
* Fixed: SEPA gateway for free trials was not working correctly. (#385)
* Fixed: Apple Pay button CSS was not correct for light outline settings. (#385)
* Fixed: Bank list styling issue correct for iDeal gateway. (#399)

= 1.6.0 =
* Improved: Compatibility with FK cart updated. (#366)
* Improved: Express checkout buttons cover a few edge cases on a single product page. (#368)
* Improved: Added additional metadata for upsell transactions to detect during webhooks. (#370)
* Fixed: Card input background CSS not working with WooCommerce native classic checkout block. (#369)
* Fixed: Compatibility with Fk checkout updated for the cases of card declines. (#361)

= 1.5.4 =
* Improvement: Display incompatibility notice for WC 8.3 (Cart and checkout blocks).(#362)

= 1.5.3 =
* Fixed: cart page setting up on site pages with combination of express checkout settings enabled in both gateway and FK Cart. (#356)

= 1.5.2 =
* Fixed: Resolved a conflict when Express setting in gateway was ON while optimisation express setting in FunnelKit checkout was off. (#353)

= 1.5.1 =
* Improved: Additional Gateways class data was passing to fragments and localised data when express checkout is enabled. (#349)

= 1.5.0 =
* Added: Stripe SDK version updated to v7.128 to provide compatibility with PHP 8.1. (#318)
* Added: Compatibility with PHP 8.2. (#337)
* Added: Compatibility with Funnelkit Cart express button feature. (#342)
* Added: Admin UI to capture authorized charges & to preview transaction data. (#304)
* Added: Username added in refund order note. (#306)
* Added: New filter hooks added to modify API key and API secret. (#312)
* Improved: Default Gateway showing on zero dollar payment for upsell improved. (#308)
* Improved: Mode metadata added in the token to filter saved cards based on mode on checkout. (#275)
* Improved: Handle saved customer ID cases causing 'no_such_customer' error during checkout. (#275)
* Improved: Edge case of two order notes adding with webhooks charge.failed & payment_intent.payment_failed. (#288)
* Improved: Handled edge case where subscription amount is zero causing the error. (#331)
* Improved: Optimize database queries for options key for the pre-setup state. (#315)
* Improved: Dynamic visibility of express checkout buttons based on cart prices on the checkout page. (#308)
* Fixed: Refund order note added twice in a few edge cases with webhooks. (#278)
* Fixed: Javascript error showing during card errors triggers when no HTML wrapper for notice. (#280)
* Fixed: Issue with credit card payments when no country field is available on checkout. (#288)
* Fixed: Shipping methods were not showing up for express button payments on the product page in a few cases. (#307)
* Fixed: PHP error during card failure for subscription free trials in a few cases. (#331)
* Fixed: SEPA Payments are not working when the credit card gateway is disabled. (#335)

= 1.4.1 =
* Improved: Handling of Webhook events when multiple sites are connected to same account. (#267)
* Fixed: An edge case where webhook event payment_intent.succeeded causing duplicate order notes. (#267)

= 1.4.0 =
* Added: Compatibility with WooCommerce HPOS. (#150)
* Added: Filter added to modify transaction metadata for orders/upsells. (#213/#255)
* Added: Support for renewal payment using e-mandate for Indian credit cards. (#256)
* Added: Express checkout compatibility with FunnelKit Cart. (#214)
* Improved: Handle case of test mode webhook causing issues in live mode setups. (#208)
* Improved: Show block loader UI while processing payments via express buttons. (#197)
* Improved: Express buttons styling and other cases. (#234/#247/#223/#224/#207)
* Fixed: Upsells are not showing up for zero-dollar payments via express buttons. (#196)
* Fixed: [Edge case]Prevent adding incorrect order notes on the order refund webhook in case the same account is linked with multiple sites. (#214)
* Fixed: Handle incorrect scroll to notice wrapper in some cases. (#260)

= 1.3.0 =
* Added: Smart buttons compatibility with FunnelKit Cart.
* Improved: Handling for the case on the add-payment-method page when CC fields are not getting initialized.
* Improved: A PHP notice on PHP v8.1.
* Improved: Support for Gpay and Applepay added for renewal processing for the subscriptions created by other gateways.
* Improved: More improved handling for upsells for SCA charges and refunds.
* Fixed: Payments for the p24 gateway were not working correctly.
* Fixed: Statement descriptor was not set up correctly in case of renewals.

= 1.2.8 =
* Fixed: Issue with saved cards in combination of subscription product purchased, Funnel Builder pro plugin is active and no upsells are setup.

= 1.2.7 =
* Improved: SVG Card Icons on Credit Card Fields.

= 1.2.6 =
* Added: Order note now covers more card decline reasons.
* Added: Better metadata added to the payment API requests.
* Added: Shipping data added to the payment api requests.
* Improved: Payments handled for the resubscribe subscription.
* Improved: Handling for cases when payments done by order-pay page.
* Improved: Shipping calculations for the express checkout buttons.
* Improved: Webhook behaviour with other Stripe gateways.
* Improved: Compatibility with payments via pay for order page corrected for few cases.
* Improved: Stripe fee and net calculation logic improved for some scenarios.
* Improved: Compatibility with Funnelkit checkout for CVC toolip.
* Fixed: Refund Webhook adding a duplicate order note item fixed.
* Fixed: Issue with saved cards from other gateways were not showing up on my-account page.

= 1.2.5.1 =
* Fixed: Webhooks request error.

= 1.2.5 =
* Improved: Webhook charge failed request, extra handling added.
* Improved: Express checkout button behaviour for saved cards.
* Improved: PHP & JS code improved with better logging and other optimization.
* Fixed: Express Checkout button on single product add_to_cart call was not working.

= 1.2.4 =
* Improved: Textual changes in admin settings.
* Fixed: Hardened Security for admin settings.
* Fixed: Optimized JS and CSS frontend assets.

= 1.2.3 =
* Improved: Added extra logs to analyze express payment scenarios.
* Improved: Handle multiple enqueue script action execution for front-end, causing issues in some cases.
* Improved: Cancel payment redirection behaviour corrected for Bancontact gateway.
* Fixed: Issue while processing payment through express checkout buttons in some specific cases.
* Fixed: A Few PHP notices were showing up during back-end ajax actions.

= 1.2.2 =
* Improved: Upsell Gateway Selection Setting will be default enabled if FunnelKit is present.
* Fixed: Subscription cancellation error for subscriptions that were created with another Stripe gateway.

= 1.2.1 =
* Improved: Intent API now passes meta_data from order.
* Improved: Webhook handling for the failed charge.

= 1.2.0 =
* Improved: Some improvements in onboarding wizard.

= 1.1.0 =
* Added: New setting to re-verify apple domain registration.
* Improved: Do not re-register the apple domain if already done.
* Improved: Localization for the front-end gateway CC fields.
* Improved: order status behaviour for failure attempts.

= 1.0.0 =
* Public Release