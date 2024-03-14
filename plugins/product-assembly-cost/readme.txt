=== Product Assembly / Gift Wrap / ... Cost for WooCommerce ===
Contributors: webdados, ptwooplugins
Donate link: http://bit.ly/donate_product_assembly_cost
Tags: woocommerce, product, assembly, installation, extra service, gift wrap, extra cost, webdados
Requires at least: 5.4
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 3.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add an option to your WooCommerce products to enable assembly, gift wrap or any other service and optionally charge a fee for it.

== Description ==

This plugin allows you to add an option to your WooCommerce products to enable assembly, gift wrap or any other service and optionally charge a fee for it.

In WooCommerce > Settings > Products you can set up the default service status and cost, the service name, the message and the way the costs are shown on the cart.

If the customer chooses to add the service when buying the product, that information is shown in the cart and orders. The service cost can be added to the cart as a global fee, instead of at the product level, to avoid discount plugins to also affect it.

This is a fork of [WooCommerce Product Gift Wrap](https://wordpress.org/plugins/woocommerce-product-gift-wrap/) by [Mike Joley](https://mikejolley.com/)

Banner photos by [Igor Ovsyannykov](https://unsplash.com/photos/mQgVyUC0V-I?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText) and [freestocks.org](https://unsplash.com/photos/k-Rp0V0XWWU?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText)

= Other (premium) plugins =

Already know our other WooCommerce (premium) plugins?

* [Shop as Client for WooCommerce](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/) - Quickly create orders on behalf of your customers
* [Taxonomy/Term and Role based Discounts for WooCommerce](https://ptwooplugins.com/product/taxonomy-term-and-role-based-discounts-for-woocommerce-pro-add-on/) - Easily create bulk discount rules for products based on any taxonomy terms (built-in or custom)
* [Simple WooCommerce Order Approval](https://ptwooplugins.com/product/simple-woocommerce-order-approval/) - The hassle-free solution for WooCommerce order approval before payment

== Frequently Asked Questions ==

= Is it possible to customize the message on each product page? =

Yes. Use the `product_assembly_cost_message` filter. Here’s an example: [link](https://gist.github.com/webdados/dbc8c634cddd2c94e69901d35b55e95a)

= Is it possible to force the service not to be available on certain products or variations? =

Yes.

For products, use the `product_assembly_show_assembly` filter and return false to it. The second argument is the product object.

For variations, use the `product_assembly_show_assembly_variation` filter and return false to it. The second argument is the variation object.

= Is it possible to set the service cost on runtime, based on custom conditions, without having to set the cost on each product? =

Yes. Use the `product_assembly_cost` filter. Here’s an example: [link](https://gist.github.com/webdados/8db7a3aa5e86bd971c747576003d89d7)

= Is this plugin compatible with the new WooCommerce High-Performance Order Storage? =

Yes.

= Is this plugin compatible with the new WooCommerce block-based Cart and Checkout? =

Yes.

= Can I contribute with a translation? =

Sure. Go to [GlotPress](https://translate.wordpress.org/projects/wp-plugins/product-assembly-cost/) and help us out.

= I need help, can I get technical support? =

This is a free plugin. It’s our way of giving back to the wonderful WordPress community.

There’s a support tab on the top of this page, where you can ask the community for help. We’ll try to keep an eye on the forums but we cannot promise to answer support tickets.

If you reach us by email or any other direct contact means, we’ll assume you need, premium, and of course, paid-for support.

= Where do I report security vulnerabilities found in this plugin? =  
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/product-assembly-cost). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

== Changelog ==

= 3.3 - 2024-02-14 =
* [NEW] New `product_assembly_show_assembly` and `product_assembly_show_assembly_variation` filters to force the assembly cost not to be shown on a product or variation if false is returned
* [NEW] [New `product_assembly_cost` filter](https://gist.github.com/webdados/8db7a3aa5e86bd971c747576003d89d7) to override/set the service cost on runtime, without having to set it on the admin
* [DEV] Tested with 6.5-alpha-57571 and WooCommerce 8.6.0-rc.1

= 3.2 - 2023-12-12 =
* Tested and confirmed WooCommerce block-based Cart and Checkout compatibility
* Requires WooCommerce 5.0 and WordPress 5.4
* Tested with WordPress 6.5-alpha-57159 and WooCommerce 8.4.0-rc.1

= 3.1 - 2022-12-02 =
* [New filter `product_assembly_cart_data`](https://gist.github.com/webdados/1b14574564e9ef0d853a5c1d43a9ed3c) that allows to change the data that is shown on the cart and order
* Tested with WordPress 6.2-alpha-54926 and WooCommerce 7.2.0-beta.2

= 3.0 - 2022-11-09 =
* You can now set the fee as taxable, or not, and choose its tax class
* Tested and confirmed WooCommerce HPOS compatibility
* Fix a CSS floating issue on the variations interface
* Tested with WordPress 6.2-alpha-54748 and WooCommerce 7.1

= 2.5.0 - 2021-06-30 =
* Field paragraph now inside a container
* New filters to change the field position: `product_assembly_cost_frontend_hook` and `product_assembly_cost_frontend_priority`
* New actions to add content before and after the field paragraph: `product_assembly_cost_before_field` and `product_assembly_cost_after_field`
* Requires WooCommerce 4.0
* Tested with WordPress 6.1-alpha-54043 and WooCommerce 6.9.2

= 2.4.0 - 2021-06-30 =
* Add - Settings link on the plugins list
* Fix - PHP notice when the fee has no taxes and taxes are enabled
* Requires WordPress 5.0, WooCommerce 3.0 and PHP 7.0
* Tested with WordPress 6.1-alpha-53556 and WooCommerce 6.7.0-beta.2

= 2.3.1 - 2021-06-13 =
* New brand: PT Woo Plugins 🥳
* Bugfix with variations
* Tested with WordPress 6.1-alpha-53479 and WooCommerce 6.6.0-rc.2

= 2.3.0 - 2021-12-03 =
* Small fix on the `woocommerce_product_options_pricing` priority to make it compatible with other plugins
* Tested with WordPress 5.9-beta1-52307 and WooCommerce 6.0.0-rc.1

= 2.2.2 - 2021-08-13 =
* Tested with WordPress 5.9-alpha-51607 and WooCommerce 5.6.0-rc.2
* Requires PHP 7.0

= 2.2.1 - 2021-03-10 =
* Tested with WordPress 5.8-alpha-50516 and WooCommerce 5.1.0

= 2.2.0 - 2020-12-21 =
* Stop using deprecated `woocommerce_add_order_item_meta` action and function
* Tested with WordPress 5.7-alpha-49782 and WooCommerce 4.8

= 2.1.0 - 2020-11-19 =
* Add the checkbox even if it wasn’t specified on the message template
* Do not load our class unless WooCommerce is active
* Tested with WordPress 5.6-beta3-49562 and WooCommerce 4.8.0-beta.1

= 2.0.1 - 2020-08-27 =
* Bugfix when adding the service information to the order item metadata

= 2.0.0 - 2020-08-16 =
* New option to multiply service cost by the quantity of product purchased (default) or charge only once per cart line (new)
* It’s now possbile to set variation service cost (breaking change if you’re already using this for variations, please check all your variations)
* Fix taxes bug (sorry guys 😐) - but still defaulting to the standard tax and cost set to taxable (we will revise this on a next version)
* Drop WooCommerce prior to 3.0 support
* Tested with WordPress 5.6-alpha-48783 and WooCommerce 4.4.0-rc.1

= 1.3 =
* Follow the “Prices entered with tax”, “Display prices in the shop” and “Display prices during cart and checkout” WooCommerce tax settings
* Tested with WooCommerce 4.2.0-beta.1

= 1.2.1 =
* Technical support clarification
* Tested with WordPress 5.5-alpha-47761 and WooCommerce 4.1.0

= 1.2 =
* Bugfix when used with AutomateWoo
* Tested with WooCommerce 4.0.0

= 1.1 =
* You can now enable the service by default and then explicitly disable it on each product
* New `product_assembly_cost_message` filter to customize the message
* Bugfix on the default service status
* Tested with WordPress 5.3.3-alpha-46995 and WooCommerce 3.9.0-rc.2

= 1.0 =
* Make the service name configurable so that it can be used for anything rather than just assembly
* New option to add the service name to the product name on the cart and order items, which can be useful for warehouse operations or invoicing
* Plugin name change
* Tested with WordPress 5.3.1-alpha-46798 and WooCommerce 3.9.0-beta.1

= 0.4.2 =
* Tested with WordPress 5.2.5-alpha and WooCommerce 3.8.0

= 0.4.1 =
* Added "/ unit" on assembly cost on each cart item
* Tested with WooCommerce 3.5.1 and bumped `WC tested up to` tag

= 0.4 =
* Use WooCommerce 3.0 (and above) CRUD functions to read/update product meta
* Bumped `WC tested up to` tag

= 0.3 =
* Fix for variations with assembly cost

= 0.2 =
* readme.txt improvements

= 0.1 =
* Initial release sponsored by [Ideia Home Design](https://www.ideiahomedesign.pt/)