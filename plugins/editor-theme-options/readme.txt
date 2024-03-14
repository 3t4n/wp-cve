=== Editor Theme Options ===
Contributors: cliffseal
Donate link: http://logoscreative.co
Tags: editor, theme options, appearance, menu, widgets, access
Requires at least: 3.8.1
Tested up to: 3.9
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow editors to access theme options in the Appearance menu.

== Description ==

Simply put: this plugin gives the Editor role access to edit "theme options", which includes:

* Appearance > Widgets
* Appearance > Menus
* Appearance > Customize if they are supported by the current theme
* Appearance > Background
* Appearance > Header

Instead of adding this capability by hand on each client site, just install and activate this plugin—it keeps your `functions.php` file clean, and keeps this type of functionality where it belongs.

The capability is added to the role once (on plugin activation) and removed when the plugin is deactivated.

== Installation ==

1. Upload `editor-theme-options` to the `/wp-content/plugins/` directory, or search for "Editor Theme Options" under Plugins > Add New and click "Install Now".
1. Activate the plugin through the "Plugins" menu.

== Frequently Asked Questions ==

= Why the Editor role, and why this capability? =

Editor access to widgets and menus is, by far, the most requested change I've had of this nature. I've seen similar sentiments.

= Why not use a role editor plugin? =

Those work, too! They're just quite robust, and this is for a specific role and capability.

= Does this work with Multisite? =

It should. Please let me know if it doesn't.

= Can I change the role or capabilities affected? =

There aren't any filters or anything like that. If you know how to `apply_filters`, then you can fork the plugin and have it do what you need.

== Changelog ==

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0 =
Allow editors to access theme options in the Appearance menu.
