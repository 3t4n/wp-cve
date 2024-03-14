=== Shortcode ===

Contributors: maxpagels
Tags: shortcode, statistics
Requires at least: 2.5
Tested up to: 4.2.1
Stable tag: trunk

Shortcode is a plugin that adds several useful shortcodes that you can use in your blog posts and pages.

== Description ==

Shortcode is a plugin that adds several useful [shortcodes](http://codex.wordpress.org/Shortcode_API) that you can use in your blog posts and pages. Updated frequently with more shortcodes.

== Installation ==

1. Upload `shortcode.php` to the `/wp-content/plugins/` directory of your WordPress installation
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Success! You're ready to start adding shortcodes to your blog posts and pages.

Using the plugin is as simple as adding any of the shortcodes found at [www.maxpagels.com/shortcodes.txt](http://www.maxpagels.com/shortcodes.txt) to your blog posts and/or pages (to make sure that the shortcodes are properly identified, use WordPress's HTML editor instead of the visual editor).

Some of the codes you can use include:

- `[postcount]` : the total number of published blog posts
- `[catcount]` : the total number of categories (those that contain one or more posts)
- `[tabcount]` : the total number of tags (those that contain one or more posts)
- `[ageindays]`: the number of days since the first blog post was published

When you are done inserting the shortcodes you want to use, save the changes to your posts and/or pages and things should start working straight away.

== Feedback ==

Like the plugin? Hate it? Want a new feature? [Send me some feedback](http://maxpagels.polldaddy.com/s/shortcode-survey)  -- no email address or registration needed.

== Changelog ==

- 0.8.1: Fixes a bug where the [ageindays], [ageinmonths] and [ageindays] shortcodes may have incorrectly returned zero
- 0.8.0: Added [wpcategories], for displaying lists of categories. See http://www.maxpagels.com/shortcodes.txt for more information
- 0.7.5: Added [ageinmonths] shortcode
- 0.7.4: No new shortcodes this time, just some code cleanup
- 0.7.3: Added [ageinyears] shortcode
- 0.7.2: Bugfix: the [wparchive] shortcode will not display incorrectly before all other post content
- 0.7.1: Added a shortcode for displaying date-based archives. See http://www.maxpagels.com/shortcodes.txt for more information
- 0.7: Added shortcodes for image count, gallery count, album count (for the NextGEN Gallery plugin). Special thanks to http://www.tiirikainen.fi for providing the code and the suggestion
- 0.6: Added British style total word count (thousands grouped with commas)
- 0.5.9: Added British style post, category, page and tag counts (thousands grouped with commas)
- 0.5.8: Added three new shortcodes for the length of the shortest post, name (title) of shortest post, and the number of days since the first post (grouped by thousands and separated with a comma sign)
- 0.5.7: Added new shortcode: totalwords counts the total number of words in published posts
- 0.5.6: Removed unnecessary code and added new shortcode: photosingallery
- 0.5.5: Added two new shortcodes: future posts & draft posts
- 0.5: Added two new shortcodes: characters per post & posts per day
- 0.4: Added shortcodes for printing the name of the longest published post and printing the length of the longest published post
- 0.3.5: Added shortcodes for average number of tags/categories per published post
- 0.3: Added comment count shortcode
- 0.2: Bugfix & added page count shortcode
- 0.1: Initial release, with shortcodes for post count, category count, tag count and blog age.
