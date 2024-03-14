=== Plugin Name ===
Contributors: Tubal
Donate link:
Tags: post, sort, order_by, reorder, query, order
Requires at least: 2.5
Tested up to: 3.4
Stable tag: trunk

Sort posts on-the-fly without making a new SQL query

== Description ==

A really simple and lightweight plugin (73 lines of code - comments included) to sort posts on-the-fly without making a new query to the database (improves performance).

= Features: =

* Supports **all `order_by` and `order` values of the [query_posts function](http://codex.wordpress.org/Function_Reference/query_posts#Orderby_Parameters)** except `meta_value` and `meta_value_num` (which require a database query).
* Supports **changing the order of all types of posts**, including custom post type posts and custom post type "archive" posts.

= Documentation =

This plugin adds the function `sort_query_posts_by(string $order_by [, string $order])` to the global context.
The second `$order` parameter is optional. Its default value is `asc` (ascending order).

**Call this function before [the loop](http://codex.wordpress.org/The_Loop) to change how posts are ordered.**
After calling this function you can show the posts as you normally would.

You can sort posts by:

* author
* comment_count
* date
* id
* menu_order
* modified
* parent
* title

This is specially useful in two cases:

* When you need to reorder the posts returned by the query that Wordpress creates from your given URL. Custom post type "archive" posts are a great example of this case.
* When you need the posts returned by your customized query (e.g. `query_posts()`) to be shown more than once on the same page and ordered differently.

**Examples:**

`<?php sort_query_posts_by('title', 'desc'); ?>`

The example above will sort posts by their title in descending order without making a new query to the database.
This way sorting is performance friendly.

`<?php sort_query_posts_by('ID'); ?>`

The example above will sort posts by their ID in ascending order.

`<?php sort_query_posts_by('rand'); ?>`

The example above will sort posts randomly. When sorting randomly `$order` is ignored.

Plugin developed by Túbal Martín at [www.margenn.com](http://www.margenn.com).

== Installation ==

1. Upload `sort-query-posts` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the added function `sort_query_posts_by` after any wordpress query and your posts will be sorted to your needs.

== Changelog ==

= 1.1 =
* Code refactored for improved performance. Updating is recommended.