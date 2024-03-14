=== Plugin Name ===
Contributors: harman79
Tags: id, post id, page id, list id, category, taxonomy, post type, custom post, template, conditional tags, WP list table, admin
Requires at least: 3.8
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get list of post IDs by taxonomy, post-type, template. Add ID column in posts, media, taxonomies, users screens with option to copy selected IDs.

== Description ==

This plugin is all about IDs and offers a twofold functionality.  

a) Adds a custom admin options page with WP List Tables that allow you to get a delimited list of post/page IDs by taxonomy / tag or post type. Listing of IDs by template only works for pages, not posts.

This can be particularly useful when for example you are working with WP conditional tags, where it is common practice to create a delimited list of post or taxonomy IDs. If there are many posts or taxonomies required, the process of finding each ID one by one can get a bit annoying and time consuming.  

This plugin makes it easier for you. It creates delimited lists of post/page IDs based on taxonomy/tag, post type, template or user selection from WP list-tables. 

b) Adds a column displaying IDs in the posts/pages, media, taxonomies and users screens. It also offers the option to automatically copy the IDs of selected items.

For a clearer picture please have a look in the Screenshots section. 

Features:

- Works for built in and custom post types and taxonomies.

- Works fine with Woocommerce and WPML.

- Provides a settings page to control functionality.

- An auto-copy to clipboard function is also provided.


Note: The "Copy Selected IDs" button added in the WP built-in post/page and taxonomy list edit screens uses Javascript and works for all major browsers (Note: IE 10+). The auto copy to clipboard function is not supported by Safari, but you still get the selected IDs in the textbox, so you can copy them manually.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/id-arrays` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->ID arrays screen to configure the plugin

== Frequently Asked Questions ==

= How to report a bug? =

Post in the official wordpress ID Arrays plugin page.

== Screenshots ==
1. List of post/page IDs by taxonomy (plugin options page) 
2. List of post/page IDs by post type (plugin options page)
3. List of page IDs by template (plugin options page)
4. IDs column, copy selected IDs button and textbox (example for posts)
5. IDs column, copy selected IDs button and textbox (example for taxonomies)
6. ID arrays plugin settings page

== Changelog ==

= 2.1.2 =
* Added information about number of IDs found following a user query.

= 2.1.1 =
* Included attachments (media) in post_type queries; removed column screen options as they were pretty useless.

= 2.1 =
* Added extra column displaying IDs for posts/pages, media, taxonomies and users

= 2.0.2 =
* Fixed a bug that was causing errors with wp-list-table for WP v4.2.x

= 2.0.1 =
* Fixed a bug for max number of posts returned.

= 2.0 =
* Major update; added options pages to get IDs of posts/pages by taxonomy, post type or template.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 2.1.2 =
* Tested OK for WordPress 4.7