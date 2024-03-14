=== Banner Alerts ===
Contributors: valicesupport
Tags: alert, notice, info, message, banner alert, alert banner, warning, display warning
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 1.4.1
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides an easy interface for creating and displaying alerts or notices as a banner on a website

== Description ==

Banner alerts are custom alerts to notify your website visitors of promotions, alerts, events, etc. These display at the top of the website until they are dismissed by the visitor. Any new visitor who has not been to the website will see these until they are dismissed.

Banner alerts are configured just like a post or page with support for title, content, and excerpt. The plugin settings allow you to define which parts of the alert to display and whether or not to link to the full post. You can run more than one alert at a time and activate or deactivate as needed.

This plugin is ideal for:

* Important operational changes
* Technical notices
* Time-sensitive appeals for donation or action
* Terms or agreement changes
* Privacy notice acknowledgements
* Service outages or maintenance messages
* Special offers or promotions

== Installation ==

* Install the plugin using Add New within WordPress or via FTP
* Activate the plugin
* Confirm your display settings under Settings -> Banner Alerts
* Create one or more alerts

== Screenshots ==

1. Multiple alerts with titles linking to full posts
2. Single alert with excerpt and no link to post
3. Alert with title, excerpt, and link to read more
4. Alert on a website with a fixed header
5. Banner Alerts settings page
6. Banner Alerts post type page

== Changelog ==

= 1.4.1 (2023-11-04) =
* Tweak: Add class to previous/next controls for better styling support

= 1.4.0 (2021-12-28) =
* New: Allow shortcodes to be used within alert content
* Tweak: Utilize translate.wordpress.org as priority for translations
* Tweak: Flush rewrite rules for all sites when network activated/deactivated
* Tweak: Additional support for PHP 8.0
* Fix: Only retrieve alerts marked as published

= 1.3.1 (2021-06-28) =
* Tweak: Only add plugin styles when there are alerts to display

= 1.3.0 (2021-06-20) =
* New: Ability to display multiple alerts in a slider
* New: Add option to not allow dismissing of alerts
* New: Add default and immediate as options to open/close settings
* Tweak: Add controls container for previous, next and dismiss
* Tweak: Updates to support latest jQuery

= 1.2.1 (2020-04-14) =
* Tweak: Minor formatting adjustments

= 1.2.0 (2020-04-12) =
* New: Option to control open/close animation speed
* New: Add POT file for language translation

= 1.1.0 (2020-04-06) =
* Tweak: Modify alert dismissal from session to 30 minutes

= 1.0.0 (2020-04-06) =
* New: Localization support

= 0.7.1 (2020-03-31) =
* Tweak: Move default styles into settings

= 0.7.0 (2020-03-30) =
* New: Minimal default styles for banner-alerts container

= 0.6.0 (2020-03-27) =
* Tweak: Remove legacy dependencies in favor of new built-ins

= 0.5.3 (2020-03-27) =
* New: Add option to display title without link

= 0.4.5 (2016-05-17) =
* Tweak: Do not check alerts for remainder of the session after being dismissed

= 0.4.2 (2014-08-31) =
* New: Support for various display options
* Tweak: Allow HTML in alert body
* Fix: Add support older IE browsers

= 0.3.1 (2014-08-17) =
* Fix: Fix issue where container does not hide correctly

= 0.3.0 (2014-08-17) =
* New: Add banner-alerts-container to support styling

= 0.1.5 (2014-05-29) =
* New: Add settings page

= 0.1.1 (2014-02-13) =
* New: Add read-more class to Read More link
* Tweak: Change slug from banner-alerts to just alerts
* Fix: Flush rewrite rules on activate and deactivate

= 0.1.0 (2014-02-12) =
* Initial beta release
