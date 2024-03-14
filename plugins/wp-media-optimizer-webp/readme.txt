=== WP Media Optimizer (.webp) ===
Contributors: francescosganga
Donate link: https://www.francescosganga.it/
Tags: media, optimizer, reduce, image, size, webp, lazy, load
Requires at least: 5.1
Tested up to: 6.3.1
Requires PHP: 5.6
Stable tag: 1.4.0
License: GPLv2 or later

Automatically optimize images in your Wordpress site by converting them to .webp extension

== Description ==
 
Optimize images (jpg, png) in your Wordpress site by converting them to .webp extension (it uses PHP GD library)
WP Media Optimizer (.webp) only works with Firefox and Chrome (at this time).

-- SAFARI NOW SUPPORTS .WEBP images --

Now with LAZY LOAD FEATURE!

IMPORTANT: WPMOWEBP works only in the frontend side of your website (e.g. not in wp-admin/)

ABOVE THE FOLD Feature is not really "for dummies" but with some tests you can really improve your PAGE SPEED RATE! Hope you can appreciate this feature.

**USAGE**

Simply install and activate it, and your images will be optimized.

You can send a Feature Request from the Plugin Settings Page.
I hope you will use this form so I can add new features to this simple and wonderfurl plugin!

You can enable the new Lazy Load Feature by making a little donation to the author, you will receive a life time token for this feature and future improvements.

**HOW IT WORKS**

When anyone access to a Wordpress page, plugin check for images already converted to .webp.
If one or more images have not been already converted, the plugin converts them immediately.
Converted images are stored in a subfolder of wp-content folder: wp-content/wpmowebp

**TROUBLESHOOTING**

If Wordpress go out of memory try to add
define('WP_MEMORY_LIMIT', '256M');
to your wp-config.php

Enjoy your new Wordpress Plugin.

== Installation ==

1. Upload folder inside zip 'wp-media-optimizer-webp.zip' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
 
== Screenshots ==
 
== Changelog ==
 
= 1.0.0 =
* First plugin release
= 1.0.1 =
* Added .jpeg support
* Removed some debug lines
= 1.0.2 =
* Added "how it works" to page plugin
* Fixed about section
= 1.0.3 =
* Some fixes for WP Plugins Directory
= 1.0.4 =
* Fixed dynamic wp-content directory
= 1.0.5 =
* Replace works only if browser is not Safari Desktop or Mobile (due to .webp incompatibility)
= 1.0.6 =
* Fixed stable tag issue
= 1.0.7 =
* Fixed readme errors
= 1.0.8 =
* Fixed some errors while creating webp images
= 1.0.9 =
* Fixed errors in new installations
= 1.1.0 =
* Fixed Safari browser compatibility
* Added support reserved to Chrome and Firefox browsers
* Fixed Googlebot User Agent compatibility
= 1.1.1 =
* Fixed readme structure
= 1.1.2 =
* Fixed error due to Fatal error: Paletter image not supported by webp
= 1.1.3 =
* Edited settings page
= 1.1.4 =
* Added assets
= 1.1.5 =
* Fix on Safari
* Known bug: can't update real path in options page, fix in few days
= 1.1.6 =
* Fixed bug in admin options, now "save options" works.
= 1.1.7 =
* Added language system
* Added feedback request form in plugin settings page
= 1.1.8 =
* Fixed error in text-domain
= 1.1.9 =
* Deleted unnecessary language files
= 1.2.0 =
* Added admin notice during plugin activation
* Added admin notice for remember users to review the plugin
= 1.2.1 =
* Improved image recognition
= 1.2.2 =
* Added Lazy Load Feature!
= 1.2.3 =
* Enabled way to disable Lazy Load Feature
= 1.2.4 =
* Added a Wonderful ABOVE THE FOLD FEATURE!
= 1.2.5 =
* Added way to enable/disable above the fold feature
* Fixed bug retrieving css/js resources
* Resourses will all whitelisted (no effect on your website) in the first fetch or after purging
= 1.2.6 =
* Fixed bug for some images name while converting them to webp
* Added "Minify HTML" feature
= 1.2.7 =
* Fixed bug
= 1.2.8 =
* With some themes you saw filtered content from WPMOWEBP in the admin area which brokes your theme. WPMOWEBP will work only in the frontend side
= 1.2.9 =
* Safari now supports webp files
= 1.3.0 =
* Updating readme file
= 1.3.1 =
* Fixed inline css background image filter
* Add support to relative urls
= 1.4.0 =
* General fixes
* Lazy load fixes