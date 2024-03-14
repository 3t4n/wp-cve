=== DayOfWeek Widget ===
Contributors: broadcastwidgets,peachysoftware
Donate link:
Requires at least: 4.0
Tags:day of week, daily, scheduled, schedule, weekly, timed, content
Tested up to: 6.4
Requires PHP: 5.4
Stable tag: 1.7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin provides an easy, lightweight widget to show content based on the day of the week

== Description ==
This plugin provides an easy, lightweight way to show different content based on the day of the week. Can be used as a widget or a shortcode in your posts or pages.

= Features: =
* Additional nested shortcode option to allow full filtering including [embed] codes (and others)
* Allows a day to be shown all the time by adding day= to shortcode ( [showday day="mon"] )
* (NEW) Tomorrow and Yesterday now added as a day option to show coming up or just missed!!
* Now includes the ability to include shortcodes from other plugins. Most requested featured
* is easy to use
* Shows content based on each day of the week. Days can be left empty too.
* Can be used in your Posts or Pages
* Can be used as a Widget
* Tested in Gutenberg
* Please send me your feedback  <a href="http://broadcastwidgets.com/contact/">here</a> for any new features you want to see in next version of this plugin.I will be happy to receive feedback.

= Pro Version =
A Pro version is now available and offers plenty more features.

- Multiple entries - You are no longer limited to just one entry for different days
- Can use a post or page for each day (and can mix and match for the week)
- Premium Support - Will receive higher priority response than this free version.

More details and you can purchase the pro plugin can be [found here.](https://peachysoftware.com/dayofweekpro/).

== Installation ==

To install DayOfWeek , follow these steps:

1.  Download and unzip the plugin
2.  Upload the entire dayofweek/ directory to the /wp-content/plugins/ directory
3.  Activate the plugin through the Plugins menu in WordPress
4.  Day of Week will appear in the sidebar. Click on it.
5.  Set the correct timezone. (If server is on a different timezone!).
6.  Enter any content for each day of the week you want different content. (Can be left blank)
7.  In any post or page enter the shortcode [showday]. It will show that day's content based on the timezone you set. Any days left blank will be ignore on the post/page
8.  You can use this as a Widget. In the Widgets page just add Day Of Week to the area you want it. An (optional) title can be added.
9. 	If you need to use another plugins shortcode, please enable "Use Nested Shortcode". (This feature is new and experimental).

= Have Feedback =
Please send me your feedback  <a href="http://broadcastwidgets.com/contact/">here</a> for any new features you want to see in next version of this plugin.I will be happy to receive feedback.

== Screenshots ==

1. The main entry screen for Day Of Week. Text can be entered and formatted for each day of the week.


== Changelog ==
= 1.7.0 =
* Allows Editor role to use this plugin.

= 1.6.0 =
* Changes in User Interface to prepare for future upgrades.
* Added tomorrow and yesterday as an option for showing next day content.

= 1.4.2 =
* Small bugfix in widget code

= 1.4.1 =
* Bug fix for showing as a widget. New filter option for [embed] and nested shortcodes. See 1.4.0 details

= 1.4.0 =
* Added 'full filter' to nested shortcode options to allow other tags such as [embed] to be shown
* Bug fixes on some variable initialization.
* Changed nested shortcode options to drop down menu, none, allow shortcodes & full filtering.

= 1.3.0 =
* Added day to shortcode to allow a specific day to be shown all the time.

= 1.2.0 =
* Allow Nested Shortcodes. Yeah. To allow nested shortcodes, please check the "Use Nested Shortcodes". This is experimental but works so far.

= 1.1.4 =
* TimeZone fix - Use alternative method of setting local timezone to comply with WordPress Date/Time in 5.3
* Tested for Wordpress 5.3

= 1.1.2 =
* Bugfix - Resolves a PHP7.2 warning appearing. Corrected proper formatting for shortcode call.
* Tested - Tested in Gutenberg editor.

= 1.1.1 =
* Bugfix for Wednesday

= 1.1.0 =
* Added Widget Support

= 1.0.1 =
* Bugfix. Widget support coming soon too.

= 1.0 =
* First stable version released.


== Upgrade Notice ==
= 1.7.0 =
* Allows Editor role to use this plugin.

= 1.6.0 =
* Changes in User Interface to prepare for future upgrades.
* Added tomorrow as an option for showing next day content.

= 1.4.2 =
* Small bugfix in widget code

= 1.4.1 =
* Bug fix for showing as a widget.

= 1.4.0 =
* We have added the ability to pass [embed] tags and changed the way nested shortcodes were handled.
* Plugin is now under our sister company, Peachy Software's umbrella.
* Bug fixes (including some variable initialization.

= 1.3.0 =
* We have added the ability to show a specific day all the time. Useful for restaurant specials and many other ideas too.

= 1.2.0 =
* We have added the ability to handle nested shortcodes. If you want to use a form or another plugins shortcode, the option is there now.

= 1.1.4 =
* Compliance with WordPress 5.3 recommendation on timezone usage to avoid WordPress Core issues.

= 1.0 =
* First stable version released.