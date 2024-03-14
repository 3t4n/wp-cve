=== Align RSS Images ===
Contributors: whiteshadow
Tags: rss, feed, images, alignment, html
Requires at least: 2.6
Tested up to: 5.2
Stable tag: 1.3.4

Scans your RSS feed and ensures that every image has the correct alignment and margin settings.

== Description ==

Align RSS Images is a simple plugin that scans your RSS feed and ensures that every image has the correct alignment and margin settings. This is useful if you want the images to appear correctly aligned not just on your site, but also in your RSS feed.

Technically speaking, the plugin finds HTML elements styled with any of the WordPress-generated classes and appends an appropriate style attribute to each element. This is done dynamically when the feed is generated, so your actual posts won’t be modified.

*Note: RSS readers that don't support inline CSS will usually ignore alignment settings.*

== Installation ==

1. Upload the `align-rss-images` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. And that's pretty much that.

== Frequently Asked Questions ==

= I installed the plugin but I see no changes in my RSS feed? =

This is usually due to caching. If you serve your feed via Feedburner or a similar service, it may take several hours before you'll see the changes. Also, most RSS readers will cache the feed and not show the updates immediately. 

= Won't this break my feed/make it non-compliant with web standards? =

Probably not. In my experience the modified feed looks fine in Google Reader, and the W3C Feed Validator also says it's valid. 

== Changelog ==

= 1.3.4 =
* Tested with WP 5.2.

= 1.3.3 =
* Fixed image padding.
* Refactored the code a bit to reduce the risk of conflicts with other plugins.
* Tested with WP 4.9.

= 1.3.2 =
* Tested with WP 4.2 (stable release). 

= 1.3.1 =
* Tested with WP 4.2-alpha.
* Note that while the plugin still works, some RSS readers no longer fully support inline CSS. Also, some readers don't recognize the `<figure>` tags used by some modern themes.

= 1.3 =
* Tested with WP 3.9-alpha.

= 1.2 =
* Added a changelog.
* Version bump - compatible with WP 2.8.4

= 1.1 =
* Initial release on wordpress.org