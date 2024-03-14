=== Wordpress Hebrew Date ===
Contributors: hatul
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4HTHWS3LGDDPJ
Tags: hebrew, date, jewish
Requires at least: 2.0
Tested up to: 6.1
Stable tag: 2.0.4.1

Convert dates in wordpress to Hebrew dates.

== Description ==

The plugin preview Hebrew date in date of post and date of comments.
The hebrew date format able to change in options page of the plugin.

= Shortcode =
You can add the shortcode `[today_hebdate]` in posts or pages for show the hebrew date of today.

= Widget =
You can add widget for show the hebrew date of today.

== Installation ==

1. Upload `wordpress-hebrew-date` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

3. (Optional) if you want to add the hebrew date of today than you can add the shortcode `[today_hebdate]` or the widget.

== Screenshots ==

1. Date in post
2. Date in comment
3. Widget

== Changelog ==
= 2.0.4 =
* Update date_sunset to date_sunset
= 2.0.1 =
* Add option to show the date by site language (good for multi language sites)
= 2.0 =
* Rewrite code
* Add filters
= 1.3.7 =
* Add filter `hebdate`
= 1.3.6.2 =
* Replace http to https links (thanks KosherJava)
* Replace batchgeo (not working) to LatLong.net
= 1.3.5 =
* Format date in the widget by the settings plugin
= 1.3.4 =
* fix today_hebDate function and add return_today_hebDate function.
= 1.3.3 =
* Improve shortcode
= 1.3.2 =
* Fix more leap year bug
= 1.3.1 =
* Fix leap year bug
= 1.3 =
* Add hebrew date of today widget
* Add shortcode `[today_hebdate]`
* Fix some bugs
= 1.2.1 =
* Fix bug that caused display the current date instead of post date.
* Replace old PHP functions.
= 1.2 =
* Organizing code and use Wordpress 3.0 functions.
* Add donate button.
= 1.1.3 =
* Add support points to splitting date.
= 1.1 =
* Add option of change date by the sunset.
* Add Hebrew months in English.
* Fix some bugs related to Adar and leap year.
= 1.0 =
* Add options page.
* Control on date format.
* Improved Gregorian date display.
* Add translation to Hebrew and English.
= 0.3.2 =
* Fix bug in drafts window in admin panel.
* Improved display hebrew date of today.
= 0.3.1 =
* Fix bug that prevent display of the hebrew date at post date.
= 0.3 =
* Add support in function "the_time".
* Fix bug in comments manager.
= 0.2 =
* The Gregorian date by setting of theme.
* Display hebrew date of today.

