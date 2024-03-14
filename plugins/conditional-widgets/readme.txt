=== Conditional Widgets ===
Contributors: MadtownLems, kgraeme
Tags: widgets
Requires at least: 3.0
Tested up to: 6.1.1
Stable tag: trunk

Easily control the display of widgets based on pages or categories

== Description ==

IMPORTANT: This plugin will NOT function after the Widgets screen overhaul that is part of WordPress 5.8 unless you use the Classic Widgets plugin (https://wordpress.org/plugins/classic-widgets/). The plugin will continue to be supported/maintained for those using Classic Widgets, but there are no plans to make Conditional Widgets work once Widgets are Blocks.

This plugin adds a form to each widget on the Widgets panel which allows users to choose which pages and/or categories the widget is either displayed on or hidden from.

For each widget, you can choose criteria to either SHOW or HIDE the widget, based on a number of categories.  The interface is quite intuitive, and requires no knowledge of php or conditional tags.


== Screenshots ==

1. Each widget gets an expandable form at the bottom of it for controlling display options on the home page and for both pages and categories.


== Installation ==

Standard Installation Procedure

1. Upload the `cets-conditional-widgets` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==
= 3.1 =
 * Added message to readme explaining the future of Conditional widgets after WordPress 5.8
= 3 =
 * Improved string translatability (Props @alexclassroom)
= 2.2 =
 * New Features: Hide on Desktop / Hide on Mobile.  (Note that using either of these options will immediately hide the widget when the condition is true, and any other options to 'show' will be ignored.
 * Fixes lots of small bugs related to the display of the widget control form

= 2.1 =
* Major update of code formatting to better align with WordPress style and guidelines - props @cFoellmann
* Support Conditional Widgets toggle JavaScript on the Customize screen

= 2.0.5 =
* Attempted to resolve the strict warnings
* IMPORTANT! If updating in a MultiSite/Network environment, you'll need to visit the dashboard of every site for the plugin to continue to work properly.  If you have a large network, consider using my helper plugin: <a href='https://github.com/MadtownLems/cets-multisite-dashboard-crawler'>https://github.com/MadtownLems/cets-multisite-dashboard-crawler</a>

= 2.0.1 =
* Major Rewrite: modified how settings are stored.

= 1.8 =
* Added an additional checkbox for ALL pages/categories to, once again, make the UI even easier to understand

= 1.7 =
* Added VERY basic string translation support
* Tweaked UI surrounding pages/categories to be easier to understand

= 1.6 =
* Only display the informative debugging text when WP_DEBUG_DISPLAY is true
* More verbose informative debugging text

= 1.5 =
* Fixed a bug surrounding the extra conditional checks on some configurations (Thanks, mmcginnis!)
* Added debugging and informative statements while WP_DEBUG is enabled

= 1.4 =
* Added support for Posts Pages (when using Static Front Page)

= 1.3 =
* Added support for saving options on Widgets that didn't otherwise have options

= 1.2 =
* Added option for Tag Archives (by request)

= 1.1 =
* Added options for Search, 404, Date Archives, and Author Archives

= 1.0.2 =
* Small edit to play nicely with some other plugins and their additional processing

= 1.0.1 =
* Basic bug fixes: resolved some warnings and errors. (Sorry for the inconvenience.)

= 1.0 =
* Initial Release

