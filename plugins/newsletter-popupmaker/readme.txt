=== Popup Maker Addon for Newsletter ===
Tags: newsletter, popup, subscription
Tested up to: 5.8
Stable tag: 1.0.3
Contributors: satollo
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds support to Popup Maker for Newsletter subscription forms

== Description ==

This simple and little plugin integrates the [Popup Maker](https://wordpress.org/plugins/popup-maker/) plugin with forms 
of [Newsletter](https://wordpress.org/plugins/newsletter/) plugin, usually generated via
shortcodes. It follows the Popup Maker standard integration methods.

Note: this plugin should be considered as developed indipendently from Newsletter and Popup Maker. For support, contribution and so on
use the support area provided by wordpress.org. Much appreciated.

= Other Newsletter plugin integrations =

* [BuddyPress](https://wordpress.org/plugins/newsletter-buddypress)

== Frequently Asked Questions ==

= How to configure =

Please see the screenshots for steps explained below.

Step 1. Just create a popup and in the page content add the shortcode [newsletter_form]. This shortcode just shows a subscription form 
(you can customized it using the many attributes available – like labels, button color and so on). 
[Read more about shortcode attributes](https://www.thenewsletterplugin.com/documentation/subscription/subscription-form-shortcodes/).
 
Step 2. Setup the popup rules to not reopen after a subscription has been submitted.

= Which Newsletter shortcodes can I use? =

As far as I know, every kind of shortcodes work. All details about shortcodes and subscription forms creation
should be found [here](https://www.thenewsletterplugin.com/documentation/subscription/subscription-form-shortcodes/).

= Does the popup open for "old" subscribers? =

Actually yes, there is no way to block the popup if an "old" subscriber is revisiting the site. Maybe I can find a solution
(I'm open for ideas and/or code to integrate!)

== Screenshots ==

1. Create a popup using Popup Maker and add the Newsletter shortcode to display a subscription form
2. Configure the rules to block the popup reopening after the subscription

== Changelog ==

= 1.0.3 =

* WP 5.8 compatibility

= 1.0.2 =

* Fixed links in the plugin header

= 1.0.1 =

* Fixed PopupMaker detection on activation
