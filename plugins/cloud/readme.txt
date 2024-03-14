﻿=== WP Cloud ===
Contributors: Milmor
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F2JK36SCXKTE2
Tags: 
Requires at least: 3.3
Tested up to: 6.1
Version: 1.4.3
Stable tag: 1.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Give to Your users a personal space with WP Cloud, the most advanced plugin to turn WordPress into a cloud platform!

== Description ==

WP Cloud allows you to build a cloud platform. With this plugin your users will have their personal space for hosting images or documents, and can easily access and share them.

This plugin generates a folder under your second-level domain (ex. website.com/**cloud**) with one folder for each user (ex. mywebsite.com/**cloud/$user_id/***). Every user can store the file there via a front-end mask displayed using shortcodes, back-end dashboard or the exciting cloud panel found in website.com/cloud.

= What is the cloud panel? =
The Cloud Panel of WP Cloud can be accessed from website.com**/cloud** and give users the opportunity to log-in and manage their file via a friendly mask. For un-logged users, the log-in for will be shown and users can authenticate.

Directly via the cloud panel, logged-in users can:

* see files uploaded, and eventually delete
* see cloud space assigned
* see cloud space used (with percentage too)
* upload a new file

= Users quota =
WP Cloud offers a standard user-quota of 10MB, that is applied by default to every member. You can change this value to give all the users the space you want.
The quota for each user is stored in a meta-field for each profile with the following criteria:

* null = default quota (the meta-field doesn't exist)
* 0 = hosting not allowed
* any other number = n MB hosting

Please note that the user-quota only applies when the user uploads a file. If a user has 90 of 100MB used and you downgrade it to 10MB, files are kept but won't be able to upload files.
There is also an **overload-quota** (default 10%) in percentage that can be set in the settings panel. It works as follows:

* 9 of 10 MB used. Overload 10%. File to upload: 2MB. -> YES
* 9 of 10 MB used. Overload 0%. File to upload: 2MB. -> NO
* 10 of 10MB used. Overload 10%. File to upload: 1MB. -> NO
* 9.99 of 10MB used. Overload 10%. File to upload: 1MB. -> YES

= Shortcodes =
In addition you can create custom pages in your website using the following shortcodes:

* **[cloud]** prints a list of files for the current user
* **[cloud_show id="0"]** prints a list of files of given user id
* **[cloud_upload]** prints a simple upload form that allows the current user to upload a file in his/her directory
* **[cloud_send]** prints a simple upload form that allows the current user to upload a file to another user directory by specifying login_name or email

= Translations =
The plugin is in English and actually doesn't have support for translations. In some days it will, with italian translation included.

= Roadmap =
The plugin is new, and there are some ideas that need some other work:

* Assign quota based on user role
* Assign different quota for each user
* Share files with other members

= Ideas? =
If you think that this plugin could be improved, please let me know.
http://wordpress.org/support/plugin/cloud

> = thank you = 

== Installation ==
1. Upload the entire folder to the '/wp-content/plugins/' directory or install direcly via the WordPress plugin screen
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!

== Screenshots ==

1. Shortcode

2. Back-end dashboard

== Changelog ==

= 1.4.3 20221027 =
* [IMPROVEMENT] Minor changes
* [IMPROVEMENT] Compatibility check for WP 6.1

= 1.4.2 20220625 =
* [IMPROVEMENT] Minor changes
* [IMPROVEMENT] Compatibility check for WP 6

= 1.4.1 20201001 =
* Compatibility fix

= 1.4 17/07/2015 =
* Added send file shortcode (Thanks to Cédric Jézéquel)
* Added show another user files shortcode (Thanks to Cédric Jézéquel)
* Added log system
* Minor improvements 
* Improved Readme and banner
* Out of beta

= 0.3.1 01/03/2015 =
* Minor bugfixes
* Better backend cloud panel
* Added mp3, mp4 as supported file types
* Fixed wrong redirection on file delete

= 0.2.3 18/07/2014 =
* **Fixed** error 404 for file links
* **Added** wp admin bar in */cloud
* **Removed** wp footer in */cloud

= 0.2.2 16/07/2014 =
* Readme.txt changes

= 0.2.1 16/07/2014 =
* **Fixed** some bugs

= 0.2 16/07/2014 =
* First commit