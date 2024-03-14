=== Plugin Name ===
Contributors: niraj.shah
Donate link:
Tags: posts, list
Requires at least: 2.7
Tested up to: 5.7
Stable tag: 0.1

Shortcode to display posts as a bulleted list. You can also use parameters order and orderby to sort the list.

== Description ==

This simple shortcode plugin allows you to list posts as a bulleted list linking to the full article. You can limit
the list to posts from a specific category and you can select the number of posts to show. The `order` and
`orderby` options lets you sort the list too.

Usage is simple. Create a page or post and use the code `[showposts category="2" num="4"]` - this will show
all 4 posts from category 2. Another example `[showposts category="1" num="-1" orderby="date" order="DESC"]`
shows all the posts from category 1 ordered by the date (newest post first).

By default, only 5 posts are shown (unless you use the `num` option) and sorting in by date (oldest to newest).

== Installation ==

1. Upload `show-posts-shortcode.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[showposts category="2" num="4"]` in your post or pages

== Example ==

You can see two examples of the code working at the following links:

* http://www.xtremeps3.com/firmware-history/
* http://www.xtremeps3.com/faqs/

== Usage ==

The shortcode is `[showposts]`

You can use the following parameters to customize what is displayed (all are optional):

`[showposts category="2" num="4" order="DESC" orderby="date"]`

Set `order` to one of the following:

* `author` - Sort by the numeric author IDs.
* `category` - Sort by the numeric category IDs.
* `content` - Sort by content.
* `date` - Sort by creation date.
* `ID` - Sort by numeric post ID.
* `menu_order` - Sort by the menu order. Only useful with pages.
* `mime_type` - Sort by MIME type. Only useful with attachments.
* `modified` - Sort by last modified date.
* `name` - Sort by stub.
* `parent` - Sort by parent ID.
* `password` - Sort by password.
* `rand` - Randomly sort results.
* `status` - Sort by status.
* `title` - Sort by title.
* `type` - Sort by type.

`orderby` can be one the following:

* DESC
* ASC
