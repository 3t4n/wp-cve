=== Plugin Name ===
Contributors: mossifer
Donate link: http://mosswebworks.com/donate/
Tags: scheduled posts, missed schedule, missed scheduled posts, missed posts
Requires at least: 4.2
Tested up to: 6.4.2
Stable tag: 6.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Checks to see if any scheduled posts have been missed. If so, it publishes them. NOTE: This plugin is meant as a stop-gap until you and your web host determine why the built-in WordPress Cron Scheduler is not working. This plugin is not designed for continuous use on large sites that have over 10k of scheduled posts or heavy traffic.

== Description ==
When a visitor loads your site, this lightweight script checks to see if any scheduled posts have been missed. If so, it publishes them immediately. 

== Installation ==
1. Go to Plugins, Add New, Upload Plugin.
2. Upload the ZIP file.
3. Activate the plugin through the 'Plugins' screen in WordPress

NOTE: Make sure that your timezone is set correctly in Settings->General.

== Frequently Asked Questions ==

= How often does it check missed posts? =

Every time someone loads your your home page or a single post/article.

= I’ve activated the plugin-and the posts are not publishing =

Make sure your time zone is set correctly. Make sure there are no plugin conflicts by turning off your other plugins, one by one. Especially caching plugins.

= The plugin is giving the site a memory error or white screen =
If your database has a significant number of scheduled posts (over 10k) or you have heavy traffic,  then you might not have enough memory allocated to WordPress. You can try adding this line in your wp_config.php file: 

	define('WP_MEMORY_LIMIT', '256M');

If you're still having memory problems, then talk to your web host about your root problem: the built-in WordPress Cron Scheduler is not publishing your scheduled posts. 


== Changelog ==

= 3.2 =
Rename plugin with company branding. Tested to WP 6.3

= 3.1 =
Fixes bug where it wasn't finding all custom post types compatible with other plugins.

= 3.0 =
Optimizes database call to use index. Will check post type=post, page, and any custom post types like portfolio, recipe, testimonial, etc.

= 2.21 =
Fixes bug in date/time algorithm.

= 2.2 =
Reduces database interaction by limiting the call to home page and blog post headers only.

= 2.1 =
Reverting code to match 1.8 until we can do further testing.

= 2.0 =
Makes significant change to plugin so it only checks once per visitor, per session instead of each page load. Less taxing on database.

= 1.8 =
Tightened up code. Will not go into the publish loop unless there is a missed post.

= 1.7 =
Small change to integrate with WP posting function.

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.7 =
Minor changes to plugin.
