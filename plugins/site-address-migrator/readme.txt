=== Plugin Name ===
Contributors: sourcefound
Donate link: https://membershipworks.com
Tags: siteurl, site address, changing site url, changing site address, update url
Requires at least: 3.0.1
Tested up to: 5.3.2
Stable tag: 2.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Updates urls in pages, posts, comments, descriptions, widgets and options when Site Address (Site URL) is changed.

== Description ==

If you ever change the Site Address under the WordPress settings, you may notice that all the links in your pages and posts may still use the old site url. This plugin plugin migrates all those urls in your Wordpress database when you change the Site Address, without requiring the Search Replace DB script.

Site Address Migrator updates all links matching the old Site Address in:

* Description, content and excerpts of all posts, pages and media (including custom types)
* Post and page option fields
* Comments and comment option fields
* Post category and tag descriptions
* User descriptions
* User website fields
* WordPress and widget option fields

Site Address Migrator will update links inside serialized data correctly. Site Address Migrator will also match links when the protocol does not match the Site Address (eg. the link uses "https://" instead of "http://", or if it is protocol-relative "//"). The protocol used for the original link will be honored (ie. if the link was "https://", it will remain "https://" even if the Site Address is given as "http://").

Warning: Make sure you have access to the new site domain before updating the Site Address, and double check for spelling errors! Entering the wrong site address can prevent you from accessing WordPress again without manually fixing the MySQL database entries. Backup your MySQL database if possible.

This plugin is not designed for, and has not been tested for Multi-Site installs.

== Installation ==

1. Install the plugin via the WordPress.org plugin directory or upload it to your plugins directory.
1. Activate the plugin.
1. Plugin will automatically kick-in when you update the Site Address.
1. Optionally you can perform a manual update by going to Settings > Site Address Updater.

== Frequently Asked Questions ==

= Does this work for changing the WordPress Address (ie. "Home" setting)  =

No, this plugin updates only the Site Address, so it will not migrate links to your assets (media files, etc) when you move your WordPress installation directory.

= Why did my ... widget or plugin stop working? =

This plugin updates only the WordPress database tables. If the widget or plugin relies on data stored outside of these normal WordPress database tables, then they could stop working.

= Does this update GUIDs? =

Updating GUIDs is disabled by default as recommended by WordPress, but you can enable updating GUIDs by un-commenting the corresponding line in the PHP file.

== Changelog ==

= 1.0 =
* Stable release

= 2.0 =
* Added manual update capability