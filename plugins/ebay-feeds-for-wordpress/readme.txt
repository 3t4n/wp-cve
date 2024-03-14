=== WP eBay Product Feeds ===
Plugin Name:  WP eBay Product Feeds
Plugin URI:   https://winwar.co.uk/plugins/ebay-feeds-wordpress/?utm_source=header&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress
Donate link:  https://winwar.co.uk/plugins/ebay-feeds-wordpress/#donate
Description:  Formerly eBay Feeds for WordPress. Output RSS to HTML with this simple plugin. Easy to install, set up and customise.
Version:      3.4.3
Tested Up to: 6.4
Author:       Rhys Wynne
Author URI:   https://www.winwar.co.uk/?utm_source=header&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress
Contributors: rhyswynne
Stable tag: 3.4.3
Requires at least: 3.0
Text Domain: ebay-feeds-for-wordpress
Tags: ebay, ebay partner feeds, block, ebay partner network, gutenberg ready, affiliate marketing

Display feeds of eBay Products from eBay Partner Network on your site.

== Description ==

WP eBay Product Feeds allows you to quickly and easily place feeds from the eBay Partner Network into your WordPress blog. These can easily be embedded into posts, placed as widgets or inserted into the themes - flexibility is huge. This plugin is ideal for bloggers who wish to make more money through their blogs by promoting eBay's affiliate programme, as well as users who sell their own items on eBay.

*Please Note* From 1st September 2020 the Dynamic Feed Generator from eBay is being withdrawn. We've tested another service - [RSS Ground](https://www.winwar.co.uk/?post_type=surl&p=4793&preview=true), that will be used to handle feeds, though any other feed generator will work.

*Having Problems?* Please use the either the [WordPress.org support forums](https://wordpress.org/support/plugin/ebay-feeds-for-wordpress/) and I'll attempt to get back to you quickly. In a rush? Use our [**Priority Support Forums**](http://winwar.co.uk/priority-support/?utm_source=description&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress) to get an answer within 1 business day.

= WP eBay Product Feeds Premium =
[**WP eBay Product Feeds Premium**](https://www.winwar.co.uk/plugins/ebay-feeds-for-wordpress-premium/?utm_source=ebayfeedsforwordpresspremium&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress) is a plugin that extends the usability of WP eBay Product Feeds to allow you to create simple templates. You can turn feeds into beautiful shop style layouts, encouraging clickthroughs, higher sales and more referrals.

Also, from 2.3, you can now use WP eBay Product Feeds Premium to introduce smart links into your post. Save time and effort creating affiliate links to eBay using Smart Links!

= About Winwar Media =
This plugin is made by [**Winwar Media**](http://winwar.co.uk/?utm_source=about&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress), a WordPress Development and Training Agency in Manchester, UK.

Why don't you?

* [WP eBay Product Feeds](http://winwar.co.uk/plugins/ebay-feeds-wordpress/?utm_source=about&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress) WordPress Plugin homepage with further instructions.
* Check out more of our [WordPress Plugins](http://winwar.co.uk/plugins/?utm_source=about&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress)
* Follow us on Social Media, such as [Facebook](https://www.facebook.com/winwaruk), [Twitter](https://twitter.com/winwaruk) or [Google+](https://plus.google.com/+WinwarCoUk)
* [Send us an email](http://winwar.co.uk/contact-us/?utm_source=about&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress)! We like hearing from plugin users.
* Check out our book, [bbPress Complete](http://winwar.co.uk/books/bbpress-complete/?utm_source=about&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress)

= For Support =
We offer support in two places:-

* Support on the [WordPress.org Support Board](https://wordpress.org/support/plugin/ebay-feeds-for-wordpress)
* A [priority support forum](http://winwar.co.uk/priority-support/?utm_source=about&utm_medium=support&utm_campaign=ebayfeedsforwordpress), which offers same-day responses.

= Want to help development? =
* Fix (or submit) an issue on Github.

== Changelog ==
= 3.4.3 =
* Tested up to 6.4

= 3.4.2 =
* Tested up to 6.3

= 3.4.1 =
* Tested up to 6.2

= 3.4 =
* Fixed a XSS security vulnerability in the Settings area. Update strongly recommended.
* Tested up to 6.1.1


= 3.3.1 =
* Tested up to 6.0

= 3.3 =
* Removed Auction Request and replaced with RSS Ground.
* Fix a bug if you use RSS Ground and don't want the links to open in a new window.
* Tested up to 5.7

= 3.2.1 =
* Tested to 5.6

= 3.2 =
* Allowed you to block search engines from browsing the listings.
* Added a new filter, `wp_ebay_product_feed_blocked_text`, which will allow you to show bots some text. Use this for things like caching.
* Tested up to WordPress 5.5.

= 3.1 =
* Added tracking for auctionrequest links to help with debugging their ends.
* Added to the new WP Block Directory.
* Changed the loading of the "ebay_feeds_for_wordpress_change_class_string" to be loaded within the loop, rather than outside it.
* You can now target each item on the page with the class 'ebay-feed-item-X' (with x being a number).
* Fixed a typo within the Option Page.

= 3.0.3 =
* Changed the deny list to an allow list

= 3.0.2 =
* Caching was a little over exhuberant, changed it to 5 minutes.

= 3.0.1 =
* Fix a display bug with the legacy list.
* Removed the option to disable feed cache, as it was slowing down sites.
* Added a blocker on known bots, to prevent indexation of results in search results.

= 3.0 =
* Added Auctionrequest Branding to some elements.
* Added warnings that the feed should be removed.
* Header is hidden if no auctions are present.
* Fixed a bug that displayed double fallback text.

= 2.6 =
* Updated to match the premium version (and having the Italian Translation).
* Added the ability to control the header.

= 2.5.2 =
* Removed Google +1 and Stumbleupon buttons.
* Changed the author description.

= 2.5.1 =
* Fixed a bug that no feed will be displayed if there is only one item in the feed
* Added text domain to allow translation.

= 2.5 =
* Introduced an important field - the "Header Field", after some affiliates were being told that their account was being deactivated by not saying their feeds were from eBay
* Fixed a bug whereby the default feed or items were showing when the shortcode was left blank.

= 2.4.4 =
* Fixed a bug that the fallback text wasn't displaying when not present.

= 2.4.3 =
* Tested with 5.3

= 2.4.2 =
* Renamed to "WP eBay Product Feeds"

= 2.4.1 =
* Tested with 5.2

= 2.4 =
* Added the ability to set max image width.
* Fixed a few bugs with a few untranslatable strings.
* Pushed to v2.4

= 2.3.1 =
* Tested with 5.1.

= 2.3 =
* Fix a bug that occurs when you try running the Premium version at the same time as the free version that results in a notice displaying in Gutenberg as you register two blocks with the same name.
* Options page cleanup in the code.

Not a huge release, but worth installing if you use the premium version.

= 2.2.1 =
* Fix a small bug that results in a fatal error for an invalid feed.

= 2.2 =
* Fixed a bug in Gutenberg code.
* Fix a bug with price based feeds. Price should be set descending now.
* Checking for register_block_type function, rather than the_gutenberg_project.

= 2.0.2 =
* Fix a bug with the images loading over SSL.

= 2.0.1 =
* Fix an error.

= 2.0 =
* Refactored the code
* Improved the popup window - no more multiple popups!
* Fixed a bug that Buy It Now/Watch Items links wouldn't open in a new window.
* Fixed a spelling error in the name of the plugin.
* Added compatibility to Gutenberg.
* Added the new settings API.

= 1.13 =
* Fix bug that links weren't nofollowing in the description.
* Add "Subscribe to Newsletter" checkbox to newsletter page.

= 1.12 =
* Fix bug with some images not displaying corrrectly over SSL.
* Push to 1.12

= 1.11 =
* Fix bug that some titles weren't displaying correctly

= 1.10 =
* Load the feed over SSL.

= 1.9 =
* Added the ability to force the feed

= 1.8 =
* Added the ability to wrap a class around each individual item.
* Auto populate the user email addresses to get bug fixes. (it is still opt in though!)

= 1.7.3 =
* Call the JavaScript better should site_url being invalid.

= 1.7.2 =
* Switch off the default text for widget title & widget text.

= 1.7.1 =
* Fixes the issue of feeds sometimes displaying the wrong details in shortcodes.

= 1.7 =
* Allow the disabling of feed caching.

= 1.6.3 =
* Made WordPress 4.3 compatible. Widget now works in debug mode.
* Fixed a wierd caching bug in RSS Feeds.

= 1.6.2 =
* French Translation Done

= 1.6 =
* Plugin now translateable

= 1.5.2 (Time Taken - <1m) =
* Fix a spelling mistake in the readme.

= 1.5.1 (Time Taken - 15m) =
* Removed a default link which was daft and no idea why it was there. (Props @[johnwilsonuk](https://twitter.com/johnwilsonuk))

= 1.5 =
* Added a "Debug Mode", so errors aren't hidden if they exist.
* Fixed a few notices that appeared with WP_DEBUG was set to true.
* Cleaned up a lot of the internal code, with proper line spacing!
* Switched fully from Blogging Dojo to Winwar Media.

= 1.4 =
* Stops a fatal error appearing when the feed doesn't return in the widget.
* Fixed a PHP warning on activation when registering the widget in Debug Mode.

= 1.3 =
* Added a "fallback" option that displays text when feeds aren't present.

= 1.2 =
* Changed to the new eBay logo.
* Fixed link in plugin display footer.
* Displays error should URL be invalid.

= 1.1 =
* Removed a few redundant files that included a security exploit. I recommend a full upgrade.

= 1.0 =
* Fixed a few small compatability issues.
* Added the ability to "Nofollow" links.

= 0.9.3 =
* Added error tracking to the service.

= 0.9.2 =
* Caught an error so that an invalid/empty feed would not break the site.

= 0.9.1 =
* Improved the backend of the plugin.

= 0.9 =
* Improved "links in new window" opening feature

= 0.8 =
Added a feature where you can open links in a new window.

= 0.7 =
* Updated the plugin to include information on how to place the code when you install it. Whoops!

= 0.6.1 =
* Removing some testing code I should've removed :(

= 0.6 =
* Added Some More CSS classes
* Added a donation link for some cheap people I work with and refuse to donate!

= 0.5.3 =
* Fixed Encoding Issues using TinyMCE
* Error Trapping on "Null" issues

= 0.5.2 =
* General Bug Fixes.

= 0.5 =
* First Public release

== Installation ==

To install, please do the following:-

1. Upload the plugin to the `/wp-content/plugins/` directory or use the Add New feature
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set the default feed and item number in the "WP eBay Product Feeds" Options Page.
4. Place `[ebayfeedsforwordpress feed="" items="5"]` in any page. Place the feed URL in the "" and the number of items to display where "5" is, in any page or post (or use the button on the rich text editor).

For a full guide on how to install WP eBay Product Feeds, check out the [how to install and setup WP eBay Product Feeds guide](https://www.winwar.co.uk/2018/09/how-to-set-up-ebay-feeds-for-wordpress/?utm_source=installation&utm_medium=wordpressorgreadme&utm_campaign=ebayfeedsforwordpress) on Winwar Media.