==== Easy Booking for WooCommerce ====

Contributors: @_Ashanna, @amsul (For pickadate.js)
Tags: woocommerce, booking, renting, products, book, rent, e-commerce, shop
Requires at least: 4.0, WooCommerce 3.0
Stable tag: 3.2.0
Tested up to: 6.4.2
WC tested up to: 8.4.0
License: GPLv3

A powerful and easy to use booking plugin for WooCommerce.

== Description ==

Easy Booking is a powerful and easy to use booking plugin for WooCommerce compatible with simple, variable, grouped and bundle products. An ideal solution to make the most of WooCommerce without having to manage a new product type.

It offers the right amount of options to adapt to your needs: \"Days\" or \"Nights\" mode, booking duration and limits, one or two dates, price calculation… And for developers, filters and action hooks to extend features.

It allows you to easily manage and keep an eye on processing or upcoming bookings from your WordPress dashboard.

= Demo =

Check the plugin demo [here](http://demo.easy-booking.me/).

= PRO version =

Add [Easy Booking PRO](https://easy-booking.pro/pro/) for extended features: stock management per date, disabled dates, prices depending on booking duration and/or dates, manual bookings and more.

== Installation ==

= Requirements =

WordPress 5.0 or greater
WooCommerce 4.0 or greater

Make sure WooCommerce is installed and activated before starting.

= Installation =

You can install the plugin automatically or manually. If you are not familiar with plugin installation, please refer to [this page](https://wordpress.org/support/article/managing-plugins/#installing-plugins).

= Settings =

Learn how to configure the plugin and your products in the [documentation](https://easy-booking.pro/documentation/).

== Frequently Asked Questions ==

Check the FAQ [here](https://easy-booking.pro/faq/).

== Screenshots ==

1. Product page
2. Calendar
3. Selected dates
4. Product in cart
5. Plugin settings
6. Reports

== Changelog ==

= 3.2.0 - 2024-02-12 =

* Fix - Load scripts only on "Reports" pages to improve performance. Temporary fix before a better performance update for "Reports" pages.
* Fix - Error with PHP 8.2.

= 3.1.9 - 2024-01-02 =

* Add - Added a "Calendar" page in the "Reports" section.
* Tweak - Updated readme and plugin website links.
* Tweak - Change plugin name to Easy Booking for WooCommerce.

= 3.1.8 - 2023-10-19 =

* Fix - Compatibility with WooCommerce HPOS.
* Fix - Issue with booking duration not being an integer.
* Fix - Undefined CSS vars in admin causing issues with product search in reports page.

= 3.1.7 - 2023-09-20 =

* Fix - Several issues with WooCommerce Product Add-Ons.

= 3.1.6 - 2023-06-03 =

* Fix - Undefined variable causing JS error with Product Add-Ons.

= 3.1.5 - 2023-06-01 =

* Fix - Compatibility with latest version of WooCommerce Product Add-Ons (6.3). No longer compatible with older versions.
* Tweak - Changed hr_HR.js (Croatian) translation file to hr.js.

= 3.1.4 - 2023-04-24 =

* Fix - Conflict with another plugin using the same ajax_url variable name.

= 3.1.3 - 2023-04-14 =

* Fix - Issue with usort function and PHP >= 8.2.

= 3.1.2 - 2023-03-10 =

* Fix - Issue with disabled dates on one calendar only, when "Allow booking of disabled dates?" is uncheck.

= 3.1.1 - 2023-01-05 =

* Fix- Error with PHP > 8.0.

= 3.1.0 - 2022-09-12 =

* Add - Tool to init booking statuses.
* Add - Check if WPML is active (for PRO version).
* Tweak - Removed old backward compatibility.

= 3.0.9 - 2022-07-05 =

* Fix - Issue when modifying order item meta.

= 3.0.8 - 2022-03-21 =

* Fix - Issue with disabled days when first weekday is Sunday.

= 3.0.7 - 2021-12-20 =

* Fix - Issue with WooCommerce Product Add-Ons > 4.1.0.

= 3.0.6 - 2021-11-08 =

* Add - Notice to remind people to upgrade to PRO.
* Fix - CSS for import page.
* Tweak - Improved settings texts and labels.

= 3.0.5 - 2021-09-13 =

* Fix - Check first available date before adding to cart/in cart.
* Fix - Polish translation for calendar.

= 3.0.4 - 2021-06-14 =

* Add - Compatibility with "Export" feature from the PRO version.
* Add - "easy_booking_after_reports_form" action hook.
* Fix - Issue when saving variation option.
* Fix - Make sure parent is a WC_Product in wceb_is_bookable() function.

= 3.0.3 - 2021-04-27 =

* Add - Compatibility with WooCommerce Deposits (from WooCommerce team).
* Add - Possibility to pass dates as url parameters when adding to cart.
* Add - 'easy_booking_settings_capability' filter to give different capability depending on the page.
* Fix - Make variation not bookable if parent product is not bookable.
* Fix - Make settings labels translatable.
* Fix - Hide datepicker form after reseting variations.
* Tweak - Changed .datepicker class to .wceb_datepicker to avoid conflicts with other librairies.
* Tweak - "Help" tab on settings page.

= 3.0.2 - 2021-03-08 =

* Fix - Issue with $0 grouped products.
* Fix - Replaced deprecated function wceb_get_price_html() with wceb_get_product_price_suffix().
* Fix - Load en_US.js file for Pickadate if translation file doesn't exist for the site language to avoid errors.
* Fix - Booking duration must be at least 1.
* Fix - Added deprecated $custom_booking_duration argument in easy_booking_get_price_suffix filter to avoid errors.

= 3.0.1 - 2021-02-18 =

* Fix - Add to cart for not-bookable products.
* Fix - Month and year dropdowns in calendar CSS.
* Fix - Missing calendar translation files for the following languages: formal DE, MX, PE, BE.
* Fix - Issue with grouped products with free products.
* Tweak - Attempt to improve option tables CSS on small screens.

= 3.0.0 - 2021-01-28 =

* Fix - Updated woocommerce_display_item_meta filter.
* Fix - Estonian translation.
* Tweak - Removed "Custom booking duration" setting and merged it with "Booking duration".
* Tweak - Removed usage of session and used $_POST instead to add product dates to cart.
* Tweak - Added new "Tools" page.
* Tweak - Removed useless admin notices.
* Tweak - Renamed POT file.
* Compatibility with Easy Booking PRO.

= 2.3.3 - 2020-09-01 =

* Fix - Unable to select current date.

= 2.3.2 - 2020-08-17 =

* Fix - Updated pickadate.js to 3.6.2 and fixed several small issues.
* Fix - Bundle products - Issue with not priced individually products.
* Fix - Check that selected dates are not in the past.
* Tweak - Added fi.js file to translate the calendar in Finnish.

= 2.3.1 - 2020-07-03 =

* Fix - Issue with order booking status when manually adding an order.
* Fix - One date booking price not updated when changing quantity.
* Fix - Issue with Rest API.

= 2.3.0 - 2020-06-15 =

* Fix - Issue with bundle products with no price.
* Fix - Compatibility with WooCommerce Rest API.
* Add - Added Lithuanian translation for the calendar.
* Tweak - Improved colors in the calendar.
* Tweak - Refactored code for cart, checkout, orders and booking statuses.
* Tweak - Changed "_ebs_start_format" order item meta to "_booking_start_date".
* Tweak - Changed "_ebs_end_format" order item meta to "_booking_end_date".
* Tweak - Replaced "easy_booking_add_booked_item" action hook with "easy_booking_add_order_item_booking_data".

= 2.2.9 - 2020-03-27 =

* Fix - Opacity issue after selecting dates.
* Fix - Fixed and improved compatibility with WooCommerce Product Add-Ons. Percentage-based, flat-fee and quantity-based add-ons should now be calculated correctly.
* Fix - In "Nights" mode, minimum booking duration is now always at least 1 night (instead of 0).
* Add - 'easy_booking_new_price_to_display' filter.
* Add - 'easy_booking_new_regular_price_to_display' filter.
* Add - 'easy_booking_cart_item' filter.
* Add - 'easy_booking_calculate_booking_price' filter.

= 2.2.8 - 2020-03-09 =

* Fix - Compatibility with Polylang.
* Fix - Issue with Product Add-Ons when addons prices had decimals.
* Fix - Prices update correctly when changing grouped/bundle products quantities.
* Fix - Default text color set to 000000 instead of #000000.
* Add - 'easy_booking_selected_booking_duration' filter.
* Add - 'easy_booking_simple_product_booking_data' filter.
* Add - 'easy_booking_variable_product_booking_data' filter.
* Add - 'easy_booking_grouped_product_booking_data' filter.
* Add - 'easy_booking_bundle_product_booking_data' filter.
* Add - 'easy_booking_new_price' and 'easy_booking_new_regular_price' filters.
* Add - 'easy_booking_total_booking_duration_text' filter.
* Add - 'easy_booking_average_price_text' filter.
* Add - 'easy_booking_pao_addon_cost' and 'easy_booking_pao_total_addons_cost' filters.
* Add - 'easy_booking_home_url' filter.
* Add - 'easy_booking_after_init' action hook.
* Tweak - Refactored frontend Ajax and compatibility with third-party plugins.

= 2.2.7 - 2020-01-07 =

* Fix - Display an alert to select product options and dates on product pages.
* Fix - Wrong minimum booking duration with custom booking duration.
* Fix - Bundle price was wrong when changing quantity once dates were selected.
* Tweak - Refactored code for frontend and functions.

= 2.2.6 - 2019-11-12 =

* Fix - Issue with product booking settings being empty when value is 0.
* Fix - Booking form shown for out-of-stock variable products but scripts were not loaded.
* Fix - Issue with non-bookable bundled items price.
* Tweak - Refactored admin and reports code.
* Tweak - Changed 'easy_booking_get_price_html' to 'easy_booking_get_price_suffix'.
* Tweak - Changed 'easy_booking_display_price' to 'easy_booking_price_html'.
* Tweak - Changed 'wceb_bookable_product_add_to_cart_text' to 'easy_booking_select_dates_text'.

= 2.2.5 - 2019-10-14 =

* Fix - Backward compatibility for Easy Booking 2.2.4 ('_booking_dates' meta changed to '_number_of_dates'). Make sure to update the database.
* Fix - Fixed and improved the "Update database" tool.
* Fix - Conflict with WooCommerce Bookings.

= 2.2.4 - 2019-10-08 =

* Important change - Changed _booking_option meta to _bookable. Make sure to update the database.
* Fix - Fixed several issues with WooCommerce Product Add-Ons.
* Fix - Removed OR conditions in queries because they are not well supported by WP.
* Fix - Set order booking status to "Processing" when ordering a bookable product.
* Add - Added a "Tools" page in the plugin settings.
* Tweak - Refactored several admin files.

= 2.2.3 - 2019-09-17 =

* Fix - Product ID not sent when selecting dates.

= 2.2.2 - 2019-09-04 =

* Fix - Products being shown as unavailable when not managing stocks.
* Fix - Issues with Polylang.
* Fix - Network settings for add-ons license keys.
* Tweak - Refactored plugin settings.

= 2.2.1 - 2019-03-18 =

* Fix - Issues with disabled dates and first available date when first weekday is Sunday.
* Fix - Remove error if bookable variable product has no bookable variation (make it not bookable instead).
* Fix - Maybe display price suffix on product page.
* Tweak - Add "Completed" order booking status to orders with no bookable products.

= 2.2.0 - 2019-01-07 =

* Updated plugin settings. They are now stored one by one in DB instead of in a big array.
* Changed text domain to woocommerce-easy-booking-system in order to match Wordpress.org rules. You'll need to change your translation files' names to the new textdomain.
* Fix - Fixed an issue with WooCommerce Product Add-Ons > 3.0.4.
* Removed - Removed "First weekday" setting, you can use the one in Settings > General > Week starts on.
* Removed - Removed unnecessary admin notices.
* Tweak - Updated Norwegian translation for the calendar.

= 2.1.12 - 2018-10-30 =

* Fix - Compatibility with WooCommerce Product Add-Ons 3.0.0.
* Fix - Moved 'easy_booking_booking_price_details' filter to make it easier to use.
* Localization - Updated nl_NL translation.
* Localization - Added en_ZA translation.

= 2.1.11 - 2018-08-29 =

* Fix - Replaced deprecated .size() with .length in JS files.
* Fix - Issue with grouped products and WooCommerce Product Add-Ons.
* Removed - Removed useless hidden input for variation ID.

= 2.1.10 - 2018-06-04 =

* Fix - Backward compatiblity with previous WooCommerce versions.
* Fix - Compatibility with WooCommerce Memberships for variable products.
* Add - Canadian French translation for the calendar.

= 2.1.9 =

* Fix - Fixed an issue with WooCommerce Product Addons.

= 2.1.8 =

* Add - Added "Multiply addon cost by booking duration?" option to multiply or not addon costs from Product Addons by booking duration.
* Add - Added 'wceb_bookable_product_add_to_cart_text' filter.
* Add - Added 'easy_booking_add_booked_item' and 'easy_booking_init_settings' action hooks.
* Add - Added filters and action hooks to the "Reports" page.
* Remove - Removed 'ebs_start_display' and 'ebs_end_display' from database, and replace them with date_i18n()) functions for display.
* Fix - Add to cart button won't be disabled anymore when selecting a not bookable variation.
* Fix - Small security fixes.
* Updated POT file.

= 2.1.7 =

* Add - Added "Reset dates" button.
* Add - Added compatibility with WPML.
* Fix - Issue in the "Reports" page when dates were not displayed correctly.
* Fix - CSS issue when selecting month/year in the "Reports" page.
* Tweak - Improved frontend ajax requests so admin-ajax.php file is not called anymore.
* Tweak - Changed "Booking limit" to "Last available date" so it can be configured in days.

= 2.1.6 =

* Add - Added booking statuses for order items and imported bookings, in order to improve the "Reports" page and make it easier to track and organize your bookings.
* Add - Added en_CA.js file for Canadian translation.
* Fix - Fixed "easy_booking_booking_price_details" filter to make it easier to use.
* Fix - Fixed an issue with disabled dates and Sunday as first weekday.
* Fix - Fixed an issue with bundled items in cart.
* Fix - Fixed an issue when getting price of not individually priced bundled items.
* Fix - Display custom sale price even if product sale price is not set.

= 2.1.5 =

* Add - Added en_AU.js file for Australian translation.
* Fix - Force http/https when calling admin-ajax.php file to avoid security fails on ajax requests.
* Fix - Issue on "sold individually" grouped products.

= 2.1.4 =

* Fix - Issue with WooCommerce Product Bundles and WooCommerce < 3.0 where variation discounted price was not taken into account.
* Fix - Issue with WooCommerce < 3.0 where not bookable variations were considered bookable.
* Fix - Check if "get_bundled_item" method exists in cart to avoid errors.
* Add - Returns start and (maybe) end date(s) when fetching orders with WooCommerce Rest API.
* Tweak - Removed unnecessary CSS file in admin.
* Tweak - Load only "Default" calendar theme in admin.

= 2.1.3 =

* Fix - Issue with Custom booking duration set at product level not being applied.
* Fix - Added Australian translation file for the calendar.
* Tweak - Added $content argument to easy_booking_display_price filter.

= 2.1.2 =

* Fix - Discounted prices on bundled products are now correctly applied.
* Fix - Issue with "Start" and "End" texts appearing on non bookable products in order details.
* Fix - Issue with WooCommerce Product Addons.

= 2.1.1 =

* Fix - Compatibility with WooCommerce 3.0 and WooCommerce Product Bundles 5.2.0.
* Fix - Fixed an issue with one date selection and product ID not sent in the ajax request with WooCommerce 3.0.
* Fix - Fixed an issue with variable products with no available variation.

= 2.1.0 =

* Fix - Compatibility with WooCommerce > 3.0
* Fix - Removed "name=variation_id" to avoid conflicts.

= 2.0.9 =

* Updated addons page.
* Fix - Return price if 0 after selecting dates.
* Fix - Compatibility with WooCommerce Deposits.
* Fix - Compatibility with WooCommerce Product Addons and variable products.

= 2.0.8 =

* Fix - Compatibility with WooCommerce Product Bundles > 5.0. If you use it, you must have at least version 5.0.
* Fix - Compatibility with the future Easy Booking: Pricing extension.
* Fix - [Localization] Added "Close" translation in catalan and added ca.js file.
* Fix - [Localization] Updated wceb.pot file.
* Removed - [Localization] Removed FR and NL included language files. To download language files, please visit http://herownsweetcode.com/easy-booking/documentation/easy-booking/localization/.

= 2.0.7 =

* Fix - Fixed wceb_get_product_price function and compatiblity with Membership plugins.
* Fix - [Frontend] Total booking duration displaying "x day(s)" in "nights" mode, instead of "x night(s)".
* Fix - WooCommerce Product Bundles compatibility.

= 2.0.6 =

* Add - [Filter] 'easy_booking_multiply_additional_costs' filter to multiply or not additional costs by booking duration (default: false).
* Add - [Filter] 'easy_booking_product_booking_min' to override product settings.
* Add - [Filter] 'easy_booking_product_booking_max' to override product settings.
* Add - [Filter] 'easy_booking_product_first_available_date' to override product settings.
* Add - [Fitler] 'easy_booking_display_average_price' to display or not the average price / day after selecting the dates (default: false).
* Add - [Filter] 'easy_booking_booking_price_details' to override booking price details (total booking duration and (maybe) average price / day).
* Add - [Filter] 'easy_booking_one_date_price' returns booking price for one date selection.
* Add - [Filter] 'easy_booking_two_dates_price' returns booking price for two dates selection.
* Add - [Frontend] Display regular and sale booking prices if the product is on sale.
* Add - [Frontend] Display the total booking duration after selecting the dates.
* Removed - [Filter] 'easy_booking_get_new_item_price' (replaced by 'easy_booking_one_date_price' and 'easy_booking_two_dates_price').
* Removed - [Filter] 'easy_booking_get_new_grouped_item_price' (replaced by 'easy_booking_one_date_price' and 'easy_booking_two_dates_price').
* Fix - [Frontend] Issue with cache plugins and nonce where the booking price wasn't updated correctly.

= 2.0.5 =

* Fix - [Frontend] Issue with bookable and non-bookable variations with the add to cart button being disabled.

= 2.0.4 =

* Fix - [Admin] Dates are now correctly saved when modifying and saving an order on the admin.

= 2.0.3 =

* Fix - [Security] Security fixes on ajax requests.
* Fix - [Frontend] Set customer session only if necessary.
* Fix - [Admin] Issue when changing dates in the admin order page.

= 2.0.2 =

* Fix - [Frontend] Issue with first available date.
* Fix - [Frontend] Issue with WooCommerce Product Addons with several addons per product.
* Fix - [Admin] Issue when saving appearance settings and "Make all products bookable?" option.

= 2.0.1 =

* Fix   - [Admin] Issue on the admin order page when order item is not a product.
* Fix   - [Frontend] Set the pickers highlight and view to the first available date.
* Fix   - [Admin] Issue with character encoding in pickadate.js.
* Tweak - [Filters] Deleted 'easy_booking_currency' and 'easy_booking_new_price_args' filters when returning the price.

= 2.0.0 =

/!\ Please update the addons if you have any and want to use this new version. Because of the new features added, it is not compatible with older versions.

Compatible versions of the addons:

- Availability Check > 1.5.
- Duration Discounts > 1.7.
- Disbale Dates > 1.5.

* Add   - [Feature] Possibility to select only one date.
* Add   - [Feature] Compatibility with WooCommerce Product Bundles.
* Add   - [Filter] 'easy_booking_start_text' filter to change the "Start" text and make it translation-ready.
* Add   - [Filter] 'easy_booking_end_text' filter to change the "End" text and make it translation-ready.
* Add   - [Filter] 'easy_booking_information_text' filter to change the information text and make it translation-ready.
* Fix   - [Frontend] Save the date to the right format when adding a product to the cart.
* Fix   - [Admin] Display datepickers when manually adding a bookable product to an order in the admin.
* Tweak - Enable "Add to cart" button only once the date(s) is (are) selected.
* Tweak - Changed ajax_object variable into wceb_object, to avoid conflicts with other plugins.

= 1.9.2 =

* Fix - [Admin] Issue with the "Make all products bookable?" option.
* Fix - [Frontend] Load scripts even if product price is 0.

= 1.9.1 =

* Fix - [Frontend] Error when setting maximum booking duration to 1.

= 1.9.0 =

This update contains major changes / improvements. Do not hesitate to go to the [support forum](http://herownsweetcode.com/support/woocommerce-easy-booking/) or to [send a message](http://herownsweetcode.com/contact/) if you see any issue, as it's hard to test everything.

/!\ You will also need to update the addons - if you have any - to these versions in order to have everything work:

* Availability Check 1.4
* Duration Discounts 1.6
* Disable Dates 1.4

Inferior versions of the addons will NOT work with WooCommerce Easy Booking 1.9.

Make sure to clear your navigator's cache if you experience any issue, so it loads the latest files.

* Add     - [Feature] Weekly or custom period bookings and pricing. It is now possible to define the booking duration (either for every product or individually) and the price will be calculated depending on this duration. E.g: weekly, 2 days, 4 nights, etc.
* Add     - [Frontend] Improved calendars CSS to prevent conflicts with themes.
* Add     - [Filter] 'easy_booking_allowed_product_types' filter to allow custom product types (by default only simple, variable and grouped products are allowed to be bookable).
* Add     - [Localization] Missing Swedish translation.
* Add     - [Admin] 'wceb_get_version' function to get the plugin's version.
* Removed - [Admin] 'manage_bookings' option on variable products.
* Removed - [Admin] Unnecessary functions on the order admin page.
* Removed - [Localization] Duplicate elements on Pickadate.js French translation.
* Fix     - [Frontend] Booking price showing when the variation is not bookable.
* Fix     - [Admin] Register frontend scripts before enqueue.
* Fix     - [Admin] [Frontend] Dependencies when loading scripts.
* Fix     - [Admin] Check date format before getting booked items from orders.
* Tweak   - [Admin] Dates are now managed in dateranges if possible, instead of individually, to prevent too heavy arrays in Javascript.
* Tweak   - [Admin] Escape reports fields and CSS settings.
* Tweak   - Reviewed, updated and improved code to optimize the plugin.

= 1.8.2 =

* Fix - [Admin] Issue when not being able to make products not bookable.

= 1.8.1 =

* Fix     - [Frontend] Allow price to be 0 when adding a bookable product to the cart.
* Tweak   - [Admin] Limit max year to current year + 10 years, to avoid having too much data to load for Disabled Dates.
* Removed - [Admin] Removed price calculation when modifying dates on the order page (price has to be updated manually now).

= 1.8 =

* Add   - [Filter] 'easy_booking_frontend_parameters' filter to pass extra parameters to the pickadate-custom.js file.
* Add   - [Disable Dates] Compatibility with the new feature of Easy Booking: Disable Dates, which allows to have disabled dates inside the booking period.
* Add   - [Settings] Option to set the first weekday to Monday or Sunday.
* Add   - [Admin] Function to load a template from the theme instead of the plugin, in case you want to override it. Overriden templates must be placed in a folder named 'easy-booking' (only works for 'wceb-html-product-view.php' - template for the datepicker inputs  - for the moment).
* Fix   - [Frontend] Issue when no date was disabled.
* Fix   - [Frontend] Wrong price calculation with WooCommerce Product Add-Ons and prices without taxes.
* Fix   - [Frontend] [Grouped products] Reset and render datepickers after changing each product quantity.
* Fix   - [Frontend] [Variable products] Selects "input[name=variation_id]"" instead of "input.variation_id" to get the selected variation ID to ensure maximum compatibility with themes.
* Fix   - [Admin] Booking meta boxes showing when they shouldn't.
* Fix   - [Frontend] [Variable products] Wrong price formatting.
* Fix   - [Admin] [Variable products] Booking fields were not showing correctly on variations.
* Fix   - [Admin] Escape settings correctly.
* Fix   - Compatibility with WooCommerce 2.5.0.
* Tweak - [Settings] Improved settings and addons pages and notices.
* Tweak - [Frontend] Format and sanitize correctly the information text.

= 1.7.6 =

* Add - Multisite support for addons and their license keys.
* Add - Uninstall file to clean database when deleting the plugin.
* Fix - Variable product prices not displaying when the product and variations are not bookable.
* Update - .pot file and French translation.

= 1.7.5 =

* Fix - Issue setting start date as "Array" instead of the date when booking a product.

= 1.7.4 =

* Fix - Wrong calculation of interval when weekdays are disabled (compatibility with Easy Booking : Disable Dates).

= 1.7.3 =

* Add - Option to set a year limit for bookings.
* Add - Compatibility with Easy Booking : Disable Dates.
* Add - Constant to define path and suffix to load scripts and styles.
* Add - wceb.pot file for translations.
* Fix - Price was updated when changing product quantity with WooCommerce Product Addons, even if there was no addon cost.
* Fix - Variation price was not updated after selecting dates.
* Fix - Display variation booking settings on the admin product page when changing the variation booking option.
* Update - French Translation.

= 1.7.2 =

* Add - Constant to load Pickadate.js translations.
* Fix - Load accounting.js for grouped products.

= 1.7.1 =

* Fix - Replaced a missing function on some PHP installations.

= 1.7 =

This update contains major changes / improvements. Be careful before updating and do not hesitate to go to the support forum or to send a message if you see any issue, as it's hard to maintain everything :)

* Add - Compatibility with grouped products.
* Add - Possibility to make all products bookable at once, and set booking options for all products.
* Fix - Wrong price calculation with prices excluding taxes.
* Fix - Variable products - Display booking price even if all variations have the same price.
* Fix - Calculated price with WooCommerce Product Add-ons.
* Fix - Prevent adding product to cart if there was an error before dates check (like a required WooCommerce Product Add-Ons field not set).
* Improved - Reports table.
* Improved - Notices.
* Improved - Better way to load minified scripts.

= 1.6.1 =

* Fix - CSS generation.
* Fix - disabled link on the add-ons page.

= 1.6 =

/!\ You might have to check variable products after this update. Backward compatibility should be ok, but you might have to check the "Bookable" checkbox again.

* Fix - Hook when saving plugin settings is now triggered when actually saving plugin settings.
* Fix - New way to generate and minify CSS. The old one was causing issues, especially with multisites.
* Fix - Calendars CSS, causing issues and conflicting with themes.
* Add - Possibility to manage booking at parent product level for variable products.
* Add - Add-ons page on the admin.
* Add - 'easy_booking_enqueue_additional_scripts' hook to enqueue scripts before the main pickadate script.
* Add - 'easy_booking_pickadate_dependecies' filter to add dependecies for the main pickadate script.
* Add - Custom Jquery events when initiliazing and setting calendars.
* Tweak - Improved Javascript for better flexibility and performance.

= 1.5.2 =

* Fix - Issue with WordPress 4.2.2 causing an error.

= 1.5.1 =

* Fix - Right to left function deprecated in WordPress 4.2.
* Fix - Backward compatibility with product booking metadata.
* Fix - First available date on start picker when minimum booking duration is set.
* Fix - is_bookable() function for variable products.
* Fix - Removed unnecessary Ajax call when clearing booking session.
* Fix - Input focus which made the calendar pop up when closing and opening window.
* Fix - Generated CSS after saving plugin settings.
* Fix - Registered CSS file for multisites.
* Fix - Price displayed on archive page for bookable products.
* Fix - Displayed price on non-bookable variable products.
* Add - Reports page on the admin.
* Add - "/ night" price when in "nights" mode.
* Add - Remove "/ day" or "/ night" text when variation is not bookable.
* Add - "WooCommerce Product Add-ons" compatibility. Please, refer to the documentation for more information about this : http://herownsweetcode.com/product/woocommerce-easy-booking/#documentation.
* Add - Automatically open second date picker after selecting first date.
* Add - Calendar titles.
* Add - Minifying CSS on-the-fly after saving plugin settings.
* Add - Close button on the calendar.
* Update - Pickadate.js version 3.5.6.
* Remove - WooCommerce Currency Switcher compatibility. Please, refer to the documentation to makes these plugins compatible : http://herownsweetcode.com/product/woocommerce-easy-booking/#documentation.

= 1.5 =

This update contains major changes for variable products. Backwards compatibility should be ok, but still check your variations after updating.

* Add - Variations are now handled individually, instead of inheriting from the parent product.
* Add - Multisite compatibility.
* Add - Right to left CSS, for right to left languages.
* Fix - Wrong price calculation when modifying an order.
* Fix - Security changes.
* Fix - Picker inputs pointer cursor.
* Fix - Added en.js file.
* Fix - Wrong $wpdb calls.
* Fix - Display product price on the right format.
* Tweak - Regenerate CSS only after saving plugin settings.
* Tweak - Improved Inputs CSS.
* Localization - Added Dutch translation.
* Localization - Update French translation.

= 1.4.4 =

* Fix - Javascript error on the notices

= 1.4.3 =

Easy Booking : Availability Check, the add-on to manage stocks and availabilities for WooCommerce Easy Booking is available !
Get it now on http://herownsweetcode.com/product/easy-booking-availability-check/ !

* Add - Admin notices styles.
* Fix - Removed WooCommerce loading gif (which was not loaded, causing Javascript errors).

= 1.4.2 =

* Fix - Issue with WooCommerce 2.3 and variable products.
* Fix - Issue with WooCommerce 2.3 and products.
* Fix - Issue with WooCommerce 2.3 on the order page.
* Fix - Issue when calculating new price and taxes on the order page.
* Fix - Removed minimum start date on the calendar on the product page.
* Add - Another theme for the calendar.
* Add - Hook when saving settings.
* Add - Filter when calculating new price.
* Add - Filter when calculating new price on the order page.
* Add - Filter for the displayed price on the product page.
* Add - Elements for the future Stock Management plugin.
* Removed - Spanish translation.
* Update - French translation.
* Update - Calendar CSS.
* Dev - Refactored code and plugin's structure.

= 1.4.1 =

* Fix - Fixed an error when updating orders.
* Fix - Fixed an error when adding a normal product to cart.
* Add - Spanish translation.
* Add - Display base price for one day on the product page.
* Add - Added an option to set the first available date.
* Update - French translation.

= 1.4 =

* Add - Option to set a minimum and a maximum booking duration for each product.
* Add - Possibility to change booking dates on the order page.
* Add - Possibility to add booking products on the order page.
* Add - en_GB translation file for the calendar.
* Add - WooCommerce Currency Switcher Compatibility
* Fix - Timezone issue with the datepicker.
* Fix - Prevent adding a product to the cart after clicking the "clear" button on the calendar.
* Fix - Incorrect selected dates with keyboard.
* Fix - Wrong price displayed when "Price excluding tax" is set on the product page.

= 1.3.1 =

* Fixed an issue where products were not added to cart if the user was not logged in.

= 1.3 =

#### This update has a lot of modifications, please do not hesitate to tell me if it's not working on the support forum here https://wordpress.org/support/plugin/woocommerce-easy-booking-system.

* Disabled dates before first date and dates after second dates, preventing users to select invalid dates
* Prevent users to select the same date in "nights" mode
* Fixed an error in the calculation price for one day in "days" mode
* Prevent product add to cart if one or both dates are missing
* Changed the way selected dates were set (old : post meta, new : session) so it doesn't affect the product itself
* Updated and cleaned Ajax requests
* Added a few things for the future stock management plugin
* Corrected an error in the French translation
* Added US translation for pickadate.js

= 1.2.2 =
* You can now choose whether to calculate the final price depending on number of days or number of nights.

= 1.2.1 =
* Changed the way CSS was added
* Security update

= 1.2 =
* The calendar is now fully customizable !
* Fixed an issue with variable products' sale price
* Added filters to easily change picker form
* Security updates
* Scripts updates
* Updated French translation

= 1.1 =
* Fixed a few issues
* WooCommerce EBS now works with variable products

= 1.0.5 =
* Fixed issues with WooCommerce 2.2

= 1.0.4 =
* Added price format
* Updated French translation

= 1.0.3 =
* Fixed an issue where fields were not showing up on product page

= 1.0.2 =
* Fix for WooCommerce 2.1

= 1.0.1 =
* Disabled dates before current day

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 3.0.0 =

This update contains important changes to the database. Please ensure you have sufficient backups before updating and update the database after, using the available tool.

= 2.2.4 =

This update contains important changes to the database. Please make a backup of your website before updating and make sure to update the database after, using the available tool.

= 2.2.0 =

This update contains important changes to the plugin settings. Please make a backup of your website before updating and check/save the plugin settings after.

= 2.0.6 =

Only compatible with Easy Booking: Duration Discounts > 1.7.2.

= 2.0.0 =

Please update the addons if you have any and want to use this new version. Because of the new features added, it is not compatible with older versions.

= 1.9.0 =

This update contains major changes. Make backups and read the changelog before updating. Only compatible with the latest addons versions: Availability Check 1.4, Duration Discounts 1.6, Disable Dates 1.4.