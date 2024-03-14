=== WooCommerce Product Price x Quantity Preview ===
Contributors: reigelgallarde
Donate link: http://reigelgallarde.me
Tags: dynamic price, price display, woocommerce, total price, final price, price times quantity
Requires at least: 3.8
Tested up to: 4.9.8
Stable tag: 1.2.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html	

An extension to WooCommerce that will preview price total when quantity changes on the product page.

== Description ==

**WooCommerce Product Price x Quantity Preview** is an extension for WooCommerce that allow visitors of your site to see the total price when they change the quantity. 

= How it works =

The plugin, once activated, do not need to be setup. You will see the total price working on the product page if you change the quantity (works only for simple product type).

This plugin uses WooCommerce currency options settings, so you don't have to worry about how the price is displayed.

= Get Plugin addons =

>This plugin offers a Pro addon which adds the following features:

>* Support to WooCommerce Variable product type. 
>* Support to [WooCommerce Dynamic Pricing & Discounts](https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts/7119279) by RightPress
>* [new] Support to [YITH WooCommerce Dynamic Pricing and Discounts Premium](https://yithemes.com/themes/plugins/yith-woocommerce-dynamic-pricing-and-discounts/) by YITHEMES
>* Custom buttons +/- supported. Must be accessible via selector `".quantity .plus"` and `".quantity .minus"`.
>* More features and integrations is coming.

>Get [WooCommerce Product Price x Quantity Preview Addons](http://reigelgallarde.me/product/woocommerce-product-price-x-quantity-preview/) now.
>[Visit demo page](http://demo.reigelgallarde.me/ppqp/product-category/clothing/hoodies/). 

= Requirements =
WooCommerce 3.0 or later.

== Installation ==
1. You can:
 * Upload the `woo-product-price-x-quantity-preview` folder to `/wp-content/plugins/` directory via FTP. 
 * Upload the full ZIP file via *Plugins -> Add New -> Upload* on your WordPress Administration Panel.
 * Search **WooCommerce Product Price x Quantity Preview** in the search engine available on *Plugins -> Add New* and press *Install Now* button.
2. Activate plugin through *Plugins* menu on WordPress Administration Panel.
3. No Setup needed.

== Frequently asked questions ==
= I don't find any setup? =
Yes! You really don't need a setup for this plugin.

= WPML ready? =
Yes! You must create your `.po` file. If you want to submit a `.po` file, I will be happy to add it on next release.
Contact me using this [form](http://reigelgallarde.me/services/).

= How to change "Product Total" to my text of choice? =
You can paste this code to your current theme's functions.php:

`
add_filter( 'ppqp_price_html', 'ppqp_price_html' );
function ppqp_price_html( $ppqp_price_html ) {
	return str_replace( 'Product Total', 'Order Total', $ppqp_price_html );
}
`
This is an example that will change "Product Total" to "Order Total".

= This plugin is not working, how can I reach you? =
First of, this plugin relies mainly onjavascript. If you have javascript error on your page, this will not work.
If you still not able to make it work and wanted to contact me, you can reach me on this [form](http://reigelgallarde.me/services/).

= Will this work with other plugins of WooCommerce? =
It will, or it will not. That really depends on how those plugins are made.
But I can help you out if you contact me using this [form](http://reigelgallarde.me/services/).

== Screenshots ==

1. Screenshot 1
2. Screenshot 2

== Changelog ==

= 1.2.1 =
>* fix a rare error for custom +/- on quantity.
= 1.2 =
>* added support for custom +/- on quantity.
= 1.1 =
>* added WPML support.
= 1.0 =
* Initial release!

