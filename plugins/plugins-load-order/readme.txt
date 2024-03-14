=== Plugins Load Order ===
Contributors: chespir
Tags: plugins, manage, admin
Requires at least: 2.5
Tested up to: 6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to change the order in which plugins will be loaded by Wordpress

== Description ==

This plugin allows you to change the order in which plugins will be loaded by Wordpress.

It shows you a simple drag-and-drop interface to set this order.

If you are plugin developer, I encourage you to use actions and hooks so that you will not need this plugin at all, but if you are managing your own blog and you face to the problem a plugin depends on other one, then this is the plugin you need.

== Installation ==

1. Upload 'plugins-load-order' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on Settings > Plugins Load Order to get the admin plugins page
4. Set the order you want and save options

== Screenshots ==

1. This is the simple drag-and-drop interface

== Changelog ==

= 1.2.2 =
* Prevent PHP warning when activate plugin and go settings first time before saving. Thanks to @beee

= 1.2.1 =
* Default language changed from Spanish to English.

= 1.2 =
* Fix WPML language compatibility.

= 1.1 =
* Improved layout design.

= 1.0 =
* Initial version.