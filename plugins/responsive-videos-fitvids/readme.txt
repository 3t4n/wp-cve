=== Responsive videos - Fitvids ===

Contributors: seebeen
Donate link: https://sgi.io/donate
Tags: fitvids, responsive, videos, youtube, vimeo, jwplayer
Requires at least: 5.1
Tested up to: 5.5
Requires PHP: 7.2
Stable tag: 3.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your Embedded videos responsive on mobile devices with jQuery FitVids plugin
== Description ==

Responsive Videos plugin will allow you to automatically resize and scale your embedded videos on mobile devices.
CSS selector option is provided so you can target only your post / page content container.

**Features**

* Automatic configuration option
* Automatically resizes / scales all your embedded videos
* Works on all browsers
* Highly compatible - you can change the css selector for the text highlight
* Customizable - you can select the page type on which to activate the fitVids script

== Installation ==

1. Upload responsive-videos-fitvids.zip to plugins via WordPress admin panel, or upload unzipped folder to your plugins folder
2. Activate the plugin through the "Plugins" menu in WordPress
3. Go to Settings->Responsive Videos to manage the options

== Frequently Asked Questions ==

= What is the difference between manual and automatic configuration =

Automatic configuration will wrap all your embedded videos in a div with class .entry-content-asset
Javascript will automatically target those elements and make your videos responsive

= I am using the autoconfig option, and some of my videos are not responsive =

Depending on the theme / plugins you're using, it is possible that embedded video HTML is not being generated using oEmbed.
Disable auto configuration option, and use CSS selector to target your content

= Default CSS selector is not working, which one should I use? ==

Generally speaking, you should use the CSS selector for your content div. If you can't find it, you can use *body* as your CSS selector.

== Screenshots ==

1. Plugin in action on Mobile device

2. Container selector settings in admin panel

== Changelog ==

= 3.0.1 =
*Bugfix: Fixed PHP 7.2 compatibility errors

= 3.0 =

* Breaking: Minimum WP Version has been bumped to 5.1
* Breaking: Minimum PHP Version has been bumped to 7.2
* Improvement: Full PSR-12 compliance
* Improvement: Better plugin performance
* Improvement: Better handling of responsive media and embeds
* Bugfix: Fixed various warnings and notices

= 2.1.0 =

* Bugfix: Fixed bracket synthax in update file.
* Improvement: Plugin 100% compatible with PHP 5.3.x

= 2.1.0 =

* Bugfix: Fixed error 500 on some hosting configurations

= 2.0 =

* New: **Enabled Auto Configuration**
* Improvement: Added update hooks
* Improvement: Added FAQ section
* Improvement: Moved settings to dedicated option page

= 1.1.3 =
* Updated compatibility for WordPress 4.7.3
* Fully documented code

= 1.1.2 =
* Fixed internal version check
* Fixed initial option saving bug
* Removed screenshots from plugin trunk

= 1.1.1 =
* Fixed conditional for front page check

= 1.1 =
* Fixed minor backend bugs	
* Added an option to select the page type on which to enable fitVids script

= 1.0 =
* Initial release

== Upgrade Notice ==

None for now