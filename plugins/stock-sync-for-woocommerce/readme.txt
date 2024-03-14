=== Stock Sync for WooCommerce ===
Contributors: wooelements
Tags: woocommerce, stock synchronization, shared stock
Requires at least: 4.5
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 2.6.2
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sync stock quantities between two WooCommerce stores.

== Description ==

Stock Sync for WooCommerce allows you to share stock quantities between two WooCommerce stores. When someone purchases a product or you set stock quantity via admin, quantity will be instantly updated to the other store.

The plugin uses WooCommerce built-in API to communicate between stores. It's as secure as WooCommerce.

= Features =

* Share stock quantities between two WooCommerce stores
* Instantly sync stock changes when a product is purchased, refunded or edited via admin
* Easily view which products are being synced in the report
* Push all stock quantities from one store to another
* Background processing for preventing slowing the sites down
* Uses WooCommerce built-in REST API for secure communication between stores
* Compatible with WooCommerce 4.0 or above

= Pro Features =

* Support for unlimited amount of products (WordPress.org version maximum 100 products)
* Support for syncing between 2 - 10 stores (WordPress.org version 2 stores)
* Edit stock quantities directly on the report list
* Email notifications about syncing errors

[Upgrade to Pro](https://wptrio.com/products/stock-sync-pro)

= How to Use =

Please see [the documentation](https://wptrio.com/guide/getting-started-with-woocommerce-stock-sync-pro/).

= Support Policy =

If you need any help with the plugin, please create a new post on the [WordPress plugin support forum](https://wordpress.org/support/plugin/stock-sync-for-woocommerce). It is checked regularly but please note that response cannot be guaranteed to all issues. Priority email support is available for the Pro version.

= Other Useful Plugins =

Make sure to check out other useful plugins from the author.

* [WooCommerce Product Sync Pro](https://wptrio.com/products/woocommerce-product-sync-pro/)
* [Conditional Shipping for WooCommerce](https://wordpress.org/plugins/conditional-shipping-for-woocommerce)
* [Conditional Payments for WooCommerce](https://wordpress.org/plugins/conditional-payments-for-woocommerce)

== Installation ==
Stock Sync is installed just like any other WordPress plugin.

1. Download the plugin zip file
2. Go to Plugins in the WordPress admin panel
3. Click Add new and Upload plugin
4. Choose the downloaded zip file and upload it
5. Activate the plugin

Once the plugin is activated, you need to set up API credentials and import stock quantities from one store to the other. Please see [the documentation](https://wptrio.com/guide/getting-started-with-woocommerce-stock-sync-pro/).

== Changelog ==

= 2.6.2 =

* Improved background processing compatibility with 3rd party plugins, mainly payment gateways

= 2.6.1 =

* Bug fix: **View response for debugging** works again

= 2.6.0 =

* Added log type filter. It's now possible to filter log to display errors only
* Added debug information if the Push All tools fails
* API check now provides more debug information

= 2.5.0 =

* Declared compatibility with High-Performance Order Storage (HPOS)
* Improved API check
* Added process model setting (background / foreground processing)
* Improved SKU lookup. Previously out-of-date WooCommerce lookup tables caused syncing errors which this version fixes.

= 2.4.1 =

* Increased API check timeout limit
* XSS fix

= 2.4.0 =

* NOTE: This release replaces the single sync method with the bulk sync method. After updating please edit some stock quantity and check the log (*_WooCommerce > Stock Sync > Logs_*) to ensure no error messages appear
* Replaced the single sync method with the bulk sync method. Now all stock changes are sent in a combined request instead of sending one request per one change. This increases syncing performance especially for large orders.
* Improved error messages
* Improved logging
* Minor security fixes: CSFR & permission check for "view last response" action

= 2.3.2 =

* Improved API check compatibility with different web servers

= 2.3.1 =

* Fixed JavaScript error caused by the last update

= 2.3.0 =

* Added Log Retention setting (*_WooCommerce > Settings > Stock Sync > Log retention_*)
* Improved API credentials check
* WooCommerce 7.x compatibility

= 2.2.0 =

* Small bug fixes and improvements
* Updated WooCommerce compatibility info

= 2.1.0 =

* Added bulk sync feature (*_WooCommerce > Settings > Stock Sync > Bulk sync_*). Bulk sync combines all stock change requests in one request which will improve performance especially for large orders.
* Improved search in the report page (*_WooCommerce > Stock Sync_*). The search will now work with SKUs as well.

= 2.0.2 =

* Improved support for special characters in SKU. NOTE: Update the plugin on both sites at the same time to avoid stock discrepancies as this update changes how SKUs are handled
* Added WooCommerce activity check to avoid fatal errors when updating WooCommerce 
* Updated WooCommerce compatibility info

= 2.0.1 =

* Updated WP compatibility info

= 2.0.0 =

* PLEASE NOTE: This is a major update. While it should work right away in most cases, it's recommended to test it on staging environment before updating production sites.
* PLEASE READ [UPGRADE NOTES](https://wptrio.com/stock-sync-for-woocommerce-version-1-2-migration-guide/)
* Added Primary - Secondary Inventory functionality
* Improved logging
* Added tools "Push All" and "Update All"
* Added Background Processing to avoid slowing the site down when changing stock quantities
* Added Batch Processing to avoid memory and timeout issues
* Improved REST API performance for stock quantity operations

= 1.2.4 =

* WooCommerce 4.1.x compatibility check

= 1.2.3 =

* Improved compatibility with 3rd party plugins that alter SKUs

= 1.2.2 =

* Added better logging about syntax error when confirming credentials in the settings

= 1.2.1 =

* Improved compatibility with servers which don't support PUT requests

= 1.2.0 =

* Added possibility to sync stock status in addition to stock quantity

= 1.1.3 =

* Added filter for 3rd party plugins to prevent syncing in certain situations
* Added settings link to the plugins page

= 1.1.2 =

* Added missing files from the last update

= 1.1.1 =

* Improved Stock Sync page in the WordPress admin
* Added support for the upcoming Pro version

= 1.1.0 =
* Added API credentials check to the settings page
* Added debug logging option
* Syncing will be now done immediately after stock changed. Before there could be a delay of a few seconds.
* First retry in case of a failed sync will be now done immediately and later retries after 10 seconds.

= 1.0.1 =
* Bug fixes

= 1.0.0 =
* Initial version
