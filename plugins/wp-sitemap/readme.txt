=== WP Sitemap ===
Tags: sitemap, html sitemap, table of contents, seo
Requires at least: 2.9
Tested up to: 2.9
Stable tag: 1.0

A HTML sitemap with both post and pages. Easy to implement, just add a shortcode tag in a page.

== Description ==

Place a shortcode into a post or a page (page recommended) and your sitemap will apear as a list with page numbers.

= Shortcode simple example =

[wp_sitemap]

= Features =
* Support for both posts and pages
* Support for page numbers and option to place it on top or bottom
* Support for include and exclude pages / posts
* Support for disable posts / pages
* Support for order by and sort order
* No data added to the database
* No settings page added into admin
* Sitemap settings are added through a shortcode added in a page
* Wordpress built in functions are used to make a very small code

= Shortcode advanced example =

[wp_sitemap pages="false" exclude="9,21,34"]

= Parameters =

exclude

* (string) Define a comma-separated list of Page IDs to be excluded from the list (example: 'exclude=3,7,31'). There is no default value.

include

* (string) Only include certain Pages or Posts in the sitemap. Like exclude, this parameter takes a comma-separated list of Page IDs. There is no default value.

posts

* (string) Include Posts. Valid values:
 * true - Posts are included. (Default)
 * false - Posts are not included.

pages

* (string) Include Pages. Valid values:
 * true - Pages are included. (Default)
 * false - Pages are not included.

sort_column

* (string) Sorts the list of Posts and Pages in a number of different ways. The default setting is sort newest first by Post / Page date.
 * 'post_date' - Published Post / Page date. (Default)
 * 'post_title' - The Post or Page title.
 * 'post_modified' - The updated Post or Page date.
 * 'comment_count' - The number of comments on a Post or Page.
 * Other fields in the Posts table.

sort_order

* (string) Change the sort order of the list of Pages (either ascending or descending). The default is ascending. Valid values:
 * 'ASC' - Sort from lowest to highest.
 * 'DESC' - Sort from highest to lowest. (Default)

paging_position

* (string) The position of the page numbers. Valid values:
 * 'top' - Add page numbers above the sitemap list
 * 'bottom' - Add the page numbers below the sitemap list (Default)
 * 'both' - Add page numbers above and below the sitemap list
 
post_count

* (string) Sets the number of Pages and Posts to display. The default is 50.

= Shortcode default settings =

If you don't know what this means, just ignore this section.

* exclude => 0
* include => 0
* posts => "true"
* pages => "true"
* sort_column => "post_date"
* sort_order => "DESC"
* paging_position => "bottom"
* post_count => 50

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the folder 'wp-sitemap' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the shortcode [wp_sitemap] to a post or a page (page rekommended)
1. Look at the post / page. Done!

== Frequently Asked Questions ==

= Where is the settings page? =

There are none. You can add settings into the shortcode.

== Screenshots ==

1. Maybe soon. It's just a list of posts and pages with page numbers.

== Changelog ==

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0 =
This is not an upgrade, it's an initial release.