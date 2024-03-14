=== Custom Price Labels for WooCommerce ===
Contributors: wpwham
Tags: woocommerce
Requires at least: 4.1
Tested up to: 6.2
Stable tag: 2.5.12
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create any custom price label for any WooCommerce product.

== Description ==

WooCommerce Custom Price Labels plugin lets you add any price label to any WooCommerce product.

Labels can be set **globally** for all products, or locally on **per product** basis.

Optionally you can select if you want to override global price labels with per product labels (if set), or combine global and local labels.

You can also use included **bulk price labels editor tool** to modify multiple individual products labels.

Labels can be added at different **positions**:

* before the price
* after the price
* instead of the price

Each label can be customized to be shown or hidden **by page type**:

* home page
* products page (i.e. archives)
* single product page
* related products
* all pages
* cart page only

Additionally for **variable products** labels can be customized to be shown or hidden for:

* main price
* all variations

You can also show label for selected **user roles** only, or hide label for selected user roles.

= Feedback =
* We are open to your suggestions and feedback - Thank you for using or trying out one of our plugins!
* Drop us a line at [www.wpwham.com](http://www.wpwham.com).

= More =
* Visit the [Custom Price Labels for WooCommerce plugin page](https://wpwham.com/products/custom-price-labels-for-woocommerce/).

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to "WooCommerce > Settings > Custom Price Labels".

== Frequently Asked Questions ==

= What are the differences between free and Pro versions? =

Free vs Pro comparison table is [here](https://wpwham.com/products/custom-price-labels-for-woocommerce/).

== Screenshots ==

1. Custom Price Labels for WooCommerce - General Dashboard.
2. Custom Price Labels for WooCommerce - Global Labels (all products).
3. Custom Price Labels for WooCommerce - Global Labels - Visibility Options.
4. Custom Price Labels for WooCommerce - Per Product Labels Options.
5. Custom Price Labels for WooCommerce - Per Product Labels (product's edit page).
6. Custom Price Labels for WooCommerce - Per Product Labels - Custom Price Label Bulk Editor Tool.

== Changelog ==

= 2.5.12 - 2023-07-15 =
* FIX: PHP 8.2 notices.

= 2.5.11 - 2022-05-31 =
* UPDATE: added compatibility with PHP 8.0.
* UPDATE: updated .pot file for translations.

= 2.5.10 - 2021-04-12 =
* UPDATE: bump tested versions

= 2.5.9 - 2021-01-21 =
* UPDATE: bump tested versions.

= 2.5.8 - 2020-09-17 =
* UPDATE: bump tested versions.

= 2.5.7 - 2020-08-25 =
* UPDATE: display our settings in WC status report.
* UPDATE: updated .pot file for translations.

= 2.5.6 - 2020-06-08 =
* UPDATE: bump tested versions

= 2.5.5 - 2020-03-23 =
* UPDATE: bump tested versions

= 2.5.4 - 2020-02-23 =
* UPDATE: bump tested versions

= 2.5.3 - 2019-12-17 =
* UPDATE: bump tested versions

= 2.5.2 - 2019-11-15 =
* UPDATE: bump tested versions

= 2.5.1 - 2019-09-14 =
* UPDATE: bump tested upto versions

= 2.5.0 - 2018-10-23 =
* UPDATE: updated .pot file for translations

= 2.4.3 - 2018-09-11 =
* Dev - Using `wc_help_tip()` function for tooltips.
* Dev - Code refactoring.

= 2.4.2 - 2018-09-10 =
* Dev - "Contributors" and "Author URI" updated.

= 2.4.1 - 2018-08-05 =
* Dev - Admin settings descriptions updated.
* Dev - Plugin link updated.

= 2.4.0 - 2017-04-19 =
* Dev - WooCommerce v3.x.x compatibility - `$product->id`.
* Dev - WooCommerce v3.x.x compatibility - `woocommerce_get_variation_price_html` filter.
* Dev - Visibility - "All pages" replaced with "All pages (except homepage)".
* Dev - Custom Price Label Bulk Editor Tool - Restyled.
* Dev - readme.txt updated: screenshots, faq etc.
* Dev - Filter rewritten.
* Dev - `coder.fm` links changed to `wpcodefactory.com`.

= 2.3.0 - 2017-03-23 =
* Dev - Custom Price Label Bulk Editor Tool - `WP_Query` optimized to return `ids` only.
* Dev - Per Product Custom Price Labels - "Disable Options" option added.
* Dev - Per Product Custom Price Labels - "Hide on" checkboxes replaced by multiple select (dropdown box).
* Dev - Per Product Custom Price Labels - "Wrap Per Product Custom Price Labels" options added.
* Dev - General - "Disable custom price labels for search bots" option added.
* Dev - Settings divided to separate sections. "Reset Section Settings" option added.
* Dev - "Enable section" option added (to both global and per product price labels).
* Dev - "Show on" options added (to both global and per product price labels).
* Dev - "Hide on single product page except main product price (e.g. related)" option added (to both global and per product price labels).
* Dev - Language (POT) file updated.
* Dev - Code refactoring.
* Dev - Descriptions etc. updated.

= 2.2.1 - 2017-03-08 =
* Dev - Global Custom Price Labels - "Visibility on Site" options added.
* Dev - Language (POT) file updated.

= 2.2.0 - 2017-02-01 =
* Dev - User Roles to Hide/Show options added.
* Dev - Global Custom Price Labels - Textarea settings fields replaced with custom textarea fields.
* Dev - Language (POT) file updated.
* Dev - Minor code refactoring.
* Dev - readme.txt updated.

= 2.1.2 - 2017-01-26 =
* Dev - "Override Global Price Labels with Per Product Labels" option added.

= 2.1.1 - 2016-12-27 =
* Dev - Version system added.
* Fix - `load_plugin_textdomain()` function moved from `init` hook to constructor.
* Dev - Language (POT) file updated.
* Dev - readme.txt updated.

= 2.1.0 - 2016-05-24 =
* Dev - Translations - POT file uploaded. "Text Domain" and "Domain Path" added to the plugin's header.
* Dev - Multisite support added.
* Dev - "Custom Price Label Bulk Editor Tool" added.
* Dev - Plugin renamed from "WooCommerce Custom Price Label" to "Custom Price Labels for WooCommerce".

= 2.0.1 - 2015-08-05 =
* Dev - Description in readme.txt extended.
* Dev - Description added to General settings tab.

= 2.0.0 - 2015-08-05 =
* Dev - Global labels added.
* Dev - Per product options: Hide on all pages, Hide on cart page only, Hide for main (variable) price, Hide for all variations.
* Dev - Per product options: Before the price unlocked.
* Dev - Major code refactoring. Settings are moved to "WooCommerce > Settings > Custom Price Label".

= 1.0.2 =
* Variable products bug fixed

= 1.0.1 =
* Minor bug fixed

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
