=== RSS Includes Pages ===
Contributors: Marios Alexandrou
Donate link: https://infolific.com/technology/software-worth-using/include-pages-in-wordpress-rss-feeds/#pro-version
Tags: rss, feed, feeds, pages, custom post types, cbt, pages feed, custom post types feed, cbt feed, 
Requires at least: 5.0
Tested up to: 6.4.2
License: GPLv2 or later

Modifies RSS feeds so that they include pages and not just posts.

== Description ==

Modifies RSS feeds so that they include pages and not just posts. Deactivating the plugin restores RSS feeds to their default state.

Including pages in your feed is particularly useful if you're using WordPress as a CMS where pages represent a good portion of your content.

The [pro version](https://infolific.com/technology/software-worth-using/include-pages-in-wordpress-rss-feeds/#pro-version) (a lifetime license is less than $15) also allows you to:

1. Specify that ONLY pages are included in the feeds i.e. no posts.
2. Exclude/include posts and pages by ID.
3. Add custom post types such as WooCommerce products or Avada portfolios.

== Installation ==

1. Upload the rss-includes-feeds folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. The free version doesn't have any settings you can change. Pages will be included by default.

== Frequently Asked Questions ==

= Will this plugin work with other RSS feed related plugins? =

Yes. The modifications to the feed are done at a low level and other plugins should remain compatible.

= I just activated the plugin, why don't I see pages in my feed? =

If you're using Feedburner you'll have to wait until your updated feed is picked up by Feedburner. This isn't immediate so give it a few hours. Also, WordPress caches feeds and doesn't rebuild it on the fly.

= I just posted a page, why don't I see it in my feed? =

If you're using Feedburner you'll have to wait until your updated feed is picked up by Feedburner. This isn't immediate so give it a few hours. Also, WordPress caches feeds and doesn't rebuild it on the fly.

= Does this plugin create new feeds? =

No. The plugin adds pages and custom post types to existing feeds. No new feeds are created; existing ones are modified.

== Screenshots ==

1. By default the plugin adds pages to your existing feeds. In the [pro version](https://infolific.com/technology/software-worth-using/include-pages-in-wordpress-rss-feeds/#pro-version) you can specify posts and/or pages. You can also excludes posts/pages by ID. 

== Changelog ==

= 3.8 =
* All Versions: Updated info regarding the pro version.

= 3.7 =
* All Versions: Security issue (cross-site scripting) fix applied. Discovered with DefenseCode WebScanner Security Analyzer by Neven Biruski.

= 3.6 =
* Pro version: Rolling back changes made to version 3.5.

= 3.5 =
* Pro version: Added option to force including by ID regardless of page/post type.

= 3.4 =
* All versions: Removing redundant code. Re-organizing code for improved readability.
* Pro version: Can now add custom post types (e.g. WooCommerce products) to feed.

= 3.3 =
* All versions: Tweaks to documentation including the readme.txt.

= 3.2 =
* All versions: Unneeded CSS removed.
* All versions: Additional security to prevent direct access of plugin file.
* Pro version: Can now specify a certain list of posts/pages to include.

= 3.1 =
* All versions: Screenshot added to readme file.
* Pro version: Can now exclude posts/pages by ID.

= 3.0 =
* Code cleanup to better conform with WordPress coding standards.
* Pro version launched.
* Pro version: Ability to specify that ONLY pages appear in feeds.

= 1.4.3 =
* Confirmed that plugin works fine with WordPress 3.9.1.

= 1.4.2 =
* More fixes to readme file.

= 1.4.1 =
* Corrected contributor list.

= 1.4 =
* Tweaked SQL that looks for updated posts / pages.

= 1.3 =
* Confirmed that plugin works fine with WordPress 3.2.1.

= 1.2 =
* FAQ updates and confirming plugin works with WordPress 2.9.2.

= 1.1 =
* Fixes for new WordPress release.

= 1.0 =
* Initial release.
