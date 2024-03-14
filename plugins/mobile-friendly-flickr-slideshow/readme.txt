=== Responsive Flickr Slideshow ===
Contributors: robertpeake, robert@msia.org, robert.peake
Tags: flickr, slideshow, mobile, responsive
Requires at least: 3.0.0
Tested up to: 6.0.2
Stable tag: 2.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embeds a responsive slideshow of Flickr images from any album or photoset

== Description ==

Embeds a responsive slideshow of Flickr images from any album or photoset

Shortcodes are of the format: `[fshow url=https://flic.kr/s/aHsiP3Xyxx]` or `[fshow photosetid=72157627847553181]`

The default parameters for api key, username, user_id and photosetid can have values set using the plugin options page that will then be used by default whenever they are omitted from the shortcode.

== Installation ==

Install as normal for WordPress plugins. Obtain an API key from Flickr to enable responsive mode.

== Frequently Asked Questions ==

= How do I use the plugin? =

You must first <a href="https://www.flickr.com/services/apps/create/apply">obtain an API key from Flickr</a>.

= How do I embed a slideshow? =

Shortcodes are of the format: <code>[fshow photosetid=72157627847553181]</code> or <code>[fshow url=https://flic.kr/s/aHsiP3Xyxx]</code>

= Where do I find these variables? =

As part of any gallery URL, you should see your photosetid at the end. It is a number.

= Are you affiliated with Flickr or SmugMug in any way? =

This plugin is not affiliated with or endorsed by Flickr or its parent company, SmugMug Inc. in any way. Flickr is a registered trademark of Yahoo!, Inc.  By using this plugin you agree  the <a href="https://www.flickr.com/services/api/tos/">terms of service</a> set out by Flickr. 

= Do you offer any warranty? = 

The author provides no warranty as to the suitability to any purpose of this software. You agree to use it entirely at your own risk.

== Screenshots ==

1. Default responsive slideshow frame
2. Settings page

== Changelog ==

= 2.5.1 =
 * Updated "tested up to"

= 2.5 =
 * Upgraded screenful.js to final stable version ( https://github.com/sindresorhus/screenfull.js/ )
 * Resolved issue with full-screen mode
 * Resolved deprecation warning for curly-brace access of chars in strings

= 2.4.7 =
 * Tested with 5.0
 
= 2.4.6 =
 * Tested with 5.0

= 2.4.4 =
 * Change Yahoo! to SmugMug

= 2.4.3 =
 * Minor bugfix

= 2.4.2 =
 * Converted noframes to noscript tags for HTML5 compataibility

= 2.4 =

 * Improved error handling for Flickr API, including longer default timeouts
 * Protection against "cache poisoning" in case Flickr API is having a bad day

= 2.3.4 =

 * Tested with WordPress 4.4x
 * Supports using shortcode attributes of the form [fshow url=https://flic.kr/s/aHsiP3Xyxx] (updated documentation)

= 2.3.3 =

 * Make gallery links no longer use shortcode URLs by default
 * Use given shortcode URLs where requested, due to occasional base-58 encoding problems in PHP
 
= 2.3.2 =

 * Specify in admin settings that width is default maximum width (container is responsive)

= 2.3.1 =

 * Added loading spinner for lazy loading of images

= 2.3 =

 * Supports using Flickr short urls in of the form [fshow=https://flic.kr/s/aHsiP3Xyxx]
 * Implements transient caching of remote Flickr API calls (default 1 hour, configurable)
 * Resolves issue with different-sized slideshow images overlapping
 * Users Flickr Short URLs for gallery link

= 2.2.1 =

 * Increased link text size

= 2.2 =

 * Added fullscreen mode for mobile devices

= 2.1.4 =

 * Added .fshow-wrapper class to outer div to facilitate styling

= 2.1.3 =

 * Show/hide navigation elements when mouse enters/leaves frame

= 2.1.2 =

 * Added "Performance mode" by default, which does not include css/js from wordpress in the slideshow frame

= 2.1.1 =

 * Improvements to full-screen mode and transitions

= 2.1 =

 * Implements lazy-loading of images for very large slideshows

= 2.0.2 =

 * Bug fix for full-screen mode from within iframe

= 2.0.1 =

 * Fixed label for API Key link in admin

= 2.0 =

 * Complete re-write due to flickr removing mobile slideshow; slideshow now based on Foundation Orbit slider (requires flickr API key)

= 1.0 =

* Verified compatability with Wordpress 4.2

= 0.1 =

* Initial release
