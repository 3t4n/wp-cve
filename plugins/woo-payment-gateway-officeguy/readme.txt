=== WooCommerce Payment Gateway - SUMIT ===
Contributors: effyteva
Tags: סליקת אשראי, אשראי, סליקה, חיוב באשראי, אופיסגיא, סאמיט, אופיס, אופיס גיא, WooCommerce, Payment, Gateway, Credit Cards, Shopping Cart, OfficeGuy, SUMIT, OfficeGuy Commerce, Israeli clearing, Extension, Subscriptions, Recurring Billing, Membership
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 3.2.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

SUMIT Israeli Payment Gateway for WooCommerce

== Description ==
Improve your WooCommerce site sales by accepting all credit cards
* Securely store customer payments methods for future purchases
* Issue and email invoices/receipts automatically following sales
* Process recurring charges using our Recurring Billing module
* Synchronize your inventory levels with our stock module
* Enable WooCommerce Subscriptions plugin for managing recurring bills
* Combine CartFlows & Cartflows Pro plugins for better sales promotions

== Installation ==
Installation guide is available on https://help.sumit.co.il/he/articles/5830000
Contact us on support@sumit.co.il for support.

== Custom hooks ==
= Custom installments count =
function CustomInstallmentsLogic($MaximumPayments, $OrderValue) {
    $Cart = WC()->cart;
    return 5;
}
add_filter('sumit_maximum_installments', 'CustomInstallmentsLogic');

= Custom customer fields =
function CustomCustomerFields($Customer, $Order) {
    $Customer['Billing last name'] = $Order->get_billing_last_name();
    return $Customer;
}
add_filter('sumit_customer_fields', 'CustomCustomerFields');

= Custom item fields =
function CustomItemFields($Item, $Product, $UnitPrice, $OrderItem, $Order) {
    // Add details to item name
    $Item['Name'] = $Item['Name'] . ' - ' . $Product->get_sku();

    // Remove zero priced items
    if ($UnitPrice == 0)
        return null;

    return $Item;
}
add_filter('sumit_item_fields', 'CustomItemFields');

== Changelog ===
= 3.2.6 =
* Declared incompatibility with checkout blocks. Compatibility will be added in future versions.

= 3.2.5 =
* Fixed API keys validation bug.
* Added additional order metadata fields.

= 3.2.4 =
* Updated WordPress 6.4 support.

= 3.2.3 =
* Removed OfficeGuy branding.

= 3.2.2 =
* Added aria-label for better accessibility support.

= 3.2.1 =
* Added Client IP header, preventing bots attacks.

= 3.2.0 =
* Token storage bugfix when using WooCommerce Subscriptions.
* Updated scripts.js to use the app.sumit.co.il hostname.
* Updated WordPress 6.3 support.

= 3.1.9 =
* sumit_item_fields bug fixes.
* Bugfix related to multi vendors.

= 3.1.8 =
* Minor fix for CartFlows Pro Refunds (feature not supported).

= 3.1.7 =
* Fixed support for Dokan Pro.

= 3.1.6 =
* Fixed rare bug causing incorrect VAT to be calculated, when all Tax Settings are 0.

= 3.1.5 =
* sumit_item_fields bug fixes.

= 3.1.4 =
* Updated support for WordPress 6.1.

= 3.1.3 =
* Rare bugfix on Buy Now button.
* Added sumit_item_fields hook, allowing developers to customize item details.

= 3.1.2 =
* Rare bugfix on Bit payments.

= 3.1.1 =
* Failed Bit payments will no longer redirect to Thank You page.
* Fixed error message visibility issue.

= 3.1.0 =
* Added sumit_customer_fields hook, allowing developers to create customer customer fields logic.

= 3.0.9 =
* Added support for installments when cart contains WooCommerce Subscription with trial.

= 3.0.8 =
* Show SUMIT Donations checkbox on variable products when taxes are disabled.

= 3.0.7 =
* Added support for custom thank you pages when using Redirect.

= 3.0.6 =
* Copy order notes to created document.
* Added sumit_maximum_installments hook, allowing developers to create custom installments logic.
* Updated support for WooCommerce 6.0.

= 3.0.5 =
* Fixed stock sync API endpoint.

= 3.0.4 =
* Added support for WPFunnels.

= 3.0.3 =
* Prevent setting SUMIT recurring combined with WooCommerce Subscriptions.

= 3.0.2 =
* Updated support for WooCommerce 5.9.
* Fixed support for issuing donation receipts following PayPal payments.
* Updated API endpoint to api.sumit.co.il (instead of www.myofficeguy.com) with full compatibility for OfficeGuy users.

= 3.0.1 =
* Updated SUMIT icon.

= 3.0.0 =
* Renamed to SUMIT
* Added support for PayPal gateway (ppcp-gateway)

= 2.7.3 =
* Improved support for custom templates using Material Design fields.
* Minor bugfixes.

= 2.7.2 =
* Minor admin bugfix from 2.7.1.

= 2.7.1 =
* Bugfix on sending documents when sending is disabled.

= 2.7.0 =
* Added support for WCVendor (Multi Vendor).
* Bugfix when attempting to mix OfficeGuy Recurring items with WooCommerce Subscriptions.

= 2.6.7 =
* Better support for mobile devices - input fields will now open numeric keyboard.

= 2.6.6 =
* Bugfix on "Buy Now" button on Variation products.

= 2.6.5 =
* Added support for Trial subscriptions when using WooCommerce Subscriptions.

= 2.6.4 =
* Added credentials validation on the settings page.

= 2.6.3 =
* Bugfix on CartFlow Downsells.

= 2.6.2 =
* "Buy Now" button now works on Variation products as well.

= 2.6.1 =
* Added support for settings products as donations.

= 2.6.0 =
* Added support for Dokan marketplace (Multi Vendor).
* Added support for WCFM marketplace (Multi Vendor).

= 2.5.6 =
* Added support for WooCommerce Subscriptions orders when using Downsells on CartFlow.

= 2.5.5 =
* ZipCode/PostCode will now be added to Customers on OfficeGuy.

= 2.5.4 =
* Created invoices will now show free shipping.
* Bugfix on installments transaction when tokens are supported.

= 2.5.3 =
* Updated Plugin support for WordPress 5.8.

= 2.5.2 =
* Bugfix on handling failed subscriptions payments.

= 2.5.1 =
* Bugfix on rare scenarios in which Bit orders weren't marked as complete.

= 2.5.0 =
* Added support for updating payment method on subscription page.
* Added the current active payment method last 4 digits on the subscription page.
* Bugfix on sending documents following Bit payments.

= 2.4.8 =
* Improved support for Polylang.

= 2.4.7 =
* Bugfix on rare rounding issues.

= 2.4.6 =
* Added support for receiving payments on Bit

= 2.4.5 =
* Added support for orders with a total amount of 0.

= 2.4.4 =
* Improved support for WooCommerce Subscriptions (Store payment method per subscription).
* Improved debugging logs.
* Bugfix on stock update.

= 2.4.3 =
* Rare scenario bugfix.

= 2.4.2 =
* Added support for product SKU field.

= 2.4.1 =
* Improved settings documentation.

= 2.4.0 =
* Improved settings documentation.

= 2.3.2 =
* WooCommerce Subscriptions bugfix.
* Fixed missing localization.

= 2.3.1 =
* Increased API calls timeout from 60s to 180s.

= 2.3.0 =
* Bugfix

= 2.2.9 =
* Added support for automatically creating documents on previously created orders.
* Bugfix on payments transaction when using External page.

= 2.2.8 =
* Added support for payments transaction when using External page.
* Added support for customer country and state.

= 2.2.7 =
* Added support for customer address 2.
* Changed default layout to single column.

= 2.2.6 =
* Bugfix on PayPal async documents producing.

= 2.2.5 =
* Added support for producing async documents following PayPal payments (for cases when both IPN and PDT are enabled).

= 2.2.4 =
* Added support for PW WooCommerce Gift Cards.

= 2.2.3 =
* Async creation of documents following payment on other providers (such as PayPal).
* Fixed payment method storing when checking out WooCommerce Subscriptions for guest users.

= 2.2.2 =
* Fixed incorrect shipping amount on rare scenarios.
* Fixed duplicate documents produced on PayPal when both IPN and PDT are enabled.

= 2.2.1 =
* Improved support for variation products.

= 2.2.0 =
* Added support for authorization only transactions (J5).

= 2.1.4 =
* Fixed incorrect behavior for installments on rare scenarios.
* Added support for VAT exempt indicator on recurring orders.

= 2.1.3 =
* Bugfix for processing credits from the backend.

= 2.1.2 =
* Added support for processing credits from the backend.

= 2.1.1 =
* Improved stock sync (now syncs using product names in addition to SKUs/External Identifiers).

= 2.1.0 =
* Added support for combined order of recurring and standard items.
* Added full support for CartFlows pro upsales flow.
* Saving tokens bugfix.

= 2.0.8 =
* Added support for CartFlows checkout page upsales.
* Added orders metadata containing created Document and Customer identifiers.
* Added support for guest orders.

= 2.0.7 =
* Added warning notification on payment page when Testing mode is enabled.
* Added warning notification on payment page when API keys aren't setup properly.
* Fixed incorrect behavior with sending emails to integration based transactions.

= 2.0.6 =
* Added support for Order fees.
* Added support for pay for order page.
* Fixed issue with tokens being resaved when using existing payment method.
* Fixed tokens layout issue.

= 2.0.5 =
* Added support for integration with custom payment provider for automatic invoices/receipts producing following checkout.
* Fixed bug on stock sync.

= 2.0.4 =
* Fixed upgrade process from previous versions.
* Fixed JavaScript error on Advanced input mode.

= 2.0.3 =
* Added support for order documents creation following payments using external page (Redirect).
* Added support for updating order status and additional payment information following payments using external page (Redirect).

= 2.0.2 =
* Store PayPal Transaction ID on the created document.

= 2.0.1 =
* Additional bug fixes.

= 2.0 =
* Breaking Change! Recurring items using OfficeGuy Recurring should be re-configured.
* Complete rewrite of PayPal invoices/receipts producing (not using IPN anymore).
* Added support for storing secure payment methods tokens and processing token transactions (not supported on Redirect flow).
* Added support for producing an Order document in addition to invoice/receipt.
* Added support for WooCommerce Subscriptions management.
* Added support for integration with PayPal express checkout & BlueSnap for automatic invoices/receipts producing following checkout.
* Added support for processing payments from Admin backend.
* Added support for OfficeGuy recurring products with a duration of up to 12 months.
* Added support for additional currencies (JPY, SEK, NOK, DKK, ZAR, JOD, LBP, EGP, BGN, CZK, HUF, PLN, RON, ISK, HRK, RUB, TRY, BRL, CNY, HKD, IDR, INR, KRW', MXN, MYR, NZD, PHP, SGD, THB).
* Added support for WordPress 5.5
* Improved fields validations messages.

= 1.5.6 =
* Minor CSS improvement for better themes support.

= 1.5.5 =
* Added support for stock sync for Variation products.

= 1.5.4 =
* JS file loading bugfix.

= 1.5.1 =
* Added support for "Buy Now" button.

= 1.5.0 =
* Added support for updating stock on purchase.
* Added redirect to settings page after plugin activation.
* Added settings button on the plugins list page.
* Fixed stock sync bug.
* Fixed logging bug.
* Improved localizations.

= 1.4.9 =
* Duplicate PayPal receipts bugfix.

= 1.4.8 =
* Menus bugfix.

= 1.4.7 =
* Improved card number input layout.

= 1.4.6 =
* Automatically hide other payment methods on recurring payments.

= 1.4.5 =
* Added support for redirect payment flow.
* Minor UI fixes.

= 1.4.4 =
* Bugfix: Support for installing when WooCommerce isn't installed.

= 1.4.3 =
* Bugfix: Support for LearnDash course products.

= 1.4.2 =
* Bugfix: Support for LearnDash course products.

= 1.4.1 =
* Show year as 2 digits by default.
* Improved card number layout (month followed by year).
* Bugfix: support for sending emails following recurring charge.
* Bugfix: CSS not loaded properly.

= 1.4 =
* Added support for recurring payments.
* Added support for syncing stock.

= 1.3.4 =
* Added setting for single column layout.
* Improved translations.

= 1.3.2 =
* Improved mobile support.
* Minor UI bugfix with the expiration date required indicator.

= 1.3.1 =
* CSS issue.

= 1.3.0 =
* Fixed validation fields handling bug.
* Minor UI bugfix with the expiration date fields.
* Separate CSS file.

= 1.2.9 =
* Improved validation fields handling.
* Added English error messages for invalid cards.

= 1.2.8 =
* Added option to set CVV/Citizen ID fields as required.
* Prevent submitted form without card number/expiration.
* Improved translations.
* Fixed broken links.

= 1.2.7 =
* Improved translations.
* Minor bugfixes.

= 1.2.6 =
* Fixed default environment.
* Minor bugfixes.

= 1.2.5 =
* Improved support for automatic invoice generation following PayPal checkout.
* Fixed incorrect documentation URL.

= 1.2.4 =
* Added support for advanced payments count settings.

= 1.2.3 =
* Create new customers on OfficeGuy with their credit card citizen ID.

= 1.2.2 =
* Added support for localized error messages.
* Added option for choosing between 2/4 years digits display.
* Improved settings page layout.
* Improved settings translations to Hebrew.

= 1.2.1 =
* Bug fixes.
* Updated support for WordPress 5.2.1.

= 1.2.0 =
* Improved support for custom WooCommerce tax settings.
* Improved latest version support (Updated to newer API methods).

= 1.1.9 =
* Improved support for custom WooCommerce tax settings.

= 1.1.8 =
* Updated support for WordPress 5.1.

= 1.1.7 =
* Added support for creating automatic invoice/receipt following PayPal payments.

= 1.1.6 =
* Bugfix (Fixed JSON request).

= 1.1.5 =
* Bugfix (Customer ID = 0 when customer isn't saved).

= 1.1.4 =
* Added support for tax settings.
* Added setting for issuing draft documents.

= 1.1.3 =
* Improved layout of fields (tooltips).
* Added support for merging customers.
* Improved form integration.
* Bug fixes.

= 1.1.2 =
* Added support for multiple Shva merchant numbers (multiple terminals).

= 1.1.1 =
* Added support for foreign currencies (USD/EUR/CAD/GBP/CHF/AUD).
* Resized input fields to 10em instead of px (citizen id, cvv).
* Fixed version number.

= 1.1.0 =
* Added support for foreign currencies (USD/EUR/CAD/GBP/CHF/AUD).
* Resized input fields to 10em instead of px (citizen id, cvv).

= 1.0.9 =
* Added automatic document language setting (enabled by default).
* Use company name when available.

= 1.0.8 =
* Bug fix (Added support for installing when WooCommerce is deactivated).
* Removed autocomplete from fields.

= 1.0.7 =
* Minor bug fix (add_payment_method_options removed).

= 1.0.6 =
* Updated Tags.

= 1.0.5 =
* Added credit card payments support.
* Added full Hebrew translation to the settings page.

= 1.0.3, 1.0.4 =
* Tagged with support for WordPress 4.9.

= 1.0.2 =
* Added remark details (Auth number, Last card digits, Payment ID, Document ID, Customer ID).

= 1.0.1 =
* Initial version.