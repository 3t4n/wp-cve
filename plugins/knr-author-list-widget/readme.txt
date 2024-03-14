=== Axact Author List Widget ===
Contributors: k_nitin_r,marcusbs
Tags: author, list, sidebar, widget, ordered, sorted
Requires at least: 3.0
Tested up to: 5.8.2
Stable tag: 3.1.1

Displays a list of authors, contributors, editors, and administrators on the blog as an ordered list, unordered list, or a dropdown list. You can use the ordered list to display a list of 'top authors' on the blog. Tweaked for performance and highly configurable.

== Description ==

The Axact Author List Widget wordpress plugin, by Yumna Tatheer, displays a list of authors, and editors 
on the blog as an ordered list, unordered list, or a dropdown list. You can use the ordered list to display a list of 'top authors' on the blog. You can set a custom order of authors by simple dran n drop, set urls where this widget should not show.
**This plugin has been re-written and all security threats removed, its now compatible with recent version of wordpress. In case update fails, please delete old plugin and install the currect one again. You might need to adjust your styles according to new class names.**

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/axact-author-list-widget/` directory
(this can be done automatically via the WordPress Plugin Browser/Installer interface)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Through the 'Widgets' sub-menu (under 'Appearance' menu), add the widget to your sidebar

== Screenshots ==

1. Axact Author list on the classic WordPress theme
2. Axact Author List in the list of widgets after activating the plugin. There are more options not shown in the screenshot.
3. Axact Author list Widget options in the Appearance menu
4. Custom Author Ordering

== Changelog ==

= 3.1.1 =
* Compatible with PHP 8

= 1.0 =
* Author listing - displays a list of all authors and editors. This is an improvement over plugins that use the wp_list_authors especially if you've got a lot of subscribers on your blog. This was orignally developed by Nitin Reddy, but didn't work with latest wordpress version, so I am improving it from now on.


== Notes ==

= General Info =

Send any queries, comments, feedback, contributions to developer.yumna (a) gmail.com

= Other Notes =

The custom author order interface under settings requires WordPress 2.6 or higher but the other features work just fine on WordPress 2.5. This is due to the inavailability of the jQuery library on pre-2.6 versions.

'Show as dropdown' overrides 'Show as ordered list'. This may seem counter-intuitive but admin interface needs a bit of cleanup. I'll soon make it simpler to use.

= Tips =

* To improve performance by reducing the number of full table scans in the database, create an index on the column "axact_author_order" in the table "(wp_prefix)users".
* You can style the author list with CSS by defining the CSS classes in the markup options of the widget. Using a text-align left for the list items with a float right for the post count aligns the post count of all the items in the list vertically. UL/OL with CSS is so much more flexible than HTML tables!! :-)
