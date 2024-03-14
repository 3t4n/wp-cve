=== SV Proven Expert ===
Contributors: matthias-reuter, matthiasbathke, dennisheiden
Donate link: https://straightvisions.com
Tags: proven expert, provenexpert, straightvisions, reviews, star reviews, google star reviews
Requires PHP: 8.0
Requires at least: 6.0.0
Tested up to: 6.2.2
Stable tag: 2.0.02
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Show Review Stars via ProvenExpert.com in WordPress

== Description ==

= Requires: =
* PHP 7.3 or higher
* WordPress 5.3.x or higher
* CuRL PHP extension

= Service Description =

<a href="https://straightvisions.com/go/proven-expert/">ProvenExpert</a> is a service for company reviews. A key feature ("PLUS" plan required) is showing the company review stars on your website. These will be retrieved from different review-providers like Google, Facebook etc.

= Plugin Description =

This plugin is build to show review stars retrieved via <a href="https://straightvisions.com/go/proven-expert/">ProvenExpert</a> on your site - additionally this enables review stars of your website's entries in Google's search engine result pages.

= Team =

* Developed and maintenanced by <a href="https://straightvisions.com">straightvisions</a>

== Installation ==

This plugin is build to work out-of-the-box. Installation is quite simple.

1. Upload `sv_provenexpert`-directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Register a free account on <a href="https://straightvisions.com/go/proven-expert/">ProvenExpert.com</a> - you can try out this feature 30 days for free.
4. Insert Api Keys in plugin settings - see menu entry "straightvisions" in WP-Admin
5. Add the widget or shortcode `[sv_proven_expert]` somewhere in your wordpress.
6. You are using a caching plugin? Don't forget to flush caches now.

<a href="https://straightvisions.com/sv-proven-expert/">ProvenExpert WordPress Plugin Docs</a> are available in English and German.

== Frequently asked questions ==

= Is this plugin for free? =

This plugin is for free and licensed to GPL.
It's open source following the GPL policy.

= How can I revert all changes or reset the plugin? =
This plugin brings an uninstall routine. Just deactivate plugin on plugin listing page in WP-Admin and after that, click on "delete" will completely remove all database entries and files generated through this plugin.

= Does this plugin calls to another server? =

Yes. As <a href="https://straightvisions.com/go/proven-expert/">ProvenExpert</a> is a service in the cloud, it is absolutely required to call the ProvenExpert.com API server to get the required data. The plugin will refresh it's cached data once a day.

= Can I change style of review stars on website? =

You can change the style via CSS, e.g. in your theme's style.css.

== Screenshots ==

1. Result: Review Stars in Google SERP entries
2. Result: Review Stars on your Website
3. Settings in WordPress
4. Result: Review Stars in machine readable format for Google

== Changelog ==

= 2.0.02 =
* fix W3C-HTML-Compatibility

= 2.0.01 =
* error fix

= 2.0.00 =
* Core Update
* Block Support added

= 1.9.00 =
* Core Update

= 1.8.03 =
* Core Update

= 1.8.02 =
* fix widget css loading

= 1.8.01 =
### Security Fix

* Third Party Vendor Library

### Various

* Core Update

= 1.8.00 =
### Various

* Core Update

= 1.7.00 =

### Various

* SV Core improvements

= 1.6.00 =
* core update

= 1.5.14 =
* core update

= 1.5.13 =
* core update

= 1.5.12 =
* core update

= 1.5.11 =
* FIX Settings not retrieved

= 1.5.10 =
* update core

= 1.5.00 =
* core update

= 1.4.08 =
* core update

= 1.4.07 =
* core update

= 1.4.06 =
* core update
* stars display fix

= 1.4.05 =
* core update

= 1.4.04 =
* core update

= 1.4.03 =
* fix error notice
* core update

= 1.3.14 =
* core update

= 1.3.1 =
* core update
* Bugfix: Remove obsolete Settings from Widgets

= 1.3.0 =
* core update
* Bugfix: Widget Loading

= 1.0.9 =
* core update

= 1.0.8 =
* core update

= 1.0.7 =
* support added for legacy shortcode

= 1.0.6 =
* PHP 7.1 and higher Support: Bugfix for Fatal error: Cannot use lexical variable $widget_class as a parameter name in /wp-content/plugins/sv-provenexpert/lib/core/widgets/widgets.php on line 127
* Decrease Admin Menu priority
* highlight shortcode in settings

= 1.0.5 =
Major Refactoring

= 1.0.4 =
Preview and Error Output added on settings screen. Usability in settings field styles improved.

= 1.0.3 =
Fixed a W3C HTML Validation Markup Error generated through HTML Snippet retrieved from Proven Expert

= 1.0.2 =
Curl Decoding Error fixed

= 1.0.1 =
Improved Caching

= 1.0 =
Initial Release

== Upgrade Notice ==
fix W3C-HTML-Compatibility

== Missing a feature? ==

Please use the plugin support forum here on WordPress.org. We will add your wish - if achievable - on our todo list. Please note that we can not give any time estimate for that list or any feature request.

= Paid Services =
Nevertheless, feel free to contact our [WordPress Agency](https://straightvisions.com) if you have any of the following needs:

* get a customization
* get a feature rapidly / on time
* get a custom WordPress plugin or theme developed to exactly fit your needs.