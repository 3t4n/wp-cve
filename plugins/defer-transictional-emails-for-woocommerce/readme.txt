=== Defer Transactional Emails for WooCommerce | Speed up the WooCommerce checkout ===

Contributors:      giuse
Requires at least: 4.6
Tested up to:      6.4
Requires PHP:      5.6
Stable tag:        0.0.2
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Tags:              speed up, checkout


It will speed up the checkout of WooCommerce by deferring the transactional emails. No settings, just install it and activate it.


== Description ==

It will speed up the checkout of WooCommerce by deferring transactional emails.

In many environments the emails that are sent when the user places an order slow down the checkout process.
Deferring those emails can drastically speed up the checkout in those cases.

The difference in terms of performance will be huge in some environments and not noticeable in others.
Anyway, if you have a slow checkout, try this plugin. If the slowness was caused by the sending of the emails, you will probably solve the issue by deferring the emails.

To defer the transactional emails, just install this plugin and activate it. It has no settings.


== How to speed up the WooCommerce checkout by deferring the transactional emails ==
* Install Defer Transactional Emails for WooCommerce
* Activate it


== How to further speed up the checkout in WooCommerce ==
* Install and activate <a href="https://shop.josemortellaro.com/downloads/checkout-speedup-for-woocommerce/">Checkout Speedup For WooCommerce</a>
* Go to Checkout Speedup For WooCommerce => Main Settings => Disable the theme during the checkout process
* Go to Checkout Speedup For WooCommerce => Main Settings => Defer the transactional emails
* Go to Checkout Speedup For WooCommerce => Plugins active during checkout => Disable all the unneeded plugins that WordPress loads during the checkout process



== Changelog ==

= 0.0.2 =
*Checked WordPress 6.4

= 0.0.1 =
*Initial release
