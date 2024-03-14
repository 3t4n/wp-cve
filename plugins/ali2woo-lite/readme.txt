=== AliExpress Dropshipping with AliNext Lite ===
Contributors: ali2woo
Tags: aliexpress, dropship, dropshipping, alidropship, affiliate, ali2woo
Requires at least: 5.9
Tested up to: 6.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Stable tag: trunk
Requires PHP: 8.0
WC tested up to: 8.6
WC requires at least: 5.0

Looking for AliExpress Dropshipping plugin for WordPress and WooCommerce stores? AliNext (new version of Ali2Woo) is the right choice! Import any products with reviews from AliExpress! Place orders on AliExpress automatically! Moreover, AliNext is integrated with AliExpress Affiliate Program! So you can earn more if you sell affiliate products.

== Description ==

Looking for AliExpress Dropshipping plugin for WordPress and WooCommerce stores? AliNext is the right choice! Import any products with reviews from AliExpress! Place orders on AliExpress automatically! Moreover, AliNext is integrated with AliExpress Affiliate Program! So you can earn more if you sell affiliate products.

[Knowledge Base](https://ali2woo.com/codex/) | [Chrome extension](https://ali2woo.com/codex/aliexpress-dropshipping-chrome-extension/) | [AliNext official website](https://ali2woo.com/) | [Full Version](https://ali2woo.com/dropshipping-plugin/)

###How To Import Products from AliExpress using the Chrome extension?
[youtube https://youtu.be/Lbq-_3j4vwk]

###How To Use Global Pricing Rules?
[youtube https://youtu.be/N-GZ3EpJYiw]

###How To Fulfill AliExpress Orders in Bulk?
[youtube https://youtu.be/S5368Pvo_F0]

### Compatibility with Woocommerce HPOS
Starting from version 3.1.3, AliNext is compatible with HPOS. 
To activate HPOS, follow these steps: go to Woocommerce -> Settings -> Advanced -> Order data storage and select the "High-performance order storage (recommended)" option. Additionally, ensure that you have enabled the "Enable compatibility mode (synchronizes orders to the posts table)" option. Save your Woocommerce settings and confirm that all orders are synchronized by Woocommerce. If some orders are pending synchronization, you will see information about it there. Please wait until all orders are synchronized before using the AliNext plugin normally. For further information about HPOS [refer official woocommerce article](https://woocommerce.com/posts/platform-update-high-performance-order-storage-for-woocommerce/)

### Important Notice:

- Plugin works based on WooCommerce plugin.

- You have to generate the access token (aliexpress token) to place or sync orders. You don't need the token to import products. Please [follow our instruction](https://help.ali2woo.com/codex/how-to-get-access-token-from-aliexpress/) in order to generate token.

- Your permalink structure must NOT be "Plain"

- It is released on WordPress.org and you can use plugin as free to build themes for sale.

### FEATURES
  
&#9658; **Import Products**:

This plugin can import products from AliExpress with several methods: via a built-in search module or through the free chrome extension. Additionally, it can pull products from selected categories or store pages on AliExpress. Also, if you want to import a specific product only, you can use AliExpress product ID or AliExpress product URL to do that.

- **Import from single product page**

- **Import from category page**

- **Import from store page**

&#9658; **Import All Products of specific AliExpress seller or store**:

The plugin has a separate page allowing you to search for products in specific AliExpress store or seller. Often it helps if you find some stor on AliExpress and want to pull all items form the store.

See a detailed guide on this topic [HERE.](https://help.ali2woo.com/codex/how-to-import-all-products-from-the-store/)

&#9658; **Split product variants into separate products**:

The plugin allows splitting product variants. For example: a lot of products on AliExpress come with the "ShipFrom" attribute. Often dropshippers don't want to show this variant for customers. With this feature it's possible to split such a product by the "ShipFrom" attribute. As result you will get separate products without it.

Please look through the [this article](https://ali2woo.com/codex/how-to-split-product-variants-video/) to understand clearly how splitting feature works.

&#9658; **Remove "Ship From" attribute automatically**: save your time, you don’t need to edit the "Shipping From" attribute for each product one by one, AliNext will do that automatically for you!

Check out an article from the plugin Knowledge Base to know [how to use this feature.](https://help.ali2woo.com/codex/how-to-hide-the-ship-from-attribute-from-product-page/)

&#9658; **Override product supplier**:

The product override feature is very helpful if you get a new order for the out-of-stock product and want to fulfill the order from another supplier or vendor on AliExpress.
Also it helps if you have a product that was loaded through other dropshipping tool or it was added manually in Woocommerce and you want to connect the product to some AliExpress item using AliNext

Check out an article from the plugin Knowledge Base to know [how to use this feature.](https://ali2woo.com/codex/how-to-change-the-product-supplier/)

&#9658; **Change product images through the built-in image editor**:

Have you ever noticed the most product images having seller’s logo on AliExpress? And when you import the products into your store, those watermarks are visible for your customers. We know about such a problem and added a built-in images editor to the plugin`s features. The image tool allows to adjust photos right in your WordPress Dashboard.

It's recommended to check a detailed article [about the image editor tool.](https://ali2woo.com/codex/how-to-hide-watermark-on-images-from-aliexpress/)

&#9658; **Configure settings for all imported products**:

This set of settings apply to all products imported from AliExpress. Go to AliNext Settings > Common Settings > Import Settings.

- **Language**: Set language of product data such as title, description, variations, attributes, etc. Our plugin supports all languages which available on AliExpress.

- **Currency**: Change the currency of products. AliNxt supports all currencies which AliExpress portal operates with. 

- **Default product type**: By default the plugin imports product as "Simple/Variable product". In this case, shoppers will stay on your website when they make a purchase else choose the "External/Affiliate Product" option and your visitors will be redirected to AliExpress to finish the purchase.

- **Default product status**: Choose the "Draft" option and imported products will not be visible on your website frontend.

- **Not import specifications**: Turn this feature on if you'd NOT like to import product attributes from AliExpress. You can see these attributes in the "specifications" tab on the AliExpress website.

- **Not import description**: Enable this feature if you don't want to import product description from AliExpress.

- **Don't import images from the description**: If you want to skip images from the product description and don't import them to the wordpress media library, use this option.

- **Use external image urls**: By default, the plugin keeps product images on your server. If you want to save free space on your server, 
activate this option and the plugin will load an image using an external AliExpress URL. Please note: This feature works if the plugin is active only!

- **Use random stock value**: By default the plugin imports the original stock level value. Some sellers on AliExpress set very high value and it doesn't look natural. To solve the issue just enable the feature. It forces the plugin to generate stock level value automatically and choose it from a predefined range.

- **Import in the background**: Enable this feature and allow the plugin to import products in a background mode. In this case, each product is loaded in several stages. First, the plugin imports main product data such as: title, description, and attributes, and in the second stage, it imports product images and variants. This feature speeds up the import process extremely. Please note: In the first stage a product is published with the "draft" status and then when all product data is loaded the product status is changed to "published".

- **Allow product duplication**: Allow the import of an already imported product. This can be useful when you want to override a product with the same product.

- **Convert case of attributes and their values**: Products may come with different text case of attributes and their values. Enbale this feature to covert all texts to the same case.

- **Remove "Ship From" attribute**: Use this feature to remove the "Ship From" attribute automatically during product import.

&#9658; **Set options related to the order fulfillment process**:

These settings allow changing an order status after the order is placed on AliExpress. Go to AliNext Settings > Common Settings > Order Fulfillment Settings.

- **Delivered Order Status**: Change order status when all order items have been delivered.

- **Shipped Order Status**: Change order status when all order items have been shipped.

- **Placed Order Status**: Change order status when order is placed. 

- **Default shipping method**: If possible, the extension auto-select the shipping method on AliExpress during an order fulfillment process.

- **Override phone number**: The extension will use these phone code and number instead of the real phone provided by your customer.

- **Custom note**: Set a note for a supplier on the AliExpress.

- **Transliteration**: Enable the auto-transliteration of AliExpress order details such as first name, last name, address, etc.

- **Middle name field**: Adds the Middle name field to WooCommerce checkout page in your store. The extension uses the field data during an order-fulfillment process on AliExpress.

&#9658; **Earn more with AliExpress Affiliate program**:

On this setting page, you can connect your store to AliExpress Affiliate program. You can connect to the program using your AliExpress, Admitad, or EPN account.
Go to AliNext Settings > Account Settings.

We have a detailed guide on how to connect to the AliExpress Affiliate program [HERE](https://ali2woo.com/codex/account-settings/) 

&#9658; **Set up global pricing rules for all products**:

These options allow setting markup over AliExpress prices. You can add separate markup formula for each pricing range. The formula is a rule of a price calculation that includes different math operators such as +, *, =. Pricing rules support three different modes that manage the calculation in your formulas. Additionally, you can add cents to your prices automatically. And even more, it's easy to apply your pricing rules to already imported products. 

Go to AliNext Settings > Pricing Rules.

Also, read a detailed post about [global pricing rules.](https://ali2woo.com/codex/pricing-markup-formula/)

&#9658; **Filter or delete unnecessary text from AliExpress product**: 

Here you can filter all unwanted phrases and text from AliExpress product. It allows adding unlimited rules to filter the texts. These rules apply to the product title, description, attributes, and reviews. Please note the plugin checks your text in case-sensitive mode.

Go to AliNext Settings > Phrase Filtering.

See a detailed guide on this topic [HERE.](https://ali2woo.com/codex/phrase-filtering/)

&#9658; **Import reviews from AliExpress**: 

Import product reviews quickly from AliExpress, skip unwanted reviews, import translated version of the reviews or reviews related to a paticular country.

Go to AliNext Settings > Reviews settings.

Check out a detailed guide about [reviews settings.](https://ali2woo.com/codex/importing-reviews/)

Please note: in the lite plugin version all reviews options are available except "Import more reviews automatically". That option is available in the Pro version (read below please).

&#9658; **Add Shipping cost to your pricing markup**:

Use this feature to increase your margin by including shipping cost to the product price. 

Go to AliNext Settings > Pricing Rules > Add shipping cost.

See a detailed guide on this topic [HERE.](https://ali2woo.com/codex/pricing-markup-formula/#shipping)

&#9658; **Automatically place orders on AliExpress through the AliExpress API**:

Go to AliNext Settings > Account Settings and click "Get Access Token". AliExpress portal will ask your permission to place orders via API. Accept it and then the feature will be activated! Go to your Woocomemrce orders list page and try to place your objects, you will see that now you can place it via API very fast.

See a detailed guide on this topic [HERE.](https://help.ali2woo.com/codex/fulfill-orders-using-aliexpress-api/)

Please note: In the lite plugin version you have a limit of 20 orders operations per day. So you can place or sync 20 orders max per day. You can increase limit using Pro version.

&#9658; **Sync orders with AliExpress through the AliExpress API**:
This feature allow you to update shipping tracking information automatically.
Please note: In the lite plugin version you have a limit of 10 orders operations per day. So you can place or sync 10 orders max per day. You can increase limit using Pro version.


###PRO VERSION

- **All features from the free version**

- **6 months of Premium support**

- **Lifetime update**

&#9658; **Find best products using built-in search tool**: 

**[pro version feature]** Get more search requests to find the best products for your store. In contrast to AliNext Lite allowing you to make only 100 operations per day.

&#9658; **Find all products of the specific store or seller on AliExpress**: 

**[pro version feature]** Get more search requests to find the best products of specific AliExpress store or seller. In contrast to AliNext Lite allowing you to make only 100 operations per day.

&#9658; **Fast Order fulfillment using API**: 

**[pro version feature]** Place more orders on AliExpress through the AliExpress API. In contrast to AliNext Lite allowing you to place ONLY 10 orders using the API.

&#9658; **Sync Orders using API**: 

**[pro version feature]** Sync more orders with AliExpress through the AliExpress API. In contrast to AliNext Lite allowing you to sync ONLY 10 orders using the API.

&#9658; **Set options related to the product synchronization**:

**[pro version feature]** This set of features allows synchronizing an imported product automatically with AliExpress. Also, you can set a specific action that applies to the product depending on change occurring on AliExpress.  Go to AliNext Settings > Common Settings > Schedule Settings.

- **Aliexpress Sync**: Enable product sync with AliExpress in your store. It can sync product price, quantity and variants.

- **When product is no longer available**: Choose an action when some imported product is no longer available on AliExpress.

- **When variant is no longer available**: Choose an action when some product variant becomes not available on AliExpress.

- **When a new variant has appeared**: Choose an action when a new product variant appears on AliExpress.

- **When the price changes**: Choose an action when the price of some imported product changes on AliExpress.

- **When inventory changes**: Choose an action when the inventory level of some imported product changes on AliExress.


&#9658; **Get email alerts on product changes**:

**[pro version feature]** Get email notification if product price, stock or availability change, also be alerted if new product variations appear on AliExpress.

You can set email address for notifications and override the email template if needed. The plugin sends notification once per half-hour.

&#9658; **Sync reviews from AliExpress**: 

**[pro version feature]** Check for an appearance of new reviews in all imported products. Unlock "Import more reviews automatically" option in the review settings.

&#9658; **Import shipping methods from AliExpress and/or show shipping selection on your website frontend**: 

**[pro version feature]** Easily import delivery methods from AliExpress, set pricing rules to add your own margin over the original shipping cost, show shipping methods selection on the product page, cart page, checkout page.

Go to AliNext Settings > Shipping settings.

See a detailed guide on this topic [HERE.](https://ali2woo.com/codex/shipping-settings/)

[GET PRO VERSION](https://ali2woo.com/dropshipping-plugin/) or [https://ali2woo.com/dropshipping-plugin/](https://ali2woo.com/dropshipping-plugin/)

### MAY BE YOU NEED

[AliNext Migration Tool](https://wordpress.org/plugins/ali2woo-migration-tool/): Convert products from other dropshipping tools to AliNext format. In other words it allows you easilly migrate your products to Ali2Woo from other dropshipping tools such as: Alidropship, Ald etc

[Variation swatches images for WooCommerce](https://codecanyon.net/item/woocommerce-variation-swatches-images/20327701): Convert your normal variable attribute dropdown select to nicely looking color or image select. You can display images or color in all common size.

[AliExpress Shipment Tracking](https://codecanyon.net/item/woocommerce-aliexpress-shipment-tracking/22040640): Add tracking numbers to WooCommerce orders, track them using special tracking service, etc.

[eBay Dropshipping and Fulfillment for WooCommerce](https://codecanyon.net/item/ebay-dropship-for-woocommerce/21805662): Allows you to easily import dropshipped or affiliated eBay products directly into your WooCommerce store and ship them directly to your customers – in only a few clicks. Also you can place your orders on eBay.com using our FREE chrome extension.

### Documentation

- [Getting Started](https://ali2woo.com/codex/)

### Plugin Links

- [Project Page](https://ali2woo.com)
- [Documentation](https://ali2woo.com/codex/)
- [Report Bugs/Issues](https://support.ali2woo.com/)

= Helpful resources: =

Check out the following resources to be successful in dropshipping.

* [Best Dropshipping Niches 2022](https://ali2woo.com/blog/best-dropshipping-niches/)
* [Best Dropshipping Ideas For Every Season](https://ali2woo.com/blog/dropshipping-ideas/)
* [The best-selling dropshipping products in 2022](http://ali2woo.com/blog/best-selling-dropshipping-products-2022/)
* [The Complete Guide: Dropshipping Tips for 2020](https://ali2woo.com/aliexpress-dropshipping-guide/)
* [Real Dropshipping Success Stories](https://ali2woo.com/blog/dropshipping-success-stories/)

= Minimum Requirements =

* PHP 8.0 or greater is recommended
* MySQL version 5.0 or greater
* WooCommerce 5.0.0+

= Support = 

In case you have any questions or need technical assistance, get in touch with us through our [support center](https://support.ali2woo.com).


= Follow Us =

* The [AliNext Plugin](https://ali2woo.com/) official homepage.
* Follow AliNext on [Facebook](https://facebook.com/ali2woo) & [Twitter](https://twitter.com/ali2woo).
* Watch AliNext training videos on [YouTube channel](https://www.youtube.com/channel/UCmcs_NMPkHi0IE_x9UENsoA)
* Other AliNext social pages: [Pinterest](https://www.pinterest.ru/ali2woo/), [Instagram](https://www.instagram.com/ali2woo/), [LinkedIn](https://www.linkedin.com/company/18910479)

== Installation ==

= From within WordPress =

1. Visit 'Plugins > Add New'
2. Search for 'AliNext Lite'
3. Activate AliNext Lite from your Plugins page.
4. Go to "after activation" below.

= Manually =

1. Upload the `alinext-lite` folder to the `/wp-content/plugins/` directory
2. Activate the Yoast SEO plugin through the 'Plugins' menu in WordPress
3. Go to "after activation" below.

== Screenshots ==

1. The AliNext Lite dropshipping plugin build-in product search tool. 
2. The Import List page, here you can adjust the products before pushing them into WooCommerce store.
3. Built-in image editor tool, easy way to remove supplier logos for the images.
4. The AliNext Lite Setting page.
5. Set up your pricing markups.
6. Remove or replace unwanted text from the content imported from AliExpress
8. Feature to quick search for all products of the same seller/store from AliExpress

== Changelog ==
= 3.2.4 - 2024.02.16 =
* fix few deprecated (legacy) methods in code
* remove old Requests library from the code and use native Requests library from wordpress core
* fix Woocommerce 8.6.* compatibility bug

= 3.2.1 - 2024.01.13 =
* fix chrome extension connection bug
* increase daily quota for order place and sync operations to 20 per day (for the lite plugin version)

= 3.2.0 - 2024.01.10 =
* add feature to synchronize selected orders (see bulk actions)
* refactor plugin code to improve performance
* fix minor bugs and errors

= 3.1.4 - 2023.11.23 =
* fix last update time on product update
* fix tracking_id param in aliexpress affiliate links
* fix some warnings related with old style function call

= 3.1.3 - 2023.10.24 =
* added compatibility with woocommerce HPOS
* added compatibility with woocommerce 8.2
* fix a2w_ping() check on some server environment setup
* update some legacy code, fix minor bugs

= 3.1.2 - 2023.10.03 =
* Fix notAvailable product exception on product sync
* Fix change product type bug on product sync
* Fix switch order status when order is shipped on aliexpress
* Fix chrome extension connection bug

= 3.1.0 - 2023.09.22 =
* Fix integration with new official AliExpress API (fix token, fix order place and sync functions)
* Fix a lot of minor bugs
* Switch minimal php version to 8.0

= 3.0.25 - 2023.07.23 =
* Fix problem with place order feature in AliNext Lite

= 3.0.24 - 2023.07.11 =
* Replaced Ali2Woo Lite with AliNext Lite
* Enhanced product import module (import works WITHOUT AliExpress token now, but still required for order operations)
* Added bulk products import via CSV
* Increased daily quota for orders: 10 operations for syncing or placing orders daily
* Upgraded built-in product image editor to support larger images
* Expanded list of supported currencies and languages in the plugin settings
* Fixed minor bugs

= 2.3.5 - 2023.05.10 =
* Fix CURL certificate bug
* Fix PHP bug
* Fix minor bugs

= 2.3.4 - 2023.04.07 =
* Fix WordPress 6.2 compatibility bug
* Fix bug when Woocommerce is not installed
* Fix minor bugs and refactor

= 2.3.3 - 2023.02.27 =
* Restruct "Order Fulfillment" settings
* Remove "Chrome extension" settings from the plugin
* Update shipping methods available in "Default Shipping Method" option
* Fix a rare nug with AliExpress update token
* Fix empty bug in global shipping rules
* Fix minor bugs and refactor

= 2.3.2 - 2023.01.12 =
* Refactor AliExpress Loader
* Remove rudiment files
* Update texts
* Fix minor bugs

= 2.3.1 - 2023.01.04 =
* Refactor and improve plugin code base
* Fixed minor bugs

= 2.3.0 - 2022.11.21 =
* Migrated to new the new AliExpress API
* Added built-in search for products in specific AliExpress store or seller
* Fixed minor bugs

= 2.2.5 - 2022.09.07 =
* Fixed AliExpress error messages
* Improved compatibility with Ali2Woo Migration Tool

=  2.2.4 - 2022.08.18 =
* Removed old bootstrap html/css from the code causing conflicts on some wp themes
* Added compatibility with Ali2Woo Migration Tool
* Fixed bug pricing reset in the Import list

= 2.2.2 - 2022.07.05 =
* Added a feature to sync orders using AliExpress API. You can sync only 1 order using the API in the lite plugin version.
* Fixed order fulfillment bug occurring when order consists of several products or variant of the product owned by the same seller
* Added support for WPML Multicurrency
* Fixed a shipping import problem occurring in the Import List
* Fixed a bug in the Reviews settings occurring while choosing the country

= 2.2.1 - 2022.06.15 =
* Added ability to place orders using AliExpress API. You can place only 1 order using the API in the lite plugin version.

= 2.2.0 - 2022.06.08 =
* Compatibility with WP 6.0
* Added ability to review order and change its details before starting the order fulfillment process
* Fixed minor bugs

= 2.1.12 - 2022.02.06 =
* Fixed manual actions on the product list page

= 2.1.11 - 2022.01.31 =
* Compatibility with WP 5.9
* Fixed manual update
* Fixed minor bugs

= 2.1.10 - 2022.01.01 =
* Improved UX of the order (tracking) sync; now its UX similar to order fulfillment
* Improved UX of the orders list page: colored order fulfillment button; "AliExpress order ID" is a link to the AliExpress Order page
* Improved UX of the order edit page: added "eye" button that points to the frontend product product

= 2.1.9 - 2021.12.09 =
* Fixed: Correct currency is set for products during order fulfillment. (Please note: Chrome extension version should be 1.37 or higher)
* Fixed loadHTML issue
* Fixed: skipped empty description processing
* Fixed minor bugs

= 2.1.8 - 2021.11.22 =
* Fixed: product cost that is displayed in the external products
* Fixed: external product update logic
* Fixed: during the fulfillment order changes state from "Completed" to "Canceled" or "Pending Payment" when the package is received
* Fixed minor bugs

= 2.1.7 - 2021.10.23 =
* Added "include shipping cost to the product price" feature
* Added Setup Wizard
* Fixed bugs

= 2.1.6 - 2021.10.12 =
* Fixed crtitical JS bug
* Updated translation for russian language

= 2.1.5 - 2021.10.11 =
* Added reviews loader module;
* Added a compatibility with the new chrome extension allowing to get tracking numbers automatically
* Added ability to remove 'Shipping From" attribute automatically
* Fixed translation template
* Fixed minor bugs

= 2.1.4 - 2021.08.11 =
* Added: Compatibility with WP 5.8 and WC 5.5
* Fixed minor bugs

= 2.1.3 - 2021.06.04 =
* Refactoring source code of the product loader;
* Added an additional mobile styles to fix a problem with shipping methods drop-down occurring on the cart & checkout page
* Fixed a table prefix in a couple of SQL requests

= 2.1.2 - 2021.05.31 =
* Fixed: get product ids query
* Fixed: do no load unused variation images
* Fixed: backend review images showing

= 2.1.1 - 2021.05.29 =
* Fixed: Ali2Woo changes the order status if ALL related tracking codes are received;
* Fixed: Tracking Sync All starts ONLY if a user is logged in the AliExpress account;
* Changed some texts in alerts appearing while the tracking synchronization script work;
* Fixed small synchronization bug.

= 2.1.0 - 2021.05.24 =
* Fixed synchronization bug

= 2.0.9 - 2021.05.22 =
* Fixed regular price discount bug

= 2.0.8 - 2021.05.17 =
* Fixed backround loader issues.
* Fixed minor bugs

= 2.0.7 - 2021.05.05 =
* Fixed shipping selector bug on the checkout page; it occurred in the latest Woocommerce
* Fixed the currency symbol placement bug, now the placement can be managed via appropriate settings in Woocommerce.
* Improved the product background loader; I should work more stable now.
* Optimized plugin cron jobs, deleted rudiment cron events.
* Now the "Load External images" button load all images from a product description (see plugin settings)
* Fixed a lot of minor bugs

= 2.0.6 - 2021.04.05 =
* Added a new feature to the Import List that allows to rename product attributes in bulk
* Added the "Convert case of attributes and their values " option to the plugin settings. It allows to convert these data to the same case. For example: Red, red, RED attribute values will be converted to red.
* Fixed styles of the AliExpress Info popup
* Fixed error warnings that have appeared when Ali2Woo run with php8
* Fixed minor bugs

= 2.0.4 - 2021.03.05 =
* Fixed a bug with plugin upgrading script
* Fixed minor bugs

= 2.0.3 - 2021.03.02 =
* Fixed a bug causing disappearing of product variations in some cases
* Enhanced the interface of the Pricing Rules page
* Fixed a bug in checking for deleted variations
* Fixed a bug causing unused images loading 
* Fixed minor bugs

= 2.0.1 - 2021.02.14 =

* Fix minor bugs

= 2.0.0 - 2021.01.30 = 

* Added a feature to import product variants
* Added a feature to import unlimited products
* Added a feature to split product variants
* Added a feature to override product supplier
* Support the latest Ali2Woo chrome extension
* Support for WordPress 5.6
* Support for WooCommerce 4.9
* Fixed a lot of bugs

= 1.1.0 - 2019.08.19 = 
* Update plugin API
* Fixed minor bugs

= 1.0.3 - 2019.07.19 = 
* Fixed the issue with an empty products description and attributes (item specifics data)

= 1.0.2 - 2019.05.23 = 
* Fixed issues with the chrome extension
* Simpliy way to connect your store to the chrome extension

= 1.0.0 - 2019.03.16 = 
* The first released
== Upgrade Notice ==


 