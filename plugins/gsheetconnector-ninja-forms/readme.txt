=== Ninja Forms Google Sheet Connector ===
Contributors: westerndeal, abdullah17, gsheetconnector
Author URI: https://www.gsheetconnector.com/
Tags: Ninja Forms, Ninja Forms Google Sheet, Google Sheet, Ninja Forms Addon, Ninja Forms Google Spreadsheet, Ninja Forms Google Spreadsheet, Google Integration, Ninja Forms Google Sheets Addon, Ninja Forms Google Sheet Integration, Contact Form Google Sheet Integration, Ninja Forms Google API, Ninja Forms API Integration, Ninja Forms Addon, Google Spreadsheet
Tested up to: 6.4.3
Requires at least: 5.6 or higher
Requires PHP: 7.4
Stable tag: 1.2.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin is a bridge between your [WordPress](https://wordpress.org/) **[Ninja Forms](https://wordpress.org/plugins/ninja-forms/) and [Google Sheets](https://www.google.com/sheets/about/).**

When a visitor submits his/her data on your website via a Ninja Forms, upon form submission, such data are also sent to Google Sheets.

[Documentation](https://www.gsheetconnector.com/docs) | [Support](https://www.gsheetconnector.com/support) | [Demo](https://ninjagsheets.gsheetconnector.com/) | 
[Premium Version](https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro?wp-repo)


Get rid of making mistakes while adding the sheet settings or adding the headers [including Merge Tags](https://ninjaforms.com/docs/merge-tags/) to the sheet column. We would be Launching soon the <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro?wp-repo" target="_blank">Ninja Forms Google Sheet Connector Pro</a> version with more automated features.

= Still haven't purchased ? <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro?wp-repo" target="_blank">Ninja Forms Google Spreadsheet Addon</a> =

= Check Live Demo =
Demo URL:&nbsp;<a href="https://ninjagsheets.gsheetconnector.com/" target="_blank">https://ninjagsheets.gsheetconnector.com/</a>

Google Sheet URL to Check submitted Data<br><a href="https://docs.google.com/spreadsheets/d/1ooBdX0cgtk155ww9MmdMTw8kDavIy5J1m76VwSrcTSs/edit#gid=1602937341" target="_blank">https://docs.google.com/spreadsheets/d/1ooBdX0cgtk155ww9MmdMTw8kDavIy5J1m76VwSrcTSs/edit#gid=1289172471</a>

= How to Use this Plugin =

* **Step: 1 - [In Google Sheets](https://sheets.google.com/)**  
➜ Log into your Google Sheets.  
➜ Create a new Sheet and name it or select the existing sheet.
➜ Copy Sheet Name, Sheet ID, Tab Name and Tab ID (Refer Screenshots)

* **Step: 2 - In WordPress Admin**
➜ Navigate to Ninja Forms > Google Sheet > Integration Tab
➜ Authenticate with Google using new "Google Access Code" while clicking on "Get Code"
➜ Make Sure to ALLOW Google Permissions for Google Drive and Google Sheets and then copy the code and paste in Google Access Code field, and Hit Save & Authenticate.
➜ Now Navigate to appropriate Ninja Forms > Edit Forms > Email & Actions and then Click on + icon to add Google Sheet Action and Enter Sheet Name, Sheet ID, Tab Name and Tab ID and Save and Publish.

* **Step: 3 - Arranging Columns in Sheet** 
➜ In the selected Google sheet, enter column names in first row (as a header) as per the Label, Copy and Paste the form field label from the Ninja Forms  to Google Sheet (e.g. "Name", "Email", "Comment or Message", "date" etc).
➜ Lastly Test your Ninja Forms and verify that the data shows up in your Google Sheet.

= 🔥 Videos to help you get started with Ninja Forms Google Sheets Connector =

🚀Ninja Forms Google Sheet Connector Introduction Video

[youtube https://www.youtube.com/watch?v=M1C3PpqKuK0]

= Important Notes = 

➜ You must pay very careful attention to your naming. This plugin will have unpredictable results if names and spellings do not match between your Google Sheets and Ninja Forms settings.

 == Upgrade Notice ==
>Get [Ninja Forms Google Sheet Connector PRO](https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro?wp-repo) addon
It helps to Automate the sheet, without Manual Sheet Configuration

== Installation ==

1. Upload `gsheetconnector-ninja-forms` to the `/wp-content/plugins/` directory, OR `Site Admin > Plugins > New > Search > GSheetConnector Ninja Forms > Install`.  
2. Activate the plugin through the 'Plugins' screen in WordPress.  
3. Use the `Admin Panel > Ninja Forms > Google Sheet > Integration` screen to connect to `Google Sheets` by entering the Access Code. You can get the Access Code by clicking the "Get Code" button. 
Enjoy!


== Screenshots ==

1. Google Sheet Integration without authentication.
2. Permission page if user is already logged-in to there account. 
3. Permission popup-1 after logged-in to your account.
4. Permission popup-2 after logged-in to your account.
5. After successful integration - Displays "Currently Active".
6. Get Sheet and Tab Id from the Google Sheet URL.
7. Add Action Screen for Google Sheets Ninja Forms
8. Google Sheet settings page with input box Sheet Name, Sheet Id, Tab Name, Tab Id.
9. Add Ninja Forms Label name of appropriate fields to the Google Sheet headers.
10. Google Sheet headers with form submitted data.

== Frequently Asked Questions ==

= Filled Form Entries not showing in my Configured Sheet? =

If the Entries never shows in your Sheet then one of these things might be the reason:
Wrong access code or did not allowed permission to Google Drive and Google Sheets(Check debug log under Integration Tab)
Not Entered Correct Sheet Name, Sheet ID or Tab Name, Tab ID
It also happens due to Wrong Column name mapping ( keep in mind that not to use special characters like underscores, double or single code, space etc.)
Please double-check those items and hopefully getting them right will fix the issue.


== Changelog ==

= 1.2.16 = [07/03/2024]
- UI and Add links for support,docs,upgrade to pro.

= 1.2.15 = [12/02/2024]
- Error Solved: Uninstallation of Plugin.

= 1.2.14 = [12/01/2024]
- Supported Html type value in google sheet.

= 1.2.13 = [29/12/2023]
- Fixed Google Sheet URL button display issue in google sheet settings.
- Fixed validate parent plugin exists or not then show alert message display issue.

= 1.2.12 = [12/10/2023]
- Solved deprecated error for NF_Action_NJGheetAction::$name 

= 1.2.11 = [09/10/2023]
- Freemius SDK Version Updated : 2.5.12.
- Redesigned the System status interface.
- Fixed Freemius Activation Issue In MultiSite  Network.
- Permissions Issue Resolved.
- Fixed Conflict Issue With Wpform Gsheet Free.
- Fixed View Of Debug Log.

= 1.2.10 = [02/09/2023]
- Updated Library Version : 2.12.6.
- Fixed PHP deprecated errors.


= 1.2.9 = [11/08/2023]
- Fixed Vulnerability to ensure data security.
- Enhanced UI to offer a more intuitive and visually appealing experience.
- Displayed Pro Featurer to provide advanced capabilities and options.
- Updated System Status.
- Redesigned the integration interface.

= 1.2.8 = [05/Jul/2023]
- Updated Freemius SDK version to 2.5.10

= 1.2.7 = [28/Apr/2023]
- Fixed : Vulnerabilities issue resolved.

= 1.2.6 = [04/Apr/2023]
- Fixed : "Cannot modify header" error issue resolved.
- Fixed : Undefined offset issue resolved.

= 1.2.5 = [04/Mar/2023]
- Added : Remove access permission from google account while deactivating authentication.
- Update : FAQ Update
- Fixed : Fixed issue with Repeatable Fields.

= 1.2.4 = [21/July/2022]
- Compatible Free version to Pro version.
- Compatible plugins with new google integration.

= 1.2.3 = [05/Mar/2022]
- Fixed Freemius error.

= 1.2.2 = [28/Feb/2022]
- Updated Freemius SDK.

= 1.2.1 = [14/Feb/2022]
- Fixed Freemius error.

= 1.2 = [24/Jan/2022]
- Solved permission issues 
- Auth validatation

= 1.1 =
- Displayed connected/authenticated email accounts
- Redirection on Setting page after activation
- Added Freemius
- New-Dashboard Widget and other UI Changes
- New-Allowed Multiple Google Sheet Actions to connect multiple sheets

- 1.0 
- Released Initial version Functionality to send Ninja Forms Entries to Google Sheets