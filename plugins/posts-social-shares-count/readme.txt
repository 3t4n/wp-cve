=== Posts Social Shares Count ===
Contributors: Bishoy.A
Tags: social, shares, count, shortcodes, Facebook, Google Plus, Pinterest, Stumble, Delicious, LinkedIn
Donate link: http://bishoy.me/donate
Requires at least: 2.5.0
Tested up to: 4.3
Stable tag: 1.4.1
License: GPL2
License URI: http://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses

Plugin that gives you shortcodes and PHP functions to count posts/pages shares on 6 social networks!

== Description ==
= About =
You can use this plugin to get the number of shares for a given post by ID.

= How to use =
To count for example how many times the post or page has been shared on Facebook use the function echo pssc_facebook() in the loop or echo pssc_facebook( $post_id ) anywhere. This function will return the count integer for example 5 or 0.

Or you can use the shortcode [pssc_facebook] in the post you want to count shares for or [pssc_facebook post_id=""] for a specific post by ID.


= Available Shortcodes =
* [pssc_facebook]
* [pssc_pinterest]
* [pssc_linkedin]
* [pssc_delicious]
* [pssc_stumble]
* [pssc_gplus]
* [pssc_all]


= Available Functions =
* pssc_facebook()
* pssc_pinterest()
* pssc_linkedin()
* pssc_delicious()
* pssc_stumble()
* pssc_gplus()
* pssc_all()

Note that PHP functions needs to be echoed.

= Change Log =

* Version 1.4.1: Removed Twitter functions due Twitter’s disabling their count API.
* Version 1.4.0: Fixed some bugs and added Posts Social Shares Count to edit posts/pages/custom post types list view in admin (edit.php).
* Version 1.3.1: Fixed a bug with caching.
* Version 1.3: Added caching support and Added total shares count to post edit page.
* Version 1.2: Fixed a bug with functions without post ID usage.
* Version 1.1: Fixed a bug with pssc_all shortcode

== Screenshots ==
1. Shares count in admin edit post view
2. Shares count in admin edit post/pages/post types list view

== Installation ==
1. Go to your admin area and select Plugins -> Add new from the menu.
2. Search for \"Posts Social Shares Count\".
3. Click install.
4. Click activate.

== ChangeLog ==

= 1.4.1 =
* Bug fixes
* Disabled Twitter functions because Twitter has disabled their count API. See [this blog post](https://blog.twitter.com/2015/hard-decisions-for-a-sustainable-platform "Hard decisions for a sustainable platform")

= 1.4.0 =
* Added Posts Social Shares Count to edit posts/pages/custom post types list view in admin (edit.php).
* Fixed PHP notices that displayed randomly.
* Fixed Issue for users with PHP safe_mode or open_basedir set on.
* Fixed Issue with Pinterest count to always 0.

= 1.3.1 =
* Fixed a bug with caching

= 1.3 =
* Added Caching support
* Improved performance
* Added total shares count to post edit page

= 1.2 =
* Fixed a bug with functions without post ID usage.

= 1.1 =
* Fixed a bug with [pssc_all] shortcode

= 1.0 =
* Initial Plugin Release