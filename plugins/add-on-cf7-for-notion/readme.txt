=== CF7 to Notion ===
Author: WP connect
Author URI: https://wpconnect.co/
Contributors: wpconnectco, staurand, bryanparmentelot
Tags: wpconnect, notion, contactform7, api, forms
Requires at least: 5.7
Tested up to: 6.4.0
Requires PHP: 7.0
Stable tag: 1.2.0
License: GPLv2 or later

Connect the popular WordPress plugin Contact Form 7 to Notion. This add-on enables you to integrate Contact Form 7 forms so that when users submit a form entry, the entries get directly added to Notion. You can link any field type including custom fields and add information to your Notion database.


== Features ==

= Set up the connection with Notion =
* Some simple steps to follow (only once)
* A new Notion tab shows up in your form settings

= Choose the destination database you want =
* Make your Notion page visible to the integration
* For each form, select the database where you want to send data

= Map your Contact Form 7 fields with Notion =
* All major field types are supported
* Select the corresponding database field for each form field

= User-friendly and helpful plugin interface =
* Dropdown lists for instant visualization of mapped fields
* Many tooltips to make things easier


== Installation ==

1. Upload plugin files to your plugins folder

2. Activate the plugin on your WordPress Back Office (Extensions)

3. Go to the Contact Form 7 settings page (under Contact > Integration > Notion)

4. Enter the Notion Internal integration token (available [on this page](https://www.notion.so/my-integrations))

5. Click Save Settings

6. Create your form then go to the tab Notion

7. Follow on-screen instructions for integrating with Notion.


== How does it work? How to use it? ==

1. Create a form with at least an e-mail field (don’t forget the consent field)

2. Go to Notion tab and check the box “Add form submissions to your database” to activate the connection

3. Choose the Notion's database in which the data should be added

4. Map the fields of your Contact Form 7 form with your Notion's columns

5. Click on “Save settings”


== Frequently Asked Questions ==

= What is Notion? =
Claiming 20 million users worldwide, [Notion.so](http://notion.so/) is an all-in-one digital workplace. It combines various collaborative tools for note-taking, task management, project management (around a kanban board) or even storage and exchange of documents.

= Why do I need a Notion account? =
Contact Form 7 Notion Add-On uses Notion’s API to send data. Creating an account on Notion is free. Once logged in your contact, you can create and get the Internal integration token [from this page](https://www.notion.so/my-integrations) (don’t forget to share it with your database).

= Do I have to pay to use the add-on and use Notion? =
Our add-on is completely free.
[Notion.so](http://notion.so/) offers a free plan, called Notion Individual. It targets small teams of up to 6 people. Allowing the creation of an unlimited number of pages and blocks, Notion Individual gives access to the platform's API.
Depending on your needs, several paid subscriptions allow you to unlock these limitations while giving access to more advanced features ([see prices](https://www.notion.so/fr-fr/pricing)).

= Why I don’t see the Notion configuration tab =
Before starting the mapping with your database please make sure you have setted up your integration. To do this, go directly to the Integration tab of Contact Form 7 and enter a valid Notion Internal integration token.

= How are my columns and fields synchronized? =
You don't have to do anything, the synchronization is automatic. Make sure you have created your database and your Notion column names before linking them to your form fields. If you don't see it, wait 1 minute. For performance reasons, Notion columns are cached for one minute.

= Why I don’t see my columns in the selection list =
Only columns that are compatible with the linked field are displayed.
Moreover, some column types are not supported (see Troubleshooting)

= How do I share my integration? =
Integrations don't have access to any pages (or databases) in the workspace at first. A user must share specific pages with an integration in order for those pages to be accessed using the API. This helps keep you and your team's information in Notion secure.
Start from a new or existing page in your workspace. Insert a new database, give it a title. Click on the Share button and use the selector to find your integration by its name, then click Invite. Full infos [here](https://developers.notion.com/docs) (Step 2)

= Which CF7 versions is it compatible with? =
This add-on requires at least version 5.5.3 of Contact Form 7 and has recently been tested successfully up to version 5.6.

= How can I get support? =
If you need some assistance, open a ticket on the [Support](https://wordpress.org/support/plugin/add-on-cf7-for-notion/)


== Screenshots ==

1. Setting up the Notion Integration
2. Configuration of Contact Form 7 and Notion table
3. Map the fields of Contact Form 7 form with Notion's columns


== Changelog ==

= 1.2.0 =
* WordPress 6.4.0 compatibility
* Added: Sending an email to the administrator upon an API error

= 1.1.0 =
* WordPress 6.3 compatibility
* Added: Map with WPForms fields of type file upload
* Added: Warning message for Notion API limit for upload type file

= 1.0.4 =
* WordPress 6.2 compatibility

= 1.0.3 =
* Added: Tab color

= 1.0.2 =
* Added: New tooltips (Databases & Secret Token)
* Added: Setup page shortcut in plugin list
* Added: Admin notices
* Added: WP connect branding
* Changed: Help links
* Changed: Plugin name

= 1.0.1 =
* Added: compatibility with new v5.6 of Contact Form 7
* Changed: Notion integration help link

= 1.0.0 =
* Initial release


== Support ==
If you need support, open a ticket on the [Support](https://wordpress.org/support/plugin/add-on-cf7-for-notion/).


== Troubleshooting ==
Make sure you have created your database and columns in Notion before linking them to your form fields. If you don't see it, wait 1 minute. Your Notion elements are cached for 60 seconds for optimal performance.
**Supported Fields: Title, Text, Number, URL, E-mail, Phone, Select, Multiple Select and Date**