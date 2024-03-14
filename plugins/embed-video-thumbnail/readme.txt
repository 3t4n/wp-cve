=== Plugin Name ===
Tags: embed, video, thumbnail, youtube, vimeo, facebook, dailymotion, replace, ikana, ikanaweb, gtmetrix, defer, javascript, parsing, replace, optimize, pagespeed
Requires at least: 4.5
Tested up to: 5.4
Stable tag: 2.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically replace embed videos everywhere with their thumbnail to reduce page load time and improve your GTmetrix score.

== Description ==

Activate this plugin and reduce you page weight by nearly 1mo !

Because embed videos can dramatically increase your page weight and loading time, Embed Video Thumbnail
automatically replace them by their corresponding thumbnail. Videos will then only be loaded after click on thumbnails.

This plugin fixes part of the following "defer parsing of javascript" error in GTmetrix :
> xxxx MiB of JavaScript is parsed during initial page load. Defer parsing JavaScript to reduce blocking of page rendering.

Currently supported video hosting services :

* Youtube
* Vimeo
* Dailymotion
* Facebook

Optional settings :

* Toggle activation on each hosting services
* Toggle activation by device (desktop, tablet, mobile)
* Copy thumbnail on local server for performance improvements
* Display video title over the thumbnail
* Toggle video loop on Vimeo and Youtube
* Import/export settings

Widget :

You can use our Embed Video Thumbnail widget to transform your videos in sidebars.

Custom hosting services can be added with the `ikevt_extension_providers` hook.
Each hosting service extension must implement `Ikana\EmbedVideoThumbnail\Provider\ProviderInterface`

Requirements :

* php 5.6+

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Embed Video Thumbnail submenu in Tools menu in admin panel to configure the plugin

== Frequently Asked Questions ==

= Plugin does not replace embed video everywhere on my website =

Unfortunately, Embed Video Thumbnail can automatically replace your embed video ONLY in your posts and pages body.
If you need to trigger video replacement in custom locations on your website, you can use the following filter :

> echo apply_filters('ikevt_video_to_thumbnail', 'https://www.youtube.com/watch?v=uGlpPcCEd8g');

You can also use our widget to transform videos in sidebars.

= Thumbnails are not showing =

Make sure `allow_url_fopen` option is enabled in your php.ini, and `php-curl` extension is installed and active.

== Changelog ==

= 2.0.3 =
* Fix filter output

= 2.0.2 =
* Remove margin on iframe

= 2.0.1 =
* Add php 5.6+ support

= 2.0.0 =
* Add support for search engines indexing, using native iframe with srcdoc. Use old strategy for IE.
* Add thumbnails default alt attribute.
* Add widget to transform videos in sidebars
* Fix bug where html entities were transformed.
* [BC] Remove support for php 5.6

= 1.3.1 =
* Removed freemius
* Refactoring

= 1.3.0 =
* Add FAQ section in readme
* Add "ikevt_video_to_thumbnail" filter
* Add Facebook support

= 1.2.3 =
* Fix thumbnail position
* Add new play button image option
* Add cookie disabling on youtube videos
* Add possibility to hide youtube logo and controls
* Bug fixes

= 1.1.7 =
* Fix youtube thumbnail position
* Add alt attribute on thumbnail when video title is enabled
* Fix referer restriction error with youtube API
* Fix youtube API error
* Fix issue where video urls were replaced if inside an href tag
* Fix minor bugs
* Remove redux framework TGM
* Add plugin logo in admin settings panel
* Add en_GB, en_CA and en_AU translations
* Add settings link on plugins page
* Add thumbnail copy on local server
* Add activation toggle by device

= 1.0.0 =
* Stable release