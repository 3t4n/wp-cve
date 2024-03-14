=== Plugin Name ===
Contributors: vlijmen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WTW7PHYKERFKY
Tags: uptime, uptimerobot, monitoring, pages, posts, widget, dashboard, shortcode, logs, dutch, english, french, german, romainian
Requires at least: 4.8
Tested up to: 6.1.1
Stable tag: 2.3
License: GPLv2 or later
License URI: www.gnu.org/licenses/gpl-2.0.html

View your uptime stats/logs within WordPress (dashboard), and if desired on pages, posts or in a widget.

== Description ==

This Uptime Robot Plugin for Wordpress let's you show your uptime server stats from [Uptime Robot](http://uptimerobot.com) inside the WordPress admin area and if desired on pages, posts or in a widget. You can show multiple monitors on your preffered place using a simpel shortcode.

- Account at [UptimeRobot.com](http://uptimerobot.com) required

Simple installation and configuration

= Admin side =
* Settings, choose wich monitors to be enabled, move offline monitors to the top
* View all monitors including status, duration and details
* Drag and drop to order monitors
* Logs with offline/paused status history
* Response time charts for all monitors
* Shortcode guide
* Custom caching time

= Client side =
* Customize styling
* Display uptime stats anywhere with a shortcode [uptime-robot]
* Display logs where you want it with a shortcode [uptime-robot-logs]
* Display a response time chart where you want it with a shortcode [uptime-robot-response]
* Custom front end shortcodes (see shortcode page inside admin area).

[Check out the live demo @Aphotrax](https://aphotrax.eu/support/uptime-monitor/?utm_source=WordPress&utm_medium=readme&utm_campaign=plugin)

== Installation ==

1. Upload the files and folder in the zip file 'uptime-robot-nh' to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your API Key and monitor id(s) on the settings page
4. Place a shortcode anywhere you wish.

== Frequently Asked Questions ==

= Can I safely upgrade from V1.x to V2? =
Yes, you can upgrade without a problem to the new version. Just be informed that the shortcodes with -nh in the end are not supported any more. Also some attributes in shortcodes have been changed. I would advise you to check your shortcodes and settings after upgrading. All old settings/options will be deleted from your WordPress installation, only the main apikey will be copied to the new database.

= Why does the function offline monitors to top change the manual order? =
Please upgrade PHP to version 7 or higher to get the ordering correct. Without it you will have to choose if you preffer offline monitors to the top with ordering by id, or manual ordering.

== Screenshots ==
1. Settings page
2. Shortcodes output example
3. Monitor details in the admin area
4. Customized styling
5. Response time charts
6. Shortcode overview
7. Log history in the admin area
8. Dashboard will show uptime stats and logs for the last month

== Changelog ==
= 2.3 =* Fix critical error PHP8: [Markxman BV](https://wordpress.org/support/topic/issue-with-characters-and-critical-error-in-wordpress/)= 2.2.2 =* Local timezone added to logs: [Markxman BV](https://www.markxman.com/)
= 2.2 =
* API request method changed so Curl is not needed anymore: [cameronjonesweb](https://wordpress.org/support/topic/doesnt-support-php-7-2/)
* Bugfix MySQL activation: [optimalisatie](https://wordpress.org/support/topic/re-install-causes-mysql-errors/)
* Bugfix script registration for jquery: [clevercookiedes](https://wordpress.org/support/topic/missing-argument-undefined-variable-cannot-modify-header/)
* Mysql requests optimized for better performance

= 2.1.2 =
* Undefined page error resolved By: [mr_swede](https://wordpress.org/support/topic/error-msg-connection-to-the-uptime-robot-api-was-not-possible/)

= 2.1.1 =
* Changes in sortabel function to avoid conflict with other themes. By: [Sebastian](https://wordpress.org/support/topic/on-admin-side-parser-blocking-cross-origin-script-error/)
* Changes in installation, activation, re-activation. By [Jan Jaap](https://wordpress.org/support/topic/re-install-causes-mysql-errors/)


= 2.1 =
* Disabled pro
* Duplicate query issue resolved
* Speed improvements

= 2.0.x =
* Changed api uptime_ratios to uptime_ranges for more accurate feedback from API
* Added Romainian and complete French language files.

* Option added to always show log table even without recent logs
* Changes in upgrade processes
* Small bug fixes
* Partial caching enabled on admin side
* Refresh option for realtime added
* Dashboard widget added back

= 2.0 =
* Complete rebuild of the plugin has taken place.
* Multisite support for all round or seperate settings.
* Caching improved and will be done in a seperate DB.
* More then 50 monitors will work.
* Duration of current status enabled.


= Can I safely upgrade from V1.x to V2? =
Yes, you can upgrade without a problem to the new version. Just be informed that the shortcodes with -nh in the end are not supported any more. Also some attributes in shortcodes have been changed. I would advise you to check your shortcodes and settings after upgrading. All old settings/options will be deleted from your WordPress installation, only the main apikey will be copied to the new database.