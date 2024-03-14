=== Simple Blog Stats ===

Plugin Name: Simple Blog Stats
Plugin URI: https://perishablepress.com/simple-blog-stats/
Description: Provides shortcodes and template tags to display a variety of statistics about your site.
Tags: stats, statistics, analytics, numbers, blog
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.6
Tested up to: 6.5
Stable tag: 20240303
Version:    20240303
Requires PHP: 5.6.20
Text Domain: simple-blog-stats
Domain Path: /languages
License: GPL v2 or later

Displays a wealth of useful statistics about your site. Display total number of posts, pages, categories, tags, and much more.



== Description ==

[Simple Blog Stats](https://perishablepress.com/simple-blog-stats/) (SBS) provides shortcodes and tags to display site stats in posts, pages, and anywhere in your theme.


**Display Statistics**

* Total number of posts
* Total number of pages
* Total number of drafts
* Total number of comments
* Total number of media files (any types)
* Number of comments in moderation
* Number of approved comments
* Number of registered users
* Number of categories
* Number of tags
* Number of words for any post
* Number of words for all posts
* Display all blog stats in a list
* Display number of posts for any Custom Post Type
* Display list of counts for all Custom Post Types
* Display current number of logged-in users
* Display number of logged-in users via Dashboard widget

__NEW!__ Display number of words in any custom field


**Plugin Features**

* Uses caching for better performance
* Provides shortcodes to display stats in Posts and Pages
* Provides template tags to display stats anywhere in your theme
* Configure text/markup to appear before/after each shortcode
* Built with the WP API for optimal performance and security
* Provides slick settings screen with toggling panels
* Provides option to restore default plugin settings
* Displays your stats with clean, valid markup
* Works with or without Gutenberg Block Editor
* Plugin is regularly updated and "future proof"
* Display list of stats via Dashboard widget


**More Statistics**

* Display date of most recent site update
* Display list of recent posts (configurable)
* Display list of recent comments (configurable)
* Display number of users per role (configurable)
* Display all blog stats in a nicely formatted list
* Configure all shortcodes via the plugin settings
* Eat a bowl of ice cream :)


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

Simple Blog Stats is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).


**Support development**

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)
* [Wizard's SQL Recipes for WordPress](https://books.perishablepress.com/downloads/wizards-collection-sql-recipes-wordpress/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect WordPress to Google Analytics
* [Simple Ajax Chat Pro](https://plugin-planet.com/simple-ajax-chat-pro/) - Unlimited chat rooms
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thank you! :)



== Installation ==

### How to install ###

1. Upload the plugin to your blog and activate
2. Visit the settings to configure your options

[More info on installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)



### How to use ###

Visit the plugin settings page to configure your shortcodes. Then copy/paste the shortcodes in any Post, Page, or Widget to display your stats. To display your stats anywhere in your theme template, visit the "Template Tags" section of the settings page.



### Meet the shortcodes ###

Visit the plugin settings page for a complete list of shortcodes. There you may customize the output of each shortcode. Here is a list of all SBS shortcodes:

	[sbs_posts]                = number of posts *
	[sbs_posts_alt]            = number of posts *
	[sbs_pages]                = number of pages
	[sbs_drafts]               = number of drafts
	[sbs_comments]             = number of comments *
	[sbs_moderated]            = moderated comments
	[sbs_approved]             = approved comments *
	[sbs_users]                = number of users
	[sbs_cats]                 = number of categories
	[sbs_tags]                 = number of tags
	[sbs_tax tax="tax_name"]   = number of taxonomy terms
	[sbs_tax_posts ...]        = number of posts for tax term(s) *
	[sbs_word_count]           = number of words in post *
	[sbs_word_count_all]       = number of words in all posts (all post types) *
	[sbs_word_count_custom]    = number of words in custom field *
	[sbs_updated]              = site last updated *
	[sbs_latest_posts]         = displays recent posts
	[sbs_latest_comments]      = displays recent comments
	[sbs_roles]                = number of users per role *
	[sbs_cpts_count]           = list of CPT counts
	[sbs_cpt_count cpt="post"] = number of any post type
	[sbs_blog_stats]           = displays list of blog stats
	[sbs_logged_users]         = number of logged-in users *
	[sbs_media_count]          = number of media files *
	[sbs_reading_time]         = estimated reading time
	
	* See notes below.


**[sbs_posts]**

The `[sbs_posts]` shortcode accepts several attributes that can be used to customize your post stats:

	cat           - limit by category    (default: empty = all cats)
	tag           - limit by tag         (default: empty = all tags)
	type          - limit by post type   (default: empty = post)
	status        - limit by post status (default: empty = publish)
	exclude       - exclude post IDs     (comma separated list of post IDs)
	exclude_cat   - exclude categories   (comma separated list of category IDs)
	number_format - thousands separator  (default: comma, like 1,234)

So by default, `[sbs_posts]` with no attributes will display the total number of published posts in any category or tag. 

Here is an example that makes use of the attributes:

	[sbs_posts cat="sci-fi" tag="sequel" type="movie" status="draft"]

So this will display all drafts of the custom post type "movie" that are in the "sci-fi" category and tagged as "sequel".

More information about the possible values for these attributes:

* [cat](https://developer.wordpress.org/reference/classes/wp_query/#category-parameters)
* [tag](https://developer.wordpress.org/reference/classes/wp_query/#tag-parameters)
* [type](https://developer.wordpress.org/reference/classes/wp_query/#post-type-parameters)
* [status](https://developer.wordpress.org/reference/classes/wp_query/#status-parameters)


**[sbs_posts_alt]**

The `[sbs_posts_alt]` shortcode is for sites with __LOTS__ of posts (like 10,000+). It is not as flexible as `[sbs_posts]`, but does provide a couple of attributes:

	[sbs_posts_alt type="page" status="draft"]

You can change the `type` and `status` of the posts that should be counted. Again, this shortcode should be used only for sites with extreme numbers of posts.


**[sbs_updated]**

The `[sbs_updated]` shortcode outputs the date and time of the latest post. It accepts two attributes, `format_date` and `format_time`, that enable you to customize the format of the output date and time, respectively. Here are some examples to show how it works:

	[sbs_updated format_date="Y/m/d"] = custom format for date, default format for time
	[sbs_updated format_time="H:i:s"] = custom format for time, default format for date
	
	[sbs_updated format_date="Y/m/d" format_time="disable"] = custom format for date, disable time output
	[sbs_updated format_date="Y/m/d" format_time="H:i:s"]   = custom format for both date and time

For the attribute values, you can use any valid PHP date/time format. Check the [PHP docs](https://www.php.net/manual/en/datetime.format.php) for a complete list of available formats.


**[sbs_comments]**

By default, the `[sbs_comments]` shortcode displays the total number of comments for all posts on your site. To display the number of comments only for a specific category, add the `cat` attribute, like so:

	[sbs_comments cat="1"]

You can change the category ID to display number of comments for any category.


**[sbs_approved]**

By default, the `[sbs_approved]` shortcode displays the total number of comments that have been approved/published on your site. This shortcode provides an optional attribute to specify the number format:

	[sbs_approved number_format=","]

You can change the number format to whatever makes sense for your site.


**[sbs_tax_posts]**

The `[sbs_tax_posts]` shortcode displays the number of posts that belong to a specific post type and taxonomy term(s). Here is an example:

	[sbs_tax_posts tax="taxonomy" terms="term-1, term-2, term-3" type="custom-post-type"]

Then change the attribute values to match your taxonomy, terms, and post type, respectively.


**[sbs_word_count]**

The `[sbs_word_count]` shortcode displays the number of words in post content. By default it displays number of words in the current post. Or you can specify any post ID:

	[sbs_word_count]         // displays word count of current post
	[sbs_word_count id="1"]  // displays word count of post with ID = 1

To display the word count for __all posts__ (any post type), use the shortcode `[sbs_word_count_all]`. Check the FAQs to customize the post type for this shortcode.


**[sbs_word_count_custom]**

The `[sbs_word_count_custom]` shortcode displays the number of words in any custom field. It requires a post ID and name of a custom field. Here is an example:

	[sbs_word_count_custom post_id="12" key="author-bio"]

So if post ID = 12 has a custom field named "author-bio", this shortcode will return the number of words contained in the custom field (not the post).


**[sbs_roles]**

The `[sbs_roles]` shortcode displays a list of all user roles and corresponding number of users. To display the number of users for a specific role, add the `role` attribute. Examples:

	[sbs_roles]                // displays list of roles and number of users
	[sbs_roles role="author"]  // displays number of users for specified role
	[sbs_roles role="all"]     // displays list of roles and number of users

The `role` attribute accepts a value of `all` or any valid user role.


**[sbs_cpt_count]**

The `[sbs_cpt_count]` shortcode displays the number of posts for the specified post type. For example, if you want to display the number of posts for a post type called `food`, would like this:

	[sbs_cpt_count cpt="food"]

The default post type for this shortcode is `post`. The `[sbs_cpt_count]` shortcode accepts three attributes that can be used to customize:

	cpt           - specifies the post type
	txt           - specifies custom label/name
	number_format - thousands separator  (default: comma, like 1,234)
	
By default, `[sbs_cpt_count]` with no attributes will display the total number of published posts. 


**[sbs_logged_users]**

The `[sbs_logged_users]` shortcode can be used to display the number of currently logged-in users. This shortcode does not have any attributes, but does provide a widget that displays the current logged-in user count on the WP Dashboard.


**[sbs_media_count]**

The `[sbs_media_count]` shortcode can display stats for any media type(s). Here are some examples:

	[sbs_media_count type="all"]          = displays number of all media files
	[sbs_media_count type="image"]        = displays number of image files
	[sbs_media_count type="video"]        = displays number of video files
	[sbs_media_count type="pdf,doc,docx"] = displays number of PDF, DOC, and DOCX files
	[sbs_media_count type="mp3"]          = displays number of MP3 files
	[sbs_media_count]                     = displays number of all media files



### Customize output ###

Most of the shortcodes display only a number. To customize the number with your own text, visit the plugin settings. There you can add any text or markup that should be displayed before/after each shortcode.

There are three shortcodes that output some default text along with the stats number:

	[sbs_roles]
	[sbs_cpt_count]
	[sbs_media_count]

So to customize the text for these shortcodes, you can add a `txt` attribute and set the value to whatever you want, for example:

	[sbs_roles txt="Whatever you want"]
	[sbs_cpt_count txt="Whatever you want"]
	[sbs_media_count txt="Whatever you want"]

Or if you want to just disable the extra text and display only the number, set the `txt` attribute to `null`, like so:

	[sbs_roles txt="null"]
	[sbs_cpt_count txt="null"]
	[sbs_media_count txt="null"]

That way only the number will be displayed without any other text.



### Like the plugin? ###

If you like Simple Blog Stats, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/simple-blog-stats/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



### Upgrades ###

To upgrade SBS, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.



### Restore Default Options ###

To restore default plugin options, either uninstall/reinstall the plugin, or visit the plugin settings &gt; Restore Default Options.



### Uninstalling ###

Simple Blog Stats cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen. Any shortcodes that you have added to your posts and pages will __not__ be removed. Likewise any template tags that have been added to your theme template will __not__ be removed.



== Upgrade Notice ==

To upgrade SBS, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 



== Screenshots ==

1. Simple Blog Stats: Settings Screen (panels toggle open/closed)

More screenshots and information available at the [SBS Homepage](https://perishablepress.com/simple-blog-stats/).



== Frequently Asked Questions ==

**How to limit/customize the number of counted posts?**

The plugin provides a filter hook for customizing the total number of posts that are displayed using the `[sbs_posts]` shortcode. To do it, add the following snippet to your theme functions.php file, or add via [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

	function sbs_get_posts_limit_custom($limit) { return 100; }
	add_filter('sbs_get_posts_limit', 'sbs_get_posts_limit_custom');

No changes need made; simply edit the `100` to whatever is desired and done.


**How to customize post status for [sbs_updated] shortcode?**

By default the `[sbs_updated]` shortcode includes only posts that have "publish" post status. To customize the post status, add the following code to your theme (or child theme's) functions.php file, or add via [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

`function sbs_updated_post_status($status) { 

	return 'publish,draft,pending'; // whatever post statuses
	
}
add_filter('sbs_updated_post_status', 'sbs_updated_post_status');`

Notice where it says `publish,draft,pending`, that determines which post statuses are included. You can change/edit as needed.


**How to customize post types for [sbs_updated] shortcode?**

By default the `[sbs_updated]` shortcode includes only posts (post type = post). To customize the post type, add the following code to your theme (or child theme's) functions.php file, or add via [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

`function sbs_updated_post_type($type) {
	
	return array('post', 'book'); // whatever post types
	
}
add_filter('sbs_updated_post_type', 'sbs_updated_post_type');`

You can edit the return array with whatever post types are required.


**How to change the separator for numbers?**

Currently the plugin does not provide a way to change from dots to commas for numerical values. For a simple JavaScript workaround, check out [this post](https://wordpress.org/support/topic/dots-instead-of-comma/).


**How to remove commas from the media count?**

By default, the plugin formats long numbers with commas. To remove/disable the commas for the `[sbs_media_count]` shortcode, add the following code to your theme (or child theme) functions.php, or add via [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

`function sbs_include_commas($enable) { return false; }
add_filter('sbs_include_commas', 'sbs_include_commas');`

Save changes and done. Note: currently this works only with the shortcode, `[sbs_media_count]`.


**How to disable the word-count shortcode?**

For sites with many many posts and/or posts with LOTS of words. Depending on server capacity, PHP may time out when trying to go through and count everything. As a workaround solution, it's possible to disable the "all word count" shortcode by adding the following code to your theme functions.php, or add via [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

`function sbs_word_count_all_disable($disable) { return true; }
add_filter('sbs_word_count_all_disable', 'sbs_word_count_all_disable');`


**How to change post type for [sbs_word_count_all]?**

By default, the shortcode `[sbs_word_count_all]` counts words in posts from any/all post types. To customize the post type, add the following code to your theme functions.php, or add via [custom plugin](https://digwp.com/2022/02/custom-code-wordpress/):

	function sbs_word_count_all_post_type($type) {
		
		return array('post', 'page', 'movie', 'book'); // whatever post types
		
	}
	add_filter('sbs_word_count_all_post_type', 'sbs_word_count_all_post_type');

You can customize the post types in the array as desired.


**Got a question?**

Send any questions or feedback via my [contact form](https://plugin-planet.com/support/#contact). Thanks! :)



== Changelog ==

If you like Simple Blog Stats, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/simple-blog-stats/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**20240303**

* Adds `number_format` attribute for `[sbs_cpt_count]`
* Updates plugin settings page
* Updates default translation template
* Improves plugin docs/readme.txt
* Tests on WordPress 6.5 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/simple-blog-stats.txt](https://plugin-planet.com/wp/changelog/simple-blog-stats.txt)
