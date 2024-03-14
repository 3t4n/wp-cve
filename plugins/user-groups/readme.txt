=== User Groups ===
Contributors: katzwebdesign, katzwebservices
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=zackkatz%40gmail%2ecom&item_name=User%20Groups&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: users, groups, user group, user
Requires at least: 2.8
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin does one thing, and does it well: create groups and organize your users by that group.

== Description ==

### Group Your Users

* Add descriptions to user groups
* Assign colors to user groups
* View all users in a group
* Add users to more than one group at a time

The User Groups plugin does not modify user capabilities, limit access, or anything like that. This plugin does one thing, and does it well: create groups and organize your users by that group.

Works beautifully with the <a href="http://wordpress.org/extend/plugins/rich-text-tags/">Rich Text Tags</a> plugin for adding WYSIWYG descriptions to the User Groups.

== Installation ==

1. Upload the User Groups plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create User Groups by going to Users > Groups
1. Add users to an User Group by visiting their profile page and selecting checkboxes of the groups you'd like to add them to

== Frequently Asked Questions ==

= Does this plugin limit user access to (anything) =

Nope.

== Screenshots ==

1. The User Groups screen is just like the Categories screen, but with a color selector!
2. View all users in a group, as well as the group description
3. Edit the Group once it is created
4. How the user groups look in the User Profile screen

== Changelog ==

= 1.3 & 1.3.1 on April 11, 2016 =

* Fixed: User Groups lost when users update own profile
* Fixed: Compatibility with WordPress 4.2 - 4.5
* Added: Translation strings
* Fixed: Additional sanitization
* Fixed: Colorpicker in Edit User Group screen

= 1.2.2 on June 11, 2015 =
* Fixed: PHP notices
* Fixed: When a User Group color wasn't set, the group would not display in the Users table column
* Fixed: Potential security issue with `add_query_arg()` - [learn more](https://blog.sucuri.net/2015/04/security-advisory-xss-vulnerability-affecting-multiple-wordpress-plugins.html) (please update!)

= 1.2.1 =
* Implemented fix for a few bugs reported on WordPress Codex
* Fixed: User Groups with no users are viewable

= 1.1.1 =
* Fixed minor PHP message <a href="http://wordpress.org/support/topic/fastcgi-error-2">as reported here</a>

= 1.1 =
* Added bulk editing of user groups

= 1.0 =
* Liftoff!

== Upgrade Notice ==

= 1.1.1 =
* Fixed minor PHP message <a href="http://wordpress.org/support/topic/fastcgi-error-2">as reported here</a>

= 1.1 =
* Added bulk editing of user groups

= 1.0 =
Liftoff!