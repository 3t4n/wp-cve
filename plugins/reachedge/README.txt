=== LocaliQ - Tracking Code ===
Contributors: LocaliQ 
Tags: LocaliQ, Lead Conversion, Call Tracking, Form Capture, Form Tracking, Email Capture, Email Tracking
Requires at least: 2.7
Tested up to: 5.9.5
Stable tag: 1.9
License: MIT
License URI: https://opensource.org/licenses/MIT

Adds LocaliQ's tracking code on all pages.

== Description ==

The LocaliQ WordPress plugin adds the tracking code to the WordPress site.  This plugin adds the required javascript code on all pages in order to track analytics and enable other features for the [LocaliQ](https://localiq.com) products and other digital marketing solutions.

The required javascript is loaded from a CDN at cdn.rlets.com/capture_static/mms/mms.js. This file is under continuing development to provide the best performance and stability across all browser and OS combinations.

As new features and functionality are added to LocaliQ Tracking, those updates will be rolled out through the mms.js file, and no updates of this plugin will be required.  

For more information on [LocaliQ](https://localiq.com) visit:

== Installation ==

1. Activate plugin.
2. In the WordPress dashboard, navigate to the 'Settings' menu.
3. Select the 'LOCALiQ Tracking Code' option from the menu.
4. Enter your tracking code ID into the ID field, and click the 'Save Changes' button.

== API Interaction provided by capture_configs js from the CDN ==

1. The capture_configs js loads the customer’s configuration data from LocaliQ
2. Sends analytics data back to LocaliQ for performance metrics.
3. Sends visit & referrer attribution back to LocaliQ for analytics
4. Sends visit, email, and form post data back to LocaliQ to provide lead management.
5. Email links are replaced with contact forms and the form data and sending of email is offloaded to LocaliQ's servers.

== Screenshots ==

1. Modified settings panel with LocaliQ tracking.
2. LocaliQ tracking settings page.

== Changelog ==
= 1.5 =
* Tested with newer Wordpress Version

= 1.4 =
* LOCALiQ branding changes, combined MMS and MIT license

= 0.4.1 =
* Updated README with information about why JavaScript asset is loaded from CDN.

= 0.4.0 =
* Changed the tracking code name for greater uniqueness 
* Use enqueue_script to place capture JS on the page  

= 0.3.0 =
* Restructured plugin for easier distribution

= 0.2.0 =
* Formalize the plugin a bit more
* Added better docs

= 0.1.0 =
* Initial skeleton and start of plugin
