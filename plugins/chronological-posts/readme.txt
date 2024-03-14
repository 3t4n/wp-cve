=== Chronological Posts ===
Contributors: celloexpressions, annenbergdl
Tags: post order, chronological, reverse post order
Requires at least: 4.4
Tested up to: 6.3
Stable tag: 1.0
Description: Globably reverses the post order to be chronological, so that your site can display a journal or a book in chronological order.
License: GPLv2

== Description ==
Chronological posts reverses the default post order throughout your site to be chronological instead of reverse-chronological. This can be useful for sites displaying historical content, journals, books, etc. where the content is intended to be read chronologically (typically, it works well for any sites where the entire content of the site - every post - is intended to be read sequentially). It is also useful for archived sites, preserving the history of posts in a sequential format, oldest to newest.

== Installation ==
1. Take the easy route and install through the WordPress plugin installer, or,
1. Download the .zip file and upload the unzipped folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Where are the options? =
There are no options. When active, this plugin will automatically display your posts in chronological order on all views, including the main blog index day/month/year archives, category/tag archives, author archives, searches, etc. The admin UI is not changed, only the frontend display.

= Displaying only specific post types in chronological order =
This plugin changes all post types to be chronological, globally. To apply it only to a specific post type, I suggest forking the plugin and adding a simple check for the post type of the query before it sets the query order to `ASC`. This is not a complicated process - the plugin has only 6 lines of code.

== Changelog ==
= 1.0 =
* First publicly available version of the plugin.

== Upgrade Notice ==
= 1.0 =
Initial release.