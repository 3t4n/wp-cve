=== Platy Syncer Woocommerce to Etsy ===
Contributors: inonkp
Tags: etsy, woocommerce, syncer, export, sync, syncing, products, orders, ecommerce
Requires at least: 3.5.0
Tested up to: 6.4
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 6.2.4
Sync Products, Inventory, and Orders between Woocommerce and Etsy.

== Description ==

[youtube https://www.youtube.com/watch?v=Dd0x9nBolbM]

Platy Syncer for Etsy is a lightweight syncer between Woccommerce and Etsy. It is designed to be simple and effective.

Platy Syncer for Etsy is quick to set up and easy to use. No need to move outside of the product table page. Platy Syncer works by opening a popup and allowing you to immediately start syncing.

Platy Syncer for Etsy is an innovative plugin that is different than other WordPress plugins control experience. Platy Syncer settings are managed completely without the need for reloading the page. This makes for a smooth control experience.

Platy Syncer free version is suitable for most lightweight stores.

Platy Syncer advanced and pro versions can bring you even closer to your desired goals.

To learn more about the capabilities of Platy Syncer for Etsy – read on.

== Features ==
Product syncing: Sync and update simple and variable products.
Product images: Sync product images.
Template support: Title formatting, market attributes, tags, materials, and price manipulation! Upto 2 templates on the free version.
Connections: Map between Woocommerce categories to Etsy categories and sections, Map Woocommerce shipping classes to Etsy shipping templates. Upto 5 connections on the free version.
Stock management. Sync with shop stock or fixed quantity.
Per product data – set individual product data through the Woocommerce product edit screen.
Product table control: Sync directly through the Woocommerce product table, no need to go to a different page!
Select all products: Supports selecting all products from the Woocommerce product table.
Product filtering: Filter by previously synced items as well as currently unsynced items.
Multiple shop support – as many shops as you’d like.
Draft Mode (Recommended) – We recommend always using draft mode. Once you are happy with the draft activate your products through the Etsy interface.
Developer friendly – modify products, inventory, and images.

== Advanced Features ==
Unlimited Product Syncing. 
Unlimited Templates – as many templates as you need.
Unlimited connections – as many connections as you want.
Etsy Product Links – Allows you to put links to your Etsy products from Woocommerce products pages.
Variation Images – Sync variation images (check the help page for more info on this).
Fine Tune Syncing – Fine Tune your syncing for specific property syncing.

== Pro Features ==
Order Syncing – Sync orders from Etsy to your Woocommerce order table manually or automatically.
Auto Stock Syncing – 2-way auto stock syncing.
Attribute Syncing – You sync product attributes to Etsy.

[youtube https://www.youtube.com/watch?v=Kj_oo_ughCA]

== Getting Started ==

[youtube https://www.youtube.com/watch?v=ccSOZt-21bI]

Follow these steps to get started:
1) Setup your shop in the Shops page.
2) In Platy Syncer, under connections, setup a default Etsy category.
3) In Platy Syncer, under connections, setup a default Etsy shipping template. If you dont have a shipping template, set one up on etsy.
4) In Platy Syncer, setup a template.
5) Start syncing!

== Screenshots ==
1. Platy Syncer enables you to create templates for you products.
2. Platy Syncer enables you to easily manage categories, sections and shipping templates - by using "Connections".
3. Platy Syncer works directly through the Woocomerce product table, with a simple-to-use interface.

== Changelog ==
= 6.2.4 =
* Allowing to process shortcodes in descriptions

= 6.2.0 =
* This is meant to work with the latest Woocommerce features. If you encounter any bugs, please do not be lazy - let me know.

= 6.1.9 =
* Order syncing should skip errors

= 6.1.8 =
* Order syncing shouldnt crash due to any error

= 6.1.7 =
* Added filter for order status.

= 6.1.6 =
* Tested for WP 6.3.*

= 6.1.5 =
* Tested for WP 6.2.*

= 6.1.4 =
* Stripping shortcodes from description.
* Added discounts to order views.

= 6.1.3 =
* Added a bulk action to unlink items from Etsy.

= 6.1.2 =
* Empty price resolves to 0.
* Fixed bug effecting simple product price and quantity.

= 6.1.1 =
*  Updated "When Made" attribute to 2023.

= 6.1.0 =
* Added caching to improve performance.
* Added shipping method to order syncing.

= 6.0.2 =
* removed product attributes cannot be set message.

= 6.0.1 =
* fix for bug which crashes existing users.

= 6.0.0 =
* New Pro Feature: Attributes Syncing.

= 5.1.2 =
* fixed reauthentication process.

= 5.1.1 =
* fixed bug where you need to save stock settings twice.
* fixed bug causing log cleaning hook to multiply.

= 5.1.0 =
* New feature: Ignore tag errors optionally.
* New feature: Make products personalizable through the template or per product.
* Fix: Auto Deactivation for stocke management should (finally) work properly.

= 5.0.4 =
* fixed auto order sync failure
* added payment method and payment method title to orders

= 5.0.3 =
* Added paid date and payment method to orders.
* Removed underscores from HTML elements: em, i, ins.
* Fixed issue causing early product sync limit reached issue.

= 5.0.2 =
* Order sync items now connect to their respective woo products and their variations.

= 5.0.1 =
* Fixed error causing bad sql syntax on activation.

= 5.0.0 =
* Limited the amount of syncable products in the free version to 50.
* There are now three type of users - free, advanced and pro.
* Each type has a different set of options available with pro having all.
* Added option to aggregate variation quantities for product level stock management.
* Fixed error causing crash on authentication refresh.
* Fixed log and product table primary key warnings.

= 4.4.1 =
* Default behavior on stock aggregation reverted to original.

= 4.4.0 =
* Order image sync error does not crash syncing.
* Stock syncing aggregates quantities for product level stock management.
* Old receipts do not get double synced.
* Should never cause stock to fall lower than zero.

= 4.3.5 =
* Added better logs for product linking.

= 4.3.4 =
* Notice: Added important notice about the plugin free version.

= 4.3.3 =
* Fix: Better order sync logs.
* Fix: Stock syncing ignores pending orders.

= 4.3.2 =
* Fix: Fix for undefined varaible selected tid.
* Fix: Order sync queries Etsy for at least the last day.

= 4.3.1 =
* Fix: better logging for communication errors.

= 4.3.0 =
* Fix: Fixed images in incosistent order error.
* Fix: Fixed duplicate product syncing from sync button on edit page.
* Feat: Added logs to image syncing.
* Feat: Added Etsy link to sync button in product edit page.

= 4.2.1 =
* Fix: The sync button on product edit should not appear before shop is validated.

= 4.2.0 =
* Feature: Added sync button on product edit page.
* Feature: Added SKU to order items.
* Feature: Added status bar for auto syncing problem detection.
* Fix: Order items Woo links point to edit page.

= 4.1.0 =
* Pro Feature: Added advanced description modifiers.
* Feature: Added links to Woo products on order page.

= 4.0.3 =
* Fix: matching product varaitions by SKU as well.

= 4.0.2 =
* Fix: Order syncing product linking issue.

= 4.0.1 =
* Fix: Fixed issue causing auto order syncing to silently fail.

= 4.0.0 =
* Fix: Countries in order syncing are taken by ISO code for better integration with Woocommerce.
* Fix: Order syncing includes shipping method chosen by customer.
* Fix: Stock sync link to logs fixed on help page.
* Feature: added optional max quantity limit for stock syncing.
* Fetaure: added input fields for variation exclusion.

= 3.9.5 =
* Fix: fixed crash when order syncing to shop with global tax enabled.

= 3.9.4 =
* Added hourly order syncing option.

= 3.9.3 =
* fixed no such function str_start_with crash.

= 3.9.2 =
* fixed help not appearing

= 3.9.1 =
* added promotion admin notice.

= 3.9.0 =
* 2-way Auto stock syncing feature added for pro version
* Product linking feature added to allow for existing shops onboarding.

= 3.3.3 =
* Bug fix - Support for variations on all attributes in case of different attribute name/labels.

= 3.3.2 =
* Bug fix - Support for variations on all attributes.
* Bug fix - Variation images bug fix.

= 3.3.1 =
* Bug fix - 2020-2022 in the when made field bug fix.

= 3.3.0 =
* Dont sync duplicate images.
* Fixed errors from third party namespace collisions.
* Out of stock on variations occur only when ALL variations are out of stock.
* New lines are not removed in the description.
* More informative syncing errors.

= 3.2.3 =
* Fine tuned conversion between html and text descriptions.
* Fixed multiple shops bug.

= 3.2.2 =
* Better handling of product description html conversion to text.

= 3.2.1 =
* Fixed pro link not working on the syncer.
* Fixed undefined variable on go pro api.
* Added _product_id meta for better integration on order sync.

= 3.2.0 =
* New pro feature: Variation Images (please read the help section about this feature).
* New pro feature: Fine Tune Syncing enables you to sync specific product fields.

= 3.1.1 =
* Variable products quantities and skus may vary
* Fixed public etsy link blocking other descriptions.

= 3.1.0 =
* Warnings and notices should not appear for WP_DEBUG.
* New Pro feature: Put Etsy product links on your Woocomerce product pages.

= 3.0.1 =
* Fixed warnings from empty indexes.
* Fixed plugin update error which doesnt allow successful authentication.

= 3.0.0 =
* Updated to Etsy latest API.

= 2.3.2 =
* Minor template bug fix.

= 2.3.1 =
* Critical activation bug fixed.

= 2.3.0 =
* Tested up to WordPress 5.8.1
* Tested up to Woocomerce 5.7.1
* Fixed bug: long template descriptions are now possible.

= 2.2.1 =
* Fixed setup bug.

= 2.2.0 =
* Plugin now requires api keys set by the user.

= 2.1.0 =
* Now with order syncing

= 2.0.0 =
* Whole new look for platy syncer
* Tested up to WordPress 5.7
* Fixed out of memory bug from getting Etsy categories

= 1.1.7 =
* minor fixes

= 1.1.6 =
* fixed crash from https://wordpress.org/support/topic/cant-activate-plugin-107/#post-14020803

= 1.1.5 =
* Added support for multiple languages. The default is taken from the site language.
* Tested upto WordPress 5.6
* Added better keyword title enhancements such as removing duplicate words from title and title capitalizing.

= 1.1.4 =
* Added sku modifier to title and description
* SKUs now appear for simple products.

= 1.1.0 =
* Added unique title words option
* Added title word capitalizing.

= 1.0.0 =
* Initial release.