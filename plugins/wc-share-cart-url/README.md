=== WC Share Cart URL ===
Tags: woocommerce share cart, woocommerce cart url, send cart to customer, cart url, cart link
Requires at least: 5.0
Tested up to: 6.3.1
Requires PHP: 5.6
Stable tag: 1.1.1
License: GNU GPLv2

Share WooCommerce cart by URL. Send the cart to any Customer in WooCommerce store.

== Description ==
# WC Share Cart URL

This plugin allows Admin/Store Manager (or any user with capability to "manage_woocommerce") to generate link to the current cart.
The link can be given to the Customer to load all products to the cart and finish the order.

WooCommerce Share Cart URL Plugin

# Usage

- This plugin creates button "Share this cart" on the Cart page. 
- The button is showed only for Store Manager, Admin and any user who has capability "manage_woocommerce"
- The button saves current cart session to file in temp directory (see: get_temp_dir()) and creates link to share the cart https://mystore.pl/cart/?share={hash}
- After opening the link, the current cart session is replaced with the data previously saved to the temporary file.

# Custom Product Price

From version 1.1.0 shop manger can change product prices in the cart before sharing.