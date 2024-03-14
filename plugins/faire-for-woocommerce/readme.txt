=== Faire ===
Contributors: Faire
Tags: Faire
Requires at least: 4.7
Tested up to: 6.4
Stable tag: 1.7.0
Requires PHP: 7.4
WC requires at least: 6.0
WC tested up to: 8.5
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

**Faire** is an easy-to-use B2B platform that connects independent retailers and brands around the world. Start selling to 600,000+ independent retailers across the US, Canada, UK, Europe, and Australia. No set-up fees. No commitments.

= Why sell on Faire? =
- **Reach new buyers:** Boost your business with exposure to over 600,000 verified independent retailers worldwide.
- **Get secure, on-time payment—guaranteed:** We take care of payment processing, pay you directly, and cover 60-day payment terms for your customers.
- **Save time and money:** Grow efficiently with easy order management, plus free marketing and customer tools.

= Features =
With Faire and WooCommerce, you can get more sales at home and abroad. Selling on Faire includes:

- **An online store:** Get a free ecommerce storefront for your products.
- **Easy order fulfillment:** Fulfill new orders and create invoices.
- **Customer management:** Keep track of all your customers in one place.
- **Marketing campaigns:** Promote your brand with rich content emails.
- **Built-in analytics:** Get insights about how your business is doing.
- **Messaging system:** Connect with your retailers through the Faire platform.

= Benefits for customers =
Two powerful platforms working together to manage your business seamlessly.

- **Order syncing** Orders received on Faire will be automatically pushed into WooCommerce, so you can manage everything from one place. No more emailing orders by hand.
- **Inventory syncing** Inventory counts on Faire and WooCommerce will be continuously updated to prevent backorders, eliminate manual data entry and so retailers know when products are out-of-stock.
- **Product importing** When you create new products on WooCommerce, you will be able to directly import them to Faire without recreating product listings and continuously sync new products to your shop on Faire
- **Fulfillment syncing** When you fulfill an order on WooCommerce, we will automatically pull that status into your Faire brand portal as well as tracking IDs and shipment information.

= Installation =
Your first step will be to set up your Faire shop—then it takes only 3 minutes to connect your WooCommerce account. From there you'll follow a few quick steps:

1. Download the plugin directly from this page.
2. Log in to your WooCommerce account and navigate to the Module Manager page via the left menu bar.
3. Select 'Upload a module' and upload the zip file that downloaded from this page.
4. Once installed, you will see a screen that says "Module Installed!"
5. Directions on how to configure your WooCommerce settings can be found in [Faire's Help Center.](https://www.faire.com/support/articles/7656909641243)

If you need help along the way you can reach us at **integrations.support@faire.com**.

== Frequently Asked Questions ==

= Which version of WooCommerce do I need? =

You need to have a WooCommerce PHP of 7.4 or higher installed to use this integration.

= Do my language and location on WooCommerce need to match what is on Faire? =

Yes, we require that this information matches between the two platforms in order for the integration to work.

= Why is the product syncing section greyed out? =

This is likely because your location and currency on WooCommerce and Faire do not match. We require that these match in order for this integration to work.

= Why aren’t my orders syncing? =

This is likely because products have not been synced or linked.
If you’d like to sync new products from WooCommerce to Faire, you can do that by following the steps in the help center. If you have existing products on Faire, you can link the existing products to products in WooCommerce following the steps in the help center.

= Can I only sync inventory? =

Yes - you can choose to only sync inventory from WooCommerce to Faire.
I don’t want to sync all product details from WooCommerce to Faire (I use different images/prices/etc)
You have the ability to select any details you wouldn’t like to sync over by adding them to the ‘exclude sync fields’ settings on WooCommerce. This will prevent those details from being overwritten on Faire with details from WooCommerce.

== Changelog ==

= 1.7.0 =
* Add:    High Performance Order Storage compatibility.
* Fix:    Faire order import when order contain Discounts.

= 1.6.3 =
* Fix:    Installation process issues.

= 1.6.2 =
* Fix:    "Create orders automatically" setting.

= 1.6.1 =
* Add:    Allow to exclude product stock when syncing.
* Fix:    Stock update when stock tracking is not enabled.
* Fix:    Measurements for product variations.

= 1.6.0 =
* Add:    New Admin UI.

= 1.5.4 =
* Add:    Support for order discounts.
* Fix:    Wholesale price when importing linked product.
* Fix:    Prevent duplicated images.

= 1.5.3 =
* Add:    Allow variable product sync when some of the variations has invalid attribute set.
* Change: Faire product already linked log severity from ERROR to INFO.
* Fix:    Simple product linking, save variation id in simple product meta.
* Fix:    WP Multilingual (WPML) default locale.
* Fix:    Price rounding.
* Fix:    Remove measurements from update request when empty.

= 1.5.2 =
* Fix: Measurements sync from WooCommerce to Faire.

= 1.5.1 =
* Add: Product measurements.
* Fix: Values of mutually exclusive inventory fields.
* Fix: Log when order status is already up to date.

= 1.5.0 =
* Add: Option to cancel order sync process.

= 1.4.1 =
* Change: Order status log message type from ERROR to INFO when status is already up to date.
* Fix: Shipment update initiated from WooCommerce Order Edit screen.

= 1.4.0 =
* Added WP Multilingual Plugin (WPML) support.

= 1.3.10 =
* Fix: Order already in sync error when sync wan't running.

= 1.3.9 =
* Fix: Add default values for unit multiplier and minimum order quantity.

= 1.3.8 =
* Enable API logging active by default.

= 1.3.7 =
* Fix product unlinking bug on variant ids.

= 1.3.6 =
* Added product unlinking.

= 1.3.5 =
* Fixed country code in imported orders had format ISO_3166-1 alpha-3.
* Minor fixes to code format in class settings.

= 1.3.4 =
* Fixed orders sync should import orders 30 days back only first time.
* Fixed Faire orders should be skipped, synced or updated depending on status.

= 1.3.3 =
* Added case sizing fields to products.

= 1.3.2 =
* Fixed duplicating products linked to original faire products.

= 1.3.1 =
* Fixed supported browsers list for assets building.
* Order Sync: some address fields in Faire order could be empty.
* Order Sync: set order currency attending to first order item.
* Order Sync: fixed order item price when currency is not USD.

= 1.3 =
* During product sync, truncate description > 1000 chars and save as Draft.
* Fix product pricing rounding when calculating wholesale/retail price from base price.
* Added a filter to suppress currency matching.

= 1.2 =
* Add settings to exclude lifecycle state and taxonomy type during product sync.

= 1.1.1 =
* Fix product sync warnings.

= 1.1.0 =
* Product linking feature.

= 1.0.5 =
* Added a filter to customize products during orders sync.
* Strip HTML from long description. Add filter to disable stripping HTML.
* Pass empty short description

= 1.0.4 =
* Add wholesale/retail price settings.
* Geo constraint fix.

= 1.0.3 =
* Fixed sync was disabled because shop locale and currency were not correctly checked.

= 1.0.2 =
* Plugin settings: fixed price policy statement for EU and not EU countries.

= 1.0.1 =
* Remove short description from sync.

= 1.0 =
* First public release.
