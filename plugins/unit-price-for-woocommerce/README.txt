=== Unit Price for WooCommerce ===
Contributors: condless
Tags: unit, kg, decimal, vegetables
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce plugin for configuring products which are sold by units but priced by weight.

== Description ==

WooCommerce plugin for configuring products which are sold by units but priced by weight.

[Documentation](https://en.condless.com/unit-price-for-woocommerce/) | [Contact](https://en.condless.com/contact/)

For example: fish, watermelon, cabbage.

= How To define products which are sold by units but priced by weight  =
1. In the plugin settings enable the 'Quantity Units' option
1. Go to the edit product screen
1. Set the price of 1 unit of the product in the price field (General tab)
1. Set the weight of 1 unit of the product in the weight field (Shipping tab)
1. Select 'Weight' in the 'Quantity Units' option (Unit Price tab)

= How It Works =
1. The customer choose how many items he needs from the product
1. On order creation the item quantity is recalculated based on its weight
1. The shop owner will be able to update the quantity of it from the edit order screen after he weighs the item

For example:
1. The customer purchased 1 Salmon fish
2. The configured weight of Salmon fish is 3kg, when order is created the quantity of the fish will be modified automatically from 1 to 3
3. The shop owner will weight the actual fish, find out it's 3.2kg, so he will change the quantity in the order from 3 to 3.2, the total price of the item will be updated automatically

Note: It's recommended to use with Authorized / Delayed Payment method so the shop owner will be able to charge the exact amount after he weights the products, the order status should be 'Pending payment' / 'delayed payment'.

= Features =
* **Quantity Units**: Config products which are sold by units but priced by weight (suitable for fish store and butcher).
* **Quantity Step**: Set decimal quantity step per product/variation- for products which are sold by weight (per kg/gram, suitable for supermarket, deli and bakery, selling fruits, vegetables, nuts).
* **Quantity Suffix**: Set quantity suffix per product/variation.
* **Price Quantity**: Display the price per specific quantity per product/variation.
* **Subtotal**: Display the product price by the selected quantity in real-time.
* **Shop Page Quantity**: Allow to choose product quantity on archive pages.

== Installation ==

= Minimum Requirements =
WordPress 5.2 or greater
PHP 7.0 or greater
WooCommerce 3.4 or greater

= Automatic installation =
1. Go to your Dashboard => Plugins => Add new
1. In the search form write: Condless
1. When the search return the result, click on the Install Now button

= Manual Installation =
1. Download the plugin from this page clicking on the Download button
1. Go to your Dashboard => Plugins => Add new
1. Now select Upload Plugin button
1. Click on Select file button and select the file you just download
1. Click on Install Now button and the Activate Plugin

== Screenshots ==
1. Product Shipping Settings
1. Purchased item as displayed to customer
1. Purchased item as displayed to admin
1. Product as displayed to customer

== Frequently Asked Questions ==

= How to config products which are sold by weight and not by units? =

Use the 'Quantity Step' option, the 'Quantity Units' option should not be used.

= How to sell the same product by both units and weight? =

Create variable product and config each variation seperately.

= Can you give an example of how to config products? =

Salmon (sold by units but priced by weight): Price (General tab)- 45 (per unit), Weight (Shipping tab)- 3 (per unit), Quantity Units (Unit Price tab)- 'weight'.

Tomatoes (sold by weight- kg): Price (General tab)- 2 (per kg), Quantity Step (Unit Price tab)- 0.1, Quantity Suffix (Unit Price tab)- kg.

Nuts (sold by weight- gram): Price (General tab)- 0.03 (per gram), Quantity Step (Unit Price tab)- 100, Quantity Suffix (Unit Price tab)- gram, Price Quantity (Unit Price tab)- 100, Price Suffix (Unit Price tab)- /100g.

= How to manage the stock? =

By the units that the product is configured (kg/gram).

= Why sometimes there are many digits in the quantity field when using decimal Quantity Step? =
Try using the WooCommerce built-in quantity buttons template or change to another quantity step.

= How to fix the 'The totals of the cart item amounts do not match order amounts' error? =

Tax: mostly the 'Prices entered with tax' and 'Round tax at subtotal level' options should be enabled.
Line Items: maybe prevent sending the line items to the payment gateway.
product price * product weight of products which are sold by units but priced by weight should not exceed 2 dp.
subtotal (include tax, exclude discount) of order line items should not exceed 2 dp (modify its quantities to fix it).
A tool which notify about exceeding prices is available.

== Changelog ==

= 1.2 - March 1, 2024 =
* Enhancement - WordPress version compatibility

= 1.1.9 - December 15, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.8 - October 12, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.7 - June 30, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.6 - March 18, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.5 - December 22, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.1.4 - August 19, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.1.3 - June 1, 2022 =
* Enhancement - Quantity Units option for Length, width and height were removed.

= 1.1.2 - April 10, 2022 =
* Dev - Cart Items Count fix for items sold by weight
* Fix - typo in hook filters: upw_qauntity_base_selector, upw_qauntity_base_trigger, upw_qauntity_on_trigger

= 1.1.1 - February 27, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.1 - December 25, 2021 =
* Enhancement - OceanWP compatibility

= 1.0.9 - October 20, 2021 =
* Enhancement - WooCommerce version compatibility

= 1.0.8 - July 28, 2021 =
* Dev - WP Compatibility

= 1.0.7 - June 29, 2021 =
* Feature - Archive quantity filter

= 1.0.6 - May 20, 2021 =
* Feature - Price format

= 1.0.5 - April 7, 2021 =
* Feature - Price for display

= 1.0.4 - March 12, 2021 =
* Feature - Quantity suffix

= 1.0.3 - February 03, 2021 =
* Fix - Edit order with decimal quantity error

= 1.0.2 - January 27, 2020 =
* Feature - Auto update quantities

= 1.0.1 - December 23, 2020 =
* Feature - Price suffix

= 1.0 - November 18, 2020 =
* Initial release
