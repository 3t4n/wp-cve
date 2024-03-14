=== Hatom/hentry remover (Fixes errors in Google Webmaster Tools) ===
Contributors: rkjellberg
Tags: remove hentry, fix google webmaster tools errors, hentry errors fixer, hatom fixer, hatom webmaster tools
Requires at least: 3.7
Tested up to: 4.4.2
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin removes the ".hentry" class with a post_class-filter and supports all themes (even the Twenty T*-family) by simply adding the tag again with javascript. This will prevent Google from indexing it as a hentry in "Structured Data" without messing up the layout.

== Installation ==

1. Upload the entire 'no-hentry' folder to the '/wp-content/plugins/' directory or download it from Plugins in WordPress Dashboard.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 1.3 =
*Release Date - 7 mars 2016*

- Added support for all themes (even the Twenty T*-family) by simply adding the tag again with javascript.

= 1.2.1 =
*Release Date - 6 mars 2016*

- First commit :) 

== Frequently Asked Questions ==
= Can this help me to remove errors in Google Webmaster Tools? =
Yes, if the errors is related to the .hentry tag.