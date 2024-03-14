=== Simple Site Verify ===
Contributors: mannweb
Tags: google webmaster, bing webmaster, pinterest, google analytics, site verify
Requires at least: 4.6.0
Tested up to: 6.4
Stable tag: 1.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple method of verifying your site via Pinterest, Google, Bing, Yandex, and Google Analytics.

== Description ==

Simple method of verifying your site via Pinterest, Google, Bing, and Google Analytics. Enter the tracking code provided by each of these services and the plugin will add the appropriate tracking code into the head section of your sites pages.

== Installation ==

Simply download, install and activate. Enter the tracking codes into the settings page and the tracking html code will be added to your sites pages.

e.g.

1. Upload `simple-site-verify` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add tracking codes on settings page

== Frequently Asked Questions ==

== Screenshots ==
 1. Simple Site Verify tracking code settings page

== Changelog ==

= 1.0.8 =
* Added input escaping to address possible Cross Site Exploitation (XSS)

= 1.0.7 =
* Added support for Google Analytic's new GA4, which start with G-
* Continue to have Universal Access (UA-) support, which ends July 1, 2023)

= 1.0.6 =
* Slight code and comment cleanup.

= 1.0.5 =
* Added support for Yandex.

= 1.0.4 =
* Updated code to first check that any verification code has been entered. This prevents empty tags from potentially being created.

= 1.0.3 =
* Updated google analytics to only show if the tracking code box is not empty.

= 1.0.2 =
* Updated plugin to add settings link on plugins page.

= 1.0.1 =
* Updated meta tags display. Now only adds if the tracking code box is not empty.

= 1.0.0 =
* Initial Release

== Upgrade Notice ==
