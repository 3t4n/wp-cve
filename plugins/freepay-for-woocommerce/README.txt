=== FreePay for WooCommerce ===
Contributors: Freepay
Tags: gateway, woo commerce, freepay, free pay, integration, woocommerce, woocommerce freepay, payment, payment gateway, psp
Requires at least: 4.0.0
Tested up to: 6.4.3
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrates your FreePay payment gateway into your WooCommerce installation.

== Description ==
With Woo FreePay, you are able to integrate your FreePay gateway to your WooCommerce install. With a wide list of API features including secure capturing, refunding and cancelling payments directly from your WooCommerce order overview. This plugin supports also the subscriptions plugins "WooCommerce Subscriptions" and "Subscriptions of WooCommerce".

== Installation ==
1. Upload the 'freepay-for-woocommerce' folder to /wp-content/plugins/ on your server.
2. Log in to Wordpress administration, click on the 'Plugins' tab.
3. Find Woo FreePay in the plugin overview and activate it.
4. Go to WooCommerce -> Settings -> Payment Gateways -> FreePay.
5. Fill in all the fields in the "FreePay account" section and save the settings.
6. You are good to go.

== Dependencies ==
General:
1. PHP: >= 5.4
2. WooCommerce >= 4.0

== Changelog ==
2.0.2 Automatic busting of transaction cache when order is automatically captured on status changed to completed
2.0.1 Added module setting for controlling how long should WooCommerce cache information about transaction
2.0.0 HPOS support and support for manual payment/renewal of failed subscriptions. Now the subcription will pick the new card information for future renewals.
1.8.11 Added support for WooCommerce Blocks
1.8.10 Removed flag for support for WooCommerce HPOS as further chnages are required
1.8.9 Flaged support for WooCommerce HPOS
1.8.8 If there is an error in auto capturing an order (on completed status) the status of the order will be updated to Failed so the customer would be able to identify that there was a problem with the automatic capture
1.8.7 Added payment complete fallback fix for orders from failed recurring billing
1.8.6 Fix for Thank you page when paying a failed order
1.8.5 Version bump for plugin repository fix (3)
1.8.4 Version bump for plugin repository fix (2)
1.8.3 Version bump for plugin repository
1.8.2 Fix for missing requests file
1.8.1 Fix for WooCommerce Subscriptions safe redirect change
1.8.0 Added logic to handle separation of subscription and authorization identifiers in Freepay. Added check for WooCommerce installation for multi-site environment.
1.7.8 Additional fix for fetching order in callback handler
1.7.7 Additional fix for fetching order in callback handler
1.7.6 Small fix for fetching order in callback handler when the order is found based on key instead of id
1.7.5 Added handling for racing condition between order confimation and payment callback handler
1.7.4 Added autoselect currency as a transition step to removing of currency selection in the settings of the plugin
1.7.3 Small adjustment to confirmation email being sent on confirmation page and on callback handler
1.7.2 Small fix for Notice warning on Thank you page for subscription orders without initial amount
1.7.1 Small fix for double confirmation email
1.7.0 Added handler of AcceptUrl that trys to validate the order and sets the status of the order to processing if it is valid
1.6.10 Fix for subscription collision
1.6.9 Fix for error in checking active plugin
1.6.8 Small fixes for Subscriptions for WooCommerce plugin
1.6.7 Capture order amount when order is completed is changed, so if the order is partially captured then the rest of the amount won't be automatically captured. When doing a partial capture and the option to capture order when status is completed is enabled, there is a warning message reminding the user that the rest amount won't be captured to prevent unintended overcharge of customers
1.6.6 Fix for Subscriptions for WooCommerce plugin when handling standard products
1.6.5 Small fix for handling a missing subscription ID in cancelled subscriptions (Subscriptions for WooCommerce plugin)
1.6.4 Added support for Subscriptions for Woocommerce plugin
1.6.3 Added Visa/Dankort icon as available payment icon
1.6.2 Small fix for invalid subscription frequency calculation sometimes happening with invalid periods
1.6.1 Added support for multiple captures. Added apple pay icon as available payment options.
1.6.0 Added support for Freepay API v2
1.5.4 Small fix to handle null reference on cancellation of order
1.5.3 Added setting for showing google pay icon on checkout
1.5.2 Fix for change payment method logic for WooCommerce Subscriptions
1.5.1 Added 'Autodetect' option for payment window language setting. The payment window language will use the user's browser settings
1.5.0 Added support for the localization plugin WPML. Added WPML option to the currency selection in the plugin's settings
1.4.2 Renamed default payment option title in checkout from 'Freepay' to 'Betalingskort'
1.4.1 Fix for an error in payment generation options field
1.4.0 Fix for invalidating cache on capture from the orders overview. Added support for single subscription creation and order authorization transactions
1.3.6 Added support for subscription creation and amount authorization in a single transaction (gateway call)
1.3.5 Fix for error notice on payment transaction info
1.3.4 Added order key identifier in callback as fallback option if order number doesn't match order_id
1.3.3 Handling of missing shipping country information
1.3.2 Added Decline URL setting
1.3.1 Version support bump
1.3.0 Added support for test payments
1.2.0 Added version meta data to payment gateway call
1.1.1 Added better error handling for subscriptions
1.1.0 Added support for subscriptions
1.0.3 Added capture button in order listing actions
1.0.2 Fix for card selection issue and language setting for payment window
1.0.1 Minor fix to transaction validation
1.0.0 Initial version of the plugin