=== WooCommerce Gold Price ===
Contributors: Gabriel Reguly
Donate link: http://omniwp.com.br/donate/
Tags: WooCommerce, Gold Price, Gold Based prices
Requires at least: 3.5
Tested up to: 6.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
WC requires at least: 3.0
WC tested up to: 7.6.1
Requires PHP: 5.2

Adds Gold Price to WooCommerce plugin, tested up to WooCommerce 4.0

== Description ==

### Add Gold Price to WooCommerce

This plugin enables easily changing prices of gold products, based on their weigth/purity and the gold value.

### New

Now one can also add a spread % value and/or a fee value.

### Support

Please use the WordPress.org forums for community support as I cannot offer support directly for free.

If you want help with a customisation, [hire a developer!](http://omniwp.com.br/hire-a-developer/)



== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
1. Activate the plugin
1. Insert/Edit a simple product in WooCommerce
1. Mark it as Gold Product and select its 'karat' value of 24, 22, 18 or 14 to indicate the purity of the gold
1. Add a Spread % and/or a fee to be added 
1. Fill in its weight value
1. Update the gold price at WooCommerce -> Gold Price and all products with 'karat' field will have their prices updated
1. Price is calculated as ( ( Karat value * weight ) + spread % ) + fee, e.g. $11 * 1g (= $11) + $2.20 (20% of $11) (= $13.20) + $5.00 = $18.20

== Screenshots ==

1. Product seetings for Gold products
1. Updating gold values and product prices

== Frequently Asked Questions ==

= How many karats can be used? =

* Only 24k, 22k, 18k and 14k.

= Do I need to calculate/inform the price when adding a new gold product? =

* No, unless you are not going to update the gold prices after adding the product.

= Can I have a sale price for gold products? =

* Yes, but the sale price will be removed when the gold price is updated.

= I see no products under "Gold priced products" ( WooCommerce -> Gold Price ) =

* This is because you have no gold products, e.g. products marked as Gold product. See edit product page.

= What "Product was on sale, can't calculate sale price" means? =

* Means that the product no longer is on sale, as the plugin can't calculate sale prices and just removed it.
There is a handy link to edit the product, if one whishes to put it on sale again.

== Changelog ==

= 7.6.1 2023.05.03 =
* Updated for WordPress 6.2 and WooCommerce 7.6.1;
* Fixed error for empty Spread/Fee options
= 4.2 2020.06.19 =
* Updated for WordPress 5.4 and WooCommerce 4.2;
= 4.0 2020.04.09 =
* Updated for WordPress 5.4 and WooCommerce 4.0;
* New interface for marking product as gold, not longer using 'karat' Custom Fields
* Added option for Spread %
* Added option for Fee
* Added log 
= 2.6.6 2016.10.22 =
* Updated for WordPress 4.6.1 and WooCommerce 2.6.6, added a message when a gold product has a non-calculated price.
= 2.4.4 2015.08.18 =
* Updated for WordPress 4.3 and WooCommerce 2.4, small cosmetic changes.
= 2.1.1 =
* Fixed admin screen where at products list it always showed 24k price, thanks cutter2222 for informing the issue
= 2.1 =
* Updated to be compatible with WooCommerce 2.1
= 1.0.3 =
* Fixed 'Product has zero weight, can't calculate price based on weight.' but product had weight
* Added 14k option.
* Improved message when there are no gold products.
= 1.0.2 =
* Fixed error 'You do not have sufficient permissions to access this page.'
* Added 18k option.
* Added message when there are no gold products.
= 1.0.1 =
* Fixed 'posts_per_page'
= 1.0 =
* Initial plugin release.

== Upgrade Notice ==

= 7.6.1 =
* Updated for WooCommerce 7.6.1
= 4.2 =
* Updated for WooCommerce 4.2
= 4.0 =
* Updated for WooCommerce 4.0
= 2.6.6 =
* Updated for WooCommerce 2.6
= 2.4.4 =
* Updated for WooCommerce 2.4
= 2.1.2 =
* Error fixing, all users of version 2.1 must upgrade.
= 2.1 =
* Users of WooCommerce 2.1 must upgrade.
= 1.0.3 =
* All users must upgrade.
= 1.0.2 =
* All users must upgrade.
= 1.0.1 =
* All users must upgrade.
= 1.0 =
* Enjoy it.
