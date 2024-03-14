=== Gravity Forms Export Entries ===  
Contributors: theverylastperson
Tags: Gravity Forms, export, entries, spreadsheet
Requires at least: 
Tested up to: 5.9.0
Stable tag: 1.9.3
Bitbucket Plugin URI: https://bitbucket.org/Optimized-Marketing/gforms-export-entries/
Bitbucket Branch:     master  

== Description ==

Export all Gravity Forms entries from selected dates to an excel spreadsheet.

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the 

Plugin from Plugins page.  

== Frequently Asked Questions ==

* How do I set up PlugIn?

You can find setup instruction here: http://optireto.com/how-to-export-all-gravity-form-entries

* Do I need to install Gravity Forms?

Yes, this PlugIn is an add-on for Gravity Forms and provides you with the ability to export all form entries to a spreadsheet.

* Are you associated with Gravity Forms?

No, we are an Agency that uses Gravity Forms on all of our client sites (we're big fans!).
We needed a way to create custom entry exports for Gravity Forms. The end result was this PlugIn.
Releasing it for everyone to use is one small way we're supporting the WordPress community.
You can find our other tools we use to manage our agency at https://OptiReto.com

== Changelog ==
= 1.9.3 =
* FIX/NEW - Actually added the code for the  ability to exclude spam entries from export - thanks again @jelena!
= 1.9.2 =
* FIX/NEW - Added ability to exclude spam entries from export - thanks @jelena!
= 1.9.1 =
* FIX - Added timestamp to filename to make it unique at time of generation
= 1.9.0 =
* NEW - Added ability to display the confirmation page - thanks @jelena!
= 1.8.4 =
* Adding form title lookup - Fixes Issue #8
* Minor CSS edit to make label stack on top of field in the admin settings
* Moved field labels to match new layout
* Corrected $form_id setting in export to match new settings
* Revised settings update to prevent old forms from sliding in
* Set $form_id to $form, which stores the new id
* Merge branch 'id_settings'
= 1.8.3 =
* Minor CSS edit to make label stack on top of field in the admin settings
= 1.8.2 =
* Moved field labels to match new layout
= 1.8.1 =
* Corrected $form_id setting in export to match new settings
* Revised settings update to prevent old forms from sliding in
= 1.8.0 =
* Merged id_settings branch - this sets the Form IDs to an index key and not form name
= 1.7.4 =
* Additional CSS adjustments to admin screen - reformat mutliple select items
= 1.7.3 =
* Adjusted CSS to format admin page to better fit in new gravity forms admin screen
= 1.7.2 =
* Added site address to report
= 1.7.1 =
* Corrected issue that caused unscheduled secondary exports from triggering if not set
= 1.7.0 =
* Added banner and link to extended instructions
= 1.6.4 =
* Added new monthly slug to 'cleaning' functions
= 1.6.3 =
* Gave GFEE monthly it's own unique slug (gfee_monthly) to prevent conflicts with anything else that might register a monthly task.
= 1.6.2 =
* Corrected monthly schedule so it runs on the same day each month
= 1.6.1 =
* Quick populate feature now handles non-custom fields
= 1.6.0 =
* Added quick populate icons next to the fields on the setup screen
* Clicking these will populate the value of the current field to all fields with the same name on all the other forms.
= 1.5.8 =
* Updated tooltip to clarify date range is not a form setting
* Updated content type for exports
= 1.5.7 =
* Fallback for removing schedule for cleaning files added. If isset fails then schedule is removed.
= 1.5.6 =
* When setting or removing export schedules the checkboxes automatically check/uncheck and are highlighted green for 6 seconds to let users know the schedule will be removed or added as selected.
= 1.5.5 =
* Added filename check to uploads root to prevent removing .xls files not created by this PlugIn
= 1.5.4 =
* Added filename check to prevent removing .xls files not created by this PlugIn
= 1.5.3 =
* Settings are now saved by creating form via js to reduce the number of sumitted fields to a minimum
= 1.5.2 =
* Added ability to remove files stored in legacy uploads root
* Changed gfee_cleaning_days_schedule() to only look for *.xls files
= 1.5.1 =
* Corrected issue changing scheduling for removing old export files
* Added form id as a field that can beexported
= 1.5.0 =
* Added option to schedule removing old export files
* FIX: resolved issue where entering a column number then changing back to 0 creates a bunch of alerts saying 0 has already been used as a column number
= 1.4.6 =
* FIX: Corrected issue that caused password reset link to be hidden in password reset email
= 1.4.5 =
* FIX: Added code to insure field order numbers are integers and added catch to while loop to prevent endless loop
= 1.4.4 =
* Revised method used to alert user to setup export entries when new form is saved
= 1.4.3 =
* Revised export process to prevent needing column offset
= 1.4.2 =
* JS Revision to correct issues with 1.4.1 so code can be used in multiple plugins
= 1.4.1 =
* Changed js from 1.4.0 so it now asks when form is saved, not when made active
= 1.4.0 =
* When making a form active it now asks if you would like to set it up for export entries
= 1.3.4 =
* Monthly schedule now pulls by month and not last 30 days.
* Added basic FAQ to readme
= 1.3.3 =
* Plugin Information Cleanup
= 1.3.2 =
* Added js to alert user if the same column number has been entered more than once.
= 1.3.1 =
* Adjusting PlugIn name and contributors
= 1.3.0 =
* Added filter to clean file name before saving
* First version prepped for WordPress.org repository
= 1.2.92 =
* If Gravity Forms is deactivated then deactivate this PlugIn
= 1.2.91 =
* Changed class ExcelWriter to use __construct() to keep with current PHP standards
* Added check to see if Gravity Forms is installed
= 1.2.9 =
* Added custom log function to help debug actions
= 1.2.8 =
* FIX: When a scheduled export runs the export name is no longer sent as an array
= 1.2.7 =
* Added advanced setting to create an offset count for custom fields
= 1.2.6 =
* Modified code to make sure no saved field has the same name as a custom field
* This prevents bogus duplicate fields from showing up in export screen and causing user confusion
= 1.2.5 =
* Fixed issue preventing multiple exports from being imported.
= 1.2.4 =
* Corrected array issues preventing scheduled export from working properly
= 1.2.3 =
* Added support for skipping multiple columns next to each other
= 1.2.2 =
* Added support for skipping columns
= 1.2.1 =
* Fixed array error on activation
= 1.2.0 =
* Added ability to save multiple exports, each with their own name and format.
= 1.1.0 =
* Added link from Dashboard->Forms->Import/Export to "Advanced Export Entries" page
= 1.0.8 =
* Added ability to allow users with capability gravityforms_edit_settings to edit settings
= 1.0.7 =
* Added +1 to custom field account to adjust for recent changes in the "gforms Analytics Capture" PlugIn
= 1.0.6 =
* Made change to CalendarPopup.js to account for day of week not lining up
= 1.0.5 =
* Bug fix: prevent form titles with double spaces from causing their export settings to not save
* Bug fix: prevent forms with an apostrophe in title from causing their export settings to not save
= 1.0.4 =
* Added wp_strip_all_tags to title filter
* Adjusted timing when saving settings to make sure header row is populated correctly after saving settings
= 1.0.3 =
* Added function to clean form title to resolves issues with invalid character names
= 1.0.2 =
* Trimmed $form['title'] to remove blank spaces to prevent issue causing settings to not save correctly
= 1.0.1 =
* Added 2 filter hooks to allow adding custom fields to be exported.
= 1.0.0 =
* First release in repository