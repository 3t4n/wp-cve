=== Feedback Company ===
Contributors: feedbackcompany, janmiddelkoop
Tags: reviews, shopping, customers, woocommerce, webshop, feedback, rich snippets
Requires at least: 6.0
Tested up to: 6.3
Stable tag: 3.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates Feedback Company review widgets and order registration into Wordpress/WooCommerce

== Description ==

This plugin enables you to display trusted, verified store review widgets on your Wordpress website, as well as product review widgets in your WooCommerce webshop. You can also send automatic review invitations to customers. This plugin requires an API key from Feedback Company to function.

* Compatible with WooCommerce (product reviews & review invitations)
* Compatible with WPML and Polylang (multilanguage support)

After installation of the plugin, you will find configuration instructions on the settings screen.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/the-feedback-company` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Obtain an API key from Feedback Company
4. Use the Settings->Feedback Company screen to configure the plugin
5. Add the widgets to your website via Appearance or the shortcodes.

== Frequently Asked Questions ==

Please contact Feedback Company with any questions you may have.

== Changelog ==

= 3.3.2 =
* released 2023-08-22
* added compatibility with WooCommerce HPOS (high performance order storage)

= 3.3.1 =
* released 2023-07-03
* improves API compatibility

= 3.3 =
* released 2022-11-21
* products that are given for free (price of 0 currency or less) are no longer considered eligible for review

= 3.2.1 =
* released 2022-09-30
* bugfix: plugin accidentally loaded non-inline product review widgets when product reviews were disabled

= 3.2 =
* released 2022-02-24
* added compatibility with Gutenberg product grid view blocks
* bugfix: fixed fatal error with WooCommerce in instance where global $product variable is not available
* bugfix: fixed fatal error when WooCommerce order line is not associated with a product

= 3.1.1 =
* released 2021-06-29
* bugfix: settings page form now properly displays an error message on validation problems

= 3.1 =
* released 2021-03-12
* added new debug mode for customer support to provide better remote assistance
* bugfix: oauth access token is refreshed unnecessarily when accessing admin dashboard

= 3.0.4 =
* released 2021-02-02
* bugfix: check if the get_sku method exists before calling it on a WooCommerce order item

= 3.0.3 =
* released 2021-01-13
* bugfix for 3.0 configuration migration filling up error log if there is no multilanguage plugin installed

= 3.0.2 =
* released 2021-01-12
* bugfix for order registrations failing with disabled reminders
* bugfix for widgets not showing up in admin dashboard
* bugfix for admin tooltips not clearly visible

= 3.0.1 =
* released 2020-12-03
* bugfix release for problems with product review widgets disappearing

= 3.0 =
* released 2020-11-26
* added support for multilanguage plugins WPML and Polylang
* added the ability to configure a different API key per language for widgets and review invitations
* added the ability to configure different widget settings per language
* Wordpress 5.6 and WooCommerce 4.7.x compatibility
* PHP 8.0 compatibility
* lots of small PHP code optimizations
* dropped support for 1.x style widgets (over two years old) to make plugin more lightweight

= 2.5.1 =
* released 2020-09-08
* small bugfix for 3rd-party plugin detection on multisite
* WooCommerce compatibility boosted to 4.4.x

= 2.5 =
* released 2020-08-12
* small bugfixes to product review structured data output
* Feedback Company product review data is now always added to WooCommerce structured data

= 2.4.5 =
* released 2020-07-13
* experimental: option to add Feedback Company product reviews to WooCommerce structured data (rich snippets)
* Wordpress 5.5 and WooCommerce 4.3.0 compatibility

= 2.4.4 =
* released 2020-04-10
* small bugfix with product widgets on product pages

= 2.4.3 =
* released 2020-04-07
* small bugfix for working with WooCommerce Gutenberg product blocks

= 2.4.2 =
* released 2020-03-17
* boosted WooCommerce compatibility to 4.0.0

= 2.4.1 =
* released 2020-02-17
* fixed PHP notices when WP_DEBUG mode is active

= 2.4 =
* released 2020-01-20
* added support for new Bar and Floating widgets
* added options to enable/disable review invitation and reminder
* merchant reviews widget is now called Badge widget
* bugfix for excessive API calls on product review enabled check

= 2.3.2 =
* released 2019-12-02
* throttle failed API calls to fix slowdown and excessive calls

= 2.3.1 =
* released 2019-03-29
* improved check for enabled product reviews
* plugin no longer overwrites WooCommerce product reviews if there is no access to Feedback Company product reviews
* added button for removing error logs

= 2.3.0 =
* released 2019-03-18
* enable Product review widgets and Product review toggle element.

= 2.2.0 =
* enable WordPress Multilanguage plugin with widgets registration
* enable WordPress Multilanguage with order registration

= 2.1.0 =
* removed BUYSMART widgets and references
* fixed an issue for changed credentials and generating widgets

= 2.0 =
* released 2018-06-19
* Entirely new version supporting the latest Feedback Company API and features
* Support for new Feedback Company widgets with rich snippets integrated
* Integrates Feedback Company product reviews into WooCommerce
* New possibilities for automatically sending review invitations to customers
* Backwards compatible with version 1.1 and 1.0

= 1.1 =
* Released 2018-02-12
* Updated rich snippets to schema.org 3.3
* BUYSMART shopper protection logo added to widgets
* URL for external shop reviews now always correct
* Logo images now support retina displays

= 1.0.4 =
* Released 2018-01-04
* Bugfixes in score and review slider widgets

= 1.0.3 =
* Released 2017-07-31
* Functionality added for automatic feedback calls on WooCommerce orders
* The plugin "Feedback Company WooCommerce Connector" has been integrated into this one
* Fixes in HTML code for W3C compliance

= 1.0.2 =
* Released 2017-06-15
* External links to feedbackcompany.com have been updated
* Images have been updated with new Feedback Company logo

= 1.0.1 =
* Released 2017-05-12
* Fixed testimonial display when added as a widget instead of shortcode
* Added options to admin settings to configure widget/shortcode titles

= 1.0 =
* Released 2017-05-08
* First release
