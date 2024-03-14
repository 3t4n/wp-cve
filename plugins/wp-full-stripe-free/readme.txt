=== WP Full Stripe ===
Contributors: CoastMountain
Tags: wordpress payment, wordpress subscription, wordpress, plugin, stripe, payment, payments, subscription, subscribe, donation, credit card, gateway, payment gateway, ecommerce, membership
Requires at least: 5.3.0
Tested up to: 6.4.3
Stable tag: 7.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Online payments with Stripe made easy in WordPress.

== Description ==

Full Pay is a WordPress plugin designed to make it easy for you to accept payments from any page your WordPress site. 
Accept donations, sell individual item, or sell subscriptions. All is possible, easy to set up, and configurable.
Reuse your existing Stripe account or create one during the setup flow.

Since v7, Full Pay is fully free to use, but an application fee of 1.9% will be applied to each transaction, in addition to the Stripe fees.
To turn off the application fees, and be eligible for support, purchase a license and make sure to keep it active. See pricing an options on [paymentsplugin.com](https://paymentsplugin.com)

Extensive documentation is available at [support.paymentsplugin.com](https://support.paymentsplugin.com).

###WP Full Stripe Features:###

* Securely take payments from any page or post on your WordPress website in over 100 currencies
* Supports major credit cards as well as ApplePay, GooglePay, AliPay, and Stripe Link
* Allow your users to subscribe for recurring payments (ever-running, or in installments) with setup fees and non-standard intervals
* Sell individual items for a set amount, custom amount, or amount selectable from list
* Customize the forms: select which fields to show, add custom fields, and style the forms with custom CSS
* Send custom payment emails, or use Stripe's built-in notifications
* Have your payment form embedded/inline in your page, or use Stripe's Checkout experience which opens in a new window
* Easily view and manage your received payments, subscribers, plans, and more
* Use the form shortcode generator for embedding forms easily into pages and posts (simple copy'n'paste)!
* Uses Stripe Connect for secure communication with Stripe
* The plugin can auto-update to the latest version with the click of a button!
* Fully supported, professionally written and regularly updated software

== Installation ==

1. Uninstall any previous version of the plugin (No data will be lost)
1. Download this plugin.
1. Login to your WordPress admin.
1. Click on the plugins tab.
1. Click the Add New button.
1. Click the Upload button.
1. Click "Install Now", then Activate, then head to the new menu item on the left labeled "Full Pay" and "Settings".
1. Make sure you go through the Connect Account flows for both Live and Test modes.
1. During the connect flow you can either connect to an existing Stripe account or create a new one

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= v7.0.7 (Mar 11, 2024) =
* Fixed: bug in customer portal
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0.6 (Mar 7, 2024) =
* Replaced customer portal stripe card elements with stripe payment element for updating payment method
* Fixed: bug when updating customers in stripe
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0.5 (Mar 7, 2024) = 
* Fixed: bug in retrieving customers
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0.4 (March 5, 2024) =
* Use "card holder name" instead of "billing name" when sending card data to Stripe. This will provide more accurate date for Stripe's Radar product and other anti-fraud measures
* Fixed: bug with stripeAccount
* Fixed: bug getting prices
* Fixed: bug getting prices in non-connect integration
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0.3 (February 29, 2024) =
* Fixed: issue in non-connect integration
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0.2 (February 28, 2024) =
* Fixed: Checkout bug
* Fixed: bug in customer portal
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0.1 (February 23, 2024) =
* Fixed: bug in customer portal
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v7.0 (February 18, 2024) =
* Added support for AliPay, ApplePay, GooglePay, and Stripe Link
* Replaced old Stripe Elements with the new Stripe Payment Element
* Connects more securely to Stripe via Stripe Connect. This also allows our support team to better assist you with issues that cross over to Stripe.
* Updated knowledge base with improved search. Access from the "?"-menu in the admin views
* Fixed: a number of smaller bugs
* NOTE: for v7.0.0 and onwards, FullPay will work without an active license but will add an application fee of 1.9% per transaction
* NOTE: If upgrading from a previous version, make go through the "Connect Account" flow in Settings->Stripe Account (both Live and Test modes) to complete the setup. Your forms will continue to work in the meantime

= v6.4 (November 13, 2023) =
* Added support for future amount placeholders for subscriptions with a trial period. %PLAN_FUTURE_AMOUNT_NET%, %PLAN_FUTURE_AMOUNT_VAT%, and %PLAN_FUTURE_AMOUNT_GROSS%
* Added option to select an image on checkout donation forms
* Fixed bug where Stripe only returns some prices
* Include Washington DC as a state

= v6.3.2 (July 8, 2023) =
* Fixed a critical security issue.
* Fixed a checkout form crash when billing and shipping fields are turned on.

= v6.3.1 (June 30, 2023) =
* Fixed some minor bugs

= v6.3.0b1 (June 25, 2023) =
* Stripe Tax is supported on one-time payment forms and subscription forms.
* Both exclusive and inclusive tax rates can be used for tax calculations.
* Form fields can be set via URL parameters.
* Price selectors of inline forms always display gross prices, and update the prices when the form changes.
* Log messages are stored in the WordPress database, and log messages can be downloaded from WP admin.
* Phone number can be collected on checkout forms.
* Updated the display languages and translations of checkout forms.
* The plugin is now called WP Full Pay.
* Javascript files are excluded automatically from Rocket Loader of Cloudflare.
* Fixed: Promotion codes applied to inline forms are redeemed as promotion codes, not as discounts.
* Fixed: The customer portal redirects to the login page if the portal is accessed without being logged in.

= v6.2.5 (March 7, 2023) =
* Fixed: Upgraded the Freemius SDK to v2.5.3 for greater compatibility with PHP 8.1.

= v6.2.4 (February 28, 2023) =
* Fixed: The customer portal displayed incomplete subscriptions.
* Fixed: The customer portal didn't display the default card if the card was added on the Stripe dashboard.
* Fixed: The customer portal displayed only the last 10 invoices of the customer.
* Fixed: The subscription summary on the customer portal wasn't up-to-date until the focus left the quantity stepper.
* Fixed: The 'amount' property of the $params array of after-payment action of inline subscriptions wasn't set when 3DS was used.
* Fixed: The 'currency' property of the $params array of after-payment action of subscriptions (inline, checkout) wasn't set.

= v6.2.3 (November 14, 2022) =
* Fixed: Some translations were missing from all language bundles.
* Fixed: Coupons couldn't be applied to products on one-time payments forms if there was only one product added to the form.

= v6.2.2 (October 24, 2022) =
* Fixed: Long transaction description could cause sql insert error on one-time payment and donation forms.

= v6.2.1 (September 14, 2022) =
* Fixed: A javascript error occurred when the prices were recalculated on checkout payment and subscription forms.
* Fixed: Some checks were not working properly for the minimum subscription quantity on the customer portal.

= v6.1.3 (September 13, 2022) =
* Fixed: The fullstripe_after_subscription_charge action received a NULL Stripe subscription object after 3DS/SCA authentication (caused a problem in WP Full Stripe Members as well).
* Fixed: Promotion codes were case-sensitive on inline payment and subscription forms.
* Fixed: Fixed-amount coupons didn't work on inline payment forms with the custom amount option selected.

= v6.2.0 b1 (September 8, 2022) =
* Custom field values are stored in the WordPress database, and are displayed on the transaction details page.
* Coupons can be redeemed only for supported products on inline forms.
* Added the %IP_ADDRESS% placeholder for the IP address of the customer.
* Added a section dedicated to add-ons on the 'Full Stripe / Settings' page in WP admin.
* Implemented minimum quantity for buying subscriptions (buying subscriptions in bulk).
* Added filters to display additional information on the top and bottom of the customer portal page.
* Added option to show/hide the 'Subscriptions' section on the customer portal.
* Added option to toggle scrolling on the customer portal.
* Fixed: When logging in to the customer portal, the email address was case-sensitive for customers stored in the WordPress database.
* Fixed: Redeeming promotion codes was case-sensitive on inline forms.
* Fixed: Fixed-amount coupons on custom-amount one-time payment forms couldn't be redeemed.
* Fixed: The 'stripeSubscription' parameter of the 'fullstripe_after_subscription_charge' action was null when called after an SCA 2nd-factor authentication.

= v6.1.2 (August 16, 2022) =
* Fixed: Some subscriptions started on checkout forms remained in the "Incomplete" state in WP admin.
* Fixed: Custom amount payments on inline one-time payment forms generated a PHP notice.
* Fixed: The "Full Stripe / Transactions" page for subscriptions generated a PHP notice when the WordPress timezone wasn't a named one but an UTC offset.

= v6.1.1 (April 25, 2022) =
* Customer portal with account selector (multiple Stripe customers using the same email address).
* Customer portal filters out and hides zero-amount invoices.
* Shortcodes are resolved in plugin email templates.
* Minimum donation amount option on donation forms.
* Minimum payment amount option on one-time payment forms.
* Generating invoices for donations.
* Logging and displaying the IP address of customers on the plugin dashboard.
* Facility to send plugin emails for testing purposes.
* WordPress filter to add URL parameters to thank you page URLs.
* WordPress filter to restrict the list of billing and shipping countries.
* Portuguese translation for all customer-facing UI.
* The Stripe PHP client has been upgraded to v7.114.0 .
* The Freemius SDK has been upgraded to v2.4.3 .
* Reversed the order of Google reCaptcha fields so that it matches the order on the Google settings page.
* Fixed: WordPress timezone settings taken into account when displaying dates and times in WP admin

= v6.1.0 b1 (April 19, 2022) =
* Customer portal with account selector (multiple Stripe customers using the same email address).
* Customer portal filters out and hides zero-amount invoices.
* Shortcodes are resolved in plugin email templates.
* Minimum donation amount option on donation forms.
* Minimum payment amount option on one-time payment forms.
* Generating invoices for donations.
* Logging and displaying the IP address of customers on the plugin dashboard.
* Facility to send plugin emails for testing purposes.
* WordPress filter to add URL parameters to thank you page URLs.
* WordPress filter to restrict the list of billing and shipping countries.
* Portuguese translation for all customer-facing UI.
* The Stripe PHP client has been upgraded to v7.114.0 .
* The Freemius SDK has been upgraded to v2.4.3 .
* Reversed the order of Google reCaptcha fields so that it matches the order on the Google settings page.
* Fixed: WordPress timezone settings taken into account when displaying dates and times in WP admin

= v6.0.11 (April 5, 2022) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: The customer portal didn't work with some WordPress themes and page builders due to a HTML escaping issue.
* Fixed: The plugin threw an error when activating it on a WordPress site powered by PHP v8.1.

= v6.0.10 (March 2, 2022) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: A high-priority security issue.
* Fixed: When using payment in installments -type plans on checkout subscription forms, the charge count didn't get refreshed in certain cases.

= v6.0.9 (February 23, 2022) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: Inline one-time payment forms charged the wrong amount when a one-time payment was followed by a subscription for the same Stripe customer.
* Fixed: When the custom amount payment option was combined with a single product added to a payment form then the custom amount selector wasn't displayed.
* Fixed: The fullstripe_modify_email_message filter didn't work for the member registration email of WP Full Stripe Members.

= v6.0.8 (January 19, 2022) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Added option to turn off invoice generation on one-time payment forms (turned off by default).
* Fixed: The tax id wasn't saved to the Stripe customer in some cases on inline subscription forms.

= v6.0.7 (December 13, 2021) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Added filter for the post types supported by Thank you pages.
* Added Romanian translation (thanks monk Theologos)
* Added Portuguese (partial) translation (thanks João Gonçalo Dias)
* Fixed: Default billing country couldn't be selected if no tax is collected.
* Fixed: The donation email template of the plugin was reset when the plugin was updated.
* Fixed: When the applied coupon was applicable only for certain number of recurring charges, the "Payment details" popover was empty.
* Fixed: The length of custom field labels wasn't validated.
* Fixed: For live transactions, the plugin displayed 'Test' API mode label in the "Transaction details" side pane.
* Fixed: Shortcodes weren't resolved within the "Thank you" page shortcodes.

= v6.0.6 (November 10, 2021) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: The payment details popover was empty when plans of different recurring intervals were listed on subscription forms.
* Fixed: Stripe email receipts weren't sent on inline one-time payment forms.
* Fixed: The payment amount was always appended to the payment button text, even in absence of the {{amount}} placeholder, on one-time payment forms.
* Fixed: The "Other" payment option (custom amount) wasn't localized properly.
* Fixed: The query to populate the thank you pages dropdown on the "General" tab of forms could run out of memory when there were pages/posts with lot of metadata.
* Fixed: The product name was fixed as "My product" when a custom amount was entered on one-time payment forms.
* Fixed: There was "null" displayed as country for tax rates with no country specified.

= v6.0.5 (Oct 23, 2021) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: Forms weren't displayed on the "Manage forms" page of some websites.
* Fixed: The webhook endpoint returned ERR 500 when a test webhook event was sent from the Stripe dashboard.
* Fixed: The interval label of subscriptions was wrong in some cases on the "Full Stripe / Transactions / Subscriptions" page.
* Fixed: A full-blown error with stack trace was shown if there was no product or plan on the form.
* Fixed: 3D Secure authentication didn't work properly on inline one-time payment forms in some cases.

= v6.0.4 (Oct 19, 2021) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: Forms weren't displayed on the "Manage forms" page of some websites.
* Fixed: The "Settings" link didn't work properly on the "Plugins" page.
* Fixed: Freemius customers received an "Access denied" error when tried to open the "Full Stripe / Settings" page.
* Fixed: The "Terms and condition" label wasn't loaded properly when it contained double quotes.

= v6.0.3 (Oct 18, 2021) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Fixed: One-time payment forms with the 'Custom amount' payment type couldn't be saved.

= v6.0.2 (Oct 18, 2021) =
* IMPORTANT: There are breaking changes if you're upgrading from v5.5.x or earlier, check out our blog post: https://paymentsplugin.com/blog/release-notes-for-wp-full-stripe-v6-0-2
* Completely redesigned admin/dashboard experience
* Tax calculations use Stripe tax rates (exclusive) on one-time payment and subscription forms, inline and checkout alike.
* One-time payment forms use Stripe products, just like subscription forms do.
* Coupon field option added to all one-time payment and subscription form types.
* Shipping address option added to checkout one-time payment and checkout subscription forms.
* The "Manage subscriptions" page has been renamed to "Customer portal".
* Customers can upgrade/downgrade subscriptions on the "Customer portal" page.
* Administrators can configure whether subscriptions are cancelled at the end of period or immediately on the Customer portal.
* The plugin generates invoices also for one-time payments.
* The one-time donation frequency is now optional on donation forms.
* Business, Agency, and Unlimited licenses can be network-activated with a singe click.
* Completely redesigned/extended function signatures for before/after payment action hooks.
* Action/filter hooks can throw a user-friendly exception shown as a banner error on payment forms.
* New webhook URL added while keeping the legacy webhook URL as well.
* Forms can be cloned on the "Manage forms" page.
* Placeholder tokens can be used in email subjects.
* The Stripe PHP client has been upgraded to v7.67.0 .
* The Freemius SDK has been upgraded to v2.4.1 .

= v6.0.1 beta 2 (Sep 30, 2021) =
* Full support for tax calculations based on Stripe tax rates (exclusive) on one-time payment and subscription forms, inline and checkout alike.

= v6.0.0 beta 1 (Aug 6, 2021) =
* Completely redesigned admin/dashboard experience
* The Stripe PHP client has been upgraded to v7.67.0 .
* The Freemius SDK has been upgraded to v2.4.1 .
* One-time payment forms use Stripe products, just like subscription forms do.
* Coupon field option added to checkout subscription forms.
* Coupon field option added to inline and checkout one-time payment forms.
* Shipping address option added to checkout one-time payment and checkout subscription forms.
* The "Manage subscriptions" page has been renamed to "Customer portal".
* Customers can upgrade/downgrade subscription plans on the "Customer portal" page.
* Administrators can configure the Customer portal so that subscriptions are cancelled at the end of period, not immediately.
* The plugin generates invoices also for one-time payments (except auth & capture forms).
* The one-time donation frequency is now optional on donation forms.
* Business, Agency, and Unlimited licenses can be network-activated with a singe click.
* Completely redesigned/extended function signatures for before/after payment action hooks.
* Action/filter hooks can throw a user-friendly exception shown as a banner error on payment forms.
* New webhook URL added while keeping the legacy webhook URL as well.
* Placeholder tokens can be used in email subjects.
* New placeholder token for the coupon code applied: %COUPON_CODE%.
* New placeholder token for payment amount with a coupon applied: %AMOUNT_DISCOUNTED%.
* New placeholder token for donation frequency: %DONATION_FREQUENCY%.
* New placeholder token for the Stripe invoice number: %INVOICE_NUMBER%.
* New placeholder token for the Stripe subscription receipt: %RECEIPT_URL%.

= v5.5.6 (Jul 21, 2021) =
* Fixed: An internal error appeared on checkout save card forms.
* Fixed: The "Manage subscriptions" page now works also on cached pages.
* Fixed: Checkout subscription forms display the plan name instead of plan id on invoice items of the setup fee.
* Fixed: Database tables now have primary keys recognized also by MySql 7.8+.

= v5.5.5 (Mar 10, 2021) =
* The "fullstripe_after_subscription_cancellation" action is fired also in the "customer.subscription.deleted" webhook event handler.
* Added the "setup_future_usage" parameter to checkout sessions for future compatibility.
* Fixed: Compatibility issues with PHP v8.
* Fixed: Compatiblity issue of the wp_localize_script() function in WordPress v5.7.

= v5.5.4 (Jan 13, 2021) =
* Fixed: The Manage Subscriptions page crashed when displaying subscriptions of the standard pricing model.

= v5.5.3 (Jan 11, 2021) =
* Fixed: When no recurring option was configured on a donation form, a recurring donation was started upon payment as well.
* Fixed: Donation forms sent plugin email receipts even when sending plugin receipts wasn't turned on.
* Fixed: When no preset donation amount was configured, the custom donation field had display issues.
* Fixed: Subscribing to zero amount (free) subscription plans on checkout forms displayed an error.
* Fixed: Activating the plugin without required PHP extensions displayed a cryptic message which masked the real error.
* Fixed: WordPress displayed Strict Standards error messages on some PHP v7.3 and v7.4 installations.
* Fixed: The payment details popover displayed NaN instead of actual payment amounts when a custom VAT handler was used.

= v5.5.2 (Nov 25, 2020) =
* Fixed: Save card forms displayed an error when either email receipts or thank you pages were turned on.
* Fixed: The subscription billing anchor day was sometimes off by a day due to timezone calculation issues.
* Fixed: Capturing, refunding, and voiding one-time payments didn't work in WP admin.
* Fixed: The create/edit one-time payment form page broke when the amount list was empty.
* Fixed: The "One-time payment forms" page displayed an error after upgrading from WP Full Stripe Free.
* Fixed: The "One-time payments" page displayed an error after upgrading from WP Full Stripe Free.

= v5.5.1 (Oct 26, 2020) =
* Donation forms with custom amount recurring donations are here!
* Form currency format is now configurable (decimal separator character, currency symbol, currency symbol position).
* Form UI is available in 6 new languages (Danish, French, German, Italian, Japanese, Spanish)
* The Manage Subscription page and payment forms can be used on the same page.
* The Subscription Receipt email template supports the %INVOICE_URL% placeholder.
* New display languages are available for checkout forms.
* The display language of the Card Info field on inline forms is now configurable.
* A description (decorated with placeholder tokens) can be added to saved cards.
* The Card Info field is now full width.
* The Cardholder's Name field is now a required field.
* The subscription plan selector displays the plan recurring fee and interval as well.
* No success message is displayed before redirecting to Thank you pages.
* The Cardholder's Name and Email address fields are moved towards the bottom of the form, just north of the Card Info field.
* Fixed: Some placeholders of the 'Subscription ended' email template didn't work properly.
* Fixed: Logging in to the Manage Subscriptions page didn't work on some Multisite installations.
* Fixed: REST API endpoint registrations complained about missing parameters (introduced in WordPress v5.5.0).
* Fixed: Reworked some calls to functions deprecated in PHP v7.4 .

= v5.5.0-beta (Oct 10, 2020) =
* Donation forms with custom amount recurring donations are here!
* Form currency format is now configurable (decimal separator character, currency symbol, currency symbol position).
* Form UI is available in 6 new languages (Danish, French, German, Italian, Japanese, Spanish)
* The Manage Subscription page and payment forms can be used on the same page.
* The Subscription Receipt email template supports the %INVOICE_URL% placeholder.
* New display languages are available for checkout forms.
* The display language of the Card Info field on inline forms is now configurable.
* A description (decorated with placeholder tokens) can be added to saved cards.
* The Card Info field is now full width.
* The Cardholder's Name field is now a required field.
* The subscription plan selector displays the plan recurring fee and interval as well.
* No success message is displayed before redirecting to Thank you pages.
* The Cardholder's Name and Email address fields are moved towards the bottom of the form, just north of the Card Info field.
* Fixed: Some placeholders of the 'Subscription ended' email template didn't work properly.
* Fixed: Logging in to the Manage Subscriptions page didn't work on some Multisite installations.
* Fixed: REST API endpoint registrations complained about missing parameters (introduced in WordPress v5.5.0).
* Fixed: Reworked some calls to functions deprecated in PHP v7.4 .

= v5.4.1 (Jul 27, 2020) =
Added:
* The billing anchor day feature now supports subscription plans with trial.
Changed:
* Fixed: Submitting subscription forms with a billing anchor day later than the current day of the month threw an error.
* Fixed: Custom VAT calculation didn't work when there was only one plan on the form.
* Fixed: When saving cards, metadata for existing customers weren't updated.
* Fixed: Payment in installments -type subscriptions weren't cancelled automatically in some cases.

= v5.4.0 (Apr 23, 2020) =
Added:
* Billing cycle anchor day can be specified for monthly subscriptions.
* Subscribers can download invoices from the "Manage subscriptions" page.
* Administrators can turn off downloading invoices by subscribers.
* Administrators can turn off cancelling subscriptions by subscribers.
* Subscription plans now support day intervals.
* Full Stripe app icon displayed on the WP admin dashboard.
Changed:
* Renamed the "Settings / Users" page to "Settings / Security"
* The "Payment details" popover doesn't display decimal places for amounts in zero-decimal currencies (like JPY).
* The "Payment details" popover doesn't display a quantity label when purchased quantity is one.
* Improved the scheduler that stops subscriptions with cancellation counts (payment in installment plans).

= v5.3.0 (Mar 17, 2020) =
Changed:
* WP Full Stripe can run concurrently with other Stripe plugins.
(Moved the Stripe PHP client library to a custom namespace)
* Fixed conflict of the "Payment details" popover with some WordPress themes.

= v5.2.0 (Feb 26, 2020) =
Added:
* Customers can purchase subscriptions in bulk.
* Subscribers can change subscription quantity on the "Manage subscriptions" page.
* Subscription plans can be selected by passing an URL parameter to subscription pages.
* Custom amount can be set on one-time payment forms by passing an URL parameter to payment pages.
* Custom placeholder tokens can be added programmatically to email templates.
* Metadata can be added programmatically to transactions (think affiliate id).

Changed:
* Updated Stripe PHP client to v7.24.0 .
* Updated Freemius SDK to v2.3.2 .
* Fixed webhook event processing on checkout forms.

= v5.1.0 (Dec 9, 2019) =
Added:
* Inline forms can collect shipping address.
* Checkout forms can be protected by Google reCaptcha.
* The plan selector is hidden automatically when only one plan is added to the form.
* The "Update card" function of "Manage subscriptions" has become SCA-compliant.

Changed:
* Fixed the auto-update feature because it didn't work for some Envato customers.
* Fixed the "Card update" function on the "Manage subscriptions" page, it didn't update the payment method for some subscribers.
* Fixed a checkout form related periodic task which caused a lot of API errors.

= v5.0.3 (Oct 28, 2019) =
* Bugfix: Checkout subscription forms fail when simple button layout is active.
* Bugfix: Checkout subscription forms crash when simple button layout is active and there is no valid plan selected.
* Bugfix: One-time payments fail when the form has an empty description.
* Bugfix: Leaves subscription status as 'Incomplete' when subscribing to a plan with a trial period.
* Bugfix: The plugin wouldn't let customers pay on inline forms with reCaptcha turned on and 3DS cards.
* Bugfix: Subscription forms with no valid plan don't return a user friendly error message.
* Bugfix: Subscription forms don't validate the email address in certain cases.
* Bugfix: The "Manage subscriptions" page doesn't display the subscriber's active subscriptions.
* Bugfix: The plugin logs errors into the web server log about checkout sessions it cannot verify.

= v5.0.2 (Sep 19, 2019) =
* Fixed issue related to billing address handling on checkout subscription forms.
* Fixed issue of subscription plans with trial period not working on inline subscription forms.
* Fixed issue of database patches not applied correctly on MySql 5.5 database servers.
* Fixed issue of delete local action not working properly for saved cards.

= v5.0.1 (Sep 13, 2019) =
* Fixed issue of checkout forms not redirecting back to the starting page after payment.
* Fixed issue of checkout one-time payment forms displaying an error message when submitting custom donation amounts.
* Now the plugin sets the billing address of customers on checkout subscription forms (because Stripe doesn't).

= v5.0.0 (Sep 12, 2019) =
* The plugin is SCA-compliant, please read our blog post: https://paymentsplugin.com/blog/wp-full-stripe-sca-ready .
* The "Manage subscriptions" feature now works without subscription data in the WordPress database (no need to import subscriptions).

= v4.2.0 (Aug 12, 2019) =
* Added %TRANSACTION_ID% placeholder to emails and thank you pages of all form types.
* Implemented WordPress authentication option for the "Manage subscriptions" feature.
* Now firing WordPress actions before and after cancelling subscriptions.
* Now showing the CSS selector on the "Appearance" tab of all form types for easier CSS customizations.
* Made the plugin demo mode more strict.

= v4.1.2 (Jun 29, 2019) =
* Bugfix: Updated the Freemius SDK to v2.3.0 to avoid a fatal error when installing the plugin on WordPress v5.2.x.

= v4.1.1 (Jun 25, 2019) =
* Refined form CSS based on feedback from our customers.

= v4.1.0 (Jun 13, 2019) =
* Modified plugin to be compatible with several licensing engines (EDD, Freemius).

= v4.0.3 (Jun 20, 2019) =
* Refined form CSS based on feedback from our customers.

= v4.0.2 (May 28, 2019) =
* IMPORTANT: WP Full Stripe v4.0.2 requires PHP 5.5 or greater
* IMPORTANT: WP Full Stripe v4.0.2 has new a form design, it's not compatible with the old design. Your custom CSS rules won't work.
* UPDATE ONLY IF you have tested v4.0.2 in your test/staging environment.
* Bugfix: Javascript and CSS files of the plugin weren't loaded on some websites
* Bugfix: The "Payment details" popover displayed "Null" when only one plan was added to a subscription form

= v4.0.1 (May 24, 2019) =
* IMPORTANT: WP Full Stripe v4.0.1 requires PHP 5.5 or greater
* IMPORTANT: WP Full Stripe v4.0.1 has new a form design, it's not compatible with the old design. Your custom CSS rules won't work.
* UPDATE ONLY IF you have tested v4.0.1 in your test/staging environment.
* Bugfix: Payment currency was always displayed as USD in popup one-time payment forms of the "Select amount from list" payment type
* Bugfix: Billing and shipping address was not set properly in some cases on popup forms

= v4.0.0 (May 23, 2019) =
* IMPORTANT: WP Full Stripe v4.0.0 requires PHP 5.5 or greater
* IMPORTANT: WP Full Stripe v4.0.0 has new a form design, it's not compatible with the old design. Your custom CSS rules won't work.
* UPDATE ONLY IF you have tested v4.0.0 in your test/staging environment.
* Professional, new look for all form types
* Professional, new look for the "Manage subscriptions" page
* Standard and compact inline one-time payment forms are merged and live on as inline forms with new, unified look
* Inline forms now use Stripe Elements for collecting card details
* Subscription forms can display plans as radio button list or dropdown
* Subscription forms have a new "Payment details" popover for subscription overview (setup fee, plan, VAT, total)
* One-time payment forms can display amounts as radio button list or dropdown
* Added donation look for one-time payments
* Vastly improved error feedback on all forms
* Stripe PHP client upgraded to v6.27.0

= v3.16.3 (Apr 30, 2019) =
* Fixed an issue of not displaying feedback after payment with certain billing countries
* Fixed an issue of not working when other plugins use Google reCaptcha

= v3.16.2 (Feb 14, 2019) =
* Added Google reCaptcha support (v2) for inline forms
* Added support for custom field placeholders in one-time payment form descriptions
* Fixed issue with the %PRODUCT_NAME% placeholder on one-time popup forms with the "Select amount from list" payment type
* Fixed HTML escaping issue for placeholders with single and double quotes in email notifications and on Thank you pages

= v3.16.1 (Oct 8, 2018) =
* Added verifications to make the CVC/CVV code required (even when Stripe doesn't require it)
* You can select more plans on a form due to increased database column size (from 255 characters to 2048 characters)
* Fixed an issue of cancelling subscriptions not working on the "Manage subscriptions" page when being logged in to WordPress
* Fixed an issue of subscription trials not working
* Fixed an issue of website assets not loading when WordPress is hosted on Windows webservers (css, js, and image files)

= v3.16.0 (Sep 3, 2018) =
* Added "Authorize & capture" support for one-time payments
* Reworked how the setup fee works so it's a proper invoice item now, and tax can be added on top of it
* Fixed an issue of customer name and billing address not set properly on Stripe invoices
* Renamed and moved the "Card captures" menu to the "Saved cards" menu
* Added webhook event handlers for one-time payment state changes (refunded, pending, expired, failed, captured)

= v3.15.1 (Jul 11, 2018) =
* IMPORTANT: Update to this version if you are using webhooks and subscriptions that end after certain number of charges
* Fixed an issue related to subscriptions not ending automatically
* Changed the way javascript files are loaded so the plugin is compatible with more themes

= v3.15.0 (May 25, 2018) =
* GDPR-compatible forms with an option to add a "Terms of use" checkbox
* Self-service area for subscribers to update credit card data, and cancel subscriptions (with Google reCaptcha protection)
* Configurable payment description for all one-time payment forms
* Changed the order of the Stripe API keys (publishable, secret) on the "Settings" page
* Made changes to be compatible with the WP Mandrill plugin

= v3.14.0 (Apr 20, 2018) =
* Card capture forms for customers to submit credit card data (so you can charge them later).
* Error message displayed upon form submit when secret and publishable API keys are entered in the wrong order.
* Updated the Stripe PHP client to v6.4.1
* Made subscription management compatible with v6.0 Stripe API (products as parents of subscription plans).
* Removed Alipay support temporarily.
* Removed Bitcoin support.
* "Lock email address field for logged in users" feature turned into "Fill in email address for logged in users".
* Fixed an issue related to not displaying error message when an empty form was submitted.
* Fixed an issue related to not saving the default billing country on subscription forms.
* Fixed an issue related to not displaying properly popup button labels containing single quote characters.
* Fixed the bug that the "Could not find payment information" error message was not localizable.

= v3.13.1 (Feb 12, 2018) =
* Fixed a subscription plan creation/editing/listing issue caused by a new Stripe API version.
* Fixed an issue related to optional custom fields.

= v3.13.0 (Jan 8, 2018) =
* Added shipping address support to popup forms (both one-time and subscription).
* Increased the maximum number of custom fields per form to 10 (used to be 5).
* The form name is displayed for each payment in WP admin.
* The form name is added as metadata to one-time payments and subscriptions.
* Performed small tweaks to make inline and popup form layouts alike (aligned labels to left, removed fieldset element).
* Removed security verification that caused nonce errors on cached websites.
* Condensed the billing address and the shipping address to one metadata each.
* For one-time payments, all metadata is added to the Stripe charge object (the Stripe customer object is left intact).
* For subscriptions, all metadata is added to the Stripe subscription object (the Stripe customer object is left intact).

= v3.12.1 (Nov 21, 2017) =
* Fixed the product description not being displayed on popup subscription forms.

= v3.12.0 (Nov 13, 2017) =
* Added tax (VAT) support to subscription forms.
* Added option for simple popup subscription button (no plan selector, no plan info label, no custom fields, no coupon field).
* Added option for selecting the display language of popup one-time payment forms, and popup subscription forms.

= v3.11.1 (Sep 5, 2017) =
* Fixed a billing address validation issue on inline one-time forms.

= v3.11.0 (Aug 22, 2017) =
* IMPORTANT! This release contains critical security fixes and critical bugfixes. Please update your Full Stripe installation as soon as possible!!!
* Added support for "custom amount" and "select amount from list" payment types to popup one-time payment forms (Stripe checkout forms).
* Updated the Stripe PHP client to the latest version (v5.1.1)

= v3.10.0 (Aug 18, 2017) =
* Added popup (Stripe checkout) support to subscription forms.
* Added custom field support to all form types (one-time and subscription, inline and popup).
* Fixed issues with zero-decimal currencies (like the Japanese Yen).
* Fixed WP admin URLs linking to Stripe charges and Stripe subscriptions.
* Fixed the value of the %AMOUNT% placeholder on subscription forms where both setup fee and plan fee have to be charged.
* Fixed an issue of not being able to select certain payment confirmation ("Thank you") pages for redirects

= v3.9.1 (April 19, 2017) =
* Fixed a bug on the edit page of popup forms in WP admin

= v3.9.0 (April 18, 2017) =
* Payment currency can be set per form.
* Payment currency can be set per subscription plan.
* Setup fee can be set per subscription plan.
* Split all form editor pages into tabs in order to make room for new features.
* Added the %DATE% placeholder token for email notifications.

= v3.8.2 (March 1, 2017) =
* Fixed a bug related to customizable "Thank you" (payment confirmation) pages

= v3.8.1 (February 26, 2017) =
* Fixed a bug related to PHP 5.3.x compatibility.

= v3.8.0 (February 24, 2017) =
* All forms are now responsive and mobile friendly.
* "Thank you" pages after payment are customizable with placeholder tokens.
* Payment types "Select amount from list" and "Custom amount" can be combined on one-time payment (and donation) forms.
* New option added to make custom fields mandatory.
* Minimum plugin requirements are verified at activation time.
* Added collision prevention code to handle those cases when other plugins load jQuery in a non-standard way.
* Fixed a bug with the %PRODUCT_NAME% placeholder when the payment type is "Select Amount from List".
* Fixed a bug with form names containing only digits.
* Fixed a bug with error messages when invalid card expiry date is provided.

= v3.7.5 (February 15, 2017) =
* Fixed an issue with payment descriptions containing commas when the payment type is "Select Amount from List".

= v3.7.4 (January 23, 2017) =
* Increased amount length from 6 digits to 8.
* The Stripe PHP client has been upgraded to v4.4.0 .
* Fixed a bug that caused the product description not properly being mapped to the %PRODUCT_NAME% placeholder on Stripe checkout forms.

= v3.7.3 (December 2, 2016) =
* The Stripe PHP client has been upgraded to v4.2.0 .

= v3.7.2 (November 24, 2016) =
* Fixed a bug related to missing button icons in WP Admin.
* Fixed a bug that prevented the plugin from being activated (class name collision with other plugins).
* Plan label handling modified to work with themes that remove empty &lt;p&gt;tags.

= v3.7.1 (November 16, 2016) =
* Error pane handling modified to work with themes that remove empty <p> tags.
* Fixed a bug that would prevent the plugin from displaying more than 100 subscription plans.
* Removed placeholders for the card and name fields on subscription forms

= v3.7.0 (November 2, 2016) =
* Any number of forms can be embedded into a page or post!
* The plugin can auto-update to the latest version with the click of a button!
* Form shortcode generator added for embedding forms easily into pages and posts (simple copy'n'paste)!
* AliPay support added for one-time payments on Stripe checkout-style payment forms.
* Subscriptions can now be deleted on the "Subscribers" page.
* Country dropdown has been added to the billing address on all form types.
* The "Action" column has been redesigned on all admin pages (iconified buttons).
* The "Payments" page has a new layout, it is more structured and more spacious.
* The "Payments" page has got a search box. Find payments based on customer's name and email address, Stripe customer id, Stripe charge id, or mode (live/test).
* The "Settings" page can now be extended by add-ons.
* "Newsfeed" tab has been added to the "About" page.
* Fixed an issue related to being unable to save subscription forms with selected subscription plan names containing spaces.
* The "Transfers" feature has been removed due to incompatibility with the latest Stripe API (will be reintroduced later).
* The Stripe client and API used by the plugin has been upgraded to v3.21.0 in order to be compatible with TLS 1.2.

= v3.6.0 (June 3, 2016) =
* Support for subscriptions that terminate after certain number of charges!
* Subscriptions can be cancelled from the “Subscribers” page.
* The “Subscribers” page has a new layout, it is more structured and more spacious.
* The “Subscribers” page has a search box. Find subscriptions based on subscribers’ name and email address, Stripe customer id, Stripe subscription id, or mode (live/test).
* The “Settings / E-mail receipts” page has a new layout for managing e-mail notifications (new email types coming soon).
* Now you can translate form titles and custom field labels to other languages as well.
* Stripe webhook support added for advanced features in the coming releases.
* Fixed an issue related to the value of the PLAN_AMOUNT token when a coupon is applied to the subscription.
* Fixed an issue related to plan ids, now they can contain comma characters.
* Improved error handling and error messages for internal errors.

= v3.5.1 (March 15, 2016) =
* Added PRODUCT_NAME token to email receipts (used when payment type is “Select Amount from List”)
* Added extra error handling for failed cards (declined, expired, invalid CVC).
* Fixed issue with long plan lists on subscription forms.

= v3.5.0 (February 21, 2016) =
* Added Bitcoin support for checkout forms!
* The e-mail field can be locked and filled in automatically for logged in users.
* Success messages and error messages are scrolled into view automatically.
* The spinning wheel has been moved next to the payment button on all form types.
* The lists on the "Payments" and "Subscribers" pages now are descending and ordered by date by default.
* Fixed an issue withX payment forms on WordPress 4.4.x: the submitted forms never returned.

= v3.4.0 (December 6, 2016) =
* New payment type introduced on payment forms: the customer can select the payment amount from a list.
* The “Settings” page is now easier to use, it has been divided into three tabs: Stripe, Appearance, and Email receipts.
* The e-mail receipt sender address is now configurable.
* All payment forms (payment, checkout, subscription) add the same metadata fields to the Stripe “Payment” and “Customer” objects.
* CSS style improvements to assure compatibility with the KOMetrics plugin.

= v3.3.0 (October 30, 2016) =
* The plugin is translation-ready! You can translate it to your language without touching the plugin code. (Public labels only)
* Usability improvements made to the currency selector on the “Settings” page.
* Improved error handling on all form types (payment, checkout, and subscription).
* Version number of the plugin is displayed on the “About” and “Help” pages in WP Admin.
* Confirmation dialog has been added to delete operations where it was missing.
* Fixed an issue on subscription forms with the progress indicator spinning endlessly, never returning.
* Fixed an issue on checkout forms with the CUSTOMERNAME token not resolved properly in email receipts.

= v3.2.0 (August 22, 2016) =
* Subscription plans on subscription forms can be reordered by using drag and drop!
* Subscription plans can be modified or deleted directly from WP Full Stripe.
* Page or post redirects can be selected using an autocomplete, no time wasted with figuring out post ids.
* Arbitrary URLs can be used as redirect URLs.
* Placeholder tokens for custom fields are available in email receipts.

= v3.1.1 (July 18, 2016) =
* Fixed a bug with Stripe receipt emails on subscription forms.

= v3.1.0 (June 25, 2016) =
* Now you can use plugin email receipts for all form types (payment, checkout, and subscription) !!
* New email receipt tokens: customer email, subscription plan name, subscription plan amount, subscription setup fee.
* Separate email template and subject fields for payment forms and subscription forms.
* Support for all countries supported by Stripe (20 countries currently).
* Support for all currencies supported by Stripe (138 currencies in total, number varies by country).

= December 30, 2014 =
* You can now use multiple checkout buttons on the same page!
* Checkout button styling can now be disabled (useful for theme conflicts).
* Some minor changes added for future extensions.

= December 5, 2014 =
* Removing form input placeholders as they conflict with some themes.
* SSN is no longer a required field for transfer forms.
* Support for KO Metrics added.
* Bugfix: settings upgrade properly when installing a new version of the plugin.

= November 4, 2014 =
* You can now add up to 5 custom input fields to payment & subscription forms!
* Subscribers and payment records can now be deleted locally (they remain in your Stripe dashboard).
* Lots of UI/UX improvements including appropriate table styling and useful redirects.
* Added livemode status to subscribers.
* Cardholder name correctly added to payment details.
