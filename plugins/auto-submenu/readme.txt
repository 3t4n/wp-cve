=== Auto Submenu ===
Contributors: jamocreations, anaid
Tags: admin, menu, submenu, subpages, child pages, page, pages, navigation
License: GPL v2 or later
Requires at least: 3.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 0.3.2

WordPress can only automatically add new top-level pages to menus. With Auto Submenu, new child pages will also be automatically added to menus.

== Description ==

By default, the menu system in WordPress only allows you to "Automatically add new top-level pages", and does not offer the same functionality for new child pages. Auto Submenu solves that problem.

When activated, Auto Submenu will do its magic whenever you publish a new child page. If the new page’s parent is placed in a custom menu, then the new page will be added to that menu too (one level below its parent). Should you wish to reorder or delete the new page from the menu, you can simply do so on the admin Menus Screen.

The plugin does not have any settings. Auto Submenu will just work for all custom menus where the “Automatically add new top-level pages” setting is enabled.

== Installation ==

The easiest way to install this plugin is via the admin's Plugins screen.

Alternatively:

1. Upload the `auto-submenu` directory to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. Publish a new child page.
2. And voila, the new child page has been added automatically to your custom navigation menu.

== Changelog ==

= 0.3.2 = 
* Fixed bug: Updating child page moves it to the bottom of the menu

= 0.3.1 = 
* Confirmed compatibility with WordPress 6.4

= 0.3.0 = 
* Confirmed compatibility with WordPress 6.3
* If the page is moved under a different parent, also move it in the menu.

= 0.2 =
* Added support for multiple menus.

= 0.1 =
* First release.
