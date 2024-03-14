WP eBay Product Feeds
========================
Plugin Name:  WP eBay Product Feeds

Plugin URI:   https://winwar.co.uk/plugins/ebay-feeds-wordpress/

Donate link:  https://winwar.co.uk/plugins/ebay-feeds-wordpress/#donate

Description:  Formerly eBay Feeds for WordPress. Output RSS to HTML with this simple plugin. Easy to install, set up and customise.

Version:      3.4.3

Author:       Rhys Wynne

Author URI:   https://www.winwar.co.uk/

Contributors: rhyswynne

Stable tag: trunk

Requires at least: 3.0

Tested Up to: 4.0

Tags: ebay, ebay partner feeds, ebay partner network, rss feeds, affiliate marketing

Output RSS to HTML with this simple plugin. Easy to install, set up and customise.

Description
===========
WP eBay Product Feeds allows you to quickly and easily place feeds from the eBay Partner Network into your WordPress blog. These can easily be embedded into posts, placed as widgets or inserted into the themes - flexibility is huge. This plugin is ideal for bloggers who wish to make more money through their blogs by promoting eBay's affiliate programme, as well as users who sell their own items on eBay.

*Having Problems?* Please use the either the [WordPress.org support forums](https://wordpress.org/support/plugin/ebay-feeds-for-wordpress) and I'll attempt to get back to you quickly. In a rush? Use our [**Priority Support Forums**](http://winwar.co.uk/priority-support/) to get an answer within 1 business day.

About Winwar Media
------------------
This plugin is made by [**Winwar Media**](http://winwar.co.uk/), a WordPress Development and Training Agency in Manchester, UK.

Why don't you?

* [eBay Feeds For Wordpress](http://winwar.co.uk/plugins/ebay-feeds-wordpress/) WordPress Plugin homepage with further instructions.
* Check out more of our [WordPress Plugins](http://winwar.co.uk/plugins/)
* Follow us on Social Media, such as [Facebook](https://www.facebook.com/winwaruk), [Twitter](https://twitter.com/winwaruk) or [Google+](https://plus.google.com/+WinwarCoUk)
* [Send us an email](http://winwar.co.uk/contact-us/)! We like hearing from plugin users.
* Check out our book, [bbPress Complete](http://winwar.co.uk/books/bbpress-complete/)

For Support
-----------
We offer support in two places:-

* Support on the [WordPress.org Support Board](https://wordpress.org/support/plugin/ebay-feeds-for-wordpress)
* A [priority support forum](http://winwar.co.uk/priority-support/), which offers same-day responses.

Changelog
=========
1.13
----
* Fix bug that links weren't nofollowing in the description.
* Add "Subscribe to Newsletter" checkbox to newsletter page.

1.12
----
* Fix bug with some images not displaying corrrectly over SSL.
* Push to 1.12

1.11
----
* Fix bug that some titles weren't displaying correctly

1.10
----
* Load the feed over SSL.

1.9
---
* Added the ability to force the feed

1.8
---
* Added the ability to wrap a class around each individual item.
* Auto populate user email addresses to get bug fixes.

1.7.3
-----
* Call the JavaScript better should site_url being invalid.

1.7.2
-----
* Switch off the default text for widget title & widget text.

1.7.1
-----
* Fixes the issue of feeds sometimes displaying the wrong details in shortcodes.

1.7
---
* Allow the disabling of feed caching.

1.6.3
-----
* Made WordPress 4.3 compatible. Widget now works in debug mode.
* Fixed a wierd caching bug in RSS Feeds.

1.6.2
-----
* French Translation Done

1.6
---
* Plugin now translateable

1.5.2 (Time Taken - <1m)
------------------------
* Fix a spelling mistake in the readme.

1.5.1 (Time Taken - 15m)
------------------------
* Removed a default link which was daft and no idea why it was there. (Props @[johnwilsonuk](https://twitter.com/johnwilsonuk))

1.5
---
* Added a "Debug Mode", so errors aren't hidden if they exist.
* Fixed a few notices that appeared with WP_DEBUG was set to true.
* Cleaned up a lot of the internal code, with proper line spacing!
* Switched fully from Blogging Dojo to Winwar Media.

1.4
---
* Stops a fatal error appearing when the feed doesn't return in the widget.
* Fixed a PHP warning on activation when registering the widget in Debug Mode.

1.3
---
* Added a "fallback" option that displays text when feeds aren't present.

1.2
---
* Changed to the new eBay logo.
* Fixed link in plugin display footer.
* Displays error should URL be invalid.

1.1
---
* Removed a few redundant files that included a security exploit. I recommend a full upgrade.

1.0
---
* Fixed a few small compatability issues.
* Added the ability to "Nofollow" links.

0.9.3
-----
* Added error tracking to the service.

0.9.2
-----
* Caught an error so that an invalid/empty feed would not break the site.

0.9.1
-----
* Improved the backend of the plugin.

0.9
---
* Improved "links in new window" opening feature

0.8
---
Added a feature where you can open links in a new window.

0.7
---
* Updated the plugin to include information on how to place the code when you install it. Whoops!

0.6.1
-----
* Removing some testing code I should've removed :(

0.6
---
* Added Some More CSS classes
* Added a donation link for some cheap people I work with and refuse to donate!

0.5.3
-----
* Fixed Encoding Issues using TinyMCE
* Error Trapping on "Null" issues

0.5.2
-----
* General Bug Fixes.

0.5
---
* First Public release

Installation
============

To install, please do the following:-

1. Upload the plugin to the `/wp-content/plugins/` directory or use the Add New feature
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the default feed and item number in the "eBay Feeds For Wordpress" Options Page.
4. Place `[ebayfeedsforwordpress feed="" items="5"]` in any page. Place the feed URL in the "" and the number of items to display where "5" is, in any page or post (or use the button on the rich text editor).
