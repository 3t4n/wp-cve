=== WunderAutomation ===
Contributors: wundermatics
Tags: automation, ifttt, zapier, nocode, automate, notifications, woocommerce
Requires at least: 5.3
Tested up to: 5.9
Requires PHP: 7.0
Stable tag: 1.9.0
License: GPLv2 or later

WordPress and WooCommerce task automation. Without code.

== Description ==

WunderAutomation brings automation features similar to IFTTT or Zapier to your WordPress site so that you can create simple and powerful automations without writing code. This enables anyone (not just coders) to customize WordPress to suit your needs. Human Friendly.

[youtube https://www.youtube.com/watch?v=qyKyDJOq2VI]

== Overview ==

Among many other things, our users use WunderAutomation to:

- Send reminders to users who created an account but never logged in
- Send follow up emails to users who buy specific products and ask for a review or suggest similar products
- Send emails to users 30 days after the last purchase of a specific product to remind them to restock
- Send notifications to internal email groups, SMS or Slack channels when orders are paid, cancelled or fail
- Send out a personalized WooCommerce coupon to users once they have placed a certain number of orders or have spent a certain amount on the site

In short, WunderAutomation lets users create [workflows](https://www.wundermatics.com/docs/workflows/?utm_source=readme&utm_medium=readme&utm_campaign=potential_users) that automate common WordPress tasks. A [workflow](https://www.wundermatics.com/docs/workflows/?utm_source=readme&utm_medium=readme&utm_campaign=potential_users) consists of a [trigger](https://www.wundermatics.com/docs/triggers/), and one or many steps. A trigger is what kicks of the workflow can be almost anything, for example a new WooCommerce order, a user login or an incoming webhook. Each step is either a [filter](https://www.wundermatics.com/docs/filters/) that determines the workflow should continue or an [action](https://www.wundermatics.com/docs/actions/) that does some work.

You can filter a workflow based on almost anything like WooCommerce order total, the customers email address. Or the WordPress user login count, or a parameter in an incoming webhook. Multiple filters can be combined using logical AND/OR expressions. When all the filters pass, the actions are carried out, you can update WordPress posts or WooCommerce orders, add custom fields, create new users or posts, add or remove taxonomy terms (tags), send emails or run a REST request to an external service.

There are endless ways to combine triggers, filters and actions so you can add almost any custom functionality to your WordPress site.

=== WooCommerce ===

WunderAutomation supports WooCommerce out of the box so it can trigger on WooCommerce events such as receiving a new order and perform actions like adding an order note or changing the order status.

From version 1.9.0 we also support the popular WooCommerce PDF Invoices & Packing Slips plugin so that you can use invoice numbers, date and notes into a workflow.

=== Advanced Custom Fields ===

WunderAutomation also supports Advanced Custom Fields (free and Pro versions) out of the box. ACF fields are accessible both in filters and action parameters so that you can take full advantage of the customizations you've made using ACF. A popular use case is to combine ACF and WunderAutomation to create [customized delivery notifications](https://www.wundermatics.com/2020/01/08/delivery-tracking-notifications-from-woocommerce/).

=== Bulk handling ===

You can also handle objects (users, posts, orders and comments) in bulk using WunderAutomation, we call this Re-Triggers. A re-trigger runs periodically, ie every morning at 8AM and finds all objects that fit a certain criteria. For instance all posts with post type = "post" that was created in the past 30 days.

These objects are then sent to a workflow that where you can handle them individually, for instance to disable comments if the post is older than X days and belongs to a certain category.

=== Webhooks ===

WunderAutomation can handle both incoming webhooks for triggering a workflow and sending out webhooks to external services. Incoming webhooks can be authenticated using Basic Auth, HTTP header tokens or HMAC signed payload (that Github uses). Webhooks makes it easy to use WunderAutomation in combination with [Zapier](https://zapier.com/), you can both start a zap using a WunderAutomation workflow and you can use Zapier to start a workflow.

=== Related objects ===

As of version 1.7 WunderAutomation supports adding related objects to a Workflow using the new "Add object" action. This enables more even advanced workflows where you can access all properties on objects that are related to the page/post/order that triggered the workflow. This is useful in situations where you are setting up relationships between custom post types, ie "city" and "country". Using "Add object" you can use the parent "country" object in filters and parameters when a "city" post have been updated.

== WunderAutomation Pro ==
Out of the box, WunderAutomation has great support for WordPress core, WooCommerce and Advanced Custom Fields. With WunderAutomation Pro you also get access to a number of additional integrations with popular plugins and services.

See [https://www.wundermatics.com/wunderautomation-pro/](https://www.wundermatics.com/wunderautomation-pro/?utm_medium=readme&utm_campaign=potential_users) for more information.

* MailChimp
* MailPoet
* BuddyPress
* Slack
* Telegram
* Twilio (SMS gateway)
* WPForms and WPForms lite
* Contact Form 7

WunderAutomation Pro also comes with more advanced Re-trigger scheduling and some additional WooCommerce parameters and filters.

== Built in functionality ==
Also see: [built in triggers](https://www.wundermatics.com/docs/built-in-triggers/?utm_source=readme&utm_medium=readme&utm_campaign=potential_users)

##### Triggers

Triggers are used to start a workflow

* __Webhook__; Starts a workflow in response to an inbound webhook (i.e from Zapier).
* __User__; Created, Login, Profile updated, Role changed.
* __Post__: Created, Pending, Published, Privatized (published as private), Saved, Status changed and trashed.
* __Order__ (WooCommerce): Created, OnHold, Paid, Processing, Completed, Pending, Refunded, Saved, Cancelled
* __Comment__: Submitted, Approved, Status changed, WooCommerce order note submitted

##### Filters:

Also see: [built in filters](https://www.wundermatics.com/docs/built-in-filters/?utm_source=readme&utm_medium=readme&utm_campaign=potential_users)
Filters can be used to filter out triggered events so that only certain events actually leads to an action being performed.

* __User__: Email, Role
* __Post__: Title, Content, Tags, Categories, Owner, Status, Type
* __Order__: Billing city, Billing country, Billing state, Downloadable products, Virtual products, Payment method, Producs, Product categories, Producs tags, Shipping city, Shipping country, Shipping state, Order total
* __Customer__: Previous Order count, Previous order total
* __Comment__: Author email, Author name, Content, Status, Type

##### Actions

Also see: [built in actions](https://www.wundermatics.com/docs/built-in-actions/?utm_source=readme&utm_medium=readme&utm_campaign=potential_users)
Actions are things a workflow can do / change / update as a result of a trigger

* Add / update custom field
* Change user role
* Create post
* Create user
* Change post / comment status
* Add / remove taxonomy term (tag or comment)
* Write a line to a log file
* Send plain text, HTML or WooCommerece email
* Run a http request to a remote REST API
* Run a webhook call (i.e to Zapier)
* Add WooCommerce order note

##### Parameters

Also see: [built in parameters](https://www.wundermatics.com/docs/built-in-parameters/?utm_source=readme&utm_medium=readme&utm_campaign=potential_users)
Parameters are used to customize an action with content from the object (post, order, user) that triggered it.

* __User__: Id, First name, Last name, Email, Nickname, Role, Last login date
* __Post__: Id, Title, Content, Slug, Admin url, public url, date, modified date, Comment count, custom fields
* __Order__: Id, Status, SubTotal, Total excl. tax, Total tax, Stripe fee, Paypal fee, Payment method, Customer email, Shipping and billing address fields, order key
* __General__: Siteurl, date, blogname, remote IP

WunderAutomation also supports both incoming and outgoing Webhooks which is ideal for integrating your workflows with Zapier. This gives you access to thousands of integrations via their service.

== Logging ==
WunderAutomation logs everything it does and in the interest of being human friendly, it also comes with a searchable log viewer. This makes it easy to keep track of what triggers that has fired, if the filters passed OK or not and if the actions was carried out or not.

== Installation ==

### Install from within WordPress

Visit the plugins page within your dashboard and select ‘Add New’
Search for ‘WunderAutomation’
Click 'Install' and wait until the button caption changes to 'Activate'
Click 'Activate'

### Install manually

Download the zip file
Upload the wunderautomation folder from the zip to the /wp-content/plugins/ directory on your server
Navigate to the Plugins page in WordPress admin and locate the WunderAutomation plugin
Click 'Activate'

== Changelog ==

= 1.9.0 =
* Feature: New action to add rows to a text (log) file.
* Feature: New action to add debug info to PHP error log.
* Feature: Support for WooCommerce PDF Invoices & Packing Slips (filters and parameters).
* Feature: New parameter type Term, allows outputting any taxonomy term(s) as a parameter.
* Feature: Adds customer_note and shipping_phone as WooCommerce order parameters.
* Fixes: Issue with setting owner via login name for Create Post action.
* PRO: Adds support for BuddyPress and BuddyBoss.
* PRO: Fix issue with MailPoet triggers firing multiple times.

= 1.8.1 =
* Fixes: Issues with editing re-triggers
* Fixes: UI support for setting post parent in Create Post action
* Feature: UI support for detecting post meta only updates in Save Post trigger

= 1.8.0 =
* Feature: Importing and exporting workflows via Settings >> Tools
* Feature: WooCommerce integration supports order numbers (i.e. Sequential Order Number for WooCommerce)
* Post saved trigger improved so it's optionally possible to also detect changes in post meta

= 1.7.1 =
* Fixes fatal error when using log level DEBUG
* Fixes broken Ajax product lookup

= 1.7.0 =
* Feature: Re-triggers for enabling bulk handling
* Adds date related filters and parameters for all objects
* Adds PUT method to Webhook and Rest API actions
* Adds new general date related filters based on Workflow runtime date/time
* Adds support tab to settings page, with ticket creation
* Stores last login date/time for all users to enable user.lastlogin filter and parameter
* Adds "pluck" parameter modifier to return part of a delimited string
* Adds transform Ω modifier to return a string in upper or lower case
* Adds "Add object" action to enable working with related posts etc.
* Fixes issue with mixed dropdown labels for some many-to-many filter operators
* Fixes UI bug that messed up rendering of complex filters with multiple groups

= 1.6.3 =

* Fixes: Run once ignored and workflow runs multiple time for same order
* Fixes: Updating meta data fails in some workflows
* Fixes: Delay acting on webhooks until after all other plugins has initialized
* Fixes: Adds option to make an order status change manual via checkbox in UI
* Fixes: Setting log level to debug can trigger PHP fatal error in some cases
* Fixes: Order notes always set to private notes

= 1.6.2 =

* Fixes: Filters not evaluated correctly in some cases
* Fixes: Workflow version not updated correctly when creating new workflows after upgrade

= 1.6.1 =

* Emergency fix, various UI bugs in some browsers

= 1.6.0 =

* WARNING: When you upgrade to this version you also MUST upgrade all WunderAutomation add-ons at the same time. Make sure to check that you have entered correct license information for any addons in order to enable automatic updates.
* Introducing steps instead of separate filters and actions
* Improved object handling
* Consistent support for Advanced custom fields for posts, order, users and comments
* Adds order_key parameter to WooCommerce order objects
* Internal: Upgraded to Vue3 and Tailwind

= 1.5.11 =

* Emergency fix. Wrong version of critical file shipped causing the workflow editor to stop working.

= 1.5.10 =

* Fix: Resolver internal method now correctly calls filters and actions for 3rd party plugins
* Fix: More sensible defaults for the email From address when using the Email sending actions
* Fix: Performance improvements
* Fix: Adds "length" as a new attribute to control WooCommerce coupon string length (not in UI)
* UX: Email from address field now has link to docs page to provide help
* Internal: Added PHP8 to all test suites

= 1.5.9 =

* IMPORTANT: This is one of the last releases with support for PHP 5.6. Please ensure your site runs the PHP version recommended by WordPress (7.4 or later)
* Bugfix: Fixes PHP error notice from Logger.php if no settings exists
* All email actions has a From-field to allow overriding the default WordPress email from address

= 1.5.8 =

* IMPORTANT: This is one of the last releases with support for PHP 5.6. Please ensure your site runs PHP 7.0 or later
* Bugfix: Order total filter always returned false
* UI: Add button to remove filters provided by an uninstalled plugin

= 1.5.7 =

* Email actions (plan, HTML and WooCommerce) now supports sending to all users with a WordPress role
* New parameters for outputting WooCommerce order details, summary and meta data in either plain text or HTML
* UX improvement: Actions can be reordered
* UX improvement: Objects created dynamically (new user, new post) can be accessed in the parameter editor and in subsequent actions
* Fixed issues with parameter grouping
* Actions create user/post emits "newuser" and "newpost" to the object context to allow manipulating using subsequent actions.

= 1.5.6 =

* Improves webhook action to let users choose between single or multiple line input (input vs textarea)
* Improved onboarding experience

= 1.5.5 =

* Adds re-evaluate option to filters. Allows re-evaluation of a filter when the workflow is run delayed
* New action: Create user - crete new WordPress users, set role, password, email etc.
* New action: Cancel delayed workflow
* Improved handling for using parameters in action. Parameter editor now has an Insert button that inserts the placeholder in the last text field
* Updates create coupon parameter to allow setting a whitelist of allowed billing email addresses
* Bugfix: Parameter filtering doesn't confuse mc-webhook with webhook
* Bugfix: Missing resources fo

= 1.5.4 =

* Emergency fix. PHP class for Webhook action went missing - Many thanks to Mr Jb VERCRUYSSE for reporting this issue promptly

= 1.5.3 =

* Adds filters and parameters for working with total order count and sum for current customer (WooCommerce)
* Internal: Adds class to recognize country regardless of language
* Bugfix: Clean up inconsistent naming for webhooks

= 1.5.2 =

* Adds filter and parameter for working with remote the IP of the request initiator (ip filtering for webhooks and forms)
* WooCommerce order triggers now also provides a user object representing the WordPress user who placed the order
* Adds filter IsGuest to determine if an order is placed by a logged in user or a guest

= 1.5.1 =

* Adds support for "Run once". Optionally ensures that a specific workflow can only be executed once for the same object
* Adds an option to the Change Role action so that it's possible to change role the current user
* Adds option to set a users role to "No role"
* Fixes a bug with extra characters some dropdown values

= 1.5.0 =

* Adds Webhook trigger for receiving data via a webhook call to WordPress
* Adds filters and parameters for working with data from webhook trigger
* Adds Webhook action for structured remote API calls
* Adds content type selector and additional authenticatiom methods to REST API action
* Adds option parameter to get values from the WordPress options table (date format, blog name, etc.)
* Adds filters and parameters for currently logged in user
* Adds filters and parameters for WooCommerce order creation method (admin, checkout or rest-api)
* Adds filter to identify order notes as system notes or order status changed notes
* Adds JSONPath parameter parsing for some filters and parameters

= 1.4.3 =

* Adds Order note (WooCommerce) trigger to specifically trigger on order notes
* Adds parameters for Order Shipping method and Shipping zone (WooCommerce)
* Adds filters for Order Shipping method and Shipping zone (WooCommerce)
* Adds a Comment content filter
* Adds new parameter modifier for date parameters to add/sub time from the returned date

= 1.4.2 =

* Emergency fix for Vue error in Workflow editor

= 1.4.1 =

* Adds order status filter
* Fixes issues with sending WooCommerce emails from certain triggers (User login) that runs before WordPress is fully initialized
* Fixes issue with filters in the workflow listing
* UI improvements to make it easier to find relevant documentation

= 1.4.0 =

* Adds new actions for sending HTML emails.
* Adds parameter type for creating confirmation links
* Adds trigger type for detecting clicks on confirmation links
* Workflows can be grouped into categories for easier navigation of related workflows
* Fix: Change custom field action did not take formulas into account for numerical values
* Fix: Issue with no or wrong user being associated with a workflow triggered by a post trigger

= 1.3.3 =

* Adds new action: Create post
* Adds new filters: Referer post id and Referer url
* Adds new parameters: Referer post id and referer url
* Internal - better handling of names and captions for some filters and parameters
* Internal - Adds post types and statuses to shared state object in Vue

= 1.3.2 =

* Adds filters for customer order count (completed and total)
* Adds parameter for generating WooCommerce coupons
* Fixes formatting issues with generated placeholders for parameters

= 1.3.1 =

* Fixes issue with js/css not loading when creating new workflow
* Bump the "tested to" version to 5.4

= 1.3.0 =

* Feature: Experimental support for delayed workflows. Ability to delay an action to minutes, hours or days after the event occured.
* Improved phone number formatting for using phone numbers with remote APIs
* Fixes bug with assigning correct owner/author when a workflow is triggered by a post
* Adds promotional page for addons available at Wundermatics.com
* Fixes issue where the WunderAutomation scripts would load on all pages in the admin area

= 1.2.0 =

* Adds reply-to, cc and bcc parameters to Send email action
* Adds line break handling to email action
* Adds support for Advanced Custom Fields as filter and parameters
* Adds Billing Email, Billing Company and Shipping Company as filters for WooCommerce Orders

= 1.1.0 =

* Adds parameter "Date" to WooCommerce orders to return order created date
* Adds parameter "PaidDate" to WooCommerce orders to return order paid date
* Adds default date format to the settings screen.
* Adds option to format phone number parameter in e.164 standard (compact with international prefix, suitable for API usage in ie Twilio)
* Adds option to URL encode any parameter
* Where applicable, adds option to return parameter value as either key or label. I.e returning "France" instead of "FR"
* Adds extra rows to parameter test page to display both key value and label for parameters that supports being returned as a label

= 1.0.1 =

* Adds data type field to custom fields action
* Adds trigger Saved (posts)
* Adds filter Initiator
* Fixes issue with field name when custom field parameter
* Fixes issue with parameter test page on non english sites
