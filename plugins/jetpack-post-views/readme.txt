=== Jetpack Post Views ===
Contributors: straker503
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPUDV9EYETJYJ
Tags: jetpack, post views
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display your most popular posts using Jetpack stats.

== Description ==

**NOTICE: I am no longer maintaining this plugin. Jetpack does not provide the necessary functionality to reliably and easily update post views for more than 500 posts. I am tired of trying to hack a solution that ultimately doesn't work. If anyone wants to continue to maintain the plugin feel free to download the  [code](http://wordpress.org/plugins/jetpack-post-views/developers/).**

A plugin that displays your most popular posts using Jetpack stats.

Jetpack Post Views is a plugin that lets you integrate Jetpack stats into your site. Jetpack is a great plugin that lets you track information about your blog, but it doesn’t give you access to this information so you can display it to your visitors. The most common information users wish to have access to are the number of views for a post.

Jetpack Post Views gives you access to this information. This plugin adds a widget that lets you display your top posts by views according to Jetpack stats. As an added bonus, this plugin adds this information to the post meta of each post, allowing you to display those stats anywhere on your site.
a
== Installation ==

1. Upload `jetpack-post-views.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Upon activation, stats will be downloaded automatically and entered into the post meta for each post. **This process takes time!** Depending on how many posts you have, this can take up to 5 minutes, so be patient!

**NOTE:** If the plugin does not work, go to the settings page and try entering in your WordPress API Key.

To find your API key, log into https://apikey.wordpress.com/. Enter this key into the “WordPress API key” field in the Jetpack Post Views Settings page and click “Save Changes.”

To display the total views for a post anywhere on your site, just add the following code to your files (such as single.php):

`<?php echo get_post_meta( $post->ID, 'jetpack-post-views', true ); ?>`

To display views for a day, week, month, or year, add the appropriate code:

`<?php echo get_post_meta( $post->ID, 'jetpack-post-views-Day', true ); ?>`
`<?php echo get_post_meta( $post->ID, 'jetpack-post-views-Week', true ); ?>`
`<?php echo get_post_meta( $post->ID, 'jetpack-post-views-Month', true ); ?>`
`<?php echo get_post_meta( $post->ID, 'jetpack-post-views-Year', true ); ?>`

Stats are updated hourly only if the plugin is active.

== Frequently asked questions ==

= Help! My posts aren't updating! =

There are many factors that could go wrong that would prevent a post from updating. Before you submit a support ticket, please ensure that:

1. the plugin can access Jetpack - go to the Jetpack Post Views Settings page and make sure that at least one of the connections is green
1. the post is getting views - go to your Jetpack Stats page and make sure that the post is actually getting views
1. an entire day has gone by with no updates - Jetpack Post Views only updates once every hour, so letting a day go by will make sure that the plugin truly isn't working

If the post still isn't updating, please open a support ticket and include the following information along with a description of what the problem is. This will help me try to identify where the problem could be:

1. Is the post a custom post type?
1. Does the post belong to a custom category/tag?
1. Does your site have over 500 posts?
1. In the Jetpack Post Views Settings page, which of the 3 connections are green?

= How can I display the top posts in my template? =

Use the function 'JPV_display_top_posts()'

*Usage*
`<?php if ( function_exists('JPV_display_top_posts') ) { JPV_display_top_posts( $args ); } ?>`

*Default Usage*
`<?php $args = array(
       	 'days'                   => '-1',
         'limit'                  => '5',
         'exclude'                => '',
         'excludeCustomPostTypes' => false,
         'displayViews'           => false ); ?>`
*Parameters*

**days** - (*string*) The number of days of the desired time frame. '-1' means unlimited.

**limit** - (*string*) The number of posts to display. '-1' means unlimited. If days is -1, then limit is capped at 500.

**exclude** - (*string*) A comma-separated list of Post IDs to be excluded from displaying.

**excludeCustomPostTypes** - (*boolean*) Excludes custom post types from displaying.

**displayViews** - (*boolean*) Displays the post views.

**NOTE** This function only works if the function `stats_get_csv()` exists. If this function is not working probably, it is probably due to the `stats_get_csv()` function not returning the needed results.

= How can I display the top posts in my posts? =

Use the shortcode '[jpv]'

The shotcode uses the same parameters as the 'JPV_display_top_posts()' function

== Screenshots ==

1. Jetpack Post Views Widget options.
2. Jetpack Post Views Sidebar that displays top posts for the site.
3. Jetpack Post Views Sidebar with number of views displayed.
4. Jetpack Post Views settings page.

== Changelog ==

= 1.1.0 (2013-12-10) =
* Fixed the upgrade script so that it actually runs
* Fixed a bug in the uninstall script

= 1.0.9 (2013-12-8) =
* Filters for post type and category now work for all Time Intervals
* Fixed the Total View column error in the "All Posts" page for posts that were not published
* Added an option in the Settings page to display Total Views in the "All Posts" page (defaults to off)
* Added post meta for each time interval (Day, Week, Month, Year)
* Widgets set to display Popular Posts with Time Interval: Day should no longer disappear when stats reset (although post output will likely be random)
* Can now disable the use of the Jetpack get_stats_csv function to get popular posts using either Blog URI or Blog ID id desired

= 1.0.8 (2013-10-4) =
* Removed cached widget output as it was causing too many problems

= 1.0.7 (2013-09-28) =
* Added a "Total Views" column to the Posts admin page
* Added different display types to the widget
* Cached the widget output
* Allowed custom post types to update and display
* Added filters for post type and category to widget output (feature only works for Time Interval of Unlimited)

= 1.0.6 (2013-05-29) =
* New post now update properly

= 1.0.5 (2013-05-21) =
* Fixed posts not properly displaying post views
* Fixed database access to tables using 'wp_' table prefix to use prefix defined in wp-config.php
* Added shortcode

= 1.0.4 (2013-03-14) =
* Added the `JPV_display_top_posts()` function to display top posts in a template
* Added widget options to exclude posts by ID and to display a different time frame

= 1.0.3 (2013-02-10) =
* Plugin can now access Jetpack stats without needing a WordPress API Key first. (Special thanks to topher1kenobe for helping me with this)
* Added a settings page to help those unable to access stats normally enter in the needed information to access the stats via the Jetpack API

= 1.0.1 (2013-01-21) =
* Reduced number of API calls made
* Considerably sped up process of adding/updating post meta data to each post
* Added security to widget
* Added uninstall.php file

= 1.0.0 (2013-01-19) =
* Public beta released