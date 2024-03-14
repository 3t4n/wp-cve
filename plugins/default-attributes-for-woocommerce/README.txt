=== Default Attributes for WooCommerce ===
Contributors: condless
Tags: Variations, Attributes, Stock, Default
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 1.1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce plugin that sets default attribute for variable products automatically if only 1 option is in-stock.

== Description ==

WooCommerce plugin that sets default attribute for variable products automatically if only 1 option is in-stock.

[Documentation](https://en.condless.com/default-attributes-for-woocommerce/) | [Contact](https://en.condless.com/contact/)

Variable product of T-shirt with color attribute (White/Blue/Black) and size attribute (S/M/XL):
If only Black T-shirts are in-stock: default color (Black), default size (none).
If only Small T-shirts are in-stock: default color (none), default size (Small).
If only White and XL T-shirts are in-stock: default color (White), default size (XL).

= How It Works =
1. Before product variations is displayed to the customer, the plugin will check each attribute, and if there is only 1 in-stock option it will be set as the default.

= Features =
* **Stock**: Set option as default when it's the only 1 in-stock.
* **Per Attribute**: Set specific option as default in all products with that attribute (from the Edit Attribute screen)
* **Top**: Set as default the first option of each attribute.
* **Out of stock**: Disable out of stock variations.
* **Add to Cart button**: In archive pages display for variable products the attribute name instead of: "Select options".
* **Select options**: In single product pages remove the select options text if default attribute is set.

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
1. Default Attributes Plugin Settings
1. Per Attribute Settings
1. Product page default attribute

== Frequently Asked Questions ==

= Why the default attribute was not set properly? =

The slug and the names of the attributes and the terms must be in latin letters.

= Why the default attribute was not set by the stock? =

* Wait 1 minute for the plugin cache to be refreshed (or disable cache- instructions in docs)
* Not all the in-stock variations belong to the same attribute option
* The product has more variations than the configured max amount to calculate for (set via plugin settings)

= How to set site-wide default per attribute? =

Dashboard => Products => Attributes => Edit => Default Form Values. Attribute must be used for variation in at least 1 product.

== Changelog ==

= 1.1.7 - March 1, 2024 =
* Enhancement - WordPress version compatibility

= 1.1.6 - October 12, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.5 - June 30, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.4 - March 18, 2023 =
* Enhancement - WooCommerce version compatibility

= 1.1.3 - December 22, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.1.2 - August 19, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.1.1 - June 1, 2022 =
* Dev - Filter hook to disable plugin's cache

= 1.1 - April 10, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.0.9 - February 27, 2022 =
* Enhancement - WooCommerce version compatibility

= 1.0.8 - December 25, 2021 =
* Enhancement - WooCommerce version compatibility

= 1.0.7 - October 20, 2021 =
* Enhancement - WooCommerce version compatibility

= 1.0.6 - July 28, 2021 =
* Dev - WP Compatibility

= 1.0.5 - June 30, 2021 =
* Dev - WP Compatibility

= 1.0.4 - April 7, 2021 =
* Feature - Filter products to apply

= 1.0.3 - February 27, 2021 =
* Feature - Global and local attributes compatibility

= 1.0.2 - February 13, 2021 =
* Feature - Filter transients expiration time

= 1.0.1 - December 23, 2020 =
* Feature - Default per attribute

= 1.0 - August 29, 2020 =
* Initial release
