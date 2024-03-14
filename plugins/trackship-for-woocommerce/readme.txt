=== TrackShip for WooCommerce  ===
Contributors: TrackShip
Tags: WooCommerce, delivery, shipment tracking, order tracking, tracking
Requires at least: 6.2
Tested up to: 6.4.3
Requires PHP: 7.4
Stable tag: 1.7.6
License: GPLv2 
License URI: http://www.gnu.org/licenses/gpl-2.0.html

TrackShip is a shipment tracking and post-purchase experience platform that helps eCommerce businesses take control of their post-shipping workflow and to provide an exceptional shipping journey experience to their customers, helping WooCommerce businesses to fulfill their promise and gain loyalty and trust.

TrackShip auto-tracks shipments with 700+ shipping carriers, it's easy to set up and it seamlessly integrates into the WooCommerce workflows and most of its features are managed inside the WordPress admin. 

TrackShip is a great solution for e-commerce merchants looking to improve their shipping process and customer experience, which is crucial for any eCommerce business to grow and succeed in the long run.

[Start for Free, 50 free shipment trackers / month](https://trackship.com/) 

https://www.youtube.com/watch?v=PhnqDorKN_c

= Why use TrackShip? = 
TrackShip is a shipment tracking and post-purchase experience platform that helps e-commerce businesses take control of their post-shipping workflow.
= Automatic Shipment Tracking = 
TrackShip auto-tracks your orders from shipping to delivery with 700+ shipping providers and carriers around the world. After you fulfill an order, TrackShip auto-tracks the shipments with the shipping providers APIs and updates your store whenever there is an update to the shipment status.
= Take control of the post-purchase workflow = 
TrackShip allows merchants to take control of their post-shipping operations, further engage customers after shipping and not rely on 3rd parties to control the customer experience post-purchase.
= Improves customer experience =
TrackShip provides an easy way for customers to track their orders and receive real-time updates on their shipment status.
= Increases customer loyalty =
By providing customers with a seamless tracking experience, TrackShip helps to increase customer loyalty and repeat business.
= Reduce time spent on customer service =
TrackShip automates the tracking process, allowing merchants to spend less time on manual tracking and more time on other important aspects of their business.
= Provides valuable insights =
TrackShip provides merchants with valuable shipping & delivery insights such as delivery times and carrier performance data that can help them optimize their shipping strategy and improve their bottom line.
= Cost-effective =
TrackShip is a cost-effective solution for merchants looking to improve their shipping process and customer experience without a large investment in time or money.

== What's included? ==
* Shipments dashboard
* Tracking page on your store
* Shipment status & delivery notifications (email/SMS)
* Delivery confirmation (custom order status “Delivered”)
* Shipping & delivery analytics 

== How does it work? ==
1. Signup for a [TrackShip](https://trackship.com/) account 
2. Connect your WooCommerce store with TrackShip
3. Setup TrackShip on your store, enable a tracking page and shipment status & Delivery notifications by email and SMS.
4. TrackShip will auto-track your shipped orders and proactively update your orders whenever there is an update in the shipment status until the shipments are delivered to your customers.

== Documentation ==
For more information, check out our [Documentation](https://docs.trackship.com/docs/trackship-for-woocommerce/)

== Requirements ==

* [TrackShip](https://trackship.com/) account
* WooCommerce REST API enabled
* SSL Certificate - you must have a secured site (HTTPS) to connect TrackShip to your store
* Pretty permalinks - navigate to Settings > Permalinks and make sure that the permalink structure is based on Post Name (TrackShip can’t work with the Plain option)
* Shipment Tracking Pugin (see compatibility)

== Compatibility ==

TrackShip will track orders when you add tracking numbers using one of the following shipment tracking extensions for WooCommerce:

* [Advanced Shipment Tracking (AST)](https://wordpress.org/plugins/woo-advanced-shipment-tracking/)
* [Advanced Shipment Tracking Pro (AST PRO)](https://www.zorem.com/products/woocommerce-advanced-shipment-tracking/)
* [WooCommerce Shipment Tracking](https://woocommerce.com/products/shipment-tracking/?aff=4780)
* [Orders Tracking for WooCommerce by VillaTheme](https://docs.trackship.com/docs/trackship-for-woocommerce/compatibility/orders-tracking-for-woocommerce/)
* [YITH WooCommerce Order & Shipment Tracking by Yith](https://docs.trackship.com/docs/trackship-for-woocommerce/compatibility/yith-woocommerce-order-shipment-tracking/)

= Compatibility with other WooCommerce plugins: =

* [SMS for WooCommerce](https://docs.trackship.com/docs/trackship-for-woocommerce/compatibility/sms-for-woocommerce/) - Send automatic SMS updates for shipment status & delivery via Twilio, Nexmo or ClickSend
* [Checkout for WooCommerce (CheckoutWC)](https://docs.trackship.com/docs/trackship-for-woocommerce/compatibility/checkoutwc/) - Add Tracking Page widget to the Order received page when its set to be the view order page
* [AutomateWoo](https://docs.trackship.com/docs/trackship-for-woocommerce/compatibility/automatewoo/) - use the "Delivered" custom order status to trigger marketing automation based on the order delivery date.

== Documentation ==
Check out TrackShip for WooCommerce [documentation](https://docs.trackship.com/docs/trackship-for-woocommerce/) for more details on how to set up and work with TrackShip

== Frequently Asked Questions ==
= What is a Shipment Tracker?
A shipment tracks one tracking number from the time it's shipped until it has been delivered, no matter how many status events were created during its life cycle.
= What if I have multiple stores?
You can add each one to TrackShip and you'll pay one price based on the combined monthly order volume of all your stores. One subscription for all your WooCommerce and Shopify stores.
= Will TrackShip affect my site’s performance?
Not at all. When you fulfill an order, the shipping information is sent to TrackShip and it does all the heavy-lifting for you, we check the status of the shipment with the shipping provider every few hours and we update your store whenever there is an update in the status, and it does not impact your load time in any way.
= Do I need a developer to connect TrackShip to my store?
Absolutely not! You can easily connect your store with TrackShip in a few simple steps and start enjoying a branded tracking experience in less than 10 minutes..
= I connected my store but the shipment status is not showing for my orders
The trigger to auto-track shipments by TrackShip is to add tracking to the order and change the order status from Processing to Shipped (Completed). TrackShip will not automatically track orders that were Shipped before you connected your store.
You can trigger these orders to TrackShip by using the [Get Shipment Status](https://docs.trackship.com/docs/trackship-for-woocommerce/manage-orders/#get-shipment-status) option on the WooCommerce orders admin in the bulk actions menu.
= My store is connected but many of my orders still show a “Connection error” shipment status
These messages are from before you connected your store, TrackShip auto-track shipments when you change the order status from Processing to Shipped (Completed). 
TrackShip will not automatically track orders that were shipped when you had a connection issue.
You can trigger these orders to TrackShip by using the [Get Shipment Status](https://docs.trackship.com/docs/trackship-for-woocommerce/manage-orders/#get-shipment-status) option on the WooCommerce orders admin in the bulk actions menu.
= How often do you check for tracking status updates?
TrackShip checks the shipment status with the shipping providers APIs every 2-4 hours. We check for updates more often once the package is in the "unknown" status, until the first tracking event is received from the providers API and when the shipment is out for delivery.
= Which shipping providers (carriers) do you support?
TrackShip supports 700+ [shipping providers](https://trackship.com/shipping-providers/) around the globe ,if you can find your carrier on our supported shipping providers list, you can suggest a shipping provider [here](https://feedback.zorem.com/trackship)
= Do you show the shipment status for orders on WooCommerce admin?
Yes, TrackShip adds a Shipment Status column on your orders admin and displays the shipment tracking status, last update date, and the Est Delivery Date for every order that you shipped after connecting your store.

= If a shipment Tracker returns no result, does it count?
It doesn’t. When a shipment tracker is not supported by TrackShip or returned Unknown the Shipment tracker isn’t counted in your trackers balance.

= Do you offer Free Trials?
Yes, When you sign up for your TrackShip account,  you’ll get a free 50 shipments monthly plan, once you finish your trial balance, you can sign up for a paid subscription in order to continue to track additional shipments.

= Will I be charged when my free shipment trackers are finished?
No. You can fully test out TrackShip and all the features with the free trial Trackers without adding a credit card. It is completely up to you if you would like to carry on using TrackShip after your trial has ended.

== Changelog ==
= 1.7.6 - 2024-02-20 =
* Dev - Improved code for displaying Email customizer preview.
* Dev - Improved code to allow saving SMS templates with normal line breaks.
* Dev - Improved Msg91 SMS provider API code.
* Enhancement - Added an "Updated at" column in the TrackShip Shipments.
* Dev - Tested compatibility with WordPress version 6.4.3.
* Dev - Tested compatibility with WooCommerce version 8.6.1.

= 1.7.5 - 2024-01-16 =
* Dev - Improve code of modern tracking page preview
* Dev - remove Cookie popup from Customizer for CookieYes plugin
* Dev - Add compatibility with WP HTML Mail - Email Template Designer
* Dev - tested compatibility with WPML.

= 1.7.4 - 2024-01-09 =
* Fix - fix error of expecting variable (T_VARIABLE)
* Enhancement - New tracking page design added(option added in TrackShip Customizer)
* Enhancement - Added a progress bar in Shipped emails for users without the Advanced Shipment Tracking (Zorem) plugin installed.
* Enhancement - Implemented admin notifications for On Hold and Exception Shipments.
* Enhancement - Implemented customer notification for Pickup reminder Shipments.
* Enhancement - Improved the design of shipment status emails.
* Enhancement - Greek language translation added
* Dev - translation updated
* Dev - Removed the view order link for guest orders on the Tracking page.
* Dev - removed functionality of notifications log of disabled settings from TrackShip Logs
* Dev - Merged TrackShip branding options for Tracking Widget and Shipment Status emails.
* Fix - Resolved database query issues related to deleting shipment meta table rows for deleted orders.
* Dev - tested compatibility with WordPress version 6.4.2.
* Dev - tested compatibility with WooCommerce version 8.4.0.

= 1.7.2 - 2023-12-04 =
* Fix - Fix database error on delete post

= 1.7.1 - 2023-11-08 =
* Fix - Fix shipments count query on TrackShip Shipments

= 1.7.0 - 2023-11-07 =
* Enhancement - added Destination city and state in TrackShip Shipments
* Enhancement - Admin notifications design improved
* Fix - Tracking widget fix on View order page
* Dev - TrackShip SMS settings desgin update
* Dev - Validation for ClickSend credentials during the data saving process.
* Dev - Tested with PHP 8.2.0
* Dev - Tested with WordPress 6.4.0
* Dev - Tested with WooCommerce 8.2.1

= 1.6.5 - 2023-10-06 =
* Enhancement - added Integration tab and Klaviyo integration added
* Enhancement - added Twilio WhatsApp for Shipment status notifications
* Enhancement - Tracking page loader improved
* Dev - Tracking evets delete option removed from tools tab
* Dev - In the admin, Pending Update added instead of Shipped
* Dev - Tested with WordPress 6.3.1
* Dev - Tested with WooCommerce 8.1.1

= 1.6.4 - 2023-09-12 =
* Dev - TrackShip Dashboard design updated
* Dev - TrackShip Dashboard code improved
* Dev - German translation improved
* Fix - Msg91 variable replacement issue fixed for DLT template
* Dev - filter(trackship_for_site_url) added for changing site URL for TrackShip API call
* Dev - Log added for error in TrackShip Shipment's data insert/update query in TrackShip database table
* Dev - option added for verifying TrackShip database table and table column exists 
* Dev - TrackShip database table column order_number length increased to 40 character 
* Enhancement - added TS logo to TrackShip analytics

= 1.6.3 - 2023-08-16 =
* Enhancement - TrackShip SMS service provider added MSG91
* Enhancement - TrackShip Settings design improved and a new tab (Tools) added
* Fix - Database update method improved
* Dev - Compatibility added to Dokan – WooCommerce Multivendor plugin
* Dev - Compatibility added to Klaviyo for WooCommerce plugin
* Dev - TrackShip page customizer improved
* Dev - TrackShip Shipment status email customizer improved
* Dev - TrackShip page widget error message improved
* Dev - Tracking page widget design improved
* Dev - TrackShip Analytics improved
* Dev - TrackShip Dashboard design improved
* Dev - TrackShip settings position changed
* Dev - Active Shipment option added to shipment status filter in TrackShip Shipments
* Dev - Tested with WordPress 6.3.0
* Dev - Tested with WooCommerce 8.0.2

= 1.6.2 - 2023-06-02 =
* Fix - 2 times tracking information on the order admin for same Tracking number added in the 1 order
* Fix - MYSQL lower version query issue fixed
* Dev - Croatian(Hrvatski) language translation added
* Dev - on TrackShip Shipments and Dashbaord page if table not exist then table create code added
* Dev - Add log for Email notification setting is disabled
* Dev - Tested with WP 6.2.2
* Dev - Tested with WC 7.7.2

= 1.6.1 - 2023-04-03 =
* Fix - TrackShip Shipment fix for new user
* Fix - TrackShip Analytics shipping length count fix

= 1.6.0 - 2023-03-28 =
* Fix - Fix warning of 'delivery_number' for not shipped order tracking widget.
* Fix - same products with different prices issue on Tracking page
* Enhancement - TrackShip Shipment design improved and new column added
* Dev - Search with the pound sign (#) in the Tracking form
* Dev - remove the case-sensitive for the carrier mapping
* Dev - Remove tracking link from delivered shipment status email
* Dev - Tracking Page Widget click outside and close popup in admin area 
* Dev - admin phone number field added in SMS settings
* Dev - show/hide shipping provider image option added to the email customizer
* Dev - do not send Shipment status notifications when the order status is delivered
* Dev - Shipment status filter added in the admin WooCommerce order table
* Dev - Datatable library updated
* Dev - Translation added for Deutsch (Sie) language
* Dev - Translations updated
* Dev - Tested with WP 6.2.0
* Dev - Tested with WC 7.5.1

= 1.5.2 - 2023-02-20 =
* Enhancement - View Shipment log added in the order admin for shipment
* Dev - TrackShip branding added in the Shipment statuses email
* Dev -in the Shipment statuses email Unsubscribe option move to footer
* Enhancement - Shipping provider image added, if AST is installed
* Fix - TrackShip Customizer design fix
* Dev - Translations updated
* Dev - Tested with WC 7.4.0

= 1.5.1 - 2023-02-02 =
* Dev - Do not add order notes for shipment status and SMS notification
* Fix - Fatal error for count shipment object/array length
* Enhancement - Add order items to the tracking page widget for shipped orders with one tracking number.
* Fix - issue with shipment email when all shipments are delivered option is checked

= 1.5.0 - 2023-01-23 =
* Dev - Compatibility added with Yith order tracking plugin
* Dev - Compatibility added with WooCommerce Order Tracking plugin
* Enhancement - Tracking form customizer added in TrackShip customizer
* Dev - Shipment table improved order is permanently deleted.
* Dev - TrackShip's menu position changed
* Dev - Shipment length cron improved
* Enhancement - Debug log settings added for shipment status change
* Dev - filter removed "remove_order_id_section", option added for hide order detail tab in the Tracking Form Customizer
* Dev - Tested with WC 7.3.0

= 1.4.8 - 2022-12-05 =
* Dev - Design improved for the Tracking page.
* Fix - Fix the issue of the delivered Shipment status email
* Fix - Fix the issue of the est delivery date in the shipment status SMS 

= 1.4.7.1 - 2022-11-29 =
* Fix - Fatal error: Uncaught ArgumentCountError: Too few arguments to function for filter woocommerce_email_from_name and woocommerce_email_from_address
* Fix - Fatal error: HPOS compatibility code tweak

= 1.4.7 - 2022-11-24 =
* Fix - Fix the issue in the TrackShip Customizer for deleted order
* Dev - TrackShip logs improved
* Dev - Documentation links updated

= 1.4.6 - 2022-11-18 =
* Enhancement - TrackShip customizer design improved
* Enhancement - TrackShip Tracking page design improved
* Enhancement - SMS opt/out option added in the notifications in the Tracking Page
* Improvement - Store connect to TrackShip process improved
* Dev - Item  in this Shipment tab removed if Order is not Tracking per Item
* Fix - Fix the Invalid shipment status issue in Tracking Page
* Enhancement - New Tracking link added
* Dev - Design improved for the get Shipment status button in the Order admin
* Dev - Translation updated
* Dev - Tested with WP 6.1.1
* Dev - Tested with WC 7.1.0

= 1.4.5 - 2022-09-26 =
* Fix - Fix the variation product image issue in Tracking Page
* Dev - Get shipment status notice on all pages of TrackShip
* Enhancement - Integration with Integrately added
* Dev - Filter( exclude_to_send_data_for_provider ) added for do not send Trcking data to TrackShip for a specific provider
* Dev - Compatibility added with AutomateWoo plugin
* Dev - Tested with WC 6.9.3

= 1.4.4 - 2022-08-22 =
* Fix - Fix the fatal error of the str_contains function
* Fix - Fix Bulk send Shipments from Tools
* Dev - Documentation URL updated
* Dev - Tested with WC 6.8.1

= 1.4.3 - 2022-07-30 =
* Dev - TrackShip Customizer design updated 
* Dev - TrackShip Analytics improved
* Dev - Compatibility improved with WooCommerce Shipment Tracking plugin
* Fix - Fix the design issue Of TrackShip Shipment status emails in some devices
* Enhancement - Improved design of TrackShip SMS Settings when SMS for WooCommerce plugin is activated and Map Shipping Providers when AST pro is activated
* Dev - Tested with WC 6.7.0
* Dev - Tested with WP 6.0.1

= 1.4.2 - 2022-06-23 =
* Fix - fixed issue of shipment status email not sent in PHP 7.x
* Dev - Tested with WC 6.6.1
* Dev - Tested with WP 6.0

= 1.4.1 - 2022-06-14 =
* Dev - Last event update time added in order admin list
* Dev - tab added in Tracking Page Shipment progress, Items in this shipment, Notifications.
* Dev - Unsubscribe shipment status email option added in Shipment status email and on Tracking Page
* Dev - compatibility added for yith woocommerce badge management plugin
* Enhancement - for USPS if the shipment is Delivered, Parcel Locker in this case we will show Delivered, Parcel Locker.
* Enhancement - Mockup order preview option added in TrackShip Shipment status email Customizer.
* Enhancement - Late Shipment email logs added in TrackShip logs.

= 1.4.0 - 2022-05-17 =
* Enhancement - Analytics menu name changed under WooCommerce Analytics "Shipping & Delivery"
* Enhancement - Settings design improved
* Enhancement - Notifications tab design improved
* Enhancement - TrackShip Customizer design improved
* Dev - compatibility added with Booster for WooCommerce Pro plugin
* Dev - Shipment status emails logs and SMS logs improved
* Dev - Analytics menu added under TrackShip
* Dev - Tracking form improved
* Dev - filter added for remove to search by order id and email id in Tracking page form
* Dev - Tested with WC 6.5.1

= 1.3.6.1 - 2022-04-14 =
* Enhancement - Design improved
* Dev - compatibility added for Wp-lister-amazon plugin
* Fix - fixed issue of HTML content in Shipment status email content
* Fix - fixed issue of saving customizer data in the Firefox browser
* Fix - fixed issue error of tracking per item Shipment status email when product is deleted
* Fix - fixed issue of WC_mail class.
* Fix - repeating settings option of SMS for WooCommerce.
* Dev - trackship-track-order shortcode added for tracking page
* Dev - Tested with WC 6.4
* Dev - Tested with WP 5.9.3

= 1.3.5 - 2022-03-24 =
* Dev - Improved compatibility with WPML for translations of Shipment status emails.
* Dev - Settings added for enable/disable shipment status notification for that order is created by amazon
* Fix - issue fix when the store is not connected
* Dev - In shipment statuses email shipped product label option and Shipping address option added in Trackship Customizer
* Dev - Tested with WC 6.3.1
* Dev - Tested with WP 5.9.2

= 1.3.4 - 2022-03-10 =
* Enhancement - Progress bar design updated.
* Enhancement - TrackShip customizer design updated.
* Dev - in Tracking Page link color added to Label Which color use in TrackShip customizer
* Dev - Support added for alphanumeric sender id for ClickSend
* Dev - discontinue to create a log in WooCommerce log for Shipment status email
* Dev - Logs and tools menu added under TrackShip menu
* Enhancement - Email tab and SMS tab design updated
* Enhancement - Tracking form design updated for TPI order
* Enhancement - in Shipments if TrackShip change shipping provider for that tool-tip added
* Fix - TrackShip customizer issues fixed
* Dev - Tested with WC 6.3.0
* Dev - Tested with WP 5.9.1

= 1.3.3 - 2022-02-14 =
* Fix - TrackShip customizer issues fixed

= 1.3.2 - 2022-02-11 =
* Dev - filter added to change the capability of TrackShip menu
* Dev - Tested with WC 6.2.0

= 1.3.1 - 2022-02-07 =
* New - Shipment status SMS and email log added in the table
* Enhancement - New Placeholders added in Shipment status SMS
* Dev - TrackShip Shipment design improved and search bar design updated
* Enhancement - TrackShip customizer design updated
* Enhancement - TrackShip icon updated in WordPress menu
* Enhancement - Shipment status SMS design updated
* Fix - Shipped email when WC shipment tracking plugin installed
* Improvement - Tracking page design.

= 1.3 - 2022-01-19 =
* Enhancement - New design of Tracking widget and shipment email Customizer
* Enhancement - link color option added in Tracking Widget and Shipment email customizer
* Enhancement - origin and destination country of shipment for option added in Tracking Widget customizer
* Enhancement - Last mile tracking number option added in Tracking Widget customizer
* Dev - show_est_delivery_date filter added to remove est delivery date from the tracking widget and shipment status emails
* Design - Improved the design of the shipment status column in Shipments.
* Improvement - in Late shipment email link updated of view all late shipment button
* Dev - TrackShip menu moved before WooCommerce
* Enhancement - Support, Docs, Review link added in plugin's page
* Dev - Tested with WC 6.1.1
* Dev - Tested with WP 5.8.3

= 1.2.4 - 2021-12-20 =
* Fix - Improved the design of the responsive Tracking page.

= 1.2.3 - 2021-12-16 =
* Dev - sync Trackship provider button added in Map Shipping provider
* Dev - Shipment status SMS code improved for Tracking per item
* Dev - code improved for Shipment status email in email content for variable {est_delivery_date}
* Fix - Fixed issue in deleting tracking events
* Dev - Tested with WC 6.0

= 1.2.2 - 2021-11-22 =
* Dev - Tracking page footer branding link changed
* Dev - Add `show_est_delivery_date` filter to remove the Est delivery date from the email.
* Dev - Remove the h1 tag from the tracking page's shipment status.
* Dev - Access is given to show TrackShip Dashboard for a free user
* Dev - If the Est delivery date is not available in that case in the SMS {est_delivery_date} variable replace as N/A
* Dev - Design update for a store is disconnected
* Fix - Fixed email widget customizer warning

= 1.2.1 - 2021-11-09 =
* Dev - 3 new(Fast2sms, MSG91, SMS Alert) SMS provider added 
* Dev - Compatibility added with Product vendor plugin
* Fix - Fixed issue when late shipment days in settings is not set
* Fix - Fixed shipping item issue in TPI delivered email
* Dev - Translations updated.
* Dev - Tested with WC 5.9

= 1.2 - 2021-10-26 =
* Enhancement - new TrackShip menu added in WordPress
* Enhancement - TrackShip dashboard added in TrackShip menu
* Enhancement - FullFillment Shipment moved into TrackShip menu
* Enhancement - TrackShip Settings moved into TrackShip menu
* Dev - option added in delivered SMS/email notifications when all shipments are delivered then send SMS/email notification
* Enhancement - Design updated for Tracking form
* Enhancement - Design updated for Settings
* Enhancement - More info popup improved
* Enhancement - design improved with RTL
* Enhancement - Filter by Shipping provider added in TrackShip Shipment
* Fix - Fixed issue when SMS provider is not selected and SMS notification is on for shipment status
* Fix - Fixed design issue in delivered email in simple progress bar
* Fix - Fixed HTML issue in tracking detail on the tracking page
* Dev - Order note updated when shipment status changes
* Dev - Tested with WC 5.8.0
* Dev - Tested with WP 5.8.1

= 1.1.5 - 2021-09-08 =
* Dev - Est. delivery date added in Shipment Dashboard
* Enhancement - Design updated for Shipment dashboard
* Enhancement - Design updated TrackShip Settings
* Dev - Late shipment notifications moved into admin notification
* Fix - Fixed issue in TrackShip Analytics
* Fix - Fixed issue for email widget customizer
* Dev - Improved track button functionality in order admin and edit order page
* Enhancement - Design updated Email customizer
* Enhancement - Design updated Tracking widget and Email widget customizer
* Dev - new section added in email customizer for email widget design
* Dev - Improvement in WC admin notice
* Dev - Improved Shipment SMS Placeholders compatibility with WooCommerce Shipment tracking plugin
* Dev - Improved order note in edit order page for Shipment SMS
* Dev - Translations updated.

= 1.1.4 - 2021-08-23 =
* Dev - design update for tracking widget
* Dev - design update for shipment status email
* Dev - design update for shipment tracking page

= 1.1.3 - 2021-08-18 =
* Dev - design update for tracking widget
* Dev - design update for shipment status email
* Dev - {shipping_first} and {shipping_last} variable added in shipment status SMS
* Dev - Tested with WC 5.6

= 1.1.1 - 2021-08-11 =
* Fix - Fixed issue for trackship pro user in TrackShip shipment and sms notifications

= 1.1 - 2021-08-10 =
* Enhancement - Trackship analytics added to the WordPress Analytics
* Enhancement - Design updated
* Dev - Trackship settings added to the WooCommerce menu
* Dev - {shipping_first} and {shipping_last} variable added in SMS notifications
* Fix - fixed issue in delivered shipment status email content
* Fix - improvement in the late shipment email

= 1.0.8.1 - 2021-07-26 =
* Fix - Fixed issue in shipment status SMS send

= 1.0.8 - 2021-07-23 =
* Dev - Create trackship shipment table for analytics and shipment
* Fix - Fixed issue in shipment email for tracking per item order
* Enhancement - SMSWOO functionality added
* Dev - add popup for trackship pro
* Dev - Late shipment functionality improved
* Dev - Tracking page customizer improved
* Dev - Tested with WC 5.5.1
* Dev - Tested with WP 5.8

= 1.0.7 - 2021-07-02 =
* Dev - remove the analytics widget from the dashboard
* Enhancement - Design updated on the Shop order page
* Dev - add popup for more info shipment status in shop order
* Dev - add compatibility with Custom order number plugins in the shipment email subject
* Dev - Translation updated

= 1.0.6 - 2021-06-26 =
* Fix - Fixed fatal error in dashboard shipments

= 1.0.5 - 2021-06-25 =
* Enhancement - Design updated
* Dev - Tracking widget email moved into tracking widget customizer
* Dev - progress bar added in the shipment email
* Dev - option added for delete old tracking event
* Dev - Analytics widget by dates (30 days, 7 days, Today)
* Dev - Active shipment dashboard added.

= 1.0.4 - 2021-06-11 =
* Enhancement - Design updated
* Dev - TrackShip menu added in WordPress, removed from WooCommerce
* Dev - hook added in shipment status email.
* Dev - Improvement in Tracking page preview
* Dev - Dashboard menu added in WP
* Dev - Improvement in the dashboard tab
* Fix - Fixed issue in look when the dark background in tracking widget
* Enhancement - Design updated in the shipment email
* Dev - Improvement in shop order page and edit order page
* Dev - Translations updated.
* New - Map shipping provider functionality added.
* Dev - Tested with WC 5.4.1

= 1.0.3 =
* Dev - Order note added for TrackShip, when tracking information is sent to TrackShip and shipment status change 
* Improved UI/UX - Shipment Tracking Column on orders admin list
* Improvement - TrackShip dashboard improved
* Dev - add track link to Shipment Tracking Column on orders admin list
* Dev - Improvement in tracking page popup.
* Fix - Tracking Widget Customizer – Show Only Last Event (was showing 2 last events)
* Fix - Shipment status filter issue solved and fixed when in settings close for shipment status filter
* Fix - in AST when click on the tab link changed
* Twick - Tracking Page Widget on thank you Page – Always show “last event” view.

= 1.0.2 =
* Fix - Tracking Page link fixed in completed email

= 1.0.1 =
* Dev - translations updated.
* Improvement - tracking-form CSS updates.

= 1.0 =
* Initial version.

