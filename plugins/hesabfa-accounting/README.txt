=== Hesabfa Accounting ===
Contributors: saeedsb, hamidprime, sepehr-najafi
Tags: accounting cloud hesabfa
Requires at least: 5.2
Tested up to: 6.4.3
Requires PHP: 5.6
Stable tag: 2.0.97
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect Hesabfa Online Accounting to WooCommerce.

== Description ==
This plugin helps connect your (online) store to Hesabfa online accounting software. By using this plugin, saving products, contacts, and orders in your store will also save them automatically in your Hesabfa account. Besides that, just after a client pays a bill, the receipt document will be stored in Hesabfa as well. Of course, you have to register your account in Hesabfa first. To do so, visit Hesabfa at the link here www.hesabfa.com and sign up for free. After you signed up and entered your account, choose your business, then in the settings menu/API, you can find the API keys for the business and import them to the plugin settings. Now your module is ready to use.

For more information and a full guide to how to use Hesabfa and WooCommerce Plugin, visit Hesabfa’s website and go to the “Accounting School” menu.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/hesabfa-accounting` directory, or install the hesabfa plugin through the WordPress plugins screen directly.
2. Activate the plugin through the \'Plugins\' screen in WordPress
3. Use the Settings->Hesabfa screen to configure the plugin

== Screenshots ==
1. API setting page
2. Catalog setting page
3. Customers setting page
4. Invoice setting page
5. Payment Methods setting page
6. Import and export setting page
7. Sync setting page
8. Log file

== Changelog ==
= 1.0.0 - 07.03.2020 =
* Initial stable release.

= 1.0.1 - 07.17.2020 =
* Fix invoiceSavePayment date error.
* add select in which order status add Payment and Invoice.
* limit item name length to 100 character.

= 1.0.2 - 07.17.2020 =
* change some translation strings.

= 1.0.3 - 07.19.2020 =
* use getObjectId() function.
* fix API limit request.
* fix update item before add invoice.

= 1.0.4 - 07.22.2020 =
* change 'not set!' to translatable string.
* fix 100 character limit in item name.

= 1.0.5 - 08.01.2020 =
* add a payment method (No need to set) for COD payment.

= 1.0.6 - 08.08.2020 =
* set reference in ReturnSaleInvoice
* add FiscalYear check
* add itemUpdateOpeningQuantity method
* add Return Sale invoice on canceled order status and sync orders
* add Export product opening quantity
* add validEmail function
* delete item when product deleted
* delete contact when customer deleted
* change order reference to order ID
* fix notice messages

= 1.0.7 - 04.10.2020 =
* compatible with product variations
* add ssbhesabfa_db_version option
* fix getObjectId bug

= 1.0.8 - 10.10.2020 =
* fix fiscalYear checker
* fix empty customer name bug
* fix show notice
* add GuestCustomer function
* add getContactCodeByEmail function
* add DebugMode
* fix webhook quantity change bug

= 1.0.9 - 18.10.2020 =
* fix combination price in convert currency
* fix id_attribute define in webhook
* improve lastcheck id checker

= 1.1.1 - 30.10.2020 =
* improve performance (decrease api request)
* check invoiceItems after add/edit/delete invoices
* merge some functions
* add activation status for products and customers
* fix some bugs
* fix postal code character limit
* change API tab position

= 1.1.2 - 02.11.2020 =
* add return sign on SaleInvoice
* fix syncOrders bug
* fix setContact bug
* fix get_phone on Contact Shipping Address

= 1.1.3 - 03.11.2020 =
* add limit to sync order function
* check Shareholder available on ExportProductOpeningQuantity
* improve notices
* remove customer ip on payment description
* fix syncChanges Button

= 1.1.4 - 04.11.2020 =
* use exportOpeningQuantity only one time
* fix product category path
* fix some translations
* export published and private products

= 1.1.5 - 07.11.2020 =
* fix IRR and IRT currency difference
* add ValidationClass for validate Item/Contact/Invoice fields
* add Item code field in Product/Variation
* improve log descriptions
* change Hesabfa logo
* delete Product/Variations in hesabfa when delete in WooCommerce

= 1.1.6 - 08.01.2021 =
* fix set variation bug
* fix API bulk request, Splid to 1000 item per request
* add tax to Freight

= 1.1.7 - 26.02.2021 =
* bug fix: add new item in hesabfa by updating product hesabfa code relation

= 1.2.9 - 26.02.2021 =
* bug fix: product and product variations duplication
* add log tab to settings
* prevent export products and customers if done before

= 1.2.10 - 06.03.2021 =
* bug fix: setting variation full name in hesabfa when it has more than two attributes

= 1.3.11 - 09.03.2021 =
* add update products in hesabfa based on store
* bug fix: price replaced with regular price in product export

= 1.4.11 - 27.03.2021 =
* add sync products manually feature
* add statistics to sync tab page in settings
* new menu, menu moved to main menu bar
* add icon to plugin menu

= 1.5.11 - 05.04.2021 =
* add farsi font 'Iranyekan'
* add icon to settings tab pages
* add loginToken instead of username and password for authentication

= 1.5.12 - 05.04.2021 =
* loginToken bug fixed

= 1.5.13 - 07.04.2021 =
* invoice webhook bug fixed

= 1.6.14 - 12.04.2021 =
* add tips for every action in sync and import export tab pages
* bug fix: set price for some products

= 1.6.17 - 14.04.2021 =
* improve performance of three sections: export products, sync products and opening balance

= 1.6.18 - 17.04.2021 =
* bug fix: webhook call.

= 1.7.19 - 28.04.2021 =
* bug fix: contact country and state code instead of name.
* add progress bar to export, import and sync options.
* improve export, import and sync options by make them ajax and batch.

= 1.7.23 - 08.05.2021 =
* bug fix: converting IRR to IRT non numeric error.
* bug fix: multiple invoice payment receipts.
* bug fix: delete product hook call error.
* bug fix: purchase invoice web hook error.

= 1.7.27 - 19.05.2021 =
* bug fix: minor bug fixed in getProductVariations method.
* update plugin logo and menu logo.
* add some notes and guides to some pages.
* sync changes automatically

= 1.71.29 - 12.06.2021 =
* some bugs fixed.
* add Hesabfa invoice number in order list
* add Hesabfa invoice submit button in order list

= 1.72.29 - 21.06.2021 =
* add business info in api setting tab.
* add a page to show duplicate product codes.

= 1.75.29 - 22.06.2021 =
* Show business expire alert when business is expired.
* Show alert when trying to connect plugin to another business in Hesabfa.
* set Order Payment besides invoice when click on invoice button in order list.

= 1.75.30 - 22.06.2021 =
* Remove plugin activation date error during sync orders.

= 1.75.31 - 23.06.2021 =
* check order and payment status when syncing orders.

= 1.77.32 - 26.06.2021 =
* add progress bar to export customers and export orders.
* bug fix: export base product in variable product.

= 1.77.33 - 27.06.2021 =
* bug fix: import products bug fixed.

= 1.77.34 - 28.06.2021 =
* bug fix: import products bug fixed.

= 1.77.35 - 29.06.2021 =
* import products code improvement.

= 1.77.36 - 03.07.2021 =
* bug fix: convert currency in import products.

= 1.77.37 - 05.07.2021 =
* bug fix: import products bug fixed (duplication of one proudct in Hesabfa).

= 1.78.37 - 01.08.2021 =
* bug fix: fix bugs related to tags in Hesabfa.

= 1.78.38 - 02.08.2021 =
* bug fix: fix bug include file.

= 1.80.38 - 05.08.2021 =
* new interface for manually change product and variations link with hesabfa
and delete link or update price and quantity (in edit product page product data tab).

= 1.84.39 - 12.08.2021 =
* add new settings for product:
    * do not submit product in hesabfa automatically
    * do not update product price in hesabfa by editing in woocommerce
    * do not save barcode in hesabfa by saving product in woocommerce
* change product and its variations stock management status to yes
by clicking update stock and price button in hesabfa tab in product edit page

= 1.85.40 - 14.08.2021 =
* fix bug: update stock management status for product.
* convert farsi numbers to english numbers when saving product to Hesabfa.

= 1.85.41 - 15.08.2021 =
* fix bug: fix UI bug in changing product code and removing product code.

= 1.86.41 - 24.08.2021 =
* Add plugin tutorial video.

= 1.86.42 - 27.08.2021 =
* fix bug: fix a bug in import products.

= 1.87.43 - 21.09.2021 =
* Set invoice project and salesman
* Add customer note to invoice
* Add shipping method to invoice note
* Add an option to prevent changing product category in Hesabfa
* A syntax error fixed

= 1.89.44 - 21.10.2021 =
* Add warehouse option to update products quantity
* Save warehouse receipt after saving invoice
* Add chapters to plugin tutorial video
* Fix 'not entered' customer name problem

= 1.89.45 - 03.11.2021 =
* Add Hesabfa new API Address

= 1.89.47 - 25.12.2021 =
* Add cover for tutorial video
* Fix bug: prevent product update hook call when update product property manually

= 1.90.49 - 03.01.2022 =
* Add ability to link a user to a contact in Hesabfa manually
* Clear product name when import from Hesabfa to not cause problem in unique product link
* set zero quantity for products that not exist in warehouse when synchronizing

= 1.90.52 - 19.02.2022 =
* Product name length limit expanded to 200 characters
* Fix bug: duplication of contact in Hesabfa when it's a guest customer
* Some code refactor and improvement

= 1.90.53 - 21.02.2022 =
* Critical error fixed in set order with guest customer

= 1.91.55 - 17.04.2022 =
* Add billing address 1 and 2 to address field in Hesabfa
* Add customer mobile number in Hesabfa if detectable
* Clear product url by regex when import products from Hesabfa
* Refactor update product price and quantity function to use default woocommerce methods
* Check guest customer existence in Hesabfa by phone or email then add or edit it to prevent duplication.
* Add a bulk action (Submit Invoice in Hesabfa) in orders grid.
* Fix set new quantity (zero quantity) for product bug.

= 1.93.57 - 05.06.2022 =
* set last log id if no changes found when webhook called.
* Add options for updating sale price in settings.
* Clear product name by replacing farsi digits to english digits while importing products to woocommerce.
* Refactor setItemChanges function to improve performance.

= 1.93.58 - 11.06.2022 =
* Fix bug: converting sale price considering site currency.

= 1.93.59 - 15.06.2022 =
* Fix bug: updating new prices from Hesabfa for varieties.

= 2.0.60 - 23.07.2022 =
* adding an option in settings to able user to select whether plugin changes product code in Hesabfa or not
* adding an option in settings to able user to select whether plugin changes contact category in Hesabfa or not
* adding an option in settings to able user to select whether plugin saves contact automatically in Hesabfa or not
* adding an option in settings to able user to select whether plugin saves invoice as draft in Hesabfa or not
* fix bug: remove Invoice-Order link by deleting invoice from Hesabfa
* adding an option to add additional fields to checkout page for customer
* adding an option for Dokan plugin to able user to select which orders save into Hesabfa

= 2.0.61 - 31.07.2022 =
* adding an option to inactive options related to Dokan plugin

= 2.0.63 - 23.10.2022 =
* ux improvement: install plugin fonts only in plugin settings page.
* bug fix: There is no need to update the invoice number, And this method caused an internal error in the Hesabfa.

= 2.0.64 - 12.11.2022 =
* bug fix: fix invoice tax bug.

= 2.0.66 - 21.05.2023 =
* Add hesabfa id to admin product list page.
* Add national code validation.
* Add website validation.
* Add persian log to the log file.

= 2.0.67 - 12.06.2023 =
* bug fixed.

= 2.0.68 - 17.06.2023 =
* add freight as service option.
* add show hesabfa id in products page option.
* persian woocommerce shipping bug fixed.
* add cash payment method.
* add set sale price as discount option.
* codes refactored.
* descriptions added.

= 2.0.70 - 08.07.2023 =
* warehouse receipt service bug fixed
* inventory bug fixed
* add default bank option
* freight zero bug fixed
* update price and quantity bug fixed
* empty additional fields values handled
* font changed

= 2.0.72 - 23.07.2023 =
* daily log history and log features added
* price update after webhook call added
* special sale currency change and quantity bug fixed
* codes optimized and refactored
* descriptions refactored

= 2.0.74 - 31.08.2023 =
* sync products based on woocommerce with ID filter added
* extra setting tab added
* variation special sale bug fixed
* request amounts per batch option added
* manually submit invoice log added

= 2.0.75 - 12.09.2023 =
* bug fix: price bug fixed

= 2.0.76 - 19.09.2023 =
* bug fix: Stock management bug fixed

= 2.0.78 - 15.10.2023 =
* bug fix: special sale currency bug fixed
* bug fix: shipping address bug fixed
* codes refactored

= 2.0.80 - 28.10.2023 =
* unnecessary files removed
* bug fix: fix woocommerce state bug
* persian log removed
* some functions refactored

= 2.0.81 - 30.10.2023 =
* salesman percentage option added

= 2.0.83 - 01.11.2023 =
* api option added

= 2.0.90 - 25.11.2023 =
* Log date format fixed
* Transaction fee percentage added
* Submit Invoice Receipt Cash in Transit feature added

= 2.0.92 - 28.11.2023 =
* Hesabfa Webhook Call fixed

= 2.0.93 - 16.12.2023 =
* GUID for invoice save feature added
* Save Stock Method Changed

= 2.0.95 - 08.01.2024 =
* bug fix: variation bug fixed
* bug fix: address bug fixed

= 2.0.96 - 05.02.2024 =
* snapp pay added to gateways

= 2.0.97 - 02.03.2024 =
* bug fix: postal code bug fixed
* add feature: jaayegah-woocommerce-plugin added and city fixed