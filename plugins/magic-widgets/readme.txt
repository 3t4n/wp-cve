=== Magic Widgets ===
Author URI:        http://toscho.de
Plugin URI:        http://toscho.de/2011/wordpress-plugin-magische-widgets/
Contributors:      toscho, dnaber-de
Author:            toscho
Tags:              widget, admin, sidebar
Requires at least: 3.0
Tested up to:      3.9
Stable tag:        2014.03.22
Version:           2014.03.22
License:           GPLv2 or later

Assigns widgets to action hooks.

== Description ==

Defines sidebar areas in `wp_head`, `wp_footer`, `admin_head` and `admin_footer`. You may extend the list.

Additionally, the plugin creates a new widget, called *Unfiltered Text*. Very similar to the regular text widget, but it doesn’t insert any extra markup.

Send me your bug reports and suggestions via my [contact page](http://toscho.de/kontakt/) or per bugtracker at the [public repository at GitHub](https://github.com/toscho/WP-Magic-Widgets).

[Beschreibung auf Deutsch](http://toscho.de/2011/wordpress-plugin-magische-widgets/)

== Installation ==

Upload the directory to your plugin directory.
Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

Version 2014.03.22

 * Fixed labels for visibility options.
 * Use fixed font for the textarea.
 * Added a title to identify the widgets easier. The title will not be printed in sidebars, it is for internal use only.
 * Added @dnaber-de as contributor. Thanks!

Version 2013.06.02

 * Removed secondary plugin.
 * Moved widget class to a separate file, so you can use it without activating the main plugin.
 * Made the plugin translatable.
 * Added German translation.
 * Added extendable visibility options.

Version 1.2

 * Fixed a problem with the version numbers. Updates should now work better.