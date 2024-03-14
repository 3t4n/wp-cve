=== Media File Sizes ===
Contributors: MadtownLems, CETS
Tags: media, files, quota
Requires at least: 2.8.6
Tested up to: 5.7
Authors: Jason LeMahieu
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily see how much disk space (in KB, MB, & a percentage of your quota) each of your uploaded files takes up.

== Description ==

This plugin adds a column to the Media Library page that displays the total space used by each media item.  For images, this includes the original image as well as any other generated sizes.

This plugin is particularly handy for networks where site owners have a limited space quota.

Additionally, sorting your Media Library by file size is actually a great way to identify duplicate media items.

== Screenshots ==

1. A sortable column is added to the Media Library which displays the total space used by this media item (including generated sizes for image files). If a quota is in effect, a color-coded percentage is displayed, based on what percent of the quota this media item is using.


== Installation ==

Standard Installation Procedure

1. Upload `media_file_sizes.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 1.8 =
* Very minor update to check for variable existence which displayed notices with WP_Debug on

= 1.7 =
* Add support for properly sizing audio/video files in 3.6+

= 1.6 =
* Added MB display for files with size 1 MB or greater (Thanks for the idea, carry2web.)

= 1.5 =
* Resolved minor issue with refreshing cached metada on plugin upgrade

= 1.2 =
* Simplified code and did some performance tweaks. Now works on more hosting configurations (in theory)

= 1.1.1 =
* Translate column heading in Media Library

= 1.1 =
* Color coded percentage displays based on how much of quota the item takes up
* Now caches mediafilesize as metadata
* Allows column sorting in WordPress versions 3.1+

= 1.0.2 =
* Fixed a bug in single site installs (thanks for pointing thisout, fwchapman!)

= 1.0.1 =
* Added percent display when media item takes up over 1% of total space

= 1.0 =
* Initial Release