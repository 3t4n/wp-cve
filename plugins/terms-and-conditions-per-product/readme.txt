=== Terms & Conditions Per Product ===
Contributors: giannis4, termsperproduct
Tags: terms and conditions, terms of service, WooCommerce, product, legal
Requires at least: 5.4
Tested up to: 6.4.1
Requires PHP: 7.2
Stable tag: 1.2.11
Author: Terms and Conditions Per Product
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Configure specific Terms and Conditions per WooCommerce product, category, or tag.

== Description ==

The **Terms and Conditions per Product** plugin allows you to easily **add terms and conditions to specific products** on your WooCommerce store. This can be particularly useful for products that require **special terms and user acceptance**.

With this plugin, you can **easily create and manage terms and conditions** for each individual product, **ensuring** that your customers are **fully informed** and **agree** to the specific terms before making a purchase.

This plugin is a **must-have** for any WooCommerce store selling products with special terms, as it helps ensure that both you and your customers are **fully informed** and **protected**.

**Important:** This plugin is an extension of the [WooCommerce](https://wordpress.org/plugins/woocommerce/) plugin and it cannot function independently.

**Check out the [Plugin Homepage](https://tacpp-pro.com/) for [documentation](https://tacpp-pro.com/documentation/) and the [changelog](https://tacpp-pro.com/changelog/).**

### Main Features

&#9989; The ability to add terms and conditions to specific products on your WooCommerce store.
&#9989; A customizable terms and conditions checkbox on the checkout page, so customers must agree to the specific terms before completing their purchase.
&#9989; Option to ensure users open the Terms and Conditions URL on the checkout page before being able to check the terms.
&#9989; Option to show the terms on the product page.
&#9989; Option to hide the default WooCommerce terms if custom terms are applicable.
&#9989; Log User Acceptance in order details. **[Premium]**
&#9989; The option to add terms and conditions per product category or tag. **[Premium]**
&#9989; The option to show the terms in a modal/popup. **[Premium]**
&#9989; Great support.

### Filters and Actions

Multiple hooks will allow you to customize the plugin even further.

**Like this plugin? Please consider leaving a [5-star review](https://wordpress.org/support/plugin/terms-and-conditions-per-product/reviews/#new-post).**

**Do you have any questions: [Contact us](https://tacpp-pro.com/support/).**

== Installation ==

1. Upload the "Terms and Conditions Per Product" plugin into the directory `wp-content/plugins/`.
2. Enable the "Terms and Conditions Per Product" plugin.


== Frequently Asked Questions ==

= Is this plugin compatible with all WordPress themes? =

Compatibility with all themes is practically impossible, because they are too many and differently built. Still, if themes are developed according to WordPress and WooCommerce guidelines and practices then the plugin should work as intended.

= Is this plugin compatible with WooCommerce Checkout Blocks? =

Unfortunately, no. WooCommerce has not migrated to WC Blocks, some important hooks that are required for the functionality of this plugin.

= Can I translate the Terms and Conditions per Product? =

You can translate [Terms & Conditions Per Product](https://translate.wordpress.org/projects/wp-plugins/terms-and-conditions-per-product/) into language.

= I am facing a problem using this plugin. What can I do? =

Post detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/terms-and-conditions-per-product/) or [post a ticket](https://tacpp-pro.com/support/) on our site [Premium].

= Where can I find Documentation for this plugin? =

On our website, you can find detailed information in the [documentation section](https://tacpp-pro.com/documentation/).

= What are Terms and Conditions =

Terms and Conditions are the rules and regulations that govern the use of a website or a service. They are also known as Terms of Service or Terms of Use.

== Screenshots ==
1. Extra custom field on every WooCommerce product.
2. The added Terms and Condition checkbox on the Checkout page.
3. The error notice if the checkbox is not selected.
4. Product variations' custom terms and conditions fields.
5. Adding Terms to product categories
6. Opening terms in a modal

== Changelog ==
= 1.2.11 =
* Fix: Remove the checkout enqueued JS and functionality from pages that do not contain the Gutenberg checkout block.

= 1.2.10 =
* Update: Enable Block Checkout functionality
* Update: Enable HPOS compatibility for admin edit order page.
* Remove: Information page

= 1.2.9 =
* Fix: Enable HPOS flag to avoid wrongfully flagging the plugin as HPOS incompatible

= 1.2.8 =
* Update: Styling changes
* Update: Freemius SDK to 2.6.0
* Fix: Remove duplicate Get premium version message.
* Declaration: WC Checkout blocks incompatibility

= 1.2.7 =
* Check: WordPress 6.4 compatibility
* Check: WooCommerce 8.2.1 compatibility
* Feature: Make single product terms function static so it can be removed using WP hooks

= 1.2.6 =
* Feature: Hide the default WooCommerce terms when there are custom ones.
* Update: Freemius SDK to 2.5.10

= 1.2.5 =
* Feature: Force users to open the term's link before checking the terms.
* Feature Premium: Add user acceptance log
* Check: WordPress 6.2 compatibility
* Update: Freemius SDK to 2.5.6

= 1.2.3 =
* Check: WooCommerce 7.3.0 compatibility
* Update: Freemius SDK to 2.5.3
* Update: Texts

= 1.2.2 =
* Feature: Added option to show terms in the product page.
* Feature Premium: Add terms and conditions to product tags.
* Feature Premium: Open WooCommerce terms in a modal.
* Optimization: Load assets only on the required pages.
* Styling: Added switches instead of checkboxes on the admin page.

= 1.2.1 =
* Fix: PHP 7.3 backward compatibility.

= 1.2.0 =
* Feature: Added Premium Version
* Feature: Added custom text for simple product terms
* Feature Premium: Add terms and conditions to product categories
* Feature Premium: Show product terms in modal
* Update: WooCommerce 7.1 compatibility check
* Update: Added new filters to replace old ones starting with gkco_
* Add admin notifications

= 1.1.0 =
* Update: WordPress 6.1 compatibility check

= 1.0.15 =
* Update: Readme.txt and WordPress version

= 1.0.13 =
* Fix: PHP 8 compatibility issue

= 1.0.12 =
* Fix: Not reaching meta variable outside of class

= 1.0.11 =
* Fix: Hooked actions

= 1.0.9 =
* Feature: Added partial links to Terms' text using [link][/link] tags

= 1.0.8 =
* Feature: Added extra terms and conditions input fields per variation (WC variable products).
* Styling: Remove float from checkout fields.

= 1.0.7 =
* Fixed duplicate Terms and Condition checkboxes when the same product appeared more than 1 time in a cart (different items).

= 1.0.6 =
* WP test

= 1.0.5 =
* Fixed an issue for variable products
* Moved the Custom terms and conditions field from "General" to the "Advanced" product tab.

= 1.0.4 =
* Add extra translation features and extra filters

= 1.0.3 =
* Remove custom fields from external product types

= 1.0.2 =
* Fix missing style

= 1.0.1 =
* Links updated

= 1.0.0 =
* First Edition release

== Upgrade Notice ==

There is no need to upgrade just yet.
