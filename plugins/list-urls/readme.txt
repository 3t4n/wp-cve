=== Plugin Name ===
Contributors: graphems, loumray
Donate link: 
Tags: list, urls, export
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a lightweight plugin which provide a way to get a list of all site urls (permalinks) exported to a CSV. Useful to do site inventory.

== Description ==

This is a lightweight plugin which provide a way to get a list of all site urls (permalinks) exported to a CSV. Useful to do site inventory. Accessible in Tools > List Urls

This is very early version, please send suggestions.

The CSV generated will give you a list of all URLs supporting custom post types, archive and also the taxonomies archives. It currently do not support the pages e.g: /page/1 or the date archive.

Columns in the CSV:

* Title
* URL (permalink)
* Post ID
* Post type name

We provide no warranty.

This plugin require at least PHP 5.5 Don't bother asking question if you are not running PHP 5.5 and higher ;)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Tools > List Urls and then click Download to get the CSV of all your urls


== Changelog ==

= 0.2.1 =
* Fix: change the readme requirements 
* Fix: check Wordpress 4.8 compatibility

= 0.2 =
* Feature: Ability to choose or not if draft or unpublished post should be included
* Tweak: Added the post status column 

= 0.1.1 =
* Fix autoloader issue that was crashing the plugin on activation

= 0.1 =
* Initial release