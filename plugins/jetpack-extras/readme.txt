=== Plugin Name ===
Contributors: BarryCarlyon
Donate link: http://barrycarlyon.co.uk/wordpress/wordpress-plugins/jetpack-extras/
Tags: jetpack, twitter
Requires at least: 3.4.0
Tested up to: 4.1.1
Stable tag: 3.3
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extras for WordPress Jetpack.
Tested up to Jetpack 3.4.1

== Description ==

This plugin adds extra bits an pieces to [WordPress Jetpack](http://wordpress.org/extend/plugins/jetpack/)

Previous called "Jetpack Extras" renamed at request of Jetpack Team to "Custom Tweaks for Jetpack"

Which includes the following additions:

*   Ability to control button placement, above, below, or both of the post content, with separate options for the archive page and content display page

*   Twitter Button added Related (username/optional description format)
*   Adds the ability to make the Twitter button share the WP.me url, if that JetPack module in use, [As suggested by SkipTweets on Twitter](http://skipsloan.com/?p=175)

Currently removed is:

*   Ability to turn on/off the [DNT Twitter](https://dev.twitter.com/docs/tweet-button#optout) button mode - it is difficult to add without editing chunks of the core


== Installation ==

Requires [WordPress Jetpack](http://wordpress.org/extend/plugins/jetpack/)

1. Install either via the WordPress.org plugin directory, or by uploading the files to your server
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Settings are inline with existing JetPack settings pages

== Screenshots ==

1. Main Options

== Changelog ==

= 3.3 =
* Version bump to match Jetpack Core
* Verfied functionaliy with WP 4.1 and JetPack 3.3
* Remove "Twitter Via" option as in JetPack Core
* Revised some text on a admin item
* Added notes about Twitter Card Whitelisting

= 1.7.1 =
* Fixing Button placement options for Pages/post types that are not Posts. It was using the wrong key entry, thanks to [TheSuperCargo](http://wordpress.org/support/profile/thesupercargo) for [reporting](http://wordpress.org/support/topic/plugin-jetpack-extras-by-barrycarlyon-share-buttons-placement-on-pages-not-working?replies=3#post-3225923)
* Also moved the screenshot out of the Zip File into the assets directory to save Zip file size.

= 1.7 =
* Updated display function (sharing)
* Added Twitter Via and Related options for the new Jetpack Sharing Buttons
* Related Supports Username, and optinal description
* Readded WP.me option for Sharing Via Twitter

= 1.6.1.1 =
* Fixed a woopsie in option saving

= 1.6.1.0 =
* Maintainence Fix
* Removed Pinterest button as Supported by JetPack Core
* Class Renames/NameSpacing to Avoid Conflicts
* Moved Twitter Button to a Separate Twitter button, so you can run Core or Extras
* Sanity Check for it JetPack exists or not

= 1.5.0 =
* Rewrote plugin to be a separate plugin,
* Added wp.me support to the Twitter button, so when Tweeting, a Embedded Preview is rendered on Twitter.com [As suggested by SkipTweets on Twitter](http://skipsloan.com/?p=175)

= 1.4.2 =
* Original Release, Whole plugin recplacment,
* Added Pinterest Support,
* Added additional Twitter options, DNT, Data via/Related,
* Button Placement Options
