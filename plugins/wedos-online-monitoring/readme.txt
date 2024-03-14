=== WEDOS OnLine monitoring ===
Contributors: wedosonline
Tags:
Requires PHP: 5.4
Requires at least: 4.7
Tested up to: 6.4
Stable tag: trunk
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WEDOS OnLine monitoring plugin. It allows you to link your website with WEDOS OnLine free monitoring solution.

== Description ==

WEDOS OnLine is free monitoring service that lets you track the functionality and availability of your website.

This plugin allows you to link your [WEDOS OnLine monitoring](https://www.wedos.online/) account to your WordPress.

This makes it easy to verify the ownership of the domain on which you run this WordPress, without which monitoring cannot be activated.

In the future, you will be able to use this plugin to track your site monitoring statistics directly in WordPress.

The plugin uses API communication with WEDOS OnLine. No personal data or any other data are sent from WordPress outside (only authentication tokens are exchanged).

This plugin does not process or store any personal data (except API authentication token).

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wedosonline` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Sign up for or sign in to your [WEDOS OnLine account](https://cp.wedos.online/), create a new HTTP check to start a watchdog for your WordPress site. You will then be able to link the check to your WordPress in the check detail.

== Changelog ==

= 1.0.10 =

* Fix connection error handling in API client

= 1.0.9 =

* Fix sessions handling

= 1.0.8 =

* Fix sessions handling

= 1.0.7 =

* Fix REST API warning

= 1.0.6 =

* Fixes for PHP 5.6

= 1.0.5 =

* Verify connection with WEDOS OnLine API
* Show basic charts on dashboard

= 1.0.4 =

* Internationalization of all texts and messages, plugin can be translated

= 1.0 =

* First version
* Only website ownership verification is available at the moment. More functions coming soon.

== Screenshots ==

1. Check detail
2. Overview uptime and average response statistics
3. Detailed average response statistics
4. Detailed uptime statistics
