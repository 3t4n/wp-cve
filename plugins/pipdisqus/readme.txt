=== pipDisqus - Lightweight Disqus Comments ===
Contributors: pipdig
Tags: comments, post, disqus, comments system
Requires at least: 4.2
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or higher

A lightweight solution for adding Disqus to your WordPress blog.

== Description ==

This plugin will remove any of the default WordPress comments features from your site, and replace this with Disqus.

Please note: Disqus comments will not be automatically imported into your WordPress dashboard. Instead, you can continue to moderate your comments via your Disqus moderation page. This plugin will add a quick link to your Disqus moderation page in the WordPress Admin Bar.

Reasons to use this plugin:

* Simplify your WordPress dashboard by removing the "Comments" admin menu.
* Easy setup: simply add the Disqus shortname in the settings and you're all set.
* Comments are NOT imported to WordPress - save server resources and database size.
* Improved spam protection to keep junk out your site's database.
* It has a silly name.

== Installation ==

1. Install the plugin.
2. Add your Disqus Shortname to `Settings > pipDisqus`.

== Screenshots ==
1. Admin Bar Shortcut
2. Settings

== Changelog ==

= 1.6 =
* Show comment moderation link in Admin Bar when user has moderate_comments permission.

= 1.5 =
* Speed up page loading times when Disqus comments are displayed.

= 1.4 =
* Defer comment count Javascript.

= 1.3 =
* Don't display comments when editing a post with [Beaver Builder](https://wordpress.org/plugins/beaver-builder-lite-version/).

= 1.2 =
* Don't display Disqus comments on draft or trashed posts.

= 1.1 =
* Use GUID in identifier.

= 1.0 =
* Initial release.