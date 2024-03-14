=== Open in New Window Plugin ===
Tags: outbound, links, open, new window
Text Domain: open-in-new-window-plugin
Requires at least: 3.0
Tested up to: 6.4.2
Contributors: Keith Graham
Stable tag: 2.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Opens external links in a new window, keeping your blog page in the browser so you don't lose surfers to another site.

== Description ==
The Open in New Window Plugin Plugin uses JavaScript to target external links to a new browser window, leaving your blog page open. 
Since it uses javascript, it targets more links than using a plugin that filter pages and rewrite the link. The plugin will find many links generated in scripts or pasted into posts and comments.
Due to the limitations of  javascript it will not be able target links in iframes such as Adsense and some other affiliate links.

== Installation ==
1. Download the plugin.
2. Upload the plugin to your wp-content/plugins directory.
3. Activate the plugin.

== Changelog ==

= 2.9 =
* Tested under WordPress 6.4.2

= 2.8 =
* Tested under WordPress 5.7

= 2.7 =
* Tested under WordPress 5.4

= 2.6 =
* Fixed a bug in the javascript loader url.

= 2.5 =
* Changed comments to /*...*/ so that javascript compressors will not get confused. 
* Fixed bug in the way options are stored.
* Fixed bug in the way control js was loaded.

= 2.4 =
* Major rewrite. 
* Uses script enqueue methods so WP loads the scripts. 
* Fixed some problems with the scripts. THey should compress correctly now.
* set up so translations might work (but I doubt it).

= 2.3 =
* Added security check.
* broke up the program to reduce load overhead
* added the first settings option to this plugin. Some files types, when found in local site, can be forced to open in a new window. These include: .pdf, .mp3, .jpg, .gif, .tiff, .png, .doc, .rtf, .docx, .xls, .wmv, .mov, .avi, .zip, .rar, .7z and .arc. Check off the option box in the settings. On by default.

= 2.2 =
Fixed bug in javascript that allowed some types to open in new window.

= 2.1 =
Added support to load certain file types in a new window. Disabled in wp-admin screens to prevent reported issues with the admin toolbar.

= 2.0 =
Now loading the script as a js file in order to cache the hits and speed page loads.

== Upgrade Notice ==

= 2.3 =
Added features and improved security

= 2.2 =
Bug fix in javascript

= 2.1 =
Added support to load certain file types in a new window.

= 2.0 =
Uses a static js file that can be cached to speed up loads.


== Support ==
This plugin is free and I expect nothing in return. If you wish to support my programming, buy my book: 
<a href="https://www.facebook.com/BlogsEye/">Error Message Eyes: A Programmer's Guide to the Digital Soul</a>
