=== ShopWP ===
Contributors: andrewmrobbins
Donate link: https://wpshop.io/purchase/
Tags: shopify, eCommerce, shop, store, sell, products, purchase, buy
Requires at least: 5.4
Requires PHP: 7.4
Tested up to: 6.4.3
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display and sell Shopify products on WordPress. Embed your Shopify products using shortcodes or blocks and create a powerful eCommerce shop.

== Description ==

**Important: This plugin will stop working on March 1st, 2024. Please [upgrade to ShopWP Pro](https://wpshop.io/purchase) to continue using the plugin.

Not ready to upgrade? [Email us](mailto:hello@wpshop.io) for a free trial.**

Thanks y'all,
- Andrew

--

Sell [Shopify](https://shopify.pxf.io/5bPL0L) products on WordPress with ShopWP. Buy buttons? We got your covered. Easily embed product buy buttons on any page with simple shortcodes and blocks. Send your customers directly to the Shopify checkout, or add products to the built-in ShopWP cart instead. We have all the shortcodes and blocks you need to build a uniquely branded ecommerce experience on WordPress.

Not only that, but ShopWP lets you sync your products into WordPress to easily create product detail pages.

Ditch the slow and complicated ecommerce solutions like Woo. Whether you need WordPress to power a whole storefront or just a landing page, ShopWP will provide beautiful layouts and give your customers the confidence they need to buy from you.

We believe your store should authentically represent your brand. It shouldn't cost tens of thousands of dollars to build a shopping experience. We want to empower entrepreneurs and small businesses to create eCommerce shops that rival the big players.

= Features =

- Simple to use buy buttons
- Sync product / collection detail pages
- No iFrames
- 10 shortcodes for displaying products
- Built-in [cart experience](https://wpshop.io/features/#cart-experience)
- SEO optimized
- Filtering and sorting functionality (Pro only)
- [Show variants as buttons](https://wpshop.io/features/#variant-buttons) instead of dropdowns (Pro only)
- Show products in [carousels](https://wpshop.io/features/#carousel) or modals (Pro only)
- [Subscription products](https://wpshop.io/features/#subscriptions) via Recharge extension (Pro only)
- [Direct checkout](https://wpshop.io/features/#direct-checkout) (Pro only)

See the [full list of features here](https://wpshop.io/features/)

= ShopWP Pro =
Use discount code `15OFFPRO` to save 15% off when upgrading to [ShopWP Pro](https://wpshop.io/purchase). Take your store to the next level with awesome features like: subscription products, template overrides, filtering / sorting, automatic post syncing, dedicated support, and more! [Save 15% off ShopWP Pro](https://wpshop.io/purchase)

= Links =

- [Website](https://wpshop.io/)
- [Documentation](https://docs.wpshop.io/)
- [ShopWP Pro](https://wpshop.io/purchase/)
- [Demo](https://wpshop.io/features/)

== Installation ==
From the WordPress dashboard:

1. Visit Plugins > Add New
2. Search for _ShopWP_
3. Activate ShopWP from your Plugins page
4. Click on the new menu item called **ShopWP**
5. Within the Connect tab, click the "Begin the connection process" button
6. Follow the wizard to connect your Shopify store. We've [created a guide](https://docs.wpshop.io/getting-started/syncing/).

== Screenshots ==

1. Connect your Shopify store to WordPress
2. Sync your Shopify store
3. Example of the syncing process running
4. How the plugin settings appear
5. Edit screen of the product detail page
6. List of product posts

== Frequently Asked Questions ==

Read the [full list of FAQ](https://wpshop.io/faq/)

= How does this work? =
You can think of WordPress as the frontend and Shopify as the backend. You still manage your store data inside Shopify (e.g., changing prices) and those changes automatically show inside WordPress. ShopWP is bundled with its own fly-out cart experience and allows you to sell directly on WordPress. When the user is ready to checkout, they're redirected to the defalt Shopify checkout page to enter payment information.

After installing the plugin, you can connect your Shopify store by following the easy to use wizard. After connecting, you can display your products in the following ways:

- Using the default pages "example.com/products" and "example.com/collections"
- Shortcodes
- Programmatically through the plugin’s Render API

You can also create product detail pages by syncing the product posts.

= Is this SEO friendly? =
We’ve gone to great lengths to ensure we’ve conformed to all the SEO best practices including semantic alt text, Structured Data, and indexable content.

= Doesn’t Shopify already have a WordPress plugin? =

They used to, but it has [been discontinued](https://wptavern.com/shopify-discontinues-its-official-plugin-for-wordpress).

Instead, Shopify has moved their attention to the buy button embed; allowing users to show products with a JavaScript code snippet. The main drawback to this is that [Shopify](https://shopify.pxf.io/5bPL0L) uses iFrames for the embed which limits the ability for layout customizations. Additionally, managing multiple JavaScript embeds can get annoying really fast.

In contrast, ShopWP creates an iFrame-free experience allowing you to sync Shopify data directly into WordPress. We also save the products and collections as custom post types which unlocks the native power of WordPress itself.

= Does this work with third party Shopify apps? =
The only "Unfortunately no. We rely on the main Shopify API which doesn’t expose third-party app data. However the functionality found in many of the Shopify apps can be reproduced by other WordPress plugins.

= How do I display my products? =
Documentation on how to display your products can be [found here](https://docs.wpshop.io/getting-started/displaying).

= How does the checkout process work? =
ShopWP does not handle any portion of the checkout process. When a customer clicks the checkout button within the cart, they’re redirected to the default Shopify checkout page to finish the process.

= Does this work with Shopify's Lite plan? =
Absolutely! In fact this is our recommendation if you intend to only sell on WordPress. More information on Shopify's [Lite plan](https://shopify.pxf.io/vnqbrj)

= Can I use this and Shopify at the same time? =

Absolutely! ShopWP doesn’t prevent you from using [Shopify](https://shopify.pxf.io/5bPL0L) on other platforms like Facebook or using a Shopify theme directly.

== Changelog ==

The full changelog can be [found here](https://wpshop.io/changelog/)

**Note: If you're upgrading from version 3.x, please [read through the migration guide first](https://docs.wpshop.io/guides/migrating-to-4.0)**

### 5.2.3
- **Updated:** Updated the free trial language in the upgrade notice

### 5.2.2
- **Updated:** Added upgrade text

### 5.2.1
- **Updated:** Added compatibility notice

### 5.2.0
- **Updated:** Changed text in plugin upgrade notice

### 5.1.11
- **Improved:** Updated the language in the README file

### 5.1.10
- **Added:** Support for WordPress 6.3.2

### 5.1.9
- **Fixed:** API syncing / connection issues

### 5.1.8
- **Added:** New upgrade notice

### 5.1.7
- **Fixed:** Occasional performance issues due to `buyer_identity` option values

### 5.1.6
- **Fixed:** Error: "Field 'metafields' is missing required arguments: identifiers"

### 5.1.5
- **Added:** Yotpo product reviews extension! [Learn more](https://wpshop.io/extensions/yotpo-product-reviews)
- **Improved:** Updated various broken links within the plugin settings

### 5.1.4
- **Improved:** Removed unused code from two years ago. Cleaning things up a bit.
- **Dev:** Updated npm dependencies

### 5.1.3
- **Fixed:** Broken products pagination when using Yotpo reviews
- **Fixed:** Yotpo reviews were not showing properly in list view
- **Improved:** Layout of product featured image now properly fills space
- **Improved:** Added additional structure data to Yotpo reviews

### 5.1.2
- **Fixed:** Resolved minor bugs in the Yotpo reviews functionality
- **Improved:** Quantity border color now matches color of variant buttons

### 5.1.1
- **Added:** WordPress `6.0` support
- **Added:** Support for the upcoming Yotpo Reviews extension
- **Fixed:** WPML conflict causing products not to load
- **Fixed:** Bug causing sync to fail occasionally when global $TRP_LANGUAGE is defined as `en_US`
- **Fixed:** Missing pagination on collection pages
- **Fixed:** Bug preventing the collection product settings from being applied consistently
- **Fixed:** Default collections listing template now correctly shows all collections
- **Fixed:** Bug when linking to modal and with active buy button
- **Improved:** Better error handling when a product has been removed from the ShopWP sales channel
- **Dev:** Updated ShopWP compatibility MU plugin version

### 5.1.0
- **New** Cart toggle links
- **New** Storefront component now provides a search field
- **New** Product image carousel can now show thumbnails
- **Fixed** The `on.cartToggle` hook now works again
- **Improved** Removed the minimum quantity line item notice within the cart
- **Improved** Added ShopWP tier indication within admin settings header
- **Improved** Cart "close" event is not properly scoped to the cart DOM element
- **Improved** Cart terms checkbox style
- **Improved** Now showing upgrade notice for ShopWP Collections
- **Dev** Added new JavaScript action: `on.itemsLoad`
- **Dev** Added new JavaScript filter: `before.cartLineItems`
- **Dev** Added new JavaScript filter: `after.cartLineItems`
- **Dev** Bumped Shopify API to `2022-07`
- **Dev** Added new shortcode attribute: `image_carousel_thumbs`

### 5.0.5
- **Fixed:** conflict with TranslatePress causing broken rest_url errors
- **Fixed:** cart colors were not being properly applied
- **Fixed:** Bug causing products to duplicate when using carousel if total products is less than carousel slides to show
- **Fixed:** Bug causing Storefront component to crash when deselecting collections
- **Fixed:** Bug causing Storefront component to crash when no products are found
- **Fixed:** Bug causing the `Product Create` webhook to fail
- **Improved:** Removed BlueHost banner inside ShopWP admin settings
- **Improved:** Cart icon background color
- **Improved:** Added skeleton loader to cart footer if slow connection detected
