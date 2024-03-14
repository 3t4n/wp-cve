=== EU/UK VAT Compliance Assistant for WooCommerce ===
Contributors: DavidAnderson
Requires at least: 4.7
Tested up to: 6.4
Stable tag: 1.29.11
Tags: woocommerce, eu vat, vat compliance, iva, moss
License: GPLv3+
Donate link: https://david.dw-perspective.org.uk/donate

Assists with EU/UK/Norway VAT compliance for WooCommerce, for the VAT regimes that began in 2015 and were extended in 2021), including the (M/I)OSS systems.

== Description ==

= The European/UK VAT (IVA) laws =

Since January 1st 2015, all digital goods (including electronic, telecommunications, software, ebook and broadcast services), and from 1st July 2021 physical goods sold across EU and UK borders have been liable under law to variable VAT (a.k.a. IVA) charged in the country of *purchase*, at the VAT rate of that country (background information: <a href="http://www2.deloitte.com/global/en/pages/tax/articles/eu-2015-place-of-supply-changes-mini-one-stop-shop.html">http://www2.deloitte.com/global/en/pages/tax/articles/eu-2015-place-of-supply-changes-mini-one-stop-shop.html</a>). This can apply even if the seller is not based in the EU or UK. It is accompanied by various auditing/recording requirements.

= How this plugin can take away the pain =

This WooCommerce plugin provides features to assist with EU, UK and/or Norwegian VAT law compliance. Currently, those features include:

- <strong>Identify your customers' locations:</strong> this plugin will record evidence of your customer's location, using their billing or shipping address, and their IP address (via a GeoIP lookup).

- <strong>Evidence is recorded, ready for audit:</strong> full information that was used to calculate VAT and customer location is displayed in the WooCommerce order screen in the back-end.

- <strong>Display prices including correct VAT from the first page:</strong> GeoIP information is also used to show the correct VAT from the first time a customer sees a product. A widget and shortcode are also provided allowing the customer to set their own country.

- <strong>Currency conversions:</strong> Most users (if not everyone) will be required to report VAT information in a specific currency. This may be a different currency from their shop currency. This feature causes conversion rate information to be stored together with the order, at order time. Currently, you can choose from official rates from the European Central Bank (ECB), Danish and Czech National Banks, the Central Bank of the Russian Federation, and HM Customs & Revenue (UK). You can also over-ride the currency and rate-provider on a per-country basis.

- <strong>Entering and maintaining each country's VAT rates:</strong> this plugin assists with entering EU and/or UK VAT rates accurately by supplying a single button to press in your WooCommerce tax rates settings, to add or update rates for all countries (standard or reduced) with one click.

- <strong>Reporting:</strong> Advanced reporting capabilities, allowing you to see all the information needed to make a OSS/MOSS/IOSS (one-stop shop) VAT report. The report is sortable and broken down by country, VAT rate, VAT type (traditional/variable) and order status, and can be exported as a CSV.

- <strong>Forbid vatable sales if any goods have VAT chargeable</strong> - for shop owners for whom VAT compliance is too burdensome, this feature will allow you to forbid customers from configured VAT territories to check-out if they have selected any goods which are subject to VAT (whilst still allowing purchase of other goods, unlike the built-in WooCommerce feature which allows you to forbid check-out from some countries entirely).

- <strong>Central control:</strong> brings all settings, reports and other information into a single centralised location, so that you don't have to deal with items spread all over the WordPress dashboard.

- <strong>Mixed shops:</strong> You can sell goods subject to your chosen territories' VAT under the customer-place-of-supply regulations and any other physical goods which are subject to traditional base-country-based VAT regulations. The plugin supports this via allowing you to identify which tax classes in your WooCommerce configuration are used for customer-place-of-supply items. Products are allocated to the correct country according to which country VAT is payable to.

- <strong>Distinguish VAT from other taxes:</strong> if you are in a jurisdiction where you have to apply other taxes also, then this plugin can correctly distinguish which taxes are payable to which jurisdictions.

- <strong>Add line to invoices:</strong> If VAT was paid on the order, then an extra, configurable line can be added to the footer of the PDF invoice (when using the <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">the free WooCommerce PDF invoices and packing slips plugin</a>, or its premium counterpart).

- <strong>Refund support:</strong> includes information on refunded VAT, on relevant orders.

- <strong>Same prices:</strong> Not strictly a VAT compliance issue (different pricing per-country is not illegal), but this plugin adds an option to enable WooCommerce's hidden support for adjusting pre-tax prices to enable the same post-tax (net) price to apply in all customer locations.

- <strong>Brexit-ready:</strong> The plugin has been audited and appropriately adapted to be usable by both "EU 27" and UK (and other) countries after the expiry of the EU and UK's transitional period (at the end of December 2020), including the ability to report taxes in multiple currencies using different exchange rate providers. Existing users should take the time to go through their existing settings to adapt to their new situation (e.g. remove tax table entries for countries that they are no longer required to remit taxes to; check that they are using the correct exchange rate provider and reporting currency for each tax region that they remit to).

- <strong>WooCommerce high-performance order storage:</strong> This plugin is HPOS-compatible (see: https://woocommerce.com/document/high-performance-order-storage/)

<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">A Premium version is on sale at this link</a>, and currently has these *additional* features ready:

- <strong>VAT-registered buyers can be exempted, and their numbers validated:</strong> a VAT number can be entered at the check-out, and it will be validated (via VIES, HMRC or VatSense). Qualifying customers can then be exempted from VAT on their purchase, and their information recorded. The customer's VAT number will be appended to the billing address where shown (e.g. order summary email, PDF invoices). An extra, configurable line specific to this situation can be added to the footer of the PDF invoice (when using the <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">the free WooCommerce PDF invoices and packing slips plugin</a>).

- <strong>Partial VAT exemption:<strong> make VAT-exemption upon supply of a valid VAT number to only apply to products in tax classes specified by the shop owner (rather than to all products)

- <strong>Optionally allow B2B sales only</strong> - for shop owners who wish to only make sales that are VAT-exempt (i.e. B2B sales only), you can require that any EU and/or UK customers (optionally including or excluding those in your country) enter a valid VAT number at the check-out. (You can have different policies for different VAT regions).

- <strong>Change taxation rules based upon year-to-date sales thresholds:</strong> for shop owners who can or must tax differently based upon their total sales in the year so far (e.g. EU shop owners who can take advantage of a €10,000 threshold for cross-border sales before which they can treat the place of supply as being their own, not the customer's location), you can dynamically treat products as having a different taxation class until this threshold is met.

- <strong>CSV download:</strong> A CSV containing comprehensive information on all orders with VAT data from your configured regions can be downloaded (including full compliance information). Manipulate in your spreadsheet program to make arbitrary calculations.

- <strong>Non-contradictory evidences:</strong> require two non-contradictory evidences of location (if the customer address and GeoIP lookup contradict, then the customer will be asked to self-certify his location, by choosing between them).

- <strong>Show multiple currencies for VAT taxes on PDF invoices produced by <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">the free WooCommerce PDF invoices and packing slips plugin</a></strong> (and on credit notes produced by its Premium counterpart).

- <strong>Support for the official WooCommerce subscriptions extension, and for Subscriptio (a RightPress/CodeCanyon alternative), and Subscriben.</strong>

- <strong>Value-based exemption:</strong> An order can have VAT removed if the order value passes a configured value and is for a specified country. This features was developed to support the UK's 2021 regulations for handling VAT differently on an order depending on whether or not the order passes a £135 threshold value, and then expanded to support multiple rules for different countries and amounts.

<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">Read more about the Premium version of this plugin at this link.</a>

It is believed (but not legally guaranteed), that armed with the above capabilities, a WooCommerce shop owner will be in a position to fulfil the requirements of EU or UK VAT laws: identifying the customer's location and collecting multiple pieces of evidence, applying the correct VAT rate, validating VAT numbers for B2B transactions, and having the data needed to create returns. (If in the EU or UK, then you will also need to make sure that you are issuing your customers with VAT invoices containing the information required in your jurisdiction, via <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">a suitable WooCommerce invoice plugin</a>).

= Footnotes and legalese =

This plugin is supported on, and information in this document is for, WooCommerce 3.8 up to the latest release (i.e. current version; you can still <a href="https://wordpress.org/plugins/woocommerce-eu-vat-compliance/advanced/">download older versions supporting previous WooCommerce release series if you wish</a>). It fetches data on current VAT rates from Amazon S3 (using SSL if possible); or, upon failure to connect to Amazon S3, from https://raw.githubusercontent.com. If your server's firewall does not permit this, then it will use static data contained in the plugin.

Geographical IP lookups are performed via WooCommerce's built-in geo-location features; or, alternatively, if you use CloudFlare, then you can <a href="https://support.cloudflare.com/hc/en-us/articles/200168236-What-does-CloudFlare-IP-Geolocation-do-">activate the CloudFlare feature for sending geographical information</a>. In some situations, these lookups may be performed via the public API at https://ipapi.co - if this is undesirable for you, then add `define('WC_VAT_COMPLIANCE_DO_REMOTE_IP_LOOKUPS', false);` to your wp-config.php file.

Please make sure that you review this plugin's installation instructions and have not missed any important information there.

Please note that, just as with WordPress and its plugins generally (including WooCommerce), this plugin comes with no warranty of any kind and you deploy it entirely at your own risk. Furthermore, nothing in this plugin (including its documentation) constitutes legal or financial or any other kind of advice of any sort. In particular, you remain completely and solely liable for your own compliance with all taxation laws and regulations at all times, including research into what you must comply with. Installing any version of this plugin does not absolve you of any legal liabilities, or transfer any liabilities of any kind to us, and we provide no guarantee that use of this plugin will cover everything that your store needs to be able to do.

Whether you think the EU's treaties with other jurisdictions will lead to success in enforcing the collection of taxes in other jurisdictions is a question for lawyers and potential tax-payers, not for software developers!

Many thanks to Diego Zanella, for various ideas we have swapped whilst working on these issues. Thanks to Dietrich Ayala and other authors, whose NuSOAP library is included under the LGPLv2 licence.

= Other information =

- <a href="https://www.simbahosting.co.uk/s3/shop/">Some other WooCommerce plugins you may be interested in</a>

- This plugin is ready for translations (English, Dutch, Finnish, French and German are currently available), and we would welcome new translations (please post them in the support forum; <a href="https://plugins.svn.wordpress.org/woocommerce-eu-vat-compliance/trunk/languages/">the POT file is here</a>, or you can contact us and ask for a web-based login for our translation website).

== Installation ==

Standard WordPress installation; either:

- Go to the Plugins -> Add New screen in your dashboard and search for this plugin; then install and activate it.

Or

- Upload this plugin's zip file into Plugins -> Add New -> Upload in your dashboard; then activate it.

After installation, you will want to configure this plugin, as follows:

1) If you are selling goods to and are liable to pay taxes in the EU and/or UK for which the VAT rate should depend upon the buyer's country (i.e. the place of supply is deemed as the customer's location), then go to WooCommerce -> Settings -> Tax -> Standard Rates, and press the "Add / Update VAT Rates", making sure that "Standard" is selected in the rates drop-down.

2) If you have products that are liable for VAT at a reduced rate, then also go to WooCommerce -> Settings -> Tax -> Reduced Rate Rates, and press the "Add / Update VAT Rates", making sure that "Reduced" is selected in the rates drop-down.

You must remember, of course, to make sure that a) your WooCommerce installation is set up to apply taxes to your sales (WooCommerce -> Settings -> Tax) and b) that your products are placed in the correct tax class (choose "Products" from the WordPress dashboard menu).

== Frequently Asked Questions ==

= Is this plugin only for shops based on the EU? Or in the UK? =

It is suitable for shops in either, or neither, of the above. That is to say, you can configure the settings appropriately for wherever you are based.

= How can I display a widget allowing a visitor to pre-select their country, when viewing products (and thus set VAT accordingly)? =

There is a widget for this; so, look in your dashboard, in Appearance -> Widgets. You can also display it anywhere in page content, using a shortcode, shown here with the default values for the available parameters: [euvat_country_selector include_notaxes="1" classes="" include_which_countries="all"]. The 'include_notaxes' parameter controls whether to include a "I am not liable to VAT" option. The 'classes' parameter allows you to add CSS classes to the resulting container. The 'include_which_countries' parameter can take the values "all", "shipping" (those countries that your store ships to, as indicated by the WooCommerce settings) or "selling" (those that it sells to), to indicate which countries should be included in the list. N.B. The "eu_" in the shortcode name is due to the plugin's history; it does not mean that only EU countries are choosable.

= I want to make everyone pay the same prices, regardless of VAT =

This is not strictly an EU/UK VAT compliance issue, and as such, does not come under the strict remit of this plugin. (Suggestions that can be found on the Internet that charging different prices in difference countries breaks non-discrimination law have no basis in fact at the time of writing). However, WooCommerce does include *experimental* support for this (see: <a href="https://github.com/woocommerce/woocommerce/wiki/How-Taxes-Work-in-WooCommerce#prices-including-tax---experimental-behavior">https://github.com/woocommerce/woocommerce/wiki/How-Taxes-Work-in-WooCommerce#prices-including-tax---experimental-behavior</a>), and so we have provided an option in the settings to tell WooCommerce to turn this on.</a>

== Changelog ==

= 1.29.11 - 2023-01-04 =

* TWEAK: Update VAT rates for Luxembourg and Estonia

= 1.29.10 - 2023-12-29 =

* TWEAK: Add missing context check before calling wc_add_notice() when reporting on  VAT number validity in the back-end with too-short numbers
* TWEAK: Update updater library version

= 1.29.8 - 2023-12-13 =

* TWEAK: Some minor refactoring within the VAT number and shortcode checkout classes to enable future improvements
* TWEAK: Move description of HPOS compatibility to the correct part of the readme.txt to avoid ambiguity about which versions this applied to (it applies to both free and paid versions)
* TWEAK: Prevent a PHP notice being logged if the add_meta_boxes is called from the front-end (e.g. UpdraftCentral)
* TWEAK: Update updater library to current series

= 1.29.7 - 2023-11-03 =

* FIX: HMRC (UK) again moved the URL at which they provide their official exchange rate XML data

= 1.29.6 - 2023-10-05 =

* FIX: Fix a regression in the 1.29.2 release which could improperly prevent the VAT number field being displayed on the checkout

= 1.29.3 - 2023-10-03 =

* FIX: Fix a regression in reporting in yesterday's 1.29.2 release which could result in incomplete data being used to build the report
* TWEAK: Adjust how reporting queries are logged to make them easier to read

= 1.29.2 - 2023-10-02 =

* REFACTOR: All JavaScript for the checkout is now abstracted into a static (and cacheable) file, instead of being generated inline. Developers who have written custom PHP code should test their code carefully, and adjust where needed.
* TWEAK: Add a constant WC_VAT_REPORTING_LOG_QUERIES to aid with report generation debugging
* TWEAK: Increase speed of report generation by not running a final query if the previous query result set was between empty and matching the full page size
* TWEAK: The filter for the checkout message stating that the VAT number is not long enough has been changed to wc_vat_msg_not_long_enough (as the previous filter name was unintentionally duplicated)
* TWEAK: Mark as requiring WP 5.0+; nothing explicit has been done to remove compatibility with earlier versions, but this is the updated support requirements)
* TWEAK: Fix invalid nesting of HTML tags on the checkout

= 1.29.1 - 2023-09-30 =

* FIX: Fix a regression in 1.29.0 that caused the self-certify radios to display incorrectly

= 1.29.0 - 2023-09-28 =

* FEATURE: Provide an option to always use the customer IP address for complete determination of the taxation country - potentially suitable for shops that are subject to special provisions only requiring one evidence of location (and who do not with to use the billing country)
* TWEAK: Remove unwarranted specificity from the default message when a cart is forbidden
* TWEAK: Add $cart_exempt parameter to wc_vat_woocommerce_product_get_tax_class_zero_rate_class filter
* TWEAK: Add filter wc_vat_exempt_shipping_rate_taxes to allow modification of empty final output of WC_EU_VAT_Compliance_VAT_Number::woocommerce_shipping_rate_taxes()
* TWEAK: Declare class variables explicitly because of deprecation of dynamic properties on PHP 8.2
* TWEAK: Declare class variables in nusoap library explicitly because of deprecation of dynamic properties on PHP 8.2
* TWEAK: Do not allow a default country (from the customer object or store) to be used during checkout processing
* TWEAK: Tweak settings tip for VAT Number message
* TWEAK: Replaced strftime(), which is deprecated since PHP 8.1
* TWEAK: Supply any checkout VAT number through the woocommerce_checkout_posted_data filter

= 1.28.2 - 2023-06-14 =

* FIX: Correct an SQL query used as an update task on HPOS order meta items
* TWEAK: Allow HPOS status to be checked during plugins_loaded

= 1.28.1 - 2023-06-05 =

* FIX: When the shop was based in a VAT region for which the settings did not indicate that VAT accounting should take place (which is an erroneous setting; all countries require VAT accounting to take place in their own country), B2B VAT exemptions were not performed correctly for cross-border sales into a different VAT region.

= 1.28.0 - 2023-05-29 =

* FEATURE: Supports WooCommerce high-performance order storage (https://woocommerce.com/document/high-performance-order-storage/). Since HPOS is a large and invasive change, we recommend you test your site on it carefully. Developers should note the changelog items below.
* FEATURE: Support currency conversions in the PDF invoice templates of PDF Invoices & Packing Slips for WooCommerce - Premium Templates (WP Overnight) - https://wpovernight.com/downloads/woocommerce-pdf-invoices-packing-slips-premium-templates/
* DEVELOPERS: (Potentially breaking change): The sixth parameter to the filter woocommerce_vat_compliance_get_report_sql has changed - it is now an array - and the seventh has been removed entirely. If you had written custom code that uses this filter, then you should review and test it to ensure that it still works.
* DEVELOPERS: Because of adding compatibility for WooCommerce High Performance Order Storage, the SQL query received by each of the woocommerce_vat_compliance_get_refunds_sql and  woocommerce_vat_compliance_get_report_sql filters is now different. If using these filters, you will need to re-work them for HPOS compatibility.
* DEVELOPERS: The filter wc_eu_vat_compliance_report_meta_fields is deprecated, and has been replaced with the filter wc_eu_vat_compliance_report_extra_meta_fields. Please adapt your code to use the new filter; the old one will be removed in a future release. See: https://www.simbahosting.co.uk/s3/faqs/when-downloading-a-detailed-csv-how-can-i-add-an-extra-column/

= 1.27.27 - 2023-05-06 =

* FEATURE: If a VAT Sense API key has been added (Premium version), then VAT Sense will be used as the preferred source for up-to-date VAT rate information
* TWEAK: Update Luxembourg's current VAT rate (should also be pulled from the Internet by existing installs before updating to this release)
* TWEAK: Remove no-longer maintained Aelia data-source for VAT rates
* TWEAK: Provide a filter woocommerce_vat_widget_without_vat_on_address_save_with_vat_number to allow site owners to turn off the default behaviour of showing prices without VAT when the customer saves a VAT number in their profile.
* TWEAK: Adjust behaviour of setting the visitor's preference to show prices without VAT upon saving an address with a VAT number to not do so if the customer was in the store base country and is not deducting VAT from such customers

= 1.27.26 - 2023-04-07 =

* FIX: The "Order number" column in the detailed CSV download was empty (since 1.27.21); it now has this capitalisation instead of "Order Number", so if any developers have written customer PHP code to target this column, they will need to adjust
* FIX: If the site was running in a non-English language, then (since 1.27.21) in the detailed CSV spreadsheet (Premium feature) any columns which had translated headings would have empty values
* TWEAK: Handle Cloudflare's "T1" and "XX" pseudo-country codes (recognise as not referring to any actual country)

= 1.27.25 - 2023-04-03 =

* TWEAK: Get the selected drop-down country over AJAX, in order to make the drop-down country selector compatible with per-country page-caching.

= 1.27.24 - 2023-03-29 =

* FIX: When the store's base country is in one of the UK/Isle of Man, France/Monaco pairs, then a transaction should be subject to base country rules if the taxation country is the other member of the pair
* TWEAK: Prevent a potential PHP notice when running under cron

= 1.27.23 - 2023-03-21 =

* FEATURE: When checkout orders are recorded with the final taxable address determined with the aid of self-certification, this information is now recorded explicitly and displayed in the dashboard order page metabox (previously it as implicit)
* TWEAK: Use data from WC's get_posted_data() method rather than $_POST directly in a couple of places
* TWEAK: Remove superceded internal "deduction setting" code
* TWEAK: Add wc_vat_compliance_log_no_line_total filter to suppress potentially unwanted logging
* TWEAK: Update Premium version's updater library to latest version
* TWEAK: Settings in the "Other WooCommerce tax options potentially relevant for VAT compliance" section are now read-only, to avoid user confusion about which component implements the settings

= 1.27.22 - 2023-02-25 =

* FIX: Country pre-selection was erroneously not using GeoIP lookups if the legacy plugin option was inactivate but the WooCommerce core option was active
* FIX: Fix regression in 1.27.21 which resulted in zero-rating no longer being applied for partial exemptions and value-based exemptions
* TWEAK: Update .pot file
* TWEAK: Move registration of action that outputs VAT number field until init, to allow shop owners to access the associated filters in child theme code
* TWEAK: Add page output time to debugging footer when activated
* TWEAK: In one specific situation, the debugging footer could include incorrect information on how the visitor country was determined
* TWEAK: Avoid unintended use of deprecated dynamic property in WC_VAT_Compliance_Preselect_Country class

= 1.27.21 - 2023-02-07 =

* TWEAK: Add filter wc_vat_woocommerce_product_get_tax_class_zero_rate_class to allow a different tax class to the WooCommerce default one to be used as the designated zero-rate class.
* TWEAK: Rename internal "Date" column in detailed CSV download to "Date (local)" to match the spreadsheet column heading; any developers who had written custom PHP code targetting this column will need to adjust their code.
* TWEAK: Filters related to the detailed CSV download did not allow removal of columns from the base spreadsheet; now they do.
* TWEAK: Filters related to the detailed CSV download now allow columns to be re-ordered.
* TWEAK: wc_eu_vat_compliance_csv_data_entries filter now has an extra parameter available containing order details after currency conversion

= 1.27.20 - 2023-01-20 =

* FIX: Prevent a potential infinite recursion (since 1.27.15) on the cart page when ascertaining the taxation country
* FIX: Fix a regression (since 1.27.17) which could prevent the customer-chosen conflict resolution choice from takin effect
* TWEAK: Log a message if a cart item has no line_item_total key.

= 1.27.18 - 2023-01-11 =

* FIX: Work around a problem in 1.27.17 when renewing subscription orders that were created when the plugin wasn't active caused by a function needed by WooCommerce's tax methods only being loaded on the front-end.

= 1.27.17 - 2023-01-09 =

* TWEAK: When creating subscription renewal orders, set the current WooCommerce customer to the order customer, to work around the WooCommerce filter woocommerce_customer_taxable_address not passing any customer identifier

= 1.27.16 - 2022-12-01 =

* FEATURE: Add support for exchange rates from the Romanian National Bank

= 1.27.15 - 2022-11-26 =

* TWEAK: Always run the country calculation during the woocommerce_checkout_process action, to fix a site with a custom checkout flow where session retrieval sometimes failed at this stage
* TWEAK: Do not allow use of request variable during checkout processing
* TWEAK: Allow woocommerce_admin_billing_fields method to be called from a front-end context, to avoid a problem caused by a third-party extension doing that
* TWEAK: Change how saving of meta-data is performed in woocommerce_process_shop_order_meta hook to prevent conflicting with an issue in a currency switcher plugin.

= 1.27.14 - 2022-10-28 =

* FIX: The last release introduced an accidental requirement for PHP 7.3+

= 1.27.13 - 2022-10-26 =

* TRANSLATION: Updated nl_NL translation, thanks to Robin De Winter

= 1.27.12 - 2022-10-19 =

* TWEAK: Fine-tune VatSense result handling for Norwegian VAT numbers
* TWEAK: Add filter woocommerce_vat_show_prices_without_vat_on_login allowing site owner to not cause prices to be shown without VAT upon login even if the customer stored a VAT number without profile
* TWEAK: Prevent WooCommerce core raising a PHP notice due to no CSS class on user profile checkbox
* TWEAK: Update update checker library to latest version
* TWEAK: Add filters woocommerce_vat_compliance_get_items_sql, woocommerce_vat_compliance_get_refunds_sql and woocommerce_vat_compliance_get_report_sql to allow modification of the SQL used to fetch relevant data for building reports
* TWEAK: Add filter wp_ajax_wceuvat_vatnumber_response to the AJAX action response for users who want to adapt messages returned from the VAT-number checking service
* TWEAK: If a subscription renewal order is created where the original order does not have previous information present for the taxable country, then an attempt to add this information will be made.
* TWEAK: Move HTML entity outside of translation string and regenerate POT file to correct previous error

= 1.27.11 - 2022-09-24 =

* FEATURE: When a new order is created in the WooCommerce admin dashboard screen, any VAT number entered will be validated; if valid, the customer will be set as tax-exempt (VAT number validation is a Premium feature).
* TWEAK: The "Load billing address" link when editing an order manually will import the customer's saved VAT number
* TWEAK: Settings export now includes WooCommerce base country
* TWEAK: Remove debugging lines related to base rate fetching that were accidentally left in a previous version
* TWEAK: Also display invalid entered VAT numbers in an order's VAT compliance meta-box (including the information that it was invalid)
* TWEAK: Include the "standard rates" tax table in the list of quick links in the "Tax tables" section

= 1.27.10 - 2022-09-08 =

* TWEAK: Update .pot file
* TWEAK: Add "Moms" to the list of labels used for VAT taxes
* TWEAK: Adjust the description for the list of VAT taxes setting to reflect its current use, improve the layout and add a link to the tax tables for clarity
* TWEAK: Update the link to the official EU list of VAT rates
* TWEAK: Update to latest version of the plugin updater library (Premium)

= 1.27.9 - 2022-09-05 =

* FIX: After adding a new VAT region over-ride to the settings and saving it, attempts to remove it failed

= 1.27.8 - 2022-09-03 =

* FIX: At least one third-party PayPal express checkout needed extra code to retain the VAT number and other details in the session for the order to record it at the end of the off-suite flow
* TWEAK: Removed debugging log message included in 1.27.7

= 1.27.7 - 2022-08-27 =

* TWEAK: Run the woocommerce_get_price_suffix filter when DOING_AJAX is true also (for plugins that fetch front-end content snippets over AJAX)
* TWEAK: Lower priority of a couple of hooks which record audit data, so that they are more likely to run successfully if there is another plugin on the site which uses the same hooks and causes a fatal error before our code got to run

= 1.27.6 - 2022-07-04 =

* TWEAK: Adjust default filename for saving short summary reports
* TWEAK: Add new filters (wc_vat_compliance_currency_converted_order_data_use_order_saved_data, wc_vat_compliance_currency_converted_order_data_use_conversion_time, wc_vat_compliance_currency_converted_order_data_use_rate, wc_vat_compliance_currency_converted_order_data_use_provider) which allow developers to modify the date used for currency conversion lookups for reporting data.

= 1.27.5 - 2022-06-20 =

* FIX: Fix incorrect JSON parsing of the new IP address lookups from https://ipapi.co introduced in 1.27.4

= 1.27.4 - 2022-06-18 =

* FEATURE: Handle admin-area editing of taxes - save the new tax information if updates are applied in the admin area (and also save the original non-admin information for audit purposes)
* FEATURE: If WooCommerce has saved an IP address for the order, but the plugin (for whatever reason - e.g. order created through custom means) has not recorded a GeoIP lookup for the IP address, then this will now be performed if viewing the order's dashboard page. These lookups may be performed via the public API at https://ipapi.co - if this is undesirable for you, then add `define('WC_VAT_COMPLIANCE_DO_REMOTE_IP_LOOKUPS', false);` to your wp-config.php file.
* TWEAK: Adjust the "No further information recorded" message for greater precision.
* TWEAK: Add the WC_Order to the parameters of the wc_eu_vat_compliance_meta_country_info filter

= 1.27.3 - 2022-06-11 =

* TWEAK: Introduce filter wc_vat_order_reporting_currency, useful for providing reports in other currencies
* TWEAK: Prevent type error when taxation country is set but an empty string
* TWEAK: Remove no-longer-relevant help message from WooCommerce's "Default Customer Address:" setting

= 1.27.2 - 2022-05-10 =

* FIX: Fix a logic error in what country was saved in the session upon order review when taxation was based on shipping country and it differed from the billing country
* TWEAK: Suppress premature "The VAT number entered is not long enough to be valid for the chosen country" message that appears in recent WC versions
* TWEAK: Upgraded the .pot file
* TWEAK: Added filter wc_vat_compliance_include_order_in_report upon user request to allow programmer-controlled removal of orders from VAT reports

= 1.27.0 - 2022-04-26 =

* FEATURE: The capability to use the plugin's GeoIP resolution on all relevant pages is now exposed as an option, defaulting to off for new installs (since WooCommerce now has its own GeoIP resolution), and on for upgrades (reflecting the previous default behaviour). As part of the related changes, the country-selection widget and/or dropdown can now be used without having to accept the plugin's own GeoIP resolution.
* TWEAK: The class WC_EU_VAT_Compliance_Preselect_Country has been renamed to WC_VAT_Compliance_Preselect_Country; if you had hand-written PHP code that interacted with it, you will want to review it
* TWEAK: The constant WC_EU_VAT_LOAD_ALL_CLASSES has been renamed to WC_VAT_LOAD_ALL_CLASSES
* TWEAK: Mark as requiring WP 4.7+; nothing explicit has been done to remove compatibility with earlier versions, but this is the updated support requirements)
* TWEAK: Mark as supporting WooCommerce 3.8+ (nothing has been changed to remove WC 3.5-3.7 support, but this is our official support requirement)

= 1.26.6 - 2022-03-29 =

* TWEAK: Add "Aelia EU VAT Assistant" as description of source of IP/country data when the data stored so indicates
* TWEAK: The debugging constant WC_EU_VAT_DEBUG has been replaced with WC_VAT_DEBUG
* TWEAK: Do not register certain hooks related to country pre-selection in an admin context

= 1.26.5 - 2022-03-15 =

* FEATURE: Any customer-entered VAT number (available in the Premium version) is now editable in the 'billing address' section of the WooCommerce order admin screen

= 1.26.4 - 2022-03-09 =

* TWEAK: Change columns headings "VAT is variable" to the more precise "VAT is based on customer location" in detailed CSV spreadsheet download
* TWEAK: Make sure that the first parameter to round() is converted to a float, to prevent PHP 8.0+ type errors
* TWEAK: Update the detection of buggy third-party plugins calling woocommerce_checkout_order_processed with insufficient actions for PHP 8.0 compliance

= 1.26.3 - 2022-02-05 =

* TWEAK: Change the multi-select HTML control for forbidding VAT checkout in specified regions into a list of checkboxes, to eliminate support requests from users unfamiliar with multi-selects and unable to succeed even with the attached help-text. Also improves UI consistency with the controls used elsewhere.
* TWEAK: Update to latest version of the paid plugin updater library

= 1.26.2 - 2022-01-25 =

* FIX: Fix a couple of cases in which it was possible for the VAT-exemption tatus to change without the checkout summary updating
* TWEAK: Update to latest version of the paid plugin updater library

= 1.26.1 - 2022-01-20 =

* FIX: Incorrect checking of the results of jQuery.inArray() led to mishandling of a case for deciding whether to display self-certification fields (regression)
* TWEAK: If WP_DEBUG is active, then show more information about failed VAT numbers at the checkout

= 1.26.0 - 2022-01-07 =

* FEATURE: Add a new possibility, "United Kingdom (excluding Northern Ireland)" to the list of territories selectable for the "VAT exemption based on value" feature, allowing correct implementation of cross-border sales with value exemptions to stores selling from the EU to both Northern Ireland and the remainder of the UK.
* TWEAK: Cart value-based VAT exemptions are not henceforth applied during product listing, so that VAT display/non-display on products is not dependent upon the current cart (even though the eventual cart and checkout values will be). It is judged that it is potentially confusing to have VAT display on listings change in this way. This behaviour can be controlled using the filter wc_vat_product_tax_class_pass_through_during_filters.
* TWEAK: Remove debugging logging line
* TWEAK: Do not crash if wc_euvat_compliance_wpo_wcpdf_footer is called without an order number having been set (log a PHP error and pass through)
* TWEAK: Improve detection of current order in wpo_wcpdf_footer_text filter
* TWEAK: Avoid using deprecated array access method on WC_Order_Item_Tax objects
* TWEAK: Mark as compatible with WP 5.9

= 1.25.11 - 2021-12-02 =

* TWEAK: Change default settings export filename to remove reference to plugin's historically narrower scope
* TWEAK: Adjustment to prevent a couple of possible PHP notices when looking up a region

= 1.25.10 - 2021-11-01 =

* FIX: Fix wrong variable reference when looking up VAT region which could cause an execution error in one combination of circumstances

= 1.25.8 - 2021-10-30 =

* FIX: When the user had chosen partial VAT exemptions (Premium feature) (i.e. some tax classes and not others), exemption was not being applied to relevant shipping cost classes
* TWEAK: Update updater library in Premium version to latest version
* TWEAK: Add a filter wc_vat_start_session_for_geoip to allow store owners to disable sessions for not-logged-in users before the checkout

= 1.25.7 - 2021-09-16 =

* TWEAK: Upon login, if the user has a saved billing/shipping country and WooCommerce settings are to use the same for tax calculations, then set this in the session as the chosen taxation country
* TWEAK: This update renames an internal session variable to remove the historic "eu_" prefix. Consequently, when you update, customers who had begun sessions in your store and had chosen a VAT country (e.g. via a widget or shortcode that you added) will have that choice forgotten.
* TWEAK: Correct the 'You can see your year-to-date sales in the "Reports" tab' link in the 'Tax class translations' settings section
* TWEAK: Prevent PHP coding notices if the cart contents are invalid

= 1.25.6 - 2021-09-07 =

* FIX: Saving settings after deleting all previously created and saved tax class translation rules did not remove them
* FIX: When getting the VAT region for an order refund for a credit note (when integrating with WP Overnight's professional version), identify using the parent order

= 1.25.5 - 2021-08-17 =

* FIX: If the order and reporting currencies for an order were the same, then the "VAT refunded (X, reporting currency)" column in the detailed CSV download was zero
* TWEAK: Remove the not necessarily pertinent "eu-" prefix from the default filename for downloaded detailed CSV files

= 1.25.4 - 2021-08-10 =

* TWEAK: When displaying meta-box information about value-based exemptions, ensure that currency symbols are displayed correctly (i.e. don't get double-encoded), in case there was an extension installed that did not follow the convention of get_woocommerce_currency_symbol() in WC core.

= 1.25.3 - 2021-08-04 =

* FEATURE: In the PDF invoice integration, when adding B2B/reverse charge text, you can include that is displayed only for a B2B (via VAT number) exemption, using the format {b2b_exemption}conditional content{/b2b_exemption}.
* FIX: Local pickup methods are now handled correctly in terms of VAT number entry on the checkout page
* FIX: At some point from 1.24.0, there was a regression in the display of the customer VAT number in invoice footers for at least some stores* TWEAK: Update bundled file with Slovenian e-book reduced VAT rate (5%). (N.B. This data was previously updated online - the bundled file is only used if the online version cannot be fetched).
* TWEAK: If you were using the constant WC_EU_VAT_NOCOUNTRYPRESELECT, then this is now deprecated; you should switch to using WC_VAT_NO_COUNTRY_PRESELECT
* TWEAK: The constant WC_EU_VAT_Compliance_Disable_Append_To_Billing has been renamed to WC_VAT_COMPLIANCE_DISABLE_APPEND_TO_BILLING

= 1.25.2 - 2021-07-01 =

* FEATURE: Added experimental support for support for Norway (which is not a part of the EU). VAT number lookup support is via vatsense.com. Users can choose their preferred combination of zones (EU/UK/Norway)
* FEATURE: Add support for VAT exemption based upon cost of a single item, rather than all items (used in the Norwegian VAT system)
* FEATURE: Add support for currency conversions via the rates provided by the Norwegian central bank (Norges bank)
* TWEAK: Add link to min/max plugin for users who want to forbid orders over a certain value to avoid the need for compliance
* TWEAK: Update bundled German reduced VAT rate
* TWEAK: Remove unwanted storing of VIES output in temporary folder

= 1.24.0 - 2021-06-11 =

* FEATURE: Handling of the "Invoice footer text (B2B exemption)" has changed; it is now used for value-and-destination based exemptions (e.g. orders above £135 to the UK), if you have configured any (if you have not, then nothing has changed). You can now also include text that is displayed only for a value/destination-based exemption, using the format {value_exemption}conditional content{/value_exemption}.
* FIX: Update nusoap library (used for VIES lookups) for PHP 8 compatibility
* FIX: Fix a bug which resulted in failure to calculate the order's VAT region for including the store VAT number in an invoice footer for a VAT-exempt order
* TWEAK: Increase cacheing time for main WSDL download from https://ec.europa.eu

= 1.23.2 - 2021-06-09 =

* FIX: Fix a regression with saving the VAT exemption currency selection (Premium version)
* TWEAK: Introduce a constant WOOCOMMERCE_VAT_PARSE_SUFFIXES_ALSO_WHEN_VARIABLE which, if defined as true, will allow a price suffix to be displayed even on variable products with dynamic elements (which by default WooCommerce disables)
* TWEAK: Remove debugging log entry included in 1.23.1

= 1.23.1 - 2021-05-19 =

* TWEAK: Catch an error thrown by wc_get_chosen_shipping_method_ids() when it tries to access a non-existent session

= 1.23.0 - 2021-05-18 =

* FEATURE: Partial VAT exemption (Premium version) - make VAT exemption upon supply of a valid VAT number to only apply to products in tax classes specified by the shop owner (rather than to all products)
* FIX: Prevent fatal error (recent regression) in admin product display if no WC Customer object has been initialised.
* TWEAK: In the 'Tax tables' accordion, list all existing tax tables (not just standard ones)

= 1.22.1 - 2021-05-17 =

* FIX: Reporting currency conversions were not being made on PDF invoices when the taxable country and reporting country differed and a custom converson currency override existed.
* TWEAK: Remove an inconsistent use of italics in the settings.
* TWEAK: Because of the number of WooCommerce PDF invoicing plugins with similar names, add a hyperlink to the intended plugin.
* TWEAK: Tweak a label in the VAT meta info box and a couple of labels in the settings for greater precision, given the possibility for country of supply to differ from the customer's taxable address country.

= 1.22.0 - 2021-05-13 =

* FEATURE: The 'reports' screen now allows the user to choose whether they wish to aggregate the reports by customer taxation country or the country that payment is due to (which can differ in the case of cross-border local pickups and cross-border threshold schemes)
* FEATURE: "This year" has been added as a report option, to show the year-to-date figures; useful when operating under a regime which applies an annual threshold after which the rules change
* FEATURE: (Premium version): Change product taxation rules based upon year-to-date sales thresholds - allows automated handling and compliance with July 2021+ EU cross-border trading thresholds (see: https://www.simbahosting.co.uk/s3/faqs/please-explain-the-tax-class-translations-feature-to-me/)
* TWEAK: All use of "digital" terminology has been updated in light of the coming new EU VAT regulations which will henceforth apply "place of supply deemed as the customer location" to non-digital goods also.
* TWEAK: A new "readiness test" has been added, checking for the existence of the default "Zero Rates" tax class. (It will exist unless the store owner manually deleted it).
* TWEAK: The filter wc_eu_vat_cart_is_vat_exempt has been renamed to wc_vat_cart_has_value_based_exemption
* TWEAK: Replace one use of 'zone' with the otherwise-used 'region'

= 1.21.0 - 2021-05-07 =

* FEATURE: It is now possible to configure multiple "VAT exemption based on value" rules for different countries, calculated in different currencies, as more countries consider adding such rules.
* FEATURE: The VAT information meta-box in the order screen will now indicate if an order was exempted because of a destination/value-based rule.
* TWEAK: Country selector dropdown widgets for the "VAT exemption based on value" feature are now enhanced using WooCommerce's enhanced select library
* TWEAK: The plugin's settings are no longer appended to the WooCommerce 'tax' settings tab; instead, just a link to the plugin's own settings page is appended. This reduces the maintenance burden and potential for errors (the settings now appear only in one place).
* TWEAK: The redundant wc_eu_vat_compliance_vat_paid filter has been eliminated; and the remaining filter wc_eu_vat_compliance_get_vat_paid has been replaced with wc_vat_compliance_get_vat_paid; if using either of these in custom PHP code, you should update it.

= 1.20.6 - 2021-05-01 =

* TWEAK: HMRC (UK) no longer require OAuth authentication for VAT number lookups
* TWEAK: Introduce filter wc_vat_validate_vat_number to allow over-riding of VAT number lookup results (Premium)

= 1.20.5 - 2021-04-13 =

* TWEAK: Re-factor code which outputs "VAT exemption based on value" settings in view of future changes
* TWEAK: Re-factor code which processes "VAT exemption based on value" rules when evaluating the cart

= 1.20.3 - 2021-04-08 =

* TWEAK: Use CSS to hide the "(Optional)" label that WooCommerce adds to the VAT number field (whether it is optional depends on several things and the label causes confusion)
* TWEAK: Suppress unwanted debugging notices in tools that ignore @-markers
* TWEAK: Mark as supporting WooCommerce 3.5 - 5.2 (nothing has been changed to remove WC 3.4 support, but this is our official support requirement)

= 1.20.2 - 2021-03-24 =

* FIX: Regression in the experimental "VAT exemption based on value" feature which stopped it from matching configured countries

= 1.20.1 - 2021-03-22 =

* TWEAK: Strip slashes correctly when saving settings

= 1.20.0 - 2021-03-18 =

* FEATURE: VAT numbers (both UK and EU) can be looked up via the VAT Sense API. This allows a fall-back option if there is a problem with VIES and/or HMRC (e.g. no network route; or HMRC taking too long to provide a VAT number).
* FEATURE: Internal VAT number lookup code can now use subsequent validators that handle the same country's numbers if previous lookups are not decisive
* TWEAK: GB VAT numbers beginning with "XI" can now be validated with any EU/UK service
* TWEAK: Drop requirement for php-curl for HMRC API interactions
* TWEAK: Send errors up from HMRC HTTP API layer so that they are handled more precisely
* TWEAK: Display validation failure error text details on front end in more circumstances

= 1.19.7 - 2021-03-16 =

* TWEAK: For any GB VAT numbers beginning with "XI", force validation via VIES (which does not require any further authentication, so will work for users who haven't configured HMRC validation)
* TWEAK: The order meta field 'Valid EU VAT Number' has been renamed to 'Valid VAT Number'. An automatic database conversion will occur. If you are using custom tables for order meta rather than the WP core postmeta table (you'll know if you are) then you will need to carry out this change manually.
* TWEAK: Introduce a constant WC_VAT_COMPLIANCE_FREE_ONLY allowing emulation of the free version when all Premium files are present (useful for developing).
* TWEAK: Update the Free/Premium feature comparison table
* TWEAK: Internal refactoring of how "VAT-exempt above (amount)" options are stored, in preparation for allowing multiple options of this type
* TWEAK: Improve a few strings relating to readiness tests

= 1.19.6 - 2021-03-08 =

* TWEAK: Re-factored VAT number lookup code into their own classes, independent of region class
* TWEAK: Replaced WC_EU_VAT with WC_VAT in all WC_EU_VAT_Compliance_Rate_Provider class names
* TWEAK: Replace the action wc_eu_vat_compliance_vat_number_settings with wc_vat_compliance_vat_number_settings

= 1.19.5 - 2021-03-04 =

* FIX: When VAT number entry policy is set to 'Never', do not pre-fill the hidden field with any previously stored VAT number (e.g. from when settings were different)
* TWEAK: When the customer is outside of supported VAT regions and the plugin is configured to not store VAT numbers in this situation, remove anything submitted (e.g. previously stored on account)
* TWEAK: Clarify the wording for the 'Permit' and 'Never' settings for VAT number entry (Premium version)

= 1.19.3 - 2021-03-02 =

* FEATURE: Provide a new option (Premium version) allowing stores to collect VAT numbers from customers outside of their own VAT zone. Such VAT numbers are only recorded (not, by their nature, used for VAT exemption or even validated, but allow the store to collect information supplied by the customer if desired).
* FIX: In the detailed CSV download (Premium feature), the ISO-3166-1 shipping/billing country entries were inverted
* TWEAK: The exemption-based-on-value feature was using a "greater than or equal to" check, whereas the wording (and inspiring UK legislation) implied "greater than"; the check has now been brought into line with the wording.
* TWEAK: Change the wording of the exemption-based-on-value feature to clarify that it is inherently incompatible with any other features that change prices based on taxes.
* TWEAK: Adjust the description of options under "VAT number entry at check-out" (Premium) to clarify that they apply to customers in a configured VAT region.

= 1.19.2 - 2021-01-28 =

* FIX: a regression in 1.19.0 that could lead to a customer's country/region being misidentified.
* FIX: When creating a credit note with WP Overnight PDF Invoices and Packing Slips Professional, a PHP fatal error would occur; now it successfully creates, including converted currency amounts.

= 1.19.0 - 2021-01-27 =

* FEATURE: (Premium feature) You can now have different VAT number policies per VAT region; e.g. accept VAT numbers from EU companies to allow VAT to be removed from the order, but require VAT numbers from UK customers to forbid UK B2C customers from ordering if there is VAT
* FIX: If you saved settings to forbid VAT-ables sales to a particular region in 1.18.3 and then removed all regions from the list, the setting did not save with the empty list
* FIX: Fix a logic error (regression) introduced in 1.18.3 when stores required a VAT number if (and only if) the cart had VAT-able items, which failed to apply the setting
* TWEAK: When forbidding VAT number entry, the CSS IDs of the elements involved on the checkout have changed from vat_number_row/vat_number to vat_number_disallowed_row/vat_number_disallowed
* TWEAK: Tweak the message displayed on the checkout if the shop requires a VAT number from a particular region for the specific order whilst the shop supports multiple regions, so as to be more precise as to what is required

= 1.18.3 - 2021-01-26 =

* FEATURE: The option for forbidding all VAT-able sales to customers in your VAT region has now been enhanced to allow forbidding such sales to any chosen combination of regions.
* TWEAK: Adjust wording of the "VAT number entry at check-out" setting to be more precise.
* TWEAK: Add FAQ link to control centre.
* TWEAK: The CSS ID wceuvat_notpossible has been replaced with wcvat_notpossible

= 1.18.2 - 2021-01-25 =

* FIX: Fix a wrong class reference in get_region_code_from_order() when dealing with older olders

= 1.18.1 - 2021-01-21 =

* TWEAK: A wrong variable reference (a regression during the 1.17 series) meant that VIES lookups were non-extended lookups

= 1.18.0 - 2021-01-16 =

* FEATURE: The summary report table is now aware of multiple reporting currencies (i.e. the per-country override feature added in 1.17.2); reporting rows are now in the desired currency (and split into multiple rows if there is more than one for the place-of-supply country), with 'grand total' rows also being split by currency.
* TWEAK: The currency selector for VAT-exemption based on value now also appears in the WooCommerce tax tab
* TWEAK: Update jQuery tablesorter to 2.31.3 (https://github.com/Mottie/tablesorter/ fork)
* TWEAK: The PHP constants WC_EU_VAT_COMPLIANCE_DIR, WC_EU_VAT_COMPLIANCE_URL, WC_EU_VAT_COMPLIANCE_ITEMS_PAGE_SIZE and WC_EU_VAT_COMPLIANCE_REPORT_PAGE_SIZE have all had EU_ removed (so, if you had customised code using them, you will need to adjust)
* TWEAK: Abstract UI-related reports code into a separate class for easier maintenance, and remove some dead code

= 1.17.9 - 2021-01-12 =

* TWEAK: The "VAT exemption based on value" feature can now optionally including shipping costs and coupon discounts in its calculations. To include shipping, use the filter wc_euvat_compliance_cart_total_includes_shipping (filter to true instead of the default false).

= 1.17.8 - 2021-01-07 =

* FEATURE: In the WooCommerce PDF Invoices + Packing Slips configuration, allow {store_vat_number} to be included in the address - and replace it in the output with the region-appropriate value for the purchase.
* TWEAK: Correct the secondary tax report hyperlink in the 'Reports' tab.

= 1.17.7 - 2021-01-07 =

* FEATURE: The store VAT number setting has now been split into a separate VAT number for each VAT territory, allowing stores to have valid VAT numbers in multiple regions (one in the EU, one in the UK), and for the right one to be used when indicating the requestor on extended VAT lookups.
* TWEAK: The store VAT number readiness check has been enhanced to perform lookups in each region where a number is configured.
* TWEAK: Add the {store_vat_number} placeholder in B2B invoice footers (now that this is not longer necessarily a fixed value).
* TWEAK: Eliminate the no-longer-used WooCommerce_Compat_0_3 library

= 1.17.6 - 2021-01-05 =

* TWEAK: Do not ask a customer to confirm their country of residence if the two conflicting pieces of data available both agree in being outside the VAT region (and tweak the option description in the settings accordingly).
* TWEAK: Change the title for the "Relevant tax classes" setting to "Digital goods tax classes", and re-write the wording to more precisely clarify its meaning and intent.
* TWEAK: In the readiness tests, add a note and tweak the wording to note that per-country VAT rates are not relevant unless you are selling goods (e.g. digital goods) with associated place-of-supply rules.

= 1.17.5 - 2021-01-02 =

* TWEAK: Make the "VAT exemption based on value" input field (Premium) a number.
* TWEAK: The "VAT exemption based on value" setting/feature (Premium) now adds a currency setting, so that comparisons can be made according to the rules of the particular country that you have configured for and not need to continuously update any exchange rates involved.

= 1.17.4 - 2020-12-31 =

* TWEAK: The "Add VAT rates" button (on the WooCommerce tax tables screen) is now based on the main "VAT region" setting - i.e. it will add/update rates for countries in your configured VAT region (only). Related to this, remember that at the end of 31st December 2020 you may want to review your VAT rates for any Brexit-related changes.
* TWEAK: Replace deprecated jQuery.size() with .length
* TWEAK: Correct time of UK becoming its own VAT area (was coded as 1 hour after midnight GMT instead of one hour before)

= 1.17.3 - 2020-12-30 =

* FIX: If the 1.17+ feature to use different exchange rate providers for different taxation countries is used, then carts of non-digital goods did not identify the country rightly (as the shop base country); this is now fixed.
* TWEAK: Prevent some PHP notices if downloading CSV information for orders that were manually created and lack normally-present fields
* TWEAK: Add a "Currency conversion provider" column to the detailed CSV download (Premium)
* TWEAK: The detailed CSV report (Premium feature) now shows the relevant currency conversion when an over-ride is configured

= 1.17.2 - 2020-12-30 =

* FEATURE: Now supports per-country over-rides for the reporting currency and exchange rate provider when recording transaction amounts. This is intended to support shops who remit taxes to multiple tax authorities who have their own currency and exchange rate requirements, and is relevant to Brexit (so, if this applies to you, please review your settings). N.B. Reporting has not yet been made compatible with this change (but this should be rectified in a release before a new reporting period has passed). Version 1.17.1 uses these over-rides on invoices too (which 1.17.0 did not).
* FIX: Fix a regression in 1.17.1 which disabled the downloading of detailed CSV reports (Premium feature).
* FIX: When an order contained taxable non-digital goods, the currency conversion shown on PDF invoices failed to include those goods.
* TWEAK: When adding meta-data to a new subscription order, include the full network result in the new format
* TWEAK: Replace deprecated jQuery click() and change() styles and :first selector
* TWEAK: Record the exchange-rate lookup provider even if the lookup failed (potentially useful for diagnostics)
* TWEAK: Tweak the formatting of the "IP Country" source display to remove potential ambiguity
* TWEAK: Eliminate obsolete usage of WooCommerce_Compat_0_3::get_meta()

= 1.16.3 - 2020-12-29 =

* FEATURE: Experimental feature (in the Premium version) allowing a shop to consider a cart as tax-exempt if the taxable address is in a specified country and the order value is above a specified amount. This is intended to support requirements such as those described at https://www.gov.uk/government/publications/changes-to-vat-treatment-of-overseas-goods-sold-to-customers-from-1-january-2021/changes-to-vat-treatment-of-overseas-goods-sold-to-customers-from-1-january-2021
* TWEAK: Update bundled updater library to latest release
* TWEAK: Remove some debugging log lines left in 1.16.2
* TWEAK: Replace jQuery.parseJSON with JSON.parse

= 1.16.2 - 2020-12-22 =

* FIX: Remove an unintended requirement for PHP 7.2+ in the Premium version 1.16.1 release

= 1.16.1 - 2020-12-22 =

* TWEAK: Replace a couple of jQuery calls using a deprecated style
* TWEAK: HMRC-lookups now possible for UK numbers (via a constant), and extra meta-info recorded and shown in the order admin meta-box

= 1.16.0 - 2020-12-17 =

* FEATURE: Continuing Brexit-related changes: a new "VAT region" setting (default to "EU VAT region") has been added (this default region will change its membership on 1st January 2021, losing members of the UK VAT region; other choices available are "UK VAT region" and "Combined EU and UK VAT regions"). All users are strongly recommended to review their local requirements and the appropriateness of their settings.

= 1.15.0 - 2020-12-11 =

* FOCUS: In light of the upcoming expiry of the EU/UK transitional agreement, the plugin has been reviewed and checked for potential issues. All users are recommended to be aware of any changes in their situation and review their settings for readiness.
* TWEAK: Enable the "VAT number lookups" section, including HMRC validation for UK businesses
* TWEAK: Separate the "VAT rates are up-to-date" readiness test into two separate tests (one for the EU, one for the UK/IM) - if you wish to carry on checking the latter automatically in the Premium version, you should visit the 'Readiness Tests' tab and enable it (it is disabled by default).
* TWEAK: When saving VAT numbers to a customer's account after check-out, include the country prefix.
* TWEAK: Change the name of the entry in the WooCommerce sub-menu to "VAT Compliance" and make similar tweaks in a couple of other places, de-emphasising "the EU" when this is unnecessary (e.g. use by UK shops post-Brexit)
* TWEAK: Internally enable use of the production HMRC VAT-number lookup app for UK VAT number lookups
* TWEAK: Separate the choice of which region to use for lookups from the home region, and allow it to be filtered

= 1.14.28 - 2020-11-05 =

* FIX: When a customer changed their VAT number on a subsequent purchase, their new number was not saved to their account (on future check-outs, the form would be pre-filled with their old one)
* FIX: Fix a potential fatal error in the short-lived 1.14.27
* FIX: When the delivery method is local pickup, then the taxation country should not be over-ridden from WooCommerce's base-country over-ride (controlled by the same filters as used in WooCommerce core - woocommerce_apply_base_tax_for_local_pickup and woocommerce_local_pickup_methods)

= 1.14.25 - 2020-11-02 =

* FEATURE: (Premium) provide ability for shop manager to mark a user as always VAT-exempt in their profile
* TWEAK: Previously, fixed (i.e. traditional, not based upon customer location) VAT was listed in summary tables by the customer's location (whilst still listed separately from digital/by-consumer VAT). It is now listed by the shop base country (at the time of ordering), reflecting its intended destination.
* TWEAK: Update jQuery document ready style to the one not deprecated in jQuery 3.0
* TWEAK: Update the bundled updater libraries
* TWEAK: Record the shop base country at the time of ordering in the plugin tax information
* TWEAK: Prevent some PHP notices when reports are empty due to (e.g.) all relevant orders being trashed

= 1.14.24 - 2020-10-29 =

* FIX: The "forbid checkout entirely" option for handling taxable country/IP address conflicts was correctly described in the options, but in reality was also firing when neither of the mutually contradictory countries was in the VAT region.

= 1.14.23 - 2020-10-23 =

* TWEAK: Do not show the VAT compliance meta-box on the manual add new-order screen
* TWEAK: Change SOAP client options to carry out UTF-8 decoding correctly

= 1.14.22 - 2020-10-19 =

* FIX: A regression in 1.14.19-1.14.21 meant that not all VAT data used for reporting was created at order time; the omitted item will be automtically recreated when a report is run (which may make reports take longer than usual until it is done)
* TWEAK: Update bundled updater libraries (paid version) to current releases

= 1.14.21 - 2020-10-10 =

* TWEAK: Don't let check_vat_number_validity() cause a fatal error if an error cannot be shown with wc_add_notice() because of running in a back-end context (e.g. automatic subscription order duplication)

= 1.14.20 - 2020-09-24 =

* FEATURE: The order-page VAT meta-box now includes the internal information WooCommerce stored on whether the customer was VAT-exempt. This covers the case where a different plugin had marked the customer (i.e. was not marked VAT-exempt by this plugin).
* FIX: Restore the check for a non-empty VAT number before checking it (regression in 1.14.19)
* TWEAK: Remove obsolete compatibility function for WC versions lacking wc_add_notice()
* TWEAK: Remove obsolete compatibility function for WC_Order::get_total_tax_refunded()
* TWEAK: Slightly improve formatting of the order-page VAT meta-box

= 1.14.19 - 2020-09-21 =

* TWEAK: Prevent invalid SQL being executed when there were no refunds to process
* TWEAK: Refactor the VAT number lookup code (looking ahead to possible future changes)

= 1.14.18 - 2020-09-19 =

* FIX: When showing the message for B2B VAT-exempt supplies, check all types of goods
* TWEAK: Remove no-longer-necessary compatibility function for fetching orders

= 1.14.17 - 2020-09-16 =

* FEATURE: If a customer saves a VAT number to their account, or logs in with a saved VAT number, then internal country pre-select status is set to "show prices without VAT"
* FIX: When "Show prices without VAT" was chosen from the widget, this failed to persist across page loads
* TWEAK: Eliminate the internally unused eu_vat_state_widget session variable

= 1.14.16 - 2020-09-11 =

* TWEAK: Eliminate duplicate SQL queries when fetching information on order status relating to refunds
* TWEAK: Do not retrieve the option woocommerce_eu_vat_compliance_same_prices during the plugins_loaded hook (causes an extra SQL query if it is unset)
* TWEAK: Increase the page size by a factor of around 3 when getting report results
* TWEAK: When storing VAT rates transient data, include the source and the time that was used for rate change comparisons
* TWEAK: Fix a wrong variable reference that could cause errors when parsing extended format VAT rates JSON data
* TWEAK: Update bundled updater libraries (paid version) to current releases

= 1.14.14 - 2020-08-31 =

* TWEAK: Update the bundled VAT rates.json file for upcoming Irish and German VAT changes
* TWEAK: Removed some code supporting obsolete WooCommerce versions (<2.5)
* TWEAK: Implement an extended format for VAT rates JSON data, allowing future VAT rate changes to be included asynchronously
* TWEAK: Update bundled updater libraries (paid version) to current releases

= 1.14.13 - 2020-07-31 =

* FIX: When a customer added a VAT number that was impossibly short, an error was shown, but the relevant setting (on by default) was failing to prevent the order from still being allowed to proceed (though VAT was still, correctly, being charged)
* TWEAK: Mark as compatible with WordPress 5.5 (requires 4.5+; nothing has been done to remove compatibility with earlier versions, but this is the updated support requirements)

= 1.14.12 - 2020-06-30 =

* TWEAK: Update the bundled VAT rates file to reflect changes to the rates in Germany
* TWEAK: Update the updater library to the latest version
* TWEAK: Mark as compatible with WooCommerce 4.3

= 1.14.11 - 2020-06-03 =

* FIX: In the case of shipping country being used for tax calculations, if this was a EU country but their billing country was a non-EU country and a VAT number was supplied, the customer was wrongly told that they were not permitted to enter a VAT number.

= 1.14.10 - 2020-05-27 =

* TWEAK: Prevent PHP notice when getting visitor country data

= 1.14.9 - 2020-05-16 =

* FIX: When the shop did not require conflict resolution, if IP address/taxable address data differed, then the wrong country was used for deciding which invoice footer to show, which made a difference if the countries' region status differed
* TWEAK: Mark as compatible with WooCommerce 4.2
* TWEAK: Update updater class in Premium version to current release

= 1.14.8 - 2020-05-07 =

* TWEAK: Mark as compatible with WooCommerce 4.1

= 1.14.7 - 2020-04-18 =

* TWEAK: Fetch the EU's WSDL file over https, now that it is supported
* TWEAK: Removed unwanted debugging statement that entered in 1.14.5
* TWEAK: Prevent unwanted PHP logging notice that began on 1.14.6
* TWEAK: When the store's VAT number lookup readiness test fails, include the error message if it is a WP_Error object
* TWEAK: Improve the logic around returning an error when a WSDL fetch failure occurs

= 1.14.6 - 2020-03-25 =

* FIX: Navigation when choosing new ranges within WooCommerce -> Reports -> Taxes -> EU VAT Report had regressed on WooCommerce 4.0 (whilst still working within WooCommerce -> EU VAT Compliance -> Taxes)

= 1.14.5 - 2020-03-21 =

* TWEAK: Revert some of yesterday's changes, as the EU VIES server appears to have reverted its own behaviour

= 1.14.4 - 2020-03-20 =

* TWEAK: readme.txt description fix (was not mentioning WC 4.0 compatibility)
* TWEAK: Updated bundled updater libraries (PUC 4.9, SPMU 1.8.3) (Premium)
* TWEAK: Updater will run availability checks without requiring login
* TWEAK: Switch to the econea/nusoap version of nusoap because of active maintenance
* TWEAK: Mark plugin as requiring PHP 5.4, as required by econea/nusoap

= 1.14.3 - 2020-03-12 =

* FIX: The UK had ceased to appear in VAT reports in WC 4.0 due to internal changes in WC 4.0

= 1.14.2 - 2020-03-07 =

* TWEAK: Mark as supporting WP 5.4 (requires 4.4+) + WC 4.0 (requires 3.4+)

= 1.14.1 - 2020-02-11 =

* TWEAK: On WooCommerce 3.9+, update the now-deprecated filter woocommerce_geolocation_update_database_periodically

= 1.14.0 - 2020-01-16 =

* FEATURE: Upon creation of an automatic subscription renewal order, a fresh VIES check will be run if relevant, and failures recorded in the order notes; the action wc_eu_vat_compliance_renewal_validation_result is also run
* FIX: At some point, a regression probably due to a WooCommerce core change occurred preventing session data for pre-selected countries being properly saved
* TWEAK: If the order country is not known when processing invoice footer text, do not add anything
* TWEAK: WooCommerce 3.8 login form has had some DOM changes which caused a little uglification; fix this
* TWEAK: Move initialisation of the checkout title and message until the 'init' hook, allowing WPML to over-ride the contents
* TWEAK: Remove some legacy code for supporting WooCommerce Subscriptions versions earlier than 2.0 (released October 2015)
* TWEAK: Add detection for the Subscriben for WooCommerce extension
* COMPATIBILITY: We now officially support WP 4.3+ and WC 3.3+ (we don't believe we've done anything to make it incompatible on earlier versions, but support is not available if you encounter problems)

= 1.13.17 - 2019/10/24 =

* TWEAK: Replace euvatrates.com/rates.json as a backup source of VAT rates, as it is not up-to-date

= 1.13.16 - 2019/10/22 =

* TWEAK: Use the action wpo_wcpdf_footer_settings_text instead of wpo_wcpdf_footer as the filter to add invoice footer text, for better compatibility with WPML + WooCommerce PDF Packing Slips + Invoices Professional

= 1.13.15 - 2019/10/19 =

* TWEAK: Updated WPML file (thanks to Jan Schrader) with entries for more options fields.
* TWEAK: Include Isle of Man in the EU VAT area list if and only if GB is found in it
* TWEAK: Mark as supporting WC 3.8
* COMPATIBILITY: We now officially support WP 4.2+ and WC 3.2+ (we don't believe we've done anything to make it incompatible on earlier versions, but support is not available if you encounter problems)

= 1.13.14 - 2019/08/27 =

* FIX: When the shop was set to calculate all taxes based on the base address, the VAT field (Premium) would show at the checkout always, even if it was configured to not do so
* TWEAK: Tweak to subscriptions support for future compatibility

= 1.13.13 - 2019/08/06 =

* TWEAK: Explicit handling for the MS_MAX_CONCURRENT_REQ and GLOBAL_MAX_CONCURRENT_REQ VIES response codes.
* TWEAK: Mark as supporting WC 3.7
* COMPATIBILITY: We now officially support WP 4.1+ and WC 3.1+ (we don't believe we've done anything to make it incompatible on earlier versions, but support is not available if you encounter problems)

= 1.13.12 - 2019/05/30 =

* TRANSLATION: Updated translations, including new Italian translation with thanks to Alessandro Spurio
* TWEAK: Updated bundled updater libraries (PUC 4.6, SPMU 1.8.1)

= 1.13.11 - 2019/04/22 =

* TWEAK: Remove some obsolete geolocation code for supporting WooCommerce < 2.4
* TWEAK: Remove use of compatibility layer for WC_Order::update_meta_data() calls

= 1.13.10 - 2019/04/03 =

* TWEAK: When generating PDF invoices for old orders with WooCommerce PDF Invoices + Packing Slips, if configured to add exchange rate information, this will now also be added retrospectively to orders made when this plugin was not active (looking up the rates from the time of the order). To turn off this behaviour, use the filter wc_eu_vat_retrospectively_add_conversion_rates.

= 1.13.9 - 2019/04/01 =

* TWEAK: Fix layout of multiple-currency tax reporting in PDF invoices in current WooCommerce versions
* TWEAK: Add multiple-currency tax reporting in PDF invoices also with the Professional version of WooCommerce PDF Invoices and Packing Slips (and likely various other solutions too, given how it works - it's not specific to that plugin)
* TWEAK: Update bundled Premium updater class to current (1.8)
* TWEAK: Update bundled WooCommerce compat library to current (0.3.1)

= 1.13.8 - 2019/03/26 =

* TWEAK: Use the available filter to request WooCommerce to keep its GeoIP database up to date
* TWEAK: Now marked as supporting WC 3.6 (no change to minimum WC 3.0 requirement)
* TWEAK: Now marked as supporting WP 5.2 (no change to minimum WP 4.0 requirement)

= 1.13.7 - 2019/02/20 =

* TWEAK: Correction in the wpml-config.xml file
* TWEAK: If XML is fetched from an exchange rates provider but does not pass, then error_log() something to help debugging.
* TWEAK: Do an ltrim() on the fetched XML before doing the "does this actually look more like HTML?" check
* TWEAK: If the XML fetches looks like HTML, error_log() that

= 1.13.5 - 2019/02/19 =

* TWEAK: The filter wceuvat_msg_checking was mis-named as wceuvat_msh_checking; this is now corrected (if you were using it, you will need to update your code)
* TWEAK: Add CSS classes vat-result-* (e.g. valid, invalid, checking) to the DOM element #woocommerce_eu_vat_compliance_vat_number_validity to allow easier styling
* COMPATIBILITY: We now officially support WP 4.0+ (we don't believe we've done anything to make it incompatible on earlier versions)

= 1.13.4 - 2019/02/16 =

* FEATURE: Allow the VAT number field (Premium) to only display if the customer has entered a company name
* TWEAK: Adjust the wording "Add / Update EU VAT rates" to include the word "Digital" for clarity.
* TWEAK: Resolve a PHP 7.3 deprecation notice
* TWEAK: Update bundled translations

= 1.13.2 - 2018/12/18 =

* TWEAK: Add a filter wc_eu_vat_number_cache_positive_validation for users who do not wish to cache positive VAT-number validations
* TWEAK: Add a filter wc_eu_vat_store_order_vat_number allowing pre-filtering of the VAT number before it is stored in the database
* TWEAK: Update updater library (Premium) to latest version (1.5.10)
* TRANSLATIONS: Update the bundled translations (Premium version)

= 1.13.1 - 2018/12/07 =

* FIX: The XML from the Danish National Bank, if that was chosen as the exchange rate provider, lists the exchange rate for 100 DKK, not for 1 DKK.

= 1.13.0 - 2018/10/18 =

* COMPATIBILITY: Marked as compatible with WooCommerce 3.5, and now requiring 3.0+. Nothing has been specifically done to break compatibility on 2.6, but this is what we are officially supporting.
* COMPATIBILITY: Similarly, we now officially support WP 3.9+ (we don't believe we've done anything to make it incompatible on earlier versions)
* REFACTOR: Various pieces of internal re-factoring and abstraction to help keep the plugin future-ready
* TWEAK: Removed more code sections that existed to support WC versions that we stopped supporting long ago
* TWEAK: Updated the list of readiness tests in light of not-supported WC versions
* TWEAK: Replace jQuery.parseJSON with JSON.parse

= 1.12.11 - 2018/08/18 =

* FIX: Fix a regression in 1.12.10 which prevented the configured EU B2B VAT-exempted footer being added to PDF invoices

= 1.12.10 - 2018/08/11 =

* FEATURE: Expose WooCommerce's experimental option for adjusting base prices to result in the same net (after tax) price for products regardless of differing tax rates for buyers in different territories (more info: https://github.com/woocommerce/woocommerce/wiki/How-Taxes-Work-in-WooCommerce#prices-including-tax---experimental-behavior)
* TWEAK: Some readme tweaks/updates
* TWEAK: Marked as supported on WP 3.8+ (nothing has changed to make it incompatible on earlier versions, but there this is now the official support requirement)
* TWEAK: Removed various pieces of code that were providing compatibility with long-unsupported WC versions. Our official support has not changed; it's still WC 2.6+, but now various things will definitely cause fatal errors before WC 2.3 now, so don't try it!

= 1.12.9 - 2018/08/04 =

* FEATURE: Add a new capability to add invoice footer text for non-EU and non-taxed orders, as required by some national laws (Premium)

= 1.12.8 - 2018/07/30 =

* TWEAK: If Subscriptio indicates a renewal for a subscription for which it lists non-existent orders, prevent this calling a PHP Fatal when Subscriptio_Order_Handler::order_is_renewal is called on it.
* TWEAK: Prevent deprecation notices related to parse_str() on PHP 7.2+
* TWEAK: Minor tweaks to the display of raw VIES information in the order screen meta box

= 1.12.7 - 2018/05/22 =

* TWEAK: Marked as WooCommerce 3.4 compatible (now requires 2.6+)
* TWEAK: Marked as supported on WP 3.7+ (nothing has changed to make it incompatible on earlier versions, but there this is the official support requirement)

= 1.12.6 - 2018/05/16 =

* FEATURE: Add an option to anonymize any personal data in downloaded CSVs (which, for now, is just IP addresess - they did not contain any other personal data). Helps with GDPR compliance; you won't need to justify having detailed CSVs lying around or create a process for cleansing them if they do not contain any personal data.

= 1.12.5 - 2018/05/11 =

* FIX: The WC_EU_VAT_NOCOUNTRYPRESELECT constant/over-ride did not work on all PHP versions because of how PHP processes class definitions.
* TWEAK: Update updater library (Premium) to latest version (1.5.3)

= 1.12.4 - 2018/04/26 =

* TWEAK: Update call to deprecated WC_Order::get_order_currency
* TWEAK: Improve the SQL query used for generating VAT report summaries, reducing the time needed by around 80% on large sites.

= 1.12.3 - 2018/02/27 =

* FEATURE: (Premium) Introduce the wc_eu_vat_get_base_countries filter, to allow multiple countries to be considered as the 'base country', for the purpose of all options/behaviours that differentiate on the base country

= 1.12.2 - 2018/01/17 =

* FEATURE: Add a new option for use with WooCommerce Subscriptions, allowing renewal orders to not be created if they are liable to EU VAT (use case: stores which previously allowed VAT-able orders from the EU, but have changed their policy, and wish to prevent future renewals). This complements the existing "forbid EU VAT checkouts" option.
* TWEAK: The wceuvat_check_cart_items_is_forbidden filter has been abolished; a necessity arising due to a restructuring. If you used it, you should look at the wceuvat_product_list_product_list_has_relevant_products and wceuvat_cart_is_permitted filters.

= 1.12.1 - 2018/01/15 =

* FIX: Fix a regression since 1.11.27 in the "require a VAT number always outside of your base country" feature, which was causing it to be required there too.

= 1.12.0 - 2018/01/12 =

* FEATURE: New hooks added for supporting download of HMRC (UK) reporting spreadsheet (with separate plugin)
* COMAPTIBILITY: Now supporting WooCommerce 3.3 (tested with release candidate 1)
* COMPATIBILITY: Support for WooCommerce 2.4+ dropped (now supporting 2.5+)

= 1.11.27 - 2018/01/08 =

* FEATURE: Added a new option, allowing a valid EU VAT number to be required, but only if the cart contains goods liable to (which includes that the VAT address is an EU one) variable EU VAT (complementing the existing option for requiring a valid EU VAT number for all carts.
* TWEAK: Introduce a WC_EU_VAT_DEBUG constant; if set to true, then the detected country will be output in front-end page footers.

= 1.11.26 - 2017/12/02 =

* TWEAK: Add wc_eu_vat_check_vat_number_country and wc_eu_vat_disallowed_country_message filters to allow a developer to forbid VAT numbers for chosen EU countries

= 1.11.25 - 2017/11/27 =

* TWEAK: Handle a null order value being passed to woocommerce_checkout_order_processed without causing an exception.

= 1.11.24 - 2017/11/23 =

* TWEAK: Update bundled updater (Premium) to the latest version (1.5.0)

= 1.11.23 - 2017/11/07 =

* TWEAK: Avoid using a deprecated method in current WooCommerce Subscriptions releases

= 1.11.22 - 2017/10/27 =

* FIX: Fix a regression in version the Premium version of 1.11.18, which caused VAT numbers to permit VAT exemption for the base country if the shop options were set to request a number.

= 1.11.21 - 2017/10/23 =

* FEATURE: New option for the drop-down country selector, as to which countries are listed: all (default/previous behaviour), countries sold to, countries shipped to. The euvat_country_selector shortcode has been enhanced with a new parameter 'include_which_countries', which can take the values "all" (default), "shipping" or "selling".

= 1.11.19 - 2017/10/23 =

* TWEAK: Enhance the wording of the 'tax based on' compliance test to clarify that it applies to EU digital goods sales.
* TWEAK: Remove some debug messages from the updates checker

= 1.11.18 - 2017/10/17 =

* FEATURE: Option to accept any number as validated (Premium) when the taxation country is your base country (in which situation, you are usually not deducting VAT, but just wish to record their entered number, especially if you have a separate set of numbers locally)

= 1.11.17 - 2017/10/06 =

* TWEAK: Update bundled updater library for Premium (now 1.4.8)

= 1.11.16 - 2017/10/05 =

* FEATURE: Add access to Czech National Bank official exchange rates
* TWEAK: Fetch ECB exchange rates over https, not http
* TWEAK: Update bundled updater library for Premium (now 1.4.7)
* TWEAK: 'Test Provider' button now correctly shows 'Testing...' when pressed

= 1.11.15 - 2017/09/30 =

* TWEAK: Marked as compatible with the forthcoming WC 3.2 (no changes were needed), and now tested and supported from WC 2.4 onwards (one-in, one-out).

= 1.11.14 - 2017/09/26 =

* TWEAK: (Relevant to Premium only): If the user has chosen 'never' for VAT number entry and made the title and text blank, then default to showing no other text in that section (still over-rideable by the filter wc_eu_vat_nob2b_message, as before)

= 1.11.13 - 2017/09/23 =

* TWEAK: Update bundled updater library for Premium version (1.4.6)
* TWEAK: Add filters allowing developers to add columns to the detailed CSV download spreadsheet (Premium)

= 1.11.12 - 2017/08/31 =

* TWEAK: Add WooCommerce version headers (https://woocommerce.wordpress.com/2017/08/28/new-version-check-in-woocommerce-3-2/)

= 1.11.11 - 2017-08-18 =

* TWEAK: Fix unbalanced HTML tag in noscript section

= 1.11.10 - 2017-07-22 =

* TWEAK: Use the latest version (1.4.2) of the bundled updater library
* TWEAK: Use WC_Customer::set_billing_country instead of the deprecated WC_Customer::set_country when possible

= 1.11.9 - 2017-06-22 =

* FIX: HMRC's CDN URL for the download of current currency conversion rates had started rejecting the default WordPress user agent, meaning that rates were not being updated
* TWEAK: Add a new readiness test for the freshness of currently configured exchange rates
* TWEAK: Give the download of currency conversion rates a few more seconds before timing out
* TWEAK: Make the VAT number edit row in 'My Account' to have the form-row-wide class

= 1.11.8 - 2017-06-01 =

* FIX: Fix a typo that caused a fatal error when generating PDF invoices in Premium in 1.11.7

= 1.11.7 - 2017-06-01 =

* COMPATIBILITY: Mark as compatible with WordPress 4.8 (requires at least: 3.4 - nothing in particular is known to make it incompatible on previously supported versions; this just indicates that it won't be tested/supported)
* TWEAK: Prevent a deprecation notice when getting order date on WC 3.0+
* UPDATE: Update the bundled WooCommerce compatibility library
* UPDATE: Update the bundled updater library (Premium)

= 1.11.6 - 2017-05-30 =

* TWEAK: Improve integration of reporting with WooCommerce Sequential Order Numbers

= 1.11.5 - 2017-05-23 =

* FIX: The prior 1.11 series releases had a packaging error (Premium), omitting code necessary for the Premium updates mechanism

= 1.11.4 - 2017-04-13 =

* FIX: Prevent a JavaScript error on checkout on WooCommerce 3.0 when forbidding all VAT-able EU checkouts
* TWEAK: Change the default message shown when the conflict mode resolution option is set to absolutely require consistent country data before checkout can proceed, so that it is less likely to be mis-read.

= 1.11.3 - 2017-03-25 =

* COMPATIBILITY: Updated for WooCommerce 3.0 (tested with release candidate 2)
* FIX: Fix a bug which prevented WooCommerce's "Tax based on shipping address" from working correctly in the free version.
* FIX: Update the bundled woocommerce-compat library library to version 0.2.2, fixing a bug in meta handling

= 1.11.2 - 2017-03-03 =

* FIX: Fix wrong function name in get_vat_paid() method
* FIX: Fix bug in recording of VAT number (Premium version)
* TWEAK: Allow get_main_chart() in the reporting module to cope with failures in wc_get_order()

= 1.11.1 - 2017-03-03 =

* COMPATIBILITY: Updated for WooCommerce 2.7 (tested with and requires at least release candidate 1)
* TWEAK: Add wc_eu_vat_set_not_vat_exempt filter, to allow developers to stop the plugin registering customers as not-VAT exempt (e.g. if they have an extra reason for thus registering them)
* TWEAK: Import woocommerce-compat library to abstract away changes in WC 2.7
* TWEAK: Port all accesses of WC_Order::id over to woocommerce-compat library
* TWEAK: Port all accesses of WC_Product::get_price_(ex|in)cluding_tax over to woocommerce-compat library
* TWEAK: On WC 2.7+, use the WC_Customer::get_billing_country() instead of the deprecated WC_Customer::get_country()
* TWEAK: Prevent PHP notice about deprecated coding construction used in NuSOAP library on PHP 7+
* TWEAK: Added a new filter wc_euvat_compliance_wpo_wcpdf_footer_result for allowing easier over-riding of the added footer
* TWEAK: Update bundled updater (Premium) to the latest version

= 1.10.40 - 2017-01-07 =

* TRANSLATIONS: Partial Dutch translation, thanks to Peter Landman

= 1.10.39 - 2017-01-07 =

* TRANSLATIONS: Plugin is now set up for compatibility with wordpress.org's translation system. Translators welcome! https://translate.wordpress.org/projects/wp-plugins/woocommerce-eu-vat-compliance
* TRANSLATIONS: Existing translations updated (many thanks to translators)

= 1.10.38 - 2017-01-02 =

* TWEAK: Change the reports page, to have a "VAT-able supplies" column. The "Items" column (which does not take partial refunds into account) is still available, but now hidden by default.
* TWEAK: In the tax rates readiness test, if less tax rates were found in your tables than expected, then the resulting message had confusing wording.
* TWEAK: In the tax rates readiness test, accomodate the fact that sales between the Isle of Man and the UK are not accounted as exports, but as in-country transactions.
* TWEAK: Allow the rounding and formatting functions to be filtered, for easier customisation, in case anyone has varying local requirements
* FIX: If you had unused and deleted tax classes in your WooCommerce install, then the readiness test for whether you had VAT rates for each country would still see these, as WooCommerce does not actually delete them from the database when you remove them from your WooCommerce options.
* FIX: In the tax rates readiness test, some wrong rates in the 'Standard' class could be overlooked

= 1.10.36 - 2016-12-31 =

* TWEAK: Update the bundled VAT rates file to reflect the change in Romania VAT rate

= 1.10.35 - 2016-12-27 =

* TWEAK: For certain classes of VAT number lookup failure (Premium version), associated returned information about the failure was not always making it through
* TWEAK: Update the bundled updater to version 1.3.0, which allows the user to choose to automatically install updates (applies to Premium version only)

= 1.10.34 - 2016-12-16 =

* FEATURE: Added an 'export settings' button, which makes debugging/comparisons easier
* TWEAK: Remove a non-sensical line from the free/paid feature comparison table, which had crept in from the code in another of my plugins that it was originally copy/pasted from.
* FIX: A logic error meant that certain combinations of options surrounding forbidding all non-B2B orders without valid EU VAT numbers did not work correctly

= 1.10.32 - 2016-12-10 =

* FIX: On orders which had multiple refunds against them, the VAT could be totalled wrongly if the MySQL server returned records in an unexpected order.

= 1.10.31 - 2016-11-29 =

* TWEAK: Add BTW, B.T.W. (Dutch) to the list of default strings recognised as VAT-like taxes
* TWEAK: The "no VAT charged" invoice footer notice was not being added if the VAT number was accepted (i.e. VAT removed) for some other reason than a successful VIES validation; it is now added.

= 1.10.30 - 2016-10-21 =

* TWEAK: Update the bundled updater to version 1.2.1, which is more tolerant of the plugin being moved to a different location (applies to Premium version only)

= 1.10.29 - 2016-10-05 =

* TWEAK: Prevent a PHP notice when running custom reports
* PERFORMANCE: A considerable speed boost (typically 80% faster) when generating reports, via using larger page sizes on queries

= 1.10.28 - 2016-09-06 =

* FIX: Fix the operation of the VAT number box (and VAT deduction) on checkout pages for which the shop owner had restricted the allowed countries to only one.
* TWEAK: Updated the bundled updater class versions

= 1.10.26 - 2016-07-29 =

* TWEAK: The bundled VAT rates file reflects Greece's new 24% rate

= 1.10.25 - 2016-07-07 =

* COMPATIBILITY: Marked as compatible with the forthcoming WP 4.6
* FEATURE: Detailed CSV download (available in the Premium version) now includes a "payment method" column
* SECURITY: (Affects Premium version only): previous releases allowed any logged-in user to download CSV reports, by visiting a specially crafted URL. This is now restricted to those with permission to view WooCommerce reports only.

= 1.10.24 - 2016-06-29 =

* FIX: VIES has made a minor change to the format of the data it returns in the case of an invalid VAT number - previous versions of this plugin handled its new format as an 'unknown' result. (So, if your settings were that unknown results should be treated as invalid, all was well - but if they were treated as valid, this was a problem).
* TWEAK: Pass back more data in the case of an unknown VIES result from the network

= 1.10.23 - 2016-04-26 =

* TWEAK: Add a work-around to parse some of the entities that the VIES server can pass back that the XML/SOAP parser doesn't like

= 1.10.22 - 2016-04-06 =

* TWEAK: Tweak rates readiness test to be more suitable for mixed stores (thanks to Fabian Schweinfurth for the patch)

= 1.10.21 - 2016-04-04 =

* TWEAK: CloudFlare's IP country header has apparently begun returning some results in lower-case, contrary to the relevant ISO standard. Tweak code to deal with this by converting back to upper-case.

= 1.10.20 - 2016-03-31 =

* TWEAK: Improve the wording of the option that allows store to require EU buyers to enter an EU VAT number.
* FIX: The capability for a customer to edit their saved VAT number was previously only working with specific WordPress permalink structures
* COMPATIBILITY: Marked as compatible with WordPress 4.5

= 1.10.19 - 2016-01-11 =

* FEATURE: Allow the base country to be exempted from the "require all customers to enter a VAT number" option (Premium)
* FEATURE: Provide an option allowing orders to be forbidden in case of conflicts between different evidences concerning the customer's location (Premium)
* FEATURE: The customer can now edit their saved VAT number as part of editing their billing address from their account page, for future orders (Premium)

= 1.10.18 - 2016-01-09 =

* TWEAK: Fix issue with unnecessary warning in the reports caused by a bug in Subscriptio (only relevant if that plugin is active)
* TWEAK: Clarify the message indicating missing WooCommerce data when creating reports, to cover more possible causes

= 1.10.17 - 2016-01-08 =

* FEATURE: Add a new (filterable) option allowing the base country to be excluded when requiring all customers with carts liable to digital VAT to have a valid EU VAT number (so, you can exclude chargeable B2C customers outside of your own country, but allow them within it).
* TWEAK: Removed obsolete fallback to VAT-number checking service that no longer exists

= 1.10.16 - 2016-01-01 =

* COMPATIBILITY: Tested + supported on WooCommerce 2.5 (tested with beta 3)
* TWEAK: Update bundled VAT rates file to reflect updated VAT rate for Romania (20%). Do remember to visit your tax rates pages in WooCommerce, and update them to current rates. (And if you've not yet set up a readiness report that automatically emails you about potential problems in WooCommerce -> EU VAT Compliance -> Readiness Report, if you've not done so already). (Note that the bundled file isn't the first chosen source of VAT rates - so, even if you don't update to the latest plugin version, you can get the latest rates; but you do need to update your tables).
* FIX: Fix a variable misnaming causing a JavaScript error on WC < 2.5 in the only-briefly-available 1.10.16

= 1.10.14 - 2015-12-01 =

* TWEAK: When VIES is unreachable, pass more information back to the customer/shop owner (e.g. the member state's service was unavailable)

= 1.10.13 - 2015-11-25 =

* TWEAK: Make the button for adding EU VAT rates to tax settings work in WC 2.5
* TWEAK: Add an option for what to do if VIES is unreachable. Defaults to the previous option: which was to assume that the customer entered a valid VAT number (which is more common than not - but you may wish to be strict, and prefer to lose the sales until VIES is back online).

= 1.10.12 - 2015-10-31 =

* TWEAK: Add a field in the WooCommerce tax rates table allowing adjustment of the tax description (so, for example, you can use MWSt or IVA instead of the default 'VAT', and avoid having to edit each line manually)
* TWEAK: Update the alternate reduced VAT rate for Greece in the bundled JSON rates (though, alternate rates aren't currently used in the plugin)

= 1.10.11 - 2015-10-14 =

* TWEAK: When WooCommerce Subscriptions 2.0+ is in use, use the new provided method instead of relying upon a deprecated method

= 1.10.10 - 2015-10-12 =

* FIX: Remove debugging function inadvertantly left in 1.10.8
* TWEAK: Work around bug in WooCommerce Subscriptions 2.0.0 (which is fixed in WooCommerce Subscriptions 2.0.1, so you should update that)

= 1.10.8 - 2015-10-10 =

* FIX: Fix bug that caused some 100% refunded orders to have the refunded amount appear in the dashboard summary table in the row for orders with 'completed' status
* TWEAK: Small internal reorganisation of how the report is generated, allowing easier access from other scripts

= 1.10.7 - 2015-09-11 =

* TWEAK: Add a CSS class to the form element containing a drop-down country selection widget
* FIX: The setting for the store's VAT number (Premium), used for optional extended VAT checks, was rejecting some valid formats.

= 1.10.6 - 2015-08-21 =

* FEATURE: Show the customer's VAT number (if any) in the billing address on the "My Account" page
* TWEAK: Prevent PHP notice being logged when displaying order in admin when no currency conversion was needed

= 1.10.5 - 2015-08-13 =

* TWEAK: Attempt to re-parse returned VIES result with a different encoding if default parse fails on an encoding issue

= 1.10.4 - 2015-08-01 =

* TWEAK: Store's VAT number check (Premium) in readiness report will display an error message, if possible, if validation fails
* COMPATIBILITY: Tested with WooCommerce 2.4 (RC1) and WP 4.3 (RC1). No issues identified (i.e. existing release believed to be compatible).
* TRANSLATION: Updated French translation (thanks to Guy Pasteger)

= 1.10.3 - 2015-07-16 =

* FIX: Remove stray line of code in 1.10.2 which broke the EU VAT control centre page layout

= 1.10.2 - 2015-07-16 =

* FIX: Country selector shortcode now returns its output, instead of echo-ing it (which could cause it to appear in the wrong place)
* FEATURE: (Premium) All (or your selection of) "readiness tests" can now be run automatically daily, with results of any failing tests emailed to specified email addresses.
* FEATURE: The comprehensive CSV download (Premium) now includes an 'Invoice Number' column, if that feature is in use, currently supporting the WooCommerce PDF & Packing Slips plugin
* TWEAK: Remove a couple of error_log() debugging calls left in 1.10.1
* TWEAK: In in-dashboard reports, show all amounts to the number of decimal places configured in the main WooCommerce settings

= 1.10.1 - 2015-07-13 =

* FEATURE: VAT summary report table now has an option to export the table directly as a CSV file
* FEATURE: It is now possible to perform an extended VIES lookup, recording the customer's detailed information (if available) of any customers supplying VAT numbers (thanks to Sven Auhagen for code and ideas)
* FEATURE: Cause the VAT number field to be pre-populated if a logged-in repeat customer checks out
* FEATURE: Show the customer's VAT number (if any) in their profile page (in the WooCommerce customer information section)
* FEATURE: Add support for WPML for multi-language translation of fields shown at the checkout and price suffixes
* FIX: Fix issue which could cause VAT number field to wrongly not appear in certain complicated visiting country/customer country/goods/taxes combinations (required that GeoIP lookup was inaccurate, amongst other conditions)
* TWEAK: Removed a little unused code
* TWEAK: When advising of pre-WC-2.1 orders (which have incomplete information due to WC not recording it before 2.2), indicate which orders specifically are meant.
* TWEAK: It turns out that a WooCommerce order can remain in the 'Payment Pending' state forever, causing a surprising "pre-WC-2.1 order" notice in one of the charts, if a customer comes back to complete a pending order from long ago. The wording of the notice has been changed to reflect this. (Obviously, as time goes on, this condition is even more unlikely to ever be seen).
* TWEAK: Introduce wceuvat_check_cart_items_is_forbidden filter, to allow developers to apply arbitrary customisations to criteria for forbidding check-out for VAT-related reasons
* TWEAK: Stop using PHP 4-style parent constructor call in widget class
* TWEAK: Update bundled TableSorter library to latest (2.22.3)

= 1.9.3 - 2015-06-27 =

* FEATURE: Support for Subscriptio (Premium) (Subscriptio is an alternative to the official WooCommerce Subscriptions extension) - i.e. repeat orders automatically created on a schedule by Subscriptio will have VAT audit/proof of location information copied over from the original order, and the current exchange rates at the order-time will be updated.
* TWEAK: Readiness test in the free version will now alert if the Subscriptio extension is active (the free version does not contain the extra code needed to support it)

= 1.9.2 - 2015-05-07 =

* TWEAK: Prevent PHP notice with bbPress due to current_user_can() being called early

= 1.9.1 - 2015-04-09 =

* FEATURE: In-dashboard reports table now includes "refunds" column
* TWEAK: Added explanatory note and link to WooCommerce refunds documentation, to help users understand the meaning/derivation of refunds data
* TWEAK: Updated a couple of the plugin screenshots
* TWEAK: Added free/Premium comparison table to free version
* TRANSLATIONS: Updated POT file
* FIX: Fix a bug in 1.9.0 that caused 100% discounted orders (i.e. 100% coupon) to result in an erronenous message appearing in the reports dashboard

= 1.9.0 - 2015-04-08 =

* FEATURE: The order-page widget now additionally displays VAT refund information, if a refund exists on the order
* FEATURE: The CSV download (Premium) now contains additional column with VAT refund information (per-rate, and total, in both order and reporting currencies)
* TWEAK: Premium version now contains support link to the proper place (not to wordpress.org's free forum)
* FIX: "Export CSV" button/link did not handle the chosen date range correctly in all situations
* FIX: Bug that caused items in orders with the same VAT rate, but which differed through some being digital VAT and others traditional VAT (i.e. physical goods), being wrongly banded together in CSV download VAT summaries.

= 1.8.5 - 2015-04-02 =

* FEATURE: Add "Items (without VAT)" column to dashboard VAT report. (Requires all orders in the selected period to have been made with WC 2.2 or later).
* TWEAK: Tested + compatible with WP 4.2 and later (tested on beta3-31975)

= 1.8.4 - 2015-03-24 =

* TWEAK: Prevent PHP notice when collating report data on orders recorded by older versions of the plugin
* TWEAK: Change the default order statuses selected on the reports page to 'completed' and 'processing' only. (It's unlikely that data for orders with statuses like 'failed' or 'pending payment' are what people want to see at first).
* TWEAK: Cause selected status boxes on the report page to be retained when selecting a different quarter

= 1.8.3 - 2015-03-16 =

* FIX: Correct one of the VAT column names in the CSV download
* FIX: Display 0, not 1, where relevant in secondary VAT columns in the CSV download
* FIX: Prevent fatal error on reports page if the user had never saved their settings.
* TWEAK: If the user has never saved their settings, then default to using ECB as the exchange rate provider (instead of saving no currency conversion information).
* TRANSLATION: Updated POT file, and updated French and Finnish translations.

= 1.8.1 - 2015-03-13 =

* FIX: Fix issue in updater that could cause blank page on some sites

= 1.8.0 - 2015-03-05 =

* FIX: Reports table now sorts on click on column headings again (unknown when it was broken)
* FEATURE: EU VAT report now re-coded to show data in the configured reporting currency (only), and to show shipping VAT separately
* FEATURE: Downloadable CSV now shows separate VAT totals for each rate in separate rows, and shows separate rows for variable and traditional non-variable VAT (if your shop sells both kinds of goods)
directory due to licensing complications.
* FEATURE: Downloadable CSV now shows information on the configured reporting currency (as well as the order currency)
* FEATURE: (Premium) - updater now added so that the plugin integrates fully with the WP dashboard's updates mechanism
* TWEAK: Removed the static 'rates' column from the VAT report table (which only showed the current configured rates), and instead show a row for each rate actually charged.
* TWEAK: Reports page now uses the built-in WooCommerce layout, including quick-click buttons for recent quarters (some code used from Diego Zanella, gratefully acknowledged)
* TWEAK: Columns in downloadable CSV are now translatable (translations welcome)
* TWEAK: Re-ordered and re-labelled some columns in CSV download for clarity
* TWEAK: Provide link to download location for geoip-detect plugin, if relevant - it is no longer present in the wordpress.org
* TRANSLATION: New POT file

= 1.7.8 - 2015-02-28 =

* TRANSLATION: Finnish translation, courtesy of Arhi Paivarinta

= 1.7.7 - 2015-02-23 =

* FIX: Deal with undocumented change in WC's tax tables setup in WC 2.3 - the "add/update rates" feature is now working again on WC 2.3

= 1.7.6 - 2015-02-20 =

* TWEAK: VAT number fields will no longer appear at the check-out if there were no VAT-liable items in the cart
* TWEAK: Add wc_eu_vat_default_vat_number_field_value filter, allowing developers to pre-fill the VAT number field (e.g. with a previously-used value)

= 1.7.5 - 2015-02-17 =

* TWEAK: If on WC 2.3 or greater, then use WC's built-in geo-location code for geo-locating, and thus avoid requiring either CloudFlare or a second geo-location plugin.
* TWEAK: Avoided using a deprecated method in WC 2.3

= 1.7.4 - 2015-02-13 =

* FIX: The HMRC (UK) decided to move their rates feed to a new URL this month (again!), removing one of the under-scores from the URL (also see changelog for 1.6.7). This fix will also be OK next month in case this was a mistake and they revert, or even if they switch back to Dec 2014's location. Update in order to make sure you are using current rates.

= 1.7.2 - 2015-02-07 =

* COMPATIBILITY: Tested on WooCommerce 2.3 (RC1). Note that WooCommerce EU VAT Compliance will over-ride WooCommerce 2.3's built-in geo-location features - so, you should not need to adjust any settings after updating to WooCommerce 2.3. WooCommerce 2.0 is no longer officially supported or tested (though this release is still believed to be compatible).
* TWEAK: Add order number to the CSV download (allowing order number to differ from the WooCommerce order ID - e.g. if using http://www.woothemes.com/products/sequential-order-numbers-pro/).
* TWEAK: Introduce WC_EU_VAT_NOCOUNTRYPRESELECT constant, allowing you to disable the built-in country pre-selection (if, for example, you already have an existing solution)

= 1.7.1 - 2015-01-20 =

* FIX: No longer require the shop base country to be in the EU when applying VAT exemptions for B2B customers
* FEATURE: Add an option for a separate checkbox for the "show prices without taxes" option in the country-selection widget (in addition to the existing, but not-necessarily-easy-to-find, menu option on the country list)
* TRANSLATION: Updated French translation (thanks to Guy Pasteger)

= 1.7.0 - 2015-01-13 =

* USER NOTE: This plugin is already compatible with version 2.0 of the GeoIP detect plugin, but if/when you update to that, you will need to update the GeoIP database (as version 2.0 uses a new format) - go to Tools -> GeoIP Detection ... you will then need to reload the dashboard once more to get rid of the "No database" warning message.
* FEATURE: Optionally forbid checkout if any goods liable to EU VAT are in the cart (this can be a better option than using WooCommerce's built-in feature to forbid all sales at all to EU countries - perhaps not all your goods are VAT-liable. Note that this is a stronger option that the existing option to only forbid consumer sales (i.e. customers who have no access to VAT exemption via supply of a VAT number))
* FEATURE: Support mixed shops, selling goods subject to EU VAT under the 2015 digital goods regulations and other goods subject to traditional base-country-based VAT regulations. The plugin supports this via allowing you to identify which tax classes in your WooCommerce configuration are used for 2015 digital goods items. Products which you place in other tax classes are not included in calculations/reports made by this plugin for per-country tax liabilities, even if VAT was charged upon them. (For such goods, you calculate how much you owe your local tax-man by using WooCommerce's built-in tax reports).
* FEATURE: Within {iftax}{/iftax} tags, you can use the special value value {country_with_brackets} to show the country that tax was calculated using, surrounded by brackets, if one is relevant; or nothing will be shown if not. Example: {iftax}incl. VAT {country_with_brackets}{/iftax}. This is most useful for mixed shops, where you will not what the confuse the customer by showing the country for products for which the VAT is not based upon country.
* FIX: Country pre-selection drop-down via shortcode was not activating if the page URL had a # in it.
* FIX: Unbalanced div tag in Premium plugin on checkout page if self-certification was disabled.
* TWEAK: Negative VAT number lookups are now cached for 1 minute instead of 7 days (to mitigate the possible undesirable consequences of cacheing a false negative, and given that we expect very few negatives anyway)
* TWEAK: Change prefix used for transient names, to effectively prevent any previously cached negative lookups for certain valid Spanish VAT numbers (see 1.6.14) being retained, without requiring the shop owner to manually flush their transients.
* TRANSLATION: Updated French translation (thanks to Guy Pasteger)

= 1.6.14 - 2015-01-10 =

* FEATURE: Upon discovery of a valid Spanish VAT number which the existing API server did not return as valid, we now use the official VIES service directly, and fall back to a second option if that does not respond positively (thus adding some redundancy if one service is down).
* FEATURE: VAT number validity at the checkout is now checked as it is typed (i.e. before order is placed), and feedback given allowing the customer to respond (e.g. hint that you have chosen a different country to that which the VAT number is for).
* FEATURE: Support for the official exchange rates of the Central Bank of the Russian Federation (http://www.cbr.ru)
* TWEAK: Move the position of the "VAT Number" field at the checkout to the bottom of the billing column, and make it filterable
* TWEAK: If Belgian customer enters a 9-digit VAT number, then automatically prefix with a 0 (https://www.gov.uk/vat-eu-country-codes-vat-numbers-and-vat-in-other-languages)
* TRANSLATIONS: Updated POT file

= 1.6.13 - 2015-01-08 =

* FIX: The button to add tax rates was not appearing when WordPress was en Français.
* TWEAK: Add TVA/T.V.A. to the list of taxes recognised as VAT by default
* TWEAK: Readiness test in the free version will now alert if the WooCommerce Subscriptions extension is active (free version does not contain the extra code needed to support it)
* TWEAK: Add link in the control centre to the official EU PDF detailing current VAT rates

= 1.6.12 - 2015-01-06 =

* FEATURE: CSV downloads now take notice of the chosen dates in the date selector widget (reports) (i.e. so you can now also download selected data, instead of only downloading all data)
* FIX: Some more translated strings are now translated in the admin interface.
* FIX: Restore functionality on WooCommerce < 2.2 (checkout broken in 1.6.0)
* FIX: Don't tweak the "taxes estimated for" message on the cart page on WooCommerce < 2.2.9, since the country choice widget requires this version
* FIX: The button on the report date selector form, if accessed via the compliance centre (rather than WooCommerce reports) was not working

= 1.6.11 - 2015-01-06 =

* FIX: Restore ability to run on PHP 5.2
* FIX: If no current exchange rates were available at check-out time, and HTTP network download failed, then this case was handled incorrectly.
* FIX: Some settings strings were not being translated in the admin interface.
* FIX: "Taxes estimated for" message on the cart page now indicates the correct country
* TWEAK: Move widget + shortcode code to a different file
* TWEAK: CSV order download will now only list orders from 1st Jan 2015 onwards, to prevent large numbers of database queries for orders preceeding the VAT law on shops with large existing order lists.
* TWEAK: CSV order download will now intentionally show orders from non-EU countries (since these could be subject to audit for compliance also); a later release will make this optional. Before, these orders were shown, though not intentionally, and the data was incomplete.
* TRANSLATION: Updated French translation (thanks to Guy Pasteger)

= 1.6.9 - 2015-01-04 =

* FIX: Download of current VAT rates via HTTP was not working (bundled copy of rates in the plugin always ended up getting used)
* FEATURE: New readiness tests added for checking access to current VAT rates via network, checking that each country has an entry in a tax table, and checking that they agree with the apparent current rates.
* TWEAK: Don't load un-needed PHP classes if not in admin area (minor performance improvement)

= 1.6.8 - 2015-01-03 =

* FEATURE: VAT rate tables can now be pre-filled for any tax class (not just WooCommerce's built-in standard / reduced), and you can choose which rates to fetch them from
* FIX: Fix bug (since 1.6.0) in the free version that caused any widget-selected country's VAT rate to be applied at the check-out, despite other settings.
* FIX: Where no reduced rate exists (currently, Denmark), the standard rate is added instead
* UPDATE: Default VAT rates for Luxembourg updated to reflect new values (Jan 2015) - you will need to update your WooCommerce tax tables to pick up the new rates
* TWEAK: Round prices before comparing taxed and untaxed prices (otherwise two actually identical prices may apparently differ due to the nature of PHP floating point arithmetic - which could cause an "including tax" label to show when tax was actually zero)
* TWEAK: CSV spreadsheet download now supplies date in local format (as well as standard ISO-8601 format) (suggestion from Guy Pasteger)
* TWEAK: Date entry boxes in the control centre now have a date-picker widget (as they did if used from the WooCommerce reports page)
* TWEAK: Record + display information on which exchange rate provider was used to convert (useful for audit), and the recorded rate
* TWEAK: Added new readiness test: tests that all coupons are applied before tax (doing so after tax leads to non-compliant VAT invoices)
* TWEAK: Added new readiness test: check that tax is enabled for the store
* TRANSLATIONS: Updated POT file

= 1.6.7 - 2015-01-01 =

* TWEAK: Added a 'classes' parameter to the [euvat_country_selector] shortcode, allowing CSS classes to be added to the widget
* TWEAK: Correct filter name in base XML provider
* FIX: "VAT Number" heading would sometimes show at the check-out when it was not needed (Premium)
* FIX: The HMRC (UK) decided to move their rates feed to a different URL this month, swapping hyphens for under-scores. How stupid. This fix will also be OK next month in case this was a mistake and they revert.

= 1.6.6 - 2014-12-31 =

* FIX: Fix bug that could cause the 'Phrase matches used to identify VAT taxes' and 'Invoice footer text (B2C)' settings to be reset to default values.
* TWEAK: Add help text to the settings in the control centre, mentioning the {iftax} and {country} tags.
* TWEAK: Automatic entries in WooCommerce tables now show the VAT rate in the name - because compliant invoices in some states require to show the rate. It is recommended that you go and update your tables in WooCommerce -> Settings -> Tax -> (rate), if this applies to you (you may need to delete all existing rows).

= 1.6.5 - 2014-12-31 =

* TWEAK: Those with non-EU billing addresses (or shipping, if that's what you're using) are no longer exempted from other checks (specifically, self-certification in the case of an address/GeoIP conflict). This release is for the Premium version only (since the tweak does not affect the free version).

= 1.6.4 - 2014-12-31 =

* FEATURE: Support official exchange rates from the Danish National Bank (https://www.nationalbanken.dk/en/statistics/exchange_rates/Pages/Default.aspx)
* TRANSLATION: German translation is now updated, courtesy of Gunther Wegner.
* TRANSLATION: New French translation, courtesy of Guy Pasteger.

= 1.6.3 - 2014-12-30 =

* FEATURE: You can now enter special values in WooCommerce's 'Price display suffix' field: anything enclosed in between {iftax} and {/iftax} will only be added if the item has taxes; and within that tag, you can use the special value {country} to show the country that tax was calculated using. Example: {iftax}incl. VAT{/iftax} More complicated example: {iftax}incl. VAT ({country}){/iftax}
* FIX: Resolve issue that required self-certification even when none was required, if the user was adding an extra option to the self-certification field via a filter.

= 1.6.2 - 2014-12-30 =

* FIX: Remove debugging code that was inadvertantly left in 1.6.0
* FIX: Fix fatal PHP error in admin products display (since 1.6.0)

= 1.6.0 - 2014-12-30 =

* FEATURE: Detect visitor's country and display prices accordingly on all shop pages from their first access (requires WooCommerce 2.2.9 or later; as noted in the WooCommerce changelog - https://wordpress.org/plugins/woocommerce/changelog/ - that is the first version that allows the taxable country to be changed at this stage). This feature also pre-sets the billing country on the check-out page.
* FEATURE: Option to make entry of VAT number for VAT exemption either optional, mandatory, or not possible. (Previously, only 'optional' was available). This means that store owners can decide to always charge VAT, or to not take orders from EU customers who are not VAT exempt. (Non-EU customers can still make orders; if you do not wish that to be possible, then there are existing WooCommerce settings for that). (This option is only relevant to the premium version, as the free version has no facility for entering VAT numbers).
* FEATURE: Support for WooCommerce subscriptions (Premium)
* TWEAK: Self-certification option now asks for 'country of residence', rather than of current location; to comply with our updated understanding of what the user should be asked to do. (But note that the message was, and continues to be, over-ridable via the wc_eu_vat_certify_message filter).
* TWEAK: Make it possible (via a filter, wc_eu_vat_certify_form_field) to not pre-select any option for the self-certified VAT country. If your view is no option should be pre-selected, then you can use this filter. (We offer you no legal or taxation advice - you are responsible to consult your own local resources).
* TWEAK: First beginnings of the readiness report: will now examine your WC version and "tax based on" setting.
* TWEAK: EU VAT report now moved to the 'Taxes' tab of the WooCommerce reports (from 'Orders')
* TRANSLATION: German translation is now complete, courtesy of Gunther Wegner. POT file updated.

= 1.5.7 - 2014-12-29 =

* FEATURE: Add the option to add configurable footer text to invoices produced by the <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">WooCommerce PDF invoices and packing slips plugin</a>, if VAT was paid; or a different message is a valid VAT number was added and VAT was removed.
* FEATURE: New German translation, courtesy of Gunther Wegner

= 1.5.6 - 2014-12-27 =

* FEATURE (Premium): Option to display converted amounts for VAT taxes on invoices produced by the <a href="https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/">WooCommerce PDF invoices and packing slips plugin</a>.
* TWEAK: Prevent many useless database queries for reports when handling orders made before the plugin was active
* TWEAK: Prevent a PHP notice on WC < 2.2 for the VAT number field at the checkout
* FIX: Prevent PHP notices + missing data for some currency combinations in the CSV spreadsheet download

= 1.5.5 - 2014-12-26 =

* FIX: Monaco and the Isle of Man were previously being erroneously omitted from reports, despite being part of the EU for VAT purposes
* FIX: The Isle of Man was being missed when rates were being automatically added/updated
* FEATURE: If the customer added a VAT number for VAT exemption (Premium), then it will be appended to the billing address, where relevant (e.g. email order summary, PDF invoices). Credit to Diego Zanella for the idea and modified code.
* FEATURE: Rate information is now saved at order time in more detail, and displayed by rate; this is important data, especially if you sell goods which are not all in the same VAT band (i.e. different VAT bands in the same country, e.g. standard rate and reduced rate)
* TWEAK: Move compliance information on the order screen into its own meta box
* TWEAK: Exchange rate information is now stored with the order in a more convenient format - we recommend you update (though, the old format is still supported; but, it's not 1st Jan yet, so actually we recommend you apply every update until then, as nobody has a good reason to be running legacy code before the law launches).

= 1.5.4 - 2014-12-26 =
 
* FIX: Back-end order page now shows the VAT paid as 0.00 instead of 'Unknown', if a valid VAT number was entered. The VAT number is also shown more prominently.
* FIX: Add missing file to 1.5.2 release (exchange rate providers were not working properly without it)
* TWEAK: Settings page will now require the user to confirm that they wish to leave, if they have unsaved changes

= 1.5.2 - 2014-12-24 =

* TWEAK: Re-worked the exchange rate cacheing layer to provide maximum chance of returning an exchange rate (out-of-date data is better than no data)

= 1.5.1 - 2014-12-24 =

* FEATURE: Added the European Central Bank's exchange rates as a source of exchange rates

= 1.5.0 - 2014-12-24 =

* FEATURE: Currency conversion: if your shop sells in a different currency than you are required to make VAT reports in, then you can now record currency conversion data with each order. Currently, the official rates of HM Revenue & Customs (UK) are used; more providers will be added.

= 1.4.2 -2014-12-23 =

* FEATURE: Control centre now contains relevant WooCommerce settings, and links to tax tables, for quick access

= 1.4.1 - 2014-12-22 =

* FEATURE: Dashboard reports are now available on WooCommerce 2.2, with full functionality (so, now available on WC 2.0 to 2.2)
* FEATURE: All versions of the plugin can now select date ranges for reports
* FEATURE: Download all VAT compliance data in CSV format (Premium version)
* TWEAK: Report tables are now sortable via clicking the column headers

= 1.4.0 - 2014-12-19 =

* FEATURE: Beginnings of a control centre, where all functions are brought together in a single location, for ease of access (in the dashboard menu, WooCommerce -> EU Vat Compliance)
* TRANSLATIONS: A POT file is available for translators to use - http://plugins.svn.wordpress.org/woocommerce-eu-vat-compliance/trunk/languages/wc_eu_vat_compliance.pot

= 1.3.1 - 2014-12-18 =

* FEATURE: Reports have now been added to the free version. So far, this is still WC 2.0 and 2.1 only - 2.2 is not yet finished.
* FIX: Reporting in 1.3.0 was omitting orders with order statuses failed/cancelled/processing, even if the user included them

= 1.3.0 - 2014-12-18 =

* FEATURE: Premium version now shows per-country VAT reports on WooCommerce 2.0 and 2.1 (2.2 to follow). Which reporting features will or won't go into the free version is still to-be decided.
* FIX: The value of the "Phrase matches used to identify VAT taxes" setting was reverting to default - please update it again if you had attempted to change it (after updating to this version)
* IMPORTANT TWEAK: Order VAT is now computed and stored at order time, to spare the computational expense of calculating it, order-by-order, when reporting. You should apply this update asap: orders made before you upgrade to it will not be tracked in your report. (Note also that reporting features are still under development, in case you're wondering where they are - they're not technically needed until the 1st quarter of 2015 ends, and only need to cover from 1st Jan 2015 onwards). 

= 1.2.0 - 2014-12-12 =

* COMPATIBILITY: Tested on WordPress 4.1
* TWEAK: Code re-factored
* TWEAK: Re-worked the readme.txt file to reflect current status
* FEATURE: Premium version has been launched: https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/
* FEATURE (Premium version): Ability to allow the customer to enter their VAT number, if they have one, and (if it validates) be exempted from VAT transactions. Compatible with WooCommerce's official extension (i.e. you can remove that extension, and your old data will be retained).
* FEATURE (Premium version): Dealing with conflicts: if the customer's IP address + billing (or shipping, according to your WooCommerce settings) conflict, then optionally the customer can self-certify their country (or, you can force them to do this always, if you prefer).
* FIX: The initial value of the "Phrase matches used to identify VAT taxes" setting could be empty (check in your WooCommerce -> Settings -> Tax options, if you are updating from a previous plugin version; the default value should be: VAT, V.A.T, IVA, I.V.A., Value Added Tax)

= 1.1.2 - 2014-12-10 =

* FIX: Fix bug which prevented France (FR) being entered into the rates table. If you had a previous version installed, then you will need to either wait 24 hours before pressing the button to update rates since you last did so, or to clear your transients, or enter French VAT (20% / 10%) manually into the tax table.
* TWEAK: Reduce time which current rates are cached for to 12 hours

= 1.1.1 - 2014-12-09 =

* FIX: Fix bug with display of info in admin area in WooCommerce 2.2

= 1.1 - 2014-12-06 =

* GeoIP information, and what information WooCommerce used in setting taxes, is now recorded at order time
* Recorded VAT-relevant information is now displayed in the admin area

= 1.0 - 2014-11-28 =

* First release: contains the ability to enter and update current EU VAT rates

== Screenshots ==

1. A button is added to allow you to enter all EU VAT rates with one click. <em>Note: Screenshots are included below from <a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">the Premium version</a>. Please check the feature list for this plugin to clarify which features are available in which version.</em>

2. VAT information being shown in the order details page

3. Per-country VAT reports

4. Download all compliance information in a spreadsheet.

5. Compliance dashboard, bringing all settings and information into one place

6. Currency conversions, if you sell and report VAT in different currencies.

7. Compliance report, checking a number of common essentials for configuring your store correctly for VAT.

== License ==

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

== Upgrade Notice ==
* 1.29.11 - Update VAT rate for Luxembourg. N.B. Because of code refactoring in 1.29.2, developers updating from versions prior to that who have written custom code based upon previous class structure should check their code. A recommended update for all.
