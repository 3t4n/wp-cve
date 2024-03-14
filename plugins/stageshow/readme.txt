=== StageShow ===
Contributors: Malcolm-OPH
Donate link: https://www.corondeck.co.uk/StageShow/donate.html
Tags: admin, calendar, cart, cinema, e-commerce, events, mollie, pages, payment, payments, paypal, posts, show, stripe, theater, theatre, tickets, trolley, user
Requires at least: 3.0
Tested up to: 6.4.1
Stable tag: 9.8.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

StageShow adds the facility for an online Box-Office for Small Theatres/Drama Groups, records sales, validates tickets and provides sales downloads.

== Description ==

StageShow provides a number of simple admin pages to allow the definition of Show names, Performance date and times and ticket Prices and types. Then a single Wordpress shortcode adds a online BoxOffice to your website.

StageShow uses its own integrated Shopping Trolley to collect orders, and a "Payment Gateway" to collect payments, allowing purchasers to pay using a credit/debit card. StageShow records sales and to collects buyer information from the gateway. See thebFeatures Summary below for full details).

Each sale is fully recorded, with contact and payment details, tickets purchased and the transaction reference from the payment gateway, all saved to the Wordpress database. Confirmation emails containing these details, which can be customised as required, are sent to each purchaser and can be copied to the system administrator. Emails use the MIME encoded email format which allows fonts, images etc. to be defined in the email template.

StageShow includes the facility on the admin pages to verify the transaction number for use at show time. Sales can also be exported to a "TAB Separated Text" file to produce a printed sales list, or for further processing by other applications (i.e. Spreadsheets etc.).

StageShow Features Summary

* No limit on number of Shows or Performances
* Unlimited number of Ticket Types for each performance
* Unlimited user defined "Price Plans" to set prices when adding a performance
* "Admin Only" ticket prices (only available via Admin menus)
* Discounted Tickets/Discount Codes
* Allocated Seating
* Customisable Seating Layouts
* Optional ticket Reservations for logged in users (i.e. Unpaid ticket sales)
* Reservation Client Details captured from Users Profile 
* Integrated Shopping Trolley
* Integrated Payment PayPal, PayFast, Stripe or Mollie Payment Gateways 
* Payments accepted using Credit/Debit cards
* EMail confirmation of Booking to Client and (optionally) to Administrator
* MIME Encoded EMails so HTML/Text mixed format emails supported
* Barcode or QR Code of Transaction ID in sale confirmation emails
* Logging of Online Ticket Validation attempts
* Multiple Terminal Support for Verification
* Editing of Sale Entries
* Show title output on Box Office page customisable per show (text/HTML) 
* Customisable Performance titles on Box Office page  per performance (text/HTML)
* Optional sales summary EMail (to a specified email address) on each new sale 
* Booking Closing Time can be specified for each performance
* Custom Style Sheets
* Manual entry of ticket sales for telephone sales etc.
* Online and Offline Sale Transaction ID validation
* Export of Ticket Sales and Settings as "TAB Separated Text" format file
* Access to StageShow Admin pages/features controlled by custom capabilities
* Requires registration with a supported Payment Gateway
* Extensive Help (in PDF format) file shipped with plugin

== Installation ==

First Time Installation

* Download the plugin archive
* Open the Wordpress Dashboard for your site
* Click "Add New" under the "Plugins" menu 
* Select the "Upload Plugin" option 
* Under "Install a plugin in .zip format" browse to the plugin archive file you downloaded
* Click Install Now.
* After it has installed, activate the plugin.

Manual Upgrade/Update

* On the WP Plugins Page deactivate (but do <span style="text-decoration: underline;">NOT</span> delete) the current StageShow plugin
* Using FTP (or your ISPs file manager) delete the current stageshow plugin folder in the wp-content/plugins folder
* Now Proceed as for the First Time Installation

== Frequently Asked Questions ==

= How do I get help? =

* Read these FAQs
* Read the <a href=https://corondeck.co.uk/downloads/StageShowHelp.pdf>documentation</a>
* Contact the plugin author <a href=https://corondeck.co.uk/contact-us/>here</a>. Requests for help that are already well documented may get a sharp response!

= How do I set up StageShow? =

* Install the plugin and activate it
* Go to the StageShow - Settings page and enter your payment gateway details and click "Save Settings"
* Now go to the show, performance and prices pages and set up your show!
* Create a page on your website for the Box Office (or edit an existing one) and add the tag [sshow-boxoffice] to it
		
= What PayPal settings are required? =

PayPal API Access must be enabled - and the associated User, Password, Signature and EMail entries added to "Stageshow" settings. 
		
IPN (Instant Payment Notification) must be enabled for Sales to be recorded by the PlugIn. Payment will still be accepted and the sale will be recorded by PayPal if IPN is disabled. 
Set the "IPN Listener" URL to https://{Your Site URL}/wp-content/plugins/stageshow/stageshow_NotifyURL.php. 

= Why can't I edit the PayPal settings? =

PayPal Login details cannot be changed if one or more performance entries are present. 

The StageShow plugin creates a PayPal "Saved Button" when a performance is added to the show. There is currently no mechanism to recreate these buttons if the PayPal configuration is changed, hence the limitation.

= What WordPress settings are required? =

StageShow needs the WordPress setting of TimeZone to be correctly set-up (i.e. to a City) for time-sensitive functionality to operate correctly. The current value can be found on the Settings->General admin page.
	
= Why can't I delete a show or performances? =

A performance cannot be deleted if there are sales recorded for it and the show start time has not yet been reached. A show cannot be deleted if performances are still configured for it.

= How do I add a Booking Form to my site? =

Add the tag [sshow-boxoffice] to either a new or existing page on your site. This will be replaced by the booking form when the page is viewed by a user.

= Do my purchasers have to have a PayPal account? =

No. Turning on the "PayPal Account Optional" setting on the sellers PayPal account allows purchasers to use a Credit or Debit card without the need for a PayPal account. Details are in the StageShow help file.

= How can I customise the EMails? =

The EMails generated by the StageShow plugin are defined by a template file. 
Template defaults are in the {Plugins Folder}/{Plugin Name}/templates/email folder, which is copied to the {Uploads Folder}/stageshow/email when the plugin is Activated or Updated. The default email template is stageshow_EMail.php.  
The default template can be copied to new a file in the uploads folder, which can then be used to create a custom template, which can then in turn be selected using the Admin->Settings page.

The template file can be modified as required. A number of "Tags" can be included in the EMail template, which will be replaced by data relating to the sale extracted from the database. 

= What tags can be used in the EMail template? =

The following tags can be used in the EMail template:

* [salePPName]	Buyer PayPal Account Details: Name
* [salePPStreet]	Buyer PayPal Account Details: Street
* [salePPCity]	Buyer PayPal Account Details: City
* [salePPState]	Buyer PayPal Account Details: State
* [salePPZip]	Buyer PayPal Account Details: Zip/Post Code
* [salePPCountry]	Buyer PayPal Account Details: Country

* [saleDateTime]	Sale Details: Date and Time
* [saleName]	Sale Details: Buyer Name
* [saleEMail]	Sale Details: Buyer EMail
* [salePaid]	Sale Details: Paid
* [saleTxnId]	Sale Details: PayPal Transaction ID (TxnId)
* [saleStatus]	Sale Details: PayPal Transaction Status
* [saleBarcode] Sale Details: PayPal Transaction ID converted to a Barcodes (Only for StageShow+)

* [startloop]	Marker for the start of a loop for each ticket type purchased
* [endloop]	Marker for the end of the loop 
* [ticketName]	Sale Details: Ticket Name
* [ticketType]	Sale Details: Ticket Type ID
* [ticketQty]	Sale Details: Ticket Quantity

* [organisation]	The Organsiation ID (as on the Settings Page)
* [adminEMail]	The Admin EMail (as on the Settings Page)
* [url]	The Site Home Page URL

= How can I use my own images on the Checkout page? =

Default Images in the {Plugins Folder}/{Plugin Name}/templates/images folder are copied to the {Uploads Folder}/{Plugin Name}/images when the plugin is Activated or Updated. 
Custom images can be copied to this folder (using FTP) and can then be selected using the Admin->Settings page.

= Where is the User Guide? =

A copy of the User Guide, as a pdf file, is included with StageShow distributions. This can be accessed via a link on the Overview page.
The User Guide can also be downloaded or viewed <a href=https://corondeck.co.uk/downloads/StageShowHelp.pdf>here</a>.

== Screenshots ==

1. Screenshot 1: Overview Page
2. Screenshot 2: Seating Plans Setup
3. Screenshot 3: Discount Codes Setup
4. Screenshot 4: Shows Setup
5. Screenshot 5: Price Plans Setup
6. Screenshot 6: Performances Setup
7. Screenshot 7: Performances Setup (Showing Options)
8. Screenshot 8: Ticket Types and Prices Setup
9. Screenshot 9: Sales Log Summary
10. Screenshot 10: Sales Log Summary (Showing Details)
11. Screenshot 11: Admin Tools Page (Showing Sale Verification)
12. Screenshot 12: PayPal Settings Page
13. Screenshot 13: General Settings Page
15. Screenshot 14: Advanced Settings Page
16. Screenshot 15: Reservations Settings Page
17. Screenshot 16: Shows Box Office Page
18. Screenshot 17: Calendar View Page
19. Screenshot 18: Select Seats Page
20. Screenshot 19: Sample EMail

== Changelog ==

= 9.8.6 (06/02/2024) =
* Bug Fix: Multiple calls to plugin activate function (since 9.7.4)
* Bug Fix: Date/Time Picker returns date in different visual format
* Last remnants of StageShowGold and StageShowPlus integrated into code

= 9.8.5 (15/12/2023) =
* Bug Fix: Duplicated inline Javascript (since 9.8.4) 

= 9.8.4 (13/12/2023) =
* Bug Fix: Javascript syntax error on Tools Page (since 9.7.6) 
* Bug Fix: Default Expiry time shows invalid date & time on admin page
* Added Export for Excel to Tools admin page

= 9.8.3 (23/11/2023) =
* Template Editor parser improved
* Bug Fix: Inputs with image have src attribute missing (since 9.7.6) 
* Bug Fix: View EMail on Sales page fails with page expired (since 9.7.6) 
* Tested with WP 6.4.1

= 9.8.2 (19/08/2023) =
* Bug Fix: Invalid SessionIDs in AJAX call (gives jQuery Call Error)
* Shopping Trolley reset on Session Timeout (24 hours)

= 9.8.1 (18/08/2023) =
* Bug Fix: Database Locking when sending EMails can make mailer plugins fail
* Bug Fix: Template Editor uses HTML specialcharacter encoding 

= 9.8 (10/08/2023) =
* Implemented [else] conditional for templates

= 9.7.7 (04/08/2023) =
* Bug Fix: EMail header From address missing line break
* Bug Fix: EMail Invalid From address (blank) PHP Ver<8.0

= 9.7.6 (19/07/2023) =
* Updated for compatibility with PHP 9.0 
* Null parameter values to string functions trapped 
* All echo calls escaped to follow updated plugin design guidelines

= 9.7.5 (15/06/2023) =
* Tested with WP 6.2.2
* Bug Fix: Barcode not shown on "View EMail" page
* Updated for compatibility with Stripe API 2022-11-15
* Added StageShow_ViewSales capability

= 9.7.4 (27/11/2022) =
* Tested with WP 6.1.1
* Mailer (optionally) uses methods in PHPMailer to handle attachments 
* Binary attachments saved to temp file for Mailer (helps with some EMail transport plugins) 
* Bug Fix: Add Diagnostics on Tools->Send Sale EMail not working 

= 9.7.3 (14/11/2022) =
* Bug Fix: Fatal Error on admin pages (since 9.7.2)

= 9.7.2 (12/11/2022) =
* Bug Fix: StageSHow EMail failure when Mail plugin installed (since 2.0.4)
* Bug Fix: Message To Seller not working with Reservations 
* Bug Fix: Decimal value settings may only allow integer values

= 9.7.1 (04/11/2022) =
* Bug Fix: Reservation Form entries not added to sale details
* Tested with WP 6.1

= 9.7 (17/05/2022) =
* MySQL version determines REGEX Library (8.0.4 onwards uses ICU)
* Tested with WP 6.0

= 9.6.16 (03/05/2022) =
* Deprecated POSIX style SQL ([[:<:]] and [[:>:]]) changed to \b style 

= 9.6.15 (01/05/2022) =
* PHP 8 onwards - Required parameter xxxxxxx follows optional parameter notification fixed
* Bug Fix: Templates Edit error when no source file selected
* Bug Fix: Fatal Error Verifying tickets

= 9.6.14 (10/03/2022) =
* Bug Fix: Sessions table size breaks too small for large shopping carts (since 9.6.7)
* Tested with WP 5.9.3
 
= 9.6.13 (18/01/2022) =
* Bug Fix: Reservation Form gives blank entries if elements hidden 
* Content check on reservation email addresses improved 

= 9.6.12 (17/01/2022) =
* Bug Fix: Fatal Error creating barcode on PHP 8 onwards

= 9.6.11 (21/11/2021) =
* Bug Fix: Filter for sales export not working (since 9.3.9)
* Tested with WP 5.8.2

= 9.6.10 (20/11/2021) =
* Bug Fix: Adding a sale gives "wp_sshow_sessions not locked" error

= 9.6.9 (20/10/2021) =
* Bug Fix: Date & Time picker not translated
* Bug Fix: Performance dates not translated

= 9.6.8 (10/10/2021) =
* Bug Fix: SESSIONS table not included in DB Locks

= 9.6.7 (06/10/2021) =
* Bug Fix: REST API reports open session error
* Bug Fix: Fatal Error Cannot declare class Stripe\Stripe (multiple use of Stripe)
* Bug Fix: Zones not saved with new prices (since 9.3.9)
* PHP SESSIONS replaced by Stageshow Sessions table entries
* Added Warning that StageShow requires non-default permalinks

= 9.6.6 (16/09/2021) =
* Bug Fix: Can't validate sales after performance expires time 

= 9.6.5 (06/08/2021) =
* Bug Fix: Template name not output by Preview button
* Added WP version to Overview page
* Added MySQL version to Overview page
* Tested with WP 5.8

= 9.6.4 (08/06/2021) =
* Stripe Keys entended to 255 characters long
* Stripe PHP library updated to v7.83.0

= 9.6.3 (27/04/2021) =
* Compatibility updated to WP v5.7.1

= 9.6.2 (28/03/2021) =
* Bug Fix: value param in option tag removed from form input 
* Added "wildcard" option to show name specifier in shortcode  

= 9.6.1 (24/03/2021) =
* Bug Fix: Fields from Custom Forms not saved to DB
* Bug Fix: Checkout custom fields not used if custom form is included 
* Added "lineNo" to fields in shopping cart data passed to checkout template 
* Price Type extended to 20 characters

= 9.6 (07/03/2021) =
* Compatibility updated to WP v5.7

= 9.5.1 (12/01/2021) =
* Bug Fix: HTML may be removed from settings fields that should allow it 
* All testing of global form data now uses IsElementSet() function 

= 9.5 (03/01/2021) =
* Bug Fix: Infinity now shown in Performance-Max Seats field 
* Compatibility updated to WP v5.6

= 9.4.1 (04/10/2020) =
* Bug Fix: Tickets list only has first row (since 9.4)

= 9.4 (03/10/2020) =
* Fixed inconsistent version declarations in v9.3.11 distribution

= 9.3.11 (03/10/2020) =
* Bug Fix: Send EMail button not working
* Bux Fix: Total Due not calculated for Revervation EMails 
* Bux Fix: Total Due changed to [soldValue] in Revervation template

= 9.3.10 (13/09/2020) =
* Added Sanitization to PayPal IPN Callback 

= 9.3.9 (12/09/2020) =
* Bug Fix: Sanitization of Textarea elements removes Line Feeds 
* Bug Fix: Payment Gateway callbacks fail if plugin folder is non-standard 
* Bug Fix: Reservation does not have a sale reference 
* Bug Fix: Total Due not calculated for validation of Reservations 
* Bug Fix: TxnId not verified if it contains '_'  
* Bug Fix: Checkout button shown when Gateway not configured and Reservations enabled 
* Bug Fix: No output for Seating Template Export 
* Added "Note To Seller" to Purchaser Fields in Sale Edit 
* Changed Serialized POST vars to JSON encoding
* All HTTP input now use context aware sanitization functions 
* Default EMail Templates renamed 

= 9.3.8 (30/08/2020) =
* Bug Fix: Error on 1st time plugin activation 
* Bug Fix: Samples give error with salePostage 
* Bug Fix: Textbox input sanitization removes CR 
* Added context aware sanitization functions to MJSLibUtilsClass 
* Bug Fix: Multi-line discount codes not saved 
* Callbacks for View/Send EMails changed to (currPageURL}&stageshow_callback=*******
* Callbacks for Payment Gateways changed to {PluginURL}/stageshow_callback/GatewayID 
* Deprecated calls to PHPMailer removed (WP5.5+)
 
= 9.3.7 (22/08/2020) =
* Bug Fix: Ticket Price column not formatted on sales page 
* Calls to stripslashes() changed to  stripslashes_deep()
* Added sanitization to $COOKIE elements 
* Enable Printing option moved (from dev options) to settings page 
* Debugging options removed from release version
* Trolley Quantity input element changed to "Number" type 
* TABLEPARAM_ROWS & TABLEPARAM_COLS optional for admin textarea 

= 9.3.6 (16/08/2020) =
* Compatibility updated to WP v5.5

= 9.3.5 (14/08/2020) =
* Trolley and Validate jQuery callbacks now processed by AJAX handler 
* Bug Fix: labels array (previously used by jQuery response translation) not removed 

= 9.3.4 (10/08/2020) =
* Further updates for Plugin Guideline Compliance
* Bug Fix: Duplicate Payment Gateway callbacks can produce duplicate payment records 
* Added Stripe Webhook event list to settings page 
* Added saleMethod to Mollie Payments 
* Payment Gateway callbacks now run within WP (uses wp_loaded action handler) 
* Added purchaser name, paypal fees and email to Mollie sales (where available) 
* "Sale Complete" message changed to "Checkout Complete - Please check your EMail for confirmation" 
* Screenshots moved to assets folder 

= 9.3.3 (28/07/2020) =
* Support for PayFast Payment Gateway removed
* Support for Authorize.net Payment Gateway removed

= 9.3.2 (25/07/2020) =
* Updated for Plugin Guideline Compliance

= 9.3.1 (20/07/2020) =
* Readme updated - Compatible with WP 5.4.2

= 9.3 (10/07/2020) =
* Added explicit sanitisation to all form inputs 
* Bug Fix: continue in switch statement gives error with PHP 7.4 
* Bug Fix: lines after closing ?> give activation error 
* Bug Fix: CSS problem with Next/Prev Page Buttons on admin pages

= 9.2.2 (02/02/2020) =
* Bug Fix: Ticket Meta values not included in admin pages or export
* Plugin Unification completed

= 9.2.1 (01/02/2020) =
* Bug Fix: stageshow_pfm.php included in distribution

= 9.2 (02/01/2020) =
* Stripe update to API v7.14.2
* Added help for Stripe and Mollie setup

= 9.1 (27/12/2019) =
* Mollie update to API v2 

= 9.0 (15/12/2019) =
* Bug Fix: Error on first activation
* Change cursor and disable controls on Checkout going to checkout details form  
* Change cursor and disable controls on final Checkout 

= 8.2.1 (14/04/2019)
* Bug Fix: Checkout forms never use PayPal Express
* Bug Fix: Formatting error with PayPal Express checkout button 
* Bug Fix: Checkout with expired NOnce hangs
* Bug Fix: Export of Database does not deal with  NUL Date & Time values 
* Bug Fix: stageshow_admin_only style always set to display:none 
* Bug Fix: Checkouts do not timeout (saleCheckoutTime not set in DB) 
* Bug Fix: Sales not purged when getting seating 
* Bug Fix: Fatal Error in AJAX call makes screen hang with busy image 
* Changed MIME type of SQL export to application/x-sql 
* Added space between First and Last names in salePPName for Reservation 
* Sales Admin page now shows "Total Due" column 

= 8.1.1 (07/03/2019)
* Bug Fix: saleFee field in Sales table should be deleted 
* Bug Fix: Get day of week not initialised to correct vaalue 
* Block export of email addresses improved 
* Bug Fix: Admin pages do not remember search text 
* Edit sale now adds any additional payment as a new record in Payments table 
* Added export of payments records 
* Compatible with WP5.1

= 8.0.7 (16/02/2019) =
* Bug Fix: PHP >= 7.2 count on non Countable object error 
* Bug Fix: Quick Sale does not save dale meta data 
* Bug Fix: Selected Seat and Ticket Type mismatch not reported 
* Bug Fix: splitting of sales into payments table could produce zero saleDateTime entries 
* Payments now stored in separate DB Table 
* Added Payments list to Sales admin page

= 8.0.6 (07/01/2019) =
* Bug Fix: Hangs adding items to trolley (since 8.0.5)

= 8.0.6 (07/01/2019) =
* Bug Fix: Hangs adding items to trolley (since 8.0.5)

= 8.0.5 (04/01/2019) =
* Bug Fix: Deleting Plugin does not remove all database tables
* Bug Fix: Options in WP Options table not remove on update

= 8.0.4 (11/09/2018) =
* Bug Fix: Closure of Stripe Checkout window not detected 

= 8.0.3 (02/09/2018) =
* Bug Fix: Needs StageShow_Admin and StageShow_Sales Permissions to Manually Add Sale 

= 8.0.2 (26/08/2018) =
* Bug Fix: Fatal Error when saving edited Sale

= 8.0.1 (25/08/2018) =
* Bug Fix: Summary EMails sent to admin if "BCC to admin" enabled 
* Bug Fix: Stripe Gateway send two "Sales Summary" EMails 

= 8.0 (18/08/2018) =
* All features of StageShowGold merged into StageShow

= 7.1 (18/08/2018) =
* Added [sshow-login] shortcode

= 7.0.2 (15/05/2018) =
* Bug Fix: Cannot add sales with Sales admin page (since 7.0)

= 7.0.1 (08/05/2018) =
* Bug Fix: Bcc of sales emails not working (since 7.0)
* Added DB download to client DB record shortcode
* Added class parameter to Edit template button 
* Implemented nested 'if' options in templates

= 7.0 (15/04/2018) =
* Bug Fix: EMail failure if attached file is empty
* Bug Fix: Overview page includes pending sales in total value
* Bug Fix: Export of plugin settings not escaping CR and LF
* Added shortcode for client DB record queries
* Added warning for SysAdmins when SMTP max line length exceeded

= 6.6.8 (09/04/2018) =
* Bug Fix: Export of plugin settings not escaping CR and LF 
* Bug Fix: Overview sales total includes pending sales
* Bug Fix: EMails with '_' rejected when adding reservation
* Added COALESCE to MySQL SUM to convert NULL total values to zero
* SMTP line length exceeded warnings for Sysadmins	
* Added stageshow_sales_updated action hook
* Added Custom Admin Stylesheet

= 6.6.7 (18/01/2018) =
* Bug Fix: Distribution error (missing files) on Wordpress.org (StageShow)

= 6.6.6 (16/01/2018) =
* Bug Fix: Validator does not respond for manually entered sales

= 6.6.5 (31/12/2017) =
* Bug Fix: Multiple StageShow Box_Office failures with Yoast SEO plugin

= 6.6.4 (22/12/2017) =
* Bug Fix: Box Office Calendar output has incorrect month names
* Bug Fix: Validation only works with jQuery enabled
* Added STAGESHOW_BACKEND_DRILLDOWN

= 6.6.3 (17/12/2017) =
* Bug Fix: Possible "SessionVarsAvailable NOT Available" error on Tools admin page (since 6.2) 
* Validation updated to ignore elements in Reservation Checkout Form hidden by CSS
* Added class derived from priceType to Box Office Rows
* Added MJSLIB_RESERVATION_******_MINLEN constants

= 6.6.2 (28/11/2017) =
* Bug Fix: Donations not added to sale record for Reservations (since v3.9.4) (StageShow+)
* Bug Fix: Summary EMail not generated for "simlulator" gateway
* Bug Fix: Summary EMail not generated for "simlulator" gateway
* Added additional calendar shortcode attribute (separate)  - Month completely separated (StageShow+)
* Added Donations to email templates
* Added "View Samples" button to Shortcodes section on Overview page
* Added "View Samples" button to Custom PHP settings row

= 6.6.1 (22/11/2017) =
* Bug Fix: EMail Date & Time Fields ignore STAGESHOW_DATETIME_BOXOFFICE_FORMAT
* Bug Fix: Error in TDT Export (since 6.5.3) 

= 6.6 (16/11/2017) =
* Bug Fix: Fatal Error for Stripe Gateway on StageShow+
* Bug Fix: Refresh of Stripe Payment Confirmation changes status to "Pending Charge" (StageShow+)
* Stripe API Updated (StageShow+)

= 6.5.5 (12/11/2017) =
* Bug Fix: Stripe Error in v6.5.3 fixed
* Bug Fix: Manual Sale: Box-Office Drilldown shown with Purchaser Details form (StageShowGold)
* Updated for WP 4.9

= 6.5.4 (11/11/2017) =
* Bug Fix: Box Office quantities always 1 if jQuery disabled
* Bug Fix: Fatal Error - Stripe Gateway Checkout Since 5.3.1 - Rolled Back (StageShowGold)

= 6.5.3 (10/11/2017) =
* Bug Fix: Box office with "Add button per price" always adds qty=1
* Bug Fix: Admin Add Sale Drilldown only includes "Public" prices 
* TDT Export updated to release memory during download

= 6.5.2 (08/11/2017) =
* Bug Fix: Box Office with "Add button per performance" adds a rogue ticket
* Bug Fix: Sale Summary export has excessive memory usage

= 6.5.1 (07/11/2017) =
* Bug Fix: Stripe gateway does not redirect to Checkout Complete URL (StageShowGold)
* Bug Fix: Stripe gateway does not redirect to Checkout Cancelled URL (StageShowGold)
* Bug Fix: Stripe gateway does not save "Note to Seller" (StageShowGold)
* Bug Fix: Custom field included in Stripe Sale Confirmation screen (StageShowGold)

= 6.5 (05/11/2017) =
* Added "Drilldown" mode for Manually added sales (StageShowGold)
* Stripe Gateway generates error emails to admin (StageShowGold)
* Removed _wpnonce from "Add Sale" button

= 6.4.5 (03/11/2017) =
* Bug Fix: Online Box Office quantities always set to 1 (sinde 6.4.4)
* Bug Fix: Postage & Donation not included in Stripe popup price (StageShowGold)

= 6.4.4 (31/10/2017) =
* Bug Fix: Stripe gateway add postage cost twice (StageShowGold)
* Bug Fix: Fees are deducted in sale record for manual entry Completed sales (StageShowPlus)
* Bug Fix: Box Office has limit of number of input elements (set by PHP) (StageShowPlus)
* Bug Fix: Rounding error on %age part of booking fee (StageShowPlus)
* Bug Fix: Undefined variable (priceCheckoutMode) saving Prices & Price Plans  (StageShowPlus) 

= 6.4.3 (10/10/2017) =
* STAGESHOW_MAXTICKETCOUNT determines no of digits in 'Max Ticket Qty' (StageShowGold)
* Export file extensions can be set by STAGESHOW_EXPORT_******_FILEEXTN defines
* Added CustomSeatingPlanGenerator.xls to doc folder (StageShowGold)

= 6.4.2 (30/09/2017) =
* Added Error Notification for Cached Box-Office Page 

= 6.4.1 (27/09/2017) =
* Added DONOTCACHEPAGE constant to prevent WP Super Cache caching shopping trolley 

= 6.4 (21/09/2017) =
* Bug Fix: Undefined $pluginObj when Transaction Cancelled 
* Added checkout buttons mode to Prices and PricePlans admin pages 
* Added "Check Modes Enable" checkbox to reservations options 
* Added Mollie payment gateway (StageShow+)
* Implemented custom discounts WP filter (stageshow_filter_discount) (StageShow+)    

= 6.3.1 (08/09/2017) =
* Bug Fix: Single Quotes in Manual Sale Purchaser Fields precced by backslash
* Bug Fix: Incorrect file Permissions creating new "logs" folder
* Bug Fix: Sale Discount Code not passed on by User Custom Fields page
* Bug Fix: Simulator Gateway adds transaction fee twice

= 6.3 (20/08/2017) =
* Bug Fix: No inventory check on manually added sales
* Bug Fix: No DB Lock on Add/Edit sales commit from admin page
* Booking fee now has fixed and %age of ticket price parts

= 6.2.6 (25/07/2017) =
* Bug Fix: Reservation User Form (Logged In) mode inoperative in Box-Office (StageShow+) 

= 6.2.5 (16/07/2017) =
* Changed to reject seat location definitions in unallocated seating zones (StageShowGold)
* Calendar shows Sold Out sales in red without link (StageShowGold)
* Removed Sale Page Edit Sale, View and Send EMail buttons when not valid 
* Bug Fix: Corrupted Overview output when shows have no performances
* Bug Fix: Fatal Error on Checkout with Simulator gateway

= 6.2.4 (01/07/2017) =
* Bug Fix: Allocated Seats option updates ignored by Preview page (StageShowGold)
* Bug Fix: Legend and Block zone defs ignored for Unallocated Seats zones (StageShowGold)

= 6.2.3 (20/06/2017) =
* Bug Fix: Tickets Meta DB Tables not available for jQuery Calls
* Bug Fix: Bcc EMail sent to WP Admin EMail (rather that SS Admin EMail)

= 6.2.2 (13/06/2017) =
* Bug Fix: Box Office reports "Session Variables not available" (StageShow)

= 6.2.1 (12/06/2017) =
* Bug Fix: Trolley Add & Remove buttons generate StageShowLibGenericDBaseClass undefined (StageShow) 

= 6.2 (09/06/2017) =
* Bug Fix: Row following "Show Available Seats" link has incorrect class 
* Bug Fix: Shopping Trolley performance divider row output before first row 
* Bug Fix: Admin pages do not save custom UI element
* Bug Fix: Select... not translated when items added or removed from trolley 
* Bug Fix: Exported fields order not controlled by exports file
* Bug Fix: Fields with NULL values may be ignored in Tools->Export 
* Bug Fix: Bcc EMail sent to WP Admin EMail (rather that SS Admin EMail)
* Bug Fix: Calendar displayed prices not defined beyond extent of price data 
* Bug Fix: Error processing email attachments when viewing emails 
* Bug Fix: GetSaleMetaFields gives ONLY FULL GROUP error with PHP7
* Bug Fix: Export StageShow DB exports all tables 
* Bug Fix: salePaidDateTime updated when editing completed sale 
* Bug Fix: salePaidDateTime does not use local time 
* Bug Fix: "Lockout" entries included in Sales Summary (StageShowGold) 
* Bug Fix: Seating Decodes file not shown for locked Seating Plans with PHP7 (StageShowGold) 
* Bug Fix: With PHP7 Saving seating plan may give invalid error message (StageShowGold)
* Bug Fix: Edit lockout button shown for perfs without seating plan (StageShowGold)
* Added support for multiple email addresses (separated by a ';') 
* Added check for session variables available in jQuery calls
* Added stageshow_filter_trolley_extrarows 
* Added implementation of [ifnot] in EMail Templates
* Filtered Admin page list now skips to first filter when defaut filter is empty
* Updated for Wordpress 4.8

= 6.1.10 (19/04/2017) =
* Bug Fix: SS and SS+ give "Unknown column 'showNotInListing'" error

= 6.1.9 (14/04/2017) =
* Bug Fix: Drilldown top level ignores "Only Shown when selected by shortcode id"
* Bug Fix: Exported StageShow settings can be corrupted

= 6.1.8 (05/04/2017) =
* Bug Fix: Add New Performance (with Performance Note) SQL Error (StageShow+) 
* Bug Fix: Incorrect selector name and id in Meals Template (StageShowGold)
* Bug Fix: Lockout Status (PAYMENT_API_SALESTATUS_LOCKOUT) undefined in jQuery calls 
* Bug Fix: Update Prices not translated in jQuery calls (StageShow+) 
* Added plugin version to MJSLIB_CONFIG_STAMP - Forces update of wp-config-db.php 
* Export of StageShow options in wp_options table removed 

= 6.1.7 (07/03/2017) =
* Bug Fix: Performance Date/Time using MYSQL formatting
* Allow locked-out seats in sales by logged in admin users (StageShowGold)

= 6.1.6 (04/03/2017) =
* Bug Fix: Seat decodes not decoded in Shopping Trolley (since 6.1.4) (StageShowGold)

= 6.1.5 (03/03/2017) =
* Bug Fix: showGatewayIndex undefined error when adding sales (since 6.1.4)
* Bug Fix: Reservation Details Form status message visible on Load (StageShow+)
* Bug Fix: Click on Reserved/Sold seats in Box Office not deactivated (StageShowGold)
* Moved Sales EMail & Template setting to gateway settings 
* Disables controls and sets cursor to "Busy" on Reserve "Checkout" (StageShow+)
* Added form validation to Reservation Details Form (StageShow+)
* Added STAGESHOW_IDENTIFY_LOCKED option to show locked seats (StageShowGold)
* Show Sale Meta details on click seat in View Sales screen (StageShowGold)
* Calendar month headers passed through stageshow_filter_monthheader filter (StageShowGold)

= 6.1.4 (14/02/2017) =
* Bug Fix: Date/Time of new performances can be uneditable
* Added check for PayPal payment account ID

= 6.1.3 (12/02/2017) =
* Added support for multiple PayPal accounts (Beta) (StageShowGold)

= 6.1.2 (11/02/2017) =
* Bug Fix: Export Error when no filter selected (StageShowGold)
* Bug Fix: Logo missing in Sale EMails if Summary EMail sent (StageShow+)
* HTML EMail Templates Rationised (StageShow+)
* Added Sales option to Exports on Tools admin page  (StageShow+)

= 6.1.1 (06/02/2017) =
* Bug Fix: Number in Reservation Purchaser EMail fails validation 

= 6.1 (31/01/2017) =
* Bug Fix: Sale Meta entries not added to sale emails from admin page
* Added "Reserved" filter to sales admin page (StageShow+)
* Added Validation to Reservation User Details Form (StageShow+)
* Modified to Maintain Sale Meta data during sale edit (StageShowGold)
* Added customisable filter to Export on Tools page (StageShowGold)

= 6.0.1 (09/01/2017) =
* Bug Fix: Unexpected AJAX return can hang Seating Selection (StageShowGold)
* Bug Fix: StageShow Database Backup has invalid table names (since 5.14.1)
* Bug Fix: EMail Sale test always uses "Sale Completed" template (StageShow+) 
* Bug Fix: Seating Plan preview Seat selection error (since 6.0) (StageShowGold) 
* Bug Fix: Locked seats count shows 1 when all locks removed (StageShowGold)
* Bug Fix: Unallocated zones included in seat lockout selector (StageShowGold)
* Bug Fix: Unallocated zones included in seating plan template (StageShowGold)
* Bug Fix: Close button inoperative in view sales window 
* Added "Golf" Tee Time templates (StageShowGold)
* Added 'stageshow_filter_trolley' filter 
* Added 'stageshow_filter_boxoffice' filter 
* Sale Meta data displayed on Sales listing mouseover (StageShowGold)

= 6.0 (31/12/2016) =
* Bug Fix: Add button missing button-primary class
* Bug Fix: Unspecified Logo File breaks EMail template 
* Bug Fix: Reserve button has two class attribute entries (StageShow+)
* Bug Fix: Legends missing from Seating Plan (StageShowGold)
* Bug Fix: Undefined field 'customFields' on reservations checkout (StageShowGold)
* Bug Fix: Send tickets by post option cleared by seats selection (StageShowGold)
* Adds HTML to MJSLIB_TROLLEYHTML_********* defines if it is missing 
* Added "View Sales" by seating plan option to Performances 
* Added Performances without any prices to Performances Admin page "Current" listing
* Added seats "Lockout" function (StageShowGold)
* Added "User Form (Logged in)" option to reservations

= 5.15 (10/12/2016) =
* Bug Fix: Discount prices not showing in Box Office (StageShow+)
* Bug Fix: Inconsistent Seat Identification to Payment Gateway (StageShowGold)
* Bug Fix: PayPal Express Fatal Error when PayPal response missing SHIPTOSTREET2 (StageShowGold)
* Bug Fix: Shopping Trolley columns incorrect when date column hidden 
* Bug Fix: Action messages in Installed Plugins admin page not visible 
* Bug Fix: EMail templates editor saves to wrong folder (StageShow+)
* Performance Improvement - SQL optimised for Seating Plan Editor (StageShowGold)
* Added custom checkout forms with Validation (StageShowGold)
* Shopping Trolley Code Rationised
* Added Text Format Summary EMail 
* Added Wordpress filters for Seating Plan Cells Title and Text (StageShowGold)
* Added reformating of EMail Templates with unusual line ends (ie CR only)
* Added EMail notification to admin when PayPal Express sale Suspended (StageShowGold)  

= 5.14.1 (02/12/2016) =
* Bug Fix: Undefined field 'customFields' on reservations checkout (StageShowGold) 

= 5.14 (16/11/2016) =
* Bug Fix: Custom Buttons only shown on initial Box-Office output
* Bug Fix: Undefined variable error on attempts to add duplicate Show Name
* Bug Fix: PayPal Express button appers with Checkout Details button (StageShowGold)
* Bug Fix: Images in HTML EMails can be defined multiple times (StageShow+)  
* Bug Fix: Edit sale generates invalid "Insufficient Seats Available" error 
* Performance Improvements for Shows and Performances admin pages Bulk Actions
* Added test that all seats have been selected before Checkout (StageShowGold)
* Added Check for EMail template lines starting with '.' 
* Added Seating Decodes File for Alpha-Rows without I (StageShowGold)
* Added DB Ids option to Prices Admin page
* Added [saleEMailURL] to entries for EMail templates
* Added [ticketsCount] to entries for EMail templates
* Added Custom PHP edit to settings 
* SQL updated for compatibility with MySQL 5.7 
* Added StageShowAction_activate action 

= 5.13 (17/10/2016) =
* Bug Fix: EMail "From" address corrupted (since v5.11)

= 5.12 (16/10/2016) =
* Bug Fix: StageShow breaks "Add Plugin" page (since v5.11)

= 5.11 (15/10/2016) =
* Bug Fix: Multiple SQL Errors with MySQL v5.7 - ONLY_FULL_GROUP_BY disabled
* Bug Fix: Ticket Printing dev code included in Tools admin page (StageShowGold)
* Bug Fix: Invalid Command - stageshowgold_dbase_api.php line 2064

= 5.10 (05/10/2016) =
* Bug Fix: Only sales near top of sales list can be deleted
* Bug Fix: HTML EMails break if text content greater than 1000 chars long
* Added stageshowCustom_AlertInvalidSeat() function to javascript
* Sale Edit: Products list and Trolley listings order rationised

= 5.9.11 (29/09/2016) =
* Added optional stageshowCustom_AlertInvalidSeat() for Minimum Empty Seats Errors (StageShowGold)
* Possible undefined $_POST['PrinterDefPath'] element (StageShowGold)

= 5.9.10 (27/09/2016) =
* Added filter (stageshow_filter_nosalesmsg) to "Sales Not Available Currently" message

= 5.9.9 (23/09/2016) =
* Bug Fix: Possible Server Error on Checkout (since 5.9.8)

= 5.9.8 (20/09/2016) =
* Added option to specify URL of PayPal checkout header image
* Default EMail and Checkout header images changed to PNG files
* French Translation Updated
* Bug Fix: Error parsing seating plans with empty zone spec sections (StageShowGold)

= 5.9.7 (14/09/2016) =
* Bug Fix: Text in HTML emails can break MIME format (StageShow+)
* Bug Fix: Tickets Export DB Error (since 5.9.6)

= 5.9.6 (10/09/2016) =
* Added "Venue Name" to seating plan settings (StageShowGold)
* DB Access Optimised by removing Seating Template output (StageShowGold)
* Added user defined Drilldown Box-Office selector (MJSLIB_DATETIME_DRILLDOWN_FORMAT) (StageShowGold)
* Readme changed to show compatible with WP 4.6.1 

= 5.9.5 (21/08/2016) =
* Bug Fix: Formatting errors when editing HTML EMail templates (StageShow+)
* EMail templates updated to improve compatibilty with TinyMCE editor

= 5.9.4 (08/08/2016) =
* Added Donations column to overview page (StageShow+)
* Added Donations to Tickets Export (StageShow+)
* Defining MJSLIB_SHOWDBIDS adds database record ID to admin pages

= 5.9.3 (26/07/2016) =
* Bug Fix: Error saving Seating Plan with short Seat Plan Ref (StageShowGold)
* Bug Fix: Invalid HTML in Seating Plan admin page (StageShowGold)
* Bug Fix: EMail templates not listed in settings (since 5.8.1) 
* Bug Fix: Custom CSS/JS files not listed in settings (since 5.8.1) 
* Bug Fix: Seating Plans missing Seat Decodes (since 5.8.1) (StageShowGold) 

= 5.9.2 (25/06/2016) =
* Bug Fix: Errors copying default templates on activation not reported
* Bug Fix: Deprecated get_currentuserinfo() changed to wp_get_current_user() - Demo only 
* Added Cancel button to template editor 
* Errors copying template files during activation/installation not reported 

= 5.9.1 (31/05/2016) =
* Bug Fix: Add buttons not enabled/disabled when Box-Office page loaded - since 5.9
* Bug Fix: Seating Plan not displayed - since 5.9 (StageShowGold)

= 5.9 (30/05/2016) =
* Bug Fix: Style Sheets will not load on sites using both HTTP and HTTPS
* Bug Fix: Note to seller width not defined by CSS
* Bug Fix: Trolley Translation Javascript can be output before HTML header
* Added "Stripe" payment gateway (StageShowPlus)
* Seating Plan editor now always allows "Add Zone" (StageShowGold)
* Seating Plan Zones can be modified if no corresponding prices exist (StageShowGold)
* Seating Decodes file can be changed if there are no sales using it (StageShowGold)

= 5.8.3 (20/05/2016) =
* Bug Fix: PHP 7 reports MYSQL_BOTH undefined
* Added '+' option to Relocation Position Specifiers (StageShowGold)

= 5.8.2 (17/05/2016) =
* Bug Fix: Reserved or Allocated Seats on Seat Selector page can still be selected (StageShowGold)
* Security Update: Block closing of PHP comments in email templates 
* Added dynamic "alt" tags to seat images
* Added "alt" tags to image buttons
* Javascript for Seating Plans moved to stageshowgold.js 

= 5.8.1 (21/04/2016) =
* Box Office Add buttons now disabled when no relevant quantity set
* Bug Fix: Box Office "Add" buttons may add tickets for any performance

= 5.8 (11/04/2016) =
* Bug Fix: Exports and Payment Gateway may fail on MU sites - Added ABSPATH to load_wpconfig.php
* Bug Fix: Entries for expired performances can still be added to trolley or included in checkout 
* Login Cookie WP Action functions made tolerant of missing arguments when called by other plugins 
* Added "Date & Time Column", "Quantity Column" and "Visibility" options to Shows admin page (StageShowGold)
* Drilldown boxoffice goes straight to performances when their is only one show (StageShowGold)
* Added "View" button for imported seating templates (StageShowGold)

= 5.7.8 (31/03/2016) =
* Bug Fix: Direct entry of page no on admin pages fails after using next/prev page buttons
* Bug Fix: Possible race condition during plugin update
* Added option for Javascript stageshowCustom_ClickSeat() function (StageShowGold)

= 5.7.7 (24/03/2016) =
* Bug Fix: fopen Error - File Root Path Duplicated (Linux/UNIX servers)
* div.notice styles removed from admin.css

= 5.7.6 (24/03/2016) =
* Bug Fix: Unexpected output on first activation (benign)

= 5.7.5 (23/03/2016) =
* Bug Fix: Discount code can disappear if there are no entries in shopping trolley (StageShowGold)
* Updated for sites that redefine WP_PLUGIN_*** and WP_CONTENT_*** constants

= 5.7.4 (09/03/2016) =
* Bug Fix: Header missing from Box-Office listing
* Bug Fix: Seats selection does not work in Demo mode
* Bug Fix: Superfluous "Sales Not Available Currently" message on Box-Office page
* Bug Fix: Sales deleted when first show/performance within it is deleted
* Bug Fix: Inconsistent Calendar View date cell colours (StageShowGold)

= 5.7.3 (05/03/2016) =
* Bug Fix: Overview page shows all entries as one show
* Formatting Error in Changelog

= 5.7.2 (03/03/2016) =
* Bug Fix: Empty StageShow database not reported by Overview admin page
* Bug Fix: Reservation Page does not scroll to Contact Details Form (StageShow+)
* Added option for Javascript OnLoad handler for Contact Details Form (StageShow+)
* Improved formatting of Admin Pages (for Search box and empty admin page)

= 5.7.1 (01/03/2016) =
* Bug Fix: "Insufficient Seats Available" error adding sets to Performances with seat quantity (since 5.6.16)
* Bug Fix: No validation of User Form when adding a Reservation

= 5.7 (28/02/2016) =
* Bug Fix: Settings Page File Editor fails on CSS and JS files
* Bug Fix: Summary EMail Template not initialised on first install (StageShow+)
* Bug Fix: Pending sales included in Tickets Export
* Added Payment Gateway Simulator (for testing)
* Added Live Payment Gateway to Demo Plugins
* Settings moved from wp_options to wp_sshow_settings

= 5.6.17 (16/02/2016) =
* Bug Fix: Add tickets with jQuery fails when max seats exceeded
* Added translation for over-subscribed performances in checkout message

= 5.6.16 (15/02/2016) =
* Bug Fix: Availability not checked when adding items to the Trolley

= 5.6.15 (10/02/2016) =
* Bug Fix: EMail Template Editor adds <br> tags to non-HTML templates
* Bug Fix: Borders missing on settings tab selectors
* Added shortcode months=active option for calendar view (StageShowGold)
* Added shortcode cols=***  option for calendar view (StageShowGold)

= 5.6.14 (28/01/2016) =
* Bug Fix: Top performance zone hides Box-Office "Add" button when Sold out
* Bug Fix: Price Plans with duplicate Ticket Types in different Zones fail
* Added button to edit CSS and JS files from settings admin page 

= 5.6.13 (24/01/2016) =
* Bug Fix: Cannot checkout error with Unallocated Zones

= 5.6.12 (15/01/2016) =
* Bug Fix: Box Office fails after Upgrade from v5.0.5 and earlier
* Bug Fix: Empty "Minimum Seat Spacing" setting crashes Javascript (StageShowGold)
* Bug Fix: Generates "Error parsing file HTML" with default reservations email (StageShow+)
* Added STAGESHOWLIB_DISABLE_JQUERY_BOXOFFICE define to disable jQuery
* Sale validation improved for zoned seats (StageShowGold)

= 5.6.11 (09/01/2016) =
* Bug Fix: Validate Sale aborts with Undefined variable Error (since v5.6.6)
* Bug Fix: Undefined variable when shopping trolley updated to empty (since v5.6.10)
* Added sale status filter to Tools Send EMail sales list

= 5.6.10 (09/01/2016) =
* Bug Fix: "Sales Not Available Currently" message on Box-Office missing (since v5.5)
* Bug Fix: Box-Office shows expired shows when admin logged in (since v5.6.8)
* Bug Fix: Seating Plan optimisation fails with adjoining blocks of spaces (StageShowGold)
* Bug Fix: Empty rows in seating plan do not have any <td> elements (StageShowGold)

= 5.6.9 (05/01/2016) =
* Bug Fix: Scroll to anchor inconsistent with "DrillDown" Box Office
* Bug Fix: Enter Discount Code Box Office positioning errors (StageShowGold)
* Bug Fix: "Enter Discount Code" not always translated (StageShowGold)
* Selecting Editable Quantity Element on Box Office selects text
* Added a discounted sale to samples (StageShowGold)
* Added template editors to Settings Admin Page

= 5.6.8 (03/01/2016) =
* Bug Fix: Discount Code entry shown when Box-Office page not shown
* Bug Fix: Paymant Gateway API logging not working
* Bug Fix: Next/last button enabled when requesting paged admin page greater than maximum
* Bug Fix: Select Performances on Tools page fails with Safari
* Bug Fix: StageShow Export on Tools page fails with Safari
* Bug Fix: Discounted prices not used on Overview page
* Bug Fix: Error generating Sale Rejected and Sale Timeout EMails
* Added Ticket Paid Price to Sales Ticket details
* Performance expiry time extended when Logged in (MJSLIB_ADMIN_EXPIRETIMEOFFSET)
* Added Sale Extras, Sale Fees and Net Sales to Overview Page

= 5.6.7 (29/12/2015) =
* Bug Fix: Address details not shown on Sales Admin Page (since 5.6.6)

= 5.6.6 (27/12/2015) =
* Bug Fix: Timeout and Rejected Sale EMail templates fail
* Bug Fix: Sales Admin page filter titles not showing
* Added "Checkout" to Sale Admin page filters
* Sales Admin Page DB Access Optimised
* Changed sale admin page 'Active' filter to 'Current'

= 5.6.5 (22/12/2015) =
* Bug Fix: Adding Performance with default expiry time produces invalid expiry time
* Bug Fix: Available seats can be missing on bottom line of Box-Office 
* Bug Fix: Maximum seats in unallocated seating zones not checked on Checkout (StageShowGold)
* Bug Fix: Undefined fields error downloading Offline Validator (Benign) (StageShow)
* Added "Add Seat" and "Edit Seat" buttons to Edit Sale Trolley
* Sale Details held on server for extended period after Checkout Timeout
* Sales now accepted when Payment confirmation after timeout if items still valid
* Sales with Payment confirmation after timeout and invalid items rejected
* Created separate Sale Rejected and Sale Timeout EMail templates
* Removed "system" email templates from Settings Menu pages

= 5.6.4 (11/12/2015) =
* Bug Fix: Space Terminating Zone Spec causes erroneous can't change Seating Plan error (StageShowGold)
* Bug Fix: Multi-seat tickets always pass availability cross-check on checkout (StageShowPlus)
* Added list filters to Sales Admin Page
* Added "Seats Qty" column to Sales Summary Download (StageShow+)

= 5.6.3 (01/12/2015) =
* Bug Fix: Performance Expires time not changed when adding extra performances (StageShow+)
* Bug Fix: Performance note shown for all performances shown in drill down mode (StageShowGold)
* Selected Price Plan made persistent in Performances editor (StageShowPlus)
* Aisle seats identified with 'a' in seatling plans (StageShowGold)
* Box-Office quantity mode defined/implemented
* Added Serbian Translation
* Added Columns to Main Theatre Seating Plan

= 5.6.2.1 (21/11/2015) =
* Bug Fix: Manual Sale Seat Selector Seats not visible (StageShowGold)

= 5.6.2 (21/11/2015) =
* Bug Fix: Shortcode id with HTML encoded characters not processsed
* Loading order of CSS files restored (Custom CSS last)
* Sales now listed by date & time (was order in database)
* Added plugin version number to JS and CSS urls (forces re-load on plugin update)
* Offline Validator now filters sales by show and performance
* Add Performance code rewritten to avoid using temporary DB table
* Bug Fix: Bug Fix: Zone Spec 's' specifier not working (StageShowGold)
* Added row and seat numbering to Seating Plan Zone Spec (StageShowGold)
* Updated sample seating plans to include numbering (StageShowGold)
* Updated Seating Decode samples to remove '-' spacer (StageShowGold)
* Added [i**] option to Zone Specs to add image to template (StageShowGold)
* Enter Discount Code moved out of Shopping Trolley output (StageShowGold)
* Added header and footer divs to Seating Templates (StageShowGold)
* Added "Reload" action to Seating Plans admin page (StageShowGold)
* Output "Seat Blocking Disabled" warning for old seating templates (StageShowGold)

= 5.6.1 (06/11/2015) =
* Add Performance code rewritten to avoid using temporary DB table

= 5.6 (05/11/2015) =
* Bug Fix: PHP POST variables limit can break admin page updates
* Bug Fix: EMail [url] entry uses site URL instead of home URL
* Bug Fix: PHP Warning - preg_replace () called with e modifier (PHP 5.5 onwards)
* Bug Fix: Multiple Shortcodes on same page repeats 1st instance (since 5.0.10)
* Bug Fix: Performance Ref checks duplicates against all shows (StageShow+)
* Bug Fix: Discount Ref entries not saving correctly on Discount Codes admin page (StageShowGold)
* Bug Fix: New price entries shown with price=-100 (StageShowGold)
* Bug Fix: Zero discount prices are not removed from database (StageShowGold)
* Added translations to Reservations User Details Form
* Admin pages updated to list shows in decreasing order of last performance date
* Performance admin page now lists performances in decreasing date order (StageShow+)
* Add New Performance copies latest performance (with date incremented by 1 day)

= 5.5 (27/10/2015) =
* Bug Fix: Selected EMail template can be changed by update/activate
* Bug Fix: Added translation for "Show Available Seats" (StageShow+)
* Bug Fix: Added translation for "Continue" (StageShowGold)
* Added shortcode style=drilldown to Box-Office (StageShow+)
* Implemented Shortcode perf=date option (StageShow+)
* Zone refs in Box-Office output inside <span> tag (StageShowGold)

= 5.4 (20/10/2015) =
* Bug Fix: Seat Selector does not work without jQuery call (StageShowGold)
* Bug Fix: Show Available Seats button not shown for all performances (StageShowGold)
* Added Discount codes (StageShowGold)

= 5.3.8 (15/10/2015) =
* Bug Fix: Seats "invisible" (using CSS) in Preview (since 5.3.6) (StageShowGold)
* Inactive Shows excluded from Summary EMails (StageShow+)
* Inactive Performances excluded from Summary EMails (StageShow+)

= 5.3.7 (14/10/2015) =
* Bug Fix: Seat Selector invisible when editing/adding sales from admin page (StageShowGold)
* Show Available Seats button removed from admin page Sales editor (StageShowGold)

= 5.3.6 (13/10/2015) =
* Bug Fix: Unallocated zones not shown on Select Seats page (StageShowGold)
* Bug Fix: Price Plan editor rejects duplicate Ticket Types in different zones (StageShowGold)
* Added STAGESHOW_IDENTIFY_RESERVED option - changes reserved seats styles (StageShowGold)
* Added STAGESHOW_CLASS_BOXOFFICE options (StageShowGold)
* Added Loading Animated GIF to Seating Selector (StageShowGold)
* Seating Selector Speed Improved using jQuery calls (StageShowGold)
* Added class to donation text (StageShowGold)
* Admin Only Tickets now visible in Box-Office when logged in (StageShow+)
* Added stageshow site config file (stageshow-wp-config.php)

= 5.3.5 (02/10/2015) =
* Bug Fix: Unallocated zones can be shown on "Select Seats" page (StageShowGold)
* Bug Fix: Import Template not reporting some errors (StageShowGold)
* Bug Fix: Add ticket can fail when ticket name identical in another zone (StageShowGold)
* Empty Seats in Seating Template are collated (StageShowGold)
* Added "Purchaser Address" setting option
* Added MJSLIB_TROLLEYHTML_*********** defines

= 5.3.4 (18/09/2015) =
* Bug Fix: Fatal Error saving edited sales (since 5.3.2)

= 5.3.3 (15/09/2015) =
* Bug Fix: Incorrect screen display of HTML special characters in Seating Plan Ref (StageShowGold)
* Bug Fix: Error parsing Zone Specifications (StageShowGold)
* Added trap for Seat Decode Definition errors (StageShowGold)
* Added Seating Plan update confirmation (StageShowGold)
* Cursor Changes to "busy" on clicking any Box-Office button

= 5.3.2 (14/09/2015) =
* Bug Fix: Database Error generating Offline Validator
* Bug Fix: Ticket export Database Error 

= 5.3.1 (13/09/2015) =
* Bug Fix: Loading of Seatingplan can be blocked by other plugins using window.onload (StageShowGold)

= 5.3 (08/09/2015) =
* Bug Fix: Trolley Column is "Show" instead of "Date & Time"
* Bug Fix: Cursor changes when hovering over seats on Seats Available page (StageShowGold)
* Bug Fix: Calendar view includes performances with no prices (StageShowGold)
* Added "Show Available Seats" and "Close Window" button images (StageShowGold)
* Added images for View Available Seating page (StageShowGold)
* Removed cursor updates from View Available Seating page (StageShowGold)
* Added/Changed Close Window button on View Available Seating page (StageShowGold)
* Added optional months parameter to Calendar shortcode (StageShowGold)

= 5.2.4 (05/09/2015) =
* Bug Fix: Messages not cleared from Box-Office when Add or Remove clicked
* Bug Fix: Text EMails not formatted by View EMail button
* Added option of Reservations without user registration
* Added "Disabled" to visibility options

= 5.2.3 (31/08/2015) =
* Bug Fix: STAGESHOW_DATETIME_BOXOFFICE_FORMAT option ignored after Box-Office Add/Remove button used

= 5.2.2 (29/08/2015) =
* Bug Fix: Individual Barcodes invalid in some email clients (gmail esp.) (StageShowGold) 

= 5.2.1 (28/08/2015) =
* Added individual ticket barcode to Sale EMails (StageShowGold)
* Bug Fix: Checkout Header Images disappeared from settings (StageShowPlus)
* Bug Fix: Selected Tab on Settings Admin Page not restored after Save Changes

= 5.2 (14/08/2015) =
* Added stageshow-boxoffice-background class to seating template background (StageShowGold)
* PayPal shipping address only used for postal delivery sales

= 5.1.3.1 (30/07/2015) =
* Added "Show Available Seats" button to box-office listings (StageShowGold)

= 5.1.3 (28/07/2015) =
* Bug Fix: Plugin does not have "active" class in "Installed Plugins" page
* Added "Send EMail" button to each sale entry
* Added "Latest News" block to overview page (when available)

= 5.1.1 (12/07/2015) =
* Bug Fix: Anchor in shortcode breaks add to trolley

= 5.1 (02/07/2015) =
* Record sale as "Unverified" if HTTP Error in PayPal IPN Verify 
* Version History for StageShow Plugins 
* Bug Fix: Duplicate write to Gateway Notify Log removed
* Bug Fix: First pass of GetLayoutTemplate () generates undefined variable error
* Bug Fix: Shortcode Anchor option only works with jQuery
* Added smooth scroll with shortcode anchor= option
* Payment Gateway Logo and Header made optional

= 5.0.16 (14/06/2015) =
* Bug Fix: Seats Requested & Seats Selected counts on SelectSeats screen not updating (StageShowGold)

= 5.0.15 (12/06/2015) =
* Bug Fix: anchor option in box-office shortcode not working

= 5.0.14 (10/06/2015) =
* Bug Fix: Show Filter id= shortcode option fails on some servers after Add button clicked

= 5.0.13 (09/06/2015) =
* Bug Fix: Single Quote in id= option of shortcode breaks Box-Office Add & Remove buttons

= 5.0.12 (04/06/2015) =
* Bug Fix: Incorrect Zone Numbers in Seating Templates (since 5.0.11) (StageShowGold)
* Bug Fix: PayPal Express Currency Always GBP
* Seating Plans page reports if using Imported Seating Plan

= 5.0.11 (27/05/2015) =
* Bug Fix: Error processing Apple/Mac text file format in Decodes file (StageShowGold)
* Option to add a space to the right of a seat (designated by an 's') (StageShowGold)
* View Template opens in new window (StageShowGold)
* Validate Sale on Tools page only available if user has "StageShow_Validate" capability 
* Export on Tools page only available if user has "StageShow_ViewSettings" capability 

= 5.0.10 (25/05/2015) =
* Bug Fix: Single Quote in Trolley Translation breaks JQuery
* Updated Help File

= 5.0.9 (21/05/2015) =
* Bug Fix: Remove button HTML class definitions merged
* Bug Fix: Sample Database seating zoneID error (StageShowGold)
* Zone Spec made (virtually) unlimited size (StageShowGold)
* Security Vulnerability Fixed

= 5.0.8 (19/05/2015) =
* Bug Fix: SQL Error Near "OPTION SQL_BIG_SELECTS=1" with MySQL from v5.6 
* Bug Fix: Translations missing in Box-Office and Trolley
* Default Trolley Button actions commented out in Custom Javascript File

= 5.0.7 (17/05/2015) =
* Added support for historical MySQL library to JQuery calls

= 5.0.6 (15/05/2015) =
* Added Add Ticket Quantities text box/drop-down selector option
* Added JS Values checks to all numeric settings
* Added JS Value check to Quantity Text boxes in Box-Office
* Added STAGESHOW_BOXOFFICE_SORTFIELD to define sorting of Tickets in Box Office
* Bug Fix: Minimum Empty Seats functionality inconsistent (StageShowGold)

= 5.0.5 (10/05/2015) =
* Bug Fix: Admin Pages cannot select page number from keyboard
* Bug Fix: Removing Seats on Select Seats page not reflected in Trolley (StageShowGold)
* Injected Javascript output on single line (improved resilience)

= 5.0.4 (06/05/2015) =
* Added delay to Validation screen before Sale Reference gets focus (STAGESHOW_VALIDATERESULT_TIMEOUT)
* Implemented optional QR Code in Sale EMails (StageShowGold)
* Terminating space on the Sale Reference now triggers validation page
* Javascrip querySelectorAll () call replaced by jQuery call (for IE6/7 compatability)
* Added error report of DB Errors

= 5.0.3 (30/04/2015) =
* Bug Fix: Auto update of Database on Version Updates fails
* Bug Fix: Javascript Error with historical version of IE
* Multiple Windows onLoad events implemented
* Added sale payment method to sale record (defined by STAGESHOWLIB_PAYMENT_METHODS)

= 5.0.2 (22/04/2015) =
* Bug Fix: Seat Numbers not included in Sale EMails (StageShowGold)
* Bug Fix: Seat Numbers not included in Sale exports (StageShowGold)
* Tested with WP 4.2

= 5.0.1 (19/04/2015) =
* Bug Fix: Trolley Button Custom Images not appearing after Add/Remove Actions
* Bug Fix: Multiple shortcodes on a page generate multiple Trolleys
* Shortcode Instance ID passed to Trolley Button Click Handlers
* Fixed Syntax Error in 5.0 changelog!

= 5.0 (17/04/2015) =
* "Sale Transaction ID" changed to "License Reference ID"
* "Sale Txn EMail Address" changed to "License Email Address"
* Ticket "Transaction ID" changed to "Sale Reference"
* StageShowHelp.pdf updated

= 4.6.0.5 (11/04/2015) =
* Bug Fix: Sale Editor adds new trolley line for unallocated Seats in a Seating Plan
* Bug Fix: Gateway simulator total sale value omits salePostage etc.
* Bug Fix: Donations not included when saving edited sale
* Bug Fix: Seats available check for Unallocated Seats in a Seating Plan incorrect
* Bug Fix: StageShowLibDirectDBaseClass does not report error details when DB connect fails
* Gateway simulator skips sale select if only one pending sale

= 4.6.0.3 (27/03/2015) =
* Bug Fix: Undefined seatingID in gateway simulator
* Bug Fix: Gateway simulator does not run callback code
* Bug Fix: DateTime format incorrect in non-wp code
* Shopping Trolley MySQL queries optimised

= 4.6.0.2 (24/03/2015) =
* Bug Fix: Settings Drop down selectors with only one option give blank OutputBulkActionsScript 
* Bug Fix: Donation inconsistent operation in Shopping Trolley  
* Bug Fix: Post Items option inconsistent operation in Shopping Trolley  
* Bug Fix: Message to seller operation in Shopping Trolley 
* Added MySQl DB_CHARSET to non-wp code
* Added PAYMENT_API_SALESTATUS_UNVERIFIED state 
* Added relocate zone option to zones defs (i.e. [ru7.5]4.3-4.9) (StageShowGold)
* Bug Fix: Date/Time picker nav buttons missing with legacy IE browsers
* Updated contributors list (translations)
* Updated translations
* Added retry code to Gateway Callback verify
* Added item description to PayFast Payment Gateway
* Debug menu changed to Diagnostics menu

= 4.6.0.1 (26/02/2015) =
* Added salePostage to Add Manual Sale 

= 4.5.6 (25/02/2015) =
* Bug Fix: Possible Parse Error in wp-config-db.php - Breaks Tools Page/Sale Validator
* Bug Fix: Tools|Verify Sale does not work with Manual Sales (since ver4.5)
* Bug Fix: Incorrect style for Manual Sale Add buttons
* Added Send EMail button to Manual Sale Confirmation Screen
* Shopping Trolley "Add" and "Remove" buttons responsiveness improved using JQuery
* Add SQL_BIG_SELECTS=1 to MySQL queries
* Added Continue button image
* Records user_login with each sale (when logged in)

= 4.5.5 (31/01/2015) =
* Bug Fix: Error Activating Plugin - STAGESHOWLIB_CHECKOUTSTYLE_STANDARD undefined

= 4.5.4 (30/01/2015) =
* Bug Fix: Currency Symbol not shown on Postage price
* Bug Fix: Currency Symbol not updated when gateway changed (StageShowPlus)
* Bug Fix: Box-Office - Unallocated seats count error with pre-allocated seats (StageShowGold)
* Added code for uid-sid and uid-pid to use text entries in URLs (StageShowGold)
* Added examples of {URL....}sid="The Wordpress Show" etc. (StageShowGold)

= 4.5.3 (26/01/2015) =
* Option to select show name or performance date in Box-Office URL (StageShowGold)

= 4.5.2 (19/01/2015) =
* Bug Fix: Possible problem with saving Currency Format
* Bug Fix: Currency Type not shown on PayFast Gateway (StageShowPlus)

= 4.5.1 (16/01/2015) =
* Added option to specify Box-Office show or performance IDs in page URL (StageShowGold)

= 4.5 (14/01/2015) =
* Optimisation: Loading and Saving of StageShow settings improved
* Payment Gateway Optimisation: Includes only loaded when needed
* Added Post Tickets Option (StageShowPlus)
* Margin of Seats Requested/Seats Selected on Seat Selector fixed (StageShowGold)
* Select Seats Button can now be an image (define STAGESHOW_SEATSSELECTEDBUTTON_URL) (StageShowGold)
* Bug Fix: Errors when defining Zone Specs not reported corectly (StageShowGold)
* Bug Fix: Extra zone added when saving Zone Spec Updates (StageShowGold)

= 4.4 (28/12/2014) =
* Bug Fix: Seat Decode Definition only changes after second template modification (StageShowGold)
* Bug Fix: Error in default IPN URLs
* Bug Fix: Server Error accessing wp-config.php in IPN call (on some servers)
* Added "Inactivity" timeout to Shopping Trolley - default 30 minutes
* Add Zone and Import Template Buttons removed when Seating Plan is ReadOnly (StageShowGold)
* Added "Requested" and "Selected" Seats Count to Seats Selector (StageShowGold)

= 4.3.1 (22/12/2014) =
* Bug Fix: Historical IPN Target URL (s) missing
* Added option to block purchasers leaving gaps in seating (StageShowGold)

= 4.3 (16/12/2014) =
* Bug Fix: Ticket Quantities Breakdown and Ticket Name Columns missing in TDT export
* Buf Fix: Uninstall Plugin failure
* Bug Fix: HTML special characters in shortcode atts not decoded
* Added "PayFast" Payment Gateway (StageShowPlus)
* Added Debug Output option to Send EMail Test 
* Added "Bcc to Admin" option to Send EMail Test 
* Added Anchor Tag for Top of Shopping Trolley to Box-Office output
* Added Anchor Tags for Top of Each Show to Box-Office output
* Added shortcode anchor argument

= 4.2.3 (20/11/2014) =
* Bug Fix: Ticket Validator Failure (StageShow & StageShowPlus)

= 4.2.2 (17/11/2014) =
* Bug Fix: StageShowPluginClass undefined (since 4.2.1)

= 4.2.1 (15/11/2014) =
* Bug Fix: Ticket Validation fails on Unix servers
* Bug Fix: Ticket Validation fails if PHP does not support mysqli_fetch_all ()

= 4.2 (11/11/2014) =
* Bug Fix: Logs folder path permissions should be 600
* Bug Fix: Paragraph tag before Validate button should not have a class
* Buf Fix: Exported Seating Template opens in browser (StageShowGold)
* Optimisation: activate () function called twice on first activation
* Optimisation: PayPalImagesUseSSL option used before definition
* Optimisation: Ticket authentication response improved using JQuery 
* Added additional barcode type (Code 128)
* Added contributors list to Overview Page
* Logs folder path default changed to "logs"
* Added JQuery loader
* Copies DB Access defines to wp-config-db.php in uploads folder
* Added translation for Seating Plans Buttons

= 4.1.2 (17/10/2014) =
* Bug Fix: Purchaser Name, Show and Performance missing on Offline Validator
* Bug Fix: Added Seats to Offline Validator (StageShowGold)
* Bug Fix: Seats not decoded in TDT export file (StageShowGold)
* EMail Address and Prices removed from Offline Validator
* Removed redundant Shopping Trolley onClick handlers on admin pages
* Added class to Remove button on admin page

= 4.1.1 (15/10/2014) =
* Bug Fix: Phantom PayPal button and Next button inoperative when editing sales (StageShowGold)

= 4.1 (13/10/2014) =
* Bug Fix: Adding Tickets for same date-time and Ticket Type always adds entry already in Trolley
* Bug Fix: Sites on Secure Server have incorrect URL for admin page filters
* Bug Fix: Sites on Secure Server have incorrect URL for Box-Office reports on Overview
* Bug Fix: Performance Expiry Date not used to determine active Performances by Verification Check
* Bug Fix: Overview Sales values calculated incorrectly for Group tickets
* Added Spanish Translation
* Added details on updating translations to help
* Remove buttons on Trolley changed from links to submit button (in a &lt;form$gt;)
* Removed box shadow from Add buttons
* Added Sale Verification fields to TDT ticket download
* Added option for PayPal Express Checkout (StageShowGold)
* Added Verify Fields to TDT Downloads

= 4.0.2.1 (27/09/2014) =
* Sale Verification code moved to separate class (StageShowSaleValidateClass)
* Added stageshow_direct_validate.php - Direct Sale Verifier (StageShowGold)
* Added Spanish Translation

= 4.0.2 (20/09/2014) =
* Bug Fix: Non-default WP DB table prefix produces empty TDT download 
* Bug Fix: Offline Validator fails with non-default WP DB table prefix  
* Bug Fix: Checkout button with image fails to redirect to PayPal on Firefox/IE but OK with Chrome
* Added "Ticket Paid" values to Offline Validator results
* Automatically creates copy of stageshow-custom.css when selected in settings
* Automatically creates copy of stageshow-custom.js when selected in settings

= 4.0.1 (10/09/2014) =
* Bug Fix: Invalid Comment line in stageshow.css blocks loading of stagehsow-seats.css
* Bug Fix: Cannot see seats in Box-Office seat selection Page (stageshow-seats.css not loaded)
* Added sample CSS for Box-Office button colours to stageshow-custom.css

= 4.0 (07/09/2014) =
* Bug Fix: Error on OFX Export with no sales
* Bug Fix: Non-existent CSS file imported (admin.css)
* Bug Fix: Content-Disposition MIME type should not include attachment
* Bug Fix: Box-Office quantity drop-down limited to 1 when no of seats is unlimited
* Bug Fix: saleNoteToSeller not initialised in empty Shopping Trolley
* Duplicate output of wp_nonce removed
* Added CSS for removing Date/Time column to stageshow-custom.css
* Export settings includes uncompleted Show & Performance entries

= 3.9.4 (26/08/2014) =
* Bug Fix: nonce missing in Remove from Shopping Trolley link
* Added STAGESHOW_***********BUTTON_URL defines to use images for Box-Office buttons
* Added optional "Donation" entry to Shopping Trolley (StageShowPlus)
* SeatingPlans made readonly once prices defined (StageShowGold)
* Added more examples of defines to stageshow_wp-config_sample.php 

= 3.9.3 (19/08/2014) =
* Bug Fix: Invalid Class name in stageshow_export.php on line 34 (since 3.8)
* Added sample wp-config.php file (stageshow_wp-config_sample.php)
* Added $_GET and $_POST to debug output options
* Separated Booking Fee and PayPal Fees in TDT Export

= 3.9.2 (08/08/2014) =
* Bug Fix: Entries with empty filenames added when Custom CSS or JS files are not defined
* Bug Fix: Note to Seller entry lost when "Select Seats" is selected (StageShowGold)
* Added optional define to replace Box-Office "Remove" link with image
* Added stageshow-boxoffice class to Box-Office buttons
* Added optional defines to rename Box-Office column labels
* Added HTTP Error Status to Plugin Auto-Update Status
* Added templates/html/stageshow-custom-defines.php with details of advanced customisations

= 3.9.1 (02/08/2014) =
* Bug Fix: Distribution problem with StageShow plugin v3.9 - GetPluginStatus undefined
* Bug Fix: Box Office Quantity selector can exceed maximum available seats
* Bug Fix: "Checkout Note" shown when editing sale
* Plural forms of system message used instead of singular forms

= 3.9 (01/08/2014) =
* Bug Fix: Parse Error if class redefinition is attempted
* Added "Note to Seller" to Sale Manual Entry/Editor and Sales admin page
* Added onClick () event handler framework to all Box-Office buttons
* Added shortcodes for performance ID and performance Date/Time (StageShowPlus)
* Auto-update disabled settings link sets focus on activation (StageShowPlus)
* Added Auto-update Server Status to Plugins and StageShow Overview pages (StageShowPlus)
* Added option for Custom Javascript file (StageShowPlus)
* Added framework for custom Checkout HTML elements (StageShowPlus)
* Added sample JS code for custom Checkout HTML elements to stageshow-custom.js (StageShowPlus)
* Allocated Seating always enabled - setting option removed (StageShowGold)

= 3.8.7 (21/07/2014) =
* Bug Fix: Sale Editor fails (StageShowPlus)
* Bug Fix: stageshow-boxoffice-seat class missing from seating templates (StageShowGold)
* Bug Fix: saleNoteToSender not shown in sample email templates
* Bug Fix: Settings "Tabs" not selecting entries correctly
* Bug Fix: Reserved status not shown by Sale Editor (StageShowPlus)
* Added "Note To Seller" to sales report
* Box-Office page buttons can use images (Set by defines in wp-config.php)

= 3.8.6 (06/07/2014) =
* Bug Fix: Obsolete JS call to SetSalesInterfaceControls () removed
* Bug Fix: Exported Seating Plans not stripped of automatically created tag parameters (StageShowGold)
* Bug Fix: Seating Template seat tags missing stageshow-boxoffice-seat class (StageShowGold)
* Box Office screen changed to have a single &lt;form&gt; tag (StageShowGold)
* Added optional "Note to Seller" to Checkout
* PayPal API settings made optional
* MerchantID made optional (Uses PayPal Account email if blank)
* Added stageshow-boxoffice-zone-{ZoneRef} to Seating Template seats classes (StageShowGold)

= 3.8.5 (25/06/2014) =
* Added SSL option for PayPal images
* Added option to specify Seating Plan for Price Plans (StageShowGold)
* Bug Fix: Zone selection missing in Price Plans (StageShowGold)

= 3.8.4 (18/06/2014) =
* Bug Fix: Distribution problem with 3.8.3 on Wordpress.org
* Seat Template has Decoded seat names as title tags (StageShowGold)

= 3.8.3 (16/06/2014) =
* Bug Fix: StageShow displays expired performances when there are non-expired performances (StageShow)
* Bug Fix: layoutNames not defined error when reporting zone spec parsing error (StageShowGold)
* Added DecodedSeatIDs as title param in Seat Layout Template seats tags (StageShowGold)

= 3.8.2 (29/05/2014) =
* Bug Fix: Seating Plan "Bulk" Delete gives "Nothing to Delete" Error

= 3.8.1 (27/05/2014) =
* Bug Fix: Number of seats remaining never shown on last box office entry
* Added option for non-allocated seat zones (StageShowGold)

= 3.8 (03/05/2014) =
* Bug Fix: StageShow on WP.org has [ticketSeat] in EMails
* Bug Fix: Error Exporting StageShowGold Seating Templates (StageShowGold)
* Bug Fix: "Admin Only" prices not included in Add/Edit Sale screen
* Bug Fix: Sale Status not included in Tickets download 

= 3.7 (27/04/2014) =
* Bug Fix: Edit Sale only saved when purchaser contact details are changed

= 3.6 (26/04/2014) =
* Bug Fix: Manually Added Sale with Allocated Seats not saved to database (StageShowGold)
* Bug Fix: Add Sale generates "saleStatus undefined" error 
* Checkout Header and Logo file types extended to include GIF, JPG and PNG
* Added "error" and "ok" class to stageshow notifications

= 3.5 (21/04/2014) =
* Bug Fix: PHP Strict standards error on function Export () declaration
* Bug Fix: Some Admin screens have edit text entries set to zero size
* Added show & performance filters to Tools->Export (StageShow+)
* Javascript function names now include plugin name (to help make them unique)

= 3.4 (18/04/2014) =
* Bug Fix: Validate ticket output does not output seats (StageShowGold)
* Bug Fix: Sale Total incorrect for Sample Sale with Allocated Seating (StageShowGold)
* Added Booking Fee to Sample Sales
* TDT Download Filenames changed
* Paid/Fee etc. fields removed from Summary Download
* Added ticketFee entry to Tickets Export
* Rendundant Columns removed from Tickets Export
* PayPal Fees and Booking Fees split between each ticket in Ticket Export
* Tickets Export Fields Rationised

= 3.3 (12/04/2014) =
* Bug Fix: Some HTML &lt;input&gt;tags on Admin pages incorrect size (size attribute is zero)
* Bug Fix: Missing space between tags in Seating Templates (StageShowGold)
* Bug Fix: Max Seats hidden on Performances page once Sales have been made (StageShowGold)
* Added seating Row/Seat "Translator" (StageShowGold)

= 3.2 (02/04/2014) =
* Bug Fix: OutputList () E_STRICT error 
* Bug Fix: Rogue .htaccess file in build (from 3.1) denys access to CSS and JS files
* Bug Fix: View Template output terminated early (StageShowGold)
* Bug Fix: HTML tag deliminator missing in Imported Templates (StageShowGold)
* Implemented Family tickets for Allocated Seating (StageShowGold)

= 3.1 (30/03/2014) =
* Bug Fix: Payment Timeout emails not generated correctly
* Bug Fix: Sale Summary Reports generated for Pending Sales (StageShowPlus)
* Bug Fix: DB Error when attempting to delete seating plan (StageShowGold)
* Bug Fix: Add Seating Plan button missing when list empty (StageShowGold)
* Bug Fix: Edit Performance Fails if Prices have been defined (StageShowGold)
* Bug Fix: SeatingID can be changed once Prices have been defined (StageShowGold)
* Updated for compatability with WP 3.9

= 3.0 (10/03/2014) =
* Bug Fix: Link to settings page from Overview page incorrect
* Bug Fix: Shortcode with count=** attribute not working
* Add/Edit Sales uses same UI as Box-Office page
* Ticket Options removed from Box Office Listing once Allocated Seats all taken (StageShowGold)
* Number of Available Seats for each Zone now shown on Box Office (StageShowGold)
* Edit of Seating Plan blocked when Prices have been defined  (StageShowGold)
* Max Seats edit hidden for Performances with Allocated Seating (StageShowGold)
* Updated Seating Plan stylesheet (StageShowGold)
* Added Number of Seats to Seating Plan Page  (StageShowGold)

= 2.5.3.5 (25/02/2014) =
* Implemented Manual Edit/Add for Allocated Seating Sales (StageShowGold)
* Added Seat Number in Confirmation EMails (StageShowGold)

= 2.5.3.3 (19/02/2014) =
* Bug Fix: Errors in Sample Data 
* Sale Editor Updated (but Incomplete) 

= 2.5.3.2 (17/02/2014) =
* Buf Fix: Option for "Box Office Below Trolley" inconsistent
* ZoneRef removed from seating templates
* Added zone limits to JS
* Select Performance screen removed from Box Office
* Partially complete SSG sale editor added

= 2.5.3.1 (15/02/2014) =
* Database Text Field Length definitions updated so they can be defined in wp-config.php

= 2.5.3 (04/02/2014) =
* Bug Fix: Fatal Error on Checkout - STAGESHOWLIB_LOGSALEMODE_CHECKOUT undefined

= 2.5.2 (03/02/2014) =
* Bug Fix: Interaction with other plugins can cause Performance Date & Time Picker to fail
* Bug Fix: Adding border to settings page corrupts other admin pages
* Bug Fix: No of sets in Price Plan not saved in new performance prices  (StageShow+)
* Bug Fix: Allocated Seats availability not checked before commiting sale (StageShowGold)
* Bug Fix: Allocated Seats not saved for Reservations (StageShowGold)
* Added check that seats are still available on checkout (StageShowGold)
* PHP with E_STRICT enabled generates warnings

= 2.5.1 (26/01/2014) =
* Bug Fix: Adding border to settings page corrupts other admin pages
* Bug Fix: Allocated Seats not saved for Reservations (StageShowGold)

= 2.5 (25/01/2014) =
* Updated for WP 3.8.1
* Bug Fix: Incorrect class for blank cells in Trolley "Remove" column
* Added link to SSG in readme
* Added custom stylesheet loader (StageShow+)
* Support for PayPal Sandbox removed
* StageShowGold (Beta) Released

= 2.4.4 (21/01/2014) =
* Bug Fix: Cannot reserve last seat for a performance
* Bug Fix: No error message when attempting to Reserve seats when Sold Out
* Bug Fix: Tickets Reserved Message class is stageshow-error (changed to stageshow-ok)
* Bug Fix: Booking Fee still shown after last row removed from trolley

= 2.4.3 (02/01/2014) =
* Bug Fix: Save Settings Error: Undefined constant TABLEENTRY_DATETIME (since 2.4.2)

= 2.4.2 (30/12/2013) =
* Aborts Activation if another StageShow variant is already activated
* Added Date/Time Picker to Performances Editor
* Added JS for Seat Selector (only used by StageShowGold)
* Modified styles for Sales Editor "View Email" button

= 2.4.1 (16/12/2013) =
* Bug Fix: Undefined Field 'transactionfee' when checking out Reservations
* Removed redundant Javascript

= 2.4 (15/12/2013) =
* Bug Fix: Verification Performance ID incorrect when performance select drop-down is not shown
* Blocks Verification if TxnID is blank
* Added view ticket to Sales Editor and Tools page (opens in separate window)
* Added "Number of Seats" option to Prices (StageShow+)
* Added Updates for WP3.8
* Booking Fee and Total rows realigned in Shopping Trolley output

= 2.3.4 (07/12/2013) =
* Bug Fix: Multiple Checkout output on pages with multiple shortcodes
* Bug Fix: Oversize Barcode in HTML Emails (StageShow+)
* Added "Seats Available" output
* Added &lt;div&gt; tag with border to HTML Emails to define page size (StageShow+)
* Added borderless HTML Email template (StageShow+)

= 2.3.3 (02/12/2013) =
* Bug Fix: id={ShowName} in shortcode not recognised
* Bug Fix: Checkbox settings may update when ReadOnly
* Bug Fix: FirstName and LastName values missing in TDT download
* Bug Fix: Incorrect PayPal URL in "Sandbox" mode
* Added Booking Fee (StageShow+)
* Added "Box Office Below Trolley" option (StageShow+)
* Added Plugin Website link to Box-Office output
* Relabelled "Fees" as "PayPal Fees"

= 2.3.2 (12/11/2013) =
* Bug Fix: Incorrect PluginURI blocks Plugin Upgrade ... server also patched to allow updates
* Bug Fix: T_PAAMAYIM_NEKUDOTAYIM expected error with PHP 5.2
* Added Performance selector to Transaction Validator
* Added custom Styles for Transaction Validator results
* Sample Sale TxnIds changed to 17 characters
* Added PayPal Simulator (for DEMO mode)
* Allocated Seating defaults to enabled (StageShowGold)
* Added HTTP Diagnostics to Plugin Updater (StageShowPlus/Gold)

= 2.3.1 (03/11/2013) =
* Bug Fix: Price Plans admin page generates Class not found error (StageShowGold)

= 2.3 (30/10/2013) =
* Bug Fix: Checkout Complete URL and Checkout Cancelled URL not passed to PayPal Checkout
* Bug Fix: <head> and <body> tags missing in email templates
* Bug Fix: Changed include to requires_once - Fix for "Zend Error" bug in PHP APC
* Bug Fix: Email Logo Image corrupts emails displayed by hotmail 
* Added styles for allocated seating (StageShowGold)
* First Release of StageShowGold - Includes Allocated Seating
* Added code for Demo Mode
* Added STAGESHOW_CAPABILITY_VIEWSETTINGS
* BARCODE_ defines can be set externally
* Checkboxes in mjslib_table display Yes/No when ReadOnly
* Tested with WP 3.7
* Added Seating Plans editor (StageShowGold)

= 2.2.5 (17/10/2013) =
* Bug Fix: DB error generating Box Office output  (since v2.2.4) (StageShow)
* Bug Fix: Tools-Export does not generate output (since v2.2.4)
* Bug Fix: stageshowplus_tdt_export.php missing in Distribution (since v2.2.4) (StageShow+)

= 2.2.4 (08/10/2013) =
* Bug Fix: Performance Expiry Date/Time does not track performance Date/Time Changes (since v2.1.5) (StageShow+)
* Bug Fix: Performance Expiry Date/Time includes seconds (StageShow+)
* StageShowPlus/StageShowGold Specific DB Fields moved to Version Specific Classes
* AddSample********* functions added

= 2.2.3 (06/10/2013) =
* Bug Fix: Test Email Destination not reported if not diverted
* Bug Fix: PayPal IPN fields not converted to UTF-8 (Special Characters not displayed/stored)
* Bug Fix: Email template not updated on upgrade from StageShow to StageShow+
* Bug Fix: Checkout errors not reported
* Bug Fix: Settings label not translated
* Bug Fix: StageShow+ Updates not detected on some servers
* Overview page Trolley Type output replaced by Plugin Type and Version
* Timezone reported on Overview page - with error notification if it is not set
* "Bcc EMails to WP Admin" setting renamed "Bcc EMails to Sales Email"

= 2.2.2 (16/09/2013) =
* Bug Fix: Sales not logged to Database
* Bug Fix: JS onchange for some SELECT and INPUT HTML elements has function name omitted
* Bug Fix: Templates not copied if destination file exists and is readonly
* Bug Fix: Summary Email generated when Checkout selected
* Purchaser name from PayPal split into FirstName and LastName

= 2.2.1 (15/09/2013) =
* Bug Fix: Upgrade from using "PayPal Shopping Cart" can leave uneditable blank Merchant ID
* Bug Fix: Items not added to Shopping Trolley with Version 2.2 distribution 

= 2.2 (07/09/2013) =
* Bug Fix: "Add" button not translated
* Bug Fix: DB error generating SaleSummary email when there are no sales
* Bug Fix: Offline Validator needs keyboard input when used with Barcode reader
* Bug Fix: Offline Validator download filename has incorrect file extension
* Support for PayPal Checkout removed
* StageShow styles loaded after theme style
* Added limited duplicate scan detection to Offline Validator
* Added translations to Offline Validator

= 2.1.6 (15/08/2013) =
* Bug Fix: Translations missing on Box Office and Shopping Trolley output
* Bug Fix: Purge Pending sales ignored daylight saving time
* Now checks WP_LANG_DIR for translation files in addition to plugin 'lang' directory

= 2.1.5 (04/08/2013) =
* Added "Contact Phone" to Sale Log details
* Updated for compatibility with WP 3.6 - depracated split () recoded
* Separated FirstName and LastName in DB and Export files
* Removed seconds from performance time display

= 2.1.4 (31/07/2013) =
* Bug Fix: Admin URLs with _wpnonce arg may have html encoded arg separator
* Added Search Sales facility to sales page

= 2.1.3 (12/07/2013) =
* Bug Fix: Styles did not format Shopping Trolley output on Box Office page
* Bug Fix: Invalid WP Date/Time format gives blank performance dates on Box Office page
* Confirm action on Delete or Set Completed Actions

= 2.1.2 (11/07/2013) =
* Bug Fix: Price Plans not checked for valid prices (StageShow+)
* Bug Fix: Bottom Bulk Action Apply button uses Selected Top Bulk Action when valid
* Zero prices permitted with Integrated Checkout

= 2.1.1 (05/07/2013) =
* Bug Fix: Checkout total is zero when currency symbol is enabled

= 2.1 (04/07/2013) =
* Bug Fix: Performance name not shown when Performance sales log has no sales
* Bug Fix: Shows Lists have inoperative pagination controls (sometimes)
* Bug Fix: Performance Lists have inoperative pagination controls (sometimes)
* Bug Fix: Sales Lists have inoperative pagination controls (sometimes)
* Bug Fix: Show name not shown when Show sales log has no sales
* Bug Fix: Bulk actions do not report error if nothing changed
* Bug Fix: Status message not shown for Activate/Deactivate Show action
* Bug Fix: Prices entires for unchanged show (s) blank after duplicate price ref error (StageShow+)
* Bug Fix: Default Performance Expires time does not track changes in performance time
* Bug Fix: Incorrect value for Sample Sales total paid values
* Added Reservations (StageShow+)
* EMail Template File renamed "Sale EMail Template" in settings
* Fuctions in one or both of PayPal mode and Reservation mode (StageShow+)
* Implemented "Visibility" setting for prices (StageShow+)
* Performance Expiry time made editable (StageShow+)
* Sales use "local time" for sale time/date
* Leading and trailing spaces removed from text settings entries
* Settings tabs renamed
* Paid/Due column added to Sales List
* Added Checkout Notes

= 2.0.6 (30/06/2013) =
* Bug Fix: Currency codes in text emails changed to three letter currency code
* Bug Fix: Surplus /table tag in empty sales list

= 2.0.5 (06/06/2013) =
* Bug Fix: Undefined stockPrice in Sale Editor fixed
* Bug Fix: Inconsistant visibility of Merchant ID, and API ***** fields in PayPal Settings
* Bug Fix: Edit box for TxnID in Auto-Update Settings too small
* Bug Fix: Plugin version number check inconsistent
* Bug Fix: Daylight saving time handling inconsistent
* Bug Fix: Box Office shows inactive/expired shows
* Deleted Shows, Performances and Prices only removed from DB when not referenced by Sales
* Integrated Checkout syles rationised
* Added salePaid and saleFee to Sales Summary export (StageShow+)
* Flush Sales removed from Tools Menu

= 2.0.4 (21/05/2013) =
* Bug Fix: Fix for WP wp_mail () bug ... no HTML email content for Outlook/iPhone

= 2.0.3 (05/04/2013) =
* Bug Fix: PayPal Checkout failures - Cannot process transaction error

= 2.0.2 =
* Bug Fix: TDT Export MIME type changed to text/tab-separated-values
* Bug Fix: (StageShow+) Plugin Version check gave undefined error if Internet unavailable
* Added Logging of PayPal Transaction Fees
* Ticket Price logged with each sale
* Export File Field Names now defined by translatable table
* Added OFX format export (StageShow+)
* Checkout Timeout added to settings
* Box Office columns widths set by style sheet (stageshow.css)
* Implemented Checkout Complete and Checkout Cancelled URLs in settings
* IPN "Callback" URL changed to stageshow_ipn_callback.php (was stageshow_NotifyURL.php)

= 2.0.1 (04/03/2013) =
* Bug Fix: Integrated Checkout fails for performances with unlimited ticket quantities

= 2.0 (20/02/2013) =
* Bug Fix: Templates not always copied to uploads folder
* Implements Integrated Checkout
* Added Checkout Type and MerchantID to Settings
* Corrected spelling of "perfarmance" on prices admin page
* Blocks edit of PayPal settings once a Show has been defined
* Added Currency Formatting
* Admin Javascript moved to stageshow_admin.js
* Added "Sold Out" message on BoxOffice output when all tickets sold 
* Added missing &lt;div&gt; tag to Sales Admin page
* Added Users Guide (in PDF format)

= 1.2.1 (18/12/2012) =
* Bug Fix: Export Data gives 404 error - stageshow_export.php file has incorrect case
* New prices are added for a specified performance which cannot then be edited

= 1.2 (04/12/2012) =
* Bug Fix: StageShow_Validate capability not deleted on uninstall
* Admin Pages code optimised
* Separators added between tabs on settings page (s)
* Added support for translations
* Added stageshow.pot file to distribution
* Added PayPal login error code reports
* Moved "EMail Template File" option to StageShow settings
* Sample Sales are always for only one show
* New Shows are always initialised as ACTIVE
* Added Settings Page URL param to select tab

= 1.1.7 (23/10/2012) =
* Activate function explicitly called on version update
* Email and Images Templates moved to uploads/{pluginID}/****** folders
* Test Send Email added to Tools admin page
* Admin Pages - Redundant &lt;form&gt; tag action parameters removed
* Admin Pages CSS - stageshow-settings-**** classes changed to mjslib-settings-****

= 1.1.6 (07/09/2012) =
* Bug Fix: Custom templates and images are deleted on plugin update
* Bug Fix: Custom roles not deleted on plugin uninstall
* Emails templates moved to uploads/stageshow/emails folder
* PayPal Logo and Header Images moved to uploads/stageshow/images folder
* PayPal Logo and Header Images now selected using drop-down box on settings page
* Deletes uploads/stageshow folder when plugin is deleted
* Settings page default tab changes once PayPal settings are added
	
= 1.1.5 (23/08/2012) =
* Bug Fix: Version update code does not check database version
* Bug Fix: Ticket Types can be omitted from Sale Summary Export
* Ticket types for some samples changed to "All"
* Added stageshow-boxoffice-add class to Box Office Add buttons
* Renamed 'StageShow+ Auto Update Settings' as Auto Update Settings'
* Sales quantities on overview page are now links to show and performance sales pages 
* Sample performance dates altered to make shows visible on setup
* Settings sections displayed as tabs on admin page
* Add New Performance status messages improved

= 1.1.4 (18/07/2012) =
* Added "Sales Summary" option to Export Data on Tools admin page
* Added Offline Sales Verififier
* Bug Fix: Tools Admin page - Footer appears in middle of page
* Bug Fix: Settings Export permitted for users without 'StageShow_Admin' capability
* Bug Fix: Performances or Prices with associated sales can still be deleted
* Add New Performance uses local date/time as performance date/time

= 1.1.3 (05/07/2012) =
* Bug Fix: (Benign) Overview page generates "Undefined offset" error for Shows without any Performances
* Total Sales values added to Overview Page
* Effenciency improved on Overview page database queries 
* Settings page layout improved
* StageShow admin email defaults to WP admin email
* StageShow Organisation ID defaults to WP Site Name
* EMail template paths default to stageshow/templates folder

= 1.1.2 (30/06/2012) =
* Bug Fix: IPN Fails when sale has quotes in any field (including the Show name)
* ReadMe changelog changed to reverse chronological order

= 1.1.1 (24/06/2012) =
* Added Performance Sales Summaries to Overview Page
* Added "booking confirmed" message to email template
* Missing Configuration messages now include links to the relevant admin page
* Sales page accessible (non-edit) to users with StageShow_Validate capability
 
= 1.1.0 (15/06/2012) =
* Bug Fix: Activate/Deactivate Show not working ...
* Compatible with WP 3.4

= 1.0.7 (13/06/2012) =
* Bug Fix: Sale Ticket Type was always recorded as the same type
* Renamed "Presets" as "Price Plans"
* Coding of Admin Pages restructured
* Error notifications on Admin Pages improved
* StageShow specific styles now defined in stageshow-admin.css
* File Names rationised
* Bug Fix: "Validate" Capability implemented

= 1.0.6 (24/04/2012) =
* Implemented "Hidden Rows" for Details Fields in Sales Screen
* Bug Fix: Inventory Control not working in v1.0.5 - Fixed (includes PayPal buttons update on activation)
* Added check for zero ticket prices - new price entries initialised to 1.00

= 1.0.5 (20/04/2012) =
* Bug Fix: "Add New Show" not displayed on Shows Admin Page (Add New Price ahown instead)
	
= 1.0.4 (14/04/2012) =
* Added Currency Symbols to currency options
* Added option to output currency symbol in Box Office
* Cosmetic: Added separator line between admin screen entries
* Bug Fix: HTML Select element options on some admin screens not retrieved
* Bug Fix: Undefined variable error generated on Performances update error
* Class of BoxOffice output HTML elements changed from boxoffice-**** to stageshow-boxoffice-****

= 1.0.3 (02/04/2012) =
* Bug Fix: Input Edit box size value fixed
* Bug Fix: Box Office shortcode output was always at top of page.
* Max Ticket Count added to settings
* Items per page added to settings
* Negative/Non-Numeric max number of seats converted to unlimited (displayed as infinite)
* New Performance defaults to unlimited number of seats
* Number of seats available can be set to unlimited using negative value (default value)

= 1.0.1 (12/03/2012) =
* Bug Fix: include folder missing from archive in 1.0.0

= 1.0.0 =
* Bug Fix: Call to wp_enqueue_style () updated for compatibility with WP 3.3
* AutoComplete disabled on Settings page
* PayPal Account EMail address added to settings (PayPal may not report it correctly)
* Shortcodes Summary added to Overview page
* Added support of "User Roles" to admin pages
* Added Ticket Sale Reference Validation to "Tools" Admin page
* Added Pagination to all admin screen lists

= 0.9.5 =
* Dual PayPal Credentials merged - Live or Test (Sandbox) mode must be set before adding performances
* StageShow-Plus renamed StageShow+

= 0.9.4 =
* Added StageShow specific capabilities (StageShow_Sales, StageShow_Admin and StageShow_Settings) to WP roles 
* Added Facility to manually add a sale
* Added Facility to activate/deactivate selected performances
* Box Office page elements formatted by stageshow.css stylesheet
* Duplicate dates on BoxOffice output supressed (STAGESHOW_BOXOFFICE_ALLDATES overrides)

= 0.9.3.1 =
* Fixed "Function name must be a string" error when changing Admin EMail ( stageshow_manage_settings.php)

= 0.9.3 =
* Fixed Distribution Error in ver0.9.1
* Added Style Sheet (stageshow.css)
* Added styles to BoxOffice page and updated default style

= 0.9.2 =
* Bug Fix: Malformed &lt;form&gt; tag on BoxOffice page fixed
* BoxOffice time/date format now uses WordPress settings
* (Note: Private release)

= 0.9.1 =
* Added Pagination to Sales Summary
* Added Uninstall
* Added Activate/Deactivate options for shows and performances

= 0.9 =
* First public release

== Upgrade_Notice ==

= 2.2.4 =
* Performances with dates changed by StageShow versions 2.1.5 to 2.2.3 may have incorrect performance Expiry Date/Time  (StageShow+)

= 2.2 =
* Support for PayPal Checkout removed - MerchantID on PayPal Settings tab must be set

= 1.0.7 =
* Bug Fix: Sales were always recorded as the same Ticket Type

= 1.0.0 =
* Earlier versions not compatible with WP 3.3 - Style sheets may not load

= 0.9 =
* First public release

