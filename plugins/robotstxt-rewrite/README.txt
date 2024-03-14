=== Robots.txt rewrite ===
Contributors: eugenbobrowski
Donate link: http://atf.li/
Tags:  crawler, crawlers, robot, robots, robots.txt, editor, google, search, seo, spiders
Requires at least: 4.7
Tested up to: 4.7.2
Stable tag: 1.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provide the easy managment of your robots.txt from admin side. It propose you the advanced then standard robots.txt content too.

== Description ==

Plugin provide to help search engines to indexing site correctly.

A simple plugin to manage your robots.txt. Plugin donn't create the file or edit it. This plugin edit WordPress output of robots.txt content. And get you a easy and usable interface to manage it.

**Features**

* Drag-n-drop robots.txt paths
* Changing `blog_public` option form plugin settings page
* Site map field for robots.txt
* Robots.txt physical file checking.

![Screenshort](https://raw.githubusercontent.com/EugenBobrowski/robotstxt-rewrite/master/screenshot-1.png)

== Installation ==

1. Upload `robots-txt-rewriter.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings / Robots.txt Options. Check the paths and save it. So the settings will be active.

== Frequently Asked Questions ==

= How to remove "created by" text =

Use the `robots_txt_rewrite_footer` filter. Paste the following text to your theme or child theme  `functions.php` file.

`add_filter('robots_txt_rewrite_footer', '__return_empty_string');`

== Screenshots ==

1. Robots.txt Options page
2. robots.txt

== Changelog ==

= 1.6.1 =
*Release Date - 21st February, 2017*

* Add required version notice.

= 1.6 =
*Release Date - 21st February, 2017*

* Add custom content fields.

= 1.5 =
*Release Date - 17th January, 2017*

* Add Crawl-delay rule.

= 1.4 =
*Release Date - 18th September, 2016*

* Fix PHP7 capability. Update HTML helper for admin.

= 1.3 =
*Release Date - 14th July, 2016*

* Bug fix `blog_public` option saving
* Add site map field to show
* Add notice if you have not saved `robots_options`

= 1.2 =
*Release Date - 6th June, 2016*

* Bug fix row repeater
* Add created by text in the end of robots.txt content
* Add script to open robots.txt content in new window for more usability

= 1.1 =
*Release Date - 4th May, 2016*

* Add robots.txt physical file checking.
* Applying this plugin options only if it was saved.
* Plugin description change.

= 1.0 =
*Release Date - 1st May, 2016*

* Initial

== Upgrade Notice ==

= 1.0 =
Initial.
