=== Dynamic User Directory ===

Contributors: Sarah_Dev
Donate link: http://sgcustomwebsolutions.com/wordpress-plugin-development/
Tags: user directory, MemberPress, BuddyPress, member directory, user registration, user meta fields, profile fields, member directory, website directory, directory, user listing, users, members, user profile, user profiles
Requires at least: 3.0.1
Tested up to: 6.4.3
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Powerful and feature-rich user directory based on user profile meta fields.

== Description ==

This lightweight yet powerful and feature-rich plugin creates a user directory displaying the pre-existing user meta information you specify. It can show avatars, social icons, mailing address, email address, website, phone, or any other user meta information you wish. It is also fully compatible with BuddyPress, S2Member, and Cimy User Extra Fields plugins.


= Current Features =

The best thing about Dynamic User Directory is the high degree of control you have over the content, formatting, and style. This allows you to create a highly customized directory and integrate it seamlessly into your WordPress theme. The intuitive backend settings interface is designed to help you get your directory up and running quickly. Features include: 

* Compatible with BuddyPress Extended Profile, S2Member Custom Fields, Cimy User Extra Fields, and many other membership plugins
* Multisite compatible for sites that centrally manage the plugin's data
* Sort by user last name or user display name 
* Specify which user meta fields to display (up to 10)
* Hide users with specified user roles
* Include or exclude specific users
* Optionally hyperlink the user name and avatar to their WP author page or BuddyPress profile page
* Enjoy a fully responsive display for smaller screen sizes
* Optionally show a search box to quickly locate a user
* Optionally show pagination to reduce page load times
* Search by any user meta field with the Meta Fields Search add-on
* Create multiple directory instances with the Multiple Directories add-on
* Hide the directory until a search is run with the Hide Directory Before Search add-on
* Show directory listings in a table format with the Horizontal Layout add-on 
* Sort by any custom meta field (instead of just last name) with the Custom Sort Field add-on
* Exclude users based on a custom meta field such as an "Opt out of Directory" option with the Exclude User Filter add-on
* Export full directory or just search results to CSV file with the Export add-on
* Show/hide WordPress avatars
* Show custom avatars where the img URL is stored in a meta field with the Custom Avatar add-on
* Set avatar style (circle, rounded edges, or standard) and size
* Show/hide listing border
* Set listing border style, color, length, and thickness
* Control font size of all text displayed
* Set the display order of each field
* Control space between alphabet letter links
* Control space between each directory listing
* Choose between showing all users or filtering by selected alphabet letter
* Hyperlink almost any user meta field
* Choose from a variety of field display formats, including phone number, comma delimited lists, & dates
* Display social media link icons (choose from two different icon styles)
* Display address fields as a formatted mailing address
* Display directory totals
* Check out upcoming features [here](https://sgcustomwebsolutions.com/planned-features/)

= Add-Ons =

There are is a growing library of powerful Dynamic User Directory add-ons available [here](https://sgcustomwebsolutions.com/wordpress-plugin-development/) to enhance and extend your directory. 

= Your Feedback is Valuable! =

If this plugin benefits your website, please take a moment to say thanks by leaving a positive rating and/or review. Did you find a bug? Let me know and I'll fix it ASAP. Have suggestions for improvement? Don't hesitate to email me with your thoughts. Thanks so much! 


== Installation ==

1. Copy the whole dynamic-user-directory subdir into your plugin directory or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Dynamic User Directory screen to configure the plugin


== Frequently Asked Questions ==

The complete DUD FAQ can be found [here](https://sgcustomwebsolutions.com/dynamic-user-directory-faq/)


== Troubleshooting ==

The DUD troubleshooting guide can be found [here](https://sgcustomwebsolutions.com/dud-troubleshooting/)

== Screenshots ==

1. Directory style example #1
2. Directory style example #2
3. Directory style example #3
4. Directory style example #4
5. Actual Site #1
6. Actual Site #2
7. Actual Site #3
8. 1 of 5: Plugin Settings Page
9. 2 of 5: Plugin Settings Page
10. 3 of 5: Plugin Settings Page
11. 4 of 5: Plugin Settings Page
12. 5 of 5: Plugin Settings Page


== Changelog ==

= 1.8 =
- Released 3/11/24
- Successfully tested against WordPress 6.4.3.

= 1.7 =
- Released 9/19/23
- Successfully tested against WordPress 6.3.1
- Updated: Changed social icon from Twitter to X.

= 1.6.9 =
- Released 4/20/23
- Successfully tested against WordPress 6.2
- Fixed: Cleaned up code to remove PHP warning notices.

= 1.6.8 =
- Released 3/01/23
- Successfully tested against WordPress 6.1.1
- New Feature: Added the option to display the user roles assigned to each member in the directory listings. Option is located under the "Meta Field Settings" section. If you do not see the new setting option after running the update, try clearing your browser's cache.
- Removed admin notice pertaining to DUD 1.6.5

= 1.6.7 =
- Released 9/20/22
- Successfully tested against WordPress 6.0.2
- Please clear your browser cache before refreshing the DUD settings page to see the new field formatting options listed below.
- New feature: Added several new field format options for multiselect boxes, multiple checkboxes, and single checkboxes.
- New feature: Added field format option to show Email Address in plain text with anti spam measures (injects a hidden HTML comment in the address to discourage scraper bots).
- New Feature: Added new field format option to show a person's current age based on a birthdate field.
- New Feature: Added new social field icons: YouTube, TikTok, Podcast.
- Enhancement: A search on last name or display name will now be a "contains" search instead of a "starts with" search. 
- Enhancement: Moved the social icons line down by 4px in the vertical directory to match the line spacing in each listing. 

= 1.6.6 =
- Released 4/04/22
- Corrected a bug in 1.6.5 that was generating an error when saving the DUD settings page on a Windows-hosted server.

= 1.6.5 =
- Released 4/01/22
- Successfully tested against WordPress 5.9.2
- New Feature: Added option to auto-scroll down to the top of the directory when page refreshes due to navigating the directory (option is in the "Main Directory Settings" section). Use if your dirctory is at the middle or bottom of the page.
- New Feature: Added option to open the website link in a new tab (option is in the "Meta Fields Settings" section).
- New Feature: Added option to hide the field label when the field is empty (option is in the "Listing Display Settings" section)
- Fixed: Website link was broken when displaying a label for the website field
- Notice: Please open your DUD settings page and click "Save options" without changing any settings to complete the update. 

= 1.6.4 =
- Released 1/11/22
- Successfully tested against WordPress 5.8.3
- Fixed: When field format is "Single Checkbox => Show Label Only," empty string arrays were being displayed. This resulted in gibberish being shown when the user did not check the box. Now the field value will only be shown if there is a value to display.
- Enhancement: Added two new format options: "Single Checkbox => Show Label and Value" and "Single Checkbox => Show Value Only" 

= 1.6.3 =
- Released 5/7/21
- New Feature: Added infrastructure for the new Custom Avatar add-on
- New Feature: Added the ability to choose whether to show WordPress email address as hyperlink or plain text

= 1.6.2 =
- Released 3/30/21
- Fixed: Hyperlinks (email and other urls) were not clickable in the directory listing after updating to DUD 1.6.1.

= 1.6.1 =
- Released 3/22/21
- Successfully tested against WordPress 5.7
- Fixed: The meta field formatting was displaying a php warning message in certain scenarios. 
- Fixed: The avatar link was not clickable at smaller screen sizes for vertical directories. 
- Fixed: The directory totals were not displayed correctly for all directory instances when the Multiple Directories add-on is used in conjunction with the Exclude User Filter add-on.
- Fixed: When switching from the dynamically populated search dropdown to the search input text box, the previous search value was not being cleared out.
- Fixed: When the avatar size is larger than 96px, the avatar was overlapping the listing border
- Fixed: When BuddyPress and S2Member are both installed and activated, DUD was not displaying S2Member fields.
- New Feature: Added the new "dud_modify_social_fld_icons" filter that allows for custom coding to add new social media icons.
- New Feature: Social media icon links are now opened in a new window
- New Feature: You can now show the user's date registered(autogenerated by wordpress and stored in the wp_users table). The new checkbox is located in the "Meta Field Settings" section.
- New Feature: You may optionally enter a label for the wordpress profile email address, website, and date registered fields. This is also located in the "Meta Field Settings" section.

= 1.6.0 =
- Released 1/20/21
- Successfully tested against WordPress 5.6
- New Feature: Added code infrastructure for the new Directory Export add-on
- Fixed: The basic last name search results total was not displaying the correct number when shown at the bottom of the directory
- Fixed: Hyperlink field formatting did not account for a URL that doesn't contain "http://" This resulted in the formatted link pointing back to the host website rather than the linked site.

= 1.5.9 =
- Released 11/20/20
- Successfully tested against WordPress 5.5.3
- New Feature: Added a new "Letter Spacing" dropdown in the "Listing Display Settings" section. Choose between 0px, 1px, or 2px spacing between letters in the listing display.
- New Feature: Added two new formatting options: 1) Multiple Value List => Bulleted (Hide MP Hyphens) 2) Multiple Value List => Comma Delimited (Hide MP Hyphens). These will allow you to hide the hyphens inserted by the MemberPress plugin for multi value lists.
- Fixed: Corrected an issue with the Multiple Directories add-on where DUD was not loading the correct directory instance after instance #49. 

= 1.5.8 =
- Released 6/17/20
- Successfully tested against WordPress 5.4.2
- New Feature: Added a new MemberPress checkbox on the DUD settings page for the Exclude User Filter add-on: "Show users if they have at least one subscription that is NOT selected for hiding." This lets you show users with multiple subscriptions if at least one of those subscriptions should be shown.

= 1.5.7 =
- Released 5/29/20
- Successfully tested against WordPress 5.4.1
- Fixed: Eliminated a warning message that appeared in some scenarios when using the custom sort add-on and the directory was not using sort category links
- Enhancement: Made 50 additional directory instances available in the Multiple Directories add-on for a total of 100 instances.
- New Feature: Added the "Image" option to the Meta Field Formatting options dropdown. This should be a filepath which DUD will then render via the IMG tag. This tag will include the css class hook "dud_img" which may be used to style the image. 
- New Feature: Added a "Performance Improvement" checkbox to the Exclude User Filter add-on settings. This will speed up page load time for directories with a high volume (1000+) of users. 

= 1.5.6 =
- Released 2/18/20
- Fixed: A debug statement was mistakenly left in the code and has been removed.

= 1.5.5 =
- Released 2/17/20
- New Feature: A new "Error Message Settings" subsection has been added under the "Listing Display Settings" section. This allows you to configure each 
of the DUD error messages that may be shown to the viewer.  
- New Feature: Added a new "Mobile Phone Hyperlink (Australian)" field format option. This is displayed as +61 XXXX XXX XXX
- Fixed: The "Phone Number (Australian)" format option was displaying in a mobile phone format instead of the main landline format. 
This has been corrected to display as (XX) XXXX XXXX.
- Fixed: The hyperlink for the Twitter social media icon was prepending an extra "https://twitter.com/" in the Twitter URL when the full URL is stored in the meta field instead of just the twitter handle.
This has been corrected. 
- Added new settings for the Meta Fields Search add-on. If you have this add-on installed, it is recommended that you clear your browser cache before viewing the DUD 
plugin settings page.

= 1.5.4 = 
- Released 11/26/19
- Successfully tested against WordPress 5.3
- New Feature: Added a "Show/Hide User Name" option under the "Listing Display Settings" section of the plugin settings page. This applies to DUD and all of its add-ons, and replaces the option by the same name previously shown under the Custom Sort Field add-on settings.
- Fixed: Renamed function "plugin_action_links" to "dud_plugin_action_links" to avoid conflicts with other plugins
- Fixed: Renamed function "endswith" to "dud_endswith" to avoid conflicts with other plugins
- Enhancement: Modified the code for including/excluding user roles to accommodate a multi-site setup

= 1.5.3 = 
- Released 9/23/19
- Fixed: some sites were getting a warning message "array_multisort(): array sizes are inconsistent." The code has been corrected to prevent this issue.

= 1.5.2 =
- Released 9/13/19
- New Feature: Directory listings are now subsorted by first name (only applicable if showing the last name in the directory). The Meta Field Search add-on has also been updated to subsort results by first name.
- New Field Formatting Feature: Hide MemberPress Hyphens
- New Field Formatting Feature: Australian phone number format
- New Field Formatting Feature: Mobile Phone hyperlink
- Upgraded to FontAwesome 5.0 icons (please be sure to clear your browser's cache before viewing the DUD settings page)
- Fixed: Alphabet link directory was defaulting to wrong letter on sites using MySQL 8.0. This was due to a new MySQL syntax change that affected the DUD alphabet links query. 

= 1.5.1 =
- Released 6/26/19
- New Feature: Added new admin settings for the Exclude User Filter add-on that is now available.
- New Feature: Modified code in core.php to accommodate the new Exclude User Filter add-on.
- Fixed (for sites with the Multiple Directories add-on): after updating the settings for a loaded directory instance, the page was refreshing to the original directory instance instead of the loaded instance.
- Fixed: Changed the way Cimy and BuddyPress table name constants are defined to eliminate a PHP warning notice

= 1.5.0 =
- Released 5/14/19
- Successfully tested against WordPress 5.2
- New Feature: Made the plugin (and all add-ons) multi-site compatible for sites that centrally manage the plugin's data.
- New Feature: Modified admin settings to allow the Multiple Dirs add-on to generate up to 50 directory instances.  

= 1.4.9 =
- Released 4/4/19
- Successfully tested against WordPress 5.1.1
- New Feature: Added new admin settings for the new "General Search" feature of the Meta Fields Search add-on.
- New Feature: Added a new admin setting that allows you to disable/enable Alpha Links Scroll for a given directory instance. This is located in the "Alphabet and Pagination Links Settings" section.
- Enhancement: Added a CSS hook for each individual line for a vertical directory listing in the format "dud_line_x" where "x" is the line number.

= 1.4.8 =
- Released 2/11/19
- Successfully tested against WordPress 5.0.3
- Fixed: Dates stored in the format yyyymmdd were mistakenly being treated as Unix timestamps.
- Fixed: Text fields with a "0" value were not being printed due to the way the PHP empty() library function works. 
- New Feature: Added the ability to create a directory that "includes" only the specified user roles.
- New Feature: Added CSS hooks around each field, label, and line to facilitate custom styling.
- New Feature: Added the ability to display total number of users and/or total number of search results. The new settings are under the "Directory Totals" section.

= 1.4.7 =
- Released 12/13/18
- Fixed: Eliminated several PHP undefined index warning notices that appear when wp_debug is set to true
- Fixed: Elimated a PHP null value warning notice for the "in_array" function call in some scenarios when wp_debug is set to true 
- Fixed: The directory was returning all results if only spaces were entered in the search box. This has been corrected to show the error "Please enter a valid search value."
- Modified code related to the basic last name search 
- Cleaned up and reorganized sections of code related to DUD add-on integration 
- Corrected several issues related to the Custom Sort Field add-on infrastructure

= 1.4.6 =
- Released 12/6/18
- Fixed: Eliminated a php statement that was causing a plugin activation error on some sites.
- Successfully tested against WordPress 5.0

= 1.4.5 =
- Released 12/5/18
- Fixed: Deleted an unused variable reference to dynamic_ud_cimy_installed, which was producing a PHP warning notice.
- Fixed: Updated code references to the count() function to eliminate "parameter must be an array or object that implements countable" error for sites running PHP 7.2.
- New Feature: Added support for Unix timestamps when formatting a meta field as a date. 
- New Feature: Added new field format options on the settings page: "Email" and "Multi-Line Text Box".
- Added infrastucture for Custom Sort add-on
- Updated code that interacts with the Multiple Directories add-on to accommodate the new 10 directory instances maximum.

= 1.4.4 =
- Released 8/15/18
- Successfully tested against WP 4.9.8
- New Feature: Pagination has been added and may be configured under the new "Alphabet and Pagination Link Settings" section. This affects three DUD add-ons: Alpha Links Scroll, Meta Fields Search, and Horizontal Layout. These add-ons must be updated to the latest versions for pagination to work properly when using them.
- New Feature: Ability to change the selected alphabet letter link color. This may be configured under the new "Alphabet and Pagination Link Settings" section.
- Enhancement: Ability to link to a user's BuddyPress profile page as opposed to the BP member activity page.
- Fixed: Corrected problem with some themes skewing the avatar when the avatar display size is set in DUD.
- Fixed: Corrected problem where "undefined index" warning notices were being displayed for var_1 and var_2 when wp_debug is turned on.
- Fixed: Changed the default "Last Name" search box width from 45% to 350px to eliminate the possibility of the field being too long in some themes.
- Reorganized SQL code and added other infrastucture in preparation for the Custom Sort Field add-on

= 1.4.3 =
- Released 6/20/18
- Fixed: Corrected the problem where fields with multiple checkboxes stored as key-value pairs were not displaying in the directory. The problem was reported by several sites using the MemberPress plugin.
- Enhancement: Added new format options to the "Format Meta Field As" drop down on the settings page: 
1) Multiple checkboxes => Show label only
2) Single checkbox => Show label only
3) Several Date/time field format options

= 1.4.2 =
- Released 4/24/18
- Successfully test against WP 4.9.5
- Fixed: Eliminated an "undefined index" warning notice that appeared on the DUD Settings page for some users for the ud_table_cell_padding
and ud_show_table_stripes fields of the horizontal directory when wp_debug is set to "true".
- Enhancement: Expanded the dud_after_load_letters filter parameter list for greater flexibility.
- New Feature: Added the new DUD setting "Format Meta Field As" dropdown with options to format the field as a hyperlink (new tab or same window), muliple value list (comma delimited or bulleted), or phone number.

= 1.4.1 =
- Released 2/28/18
- Fixed: Corrected the problem with the Multiple Directories add-on where you couldn't add, delete, or modify dirctory instances on the settings page using the Safari browser.
- Fixed: Corrected the problem on some sites where user profile pics were being hidden for smaller screen sizes on the vertical directory. 

= 1.4.0 =
- Released 2/7/18
- Successfully test against WP 4.9.4
- Fixed: changed the sql for loading the "user include/exclude" listbox on the settings page when there are 1000+ users, to prevent the page from hanging.
- Fixed: eliminated the "undefined index" warning notices appearing on some sites for the new Social meta fields when wp_debug is set to "true".
- Enhancement: updated the users include/exclude and user roles exclude listboxes to multi-selectable dropdowns with search capability for ease of use.
- Enhancement: added a "country" field to the Address meta fields section.

= 1.3.9 =
- Released 1/22/18
- Successfully tested against WP 4.9.2
- Fixed: Adjusted the new dud_modify_social_flds filter to send all necessary parameters.
- Fixed: Removed the <BR> that pushes the value below the label for meta fields containing arrays with only one item.

= 1.3.8 =
- Released 1/7/18
- Successfully tested against WP 4.9.1
- New Feature: Added a Social Meta Fields section that will format your social media links as a row of icons.
- New Feature: Added three new DUD filters: dud_set_user_email, dud_set_user_email_display, and dud_modify_social_flds
- Fixed: When accessing the S2Member meta field name that holds all custom fields, the "wp_" prefix was hard coded. This has been changed to pull the prefix dynamically from the config file in case it has been changed. 
- Fixed: The DUD settings page was calling the deprecated function "screen_icon()," which generates an error notice when WP Debug is turned on. This call has been removed.

= 1.3.7 =
- Released 11/12/17
- New Feature: Added four new DUD filters: dud_modify_letters, dud_format_key_val_array, dud_srch_fld_placeholder_txt, and dud_modify_address_flds
- New Feature: Added one new add-on filter: dud_hide_dir_before_srch
- New Feature: Added the ability to control the avatar size.
- New Feature: Added new letter divider options: Letter Only, Letter with Bottom Border, and Letter with Top and Bottom Border
- Fixed: The CSS for the directory search box was shrinking the box's height in some themes. This has been corrected.
- Internal code reorganization to streamline certain actions

= 1.3.6 =
- Released 8/29/17
- Enhancement: Redesigned and reorganized the admin settings page for improved aesthetics, readability, and ease of use.
- Fixed: When text with an apostrophe is entered on the BuddyPress profile, a slash was being shown in the directory next to the apostrophe. The text is now shown correctly without the extra slash.

= 1.3.5 =
- Fixed: When user roles with a space in the name are selected for hiding, DUD did not hide those roles. It will now hide all selected roles properly.
- Enhancement: Added two new filters, dud_search_err and dud_no_users_msg, so that developers can customize the plugin error messages shown to the viewer
- Multiple Directories code cleanup: Internal reorganization to handle loading a selected directory instance more efficiently in core.php 

= 1.3.4 =
- Internal code tweak that allows developers to show only the search box and hide the directory unless a search is run.
- Added two filters, dud_set_avatar_link and dud_set_user_profile_link, so that developers can manually set the links to the user profile/author page if needed.  

= 1.3.3 =
- New Feature: DUD is now fully compatible with BuddyPress Extended Profile fields
- New Feature: DUD is now fully compatible with S2Member Custom fields

= 1.3.2 =
- Code clean-up: properly initialized all variables to eliminate the PHP warning notices that were being shown for this plugin when DEBUG = true in the wp_config.php file.

= 1.3.1 =
- Successfully tested against WP 4.8
- Fixed: Letter divider was showing up on the Single Page Directory even when "No letter divider" was selected.
- New Feature: You can now link the user name and avatar to their BuddyPress profile page in addition to the WP Author Page. 

= 1.3.0 =
- Fixed: Alpha links were not always properly created when the site uses a custom permalink structure, resulting in a 404 error.

= 1.2.9 =
- Fixed: When the Meta Fields Search add-on is installed, and an invalid search value is entered, a PHP notice "Warning: Missing argument 2 for dud_build_srch_form_custom()" appears at the top of the page.

= 1.2.8 =
- IMPORTANT: If you have the Meta Fields Search or Alpha Links Scroll add-ons, you should see an update available for each of these on the plugins page. If you do not see these updates, contact me and I will resolve the issue. These should be run in tandem with the Dynamic User Directory update to 1.2.8. 
- Enhancement: Added new code to accomodate the new Multiple Directories add-on.
- Fixed: when showing a dividing border and a letter divider on a single page directory, a dividing border was being displayed just before the letter divider of the single page directory.
- Tweak: set the height of the default user search box to 40px.

= 1.2.7 =
- New feature: Added the ability to hyperlink any meta field.
- Enhancement: Added new code to accomodate the new Meta Fields Search add-on.
- Internal code reorganization on the admin settings page. 

= 1.2.6 =
- Enhancement: Added new code to accomodate the new Meta Fields Search add-on.
- Fixed: Search box width was too long. Set new width to 45%.

= 1.2.5 =
- Fixed: Corrected a missing </pre> statement when the debug mode is turned on.

= 1.2.4 =
- Fixed: Corrected a null error warning: "Warning: in_array() expects parameter 2 to be array, null given" which may occur for those who do not have the Cimy plugin.

= 1.2.3 =
- Fixed: Code was generating incorrect Letter Link URLs for certain intranet website confirgurations and for the WordPress "Plain" permalink setting. It will now generate the links correctly. 
- Enhancement: Added code to accommodate the new Meta Fields Search add-on.

= 1.2.2 =
- Fixed: User meta fields that contained arrays would not display properly (e.g. multiple checkbox or radio button values stored in an array). It will now show a list of array items vertically, with one item per line.
- Code enhancement: now storing all settings page options as an array in a single options setting. This will improve performance since every "get_option" call requires a database read.
- New Feature: You can now choose to show Author Page links for all users rather than only for those with posts. This accomodates those who have a custom author.php page that should be shown regardless of the post count.

= 1.2.1 =
- Fixed: the code variable "$this" was causing fatal error in php 7.1. Changed variable name to correct problem.

= 1.2.0 =
- New Feature: Added Name Display Format on the settings page that will allow you to display name as "First Last" or "Last, First." 
- Enhancement: Expanded the width of the key names listing and sorted it alphabetically for ease of use.
- Enhancement: Added link to the Dynamic User Directory add-ons page. 

= 1.1.9 =
- Fixed: Admin settings page did not set a default value for the letter divider font and fill colors, 
resulting in an error message if you submitted the page without choosing those colors.
- Changed: Removed the Cimy User Extra Fields notification from the settings page for those who do not have that plugin loaded.

= 1.1.8 =
- Fixed: internal change in the id field of the letter dividers.

= 1.1.7 =
- Enhancement: Added five filter hooks to allow developers to extend this plugin
- Fixed: The city/state/zip portion of the address field was not showing if there was no state meta field. It will now show any portion of the city/state/zip address fields that is present. 
- Fixed: Search box was case sensitive, so that you could not search using all lowercase letters. You can now search using upper, lower, or mixed case.

= 1.1.6 =
- New Feature: Added "Show search box" checkbox on the settings page that will show a search box at the top of the directory. You may search by user last name or display name, depending on the sort field. 
- Fixed: A message incorrectly stating that there are "no users in the directory" was being displayed when viewing the directory with the following settings: 1) the "Single Page Directory" option was selected, 2) The Sort Field was set to "Display Name," and 3) users were selected for exclusion. 

= 1.1.5 =
- Code successfully tested on WordPress 4.7

= 1.1.4 =
- New Feature: Added "Directory Type" dropdown on the settings page. You may select the "all users on one page" option to display the entire directory on one screen. 
- Code enhancement: Minified all CSS files for faster load time.

= 1.1.3 =
- Internal change to code generating alpha links to eliminate potential display issues
- New Feature: Added "Debug Mode" setting that will display a set of debug statements for Admins *ONLY* when turned on. This will help me debug site-specific issues more quickly. 

= 1.1.2 =
- New Feature: Added a "link to author page" checkbox on the settings page that will hyperlink the user name and avatar to the user's WP Author Page.
- Code cleanup and reorganization 

= 1.1.1 =
- Successfully tested on WordPress 4.6 
- New Feature: Added 5 new meta fields for a total of 10 available meta fields (not including address fields).
- New Feature: Added the User Meta Fields dropdown on the settings page so you can select the exact number of fields you need.
- New Feature: Added the Address Fields checkbox so you can hide that section if you do not need it.

= 1.1.0 =
- New Feature: "Space between listings" setting added for greater formatting control
- Fixed: Directory was not displaying results when using the include/exclude or hide user roles feature and sorting by display name
- Fixed: Directory would not work if the default WordPress table name prefix had been changed (thanks, Jaya P!)
- Fixed: Responsive display at very small screen sizes was not properly formatting the avatars

= 1.0.4 = 
- Fixed: Spacing issue when a directory listing showed an avatar next to three or less lines of text.
- Fixed: An extra underline was appearing in the empty space next to each letter link for themes that underline hyperlinks.
- Fixed: The city and state of the address fields did not display if there was no zip code.
- New Feature: A fifth meta field was added.
- New Feature: An "Include/Exclude User" setting was added to provide a more customized directory.

= 1.0.3 =
- Security update: Added SQL injection protection.
- Fixed: Display issue related to show/hide user role feature.

= 1.0.2 =
- Added default plugin settings.
- Corrected a spacing issue related to the directory listing display.

= 1.0.1 =
- Updated readme.txt.

= 1.0.0 =
- First public release.