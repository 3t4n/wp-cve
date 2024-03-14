=== WordPress Tweaks ===
Contributors: Lamansky
Tags: options, settings, tweaks, admin, comments, pings, media, post, posts, pages, disable, admin bar, dashboard, search engines, tags, footer, links, JavaScript, new window, captions, flash, tab, jpg, jpeg, images, revisions
Requires at least: 3.1
Tested up to: 3.9.1
Stable tag: 2.2

Adds many must-have options for tweaking comments, posts, media, multisite, the administration back-end, and more.

== Description ==

WordPress Tweaks is a powerful yet simple plugin to help you push the boundaries of WordPress by fine-tuning its many aspects. This multi-purpose plugin specializes in simple toggle-on/off changes and dropdown selections. It adds many useful settings pertaining to comments, posts, media, the administration back-end, and much more. Some tweaks can even replace plugins that you may already have installed.

The WordPress Tweaks plugin adds a "Tweaks" page to your "Settings" menu. Each individual tweak can be enabled/disabled at will using a checkbox or dropdown. An instant search bar makes it a snap to find the tweak you're looking for.

The tweaks are as follows:

* Admin
	* Disable the admin bar on the front-end
	* Disable the Dashboard
	* Disable tag autocomplete
	* Disable WordPress's admin footer text/links
* Comments and Pings
	* Disable self-pinging
	* Dofollow comment author links
	* Dofollow comment body links
	* Open external comment links in new windows
* Media
	* Default media inserter tab (options: From Computer, From URL, Gallery, Media Library)
	* JPEG Quality
* Multisite
	* Let site admins edit the "Update Services" list
* Posts
	* Disable post revisions
	* Force excerpts on archives
	* Open external post links in new windows
	* Add a "Continue reading" link to excerpts
* Updates
	* Core update auto-installation (options: disabled, minor only, major and minor)

[Download it now](http://downloads.wordpress.org/plugin/wordpress-tweaks.zip) and begin tweaking!

**Supported Languages:** English, Spanish, and Polish

== Screenshots ==

1. The admin page of WordPress Tweaks 2.0
2. Use the instant search bar to quickly find the tweak you're looking for

== Changelog ==

= Version 2.2 (May 23, 2014) =
* Feature: Added the "Core update auto-installation" tweak
* New Translation: Spanish (es_ES) by Andrew Kurtis of [Web Hosting Hub](http://webhostinghub.com)
* New Translation: Polish (pl_PL) by [Michał Hunger](http://blog.13mhz.kapa.pl/)
* Improvement: Made aesthetic and usability enhancements to the settings page
* Improvement: A dialog appears if you leave the settings page after making changes without saving
* Improvement: When the "Disable the admin bar" tweak is activated, the "Toolbar" checkbox option is now removed from all user profile pages
* Removed the "Automatically scroll to the post editor" tweak, since it has little effect under the current WordPress admin interface

= Version 2.1 (June 4, 2012) =
* Feature: Added the "Let site admins edit the Update Services list" tweak (visible on multisite setups only)
* Bugfix: Fixed CSS conflict that caused search results highlighting to spill over into adjacent text characters
* Improvement: Updated the text for the "Disable the admin bar" tweak to reflect that, as of WordPress 3.2, the tweak only takes effect on the front-end
* Removed the "Disable the Search Engines Blocked notice" tweak (no longer needed in WP 3.2+)
* Removed the "Disable the Flash uploader" tweak (no longer needed in WP 3.3+)

= Version 2.0 (May 27, 2011) =
* Feature: Added an instant search bar to the plugin admin page
* Feature: Added the "Disable the admin bar" tweak
* Feature: Added the "Disable the 'Search Engines Blocked' notice" tweak
* Feature: Added the "Disable WordPress's admin footer text/links" tweak
* Feature: Added the "Default media inserter tab" tweak
* Feature: Added the "JPEG Quality" tweak
* Bugfix: Increased browser compatibility for the "Automatically scroll to the post editor" tweak
* Bugfix: The "Disable the Dashboard" tweak now works on the latest versions of WordPress
* Bugfix: The "Disable tag autocomplete" tweak now works on the latest versions of WordPress
* Improvement: Replaced deprecated ereg functions with preg functions
* Improvement: The "Open external links in new windows" tweaks now use 5 lines of jQuery each instead of 30+ lines of custom JavaScript
* Improvement: Now uses the new script/style queueing API introduced in WP 2.6
* Improvement: Now uses the new options.php API introduced in WP 2.8
* Improvement: Plugin now fails gracefully when installed on an old, unsupported version of WordPress
* Removed the "Remove the width restraint on administration pages" tweak (no longer needed in WP 2.7+)
* Removed the "Show comments in reverse order" tweak (no longer needed in WP 2.7+)
* Removed 5 nofollow tweaks (Google no longer supports nofollow-based PageRank sculpting as of 2008)
* Removed the "Disable directory listing for my plugins folder" tweak (no longer needed in WP 2.8+)
* Removed the "Hide WordPress's version number from my theme and feeds" tweak
* Removed the "Add code references to favicon.ico" tweak (not needed for modern browsers)
* Removed the "Remove white space from pages list" tweak
* Removed the "WordPress 2.3 Legacy Fixes"
* Compatibility: WordPress Tweaks now requires WordPress 3.1 or above

= Version 1.8 (June 30, 2008) =
* Added the "Automatically scroll to the post editor" tweak
* Removed unnecessary JavaScript HTTP calls
* Removed unused code
* Added a POT file to the plugin distribution
* Fixed a WordPress 2.6 compatibility bug
* Other minor enhancements

= Version 1.7 (June 16, 2008) =
* Added internationalization support to all strings
* Added nonce support to the administration interface to guard against unauthorized changes
* Other minor enhancements and fixes

= Version 1.6 (June 10, 2008) =
* Added the "Disable the Dashboard" tweak
* Added the "Disable directory listing for my plugins folder" tweak
* Added information on version requirements for some tweaks

= Version 1.5 (June 6, 2008) =
* Added 9 new tweaks!
* Overhauled the administration interface
* Added helpful descriptions to many tweaks
* The footer counter is now controlled by a checkbox instead of a constant
* Other minor enhancements

= Version 1.0 (April 28, 2008) =
* Should now be compatible with the new plugin auto-updater in WordPress 2.5.

= Version 0.1.2 (March 14, 2008) =
* Resolved a minor, aesthetic IE CSS rendering issue.
* Other minor changes.

= Version 0.1.1 (February 28, 2008) =
* Added the "Remove 'nofollow' from comment author links" tweak.
* Added a donation link in the administration panel.
* Made an admin change for WordPress 2.5 conformity.
* Fixed bugs that prevented the "Open external comment links in new windows" tweak from working.
* Fixed a bug that caused the tag cloud to be removed from the Widgets administration when the "Only show the tag cloud widget on the homepage" tweak was enabled.
* Fixed a duplicate JavaScript function problem.

= Version 0.1 (February 27, 2008) =
* Initial release
