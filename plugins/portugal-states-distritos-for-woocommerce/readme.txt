=== Portugal States (Distritos) for WooCommerce ===
Contributors: webdados, ptwooplugins
Tags: woocommerce, states, portugal, distritos, districts, distrito, district, postal code, postcode, código postal, address, morada
Requires at least: 5.4
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 3.5

This plugin adds the Portuguese "States", known as "Distritos", to WooCommerce and sets the correct address format for Portugal.

== Description ==

This plugin adds the 18 Portuguese "States" (known as "Distritos") plus the 2 Autonomous Regions (known as "Regiões Autónomas", Madeira and Açores) to WooCommerce.

Also sets the right name for "Districts" and the correct "Postcode"/"City" order and the correct address format for Portugal.

And... that’s pretty much it.

= Features: =

* Adds the Portuguese "States", known as "Distritos", to WooCommerce
* Sets the correct address format for Portugal, including the correct "Postal Code"/"City" positioning on the checkout

= Do your customers still write the full address details manually on the checkout? =

Activate the automatic filling of the address details at the checkout, including street name and neighbourhood, based on the postal, avoiding incorrect data at the time of shipping, with our plugin [Portuguese Postcodes for WooCommerce](https://ptwooplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/)

The Portuguese Postcodes plugin can also do a district-postcode cross validation on checkout and guarantee they match.

= Are you already issuing automatic invoices on your WooCommerce store? =

If not, get to know our new plugin: [Invoicing with InvoiceXpress for WooCommerce](https://wordpress.org/plugins/woo-billing-with-invoicexpress/)

= Other (premium) plugins =

Already know our other WooCommerce (premium) plugins?

* [Portuguese Postcodes for WooCommerce](https://ptwooplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/) - Automatic filling of the address details at the checkout, including street name and neighbourhood, based on the postal code
* [Invoicing with InvoiceXpress for WooCommerce](https://wordpress.org/plugins/woo-billing-with-invoicexpress/) - Automatically issue invoices directly from the WooCommerce order
* [DPD Portugal for WooCommerce](https://ptwooplugins.com/product/dpd-portugal-for-woocommerce/) - Create shipping and return guide in the DPD webservice directly from the WooCommerce order
* [Feed KuantoKusta for WooCommerce](https://ptwooplugins.com/product/feed-kuantokusta-for-woocommerce-pro/) - Publish your products on Kuanto Kusta with this easy to use feed generator
* [Simple WooCommerce Order Approval](https://ptwooplugins.com/product/simple-woocommerce-order-approval/) - The hassle-free solution for WooCommerce orders approval before payment
* [Shop as Client for WooCommerce](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/) - Quickly create orders on behalf of your customers
* [Taxonomy/Term and Role based Discounts for WooCommerce](https://ptwooplugins.com/product/taxonomy-term-and-role-based-discounts-for-woocommerce-pro-add-on/) - Easily create bulk discount rules for products based on any taxonomy terms (built-in or custom)
* [DPD / SEUR / Geopost Pickup and Lockers network for WooCommerce](https://ptwooplugins.com/product/dpd-seur-geopost-pickup-and-lockers-network-for-woocommerce/) - Deliver your WooCommerce orders on the DPD and SEUR Pickup network of Parcelshops and Lockers in 9 European countries

== Installation ==

Use the included automatic install feature on your WordPress admin panel and search for "Portugal States (Distritos) for WooCommerce".

== Frequently Asked Questions ==

= After version 3.0.0 the Postcode and Postcode City are showing side by side on the checkout and I want it the way it was before. What do I revert it? =

Add this to your (child) theme’s functions.php file:
`add_filter( 'woocommerce_portugal_postcode_class', function( $class ) {
	return array( 'form-row-wide' );
});
add_filter( 'woocommerce_portugal_city_class', function( $class ) {
	return array( 'form-row-wide' );
});`

= Is this plugin compatible with the new WooCommerce High-Performance Order Storage? =

Yes.

= Is this plugin compatible with the new WooCommerce block-based Cart and Checkout? =

Yes.

= I need help, can I get technical support? =

This is a free plugin. It’s our way of giving back to the wonderful WordPress community.

There’s a support tab on the top of this page, where you can ask the community for help. We’ll try to keep an eye on the forums but we cannot promise to answer support tickets.

If you reach us by email or any other direct contact means, we’ll assume you are in need of urgent, premium, and of course, paid-for support.

= Where do I report security vulnerabilities found in this plugin? =  
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/portugal-states-distritos-for-woocommerce). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

== Changelog ==

= 3.5 - 2023-12-12 =
* Declare WooCommerce block-based Cart and Checkout compatibility
* Requires WooCommerce 5.0 and WordPress 5.4
* Tested with WordPress 6.5-alpha-57159 and WooCommerce 8.4.0-rc.1

= 3.4.1 - 2023-07-07 =
* Fix some strings and links

= 3.4 - 2023-07-06 =
* Requires WooCommerce 5.0
* Tested with WordPress 6.3-beta3-56143 and WooCommerce 7.9.0-rc.2

= 3.3 - 2022-11-09 =
* Tested and confirmed WooCommerce HPOS compatibility
* Tested with WordPress 6.2-alpha-54748 and WooCommerce 7.1

= 3.2.0 - 2022-06-30 =
* New brand: PT Woo Plugins 🥳
* Requires WordPress 5.0, WooCommerce 3.0 and PHP 7.0
* Tested with WordPress 6.1-alpha-53556 and WooCommerce 6.7.0-beta.2

= 3.0.3 - 2021-05-14 =
* Fix the order in which we set the address format because other plugin's changes, like EU VAT Assistant, were being overriden
* New `woocommerce_portugal_localisation_address_formats_priority` filter to change the hook priority for the address format
* Tested with WooCommerce 5.3.0

= 3.0.2 - 2021-04-14 =
* Removed the information about WooCommerce 5.2.0 from the readme.txt file as the change was reverted on WooCommerce 5.2.1

= 3.0.1 - 2021-04-14 =
* Important information on readme.txt about WooCommerce 5.2.0 and the fact they now include the Portuguese Districts.

= 3.0.0 - 2021-04-08 =
* Complete code refactoring
* Show Postcode and Postcode City side by side on the checkout and added two new filters to change it: `woocommerce_portugal_postcode_class` and `woocommerce_portugal_city_class`
* Changed the (english) city label from "Postcode Town / City" to "Postcode City" (no changes in Portuguese)
* Added support information to the Frequently Asked Questions readme section
* Requires WooCommerce 3.0
* Tested with WordPress 5.8-alpha-50689 and WooCommerce 5.2.0-rc.2

= 2.1.9 - 2021-03-10 =
* Tested with WordPress 5.8-alpha-50516 and WooCommerce 5.1.0

= 2.1.8 =
* Tested with 5.6-beta3-49562 and WooCommerce 4.8.0-beta.1

= 2.1.7 =
* New [Portuguese Postcodes for WooCommerce](https://ptwooplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/) plugin information
* Tested with 5.5-alpha-47761 and WooCommerce 4.1.0-rc.2

= 2.1.6 =
* Tested with WordPress 5.3.3-alpha-47419 and WooCommerce 4.0.1

= 2.1.5.2 =
* Changes on the InvoiceXpress banner

= 2.1.5.1 =
* Fix version number

= 2.1.5 =
* Hide the InvoiceXpress nag if the invoicing is already installed and active
* Tested with WordPress 5.3.1-alpha-46798 and WooCommerce 3.8.1

= 2.1.4 =
* Change InvoiceXpress nag interval from 30 to 90 days
* Tested with WordPress 5.2.4-alpha-46074 and WooCommerce 3.8.0-beta.1
* Requires PHP 5.6

= 2.1.3.1 =
* readme.txt small fix

= 2.1.3 =
* Tested with WooCommerce 3.5.2
* Bumped `WC tested up` tag
* Bumped `Requires at least` tag

= 2.1.2 =
* Tested with WooCommerce 3.5
* Bumped `WC tested up to` tag

= 2.1.1 =
* Added the `woocommerce_portugal_postcode_priority` filter to allow overriding the "Postal Code" priority value

= 2.1 =
* Fix "Postal Code"/"City" fields order on the checkout on newer WooCommerce versions
* The "City" field label is now "Postcode Town / City" on the checkout
* New `woocommerce_portugal_city_label` filter to be able to change the "City" field label
* New `woocommerce_portugal_state_label` filter to be able to change the "District" field label
* New `woocommerce_portugal_state_required` filter to be able to set the "District" field as not required
* Bumped `WC tested up to` tag

= 2.0 =
* Removed the district from the plain text address format for Portugal, as we do not use it on a day to day basis: "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}" (can be restored via the `woocommerce_portugal_address_format_include_state` filter)
* Better coding standards

= 1.5.3.2 =
* Fixed readme.txt

= 1.5.3.1 =
* Tested with WooCommerce 3.3
* Bumped `Tested up to` tag

= 1.5.3 =
* Removed the translation files from the plugin `lang` folder (the translations are now managed on WordPress.org’s GlotPress tool and will be automatically downloaded from there)
* Tested with WooCommerce 3.2
* Added `WC tested up to` tag on the plugin main file
* Bumped `Tested up to` tag

= 1.5.2 =
* Tested with WooCommerce 3.0.0-rc.2
* Bumped `Tested up to` tag
* Portuguese translation update

= 1.5.1 =
* Bumped `Tested up to` tag

= 1.5 =
* Sets the correct Portuguese address format: "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}"

= 1.4.1 =
* Bumped "Requires at least" and "Tested up to" tags

= 1.4 =
* The district is now required by default, as is on all the other countries that have states loaded by the WooCommerce core

= 1.3 =
* Important bug fix: No longer overrides other countries states

= 1.2 =
* WordPress Multisite support

= 1.1 =
* Now also sets the right name for "Districts" and the correct "Postal Code"/"City" order

= 1.0 =
* Initial release.