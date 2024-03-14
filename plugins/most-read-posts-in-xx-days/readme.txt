=== Most Read Posts in XX days ===
Contributors: mrbrown
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=JH5C8NEPH7M8U&currency_code=EUR&source=url
Tags: stats, hits, visits, count, posts
Requires at least: 2.6.3
Tested up to: 5.2
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin that counts and shows hits for each Post and Page in your WordPress blog.

== Description ==

Most Read Posts counts Post and Pages hits and allows you to show them in:

* The Single Post page
* The Page page
* Index and Archive pages
* The Sidebar through a configurable Widget, also with featured images

== Installation ==

1. Upload the `most-read-posts-in-xx-days` directory in your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If needed, configure Plugin Settings and the Widget
4. (optional) Place these tags in your template:

* `single.php` or `page.php` in the Loop

	`<?php ST4_single_hits(); ?>`
		
* `index.php` (and / or `archive.php` etc...), in the Loop

	`<?php ST4_hits(); ?>`

* `sidebar.php` (only if you don't use Widgets)
	
`// last 5 posts in last 30 days, show the hits, show image preview
	ST4_mostread(30, 5, 'yes', 'yes');`
`// show 10 posts from all published posts, show hits, don't show preview
	ST4_mostread(0, 10, 'yes', 'no');`
`// show 3 pages from all published pages, show hits, don't show preview
	ST4_mostread(0, 10, 'yes', 'no', 'page');`

== Frequently Asked Questions ==

= Can I show the most read Posts without the date limit? =

Yes, you can remove the date limit in the Sidebar widget settings

= Can I display Post and Page featured images? =

Yes, you can display them through Sidebar widget settings

= Can I track also Pages? =

Yes, since version 2.3.2 you can show Page hits through Sidebar widget settings

= Can I import Page and Post hits from a different plugin? =

Yes, since 2.4 version you can edit hits through the Bulk and Quick Edit screens.

== Screenshots ==

1. The Sidebar Widgets in front-end: one for Pages, one for Posts.
2. Every widget is configurable.
3. The Plugin Settings Page.
4. In the Edit Posts and Page Screen you have a column with the Hits and you can also edit them.

== Changelog ==

= 2.7.1 =
* Moved translation files in `languages` directory to comply WordPress Plugin Repo localization requirements

= 2.7 =
* Fixed PHP 7 deprecated warning about PHP 4 style constructors on MostRead Widget

= 2.6 =
* Improved custom DB table registration to avoid PHP warnings on plugin activation on some hosts
* WordPress multisite support added
* Aligned version number (2.6) in `readme.txt` and `ST4_most_read.php`

= 2.5 =
* Corrected informations in `readme.txt`

= 2.4 =
* Added a new field in the Bulk and Quick Edit screens to edit Posts and Page Hits (useful if you want to import manually hits from another stats plugin).
* Some bugs fixed
* Changes and fixes in localization files
* Added plugin screenshots
* Corrected informations in `readme.txt`

= 2.3.2 =
* Added hits count also for Pages.
* Added a sortable column with Page Hits in All Pages Administration Screen.

= 2.3.1 =
* Added a sortable column with Post Hits in All Posts Administration Screen.

= 2.3 =
* Some bugs fixed
* Added new Widget option to show featured image preview
* Added new Widget options to remove the date filter
* Added new Plugin Option to show post hits automatically
* Added new Plugin Option to use custom CSS rules
* Added new Plugin Option to set image preview custom CSS sizes
* Changes in localization files

= 2.2 =
* Code upgrade for WordPress 2.8 compatibility.
* Added new function `ST4_get_post_hits()` to get post hits outside the Loop
* Changes in localization files
* Code optimization

= 2.1 =
* First release.

== Upgrade Notice ==

= 2.3.3 =
If you use Sidebar Widgets, check Most Read settings in the Widget Page. They should be OK, but it is better to do a check.

= 2.3 =
Visit Plugin Settings Page and set a width and an height for image previews.

= 1.0 =
Delete old plugin directory and replace with the new one.
