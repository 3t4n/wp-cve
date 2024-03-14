=== Include Me ===
Tags: php, include, php execute, external page, iframe
Tested up to: 6.3.2
Stable tag: 1.3.2
Donate link: https://www.satollo.net/donations
Contributors: satollo

Include Me helps to include any external file (textual, HTML or PHP) in posts or pages.

== Description ==

Include Me helps to include in posts or pages external files usually to be shared
between different posts or pages or that contains PHP or other code that can be
compromised by the visual editor.

The use is immediate: the shortcode [includeme] is all that you need (see the documentation
on [Include Me official page](https://www.satollo.net/plugins/include-me)).

The best way to use it is to include functionalities
written in external PHP that will be rendered in post body or to include pieces of
javascript that will be hard to add with WordPress editor.

Inclusions can be rendered with IFRAME if needed to create boxes that display
external web pages.

This plugin is made of few line of code, ultralite!

Other plugins by Stefano Lissa:

* [Hyper Cache](https://www.satollo.net/plugins/hyper-cache)
* [Newsletter](https://www.thenewsletterplugin.com)
* [Header and Footer](https://www.satollo.net/plugins/header-footer)
* [Thumbnails](https://www.satollo.net/plugins/thumbnails)

= Translation =

You can contribute to translate this plugin in your language on [WordPress Translate](https://translate.wordpress.org)

== Installation ==

Once installed you can start to use the `[includeme]` shortcode to include external file in your posts or pages.
See the official page for example and options.
The inclusion folder is initially set to `WP_CONTENT_DIR/include-me`. You can change it with a `define('INCLUDE_ME_DIR', '...')` in your `wp-config.php`.
If you want to enable the old behavior and be able to include from any location, use `define('INCLUDE_ME_DIR', '*')`.

== Frequently Asked Questions ==

No questions have been asked.

== Screenshots ==

No screenshots are available.

== Changelog ==

= 1.3.2 =

* iframe fix

= 1.3.1 =

* Updated compatibility with WP 6.3.2

= 1.3.0 =

* Added an option to include files from any location

= 1.2.2 =

* Breaking change and security fix
* includeme shortcode is executed only on posts owned by an administrator
* includeme shortcode includes only files inside the blog folder (ABSPATH and below)

= 1.2.1 =

* Compatibility check with WP 5.7

= 1.2.0 =

* Compatibility check with WP 5.4.2
* Increased minimum PHP version to 5.6

= 1.1.8 =

* Compatibility check with WP 5.2.4
* Reorganized admin files

= 1.1.7 =

* General compatibility check with latest WP

= 1.1.6 =

* Add support for inclusion and execution of a post meta field

= 1.1.5 =

* Added support for inclusion of a post/page content

= 1.1.4 =

* Fixed a link

= 1.1.3 =

* Added usage search in posts and pages

= 1.1.2 =

* WP 4.4.2 compatibility check
* Fixed few texts

= 1.1.1 =

* Added translation code

= 1.1.0 =

* Fixes

= 1.0.9 =

* Compatibility check for WP 4.0
* readme.txt fix

= 1.0.8 =

* Compatibility check

= 1.0.7 =

* Performce improvements

= 1.0.6 =

* Improvements

= 1.0.5 =

* Added short codes execution feature on included content (by Rusty Eddy)

= 1.0.4 =

* Administrative styles and header