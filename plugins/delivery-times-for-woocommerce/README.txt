=== Delivery Times for WooCommerce ===
Contributors: deviodigital
Tags: delivery, delivery-times, courier, woocommerce, order-delivery
Requires at least: 3.0.1
Tested up to: 5.9.2
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow your customers to choose their desired delivery date and time during checkout with WooCommerce

== Description ==

Allow your customers to choose their desired delivery date and time during checkout with WooCommerce

## Admin Settings

The Delivery Times for WooCommerce plugin comes with settings that help you customize what delivery date & time options are available for your customers during checkout.

### Basic Settings

*   **Pre-order days** - How many days ahead are customers allowed to place an order?
*   **Delivery days prep** - How many days notice do you require for delivery?
*   **Delivery time prep** - How many hours notice do you require for delivery?
*   **Delivery date label** - The label displayed on checkout page and in order details
*   **Require delivery date** - Check this box to require customers select a delivery date during checkout
*   **Delivery time label** - The label displayed on checkout page and in order details
*   **Require delivery time** - Check this box to require customers select a delivery time during checkout

### Business Hours

*   **Delivery days** - Check the box for each day of the week that you offer delivery
*   **Opening time** - What time does your business start delivering orders?
*   **Closing time** - What time does your business stop delivering orders?

### Delivery Drivers for WooCommerce

This plugin allows offers better driver management for all delivery services who use WooCommerce, streamlining your workflow and increasing your bottom line.

Learn more at [Delivery Drivers for WooCommerce](https://www.wordpress.org/plugins/delivery-drivers-for-woocommerce)

### Delivery Fees for WooCommerce

Our WooCommerce delivery fees plugin adds a custom shipping method to WooCommerce specifically for delivery services.

Learn more at [Delivery Fees for WooCommerce](https://www.wordpress.org/plugins/delivery-fees-for-woocommerce)

== Installation ==

1. In your dashboard, go to `Plugins -> Add New`
2. Search for `Delivery Times for WooCommerce` and install this plugin
3. Pat yourself on the back for a job well done :)

== Screenshots ==

1. Example of the `Delivery Date` & `Delivery Time` fields on checkout.
2. Date picker with days disabled based on your chosen settings
3. Time selection displaying 30min intervals based on your opening & closing times in settings
4. DTWC Settings Basic Settings screen
5. DTWC Settings Advanced Settings screen
6. Delivery date & time added to Successful Order details screen
7. Delivery date & time added to WooCommerce admin Order details screen

== Changelog ==

= 1.8.0 =
*   Updated various security related issues found with [Codacy](https://codacy.com) throughout multiple files in the plugin

= 1.7 =
*   Added delivery time checkout page placement setting in `admin/dtwc-admin-settings.php`
*   Added a remove delivery time from customer email setting in `admin/dtwc-admin-settings.php`
*   Added 'dtwc_delivery_time_checkout_display' helper function in `admin/dtwc-helper-functions.php`
*   Added 'dtwc_remove_delivery_time_from_emails' helper function in `admin/dtwc-helper-functions.php`
*   Updated checkout fields to use the new display placement setting in `admin/dtwc-woocommerce-checkout.php`
*   Updated email fields to only get added if removal setting is `off` in `admin/dtwc-woocommerce-checkout.php`
*   Updated delivery date/time checks before adding to email data in `admin/dtwc-woocommerce-checkout.php`
*   Updated 'delivery time' email field to use label helper function in `admin/dtwc-woocommerce-checkout.php`
*   Updated text strings for localization in `languages/delivery-times-for-woocommerce.pot`
*   Updated text strings for localization in `languages/delivery-times-for-woocommerce-es_ES.pot`
*   Updated text strings for localization in `languages/delivery-times-for-woocommerce-fr_FR.pot`
*   Updated text strings for localization in `languages/delivery-times-for-woocommerce-it_IT.pot`

= 1.6.1 =
*   Updated admin settings class to load on `init` for translations to take effect in `admin/dtwc-admin-settings.php`
*   General code cleanup throughout multiple files

= 1.6 =
*   Added new Spanish translation in `languages/delivery-times-for-woocommerce-es_ES.pot`
*   Added new French translation in `languages/delivery-times-for-woocommerce-fr_FR.pot`
*   Added new Italian translation in `languages/delivery-times-for-woocommerce-it_IT.pot`
*   Updated text strings for localization in `languages/delivery-times-for-woocommerce.pot`
*   General code cleanup throughout multiple files

= 1.5 =
*   Added minDate value if prepDays is not 0 in `public/js/dtwc-public.js`
*   Bugfix delivery times in checkout when selecting today in `public/js/dtwc-public.js`
*   Updated minDate to be set as `tomorrow` if the current time is after all delivery times `public/js/dtwc-public.js`
*   Updated text strings for localization in `languages/dtwc.pot`
*   General code cleanup throughout multiple files

= 1.4.1 =
*   Bugfix date display update on checkout in `public/js/dtwc-public.js`
*   General inline doc updates throughout multiple files

= 1.4 =
*   Added option to select placement of delivery time in Edit Order screen in `admin/dtwc-admin-settings.php`
*   Added helper function to get delivery time placement option in `admin/dtwc-functions.php`
*   Added function to move the delivery time on Edit Order screens based on admin setting in `admin/dtwc-woocommerce-settings.php`
*   Bugfix to change `apply_filters` to `add_filter` in `admin/dtwc-helper-functions.php`
*   Updated code to remove warning for empty variable in `public/class-dtwc-public.php`
*   Updated class datepicker class names in `public/js/dtwc-public.js`
*   Updated admin settings table styles in `admin/css/dtwc-admin.css`
*   Updated text strings for localization in `languages/dtwc.pot`
*   Updated the date/time format to default as the WordPress settings options in multiple files
*   Updated first day of week in datepicker to use default WP setting in multiple files
*   Updated datepicker to use `dtwc_date_format` filter in multiple files
*   General code cleanup throughout multiple files

= 1.3 =
*   Added `dtwc_date_format` filter in `admin/dtwc-woocommerce-checkout.php`
*   Added `dtwc_time_format` filter in `admin/dtwc-woocommerce-checkout.php`
*   Added `dtwc_date_format` filter in `admin/dtwc-woocommerce-settings.php`
*   Added `dtwc_time_format` filter in `admin/dtwc-woocommerce-settings.php`
*   Added `dtwc_date_format` filter in `admin/dtwc-ddwc-settings.php`
*   Added `dtwc_time_format` filter in `admin/dtwc-ddwc-settings.php`
*   Updated the style for delivery date label in DDWC dashboard with CSS in `public/css/dtwc-public.css`
*   Updated the style for delivery date label in DDWC dashboard with CSS in `admin/dtwc-ddwc-settings.css`
*   Updated text strings for localization in `languages/dtwc.pot`
*   General code cleanup throughout multiple files

= 1.2 =
*   Added JavaScript to remove delivery times if selected delivery date is today in `public/js/dtwc-publc.js`
*   Added delivery times details to WooCommerce Edit order screen in `admin/dtwc-woocommerce-settings.php`
*   Added delivery times details to Driver Dashboard order details in `admin/dtwc-ddwc-settings.php`
*   Updated `delivery_date` and `delivery_time` variable names in `admin/dtwc-woocommerce-checkout.php`
*   Updated checkout delivery times to include all times from open to close by default in `admin/dtwc-woocommerce-checkout.php`
*   Updated text strings for localization in `languages/dtwc.pot`

= 1.1 =
*   Added `dtwc_checkout_deliter_times_select_default_text` filter in `admin/dtwc-woocommerce-checkout.php`
*   Added `dtwc_order_received_delivery_details` filter in `admin/dtwc-woocommerce-checkout.php`
*   Added `dtwc_settings_delivery_days_options` filter in `admin/dtwc-admin-settings.php`
*   Added `dtwc_order_received_delivery_details_before` action hook in `admin/dtwc-woocommerce-checkout.php`
*   Added `dtwc_order_received_delivery_details_after` action hook in `admin/dtwc-woocommerce-checkout.php`
*   Bugfix misspelling of `delivery_time_label` name in `admin/dtwc-helper-functions.php`
*   Bugfix changed delivery time label text to use helper function in `admin/dtwc-woocommerce-checkout.php`
*   Bugfix prep time check for delivery times options in `admin/dtwc-woocommerce-checkout.php`
*   Updated text strings for localization in `languages/dtwc.pot`
*   General code cleanup throughout multiple files

= 1.0 =
*   Initial release
