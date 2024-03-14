=== Plugin Name ===
Contributors: mattdu
Tags: byline, authors, coauthors, multi-author, multiple authors, publishing, taxonomy
Requires at least: 3.0.1
Tested up to: 3.5
Stable tag: 0.25
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Solves the co/multi-author problem without modifying the theme. Uses a custom taxonomy, "Byline," that replaces the Display Author. 

== Description ==

This plugin uses the custom taxonomy features of WordPress to create "bylines" — essentially, tags representing authors who contributed to a post. The differentiating feature of this plugin is that it should not require any modifications to your theme files (assuming it is already displaying the post author). 

This plugin would be useful for publishing situations where the majority of your content is from contributors who you don't necessarily want to have access to your back-end. It still conveys the appearance that the authors are members of the site (it provides a built-in archive page for each Byline). If your theme displays tag descriptions, you could use the Byline description field to identify guests vs. "staff" writers or provide other author information. 

You can see an example of this plugin at work at http://thedailycougar.com


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `byline.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add names directly in the post editor or the admin menu for "Bylines" just like you would for a regular tag.

== Frequently Asked Questions ==

= If you have questions please contact me. =

Visit me at http://mattdulin.com/byline



== Changelog ==

= 0.25 =
* Fixed code to allow byline to appear in widgets.
* Fixed code preventing byline from appearing in posts admin column.


= 0.1 =
* Plugin created. 
