=== WC Pickup Store ===
Contributors: keylorcr
Donate link: https://www.paypal.me/keylorcr
Tags: ecommerce, e-commerce, store, local pickup, store pickup, woocommerce, local shipping, store post type, recoger en tienda
Requires at least: 4.7
Tested up to: 6.4.1
Stable tag: 1.8.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WC Pickup Store is a custom shipping method that lets you to set up one or multiple stores to local pickup in the Checkout page in WooCommerce

== Description ==
WC Pickup Store is a shipping method that lets you to set up a custom post type "store" to manage stores in WooCommerce and activate them for shipping method "Local Pickup" in checkout page. It also includes several options to show content by Widget or a WPBakery Page Builder component. Configuration of shipping costs are also available globally or per stores. More about documentation and filter usage in my website [keylormendoza.com](https://keylormendoza.com/woocommerce/wc-pickup-store/#developers)

Check or request a custom PRO add-on [here](https://keylormendoza.com/pro-add-ons/)


### Features And Options:
* Shipping costs globally or per stores.
* Compatible with WPBakery Page Builder with its own addon.
* Widget option.
* Dropdown of stores on the Checkout page.
* Local pickup details in thankyou page, order details and emails.
* Archive template is now available.
* All templates from /wc-pickup-store/templates/ can be overridden in your custom themes.
* Filters and actions are available throughout the code to manage your own custom options.
* Font Awesome and Bootstrap CSS libraries are included in the plugin. You can disable them from the plugin configuration page
* Shipping email notification to stores in the store admin page
* Order and orderby options
* Shipping costs by flat rate or percentage, by method or per stores
* Filter wps_settings_data to edit shipping title and other settings
* Store details on Checkout page. Includes filters, template and JS trigger pickup_store_selected
* Multicountry stores are available. Just choose a country per store and they will be filtered on the Checkout page.
* NEW Tax handling for WC Pickup Store shipping method option (v1.7.0)


= Some Useful Hooks =

These are some useful filters and actions that you might need to extend the plugin functionalities

**wps_store_query_args** to edit the query of stores
**wps_no_stores_availables_message** message to show when no stores are available to display in the Checkout
**wps_first_store** choose the first selected store
**wps_store_pickup_cost_label** label for store pickup costs
**wps_shipping_costs** override method shipping costs
**wps_order_shipping_item_label** method title with instructions
**wps_subtotal_for_store_cost** subtotal to calculate percentage shipping costs
**woocommerce_shipping_wc_pickup_store_is_available** check for shipping method availability
**wps_settings_data** to edit the plugin settings including the shipping title
**wps_get_store_custom_fields** to choose the custom fields to be returned in wps_stores_fields function
**wps_stores_fields** all custom information by store
**wps_formatted_shipping_title** shipping method title on Checkout
**wps_disable_country_filtering** disable filtering by country

== Installation ==

= Requires WooCommerce =

1. Upload the plugin files to the `/wp-content/plugins/wc-pickup-store` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to settings page from `Menu > Stores > Settings` or the shipping methods page in WC to activate `WC Pickup Store` shipping method.
4. Done.


== Frequently Asked Questions ==

= How to setup? =
Just activate the plugin, go to settings page and enable the shipping method. Customize the shipping method title, default store and checkout notification message.

= How to manage stores? =
Go to Menu > Stores > All Stores > Add New

= Can I edit the store templates? =
Yes, you can override all the templates. Just copy from /plugins/wc-pickup-store/templates/ to /theme/template-parts/. Single store and archive page might be overriden in /theme/ directory as WordPress does.

= How do I replace or remove waze icon? =
Simply use filters wps_store_get_waze_icon or wps_store_get_vc_waze_icon to manage waze icon

= Can I set a default store in checkout? =
Yes, just go to Menu > Appearance > Customize > WC Pickup Store > Default Store. Also you can use the filter _wps_first_store_ to do that

= Can I set custom page without using WPBakery Page Builder? =
The shortcode functionality had been removed since previous versions but since version 1.5.13 you can use the `archive-store.php` located in the plugin templates directory

= Is there a way to add a price for the shipping method? =
Fortunately since version 1.5.13 the option to set custom costs by shipping method or per stores is available. Hope you enjoy it! **Update 1.5.21** let you calculate shipping costs by flat rate or percentage 

= Can I send an email to the store with the order details, is that possible? =
Sure, now you can add an email address into the store admin page and it will be notified on order sent to this store.

= Can I translate the shipping method title? =
You can use the filter wps_settings_data with the key **title** to create a custom valid translation for the title. Available since version 1.5.22

= How does the multicountry stores work? =
First, this functionality will work if your Shop is enabled to sell to specific countries, if not, you must to update all the stores to the default Shop country using the link on the notice about this feature (available since version 1.5.24). Then, you just have to choose a country for each store on the store settings page.

= How can I manage tax options for this shipping method? =
View more information about tax handling in [this documentation](https://keylormendoza.com/how-to/tax-handling-by-stores/)


== Screenshots ==
1. WC Pickup Store shipping configurations.
2. Default Store.	
3. Checkout page.
4. Order details.
5. VC element.
6. VC element Result.
7. Widget Element.
8. Widget Element Result.
9. Published store validation.
10. WC error after store validation.
11. Email notification
12. Shipping cost by shipping method
13. Shipping cost per stores
14. Order Email Notification
15. Order and Orderby options
16. Store details on Checkout page
17. Filtering stores by Country
18. Default label in Store picker on the Checkout page


== Changelog ==
= 1.8.6 =
* Update: Check for compatibility with WC HPOS in wps_show_store_in_admin, wc_reordering_order_item_totals, wps_get_email_address, wps_wc_order_get_formatted_shipping_address and wps_store_save_order_meta
* Improvement: Action woocommerce_checkout_update_order_meta replaced by woocommerce_checkout_create_order in wps_store_save_order_meta
* Update: Replace get_post_meta with native wps_get_post_meta in wps_store_id_column_content and wps_store_metabox_details_content
* Review: Code review in wps_locate_template
* New: Filter wps_store_widget_query_args for all the query args in widget display in class-widget-stores.php
* New: Filter wps_store_widget_query_args for all the query args in widget display for WPBakery in class-vc_stores.php
* Update: Check for compatibility WC & WP

= 1.8.5 =
* Update: Check for compatibility with WP y WC
* Update: Check for compatibility with PHP+8
* New: Custom setting to override wps_store_select_first_option filter for dropdown first option text
* Fix: Array validation in wps_store_validate_checkout
* Fix: Validation added to wps_store_country_notice to remove admin country notification 
* Update: Replacement for deprecated function get_page_by_title

= 1.8.4 =
* Update: Check for compatibility

= 1.8.3 =
* Fix: Issues in query that affects all the post types.
* Update: Check for compatibility

= 1.8.2 =
* Fix: Multisite validation if WC plugin is not active
* Improvement: Validations with country filtering notice and functionality
* Fix: Undefinded value in function wps_store_row_layout with variable $country_to_filter
* Fix: Condition to prevent undefined variable in function wps_get_chosen_shipping_method

= 1.8.1 =
* Improvement: Admin notices for validations
* Update: Check for compatibility

= 1.8.0 =
* New: Actions wps_miscellaneous_admin and wps_save_post
* New: Filter wps_localize_script 
* Improvement: Code readability

= 1.7.3 =
* Update: Template .pot file for translations.
* Update: es_CR and es_ES translations

= 1.7.2 =
* Fix: Default information for shipping address

= 1.7.1 =
* Remove: Function wps_get_tax_sufix
* New: Shipping order details replaced by Store information
* Fix: Some fixes from previous versions
* Improvement: Notice when country field was updated visible on store page was removed
* WC Compatibility check

= 1.7.0 =
* New: Tax handling for WC Pickup Store shipping method
* Fix: Deprecated mark for function wps_get_chosen_shipping_method removed
* New: French translations files, thanks to Christophe Pouillet

= 1.6.3 =
* New: Customizer option to select the default label as the first option in the dropdown instead of using a store
* New: Filter wps_notice_store_validation for message on the Checkout validation for non selected store
* New: Function wps_get_store_id_by_name to get store_id by store_name
* Remove: Unused woocommerce_after_shipping_calculator action for layout in function wps_store_row_layout

= 1.6.2 =
* Fix: Undefined function wps_get_chosen_shipping_method, now it is deprecated

= 1.6.1 =
* New: Filter wps_store_select_first_option to change the first option in the store select field
* New: Filter wps_stores_dropdown to wrap select stores HTML tag
* New: Custom message for cart page

= 1.6.0 =
* Update: Validation to disable filtering by country using filter **wps_disable_country_filtering** or custom option from settings page
* Improvement: Language .pot file and plugin textdomain
* Fix: Network activation

= 1.5.29 =
* Improvement: Documentation
* Fix: Remove validation to save default country

= 1.5.28 =
* Fix: Default country in admin store page from previous version in includes/post_type-store.php

= 1.5.27 =
* Remove: esc_attr from wps_stores_fields in includes/wps-functions.php
* Update: Country data in products listing in includes/post_type-store.php
* New: show_in_rest parameter for Custom Post Type
* Remove: Unused template file wrapper-store.php added to includes/ directory
* New: return array with keys in wps_store_get_store_admin in includes/wps-admin.php

= 1.5.26 =
* Fix: Shipping rate cost on Checkout using store shipping cost in calculate_shipping in includes/wps-init.php
* Update: Concat country code and name on stores page
* Remove: Customer notification for store notification in wps_cc_email_headers, only new_order is available in includes/wps-functions.php

= 1.5.25 =
* Fix: From previous 1.5.24 version
* New: Country filter for stores admin and stores on Checkout
* New: Country dropdown in stores admin page if Shop sells to specific countries in includes/post_type-store.php
* New: Option to update all stores without Country wps_update_stores_without_country in includes/post_type-store.php

= 1.5.24 =
* Fix: Apply filter position in validation of wps_shipping_method_label in includes/wps-functions.php
* New: Filter wps_store_checkout_label in title of store dropdown options in includes/wps-functions.php
* New: Filter wps_store_calculated_costs in includes/wps-init.php
* Update: Improvement to accept multiple email addresses separated by comma in wps_get_email_address in includes/wps-functions.php
* Update: Validation of WC email types to add the store admin email in wps_cc_email_headers in includes/wps-functions.php
* New: Filter wps_cc_on_email_types for accepted email types in includes/wps-functions.php
* New: Function and filter wps_get_post_meta to return all custom meta using filter in includes/wps-functions.php
* New: Stores dropdown loads using select2 library and option might be disable from plugin settings in includes/wps-init.php and stores.js
* New: Functions wps_check_countries_count and wps_stores_filtering_by_country to allow multicountries stores and Country filter on the stores dropdown in includes/post_type-store.php 
* Update: Param meta_query updated to use relation AND instead of OR on custom stores query in includes/wps-admin.php

= 1.5.23 =
* Fix: Function wps_locate_template to load local templates with locate_template
* Fix: Unnecessary parameter $store_id removed in filter wps_stores_fields
* New: Setting hide_store_details to hide/show store details in the Checkout page
* Update: Template validation if exists in stores.js

= 1.5.22 =
* Fix: Option none in Shipping costs type to invalidate shipping costs calculation in includes/wps-init.php
* New: Filter wps_settings_data to edit the plugin settings including the shipping title, in includes/wps-init.php
* New: wp_localize_script wps_ajax.stores to get all custom fields from stores with wps_stores_fields function and filter in includes/wps-functions.php
* New: Filter wps_get_store_custom_fields to choose the custom fields to be returned in wps_stores_fields function, in includes/wps-functions.php
* New: Function wps_locate_template to get the template file path from plugin or custom theme
* New: Store details in the Checkout page
* New: Template file selected-store-details.php to show store details on Checkout page

= 1.5.21 =
* Fix WPBakery store component
* Fixed span elements added to item label in includes/wps-init.php
* Obsolete file removed in includes/wrapper-store.php
* New percentage or flat rate shipping costs calculation, per store or shipping method

= 1.5.20 =
* Fix filter wps_order_shipping_item_label parameter

= 1.5.19 =
* Update textdomain as a global variable
* New filter wps_order_shipping_item_label wrapping the shipping order/checkout label
* New order and orderby options are added to the configuration page

= 1.5.18 =
* Fix BS+4 conflict with .col class in includes/vc_stores.php

= 1.5.17 =
* Fix FA+5 icon in VC template

= 1.5.16 =
* Fixing issue with local and external libraries validation

= 1.5.15 =
* Validation for local and external libraries
* Function to return main instance for WC_PICKUP_STORE
* New admin fields store_order_email and enable_order_email

= 1.5.14 =
* Change of wp_enqueue_style instead of using wp_register_style with bootstrap and font awesome libraries

= 1.5.13 =
* **New** shipping method custom price
* **New** adding shipping method price per store
* Fix in VC element initialization
* Fix in image custom size validation used in VC custom element
* **New** Archive Template
* New .pot file
* Font Awesome and Bootstrap css have been included

= 1.5.12 =
* Logo waze svg
* Filters wps_store_get_waze_icon and wps_store_get_vc_waze_icon to manage waze icon

= 1.5.11 =
* Single store template
* Filter wps_store_query_args for store query args
* Fix esc_html to print content in template
* VC element and widget from template

= 1.5.10 =
* Validate whether all stores are published, otherwise, shipping method is not applicable
* Fix selected store notification in emails
* Notification was added in admin panel 
* Editor field was added to stores

= 1.5.9 =
* Latest stable version


== Upgrade Notice ==
= 1.8.5 =
* New: Custom setting to override wps_store_select_first_option filter for dropdown first option text
* Review: Compatibility with WP 6.2.0 and WC 7.6.1
* Fix: Validation to hide the country validation for stores visible on admin pages

= 1.8.3 =
* Important Fix: Reported issues related to query in post types

= 1.8.2 =
* Important Fix: Multisite validation if WC plugin is not active

= 1.8.0 =
* New: Filters for admin options page
* Improvement: Code readability
* Review: Compatibility with WP 5.9 and WC 6.2.0

= 1.7.1 =
* Fix: Some fixes from previous versions

= 1.6.2 =
* Fix: Undefined function wps_get_chosen_shipping_method

= 1.6.1 =
* Improvement: Code structure
* New: Message on the cart page when shipping is chosen

= 1.5.24 =
* New: Filters to update the shipping label and stores picker label on Checkout: wps_shipping_method_label and wps_store_checkout_label
* New: Country stores filter if multicountries shipping option is enable
* Update: Comma separated email are accepted for email store notification

= 1.5.23 =
* Fixing version 1.5.22

= 1.5.22 =
* New features available

= 1.5.21 =
* WPBakery store component fixed
* Span elements reported in the Checkout were removed. Filter wps_order_shipping_item_label is available for any change to display the instructions in the label
* New percentage or flat rate shipping costs calculation, per store or shipping method

= 1.5.20 =
* Fix filter wps_order_shipping_item_label parameter

= 1.5.19 =
* New filter wps_order_shipping_item_label wrapping the shipping order/checkout label
* New order and orderby options are added to the configuration page

= 1.5.18 =
* Fix BS+4 conflict with .col class in includes/vc_stores.php

= 1.5.17 =
* Fix FA+5 icon in VC template

= 1.5.16 =
* Fixing issue with local and external libraries validation

= 1.5.15 =
* Validation for local and external libraries
* New admin fields store_order_email and enable_order_email
* Compatibility for WC 3.6.4 and WP 5.2.2

= 1.5.14 =
* Change of wp_enqueue_style instead of using wp_register_style with bootstrap and font awesome libraries

= 1.5.13 =
* Shipping costs added by shipping method or per each store
* Archive template added
* File .pot updaded
* Fixes in VC element
* Font Awesome and Bootstrap css have been included

= 1.5.12 =
* Filters wps_store_get_waze_icon and wps_store_get_vc_waze_icon to manage waze icon

= 1.5.11 =
* Fix esc_html to print content in template

= 1.5.10 =
* Fix selected store notification in emails
* Fix validation for available stores in checkout

= 1.5.9 =
* Fix: Validate shipping method before to show the store in checkout page
* Update: Change in shipping method title to remove the amount ($0.00)

= 1.5.8 =
* Update: Textdomain and function names
* Delete: provincias taxonomy
* Add: Minify VC element styles file
