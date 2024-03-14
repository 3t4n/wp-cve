=== WP Date and Time Shortcode ===
Contributors: denra, itinchev
Donate link: https://www.paypal.me/itinchev
Tags: wp, date, time, shortcode, shortcodes, wordpress, wpdts, show, display, post, page, content, plugins, next, today, tomorrow, year, month, day, weekday, name, current, past, future
Requires at least: 4.0
Tested up to: 6.4.2
Requires PHP: 7.4
Stable tag: 2.6.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shortcode to show any current, past, and future date or time. Display this, previous, or next year, month, day, etc.

== Description ==

Shortcode to show any **current, past, and future date or time**. Display this, previous, or next year, month, day, etc. in your posts and pages.

This is probably the last date and time shortcode plugin you will ever need for your websites since it is very rich in features.

*It takes lots of efforts to develop and support a plugin. Please send us your feedback and questions to fix your issue before leaving a bad review.*

Are you satisfied by using this plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/wp-date-and-time-shortcode/reviews/?rate=5#new-post). You can also [donate](https://www.paypal.me/itinchev).

If you need support or more information about this free plugin please read the description and the F.A.Q. section below or write in the [Support Forum](https://wordpress.org/support/plugin/wp-date-and-time-shortcode/).

== How it works? ==

Just put one of these shortcodes in your post or page content and they will work out of the box.

* `[wpdts]` - the main shortcode which works with all attributes; equals to `[wpdts-date-time]` by default;
* `[wpdts-date-time]` - default date and time format from WordPress general settings
* `[wpdts-date]` - default date format from WordPress general settings
* `[wpdts-time]` - default time format from WordPress general settings
* `[wpdts-custom]`- custom format using the PHP [date and time formatting characters](https://www.php.net/manual/en/datetime.format.php)
* `[wpdts-years]`, `[wpdts-year]` - 4-digit year e.g. 1999 or 2134
* `[wpdts-year-short]` - 2-digit year e.g. 99 or 34
* `[wpdts-months]`, `[wpdts-month]` - month as number (1-12)
* `[wpdts-month-name]`- month as name (January-December)
* `[wpdts-month-name-short]` - month as 3-letter name (Jan-Dec)
* `[wpdts-days]`, `[wpdts-day]` - day of month (1-31)
* `[wpdts-hours]`, `[wpdts-hour]` - hours (0-24)
* `[wpdts-minutes]`, `[wpdts-minute]` - minutes (0-60)
* `[wpdts-seconds]`, `[wpdts-second]` - seconds (0-60)
* `[wpdts-timestamp]` - Unix timestamp
* `[wpdts-day-of-year]` - day of the year as number (1-366)
* `[wpdts-days-in-month]` - number of days in the month (28-31)
* `[wpdts-days-in-february]` - number of days in the month of February for the year (28-29)
* `[wpdts-days-in-year]` - number of days in the year (365 or 366)
* `[wpdts-day-of-week]`- day of the week as number (1-7)
* `[wpdts-day-of-week-name]`- day of the week as full name (Monday-Sunday)
* `[wpdts-day-of-week-name-short]` - day of the week as short 3-letter name (Mon-Sun)
* `[wpdts-week-of-year]` - week of year, since first Monday of the year (1-52)
* `[wpdts-am-pm]`- am/pm or AM/PM according to the am_pm attribute ("L" or "U")
* `[wpdts-time-zone]`- the current time-zone for the shortcode result

If you need to use the shortcodes in other places (like titles, navigation menus, footers, widgets, etc.) additional code or plugins may be needed to turn them on in your theme if they are not supported by default. See F.A.Q. section for details.

**EXAMPLES**
If you need to view *real-time examples* of usage or set up custom attributes to show specific date or time please see the page of [WP Date and Time Shortcode](https://denra.com/products/wordpress/plugins/wp-date-and-time-shortcode/) on [Denra.com](https://www.denra.com/).

**ATTRIBUTES**
If you need more detailed features please use the shortcode attributes.

== Features ==

* Show date and/or time in the default WordPress formats.
* Show date and/or time in custom format based on the PHP [date and time formatting characters](https://www.php.net/manual/en/datetime.format.php)
* Show date and/or time based on fixed SQL/date/time format or any [relative date and time format](https://www.php.net/manual/en/datetime.formats.relative.php)
* Show year separately as 4 or 2-digit number (e.g. 1999 or 99).
* Show month as number (1-12), full name (January-December) or 3-letter name (Jan-Dec).
* Show day as number with or without leading zero, with or without suffix (st, nd, rd, th).
* Show hour, minutes, seconds with or without leading zero.
* Show day of week as number (1-7), full name (Monday-Sunday) or three letters (Mon-Sun).
* Show the day of the year (1-366).
* Show the number of days for the month (28-31).
* Show the number of days in February for the year (28 or 29).
* Show the day of the year (365 or 366).
* Show the week of the year (1-52).
* Show currently used time-zone.
* Show the first day from a list of next coming weekdays, days of month, or the last day of the current month.
* Add or subtract years, months, days, hours, minutes and/or seconds before showing the final result.
* Set post/page creation or modification time (including GMT variants) as "init" attribute.

= Attributes and values =

You can add the following attributes to **`[wpdts]`** to show the date and time that you need:

* `item` - what date and time information to show. The used values are:
    * `date-time` - default WP date and time format (default value)
    * `custom` - custom format using the PHP [date and time formatting characters](https://www.php.net/manual/en/datetime.format.php)
    * `date` - default WP date format
    * `time` - default WP time format
    * `year`, `years` - 4-digit year
    * `year-short`, `years-short` - 2-digit year
    * `month`, `months` - month as number (1-12)
    * `month-name` - month as name (January-December)
    * `month-name-short` - month as 3-letter name (Jan-Dec)
    * `day`, `days` - day of month (1-31)
    * `hour`, `hours` - hours (0-24)
    * `minute`, `minutes` - minutes (0-60)
    * `second`, `seconds` - seconds (0-60)
    * `timestamp` - Unix timestamp
    * `day-of-year` - day of the year as number (1-366)
    * `days-in-month` - number of days in the month (28-31)
    * `days-in-february` - number of days in the month of February for the year (28-29)
    * `day-of-week` - day of the week as number (1-7)
    * `day-of-week-name` - day of the week as full name (Monday-Sunday)
    * `day-of-week-name-short` - day of the week as short 3-letter name (Mon-Sun)
    * `week-of-year` - week of year, since first Monday of the year (1-52)
    * `am-pm` - am/pm or AM/PM according to the am_pm attribute ("L" or "U")
    * `time-zone` - the current time-zone for the shortcode result
* `format` - date format used with the PHP [date and time formatting characters](https://www.php.net/manual/en/datetime.format.php) only when `item` is set to `custom`
* `start` - set the basic date and time for the shortcode; defaults to the WordPress time in the current timezone.
    * initial date and/or time string based on fixed SQL/date/time formats (e.g. 2019-09-16 17:45:53 or Sep 16, 2019 17:45:53) or any [relative date and time format](https://www.php.net/manual/en/datetime.formats.relative.php)
    * `now` - the default initial current date and time based on the WordPress General settings time-zone
    * `post-created` - gets the post/page creation date and time
    * `post-created-gmt` - gets the post/page creation date and time GMT
    * `post-modified` - gets the post/page last modification date and time
    * `post-modified-gmt` - gets the post/page last modification date and time GMT   
* `next` - move the start date and time to the next coming selected (can have more than one value separated by comma)
    * `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun` - weekday shortname (and with first letter in uppercase)
    * `1`-`31` - day of month
    * `last-day-of-month` - the last day of month - 28, 29, 30, or 31
* `time_zone` - select the time-zone for which to display the result from the available PHP [time-zones](https://www.php.net/manual/en/timezones.php).
* `i18n` - set months and weeks names to be displayed in the current language ('yes' by default)
* `days_suffix` - set suffix st, nd, rd, th for the `day` and `days` item e.g. 1st, 2nd, 3rd, 4th, etc.
* `hours_24` - set 12 or 24 hours format for the `hours` item.
* `am_pm` - used with the `am-pm` item when 12 hours format is preferred: "L" for lowercase (am, pm) or "U" for uppercase (AM, PM)
* `post_id` - post ID from which to get post-created(-gmt) or post-modified(-gmt)
* `years` - change in years e.g. `years="+1"` or `years="-1"` before showing
* `months` - change in months e.g. `months="+2"` or `months="-2"` before showing
* `days` - change in days e.g. `days="+7"` or `days="-7"` before showing
* `hours` - change in hours e.g. `hours="+12"` or `hours="-12"` before showing
* `minutes`, `minutes_change` - change in minutes e.g. `minutes="+30"` or `minutes="-30"` before showing
* `seconds`, `seconds_change`  - change in seconds e.g. `seconds="+45"` or `seconds="-45"` before showing
* `zero` - show leading zero when months, days, hours, minutes and seconds are displayed. Old ones '*x*-zero' for each separate item are still supported for compatibility.

== (COMING ASAP) 3.0 PRO EDITION with an annual subscription plan ==

WP Date and Time Shortcode is fully FREE and contains all main features that are needed by most users. Check them out!

However, since we want to continue supporting this plugin and adding new non-basic features, we have decided to offer a Pro edition with an annual subscription plan offered at a very affordable price per website. We are working hard to release the Pro edition although it took us a little bit more time to prepare it than expected in the beginnig.

**What will you get with WP Date and Time Shortcode Pro in the near future?**

* Technical support by [email](mailto:support@denra.com) and Facebook Messenger chat on our [website](https://www.denra.com/). The free version will be supported by the [Support Forum](https://wordpress.org/support/plugin/wp-date-and-time-shortcode/) only.
* JavaScript display of the shortcode result even on cached pages. You won't need to turn off the caching for any page and post!
* Additional pre-defined date and time initialization values like:
    * First visit on the website (saved as cookie, session and user option if possible)
    * Last visit on the website (saved as cookie, session and user option if possible)
    * Catholic and Orthodox Easter dates
    * Passover
    * Hanukkah
    * Mother's day
    * Father's day
    * Columbus day
    * Thanksgiving day
    * Black Friday
    * Cyber Monday
    * Christmas
    * New year
    * Chinese new year
    * and more.
* Additional items and attributes:
    * `style` - change font face, color and size;
    * `time-zone` browser/IP parameter - modify the shortcode result to the visitor's timezone (not 100% accurate since it gets the timezone by IP address);
    * `counter` - dynamically change displayed values as a ticking text clock;
* Shortcode Wizard - create shortcodes using an easy to use step-by-step wizard.
* Menu Location - change the menu display - main admin menu, settings menu, top menu, or turn off all.
* And more.

**IMPORTANT NOTE:** Not all Pro features will be released in the first Pro version since more work and testing is needed for some of them.

== Installation ==

= From WordPress Dashboard =

1. Navigate to `Plugins` -> `Add New` from your WordPress dashboard.
2. Search for `WP Date and Time Shortcode` and install it.
3. Activate the plugin.

= Manual Installation =

1. [Download](https://www.denra.com/products/wordpress/plugins/wp-date-and-time-shortcode/) the plugin file: `wp-date-and-time-shortcode.zip`.
2. Unzip the file.
3. Upload the `wp-date-and-time-shortcode` folder to your `/wp-content/plugins` directory (do not rename the folder).
4. Activate the plugin from the Plugins menu.

It will start doing its job as soon as you put the shortcode in your page or post. That's all, folks!

== Frequently Asked Questions ==

= Have you already released a paid Pro edition of this plugin with more features? =
We are working on such a subscription based Pro edition and it will be released by the end of Q1 2024.

= Why the shown shortcode result is not updated for every single visit? =
If you are using a caching plugin you may need to exclude the page or post with the shortcode from it so the content may be generated dynamically. If you are using the `start` attribute with a fixed initial value you may also block the change of the shortcode result if needed.

= Why some or all of the used attributes of the shortcode do not work? =
Please make sure that you are using only straight double quotes (`"`) and not curly/smart ones(`„` or `“`) for the attributes. The curly ones are not recognized by the shortcode functions. If you are using the straight quotes only and you are still having issues please contact us for support.

= The shortcode doesn't work in all places e.g. page/post title, footer, menus, etc.? =
WordPress applies the shortcodes in post's and page's content by default. If your theme does not apply shortcodes in other places automatically you may need to add additional code to your child theme's functions.php to hook into those functions where you need to see the shortcodes applied.
For example:
`// For the titles.`
`add_filter('wp_title', 'do_shortcode', 10);`
`add_filter('the_title', 'do_shortcode', 10);`
`// For the menu.`
`add_filter('walker_nav_menu_start_el', 'do_shortcode', 10);`
etc.
If you do not know how to put the correct code you may want to use additional plugin for the purpose like [this one](https://wordpress.org/plugins/jonradio-shortcodes-anywhere-or-everywhere/) to enable any shortcodes in other places of your website pages.

= Why the major shortcode is `[wpdts]` now instead of the old `[wp-dt]` or `[wp_dt]`? =
As we were improving the plugin we found out the `[wpdts]` shortcode will be much more recognizable and easier to use by everyone so we decided to change the old ones. We strongly recommend the use of the new `[wpdts]` shortcode although backward compatibility for `[wp-dt]` or `[wp_dt]` is supported.

= I have an idea how this plugin can be improved. Can I send it somehow? =
We have generally completed the development of Free edition of this plugin and it has a big number of features which may cover every basic need. In future we will be mainly supporting it for WordPress version compatibility and bugs. However we will be releasing a Pro edition soon with an annual subscription plan and it will have many new features. Please send all your suggestions and ideas to our [e-mail address](mailto:support@denra.com) and they may be developed and included in the paid product in future. Please note that if you have suggested a new feature and it is implemented you will get a 1-year subscription for 1 website for free.

== Changelog ==

= 2.6.5 =
* Fixed: Some changes to remove deprecation messages related to PHP 8.0+.

= 2.6.4 =
* Fixed: Bug fixed in the `next` attribute for some edge cases.
* Added: Backward compatibility with version 2.5.3 - `timezone` duplicate item added.

= 2.6.3 =
* Added: The missing post ID or post object for the `post_id` attribute when the `item` is `post-created(-gmt)`, `post-modified(-gmt)` does not throw a PHP error but shows an understandable message.

= 2.6.2 =
* Fixed: A bug with UTC+ or UTC- time zones. Please update if you have that type of time zone set for your website.

= 2.6.1 =
* Fixed: Removed a couple of warnings.

= 2.6 =
* Fixed: A bug with WordPress default time-zone for relative dates was fixed. The default time-zone works correctly now when a relative date is set in the `start` parameter e.g. today, tomorrow, yesterday, next week, etc.
* Added: A specific time-zone can be used in the `start` attribute e.g. America/New_York, Europe/London, Asia/Tokyo, etc.
* Added: New attribute `time_zone` was added to define for which time-zone the result must be displayed e.g. America/New_York, Europe/London, Asia/Tokyo, etc.
* Added: New `timestamp` parameter for the `item` attribute to show the UNIX timestamp.
* Added: New `time-zone` parameter for the `item` attribute to show the current time-zone for the shortcode result.
* Added: A post ID can be selected with a new `post_id` attribute when `date-created-(gmt)` or `date-modified(-gmt)` is set for `item`.
* Modified: Readme.txt info changes.
* Other: WordPress 6.2 compatibility.

= 2.5.3 =
* Other: WordPress 5.5.3 - 6.1 compatibility.

= 2.5.2 =
* Modified: Readme.txt info changes.
* Other: WordPress 5.5 compatibility.

= 2.5.1 =
* Added: Description how to show date and/or time based on any [relative date and time format](https://www.php.net/manual/en/datetime.formats.relative.php).

= 2.5 =
* Fixed: Removed Framework warnings on plugin activation and usage.

= 2.4 =
* Fixed: There was a bug in the `next` attribute in some cases when the next day is in the next month. Please update!
* Modified: Changing the shortcode to `[wpdts]` instead of `[wp-dt]` and `[wp_dt]`. Backward compatibility is supported and old shortcodes will still work but the use of the new one everywhere is strongly recommended.
* Modified: Changing the `init` attribute to `start`. Backward compatibility is supported the old `init` attribute but the use of the new `start` attribute everywhere is strongly recommended.
* Other: Preparation for the next 3.0 major update.

= 2.3.1 =
* Fixed: A critical bug with shortcodes attributes was fixed. Please update!

= 2.3 =
* Fixed: A critical bug with some default attributes' initialization was fixed. Please update!
* Improved: Added `years-short` alias for `year-short`.
* Improved: There is only one `zero` attribute now for months, days, hours, minutes and seconds. Old ones '*x*_zero' are still supported for compatibility.
* Other: WordPress 5.4 compatibility.

= 2.2.1 =
* Fix: Item `days-in-month` works even when PHP on server is complied without --enable-calendar option.
* Other: WordPress 5.3.2 compatibility.

= 2.2 =
* Added: Shortcode item `days-in-year` added.
* Added: Shortcode duplicate/alias item `date-time` for item `datetime`.
* Added: `next` attribute that helps in displaying the next date from a list of weekdays, days of month, or the last day of the current month.
* Improved: Framework update and bug fixes.

= 2.1.1 =
* Fixed: `week-of-year` item not showing correctly.

= 2.1 =
* Framework update and bug fixes.

= 2.0 =
* Added: Denra Plugins Framework 1.0.
* Added: Feature to set the `init` attribute from page/post creation or modification time.

= 1.2 =
* Added: Shorter version for the `_change` attributes removing the `_change` part.

= 1.1 =
* Added: More shortcodes for easier and faster use by missing the `item` attribute.

= 1.0.1 =
* Fixed: A couple of bug fixes after the initial release

= 1.0 =
* Initial release
