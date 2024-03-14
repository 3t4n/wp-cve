=== WPForms Google Sheet Connector ===
Contributors: westerndeal, abdullah17, gsheetconnector
Author URI: https://www.gsheetconnector.com/
Tags: WPFORMS, WPForms Google Sheet, WPForms Addon, Google Sheet, Google Sheet Integration, GSheet, GSheetConnector, GSheet Integration, WPForms GSheet, WPForms GSheet Integration, WPForms Google Sheet Integration, Contact Form Google Sheet Integration, WPForms Google
Requires at least: 5.6
Tested up to: 6.4.3
Requires PHP: 7.2 
Stable tag: 3.4.18
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This is an Addon Plugin of WPForms, A bridge between your [WordPress](https://wordpress.org/) based **[WPForms](https://wordpress.org/plugins/wpforms-lite/) and [Google Sheets](https://www.google.com/sheets/about/).** It helps to record the entries in real-time.

When a visitor submits the form on your website from the frontend using WPForms, upon the form submission, such responses/filled entries are also sent to Google Sheets.
**Compatible with [WPForms Lite](https://wordpress.org/plugins/wpforms-lite/) and [PRO Versions](https://wpforms.com/pricing/)**

[Homepage](https://www.gsheetconnector.com/) | [Documentation](https://www.gsheetconnector.com/docs) | [Support](https://www.gsheetconnector.com/support) | [Demo](https://wpformsdemo.gsheetconnector.com/) | [Premium Version](https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?wp-repo)

= 🤝 PRO FEATURES 🙌 =

Get a rid of making mistakes while adding the sheet settings or adding the headers manually to the sheet column. We have Launched the [WPForms Google Sheet Connector Pro](https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?wp-repo) version with more automated features.
➜ Custom Google API Integration Settings
➜ Allowing to Create a New Sheet from Plugin Settings
➜ Custom Ordering Feature / Manage Fields to Display in Sheet using Enable-Disable / Edit the Fields/ Headers Name to display in Google Sheet.
➜ Using all the [Smart Tags](https://wpforms.com/docs/how-to-use-smart-tags-in-wpforms/) Fields in Headers
➜ Syncronize Existing Entries for WPForms PRO users
➜ Freeze Header Settings
➜ Header Color and Row Odd/Even Colors.
Refer to the features and benefits page for more detailed information on the features of the [WPForms Google Sheet PRO Addon Plugin](https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?wp-repo)


= ⚡️ Check Live Demo =
[Demo URL: WPForms Demo](https://wpformsdemo.gsheetconnector.com/)

[Google Sheet URL to Check submitted Data](https://docs.google.com/spreadsheets/d/1ooBdX0cgtk155ww9MmdMTw8kDavIy5J1m76VwSrcTSs/edit#gid=1289172471)

= ✨ How to Use this Plugin =

* **Step: 1 - [In Google Sheets](https://sheets.google.com/)** 
➜ Log into your Google Account and visit Google Sheets.  
➜ Create a new Sheet and name it.  
➜ Rename or keep default name of the tab on which you want to capture the data. 
➜ Copy Sheet Name, Sheet ID, Tab Name and Tab ID (Refer Screenshots)

* **Step: 2 - In WordPress Admin**
➜ Navigate to WPForms > Google Sheet > Integration Tab.
➜ Authenticate with Google using new "Google Access Code" while clicking on "Get Code"
➜ **Make Sure to ALLOW Google Permissions for Google Drive and Google Sheets and then copy the code and paste in Google Access Code field, and Hit Save & Authenticate.**
➜ Then, Navigate to GoogleSheet Form Settings Tab Selected respective WPForm from the dropdown with which you want to connect with Google Sheet.  
➜ Now copy and paste the Google Sheets sheet name and tab name into respective input fields, and submit.

* **Step: 3 - Arranging Columns in Sheet**
➜ In the selected Google sheet, enter column names in first row (as a header) as per the Label, Copy and Paste the form field label from the WPForms to Google Sheet (e.g. "Name", "Email", "Comment or Message", "date" etc).
➜ Lastly Test your WPForms and verify that the data shows up in your Google Sheet.


= 🔥 Videos to help you get started with WPForms Google Sheets Connector =

🚀WPForms Forms Google Sheet Connector Introduction Video

[youtube https://youtu.be/tgF9GfDjQOw?si=4Ej3vPfpxfDcU7hq]

= Important Notes = 

➜ You must pay very careful attention to your naming. This plugin will have unpredictable results if names and spellings do not match between your Google Sheets and WPForms settings.

👉 [Get WPForms PRO today]((https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?wp-repo))

== Installation ==

1. Upload `gsheetconnector-wpforms` to the `/wp-content/plugins/` directory, OR `Site Admin > Plugins > New > Search > GsheetConnector Wpforms > Install`.  
2. Activate the plugin through the 'Plugins' screen in WordPress.  
3. Use the `Admin Panel > WPForms > Google Sheet > Integration` screen to connect to `Google Sheets` by entering the Access Code. You can get the Access Code by clicking the "Get Code" button. 
Enjoy!

== Screenshots ==

1. Installation step 3 - Google Sheets Connect Page.  
2. Google Sheet Settings for WPForms. 
3. Google Sheet with mail tags.

= How do I get the Google Access Code required in step 3 of Installation? =

* On the `Admin Panel > WPForms > Google Sheet > Integration` page, click the "Get Code" button.
* In a popup Google will ask you to authorize the plugin to connect to your Google Sheets. Authorize it - you may have to log in to your Google account if you aren't already logged in. 
* On the next screen, you should receive the Access Code. Copy the code. 
* Now you can paste this code back on the `Admin Panel > WPForms > Google Sheet > Integration` page.

== Frequently Asked Questions ==

= How to get smart tag like query_vars, user_meta key values to the Sheet?  =

* First, use hook "wpforms_smart_tags" to add the smart tags with the key for which you want the value to the Google Sheet as below example.

   add_filter( "wpforms_smart_tags", "edit_smart_tags" );

   public function edit_smart_tags( $tags ) {
      $tags['query_var key="wpformstest"'] = esc_html__( 'Query String Variable', 'wpforms-lite' );
      return $tags;
   }
* Add same key ( query_var key="wpformstest" ) to the Google Sheet header to get the values.

= entry_id and entry_date smart tag not getting saved to the Google Sheet.

* WPForms lite version don't save form submitted entries to the database. For a reason entry_id is zero(0) and entry_date is null. Hence not being saved to the Google Sheet.


== Changelog ==

= 3.4.18 = (09-03-2024)
Added: Add links for support,docs,upgrade to pro.

= 3.4.17 = (28-12-2023)
Fixed: Connected email account display issue.

= 3.4.16 = (30-10-2023)
Fixed: error displayed for dashboard widget.

= 3.4.15 = (11-10-2023)
Fixed: Solved PHP Warning and compatible with PHP 8.X.

= 3.4.14 = (11-10-2023)
Added : The Google API Client Library has been upgraded to version 2.12.6, incorporating Guzzle HTTP version 7.4.3.
Added : Update enhances reliability and debugging capabilities, ensuring smoother integration with Google services.
Added : Added Language files for French, Spanish, Dutch, and Italian.

= 3.4.13 = (22-09-2023)
Fixed: error displayed for dashboard widget.
Added : For user without Google Drive and Google Sheets permissions Authentication shown alert with message.
UI Changes : Redesigned System Status and Error Log for improved functionality and user experience.

= 3.4.12 = (10-08-2023)
Fixed : Duplicate entry issue resolved.
Added : Transfering old setting to a new feed while updating.
Fixed : css issue.

= 3.4.11 = (07-08-2023)
Added : Moved old settings to new settings.
Fixed : Undefined function resolved.
UI Changes : Showcasing PRO Features in WPForms > GSheetConnector Feeds.

= 3.4.10 = (03-08-2023)
Added : Google sheet Setting added in Edit forms > GSheetConnector
Added : System Status tab added. 

= 3.4.9 = (24-07-2023)
Fixed : revert some changes.

= 3.4.8 = (24-07-2023)
Added : UI changes.

= 3.4.7 = (10-06-2023)
Fixed : smart_tags undefined issue resolved 

= 3.4.6 = (28-04-2023)
Added : Remove access permission from google account while deactivating authentication.
Fixed : Undefined class issue solved.
Fixed : Vulnerabilities issues.

= 3.4.5 = (30-07-2022)
* New Google Integration method implemented using web app.

= 3.4.4 =
* Fixed: Undefined function issue.

= 3.4.3 =
* Fixed: Wrong Class issue resolved.
* Fixed: Undefined function issue.

= 3.4.2 =
* Fixed: Displaying error and not allowing data to get saved to Google Sheet.

= 3.4.1 =
* Fixed: Undefined index issue.

= 3.4 =
* Fixed: redeclared class error.
* Fixed: smart_tags data not getting saved and throwing errors.
* Displayed connected Google account at the Integration Page.
* Added Upgrade to PRO link and list out feature of PRO version.
* Added Logo on dashboard page.

= 3.3 =
* Fixed smart tag not getting saved to Google Sheet.
* Update API Libraries.

= 3.2 =
* Fixed fontend errors on form submission.

= 3.1 =
* Fixed Smart tag errors and issues with new update of WPForms Lite Version 1.6.3.1

= 3.0 =
* Get <a href="https://wpforms.com/docs/how-to-use-smart-tags-in-wpforms/#smart-tags" target="_blank">smart tags</a> value to the sheet. Add tags to header without curly braces.
* Fixed displaying of single quote sign in front of numeric and date values.
* Fixed - conflicts error.
* Fixed - Get Date and Time as per local time instead of universal time.

= 2.0 =
* Upgrade Google APIs Client Library to V4.

= 1.3 =
* Removed admin notifications for limit.
* Fixed not allowing form data to be saved at Google Sheet.
* Fixed WPForms listing showing only five forms at Google Sheet settings tab.

= 1.2.1 =
* Fixed error at dashboard widget.

= 1.2 = 
* Allow user to deactivate authentication
* Added FAQ and System Status Tab
* Fixed displaying of debug statements after form submission.
* Allow user to add default "date" and "time" column to Google Sheet.

= 1.1 =
* UI changes
* Moved tab from WPForms settings to "Google Sheet" tab.

= 1.0 =
* First public release
* Integrated WPForms with Google sheets.
