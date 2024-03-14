=== Vision6 Gravity Forms Add-On ===
Contributors: vision6
Donate link: https://www.vision6.com.au/
Tags: vision6, forms, form, contact, gravity forms, gravityforms
Requires at least: 7.4
Tested up to: 6.4.2
Requires PHP: 7.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Grow your list by adding Vision6 forms to your website using Gravity Forms.

== Description ==

Simply download the Vision6 Gravity Forms plugin and create new Vision6 feeds on your existing forms. Capture website visitor details through newsletter subscriptions, enquiry forms, surveys or feedback forms and watch your lists grow.

The WordPress + Vision6 plugin features include:

* Choose which Gravity Forms fields will be sent to your Vision6 list fields
* Conditionally allow for your contacts to be sent to Vision6, based on your Gravity Forms fields.
* Publish Vision6 forms on any page of your WordPress site
* Use WordPress Widgets to manage your forms
* New contact information synced in real time within your Vision6 account

**Please note that you will need a new or existing [Vision6](https://www.vision6.com.au) account and [Gravity Forms](https://www.gravityforms.com) plugin license to select and manage your feeds.**

== Installation ==

#### Using the WordPress Dashboard

1. Navigate to the ‘Add New’ in the plugins dashboard
2. Search for `vision6-gravity-forms`
3. Click the ‘Install Now’ button
4. Click the 'Activate' button, once it is available
5. Open the 'Vision6 Settings' page via the 'Forms' and 'Settings' pages
6. Insert your Vision6 API Key and API Endpoint, available from Vision6 - [Create an API Key for Integrations](https://support.vision6.com.au/hc/en-us/articles/201971540-Create-an-API-Key-for-Integrations)

#### Uploading via the WordPress Dashboard

1. Navigate to the ‘Add New’ in the plugins dashboard
2. Navigate to the ‘Upload’ area
3. Select `vision6-gravity-forms.*.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard
6. Open the 'Vision6 Settings' page via the 'Forms' and 'Settings' pages
6. Insert your Vision6 API Key and API Endpoint, available from Vision6 - [Create an API Key for Integrations](https://support.vision6.com.au/hc/en-us/articles/201971540-Create-an-API-Key-for-Integrations)

#### Using FTP

1. Download `vision6-gravity-forms.*.zip`
2. Extract the `vision6-gravity-forms` directory to your computer
3. Upload the `vision6-gravity-forms` directory to the /wp-content/plugins/ directory
4. Activate the plugin in the Plugin dashboard
5. Open the 'Vision6 Settings' page via the 'Forms' and 'Settings' pages
6. Insert your Vision6 API Key and API Endpoint, available from Vision6 - [Create an API Key for Integrations](https://support.vision6.com.au/hc/en-us/articles/201971540-Create-an-API-Key-for-Integrations)

== Frequently Asked Questions ==

= Where will the form details be sent after the form is submitted? =

Your form details will be sent to Vision6 as contacts.

== Screenshots ==

1. Updating Vision6 Feed Settings


== Changelog ==

= 1.0.0 =
* First publicly available version of Vision6 Gravity Forms released

= 1.0.2 =
* Feature: Introduce the GDPR Consent advanced field
* Feature: Provide the ability to use subscribeContact within the API
* Feature: Upgrade API version to 3.3
* Bug fix: Hide fields if the List is not found within Vision6

= 1.0.3 =
* Feature: Introduce Terms and Condition checks
* Bug fix: Extend the timeout time of API calls
* Bug fix: Fix exception errors on the preview forms

= 1.0.4 =
* Plugin tested with WordPress 5.0

= 1.0.5 =
* Plugin tested with WordPress 5.2
* Enhancement: Add parameters to api logs
* Bug fix: Correct the contact ID detection within api logs

= 1.0.6 =
* Plugin tested with WordPress 5.3

= 1.0.7 =
* Bug fix: Remove the use of the deprecated get_conditional_logic_event()
* Bug fix: Correct the display of the GDPR Consent Summary input

= 1.0.8 =
* Enhancement: Add Vision6 folder filters

= 1.0.9 =
* Bug fix: Convert legacy GDPR Consent fields to Gravity Forms 2.5+ compatible fields
* Enhancement: Force new API endpoints to use the correct hosts and schema
* Plugin tested with WordPress 5.7.2

= 1.0.10 =
* Plugin tested with WordPress 5.8.3

= 1.0.11 =
* Enhancement: Quicker loading of list fields in the UI
* Enhancement: Improved debug logging
* Plugin tested with WordPress 6.0.0

= 1.1.0 =
* Bug fix: GDPR Consent field now has the correct HTML to match checkboxes, and is legacy HTML compatible

= 1.1.1 =
* Bug fix: Request more than 100 list names
* Plugin tested with WordPress 6.2.2

= 1.1.2 =
* Plugin tested with WordPress 6.4.2


== Upgrade Notice ==

= 1.0.0 =
* First publicly available version of Vision6 Gravity Forms released

= 1.0.2 =
* Feature: Introduce the GDPR Consent advanced field
* Feature: Provide the ability to use subscribeContact within the API
* Feature: Upgrade API version to 3.3
* Bug fix: Hide fields if the List is not found within Vision6

= 1.0.3 =
* Feature: Introduce Terms and Condition checks
* Bug fix: Extend the timeout time of API calls
* Bug fix: Fix exception errors on the preview forms

= 1.0.4 =
* Plugin tested with WordPress 5.0

= 1.0.5 =
* Plugin tested with WordPress 5.2
* Enhancement: Add parameters to api logs
* Bug fix: Correct the contact ID detection within api logs

= 1.0.6 =
* Plugin tested with WordPress 5.3

= 1.0.7 =
* Bug fix: Remove the use of the deprecated get_conditional_logic_event()
* Bug fix: Correct the display of the GDPR Consent Summary input

= 1.0.8 =
* Enhancement: Add Vision6 folder filters

= 1.0.9 =
* Bug fix: Convert legacy GDPR Consent fields to Gravity Forms 2.5+ compatible fields
* Enhancement: Force new API endpoints to use the correct hosts and schema
* Plugin tested with WordPress 5.7.2

= 1.0.10 =
* Plugin tested with WordPress 5.8.3

= 1.0.11 =
* Enhancement: Quicker loading of list fields in the UI
* Enhancement: Improved debug logging
* Plugin tested with WordPress 6.0.0

= 1.1.0 =
* Bug fix: GDPR Consent field now has the correct HTML to match checkboxes, and is legacy HTML compatible

= 1.1.1 =
* Bug fix: Request more than 100 list names
* Plugin tested with WordPress 6.2.2

= 1.1.2 =
* Plugin tested with WordPress 6.4.2
