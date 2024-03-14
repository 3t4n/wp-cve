=== Church Tithe WP ===
Contributors: churchtithewp
Tags: donations, church, giving, recurring payments, stripe, apple pay, google pay, credit card
Donate link: https://churchtithewp.com
Requires at least: 4.8
Tested up to: 5.9
Requires PHP: 7.0
Stable tag: trunk
License: GPLv3
License URI: https://opensource.org/licenses/GPL-3.0

Smoothly, easily, and quickly accepting online tithes and donations is an important thing for every church today. Church Tithe WP makes it simple for any church to accept tithes online in seconds.

== Description ==
Smoothly, easily, and quickly accepting online tithes and donations is an important thing for every church today. Church Tithe WP makes it simple for any church to accept tithes online in seconds.

On the cutting edge of payment technologies through the power of Stripe, you can accept Apple Pay, Google Pay, standard credit cards, recurring payments, and more with a beautiful and responsively designed payment form.

= Here's a few of the things included in Church Tithe WP: =

* **Recurring payments**
Users can choose to tithe once, or on a recurring basis automatically.

* **Stay-on-site payments**
Users can pay without ever leaving your website.

* **Multi-currency**
Allow your users to pay in their own currency.

* **User dashboard**
Your users can log in to print their receipts, review their plans, or cancel their plans at any time.

* **Apple Pay**
On Apple devices that support Apple Pay, users can tithe with a single tap (or "look") on their device.

* **Google Pay**
Users of Google Chrome with saved credit cards can pay with a single tap.

* **3D Secure and Strong Customer Authentication (SCA)**
You are protected from fraudulent purchases and chargebacks, and also comply with SCA regulations in the EU.

* **Everything is included**
Church Tithe WP is a fully featured plugin, including recurring payments, Stripe, multi-currency, customer management dashboard, admin controls, and more. New features will also be included at no extra cost.

= So what does it cost? =
Church Tithe WP is totally free to install. Church Tithe WP makes money through a 1% transaction fee, so you can install it and keep it forever, without paying anything. The 1% fee is in addition to any credit card fees applied by Stripe. If you are a registered non-profit, you may be eligible for a lower rate at Stripe. See this link: https://support.stripe.com/questions/fee-discount-for-nonprofit-organizations

View all features and details on [churchtithewp.com](https://churchtithewp.com)

== Installation ==

= Step-by-step instructions =

1. In your WordPress dashboard go to "Plugins", "Add New". Search for "Church Tithe WP" and click "Install". Then click "Activate".
2. Find "Church Tithe WP" on the left sidebar in your WordPress dashboard and click on it.
3. Follow the step-by-step "wizard" to make sure everything that needs to be configured, is. We made sure it covers all of the most important things so you don't need to do any guesswork during setup.
4. Once you've completed the set-up wizard, use the [churchtithewp] shortcode on any page/post, or click the "How to show a tithe form" after the wizard to set more options, like the popup modal.

== Frequently Asked Questions ==

= Does this include Stripe as a payment gateway for free? =
Yes. Stripe is the only payment gateway that works with Church Tithe WP and it is included for free.

= Are there any up-sells or extensions? =
No. Everything you will need to accept single and recurring tithes is included in Church Tithe WP for free.

= Do I need an SSL certificate in order to use this? =
Yes. Most webhosts are now able to set this up for you at no extra cost. Ask them about "LetsEncrypt".

= How much does it cost to use this plugin? =
Church Tithe WP is totally free to install. Church Tithe WP makes money through a 1% transaction fee, so you can install it and keep it forever, without paying anything. The 1% fee is in addition to any credit card fees applied by Stripe. If you are a registered non-profit, you may be eligible for a lower rate at Stripe. See this link: https://support.stripe.com/questions/fee-discount-for-nonprofit-organizations

= What about GDPR Compliance? =
Aside from their email address, this plugin stores absolutely no personally identifiable information about your users when they make a payment. No names, credit card information, IP addresses, nothing. Only emails are stored in WordPress core's "users" table, and can be erased using WordPress's normal erase-personal-data process. We recommend adding a note to your privacy policy stating that the only information recorded during a payment is the email address, and that all other data is handled by Stripe.com.

= Does this plugin fully support 3DSecure and the SCA regulation in the EU? =
Yes.

= Where and how do I show the payment form on by website? =
We typically recommend creating a new page called something like "Give", and placing this shortcode on it [churchtithewp]. If you'd prefer it to open in a popup modal, use this shortcode:
[churchtithewp mode="button" link_text="Give Online" open_style="in_modal"]

= Is there a discounted rate for non-profits? =
If you have non-profit status, Stripe requests that you reach out to them to discuss this. See: [https://support.stripe.com/questions/fee-discount-for-non-profit-organizations](https://support.stripe.com/questions/fee-discount-for-non-profit-organizations)

== Screenshots ==
1. The tithe form using Apple Pay.
2. The purchase receipt, which shows without needing a page reload.
3. The login prompt which shows if a user click "Manage Payments" while being logged out.
4. The area to enter the one-time login code from their email.
5. The list of plans which belong to this logged-in user.
6. The single-details for a plan, with the option to cancel.
7. The admin-side controls for the payment form.
8. The admin-side settings for Stripe.
9. The admin-side list of all transactions completed by all users.
10. The admin-side list of all plans by all users.

== Changelog ==

= 1.0.0.17 - 2020-10-14 =
* Ensure Apple Pay domain verification file is always up to date upon creation.

= 1.0.0.16 - 2020-09-20 =
* Improved Apple Pay domain verification.

= 1.0.0.15 - 2020-08-12 =
* Ensure name of user is saved for future payments.

= 1.0.0.14 - 2020-06-11 =
* Improve handling of upfront card errors, like insufficient funds, and provide a helpful response to the user.

= 1.0.0.13 - 2020-05-04 =
* Fix: Ensure trailing slashes are added to endpoints to fix browser redirects changing POST requests to GET requests with no payload.

= 1.0.0.12 - 2020-03-31 =
* Fix: Fixed caching issues for logged-out users.

= 1.0.0.11 - 2020-03-16 =
* Fix: Ensure that only the payment_method is sent to Stripe, as opposed to payment_method and source.

= 1.0.0.10 - 2019-10-01 =
* Fix: Made sure the email test in the onboarder is editable.

= 1.0.0.9 - 2019-08-05 =
* Store the stripe customer ID as user meta.

= 1.0.0.8 - 2019-07-25 =
* Fix: Fixed issue when getting the account country code from Stripe.

= 1.0.0.7 - 2019-07-24 =
* Improved: Offset user generation until after Stripe paymentIntent successfully set up.
* Improved: Added a validation callback function which improves security.
* Improved: Upgraded to React Stripe Elements 4.0.
* Improved: Fully switched from Stripe Sources to Stripe Payment Methods for subscription/customer defaults for SCA integration.
* Improved database caching hash method.

= 1.0.0.6 - 2019-06-26 =
* Fix: The shortcode-specific text was not overriding the default for the link text.
* Improved: The permalink handling has improved to better handle page builders.

= 1.0.0.5 - 2019-06-25 =
* New: Improved accuracy of Apple Pay health check.

= 1.0.0.4 - 2019-06-19 =
* Fix: Make sure URLs work properly on WordPress pages that don't have permalinks (like category pages).
* Fix: The notice on the frontend if no SSL installed was not properly being output.
* New: Fee thresholds added.

= 1.0.0.3 - 2019-06-15 =
* Fix: Make sure payment popup modal opens above everything on the page no matter what.

= 1.0.0.2 - 2019-06-11 =
* New: Improved frontend output/guidance if Stripe not-yet connected

= 1.0.0.1 - 2019-05-23 =
* Fix: Label around Postal Code field on mobile wasn't operating properly.

= 1.0.0.0 - 2019-05-11 =
* New: Initial Release
