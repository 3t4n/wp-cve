=== WooCommerce Cart Count Shortcode ===
Contributors: prontotools, sandsine, zkancs
Tags: woocommerce, cart, count, shortcode, shopping cart, item count, cart count, button, link
Requires at least: 4.0
Tested up to: 4.8.2
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display a link to your shopping cart with the item count anywhere on your site with a customizable shortcode.

== Description ==

Looking for a flexible way to display items in your site’s WooCommerce cart? Look no further! This plugin allows you to insert a shortcode anywhere on your site that generates an a href link. 

**Parameters:**

- **icon** - Any [Font Awesome](https://fortawesome.github.io/Font-Awesome/) icon. Most of the time you’ll want to use `shopping-cart` or `shopping-basket`. 
- **empty_cart_text** - The text to display when there are zero items in the cart. You may want this to default to “Shop”.
- **items_in_cart_text** - The text to display when there are one or more items in the cart. You may want this to default to “Cart”.
- **total_text** - The text to display when there are one or more items in the cart. You may want this to default to “Total:”.
- **show_items** - Enter “true/false” to show/hide items in the cart in parentheses. The item count will only show when there are one or more items in the cart.
- **custom_css** - Any custom CSS you’d like to add to the link.
- **show_total** - Enter “true/false” to show/hide total price in the cart

**Examples:**

- `[cart_button]`
- `[cart_button icon="basket"]`
- `[cart_button show_items="true"]`
- `[cart_button show_items="true" show_total="true"]`
- `[cart_button show_items="true" show_total="true" total_text="Total Price:"]`
- `[cart_button show_items="false" items_in_cart_text="Cart"]`
- `[cart_button show_items="true" empty_cart_text="Store"]`
- `[cart_button items_in_cart_text="Cart" custom_css="custom"]`


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Include the shortcode anywhere on your website: `[cart_button icon="shopping-cart" empty_cart_text="Shop" items_in_cart_text="Cart" custom_css="custom"]`


== Screenshots ==

1. Example of the plugin in action. Ain't she purdy!?

== Changelog ==

= 1.0.0 =

* First release!
* Tested with WordPress 4.4.2 and WooCommerce 2.5.5.

= 1.0.1 =
* Add parameter `show_total` to make this shortcode can show total price in cart

= 1.0.2 =
* Add parameter `total_text` to custom text for total price in cart

= 1.0.3 =
* Prevent fatal error when WooCommerce does not get initialized properly.

= 1.0.4 =
* Use `WC()` instead of the global variable `$woocommerce`.
* Add 3 filters below to allow users to modify the generated markup.
  1. `wccs_cart_icon_html`
  2. `wccs_cart_count_html`
  3. `wccs_cart_total_html`
