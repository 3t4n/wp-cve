=== Plugin Name ===
Contributors: brianmcculloh
Tags: reading, length, progress, reading time, scroll, scroll progress, reading progress, read time estimate
Requires at least: 3.8
Tested up to: 6.4.2
Stable tag: 1.14.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An adjustable progress meter showing how much of the post/page the user has scrolled through, and a read time commitment label near the post titles.

== Description ==

A very unobtrusive and light-weight reading progress bar indicator showing the user how far scrolled through the current post or page they are. You can control placement and color of the progress bar, and you can choose whether it includes just the main content or also the comments.

The progress bar only displays once the user begins scrolling the page so it is as unobtrusive as possible. Once the user stops scrolling or scrolls down past the content the progress bar subtly mutes until it is needed again.

There is also a reading time commitment feature that you can separately enable. Control the placement (above or below title, or above content), style, and whether it displays on posts and/or pages. Uses 200wpm as the metric for average reading time.

You can also place the time commitment label anywhere you want via the [wtr-time] shortcode.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/worth-the-read` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Worth The Read screen to configure the plugin
4. Enable the plugin by selecting at least one option for "Display On" in the plugin settings screen, such as Posts or Pages


== Frequently Asked Questions ==

= Why isn't the progress bar showing up? =

Make sure you enabled it in the Worth The Read settings page and that you're actually viewing a single post or page on your site (not your archive page, for instance). The bar won't display unless you have actually scrolled down into your main content. So if you have stuff going on at the top of your page above your post content (sliders, content panels, ads, etc.) the progress bar will remain hidden until it becomes relevant.

If the height of your post content is less than the height of the visible page, the progress bar will not display since the user already knows how much content there is. 

The functionality is javascript-based, so if you have a javascript error caused by something else like another plugin or your theme, it could affect the display of the progress bar.

= How much control do I have over the look and feel of the progress bar? =

You can control the foreground color, background color, transparency, width, offset, and placement of the progress bar. You can also separately control the background color of the comments portion (if enabled).

= How does it work? =

WordPress action hooks are used to insert small html tags above and below your post/page content and comments. jQuery is used to target those tags and use them to calculate distances on window scroll, and then the actual progress bar is animated accordingly.

= Why do you say it's "unobtrusive"? =

The plugin is as minimally distracting visually as it can be while still being easy to find. It auto-mutes any time the user does not need to visually reference it. Technically speaking, the html tags added to the DOM and corresponding CSS are very minimal and will not have any affect on the rest of the page DOM or any other plugins or your theme.

== Changelog ==

= 1.14.2 =
* Fixed: Compatible with WordPress 6.4.2

= 1.14.1 =
* Fixed: Fatal error

= 1.14 = 
* Fixed: Security vulnerability issue

= 1.13.2 = 
* Fixed: Menu priority glitch introduced in 1.13

= 1.13 =
* New Feature: Ability to display progress bar only on specific posts and pages
* New Feature: Ability to display reading time only on specific posts and pages
* Updated: Redux options framework from 4.3.12.7 to 4.3.21.2
* Tested: Compatible with WordPress 6.1.1

= 1.12 =
* Tested: Compatible with WordPress 6.0 

= 1.11 =
* New Feature: Option to display time commitment within posts loops, including archives and homepage

= 1.10 =
* Fixed: Resolved missing options files

= 1.9 =
* Updated: Redux options framework from 3.6.18 to 4.3.12.7

= 1.8 =
* New Feature: Added new debugging mode to help troubleshoot javascript issues
* Fixed: Comment end div is no longer inserted into the DOM twice
* Fixed: Homepage content is now correctly calculating progress bar length

= 1.7 =
* New Feature: Option to disable progress bar shadow added
* New Feature: Option to change the progress bar color when the end of the article is reached
* Fixed: Text domain renamed from wtr to worth-the-read so translations plugins work
* Fixed: JavaScript syntax issues in the js/wtr.js file

= 1.6 =
* New Feature: Reading time now optionally takes images into account and allows you to adjust pictures-per-minute variable
* Fixed: Improved handling of bugs which would clog up error logs
* Fixed: Redux framework compatiblity fatal error resolved

= 1.5 =
* New Feature: WTR now works with manually-entered custom post types
* New Feature: You can spcify a unique singular format for the time commitment label
* New Feature: Custom time commitment labels on a per-post and per-page basis
* New Feature: You can now disable the progress bar for non-touch devices only
* New Feature: Added RTL support for progress bar
* Updated: Updated Redux (the plugin options framework) from 3.6.8 to 3.6.15
* Updated: Implemented some Redux Framework annoyance helpers (https://hasin.me/2015/04/24/getting-rid-of-redux-framework-annoyances/)
* Fixed: The default time format string is now translation-ready
* Fixed: Commented out some console logging that had been used for debugging

= 1.4 =
* New Feature: You can remove the reading progress bar and time commitment label from individual posts and pages
* New Feature: You can manually adjust the average words per minute used in the time commitment calculation
* New Feature: Choose between two methods of word counting for the time commitment calculation
* New Feature: Content Offset setting. You can now manually add an offset to where the progress bar thinks the content begins
* New Feature: [wtr-end] shortcode. You can now manually specify where the progress bar thinks the content ends
* Updated: Updated Redux (the plugin options framework) from 3.6.5 to 3.6.8 which removed an unused set_transient causing slow queries
* Fixed: All registered custom post types are now available to select in the custom post types plugin options
* Fixed: Removed some php notices/warnings that popped up in error_logs
* Fixed: Non-latin languages now work with the time commitment calculation
* Fixed: Removed unused wtr-comments-end div injected into posts/pages with no comments

= 1.3.3 =
* Added custom post types to the time commitment label
* Added new option to change muted progress bar color in addition to opacity
* Added separate progress bar placement and offset options that apply only to touch devices
* Improved logic of where to display the time commitment label. You can now display it with a shortcode only instead of auto-placement.
* Improved time commitment label so the minimum read time is 1 minute (i.e. it will no longer display any "0" minute reads)
* Increased z-index of the progress slider from 99 to 99999
* Fixed PHP in_array notices in a few places

= 1.3.2 =
* Changed page slug of Redux options for better compatibility
* Updated installation instructions in readme.txt
* Removed a couple unneeded admin js/css files after migrating to Redux framework

= 1.3.1 =
* Fixed tagging issue causing 500 error

= 1.3 =
* Complete rewrite of options panel in Redux options framework
* Added shortcode [wtr-time] for custom placement of reading time commitment

= 1.2.1 =
* Scripts/styles no longer load on homepage if progress bar is not set to display on the homepage
* Comments div anchor only renders where applicable

= 1.2 =
* Added new time commitment feature
* Added custom post types compatibility
* Added home page compatibility
* Added disable for touch devices feature
* Added placement offset feature
* Added muted opacity feature (was previously locked at .5) 
* Improved top placement to work better with WordPress admin bar on various screen sizes
* Changed “width” setting label to “thickness” to be more intuitive
* Changed “mute” setting label to “fixed opacity” to be more intuitive
* Fixed php notices that displayed while wp_debug was turned on

= 1.1 =
* Added ability to display progress bar on posts and pages, instead of only posts
* Added new settings to adjust width and opacity of progress bar
* Added new setting to choose whether progress bar stays muted on scroll
* Improved calculations of progress bar scroll placement when Include Comments is on
* Fixed a few text strings that weren't wrapped in gettext function (i18n)

= 1.0.2 =
* Added settings link directly to plugin page

= 1.0.1 =
* Improved detection of comments

= 1.0 =
* Initial release

== Upgrade Notice ==

There are no upgrade notices at this time.
