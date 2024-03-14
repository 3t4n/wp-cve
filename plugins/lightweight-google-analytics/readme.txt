=== Lightweight Google Analytics ===
Contributors: someguy9
Donate link: https://www.buymeacoffee.com/someguy
Tags: Google Analytics, Analytics, Google Analytics Plugin, WordPress analytics
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.0
Stable tag: 1.4.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Extremely simple plugin to add Google Analytics to your WordPress site using your tracking ID. No upsells or additional features, simply an easy way to include Google Analytics into your WordPress site.

== Description ==

This simple plugin adds Google Analytics into your site using your tracking ID. Additional features include the ability to change the tracking code position, disabling display features, anonymize IP, and excluding roles from tracking. Additionally the plugin works regardless if you're using a Global site tag measurement ID (gtag.js) or Universal Analytics tracking ID (analytics.js).

**Features**

* Performance focused with no bloat
* Extremely simple to setup
* Works with analytics.js or gtag.js measurement/tracking IDs
* Regular updates for when GA changes in the future
* Only requires your GA tracking ID to work
* Option to put the tracking code in the header or footer
* Options to disable display features and anonymize IPs
* Options to exclude specific roles from GA tracking
* Option to use MinimalAnalytics.com's tracking code (or MinimalAnalytics4 if using gtag.js)
* Works with WP-Rocket's local GA script feature
* Never any upsells

This plugin uses Google Analytics' analytics.js or gtag.js tracking code by default. It does not include any built in reporting, to view your site statistics you can visit Google Analytics after installing.

== Installation ==

To install this plugin:

1. Download the plugin
2. Upload the plugin to the wp-content/plugins directory,
3. Go to "plugins" in your WordPress admin, then click activate.
4. You'll see a new option for Lightweight Goole Analytics in your settings tab.

== Frequently Asked Questions ==

== Screenshots ==

1. Plugin settings page for Lightweight Google Analytics


== Changelog ==

= 1.4.2 =
* Tested up to WordPress 6.3.

= 1.4.1 =
* PHP Error bugfix.

= 1.4 =
* Included MinimalAnalytics4 by [idarek on GitHub](https://gist.github.com/idarek/9ade69ac2a2ef00d98ab950426af5791). This allows you to use a gtag tracking code for page views & site search without loading Google's bloated tracking library.
* Removed jQuery references in the admin for vanilla JS.
* Tested up to WordPress 6.0.

= 1.3 =
* Tested up to WordPress 5.8.
* Added link to analytics.google.com in settings page

= 1.2.2 =
* PHP Warning bug fix.

= 1.2.1 =
* Tested up to WordPress 5.7.

= 1.2.0 =
* Added the ability to use Global site tag measurement ID (gtag.js) as well as Universal Analytics tracking ID (analytics.js).

= 1.1.1 =
* Admin page tweaks and added a setup prompt at first install

= 1.1.0 =
* Added option to use MinimalAnalytics.com's tracking code

= 1.0.0 =
* Initial Release.
