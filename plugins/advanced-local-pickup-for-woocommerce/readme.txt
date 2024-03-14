=== Advanced Local Pickup for WooCommerce ===
Contributors: zorem,kuldipzorem,gaurav1092
Donate link: 
Tags: woocommerce, local pickup, in store pickup, shipping, shipping options
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 4.0.1
License: GPLv2 
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The Advanced Local Pickup plugin helps you handle the in-store pickup more conveniently by extending the WooCommerce Local Pickup shipping method and creating a local pickup fulfillment workflow.

The plugin lets you change your WooCommerce orders status to “Ready for Pickup” and adds the pickup instructions to the email sent to your customers. When your customers come to pick up their order, you can change the order status to “Picked up” and optionally send email notification to the customer letting them know that the order has been picked up.

== Key Features ==

* Custom order status Ready for Pickup - notify your customers by email when their order is ready for pickup
* Custom Order Status Picked Up
* Custom Order Status email notifications 
* Customize the order status emails (subject, heading and content)
* Pickup Location setup - name, address, work hours and special instructions
* Customize the pickup display on the order status emails
* Add pickup instruction to the Processing order email
* Add pickup instruction to the order received page

== PRO Features ==

* Multiple Pickup Locations
* Pickup Appointments
* Limit Pickup Location by products & Categories
* Pickup Location notifications
* Pickup Location discounts
* Split the Work hours
* Allow Mixed Local Pickup and Shipping orders
* Pickup Appointments
* Pickup per item from multiple locations
* Local Pickup availability message
* Force Local Pickup
* Custom email notification templates

[Get the  Advanced Local Pickup PRO](https://www.zorem.com/product/advanced-local-pickup-pro/)

== Translations == 

The Advanced Local Pickup for WooCommerce plugin is localized/ translatable by default, we added translation to the following languages: 

* English - default, always included
* German
* Spanish (Spain)
* French (France)
* Hebrew
* Italian

== Compatibility ==

Check out [ALP’s list of plugins compatibility](https://docs.zorem.com/docs/advanced-local-pickup-free/compatibility/)

== Documentation ==

You can get more information, detailed tutorials on the [ALP documentation](https://docs.zorem.com/docs/advanced-local-pickup-free/).

== Installation ==

1. Upload the folder 'woo-advaned-local-pickup` to the `/wp-content/plugins/` folder
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to plugin setting under WooCommerce > Local Pickup


== Changelog ==

= 1.6.3 =
* Fix - the vulnerability issue of security
* Fix - the vulnerability issue of nonce in admin notice
* Dev - Tested with WooCommerce 8.5.2

= 1.6.2 =
* Fix - the vulnerability issue of security
* Dev - Tested with WooCommerce 8.5.1

= 1.6.1 =
* Fix - the HTML is no support in additional content in processing email
* Dev - Tested with WooCommerce 8.4.0

= 1.6.0 =
* Improved - Improve the settings design
* Dev - Tested with Wordpress 6.4
* Dev - Tested with WooCommerce 8.2.1
* Dev - Compatibility with PHP 8.2
* Fix - PHP message: PHP Deprecated warning
* Fix - the database error on plugin activation
* Fix - the vulnerability issue of security

= 1.5.5 =
* Improved - Improve the settings design
* Dev - Tested with Wordpress 6.3
* Dev - Tested with WooCommerce 7.9.0

= 1.5.4 =
* Enhancement - Improved the customizer design
* Fix - Translation string issue
* Fix - Html not supported in email content in customizer
* Dev - Tested with Wordpress 6.2
* Dev - Tested with WooCommerce 7.8.0

= 1.5.3 =
* Fix – Vulnerable to Cross Site Request Forgery (CSRF)
* Dev - Tested with Wordpress 6.1
* Dev - Tested with WooCommerce 7.5.1

= 1.5.2 =
* Dev - Tested with WooCommerce 7.4

= 1.5.1 =
* Fix - Uncaught Error: Call to undefined function wp_kses_post() 

= 1.5 =
* Dev - Tested with WordPress 6.1
* Dev - Tested with WooCommerce 7.1
* Enhancement - Added compatibility of HTML support in email content
* Enhancement - Added compatibility with High-Performance Order Storage
* Improved - Special instruction text WPML translation

= 1.4.1 =
* Dev - Tested with WordPress 6.0.1
* Dev - Tested with WooCommerce 6.7
* Enhancement - Improved the customizer design

= 1.4.0 =
* Dev - WC tested upto 6.3
* Enhancement - Setup a new customizer

= 1.3.6 =
* Dev - Wp tested upto 5.9
* Enhancement - Added a Docs, Review links on plugins page
* Fix - Translate for all the strings for all the languages
* Fix - issue of invalid argument supplied for foreach()

= 1.3.5 =
* Dev - WC tested upto 5.9
* Fix - the issue of the translation
* Fix - the issue of the js admin

= 1.3.4 =
* Dev - WC tested upto 5.8
* Fix - the issue of the all weekdays translatable

= 1.3.3 =
* Dev - WC tested upto 5.6
* Fix - the issue of the Uncaught ReferenceError: setCountryCookie is not defined
* Improved - Update Skin colors of the settings design

= 1.3.2 =
* Dev - WC tested upto 5.5.2
* Dev - WP tested upto 5.8
* Fix - Customizer fatal errors.
* Improved - code review.

= 1.3.1 =
* Fix - on activation errors.

= 1.3.0 =
* Improved - Settings Skin (header/menu) – Update the new general settings design
* Dev - WC tested upto 5.4.1

= 1.2.9 =
* Enhancement - competibility with SMSWOO
* Dev - WC tested upto 5.2

= 1.2.8 =
* Tweak - updated settings design.
* Dev - WC tested upto 5.1
* Dev - WordPress tested upto 5.7

= 1.2.7 =
* Tweak - updated settings design.
* Enhancement - Customizer setting option.

= 1.2.6 =
* Tweak - updated settings design.
* Enhancement - Free plugin not run if PRO is activated.

= 1.2.5 =
* Enhancement - updated design in setting.

= 1.2.4 =
* Enhancement - updated design in setting.
* Localization - Updated translations files

= 1.2.3 =
* Enhancement - updated design in setting.

= 1.2.2 =
* Enhancement - updated design in setting.
* Enhancement - added pro option of Add Local Pickup instructions on the Completed Renewal email (Subscriptions).

= 1.2.1 =
* Enhancement - updated design in setting.
* Enhancement - change label of "Time format" to "Display Time Format" in location setting.
* Enhancement - Display time format apply only on frontend(not admin).

= 1.2.0 =
* Enhancement - updated design in setting.
* Fix - issue translate “to” string.
* Fix - Fix Work Hours issue for 12 Hours format.
* Fix - Remove default “complete order” action button for pickup order.
* Dev - WC tested upto 4.8
* Dev - WordPress tested upto 5.6

= 1.1.9 =
* Fix - issue of two time display additional pick-up note.
* Fix - issue translate “to” string.
* Fix - Fix Work Hours issue for 12 Hours format.

= 1.1.8 =
* Fix - Fixed WP_User error in customizer.
* Enhancement - minor changes in design.
* Enhancement - Added Add-ons tab in settings.
* Enhancement - Change date text for 12 hours date format admin and frontend.

= 1.1.7 =
* Fix - Custom Order Statuses mail not working
* Fix - Fix Working hours issue on frontend when settings change from WordPress general settings
* Fix - Fix {customer_first_name} variable issue in Ready For Pickup email
* Enhancement - Change date text for 12 hours date format
* Enhancement - Added Phone number field in location form
* Enhancement - Re-design of settings

= 1.1.6 =
* Dev - Added compatibility with WooCommerce 4.5
* Fix - Work Hours translation issue
* Enhancement - set work days list as a general setting of WordPress
* Enhancement - Re-design of settings
* Enhancement - Re-assign ready for pickup & pickup order to other order status on uninstall
* Enhancement - added Admin notice - ask for review

= 1.1.5 =
* Localization - added translation files for Italian
* Dev - WordPress tested upto 5.5

= 1.1.4 =
* Localization - added translation files for Danish
* Localization - Updated french translations files
* Fix - No Order Again Button for Picked Up Status

= 1.1.3 =
* Dev - Added compatibility with WooCommerce 4.3.0
* Localization - Added Work days in translations

= 1.1.2 =
* Enhancement - Added Available placeholders section in Ready for pickup and picked up email customizer
* Enhancement - Added option in location settings for select time format of work hours
* Dev - Added 'Ready for Pickup' and 'Picked up' text in translations
* Dev - Updated code for better security

= 1.1.1 =
* Fix - Fixed Ready for pickup and picked email not sending issue from orders page actiona panel

= 1.1.0 =
* Fix - Fixed Ready for pickup and picked email not sending issue from orders page actiona panel

= 1.0.9 =
* Enhancement - Removed am/pm from hour display on settings
* Enhancement - Separate State and Country in location settings
* Enhancement - Added plugin uninstall message on plugins page
* Localization - Updated french translations files

= 1.0.8 =
* Fix - Fixed multiple email sending issue from bulk action if order status change to ready for pickup and picked up
* Localization - Updated template langage file and added all days of the week

= 1.0.7 =
* Enhancement - Added validation in work hours save in settings page
* Enhancement - rename Locations tab - Pickup Locations
* Enhancement - Do not display the Pickup Instruction header in the email/myaccount/order-received if the location Pickup Instruction field is empty.
* Enhancement - Added actions button in orders panel for change order status from "Processing" to "Ready for Pickup" and "Ready for Pickup" to "Picked up"
* Fix - Fixed bug Email content reset when settings save
* Fix - Fix error - Uncaught Error: Call to undefined function array_key_first()
* Localization - Updated translation template file and language files

= 1.0.6 =
* Fix - fixed save issue of "Additional content on processing email in case of local pickup orders" in settings page
* Fix - fixed email subject and heading issue in ready for pickup and picked up email customizer
* Localization - change text domain from "woo-local-pickup" to "advanced-local-pickup-for-woocommerce" 
* Localization - added translation files for German, Spanish, French and Hebrew

= 1.0.5 =
* Fix - fixed bug in Orders page bulk action drop down
* Fix - Fix issue of additional content not change in Ready for Pickup and Picked Up email customizer
* Enhancement - Updated design of settings page
* Localization - Added language .pot and .po file

= 1.0.4 =
* Fix - Fixed warning in pickup instructions in order details page and ready for pickup email
* Fix - Fixed bug so additional instruction only display in processing email if shipping method is local pickup
* Fix - Fixed all bug of Work Hours section and and if not select any days Work Hours section will not display
* Enhancement - Added bulk order action for change order status to Ready for pickup and Picked up 

= 1.0.3 =
* Fix - Fixed error in customizer - Call to undefined method Automattic/WooCommerce/Admin/Overrides/OrderRefund::get_billing_first_name()
* Fix - Fixed warning in pickup instructions in email

= 1.0.2 =
* Enhancement - Updated design of settings page
* Enhancement - Updated design of Pickup Instruction display in email and orders page
* Enhancement - Added option in Ready for pickup email customizer for change Pickup Instructio heading, set padding, background color and border color of Pickup Instruction table
* Dev - Change position of display pickup instruction in email orders page

= 1.0.1 =
* Enhancement - Added a option for select different pickup time for different days

= 1.0.0 =
* intial version.
