=== Plugin Name ===
Contributors: AMIB
Donate link: 
Tags: private, post, attachment, show, everyone
Requires at least: 3.0
Tested up to: 5.7
Stable tag: trunk

Using this plugin everyone can get access to Private Pages and Attachments knowing their direct link without requiring admin rights.

== Description ==

This plugin provides access to "Private Pages" and "Private Attachments" for everyone using their direct link and removes "Private" prefix from title of this pages.

== Installation ==

1. Upload plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Deactivate plugin any time to disable its effect.

== Frequently Asked Questions ==
= A page of my weblog is set to Private but it is indexed by google. Why? =
There are diffrent situations:
Maybe there is a link to the private page in another public page, in XML site map, another site, etc.
You may add "noindex" meta directive for this kind of pages if required.

== Changelog ==

= 0.2.1 =
* fixed a bug with private attachments in Media Library

= 0.2 =
* support for private attachments added( needs test with diffrent themes and plugins )

= 0.1 =
* first release
