=== WP-Lister Lite for Amazon ===
Contributors: wp-lab
Tags: amazon, woocommerce, integration, products, import, export
Requires at least: 4.2
Tested up to: 6.4.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

List products from WordPress on Amazon.

== Description ==

WP-Lister for Amazon integrates your WooCommerce product catalog with your inventory on Amazon.

= Features =

* list any number of items
* supports product variations
* supports all official Amazon category feeds as well as custom feed templates
* supports Fulfillment By Amazon (FBA)
* import products from Amazon to WooCommerce
* view buy box price and competitor prices
* includes SKU generator tool

= More information and Pro version =

Visit <https://www.wplab.com/plugins/wp-lister-for-amazon/> to read more about WP-Lister and the Pro version - including documentation, installation instructions and user reviews.

WP-Lister Pro for Amazon will not only help you list items, but synchronize sales and orders across platforms and features an automatic repricing tool.

== Installation ==

1. Install WP-Lister for Amazon either via the WordPress.org plugin repository, or by uploading the files to your server.
2. After activating the plugin, visit the Amazon account settings page and follow our guide on [How to set up WP-Lister for Amazon](https://docs.wplab.com/article/85-first-time-setup).

== Frequently Asked Questions ==

= What are the requirements to run WP-Lister? =

WP-Lister requires a recent version of WordPress (4.2 or newer) and WooCommerce (3.0 or newer) installed. Your server should run on Linux and have PHP 7.0 or better with cURL support.

= Does WP-Lister support windows servers? =

No, and there are no plans on adding support for IIS.

= Are there any more FAQ? =

Yes, there are. Please check out our growing knowledgebase at <https://www.wplab.com/plugins/wp-lister-for-amazon/faq/>

== Changelog ==
= 2.6.9 - 2024-01-25 =
* Fix: Escape input data from URL
* Fix: Allow the installation of Feed Templates for the newly added marketplaces
* Fix: When uploading invoices to Amazon, send the order number if the invoice number is not set

= 2.6.8 - 2024-01-17 =
* Hotfix - Feed value for standard_price and sale_price not getting set properly

= 2.6.7 - 2024-01-16 =
* New: Added the ability to parse the newer listing templates from Amazon
* Fix: Javascript issue preventing the Change Profile bulk action tool from working
* Fix: Convert price value to use a decimal point character if coming from a profile field attribute

= 2.6.6 - 2024-01-10 =
* New: Added new beta support for these marketplaces: Belgium, Egypt, Mexico, Poland, Singapore, South Africa, Sweden, Turkey, Polan
* Fix: Added the missing MY_VOEC reseller category
* Fix: List on Amazon and Change Profile window not working
* Dev: Display local time in the log files

= 2.6.5 - 2024-01-02 =
* Fix: List on Amazon not working when WC Block Editor is enabled
* Fix: Link to download personalization archive not showing up
* Fix: Lowest offer details window not showing Lowest Offer Data
* Dev: The wpla_mcf_enabled_order_statuses filter must have the same values in both places where it is used
* Dev: Log files not getting created
* Dev: Display local time in the order details window

= 2.6.4 - 2023-12-06 =
* Tweak: Remove the deduction of the promotional discount from the subtotal to display discount on the edit order page
* Fix: Invalid argument passed to wpla_delayed_amazon_order_completion
* Fix: Error accessing get_meta() on null
* Fix: Uninitialized cart object when accessing from the REST API
* Fix: Run checks on the GetOrders response to prevent fatal errors
* Fix: Add the non-prefixed order status when checking for an order's ability to be submitted to FBA

= 2.6.3 - 2023-11-16 =
* Tweak: Removed the Supported Marketplace text for B2B Price support
* Fix: Show Problems button in the Feeds page not working
* Fix: Unclosed tag causing display issues in the product pages
* Fix: Do not override variation images with custom gallery images
* Fix: Missing plugin update notification on some sites
* Fix: License form disabled with empty license details
* Dev: New filter: wpla_mark_item_as_modified_data
* Dev: New filter: wpla_delayed_amazon_order_completion

= 2.6.2 - 2023-10-19 =
* Fix: Orders table not showing WPLA columns and meta boxes

= 2.6.1 - 2023-10-18 =
* New: Added views for Locked and Unlocked listings
* Fix: Unable to parse FBA Manage Inventory reports due to the missing seller-sku column
* Fix: Make sure the out-of-stock threshold is of int data type
* Fix: Gather shipment rates from all active accounts
* Fix: Store the date_paid and ship_dates using the local time
* Fix: Skip variables (parents) when looking for listings with missing ASINs
* Fix: Missing dompdf autoloader
* Dev: Rotate logs on a daily basis
* Dev: Added back the AWS data directory

= 2.6.0 - 2023-09-28 =
* New: Support for WooCommerce's High Performance Order Storage feature
* New: Enhanced searching performance in the Listings table
* New: Added the ability to lock listings to only update their stock quantity and price (Beta)
* New: Added the option of using a dedicated DB table when creating fulfillment feeds (Beta)
* Tweak: Split products list into several rows when running inventory checks
* Fix: Generating empty order fulfillment feeds
* Fix: Warnings in the Feeds and Edit Product pages
* Fix: Error when the filter woocommerce_get_product_from_item is triggered
* Fix: Prevent query string from being URL-decoded
* Fix: Variables are left without ASINs after they are published to Amazon
* Fix: Matching using SKU does not return any results
* Fix: Using the Parent option for the Variation Title setting should also pull the Listing Title when available
* Fix: Added support for the item-condition column for InventoryLoader feeds
* Fix: Size mapping case sensitivity issue
* Dev: Added the filter wpla_order_builder_update_skip_statuses
* Dev: Added the action wpla_order_builder_before_create_order
* Dev: Added the action wpla_item_updated_from_report
* Dev: Update the Dompdf library to address a vulnerability
* Dev: Added the ability to limit the results when generating pending feeds to prevent timeouts
* Dev: Removed the /data directory from the AWS library

= 2.5.5 - 2023-07-10 =
* New: Added the shipping methods ExpeditedGlobalParcel and ExpeditedLocalParcel to the GLS carrier
* New: Added the shipping carrier DX Freight
* Tweak: Increase the timeout value when trying to activate the plugin license
* Fix: Personalization data from orders not getting recorded
* Fix: Product-level _amazon_price value must have priority over profile value
* Fix: Added the missing Reseller Category value JE_VOEC
* Dev: Handled errors in the Accounts page
* Dev: Removed MWS fields from the Accounts page
* Dev: Added the filter wpla_force_amazon_collected_taxes

= 2.5.4 – 2023-05-17 =
* New: Added the option of saving the Amazon Order ID as both a meta value and an order note
* Tweak: Readability of the status text for tools that run in batches (Step X / Y items processed)
* Dev: Handle errors from the getReports call
* Fixed: Warnings from the WPLA_InventoryCheck class
* Fixed: Warnings in the Listings page
* Fixed: Added a Primary Key to the amazon_shipping table
* Fixed: Empty quantity value in the Repricing table
* Fixed: Importer attempting to create variable products from simple listings

= 2.5.3 – 2023-04-27 =
* New: Added the ability to search the Stocks Logs using the Amazon Product ID
* New: Added a setting to skip adding line item SKUs to created WooCommerce orders
* Tweak: Enhanced the search results for the Listings and Repricing tables
* Tweak: Show the total stocks and FBA stocks in the Repricing table
* Fixed: Some custom feed templates not showing dropdown options for valid values
* Fixed: Undefined variable [wpl_repricing_pricing_options] error in WP-Lister for Amazon Lite
* Fixed: Revert stocks of cancelled orders from the Unshipped order status
* Dev: New filter added: wpla_orderbuilder_cleanup_session
* Dev: Better error handling when calling getCatalogItem()
* Dev: Removed the file gen_stub.php from the AWS library (false-positive malware report)

= 2.5.2 – 2023-03-09 =
* Tweak: Use basedir in storing log files
* Fixed: Amazon customer data not getting cleared from session after importing orders
* Fixed: Use the correct UserAgent in the API calls
* Fixed: Added the missing KZ_VOEC property
* Fixed: Repricer not setting to the max possible price

= 2.5.1 – 2023-02-06 =
* New: Longer data retention options for Amazon orders
* New: Import the listing description from the Amazon catalog
* New: Added a setting to periodically pull Order Reports to be able to import Business Tax IDs for business orders
* Tweak: Enhanced the frontend checkout by sending the feed updates to a background task
* Tweak: Lowered the number of ASINs when querying for price updates to prevent getting throttled
* Tweak: WP capability check - changed edit_others_pages to edit_others_shop_orders and edit_orders_products
* Tweak: When generating a new feed, exclude all invalid listings (missing SKUs, etc) so they do not get included in the CSV file
* Tweak: Update price check date on ASINs with error to prevent from staying at the front of the queue
* Fixed: $SubmittedDate undefined notice
* Fixed: Added the missing SG_VOEC property
* Fixed: Error when trying to count attributes from an imported listing
* Fixed: Handle throttled responses for GetOrderAddress and GetOrderBuyInfo calls
* Fixed: Warning "foreach() argument must be of type array" when importing some orders
* Fixed: Fatal error when the parent ASIN could not be loaded
* Fixed: Dompdf library clash when the Invoice Upload setting is enabled
* Dev: Log errors from getCompetitivePricing

= 2.5 – 2023-01-02 =
* New: Now you can request and download the Pending Order Report from within WP-Lister
* Tweak: Delay consecutive calls to the getCompetitivePricing API to avoid triggering the rate limit
* Fixed: WP-Lister will now get notified of changes made via the Admin Columns Pro plugin
* Fixed: PHP Error: Call to undefined method getFeedId()
* Fixed: Invoice upload feed should always be sent as UTF-8
* Fixed: Library clash for FontLib\Autoloader and Dompdf\Autoloader
* Dev: Added a custom namespace to the Dompdf library to prevent collisions
* Dev: More verbose debug log allows for more precise bug squashing when processing shipping fees
* Dev: Allow plugins to control whether feed content should be utf8_decode'd using the new wpla_utf8_decode_feed_content filter
* Dev: Fully compatible with WooCommerce 7.2

= 2.4.4 – 2022-12-08 =
* Security: Fixed a Reflected Cross-Site Scripting (XSS) vulnerability reported by Animesh Gaurav
* Tweak: Make the list of prepared listing issues expandable to save screen space
* Fixed: Invoice uploaded to amazon could show as blank PDF due to invalid characters
* Fixed: Issue where "There are no shipping options available" was shown incorrectly
* Fixed: Shipping method ID not being accessed correctly
* Fixed: Invalid argument for foreach in ListingsModel.php
* Fixed: Call to undefined method getFeedId() in AmazonFeed.php
* Fixed: Check for an existing Dompdf class before including the autoloader (3rd party plugin compatibility)
* Dev: Force download of log files instead of directly linking to them

= 2.4.3 – 2022-11-24 =
* Tweak: Hide parent container items when filtering listings to only show items without ASINs
* Fixed: Repricing did not reset to max price if seller has buy box and no competition
* Fixed: Improved downloading gallery thumbnails when importing items from Amazon
* Dev: Added definition to be able to access the Contributors property when fetching catalog items
* Dev: Added parameter 'product_node' to the wpla_filter_imported_product_data filter

= 2.4.2 – 2022-11-08 =
* Tweak: Removed old report types which are no longer supported by Amazon's new SP-API
* Tweak: Regularly clean the amazon_jobs table
* Fixed: Uploading Invoices to the SP-API
* Fixed: Import Book attributes from the API
* Fixed: Use the ReportDocument::getCompressionAlgorithm() method to determine if the report body needs to be decoded
* Fixed: Call to a member function get_regular_price() on bool when passed an invalid/nonexistent product
* Fixed: Attempt to read property "account_id" on array
* Fixed: Error when trying to access null as object
* Fixed: Call to function getAttributes() on bool
* Fixed: GuzzleHttp conflict with other plugins
* Dev: Added the method getEligibleShipmentServices()

= 2.4.1 – 2022-10-24 =
* New: Importing listings is now using the Catalog API (SP-API)
* Tweak: Removed the option to import using the old Listings API
* Fixed: Various minor issues related to the new SP-API 
* Fixed: Database error over large attribute sets

= 2.4.0 – 2022-10-18 =
* New: Use the new SP-API for feeds and catalog requests, including repricing and importing tasks (beta)

= 2.3.0 – 2022-10-13 =
* New: Use the new Catalog API to pull images and bullet points when importing listings
* New: Added shipping methods for the Australia Post-ArticleID carrier
* Tweak: Improved performance in SQL query when building large feeds
* Tweak: Display "Sync sales is disabled" in the order history log for products where syncing has been disabled
* Fixed: Removed redundant code causing a "Undefined variable awsToken" notice
* Fixed: Added the missing constant AU_VOEC in the SP-API library
* Fixed: Undefined offset warnings when saving variable products
* Dev: Added the filter wpla_run_plugin_update_check to override the frequency of update checks from the license server
* Dev: Added filter wpla_attribute_shortcode_value

= 2.2.9 – 2022-09-17 =
* New: Added the CTTExpress shipping provider
* Tweak: Improved Product Bundles support: Update bundle quantity when child components are updated
* Fixed: Issue where requesting the FBA Shipment report would fail
* Fixed: Plugin conflict with Advanced Shipping Tracking Pro
* Fixed: Avoid double-encoding data from the Merchant Listings report
* Fixed: Invalid value 'NZ_VOEC' for 'deemed_reseller_category' error
* Fixed: Use WC_Product_Bundle::get_bundle_stock_quantity() to get stock quantity of bundled products
* Dev: Added filter wpla_shipment_tracking_instance

= 2.2.8 – 2022-08-30 =
* Fixed: Record the correct Gift Wrap amount from Amazon
* Fixed: Undefined function get_stock_managed_by_id() (since 2.2.7)
* Fixed: Undefined variable wpl_is_reg_brand
* Fixed: Warnings on the Advanced Settings page

= 2.2.7 – 2022-08-26 =
* Tweak: Exclude product meta when cloning products
* Fixed: Issue where scheduled reports not getting requested
* Fixed: Amazon Business flag was not shown on the Orders page
* Fixed: Improved support for variations with parent-level stock management (from Yatin @ multidots) 

= 2.2.6 – 2022-08-09 =
* Fixed: Download customized order item data
* Fixed: Reports not being downloaded and displayed properly
* Fixed: Create Customers action failing due to missing order email address
* Fixed: Skip automatically generating feeds when running imports
* Fixed: Support for Price Based on Country plugin
* Fixed: Automatically process supported reports
* Fixed: Still show the search form when trying to match products and the initial search returned no results
* Dev: Removed unnecessary use of GuzzleHttp\json_encode()

= 2.2.5 – 2022-08-02 =
* Fixed: Missing buyer details from orders
* Fixed: Compatibility with PHP 7.4 restored
* Fixed: Improved compatibility with Amazon's new SP-API
* Fixed: Matching a WooCommerce product to Amazon would return no results
* Tweak: Do not fetch address and buyer info on pending and cancelled orders
* Tweak: Improved handling of throttling when importing orders and order addresses
* Dev: Result from GetOrders call was too large to be stored in log

= 2.2.4 – 2022-08-01 =
* New: WP-Lister now uses Amazon's new SP-API to fetch orders and request reports
* Tweak: Updated the Accounts pages to allow users to login and fetch token from existing accounts
* Fixed: Fix French character encoding when importing from Amazon
* Fixed: Feed Error Emails still sending despite the setting being off
* Fixed: Include the SKU and error message in the feed submission error email
* Fixed: Prevent fatal errors if the data being accessed was not provided by Amazon
* Dev: Compatible with WooCommerce 6.7
* Dev: Added the ability to toggle calls between sandbox and production APIs
* Dev: Added wpla_update_feeds action to force WPLA to generate feeds for changed listings

= 2.2.3 – 2022-07-11 =
* New: Added option to send an email to site admin in case of any feed submission error
* Tweak: Apply the wpla_orderbuilder_prices_include_tax filter every time the woocommerce_prices_include_tax option is pulled
* Fixed: Check All button on the Import page was not working
* Fixed: Variation main image fallback must first be set prior to attempting to load from the product gallery
* Fixed: Undefined array key "leadtime-to-ship" warning
* Fixed: Uncaught TypeError: round(): Argument #1 must be of type int|float, string given
* Fixed: Warning Undefined index: listing_title
* Dev: Added filter wpla_override_variation_attribute_with_profile to allow WPLA to use variation attribute value from the profile
* Dev: Use WC_Product methods to get and set product price in Woo_ProductWrapper.php
* Dev: Lock table for reading when using the wpla_lock_feeds_table filter

= 2.2.2 – 2022-05-26 =
* Tweak: Adjusted the shipping methods for the GLS courier
* Tweak: Store the Amazon account title with each order when creating orders in WooCommerce
* Tweak: Increased the timeout limit when fetching converted template from conversion server
* Fixed: Error message "Trying to access offset of null in WPLA_AjaxHandler.php"
* Fixed: Fallback to accessing post data from $_REQUEST for sites that cannot read from php://input
* Fixed: Possible issue processing special characters (introduced in 2.2.1)
* Dev: Include Amazon account name and Merchant ID in the WC REST API response for GetOrder calls
* Dev: Store the IOSS in the order metadata

= 2.2.1 – 2022-05-09 =
* New: Added invoice uploading for the WooCommerce Print Invoices plugin by SkyVerge
* Tweak: Convert merchant-shipping-group field value to UTF-8 prior to importing
* Tweak: Handle new AFN quantity fields (afn-fulfillable-quantity-local and afn-fulfillable-quantity-remote)
* Fixed: PDF Invoice missing images and fonts
* Fixed: French characters not displaying correctly
* Fixed: Rare issue where a product's stock level could be set to 0 when updating the product in WooCommerce
* Fixed: Removed shirt_size fields from the parent_var_columns array
* Dev: Added logging for checking FBA autosubmit orders
* Dev: Added the filter wpla_update_woo_stock_skip_status_array
* Dev: Added filter wpla_filter_skip_listing_feed_item to skip certain listings from being added to feeds

= 2.2.0 – 2022-04-06 =
* New: Added "Couriers Please" to the list of available shipping providers
* New: Retry failed requests due to temporary network issues automatically (HTTP code 502)
* New: Added more parent variation columns (age_range_description, fabric_type, shirt_size, shirt_size_class, shirt_size_system)
* Tweak: Delay creating orders without order items to circumvent temporary network issues (502 bad gateway)
* Tweak: Check ignore_orders_before against PurchaseDate instead of LastUpdateDate
* Tweak: Convert listing titles to UTF8 before importing
* Fixed: Skip listing parent variables if variations mode is flat
* Fixed: When Background Inventory Check is off, also unschedule the  wpla_bg_inventory_check_run jobs
* Fixed: Prevent creating orders without any items
* Fixed: Undefined property stdClass::ShippingTax
* Fixed: Selected value in the Feed Attributes list showing up as Custom Values
* Dev: Added the function wpla_is_json()
* Dev: Added logging to the handle_woocommerce_order_status_update_completed checks
* Dev: Added the filters wpla_import_update_product_price and wpla_import_update_amazon_price
* Dev: Increased cURL timeout limit from 15 to 30 seconds
* Dev: Store the Earliest and Latest Ship Dates in the order postmeta
* Dev: Commented out the code block with ATUM plugin support which now is causing a fatal error
* Dev: Passed post_id parameter is in JSON format when using the REST API. Decode and extract the correct post_id if necessary
* Dev: Check for a valid order_id before adding a history entry that the order was created
* Dev: Compatible with WooCommerce 6.3.1

= 2.1.0 – 2022-02-18 =
* New: Added support for custom templates with multi-marketplace fields
* New: Added AU shipping carriers Australia Post and StarTrack
* Tweak: Skip checking for stock sync issue if the quantity property is empty to avoid checking against FBA listings
* Tweak: Include the time when filtering the stocks log by date 
* Fixed: Convert units to a dot decimal character for the length, width and height properties
* Fixed: Check for the ups_shipment_ids index to prevent getting an Undefined Index warning
* Fixed: Load thickbox library on the Products table to make sure product matching works
* Fixed: Load thickbox library on the Import page
* Dev: Added $order to the wpla_shipping_service_id_map and wpla_shipping_service_title_map filters
* Dev: Compatible with WooCommerce 6.2

= 2.0.8 – 2022-01-28 =
* New: Added DE colors to the color map list and switch to using text input with autocomplete because Amazon now requires localised color names
* Fixed: Improved script loading to make sure plugin scripts are only loaded within WP-Lister pages
* Fixed: Assigning the main_image_url for each variation was using the primary image of the parent product instead
* Dev: Added filter wpla_duplicate_product_excluded_meta to modify or remove the excluded product meta when duplicating WooCommerce products

= 2.0.7 – 2021-12-30 =
* New: Added support for dismissible notices, including warnings about outdated PHP versions
* New: Display important announcements like important new features or critical fixes as dismissible notices
* Fixed: Updated support for WooThumb's new way of storing the variation images

= 2.0.6 – 2021-12-07 =
* New: Added a setting to skip checking for invalid EAN and UPC
* New: Show warning when a PHP version older than 7.4 is detected
* Tweak: Use SQL transactions when building shipment feeds to prevent overwriting existing shipments
* Fixed: Possible fatal error during feed generation (Uncaught TypeError: number_format(): Argument #1 must be of type float)
* Fixed: Use the wpla_orderbuilder_prices_include_tax filter to fix the shipping total of created orders
* Dev: Added wpla_process_fba_report_row action hook
* Dev: Added wpla_add_attribute_name and wpla_add_attribute_label filter hooks
* Dev: Updated the translation files and the strings.php index

= 2.0.5 – 2021-11-22 =
* New: Allow custom values to feed attribute drop downs
* New: Import Proxy setting for those sites whose IP are banned from importing from Amazon
* Fixed: Filter wpla_orderbuilder_prices_include_tax not working properly
* Fixed: Added feed_product_type and country_of_origin to the parent feed columns
* Fixed: Removed the parent columns that according to Amazon should not be submitted in the parent listings
* Fixed: Make sure get_data_rows() always returns an array
* Fixed: Fatal error when parsing personalized data
* Fixed: Parse French Valid Values templates

= 2.0.4 – 2021-11-02 =
* Tweak: Record the Tax Registration ID / VAT ID when creating order in WooCommerce
* Fixed: Issue where the FBA shipping option was not available during checkout
* Dev: Set the ATUM Multi-Inventory hooks to the highest priority as suggested by their support staff
* Dev: Added filter wpla_listen_for_sku_changes to disable popup when the change action is triggered by the SKU field

= 2.0.3 – 2021-10-17 =
* New: Store the FulfillmentChannel in the order postmeta
* Tweak: Round the imported dimension values down to 2 decimal places
* Fixed: Possible fatal error parsing shipping fee values when generating feeds
* Fixed: Reformat the price value to ensure that it can be parsed properly by PHP
* Fixed: Check for the REQUEST_METHOD key which is not available in a CLI environment
* Fixed: Check for wpla_permissions to prevent warnings. This isn't available in multisite installs
* Dev: Added action hook wpla_buy_box_pricing_updated
* Dev: Added filter hook wpla_update_custom_order_status

= 2.0.2 – 2021-09-21 =
* Fixed: Removed invalid parameter in process_ship_from_address() method
* Fixed: Prevent WP-Lister from setting order status back to Processing if it is already marked as Completed

= 2.0.1 – 2021-09-16 =
* New: Added a setting to choose whether to match order items using ASIN or SKU
* Tweak: If there is no tracking number found, try pulling it from the tracking_code post meta key
* Fixed: Bulk editing in WooCommerce was not marking listings as Changed
* Fixed: Display the correct submit box actions for variable-product-part products
* Fixed: Decode keyword values into ISO-8859-1 charset to fix feed errors when using umlauts
* Fixed: Warning "Invalid argument supplied in foreach" when saving custom size map settings
* Fixed: Issue where ship-from address was not getting pulled correctly
* Fixed: Issue where the disable_sale_price setting was ignored in some cases
* Fixed: Issue whee feeds could fail due to strtolower conversion (since 2.0)
* Dev: Added the filter hook wpla_find_matching_tracking_provider
* Dev: Cleaned up the call_complete_order() method and added the wpla_processed_third_party_tracking action

= 2.0 – 2021-08-28 =
* New: Create separate size maps for different size fields using the new "Custom Size Mapping" option
* New: Added a "Default Ship From Address" option on the Advanced Settings page
* New: Added support for the Variable Product Part product type
* New: Variable Product Part support for ATUM Product Levels plugin
* New: Shipping tracking support for YITH WooCommerce Order Tracking plugin
* New: Added the Hermes UK shipping provider
* New: Store the IOSS number as order line item meta when creating orders in WooCommerce
* Tweak: Changed the size map select boxes into autocomplete input boxes to allow custom values to be used
* Tweak: Size column mapping: attribute shortcodes are executed first and all custom sizes are transformed to lowercase
* Fixed: FBA tracking was not getting processed if background inventory checking was enabled
* Fixed: Sync product bundle products after increasing and decreasing stocks (WooCommerce Product Bundles)
* Fixed: Fixed the shipped date and carrier-code for DHL (YITH Order Tracking)
* Fixed: Disable transactional emails (WooCommerce Germanized)
* Fixed: Added missing description for the Gift Wrap Options setting
* Fixed: Make sure to leave carrier-name field empty if carrier-code is not "Other"
* Fixed: Perform some additional checks to prevent rare issues with 3rd party plugins
* Fixed: Warning "Required parameter follows optional parameter" on PHP8
* Fixed: Possible fatal error when product price returned is not a number
* Fixed: Newly matched variations were not inheriting the parent's profile
* Fixed: MSRP in the Repricer was not displaying the correct price 
* Dev: Added filter wpla_shipping_total
* Dev: Added parameter to the wpla_order_builder_line_item filter
* Dev: Database upgrade to version 53 - added indices to feeds and orders tables

= 1.9.7 – 2021-06-07 =
* New: Added a search box in the List on Amazon popup to filter profiles
* New: Added an excluded markets setting for the Size Map feature
* New: Setting option to assign a default profile for all matched listings 
* New: Setting option to complete WooCommerce orders after successful FBA submission 
* Tweak: Added more parent variation columns (recommended_browse_nodes, material_composition, color_map, lifestyle1)
* Fixed: Exclude WP-Lister meta when duplicating products
* Fixed: Ship Method and Ship From values not getting displayed in the Order Metabox
* Fixed: Product Bundles: Check for errors when adding bundle to order
* Fixed: Profiles per page not being saved
* Fixed: Missing screen option label
* Fixed: Listings from inactive accounts showing up in the getStatusSummary method
* Fixed: Uncaught exception when cancelling feed submission
* Fixed: Warnings from required parameters preceded by an optional parameter
* Fixed: Move the removeASINFromProducts() call to before trashing the listings
* Fixed: Load the child listings when removing listings from Amazon using a parent product
* Dev: Added the wpla_orderbuilder_prices_include_tax

= 1.9.6 – 2021-04-27 =
* New: Set a custom WooCommerce order status for cancelled Amazon orders
* Fixed: Add product bundle information when creating WooCommerce orders
* Fixed: Possible issue switching profiles for a product from the edit product page
* Dev: Added filter wpla_fallback_stock_quantity

= 1.9.5 – 2021-04-14 =
* New: Added the Default Shipping Method setting option to set the default shipping provider
* New: Added a configure link on the new Ship From field to make it easier to manage Ship From addresses
* Tweak: Updated the list of shipping carrier codes
* Tweak: Skip feed generation when importing/updating listings using WP All-Import
* Tweak: Adjusted the ValidValues parser to handle the new format from Amazon
* Fixed: Show the stock log and feed submitted date in local time
* Fixed: Only combine the keywords if keyword_fields_type is set to separate
* Fixed: Fetch the bullets into an array so they could be stored separately in _amazon_bullet_pointN postmeta fields
* Dev: Added filters wpla_fetch_overwrite_description and wpla_fetch_overwrite_bullets

= 1.9.4 – 2021-03-25 =
* New: Allow the individual profile switching of products linked to multiple listings
* New: Added the option of pulling specific order numbers from Amazon in the Orders page
* New: Added more size options in the size map options to include apparel_size values
* New: Added a warning for partially published variable listings
* New: Added date range filter to the Stocks Log page
* Tweak: Improved quantity calculation in the Listings table
* Fixed: Rare issue where calling set_include_path() multiple times could cause timeout and memory errors
* Fixed: Call to a member function get_customer_id() on null in WooCommerce Payments plugin
* Fixed: Ensure that the second parameter in the wpla_get_stock filter is an integer
* Fixed: Use wc_get_product() instead of get_product()
* Dev: Switched all jQuery .success() calls to .done()
* Dev: Added filter wpla_after_processing_inventory_report

= 1.9.3 – 2021-03-03 =
* New: Added support for additional image fields in Handmade feed templates
* New: Added a setting to turn off gift line item processing on new orders
* New: Use the stock status (in stock/out of stock) to list products which have stock management disabled
* New: Record the earliest and latest ship dates from Amazon into the order notes
* New: Show a link to the Amazon order page on the WooCommerce order details page
* Tweak: Updated the list of supported plugins for uploading invoices to Amazon
* Tweak: WP-All Import support: feeds are not updated during import to improve performance
* Fixed: Issue extracting error messages from feeds
* Fixed: Notification boxes layout

= 1.9.2 – 2021-02-07 =
* New: Ship From field in the order tracking meta box
* New: Ship From addresses can now be stored on the Advanced Settings page
* Dev: Added the wpla_force_create_fba_orders filter

= 1.9.1 – 2021-02-03 =
* New: Added the Shipping Method field in the order shipment details metabox
* New: Added a setting to apply profile pricing options to the repricer
* Tweak: Check for a valid profile before processing the profile price
* Fixed: Display processed profile price when displaying the variations in the Listings table
* Fixed: Ignore stock updates from the WPC Product Bundles plugin since it causes as infinite loop
* Fixed: Use the WC_Order_Item_Product::get_product() if available
* Dev: Added the filter wpla_enable_rest_api_listener to turn off feed generation triggered by REST updates
* Dev: Added the filter wpla_shipping_instance_id to set the instance_id

= 1.9 – 2021-01-06 =
* New: Added support for the WooCommerce UPS Shipping plugin by PluginHive
* New: Allow to set a custom image URL via the post meta key 'amazon_image_url'
* New: Added Australia Post to the list of default shipping providers
* Tweak: Improved algorithm to match shipping providers
* Tweak: Allow stock logs to be stored for SKUs longer than 32 characters
* Fixed: Fixed issue where a normal order could be processed as FBA

= 1.8.3 – 2020-12-07 =
* Tweak: Improved handling of unexpected or incomplete XML responses
* Tweak: Added outer_material_type to the list of enabled parent columns
* Fixed: Disable profiles with inactive accounts from the List on Amazon dropdown
* Fixed: Matching by ASIN could cause duplicate category feed to be created in some cases

= 1.8.2 – 2020-11-24 =
* New: Added support for the temporary tax reduction add-on for the German Market plugin
* New: Added support for Germanized Pro's newer invoice action hook for uploading invoices to Amazon
* Tweak: Display the status of listings with fixed profile quantity as "online" 
* Tweak: Display a "No profiles found" message in the dropdown when trying to list without available profiles
* Fixed: Skip sending sale_price if the external repricer setting is enabled
* Fixed: Images from the customization data not being copied to the uploads directory
* Fixed: Line item metadata was not saved properly when creating orders in WooCommerce
* Fixed: The setting "Default carrier name" was not regarded in some cases
* Fixed: The screen option "Orders per page" in the Orders page was not saved
* Fixed: FBA Shipment feed error was not getting caught
* Dev: Added the filter wpla_build_feed_type
* Dev: Added the filters wpla_order_builder_fixed_vat_percent and wpla_order_builder_fixed_tax_rate_id
* Dev: Added latin2 to the supported charsets to convert to UTF8
* Dev: Set SQL_BIG_SELECTS to prevent hitting the max join limit in MySQL
* Dev: Use the getter methods in WC_Tax class to trigger the filters
* Dev: Replace all jQuery.attr() calls with jQuery.prop() and jQuery.data()

= 1.8.1 – 2020-10-14 =
* New: Added setting option to use the product name from WooCommerce when creating orders from Amazon in WooCommerce
* Tweak: Issue where stock for cancelled orders was not replinished if that order status was still pending and no order in WooCommerce was created
* Tweak: Added more parent variation columns (size_name, size_map, gem_type)
* Fixed: Issue where duplicate listings could show up in feeds (since 1.8)
* Fixed: Shipping method ID not being used
* Dev: Added filter hook wpla_order_builder_force_deduct_line_taxes
* Dev: Added filter hook wpla_shipping_method_title
* Dev: Added filter hook wpla_new_order_item_meta

= 1.8 – 2020-09-23 =
* New: Display the current profile in the Edit Product screen
* New: Added support for the new customized item data in Amazon orders
* Tweak: Store the color name alongside the hex code when importing the new customization data
* Tweak: Store the SKU as line item meta when creating orders
* Tweak: Added target_gender to the allowed parent columns
* Tweak: Run the repricer on newly listed products and variations automatically
* Tweak: Display the current profile name of variable products on the Switch Profile form
* Fixed: Some variable listings were not getting included in the background update process
* Fixed: Use WPLA_ProductWrapper::getStock() to disregard ATUM's multi-inventory values
* Fixed: Matched variations not getting assigned a profile ID
* Fixed: Escape the staging site pattern to prevent the unknown modifier warning
* Fixed: Catch thrown WC_Data_Exception when trying to assign an invalid variation ID
* Fixed: Multiple wpla_update_reports scheduled actions not being cleared
* Dev: Log errors to WC_Logger
* Dev: Added the wpla_new_order_item_product filter
* Dev: Added the wpla_before_order_builder_line_item filter

= 1.7 – 2020-08-18 =
* New: Support for Amazon NL
* New: Support for German Market's tax reductions
* New: Added Lawn & Garden feed template for Amazon UK
* Tweak: Inventory Check: Skip products with FBA override set enabled
* Tweak: Remove ASIN from products when using the Remove from Amazon bulk action
* Tweak: When checking for the staging site pattern, fallback to the siteurl if SERVER_NAME is unavailable
* Tweak: Do not autoload the inventory queue data to reduce memory footprint
* Tweak: Added display_type and watch_movement_type columns to the allowed parent variation columns
* Fixed: Encoding issue when uploading invoices to Amazon, resulting in empty PDFs
* Fixed: Listings per page setting not getting saved
* Fixed: Matched draft products are not getting included in the feeds
* Fixed: Stop checking for referrer when using nonce for search fields to prevent the URL from getting too long
* Fixed: Deduct the taxes from the shipping total when autodetecting taxes
* Fixed: Make sure ActionScheduler is available before using the library
* Dev: Use WC_Order::add_item() to add order line items
* Dev: Added wrapper function wpla_enqueue_async_action

== Changelog ==
= 1.6 – 2020-06-26 =
* New: Support for uploading invoices created by WooCommerce PDF Invoices and Packing Slips
* New: Perform background inventory checks and get notified about discrepancies
* New: Setting option to use a dedicated background process to check for new orders
* New: Setting option to assign an existing payment gateway to orders created by WP-Lister
* New: Setting option to disable "wpautop" filter when processing shortcodes
* New: Setting option to enable external repricing for all products globally
* New: Allow to edit MSRP on the Min/Max Price Wizard screen
* New: Added support for ATUM Multi Inventory plugin
* New: Added support for ATUM Product Levels (BOM) plugin
* New: Added support for DHL for WooCommerce plugin
* Tweak: Always record the fba_quantity to compare with current stock level and mark listings as out of stock even when Fallback to Seller is enabled
* Tweak: Display the green check icon for orders where stocks have been processed/reduced
* Tweak: The new "Revert Stock Changes" setting option replaces the wpla_revert_stock_reduction filter hook
* Tweak: Make the delete keyword for removing min/max prices case-insensitive
* Fixed: Tax order items do not have the rate_percent value
* Fixed: Date Paid not getting set when using a custom order status
* Fixed: Set the Date Paid value when creating orders with a processing status
* Fixed: Only deduct the taxes from the line totals if wc_prices_include_tax() is true
* Fixed: Call to undefined method Exception::getErrorCode()
* Fixed: insertMissingVariations() only checked partial variations in a multi-page variations list
* Dev: Added wpla_deduct_discounts_from_line_total filter hook

= 1.5.4 – 2020-05-23 =
* Added: New setting option to allow listing draft products
* Tweak: You can now search for the Amazon order ID on the WooCommerce Orders page
* Tweak: Added department_nameX columns to the list of allowed parent columns
* Fixed: Make sure the Germane Market plugin is installed when trying to generate an invoice
* Fixed: MSRP in the repricing tool was not saving the MSRP of simple products correctly
* Fixed: Possible issue importing items where the weight value could not be parsed, resulting in a fatal error
* Fixed: Issue where the WooCommerce iOS app could crash when viewing orders from WP-Lister
* Fixed: SKU comparison not using the wpla_case_sensitive_sku_matching setting
* Fixed: Fetch full description not getting saved to parent product
* Fixed: Automatically matching ASIN uses the first profile ID in the list
* Fixed: WPLA_Product_MetaBox::save_meta_box() ran too early which prevented it from catching other product changes such as SKU updates
* Fixed: Use WC_Product methods when setting the visibility and stock status when importing so WC can properly filter them
* Fixed: Some PHP warnings (Trying to access array offset on value of type bool and others)

= 1.5.3 – 2020-05-06 =
* New: Added support for uploading invoice PDFs using the German Market plugin
* New: Added the option to automatically detect the tax rate ID for Import and Fixed order tax processing options
* Fixed: Processing new orders might fail unless WP-Lister for eBay was installed as well
* Fixed: Allow a value of 0 in listing feeds without reverting back to the profile's value

= 1.5.2 – 2020-05-05 =
* New: Added setting options "Sales Tax Action" and "Sales Tax Rate"
* New: Added setting to instantly submit inventory feeds as soon as they are created
* New: Read the Hide on Amazon setting on active listings and send 0 stocks if it is checked
* Fixed: Remove ASIN from products when using the "Remove Listings from Amazon" action so they do not get automatically matched repeatedly
* Fixed: Cast item dimensions to float to prevent them from being stored as stdClass
* Fixed: Error Call to undefined method Exception::getErrorCode()
* Fixed: Lock table in order to prevent rare race conditions in updating shipping feeds
* Fixed: Undefined index: sku warnings
* Tweak: Allow to search for a feed by its Feed ID / Batch ID
* Tweak: Readded max-width and max-height CSS properties to the thumbnails to prevent them from stretching
* Tweak: Editing of Sale and eBay prices in the Repricing tool is now possible, even if they are initially empty
* Dev: Added the filter wpla_revert_stock_reduction
* Dev: Added filter wpla_order_shipping_taxes

= 1.5.1 – 2020-04-14 =
* New: Added the ability to edit the MSRP from the Repricing tool
* New: Added option to use external repricer (omit price when updating amazon listings) 
* Tweak: Apply selected profile when auto matching listing by ASIN
* Tweak: Prevent double-clicking on the Create Order link which creates duplicate WooCommerce orders
* Fixed: Issue where bookload templates were marked as outdated
* Dev: Added the feed ID in the history message

= 1.5 – 2020-04-03 =
* New: Initial support for VAT invoice uploading with WC Germanized plugin
* New: Added the Amazon Order IDs Storage setting option
* New: Added the Ignore Orders Before setting option
* New: Added setting option to select thumbnail size on Listings page
* Tweak: Enabled thumbnails column on Listings page by default
* Tweak: Improved settings page descriptions and layout
* Fixed: Check and make sure that the estimated arrival dates are available before displaying them
* Fixed: Included listing templates can be updated to the latest versions again
* Fixed: Updated BTG for Shoes US

= 1.4 – 2020-03-26 =
* New: Added setting to select the role of new customers from Amazon orders
* New: Added the valid values for footwear sizes in the variation sizes map dropdown
* New: Improved layout and colors scheme on WP-Lister pages
* Fixed: Regard the 'Status for shipped orders' setting for FBA orders that have been shipped
* Fixed: Use the locale settings to display and save prices properly
* Dev: Ready for WooCommerce 4.0

= 1.3.1 – 2020-03-15 =
* Tweak: Store Amazon Order ID in a private order note instead of the order comments
* Fixed: Check the reduced_stock property before reverting stock reduction
* Fixed: Background CSS change while modal window is open
* Fixed: Cleaned layout of images metabox on edit product page
* Fixed: Broken layout on profile selection message on WooCommerce product page
* Fixed: Various minor layout issues

= 1.3 – 2020-03-01 =
* New: Added a setting option to disable the use of sale prices
* New: Allow setting the Restock Date value on product level and variation level
* Fixed: HTML in custom shortcodes was getting stripped
* Fixed: Import by ASIN was removing newlines from item list
* Fixed: Imported/Queued listings were not showing up in the Listings table
* Fixed: Various warnings when trying to process cancelled orders without address and order items
* Tweak: Show custom text field for recommended_browse_nodes
* Tweak: Added unit_count, unit_count_type and standard_product_id to the allowed fields in parent rows
* Dev: Added wpla_before_update_product_from_item hook

= 1.2.1 – 2020-02-15 =
* Fixed: Warning: Use of undefined constant WPLA_PLUGIN_URL
* Fixed: Amazon price was not displayed in the Quick Edit form
* Fixed: The line count did not always match the actual number of lines in the feed
* Tweak: Exclude draft products from the feed notifications
* Dev: Use wp_kses_post_data filter when parsing HTML input on settings page

= 1.2 – 2020-01-24 =
* New: Added Amazon Price field in the Quick Edit screen
* New: Added support for updating basic listing properties via the REST API
* New: Added buttons to optimize and clear the stocks log table
* Fixed: Use wpla_clean instead of wc_clean to prevent fatal errors when updating WooCommerce
* Fixed: Override line tax calculation to use the gross price
* Fixed: Adjust line totals after getting the correct tax totals from WooCommerce
* Fixed: Deduct the shipping tax from the shipping totals when autodetecting taxes
* Fixed: Let WooCommerce take care of the tax calculation when autodetect mode is selected
* Fixed: Make sure FBA Reports are pulled as scheduled even if update interval is set to External Cron
* Fixed: Added proper checks to array indices to prevent Illegal string offset errors
* Fixed: Fixed error when accessing item as an array
* Tweak: Optimize log tables during the daily maintenance run
* Dev: Increased max log size to 50MB
* Dev: Added 1 day and 3 days options to the setting stock_days_limit
* Dev: Added wpla_order_builder_force_shipping_tax_deduction filter hook

= 1.1 – 2019-12-29 =
* Tweak: Check both afn-listing-exists and afn-fulfillable-quantity prior to processing the report row
* Tweak: Skip draft products from being included in feeds
* Tweak: Skip processing order line item if the quantity is zero 
* Tweak: Check profile quantity when running FeedValidator checks
* Fixed: Stocks getting reduced when creating orders even with Sync Sales off
* Fixed: Importing variable products could show as out of stock
* Fixed: Check for handling-time from the profile when getting the leadtime-to-ship value
* Fixed: Check Now button in the Reports page
* Dev: Catch exceptions to prevent throwing fatal errors on empty responses from the API
* Dev: Added the $custom_shortcode as a parameter when processing custom shortcodes

= 1.0.5 =
* Fixed: Custom Amazon product description was missing HTML tags when a product was saved
* Fixed: Remove from Amazon bulk action was not shown

= 1.0.4 =
* Fixed: Improved security and compatibility by following best practice guidelines 
* Fixed: Automatic tax detection when tax calculation is based on billing address in WooCommerce
* Tweak: Check the afn-listing-exists value before processing the FBA listing
* Tweak: Set the data_paid value depending on the order's new status
* Tweak: Removed legacy support for PHP 5.2

= 1.0.3 =
* Fixed: Reverted change from 1.0.1 to recalculate totals based on the line items as it could lead to wrong totals
* Dev: Added the $order parameter to the wpla_skip_quantity_sync filter hook

= 1.0.2 =
* Fixed: Fixed error "Call to a member function is_type() on boolean"
* Fixed: Warnings about accessing undefined property
* Fixed: Some taxes getting dropped after recalculating totals
* Tweak: Added the department_name column back to the list of allowed parent columns
* Tweak: Improved Tutorial page
* Dev: Added the wpla_allowed_parent_var_columns filter

= 1.0.1 =
* Added: Option for stats to count FBA overrides on product level (disabled by default for better performance)
* Fixed: Listings were not getting removed (reverting to Submitted status) if they had matching SKUs in other accounts/markets
* Fixed: Possible issue with special characters in the listing title
* Fixed: Possible issue where size_map or color_map column would not be populated correctly when creating feeds
* Fixed: PHP warning "undefined offset"
* Tweak: Delay updating pending feeds when bulk-listing products
* Tweak: Recalculate totals for WC orders based on the line items fetched from Amazon (since Amazon now collects taxes)
* Tweak: Exclude certain meta from being copied over to duplicated variations
* Tweak: Allow to manually process FBA Managed Inventory reports
* Tweak: Regard the Case Sensitive SKU setting when processing FBA Manage Inventory Report
* Tweak: Updated the list of allowed parent columns
* Tweak: Changed the text domain to wp-lister-for-amazon
* Tweak: Retry requests returning HTTP code 429 the same way as for code 503 (RequestThrottled)
* Dev: Added the wpla_process_inventory_report_listing filter

= 1.0 =
* Added: Search specific listings using comma-separated ASINs (or post IDs) prefixed with #
* Added: Support for the FBA Manage Inventory report to be able to use the afn-fulfillable-quantity data from Amazon
* Added: Setting to turn on case-sensitive SKU matching 
* Added: Support for 3rd party plugins: Admin Columns Pro, ATUM and PW Bulk Edit 
* Added: Added AMAZON_JP to the available Fulfillment Center IDs for FBA 
* Fixed: FBA/Non-FBA views in the Listings table did not reflect the FBA Overwrites set on the product level 
* Fixed: Variation titles were getting overwritten when new variations to a product were added 
* Fixed: Error messages which were being hidden by the wc-admin plugin should be visible again
* Fixed: Shipping total when discounts are used 
* Fixed: Description fetching and included features/bullets when pulling full description from Amazon 
* Fixed: Errors in the Orders page when displaying orders under a deleted account 
* Fixed: Undefined variable warnings in WPLA_OrderBuilder and Woo_ProductWrapper classes 
* Fixed: Deprecated WC_Product::increase_stock() call 
* Fixed: Various warnings and notices 
* Tweak: Skip looking up listings to update after submitting an Order Fulfillment feed 
* Tweak: Added manufacturer_minimum_age to the allowed parent columns 
* Tweak: The process to handle product stock updates should no longer listen to checkout stock changes 
* Tweak: Enhanced the CustomizedInfo parser 
* Dev: Added wpla_inventory_before_change action hook 
* Dev: Added wpla_processed_attribute_shortcode filter hook 

= 0.9.25 =
* Added: New setting to "Convert newline to BR HTML tags" in content fields
* Added: Show flags on Products page to indicate the marketplace(s) the product is listed on 
* Added: Handling time field for variations
* Fixed: Issue with CustomizationInfo parser 
* Fixed: B2B price not getting included in the feeds 
* Fixed: Some rare cases where processing reports could fail or throw errors 
* Fixed: Non-fulfillable error showing in cart with no FBA items added 
* Fixed: Store the correct value in reduced_stock item meta field 
* Fixed: Character encoding in some feeds 
* Fixed: Limit the address to 60 characters to prevent an InvalidRequest error when getting shipping rates from Amazon 
* Tweak: Renamed the "Record Promo Discounts" setting to "Apply Amazon Discounts" 
* Tweak: Mark order as shipped if there are no errors and warnings 
* Tweak: Handle case-sensitive SKUs when updating quantity from an FBA Inventory report 
* Tweak: Update the title when marking a listing as changed 
* Dev: Added wpla_updated_product_from_item action hook 
* Dev: Added wpla_order_builder_new_order_status and wpla_amazon_size_values filter hooks 
* Dev: Added more parameters to the wpla_added_order_item_meta hook 

= 0.9.24 =
* Added: B2B price fields on edit product page 
* Fixed: PHP Error "Class JobsModel not found" 
* Fixed: PHP Notice "Undefined variable: tpl_title" 
* Dev: Added wpla_added_order_item_meta action hook 

= 0.9.23 =
* Added: Indicate Amazon Business orders on Orders page 
* Tweak: Increased the timeout to 300 seconds when uploading custom feed templates to be converted 
* Tweak: Apply profile in batches to use less ressources when updating the profile on a large number of items 
* Tweak: Enable update_delete column in profile editor if expert mode is enabled 
* Tweak: Add the _reduced_stock item meta to tell WooCommerce to restock the item when its refunded 
* Tweak: Improved wording on import page, clarify difference between importing by ASIN and from a report 
* Fixed: Gift Wrap line items always getting added to orders 
* Fixed: Possible issues with CustomizedBuyerInfo 3.0 data 
* Fixed: PHP notice 'Trying to get property 'title' of non-object' when creating feeds without a feed template 

= 0.9.22 =
* Added: Support for WooCommerce Additional Variation Images Gallery plugin 
* Added: Support for CustomizedBuyerInfo property data version 3.0 
* Added: Button to "Clean listings table and delete from Amazon" next to existing "Clean listings table" button 
* Tweak: Decode HTML entities when processing attributes 
* Tweak: Delete update_plugins transient when "Force update check" button is clicked 
* Fixed: ConsumerEletronics IT feed template 
* Fixed: Warning "sizeof (): parameter must be an array or object that implements countable" 
* Fixed: Warnings in the Storefront Powerpack plugin 
* Fixed: Warning about missing status index 

= 0.9.21 =
* Fixed: Feeds using different feed templates could possibly be merged 
* Fixed: FBA only mode could stay enabled while FBA itself was disabled 
* Fixed: Check license status button redirecting to general setting page 
* Fixed: Log auto deletion would not work on some cases 
* Tweak: Do not show invalid EAN warning if 13 digit EAN is prefixed with a zero 
* Tweak: Added supplier_declared_dg_hz_regulation1-5 to the list of allowed parent columns 
* Dev: Added wpla_order_shipping_location filter hook 

= 0.9.20 =
* Added: Full support for uploading custom feed templates generated on seller central
* Added: Reminder message if you are not using MWS Auth Token for authentication yet 
* Added: Reminder message about updating outdated feed templates on Categories and Profiles pages 
* Tweak: Improved profile editor for InventoryLoader template (explain codes like 1 = ASIN) 
* Tweak: Skip the first image in the gallery if using custom amazon images 
* Tweak: Improved representation of gift wrap options in generated WooCommerce order 
* Tweak: Store the BuyerCustomizedInfo for orders with customized listings 
* Tweak: Store the _order_tax order meta for backwards compatibility 
* Fixed: Feed generation on WooCommerce 2.6 
* Fixed: Non-numeric error message in PHP 7.1+ 
* Dev: Added the filter hook wpla_profile_field_force_text_input 
* Dev: Added wpla_get_listings_where function for 3rd-party devs 

= 0.9.19 =
* Re-added support for Amazon AU 
* Fixed layout issue on add account form 
* Ignore the fulfillment_latency value if listing is FBA 
* Normalize the MSRP prices before saving to the DB 
* Make sure brand_name is a free text field in the profile editor 
* Regard feed currency format setting when building InventoryLoader feeds 
* Fixed possible issue when FBA Override setting was not working correctly when set to FBM 

= 0.9.18 =
* Added the Length attribute to the Merge Variation Attributes dropdown 
* Fixed issues importing some new feed templates with very long signatures 
* Show warning if template signature is missing on category settings page and feed details page 
* Display feed template version on Profiles overview page to see which profiles use outdated templates 
* Improved field sort order in profile editor 
* Set parent variable's status to online before skipping when generating LiLo feeds 
* Modified the regex pattern in the _extractHttpStatusCode methods to properly parse HTTP/2 responses 
* Fixed the retrieval of the shipping country and state values fixCountryStates() 

= 0.9.17 =
* Added support for InventoryLoader feed template as an alternative to ListingLoader feeds for US, UK, CA and DE 
* Added missing category and signature to new custom/category listing feeds (template refresh required) 
* Disallow direct editing of prices on repricer page for items with min/max prices if automatic repricing is enabled, because manual price changes would be overwritten by repricer 

= 0.9.16 =
* Added support for installing custom feed templates for the UK, FR, DE, ES and IT marketplaces 
* Added billing and shipping address index to make the orders searchable 
* Added the Import WPLE Product IDs tool in Tools > Developer 
* Added action hook 'wpla_order_submitted_to_fba' 
* Updated instructions to connect an account to reflect recent changes 
* Removed support for new AU sellers for now (will return shortly) 
* Include the batteries_required property for parent variations 
* Automatically convert variation theme MaterialColor into Color-Material 
* Fixed sale-end-date being set to 2019-01-01 when no end sale date is specified 
* Fixed display_errors in WPLA being enabled by default when WPLA_DEBUG is set 
* Fixed warnings in the FeedTemplateHelper class 
* Fixed leadtime-to-ship column not processing shortcodes 

= 0.9.15 =
* Fixed fatal PHP error that some users were seeing 

= 0.9.14 =
* Finally: We are able to support new US sellers again! 
* Added support for uploading custom feed templates in Settings > Categories 
* Added missing feed template for AutoAccessory DE category (Auto & Motorrad) 
* Fixed empty leadtime-to-ship column in Price&Quantity feeds for items using a category feed 
* Fixed the date_created date when creating WC orders from Amazon 
* Fixed "Failed to parse valid HTTP response" error on some sites 
* Fixed the Delete Listing link in the Duplicate Listings table 
* Force error logging when WPLA_DEBUG is enabled 
* Added wpla_end_listing action hook 
* Added wpla_complete_sale_on_amazon action hook 
* Added wpla_prepare_product_without_profile filter hook 

= 0.9.13 =
* Added support for new feed templates of type "fptcustom" (like Health UK, which uses "fptcustom" type now instead of "health") 
* Disabled check for required fields in profile editor, because there are too many fields incorrectly tagged as required in the new feed templates (for example 'lens_type' in Health UK) 
* Added message to tooltip description for fulfillment-center-id (FCID) profile field 
* Make ingredients columns available for parent variations 
* Use new ListingLoader feed template (version 2014.0703) by default on new installations 
* Removed the completed order status from orderCanBeFulfilledViaFBA so WPLA doesn't send completed orders to Amazon 

= 0.9.12 =
* Fixed feeds being stuck as pending

= 0.9.11 =
* Fixed issues authenticating via SellerID and MWS Auth Token for EU sellers 
* Small improvements on the edit accounts page 
* Updated MWS client libraries to latest versions 

= 0.9.10 =
* Added support for authenticating via SellerID and MWS Auth Token when adding account (EU only) 
* Added Repricing Shipping option to allow automatic repricer to take shhipping fees into account 
* Added setting to use a single search term field to replace the 5 keyword fields on the edit product page 
* Fixed missing min/max prices for new listings 

= 0.9.9 =
* Added support for MWS Auth Token in account settings 
* Added option to manually switch a specific SKU to FBA or FBM on the product/variation level 
* Added FBA stock sync option to allow syncing back FBA stock levels into WooCommerce without having to enable FBA only mode 
* When syncing FBA stock levels back to WC, skip products which are manually set to FBM (via fba_overwrite) 
* When importing taxes from Amazon, deduct the tax amount from the line totals 
* Notify WPLE when updating stock levels from FBA Inventory report 
* Validate the billing email before storing it to avoid the WC error 
* Skip parent variables in ListingLoader feeds 
* Increased the log file size to 25MB 
* More timezone adjustments 
* Fixed line tax not getting detected properly 
* Fixed issue where listings are being marked as changed after importing orders from Amazon 
* Added PHP Error Handling setting 
* Added "LiLo version" option on developer settings page 
* Removed item_weight and other columns from LiLo 2014.0703 template to avoid Error 8058 due to missing units (like item_weight_unit_of_measure) 
* Removed item_weight/product_weight shortcode to be filled in automatically in new ListingLoader profiles 
* Added wpla_custom_tracking_number and wpla_custom_tracking_provider filters 
* Added wpla_custom_shipping_date and wpla_custom_shipping_date filters 
* Added wpla_parse_order_column_value filter 
* Added wpla_profile_template_data filter 

= 0.9.8.1 =
* Added support for latest ListingLoader versions for Amazon US, CA, UK, DE, FR, IT, ES and AU 
* Automatically set default values for batteries_required and supplier_declared_dg_hz_regulation columns for FBA items in ListingLoader feeds 
* Keep internal and external ListingLoader feeds separate (Offer/LiLo) to avoid merging feeds with different columns 
* Updated internal ListingLoader template to latest version 
* Tweak: If FBA only mode is enabled, force all items to FBA enabled in all product feeds 
* Show dates in the local timezone defined in WordPress settings
* Fixed support for Aelia Currency Converter 
* Listen to events from wc_product_update_stock() to be able to mark listings as changed 
* Use WC_Order::set_status() then WC_Order::save() to allow order status overrides 
* Changed the size of the post_id and parent_id columns in amazon_listings to match the size of wp_posts::ID table column 
* Added wpla_fulfillment_preview_address and wpla_fulfillment_preview_products filters hooks 

= 0.9.8 =
* Added FBA support for Amazon AU 
* Added wpla_get_stock filter hook 
* Added a check to make sure only items from the same account can get purchased using FBA 
* For products linked to multiple listings, attempt to use the one that's linked to the default account when processing FBA submissions 
* Store the item's tax class regardless of the vat_enabled status 
* Fixed issue where only the first 4 valid values were imported from a feed template 
* Fixed TotalShippingFee in getFulfillmentPreview so it doesn't multiply the fee with the item quantity 
* Fixed REST API listener to add backwards compatibility with the v1 branch 
* Fixed Check License Activation link nonce 

= 0.9.7.9 =
* Added options to map WooCommerce colors/sizes to standard Amazon colors/sizes to automatically populate the color_map and size_map columns 
* Added support for searching listings using UPC/EAN 
* Added the wpla_product_matches_query_fields filter hook 
* Show warning on edit account page if secret key length is not 40 chars 
* Remove all spaces from secret key when creating account 
* Fixed process links on Reports page 
* Fixed version details link on Plugins page 

= 0.9.7.8 =
* Fixed thousands separator on rounded prices (1,000 would show as 1) 
* Fixed the Create Order link not getting processed 
* Added part_number to the allowed columns for parent listings 
* Disabled the product counts by default to avoid rare performance issues on slow servers 

= 0.9.7.7 =
* Added MusicalInstruments feed template for Amazon FR, DE, CA, IT, ES 
* Added a setting option to enable the product counts on "On Amazon" and "Not on Amazon" views 
* Disabled the product counts by default to avoid rare performance issues on slow servers 
* Use number_format() instead of round() to fix rare precision issue 

= 0.9.7.6 =
* Improved feed generation performance by caching data for variable products 
* Improved security against CSRF (cross site request forgery) 
* Added wpla_order_builder_line_item filter 
* Added wpla_set_tracking_number_for_order and wpla_set_tracking_service_for_order filter hooks 
* Minor CSS fixes 

= 0.9.7.5 =
* Added product counts to the On Amazon and Not on Amazon filter options 
* Added option to enable offer images (aka condition images) in all ListingLoader feeds (Pro) 
* Added experimental option to load B2B templates (only Amazon UK and DE for now) (Pro) 
* Improved edit account page and added information about brand registry option 
* Tweaked "Feed currency format" option, which is now only active on EU sites using Euro (DE, FR IT, ES) and enabled by default 
* Reduce the line total when a discount is present to adjust the line tax accordingly 
* Store the total_tax item meta to fix the shipping display in the PDF Invoices and Packing Slips plugin 
* Fixed shipping tax bug where compound rates are being ignored 
* Fixed multiple issues related to importing products und updating imported products
* Fixed wrong quantity in Listings table if profile quantity override option was used 
* Fixed profile selection not reflecting the active profile on edit product page for variable products 
* Fixed and improved warning when user attempts to change SKU on child variations (and show no warning if SKU was empty) 
* Fixed issue where changing SKU on child variations would lead to duplicate variation listings with identical SKUs 

= 0.9.7.4 =
* Added feed templates for Amazon.es (Office, Luggage, ConsumerElectronics which replaces CE) 
* Show warning on Profiles page if template is meant for a different marketplace than a profile's account is linked to 
* Improved tooltip for repricing options (update interval, explain limit of 2400 items per hour) 
* Fixed issue when importing shipping taxes from Amazon 
* Fixed minor CSS issues on edit product page 
* Fixed possble PHP notices 
* Pass the account ID in WPLA_ListingsModel::processBuyBoxPricingResult() to fix possible error introduced in 0.9.7.3 
* Pass data parameter to the wpla_added_product_from_listing action hook 

= 0.9.7.3 =
* Added support for amazon.com.au (Australia) 
* Added missing PetSupplies and Luggage feed templates for Amazon DE (Haustierbedarf / Koffer) 
* Added support for the new WooCommerce Products Importer 
* Fixed issue where tax was doubled when imported from Amazon 
* Fixed issue where bulk actions on the SKU Generator would not work 
* Fixed issue getting the proper name of the shipping provider in WC Shipment Tracking v1.6.6+ 
* Fixed issue updating buy_box and lowest_price in the listings when using multiple accounts 
* Listings Table: Show custom profile price if set 
* Order creation: Set the total and save the order using WC_Order::set_total() and WC_Order::save() to trigger the currency converter action 
* Improved statename detection in created orders (remove accents from statename) 
* Make sure variation_theme column is empty in feeds for simple products 
* Trim excess whitespace before using nl2br() 
* Skip trashed listings when marking items as modified 
* Use new WPLA_ProductWrapper::getProductTitle() method in the WPLA_SkuGenTable class to display correct product titles 
* Tweaked Custom Product Description field: use wp_editor() instead of woocommerce_wp_textarea_input() to avoid escaping all HTML tags 

= 0.9.7.2 =
* Added option to download and upload listing profiles (backup and restore) 
* Added partial support to qTranslate to translate the title, description, bullet points and keywords based on the account's site code 
* Added Sequential Order Numbers support for FBA orders 
* Added setting to ignore product images and not add them to the feeds 
* Added wpla_build_feed action hook 
* Added wpla_account_locale filter hook 
* Added new amazon.com.br marketplace (experimental, not officially supported yet) 
* Allow profile fields to override the product price under certain conditions 
* Record shipping taxes that are outside the vat_rates array 
* Use WC_Product::get_title() instead of $product->post->post_title for best practice 
* Use wc_update_product_stock() over WC_Product::reduce_stock() which is now deprecated 
* Unset leadtime-to-ship feed column if quantity is empty to avoid errors in feeds 

= 0.9.7.1 =
* Fixed issue with sale price and/or sale start and end dates being empty in feed 
* Fixed issue where listings were not marked as changed when updated via WC REST API in WC 3.x 
* Fixed "WC_Product::get_total_stock is deprecated" warning 

= 0.9.7 =
* Reorganized the Tax options on the settings page 
* Added option to import sales tax data from Amazon orders 
* Added 'Create selected orders in WooCommerce' bulk action on orders page 
* Added wpla_custom_values filter hook to allow custom code snnippets to create custom product properties and attributes 
* Load column values using variation IDs if the current product is a variation 
* Load the variations using the product's parent ID (WC3+) 
* Strip all HTML tags from the product_title 
* Allow more valid parent columns for the Books feed template 
* Marked outdated US feed templates as deprecated (Lighting, Outdoors, removed TiresAndWheels from AutoAccessory template) 
* Fixed bug where the %%% placeholder is not getting processed when used at the start of the title 
* Fixed possible fatal error in the WC_Order class when 3rd party plugins update an order's status 
* Fixed issue where the shipping line was not showing taxes 
* Fixed issue where custom profile sale dates were replaced by default dates 
* Fixed issue with order creation date by replacing the MEST timezone with CEST so PHP can parse it 

= 0.9.6.41 =
* updated process of linking an Amazon seller account to WP-Lister to refrect recent changes on Seller Central
* load the product level feed columns for the parent product when dealing with variable products
* fixed an issue when pulling attached gallery images
* fixed an issue with variation product data on WooCommerc 2.x
* use native WC3.x methods to update the order date after updating its status to prevent the status update from resetting
* added filter hook wpla_order_can_be_fulfilled_via_fba

= 0.9.6.40 =
* improved compatibility with WooCommerce 3.x
* added the option to disable on-hold order emails
* store the currency first when creating orders to make the currency coverter work again
* do not touch the _price product meta when updating products from Amazon to make sale prices function properly
* changed the data column in the amazon_reports column to longblob
* fixed undefined variable error in FeedsPage

= 0.9.6.39 =
* improved compatibility with WooCommerce 3.x 
* fixed child variations not being able to access data in WC3.x 
* added Conditional Order Item Updates setting option to improve order fetching performance in some cases 
* added the wpla_order_post_data filter to allow 3rd-party code to hook into the process of creating orders in WooCommerce 

= 0.9.6.38 =
* improved compatibility with WooCommerce 3.x 
* update existing WC Order's status when importing orders from Amazon 
* added the ability to store multiple tax rates for a single line item 
* skip loading the ProductMatcher JS in the frontend/toolbar if the current user does not have the manage_amazon_listings capability 
* hide the Filter Orders notice on Lite users since they do not have access to that functionality 
* store the item description from the import CSV 
* added Custom feed template for Amazon UK 

= 0.9.6.37 =
* added warnings to the listings page and edit product page when SKUs that start with 0 are found 
* added support for WC Shipment Tracking v1.6.6 
* use the parent ID in variable products for the Edit Product links 
* make sure jQueryFileTree is loaded on the Edit Product screen 
* fixed selected shipping service on edit order page 
* do not auto-submit FBA orders if FBA is not enabled 
* removed “skipped node without parent” warnings during category update 
* improved WooCommerce 3.0 compatibility 
* tested on WordPress 4.8 

= 0.9.6.36 =
* added Software & Video Games feed template for amazon.ca 
* added 'wpla_shipping_method_label' filter hook to allow modification to the shipping labels 
* added 'wpla_added_product_from_listing' action hook 
* removed jQueryFileTree connectors due to a potential security issue 
* store the shipping tax line inside a 'total' index to make shipping taxes appear in the order items list 
* fixed order VAT rates not getting stored properly 
* fixed possible PHP warning on WC Email Settings page 
* fixed product gallery for variable products on WooCommerce 3.0 

= 0.9.6.35 =
* added “Material” to the list of destination attributes for Merge Variation Attributes option 
* fixed possible fatal error during feed generation (which could break cron job execution)
* fixed possible "Undefined index" warning (PHP Notice) when updating stock levels

= 0.9.6.34 =
* fixed issue where disabled emails are still sent on WooCommerce 3.0 
* fixed issue where Amazon metadata was not copied when duplicating a product on WooCommerce 3.0 
* fixed issue where quantity was only updated when an inventory report was processed twice 
* added option to include the shipment time when submitting an Order Fulfillment Feed 
* added tooltip to listing title to indicate whether an item was imported, matched or listed from WooCommerce 
* pull condition_type and condition_note from variations in variable products if set 

= 0.9.6.33 =
* added FoodAndBeverages feed template for amazon.it (Alimentari e cura della casa) 
* added 'wpla_skip_quantity_sync' filter to skip quantity sync via 3rd-party code 
* added 'wpla_product_matches_request_query' filter to allow 3rd-party code to alter the query before sending to Amazon 
* allow 0 to be saved in the Profile edit screen 
* allow 0 profile quantity to override the WC product's quantity 
* fixed autodetect taxes not working when no tax rate ID is set 
* fixed On Amazon filter being reset when using search form, direct pagination or other filter options on Products page 
* fixed fatal error when plugin updates check doesn't return a WP_Error object and an HTTP code other than 200 
* fixed “Sold Out” listing status if stock level is below zero 

= 0.9.6.32 =
* added LawnAndGarden (Jardin) feed template for amazon.fr 
* check for missing database tables and show warning on settings pages 
* updated tooltip for “Process daily inventory report” option (include warning about outdated data in reports) 
* remove amazon_stock_log table when uninstalling the plugin 
* minor layout fixes and improvements 

= 0.9.6.31 =
* added feed template for Collectible Coins US 
* show the Prime icon for orders using Amazon Prime 
* exclude changed and submitted items from having their stocks updated from the reports 
* fixed importing products without ASIN (where the Merchant report only contains SKU and UPC/EAN) 
* fixed issue where VAT would be incorrectly added to created WooCommerce orders if prices are entered without taxes 
* fixed profile editor not loading on PHP7.1 
* fixed importing book specific metadata from amazon.it 
* make sure the daily cron schedule is executed when wp-cron is broken and an external cron job is used (trigger daily cron by external cron if not executed for 36 hours) 
* improved daily schedule: show last run on tools and dev settings page, allow manual execution more often than once in 24 hours, log table cleaning results to log file 
* prevent accidentally removing all listings without a parent from Amazon by running the “Remove from Amazon” bulk action without any products selected 
* prevent resetting the shipment date to today when importing old shipped orders (like after restoring a backup) with both options to “Create Orders” in WooCommerce and to “Mark as shipped on Amazon” enabled 
* auto-detect staging site (if domain contains staging or wpstagecoach) and omit requesting daily reports on staging site 
* only update other listings with the same post/parent ID if there are more than one account set up 
* force all date/time functions to use UTC regardless, of local timezone (use gmdate instead of date, use ‘UTC’ suffix on strtotime) 
* show warning on accounts page if multiple accounts use the same Merchant ID and “Filter orders” option is disabled 
* importing orders: if "Filter orders" option is enabled, make sure an existing order is assigned to the right account_id 
* improved performance when importing orders by not updating pending feeds when updating other listings with the same post/parent ID 
* if invalid data is found show warning and offer to assign all found items to the default account 
* fixed fatal error in SDK class MarketplaceWebService/Model/ResponseHeaderMetadata.php (Redefinition of parameter $quotaMax) 
* improved log viewer 

= 0.9.6.30 =
* added MusicLoader feed template for Amazon UK 
* added support for defining a custom position for variation attribute values in listing title by using the placeholder ‘%%%’ 
* added the filter 'wpla_disable_fba_to_wc_stock_sync' to allow 3rd-party code to disable fba stock sync 
* allow orders to Canada (CA) to be fulfilled from FBA US (AMAZON_NA) 
* fixed a rare issue where the fulfillment_latency column was not being processed correctly 
* fixed the 'Select from Amazon' button to not working properly on Edit Product screen
* fixed an undefined variable warning when calculating FBA shipping (on checkout)

= 0.9.6.29 =
* fixed execution of background tasks (cron job) 

= 0.9.6.28 =
* added missing feed templates for amazon.it 
* added missing BookLoader feed template for Amazon IT, ES, DE, FR and CA 
* added staging site option on developer settings page 
* added support for additional variation images added by WooThumbs plugin 
* added 'wpla_repriced_products' hook that runs after products have been repriced 
* improved tooltip description for custom Amazon price field 
* listen for product updates made via the new WC REST API in WC 2.6+ 
* broadcast stock updates using wpla_product_has_changed to update other listings with the same post and parent ID 
* use MWS Orders API version 2013-09-01 to fetch orders and order line items 
* fixed saving settings on the Advanced Settings page on multisite installs 

= 0.9.6.27 =
* added In WooCommerce / Not in WooCommerce filter views on Orders page 
* added check for ASINs containing leading or trailing whitespace - and prompt user to fix it by clicking a button 
* added Shoes feed template for amazon.de (Schuhe & Handtaschen) - and fixed internal ID for Kitchen template 
* added various missing feed templates for Amazon ES 
* added feed templates for Amazon Japan 
* allow custom quantity in listing profile to overwrite WooCommerce quantity 
* allow orders to Puerto Rico (PR) to be fulfilled from FBA US 
* minimize delay when syncing sales from eBay to Amazon and allow an external cron job to run every minute 
* include only shipping date (not time) in ship-date column in Order Fulfillment Feed 
* fetch more orders at a time by using ListOrdersByNextToken request to process multi-page API results (increases maximum number of orders from 100 to 600 before throttling kicks in) 
* if product has no feature image assigned use first gallery image instead 
* switched recommended web cron service from CronBlast to EasyCron 
* improved processing of browse tree guides 
* store the tracking service and number when saving orders so they'll get submitted when an order gets marked as complete 
* store the currency before storing the order total to allow currency switcher to work when saving order totals 
* fixed issue where FBA items would not automatically fall back to seller fulfilled unless FBA Inventory Report is processed manually 
* fixed order date on WooCommerce 2.6 
* fixed possible PHP Notice "Undefined offset..." for invalid CSV data 
* fixed issue where stock levels on Listings page would not be reverted when an order was cancelled 

= 0.9.6.26 =
* added Eyewear feed template for Amazon UK 
* added option to store Amazon promotional discounts as WooCommerce order discounts 
* added an option to use the Amazon order number when displaying WooCommerce orders 
* fixed possible division by 0 warnings when creating orders in WooCommerce 
* fixed possible fatal error in wp-admin when Shipping Options are enabled 
* fixed possible issue where main product image was sent as variation image 
* disable the shipping options check when running wp-cron to prevent fatal error 
* improved storing stock log records 

= 0.9.6.25 =
* added options to set add-on FBA shipping fees 
* added Luggage feed template for Amazon UK 
* added Entertainment Collectibles browse tree guide for Amazon US 
* added button to repair crashed MySQL tables on developer tools page 
* fixed issue when importing SKUs with an apostrophe 

= 0.9.6.24 =
* fixed product prices in created WooCommerce orders for multiple units of the same product if auto detect tax rate option is disabled 
* fixed item count on Orders page for orders containing multiple units of the same product 

= 0.9.6.23 =
* added option to list/prepare a product and switch listing profile from the Edit Product screen's sidebar 
* added option to "Skip orders for foreign items" when creating orders in WooCommerce 
* added Toys and Baby feed templates for amazon.it (Giochi e giocattoli / Prima infanzia) 
* added support for Amazon Payments Advanced plugin 
* added admin-ajax.php action "wpla_request_inventory_report" to request Merchant Inventory Report via external cron job 
* added class wc_input_price to all price fields on edit product page to enable WooCommerce inline price validation 
* added option to enable/disable automatic tax calculation when creating orders in WooCommerce 
* fixed incorrect order date when creating orders in WooCommerce 2.6 
* fixed issue adding new variations to existing listings if more than one amazon account are set up 
* fixed PHP warning on WC2.6: Declaration of WPLA_Shipping_Method::calculate_shipping() should be compatible with WC_Shipping_Method::calculate_shipping() 
* fixed issue where VAT would be incorrectly added to created WooCommerce orders if prices are entered without taxes and the site has a tax rate set up for the shop's base address 
* fixed issue where customers without a default shipping address (e.g. guests) could not calculating the shipping costs from the cart page 
* fixed wp_remote_* calls which will be returning objects in WP 4.6 
* force merchant_shipping_group_name template field to be an optional text field (fixes issue with Clothing UK feed template where this field is incorrectly marked as required) 
* improved 'On Amazon' and 'Not on Amazon' product filters for large sites 
* improved stock logger - include information about class and method which triggered stock change 
* store optional gift message as order line meta field when creating order in WooCommerce 

= 0.9.6.22 =
* added support WooCommerce Additional Variation Images plugin when listing items on Amazon 
* fixed line totals and VAT amounts when creating WooCommerce orders for more than 1 purchased unit 

= 0.9.6.21 =
* added option to enable FBA Shipping Options on WooCommerce checkout page 
* calculate taxes based on the product's tax class and shipping address when creating orders in WooCommerce 
* improved tax information in created WooCommerce orders if prices are entered without tax 
* added editable eBay Price column to repricing tool 
* added "Remove from Amazon" bulk action on Products page 
* import "Important Information" section (with legal info) when using the "Fetch Full Description" bulk action 
* fixed pagination links loosing search box query on repricing page 
* fixed issue where eBay listing was not updated when variable product was purchased on Amazon 

= 0.9.6.20 =
* added AutoAccessory feed template for amazon.it 
* added MSRP column and discount percentages on repricing tool page 
* added experimental support for handling tracking details stored by WooForce Shipment Tracking plugin 
* added check for Solaris/SunOS - show warning message if running on Solaris 
* added FBA Inventory Health Report to list of reports which are processed automatically 
* allow sort by stock level on repricing page 
* skip parent variations when creating Price&Quantity feed 

= 0.9.6.19 =
* added support for processing FBA Inventory Health reports and show inventory age details in repricing tool 
* added support for custom order numbers when submitting MCF orders to be fulfilled via FBA 
* added support for WooCommerce Sequential Order Numbers Pro 1.7.0+ 
* added option to subtract the quantity sent to Amazon by the value entered as "Out Of Stock Threshold" in WooCommerce 
* added option to skip out of stock items when fetching lowest price data from Amazon 
* added option to display item condition and notes in the product page 
* added separate BuyBox filter on repricing page and changed lowest price filter to ignore BuyBox flag 
* added FBA inventory age filter on repricing page 
* added stock log tools page 
* added FoodAndBeverages feed template for amazon.fr 
* added Eyewear feed template for amazon.de 
* improved support for WooCommerce Product CSV Import Suite (fix issue where updated listings were not marked as changed) 
* improved processing FBA Inventory Reports: mark item as changed if FBA fallback is enabled and Fulfillment Center ID has changed 
* improved repricing algorithm - set ExcludeMe parameter for GetLowestOfferListingsForASIN request to make sure lowest offer data shows only prices from competitors, and enable repricing undercut for competitors prices 
* improved performance by caching account and marketplace data 
* improved displaying listing quality warnings in listings table 
* improved creating orders: convert country state names to ISO code (New South Wales -> NSW) (requires WC2.3+) 
* show FNSKU on listing page for FBA items 
* allow held orders to be submitted again to Amazon (submission status "hold") 
* update WooCommerce order status when Amazon order status has changed (Unshipped to Shipped) 
* fixed issue where product variations with missing parent products (corrupted database) would break feed generation process - show warning on Feeds page if this is detected 
* fixed search box on orders page 
* fixed profile shortcodes being ignored for price and sale price 
* fixed "not lowest price" filter on repricing tool showing items with no lowest price at all (no competitor and no buy box) 
* fixed blank details page if WooCommerce Product Reviews Pro plugin is active 
* fixed fetching feed processing results when there are more than 100 submitted/pending feeds 
* fixed error handling when submitting MCF orders to be fulfilled via FBA 
* fixed Error 99001: A value is required for the "manufacturer" field (for parent variations) 
* fixed Error 8560: Missing Attributes target_audience_keyword (for parent variations, Toys US) 
* fixed rare issue where variation_theme column would be left empty (Error 99006: A value is required in the "variation_theme" field...) 
* database upgrade to version 35 - change feed processing result column to MEDIUMTEXT (wp_amazon_feeds.results) 
* database upgrade to version 36 - increase field size for varchar columns in table wp_amazon_feed_tpl_data (fix incomplete profile data for some templates like Health FR) 

= 0.9.6.18 =
* added option to not use featured image from parent variation for child variations (avoid same swatch image for all child variation, disabled by default) 
* added Kitchen and Office feed template for amazon.de 
* added Clothing (Abbigliamento) feed template for amazon.it 
* added Amazon Logistics shipping provider 
* fixed Entertainment Collectibles feed template 
* remember invalid parent variation problems during import process and show warning message on edit product page 
* fixed fetching reports when there are more than 100 submitted/pending reports 
* fixed possible issue with Map Variation Attributes setting option 
* fixed issue where Main Image URL would show as "Required" when editing a profile 
* fixed empty quantity column issue if fulfillment_center_id is forced empty in profile - and make sure fulfillment_center_id column is set to Optional 

= 0.9.6.17 =
* added option to bulk remove min/max prices from min/max price wizard 
* added support for AMAZON_CA fulfillment center ID (experimental!) 
* improved checking for processed feeds - avoid feeds being stuck as submitted due to agressive caching plugins 
* improved SKU Generator tool: check existing SKUs and skip products where SKU generation would result in duplicates 
* make sure sale price stays within min/max boundaries - prevent Amazon from throwing price alert and deactivating the listing 
* do not use featured image from parent variation for child variations (avoid same swatch image for all childs) 
* renamed Feed ID to Batch ID and improved title on feed details page 
* removed deprecated feed template Miscellaneous (US) 
* fixed custom main_image_url setting on product level being ignored 
* fixed issue updating product details on PHP5.6 with Suhosin patch installed (and suhosin.post.disallow_nul option on) 
* fixed issue where cancelled orders were stuck as "Pending" (since LastUpdateDate apparently stays the same) 
* fixed SKU generator not showing and processing all missing SKUs (check for NULL meta values) 
* fixed missing success indicator and message when preparing items in bulk from Products page (or matching products or applying lowest prices in bulk) 

= 0.9.6.16 =
* trigger stock status notifications when reducing stock level 
* implemented batch mode for FBA inventory check tools 
* improved inventory check memory requirements - disable autoload for temp data (requires WP4.2+) 
* make sure ASINs have no leading or trailing spaces when creating matched listings from product 
* fixed importing listings with identical SKUs from multiple accounts / sites 
* fixed possible SQL error during import: Column 'post_content' cannot be null 
* fixed possible PHP error on edit account page if MWS credentials are incorrect and no marketplaces were found 
* fixed missing categories when processing multiple browse tree guides for the same feed template 
* fixed fatal error when using Min/Max Price Wizard to set prices based on sale price 
* fixed possible fatal error in Woo_ProductBuilder.php on line 1045 
* fixed fatal error: Redefinition of parameter $quotaMax (PHP7) 
* fixed possible fatal error in ListingsModel.php on line 1679 
* fixed narrow tooltips 
* fixed PHP warning on PHP7 

= 0.9.6.15 =
* improved performance on log and orders pages - database version 33 
* improved performance of processing FBA reports 
* improved inventory check tools - implemented batch processing and improved general performance 
* improved address format for in FBA submission feeds - if shipping_company is set, use company name as AddressFieldOne 
* improved error handling on import - if creating product failed (db insert) 
* improved logging: add history record when creating WC order manually 
* fixed search on repricing page 
* fixed support for multiple images in BookLoader feed template 
* fixed wpla_mcf_enabled_order_statuses filter hook not working for automatic FBA submission (only manual) 
* fixed possible fatal error when processing FBA inventory report (Call to a member function set_stock_status() on a non-object) 
* fixed empty log records being created when checking for reports 
* added "Allow direct editing" developer option - hide Edit listing link by default 
* added experimental support for reserving / holding back FBA stock (MCF) - if order status is on-hold set FulfillmentAction to 'Hold' 
* added wpla_run_scheduled_tasks ajax action hook to trigger only the Amazon cron job (equal to wplister_run_scheduled_tasks) 

= 0.9.6.14 =
* fixed gift wrap row being added to WooCommerce orders even if gift wrap option was not selected 
* fixed issue where messages on the settings page would be invisible with certain 3rd party plugins installed 

= 0.9.6.13 =
* added option to leave email address empty when creating orders in WooCommerce 
* added FBA only mode settings option - to force FBA stock levels to be synced to WooCommerce 
* added filter hook wpla_mcf_enabled_order_statuses - allow to control which order statuses are allowed for FBA/MCF 
* added advanced options to make account settings, category settings and repricing tool available in main menu 
* store SKU as order line item meta when creating orders in WooCommerce 
* show warning if OpenSSL is older than 0.9.8o 
* show gift wrap option on orders page and details view 
* fixed creating orders with gift wrap option enabled - add gift wrap as separate shipping row 
* fixed tracking details not being sent to Amazon if orders are completed via ShipStation plugin 
* fixed fetching full listing description from amazon.es 
* fixed imported products not showing on frontend if sort by popularity is enabled (set total_sales meta field) 
* fixed stock status for imported variations 
* fixed issue where tracking details were not sent to eBay if autocomplete sales option was enabled 
* fixed issue where FBA items would not be updated from report if their SKU contains a "&" character 
* improved performance when updating products via CSV import (and show more debug data in log file) 
* improved error handling on storing report content in db (error: Got a packet bigger than 'max_allowed_packet' bytes) 
* improved error handling and logging if set_include_path() is disabled 
* escape HTML special chars in invalid SKU warning (force invisible HTML like soft hyphen / shy to become visible) 
* relabeled "Inventory Sync" option to "Synchronize sales" 

= 0.9.6.12 =
* improved storing taxes in created WooCommerce orders 
* fixed order line item product_id and variation_id for created WooCommerce orders containing variations 
* skip FBA orders from being auto completed on Amazon / Order Fulfillment Feed 
* fixed "The order id ... was not associated with your merchant" error for FBA orders
* repricing tool: restore listing view filters after using min/max price wizard 
* added option to filter order to import by marketplace - prevent duplicate orders if the same account is connected to multiple marketplaces 
* added feed template / category "Sport & Freizeit" on amazon.de 
* added Deutsche Post shipping provider 

= 0.9.6.11 =
* fixed missing variation_theme values for some templates like Jewellery and Clothing 
* fixed Error 99001: A value is required for the "feed_product_type" field (for parent variations) 
* fixed missing bullet points for (parent) variations 
* fixed FBA cron job not running more often than 24 hours 
* fixed duplicate description on imported products - leave product short description empty 
* fixed possible layout issue caused by 3rd party CSS 
* fixed empty external_product_id_type column for amazon.in 
* send SellerId instead of Merchant in SubmitFeed request header (SDK bug) 
* added DPD shipping service on edit order page 

= 0.9.6.10 =
* added experimental support for amazon.in 
* improved order processing for large numbers of orders 
* fixed error: A value is required for the brand_name / item_name field 

= 0.9.6.9 =
* added filter option to show listings with no profile assigned 
* fixed issue where orders were not imported / synced correctly if ListOrderItems requests are throttled 
* fixed issue where some orders were not imported if multiple accounts are used 
* fixed possible issue where lowest prices would not be updated from Amazon 

= 0.9.6.8 =
* fixed issue where variable items would be imported as simple products 
* fixed issue where parent attributes (e.g. brand) were missing for child variations (attribute_brand shortcode) 
* parent variations should only have three columns set: item_sku, parent_child, variation_theme 
* fixed possible php notice on inventory check (tools page) 
* added filter hook wpla_reason_for_not_creating_wc_order - allow other plugins to decide whether an order is created 

= 0.9.6.7 =
* fixed issue where activity indicator could show reports in progress when all reports were already processed 
* improved multiple offers indicator on repricing page - explain possible up-pricing issues in tooltip 
* feed generation: leave external_product_id_type empty if there is no external_product_id (parent variations) 
* skip invalid rows when processing inventory report - prevent inserting empty rows in amazon_listings 
* don't allow processing an inventory report that has localized column headers 
* added filter hook wpla_filter_imported_product_data and wpla_filter_imported_condition_html 

= 0.9.6.6.8 =
* fixed issue where sale dates were sent if sale price was intentionally left blank in listing profile 
* fixed inline price editor for large amounts - remove thousands separator from edit price field 
* fixed no change option in min/max price wizard  

= 0.9.6.6.7 =
* fixed sale start and end date not being set automatically 
* fixed repricing changelog showing integer prices when force decimal comma option was enabled  
* feed generation: leave external_product_id_type empty if there is no external_product_id (parent variations) 

= 0.9.6.6.6 =
* added warning note on import page about sale prices not being imported, but being removed when an imported product is updated 
* fixed issue where sale start and end date would be set for rows without a price (like parent variations in a listing data feed) 

= 0.9.6.6.5 =
* added warning on listing page if listings linked to missing products are found 
* added support for tracking details set by Shipment Tracking and Shipstation plugins (use their tracking number and provider in Order Fulfillment feed) 
* if no sale price is set send regular price with sale end date in the past (the only way to remove previously sent sale prices) 
* fixed stored number of pending feeds when multiple accounts are checked 

= 0.9.6.6.4 =
* include item condition note in imported product description 
* automatically create matched listing for simple products when ASIN is entered manually 
* trigger new Price&Quantity feed when updating min/max prices from WooCommerce (tools page) 
* updating reports checks pending ReportRequestIds only (make sure that each report is processed using the account it was requested by) 
* fixed issue where reports for different marketplaces would return the same results 
* fixed shipping date not being sent as UTC when order is manually marked as shipped 
* fixed importing books with multiple authors 
* added more feed templates for amazon.ca 

= 0.9.6.6.3 =
* added option to filter orders by Amazon account on WooCommerce Orders page 
* added prev/next buttons to import preview and fixed select all checkbox on search results 
* import book specific attributes - like author, publisher, binding and date published 
* extended option to set how often to request FBA Shipment reports to apply to FBA Inventory report as well 
* fixed importing item condition and condition note when report contains special characters 
* fixed possible error updating min/max prices 

= 0.9.6.6.2 =
* profile editor: do not require external_product_id if assigned account has the brand registry option enabled 
* update wp_amazon_listings.account_id when updating / applying listing profile 
* fixed issue where FBA enabled products would be marked as out of stock in WooCommerce if FBA stock is zero but still stock left in WC 
* fixed rare issue saving report processing options on import page 

= 0.9.6.6.1 =
* added option to import variations as simple products 
* fall back to import as simple product if there are no variation attributes on the parent listing (fix importing "variations without attributes") 
* fixed issue importing images for very long listing titles 
* improved error handling during importing process 

= 0.9.6.6 =
* added filter option to hide empty fields in profile editor
* added Industrial & Scientific feed templates for amazon.com
* added support for WooCommerce CSV importer 3.x

= 0.9.6.5.4 =
* added optional field for item condition and condition note on variation level
* added options to specify how long feeds, reports and order data should be kept in the database
* order details page: enter shipping time as local time instead of UTC
* view report: added search box to filter results / limit view to 1000 rows by default
* regard shipping discount when creating orders in WooCommerce (fix shipping total)
* fixed search box on import preview page - returned no results when searching for exact match ASIN or SKU

= 0.9.6.5.3 =
* fixed saving variations via AJAX on WooCommerce 2.4 beta
* show warning on edit product page if variations have no SKU set
* improved SKU mismatch warning on listings page in case the WooCommerce SKU is empty
* edit product: trim spaces from ASINs and UPCs automatically
* when duplicating a profile, jump to edit profile page

= 0.9.6.5.2 =
* shipping feed: make sure carrier-name is not empty if carrier-code is 'Other' (prevent Error 99021)
* edit order page: fixed field for custom service provider name not showing when tracking provider is set to "Other"
* fixed setup warnings not being shown (like missing cURL warning message)

= 0.9.6.5.1 =
* improved performance of generating import preview page
* fixed possible error code 200 when processing import queue

= 0.9.6.5 =
* added support for custom order statuses on settings page
* added gallery fallback option to use attached images if there is no WooCommerce Product Gallery (fixed issue with WooCommerce Dynamic Gallery plugin)
* added loading indicator on edit profile page
* added missing SDK file MarketplaceWebServiceProducts/Model/ErrorResponse.php
* added button to manually convert custom tables to utf8mb4 on WordPress 4.2+ (fix "Illegal mix of collations" sql error)
* improved Amazon column on Products page - show all listings for each product (but group variation listings)
* make sure the latest changes are submitted - even if a feed is "stuck" as submitted
* optimized memory footprint when processing import queue (fixed loading task list for 20k+ items on 192M RAM)
* improved processing of browse tree guide files - link db records to tpl_id to be able to clean incorrectly imported data automatically
* fixed php warning in ajax request when enabling all images on edit product page
* fixed issue with SWVG and Sports feed templates ok Amazon UK

= 0.9.6.4.2 =
* added option to request FBA shipment report every 3 hours
* added Clothing feed template for amazon.ca

= 0.9.6.4.1 =
* fixed possible php error during import 

= 0.9.6.4 =
* added option to set a default product category for products imported from Amazon (advanced settings page) 
* added option to automatically create matched listings for all products with ASINs (developer tools page) 
* improved profile editor for spanish feed templates 
* fixed some CE feed templates not being imported properly (amazon.es) 
* fixed possible fatal error during import 

= 0.9.6.3 =
* added option to process only selected rows when importing / updating products from merchant report 
* added option to enable Brand Registry / UPC exemption for account 
* brand registry: create listings for newly added child variations automatically, even if no UPC or ASIN is provided 
* fixed issue where items listed on multiple marketplaces using the same account would stay "submitted" 
* fixed matching product from edit product page - selected ASIN was removed if products was updated right after matching 
* fixed "View in WP-Lister" toolbar link on frontend 
* addedtooltips for report processing options on import page 
* import process: fixed creating additional (new / missing) variations for existing variable products in WooCommerce 
* regard "fall back to seller fulfilled" option when processing FBA inventory reports - skip zero qty rows entirely if fall back is enabled 

= 0.9.6.2 =
* added option to search / filter report rows in import preview 
* automatically fill in variation attribute columns like size_name and color_name  
* show number of offers considered next to lowest offer price in listings table 
* changed labeling from "imported" to "queued" - and updated text on import and settings pages 
* added developer tool buttons to clean the database - remove orphaned child variations and remove listings where the WooCommerce product has been deleted 
* fixed issue where selecting a category for Item Type Keyword column would insert browse node id instead of keyword (profile editor and edit product page) 
* make sure the customer state (address) is stored as two letter code in created WooCommerce orders (Amazon apparently returns either one or the other) 
* fixed search box on SKU generator page not showing products without listings 
* fixed formatting on ListMarketplaceParticipations response (log details) 
* fixes issue with attribute_ shortcodes on child variations inserting the same size/color value for all variations 

= 0.9.6.1 =
* added option to hide (exclude) specific variations from being listed on Amazon 
* added option to set WooCommerce order status for orders marked as shipped on Amazon 
* added "Sports & Outdoors" category for Amazon CA 
* regard WordPress timezone setting when creating orders 
* automatically update variation_theme for affected items when updating a listing profile 
* make sure sale_price is not higher than standard_price / price - Amazon might silently ignore price updates otherwise 
* fixed issue preparing listings when listing title is longer than 255 characters 
* fixed duplicate ASINs being skipped when importing products from merchant report 
* don't warn about duplicate ASINs if the SKU is unique 
* added action hook wpla_prepare_listing to create new listings from products 

= 0.9.6 =
* Initial release on wordpress.org

