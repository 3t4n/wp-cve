=== Better Variation Price for Woocommerce ===
Contributors: josserand
Tags: woocommerce, product, variable, variation, price
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replace the Woocommerce variable products price range with the lowest price and update it on variation change.

== Description ==

You can activate any of these features at will:

* **Show lowest price:** Replace the ugly price range on your Woocommerce variable products with the lowest price
* **Update the main price:** Update the main price with the selected variation's price, instead of displaying a barely visible price somewhere below the description
* **Remove *clear* link:** Remove the *clear* link that appears when you select a variation

== Frequently Asked Questions ==

= How do I access the settings page? =

Settings can be found under Woocommerce -> Settings -> Products -> Better Variation Price
Alternatively, you can access the settings from the installed plugins page

= Can I remove the *From:* in the variable product price? =

You can add this style to your theme's css or to the *Additional CSS* section of the WordPress Customizer:
> .price-from { display: none; }