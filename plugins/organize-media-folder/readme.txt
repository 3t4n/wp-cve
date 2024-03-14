=== Organize Media Folder ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags: admin, directories, folders, media, media library
Requires at least: 4.7
Requires PHP: 8.0
Tested up to: 6.5
Stable tag: 1.35
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Organize Media Library by Folders. URL in the content, replace with the new URL.

== Description ==

Organize Media Library by Folders. URL in the content, replace with the new URL.

= Things to manage =
* Organize files into the specified folder.
* Can create folders.
* Can filter searching by folders.
* URL in the content, replace with the new URL.
* Can upload media to the specified folder.
* Can upload media to the specified date time.

= Logs =
* Displays the last 100 logs.

= How it works =
[youtube https://youtu.be/bIGq09DY47c]

== Installation ==

1. Upload `organize-media-folder` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

none

== Screenshots ==

1. Settings
2. Management
3. Filter of Media Library by folders
4. Filter of Insert Media

== Changelog ==

= [1.35] 2024/03/03 =
* Fix - Added nonce when sorting.
* Fix - Changed file operations to WP_Filesystem.

= 1.34 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 1.33 =
Fixed adminbar dropdown.

= 1.32 =
Fixed potential security issue.

= 1.31 =
Fixed a pagination problem when searching for text.

= 1.30 =
Fixed problem of register to term for folder by XAMPP.

= 1.29 =
Fixed initialization problem.
Fixed uninstall.

= 1.28 =
Fixed a problem where terms for media other than images were not registered.

= 1.27 =
Fixed problem of folder filter for gallery block.

= 1.26 =
Added a warning message when a conflicting plugin is activated.

= 1.25 =
Fixed uninstall.

= 1.24 =
Fixed a problem with getting options.

= 1.23 =
Added the ability to select the folders to be displayed in the admin bar.

= 1.22 =
Fixed translation.

= 1.21 =
Change readme.txt

= 1.20 =
The upload folder can now be changed from the admin bar.
Removed unnecessary code.

= 1.16 =
Added an error handler for moving files of the same name between folders.

= 1.15 =
"Character Encodings for server" setting removed.
Fixed a problem with content replacement in threshold images.

= 1.14 =
Fixed a problem with error handling when copying files.

= 1.13 =
Simplified the output log.

= 1.12 =
Supported MAMP(Windows).

= 1.11 =
The display when moving folders has been made more detailed.
Added logging.
Added media ID display.

= 1.10 =
Fixed problem of "Folder" column for Media Library list screen.

= 1.09 =
Added explanation.

= 1.08 =
Fixed a problem with PHP8.

= 1.07 =
Change the "Folder" column in the admin page.
Added a "Folder" column to the Media Library list screen.

= 1.06 =
Fixed the method of updating the term.

= 1.05 =
Fixed a problem with media uploads.

= 1.04 =
Supported XAMPP.

= 1.03 =
Supported XAMPP.

= 1.02 =
Fixed translation.

= 1.01 =
Fixed an issue with the display of the narrowed list regarding gallery insertion when posting.

= 1.00 =
Initial release.

== Upgrade Notice ==

= 1.32 =
Fixed potential security issue.

= 1.00 =
Initial release.
