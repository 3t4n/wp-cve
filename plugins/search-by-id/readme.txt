=== Search by ID ===
Contributors: wpkonsulent
Tags: search, id, posts, pages, custom post types, media, admin
Requires at least: 4.0
Tested up to: 5.0.2
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables the user to search by post ID using the built-in search within the control panel. Works for all kinds of posts.

== Description ==

Ever wanted to do a quick search for a post with a specific ID? The built-in search doesn't allow that. But now you can.

= Features: =
* Works for all kinds of posts (regular posts, pages, custom post types and media).
* No configuration needed.
* Doesn't add javascript or css; it has virtually no impact whatsoever.
* No front-end functionality, just back-end.
* Doesn't add any options or tables to the database.

Just a nice, clean and easy, seamless extension of the built-in search.

= How to use it: =
Simply enter an ID into the search field. If a post with that ID is found, it will show up in the search result.

You can even enter a list of IDs if you want to search multiple IDs. For instance "100, 200, 300".

== Installation ==

1. Upload the `search-by-id` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

That's right. There's no step three.

== Frequently Asked Questions ==

= What does it do? =

Whenever a search is executed, the plugin simply hooks into that query and checks if the query (the phrase you entered into the search field) is a numeric value. If it is, it modifies the query so that it also searches for post ID's (default is to only search title and content).

= I've installed and activated the plugin, but cannot find it in the admin area =

There is nothing new to be found, my friend. It hooks into the good old search input field you see in the top right corner when viewing the lists of posts (or pages, or whatever post type you may use). Just enter an ID into that field, and you will see.

= It's not working =

That's not really a very descriptive question, but I shall try to answer nonetheless ;). If you find that searching for an ID that definitely does exist does not work, it's most likely because you have another plugin installed that interferes with the same filter that this plugin uses. I'm afraid I can't do anything about that. Almost every bug report I receive is caused by an interfering plugin, so please check before you post in the support forum.

== Screenshots ==

This plugin doesn't add any configuration pages or anything like that. So there's no need for screenshots :)

== Changelog ==

= 1.3 =
* Code maintenance for five oh. No new features has been added.

= 1.2 =

* Updated for 4.1.1. Slight rewrite of the filter was necessary. Updated description and FAQ.

= 1.1 =
* Added support for searching multiple IDs. Credit goes to Ben Wise for suggesting this.

= 1.0 =
* Initial release.