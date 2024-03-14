﻿=== WP Calameo ===
Contributors: calameo, Sevanova
Tags: embed, calameo, publication, widget, document
Requires at least: 2.1
Tested up to: 6.4.3
Stable tag: 2.1.8

This plugin allows to embed Calaméo publications in blog posts. Copy the WordPress embed code and paste it into your post.

== Description ==

This plugin allows to embed Calaméo publications in blog posts. Simply copy the WordPress embed code provided by Calaméo and paste it into your post.

[http://www.calameo.com](http://www.calameo.com)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `wp-calameo.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Copy the WordPress embed code provided by calameo.com and paste it in your post

== Frequently Asked Questions ==

Have questions or feedback? Please check our [support community](http://getsatisfaction.com/calameo).

== Changelog ==

= 2.1.8 =
* [Bugfix] Fixed XSS security issue
* [Check] Plugin works with WordPress 6.4.3+

= 2.1.7 =
* [Check] Plugin works with WordPress 6.4.2+

= 2.1.6 =
* [Check] Plugin works with WordPress 6.1.1+

= 2.1.5 =
* [New] Added "lang" parameter

= 2.1.4 =
* [New] Added "mobiledirect" parameter

= 2.1.3 =
* [Bugfix] Fixed "showsharemenu" parameter

= 2.1.2 =
* [Bugfix] Missing "authid" parameter

= 2.1.1 =
* [Bugfix] "authid" parameter fixed

= 2.1.0 =
* [New] Global refactoring to fit with WordPress coding standards
* [New] Use the shortcode API instead of a preg function

= 2.0.7 =
* [Check] Plugin works with WordPress 4.9+

= 2.0.6 =
* [Bugfix] Language detection

= 2.0.5 =
* [Check] Plugin works with WordPress 4+
* [New] Added plugin icon (svg+png)
* [New] Added plugin banner (jpeg)

= 2.0.4 =
* [Bugfix] "wmode" parameter fixed

= 2.0.3 =
* [Change] Updated the HTML embed code to support SSL if needed

= 2.0.2 =
* [Bugfix] Removed height CSS on container DIV

= 2.0.1 =
* [New] Added customization parameters available in the new viewer

= 2.0.0 =
* [New] Upgrade to embed code to use the new iframe version

= 1.2.3 =
* [Bugfix] PHP short tags bug fixed

= 1.2.2 =
* [Bugfix] Height attribute fixed inside DIV wrapper

= 1.2.1 =
* [Bugfix] Width and height attributes fixed.

= 1.2.0 =
* [New] Added iPad, iPhone and iPod Touch support.

= 1.1.0 =
* [Bugfix] Full-sized publication loading bug on MacOs Safari (allowScriptAccess added).
* [Bugfix] "view" parameter fixed.

= 1.0 =
* Initial release.