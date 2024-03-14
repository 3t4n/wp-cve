=== When Last Login - Export User Records ===
Contributors: andrewza, yoohooplugins, travislima
Tags: last login, when last login, user login records, export user login records
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4GC4JEZH7KSKL
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Export your user's login records into a CSV or JSON file in seconds.

== Description ==
An add-on to When Last Login which allows you to export all user login records into a CSV or JSON file. Get meaningful data at the click of a button.

= Features =
* Export user login records into a CSV file
* Export user login records into a JSON file
* Export user records with most recent login time into a CSV file
* Export user records with most recent login time into a JSON file

== Installation ==
1. Upload the plugin files to the '/wp-content/plugins' directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to the 'When Last Login' menu item and click on the 'Export User Records' settings tab.
4. Click on the relevant file type you'd like your data to be exported to.

== Frequently Asked Questions ==

= Do I require any other plugins for this add-on to work? =
Yes, you will need (When Last Login)[https://wordpress.org/plugins/when-last-login/] installed and activated on your website in order for this plugin to work.

= What does When Last Login - Export User Records do exactly? =
When Last Login - Export User Records will export your user's login records into a CSV or JSON file.

= Is this plugin free? =
Yes, When Last Login - User Email is a free plugin for WordPress. 

== Screenshots ==
1. When Last Login - Export User Records settings page

== Upgrade Notice ==
None applicable

== Changelog ==

= 1.1 - 2022-12-07 =
* SECURITY: Added nonces for exporting data and sanitized data.
* ENHANCEMENT: Make plugin strings localizable.
* REFACTOR: Applied WPCS to make code more legible.
* BUG FIX: Fixed a fatal error with PHP 8+ where date functions were expecting an integer.

= 1.0.1 - 2017-07-31 =
* Added in the ability to export user records
* Separated exports between login records and user records
* Export tab has been added to the 'When Last Login' menu

= 1.0 =
* First Release