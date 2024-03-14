=== Admin Collapse Subpages ===
Contributors: bravokeyl, lupka
Donate link: https://bravokeyl.com/contact/
Tags: page, post, links,pages, admin , link ,plugin, random, posts, widget, categories, date, date range, timeframe, excerpt, randomize, sidebar, category
Requires at least: 4.6
Tested up to: 5.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Using this plugin one can easily collapse/expand pages with children and grand children.

== Description ==

Simple plugin that allows you to collapse subpages in the Pages admin list also for custom post types that are heirarchial. Especially helpful if you have a ton of pages /cpt's with heiararchial set to true. It uses a cookie to save the expand/collapse status of your pages.

This is loosely based on Collapse Sub-Pages by Dan Dietz, which broke with the 3.0 upgrade due to UI changes and hasn't been updated. I've had to rewrite the jQuery to make it work with 3.x versions.

Because this is a jQuery, it's possible that they could make additional changes that would break it. I'll do my best to stay on top of it, but let me know if it stops working.


<h3>Plugin in your Language</h3>
From version 2.3 our plugin supports internationalization, which means you can have plugin in your specified language.

It's currently available in US English ,UK English, Chinese, Telugu , Serbian.

<h3>Support us by Translating</h3>
Go <a href="https://translate.wordpress.org/projects/wp-plugins/admin-collapse-subpages">here</a> and translate into your language.

<h3>Special Thanks</h3>
We specially thank <a href="http://www.webhostinghub.com/" target="_blank">Borisa Djuraskovic</a> for translating this plugin in Serbian Language.

What's new in Version 2.0 ?

1)Fix en-queuing of scripts .
2)Expand all , Collapse all links appear only on the pages list not on every list (like plugins ,posts etc.,)
3)Updated jQuery.cookie.js to 1.4.0

== Installation ==

1. Download, unzip, and upload the 'admin-collapse-subpages' folder along with all its files to the '/wp-content/plugins/'' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit your Pages admin page and notice the lovely +/- buttons.

== Frequently Asked Questions ==

= Why is there a delay after I use "Quick Edit"? =

The WordPress Quick Edit functionality is a little buggy in my opinion. To make a long story short, this delay is so that WordPress can complete the edit(and any possible parent changes) before refreshing the expand/collapse status.
I'd recommend not using Quick Edit to change parent/child pages at all. It often doesn't refresh any changed rows properly.

== Screenshots ==

1. Example Edit Page menu.
2. Same menu with a group of subpages minimized.
3. Same menu completely minimized.
4. Expand/collapse all buttons at the top of the page.

== Changelog ==

= 2.4 =

** New

* Added support for category pages

** Changed

* Used localStorage instead of jquery.cookie
* Used dashicons for plus/minus instead of images

= 2.1 =
* Added support custom post types which are hierarchical

= 2.0 =
* Fixed bug - Adding expand/collapse links to all list tables
* Updated jquery.cookie.js to 1.4.0
* Enhanced loading of scripts and styles

= 1.0 =
* Initial version of the plugin

== Upgrade Notice ==

* Nothing to worry
