=== Simple Full Screen Background Image ===
Contributors: scott.deluzio, ampmode
Author URI: https://amplifyplugins.com
Plugin URI: https://fullscreenbackgroundimages.com
Tags: background, fullscreen, background image, full screen, image
Requires at least: 3.6
Tested up to: 6.1.1
Stable tag: 1.2.10

This plugin provides a simple way to set an automatically scaled full screen background image.

== Description ==

Simple Full Screen Background Image will allow you to easily upload and set a full screen image as the background of your website. Images will be automatically scaled with the browser, so regardless of the browser size, the image will always fill the screen, even as the browser is resized live.

Once activated, go to Appearance > Fullscreen BG Image. From here click Choose Image, then either upload from your computer or choose one from the media library. Once you have chosen your image, select the size you wish to insert and click Insert Into Post. A preview of your image will appear below. Now click Save Options
and view your site. The image should now be applied as a full screen background image.

= Go Pro! =

A greatly enhanced Pro version is [available](https://fullscreenbackgroundimages.com/downloads/full-screen-background-images-pro/)! Features of the pro version include:

* Unlimited background images
* Post / page-specific background images
* Multiple images with fade transitions on pages

Learn more about the Pro version [here](https://fullscreenbackgroundimages.com/downloads/full-screen-background-images-pro/).

== Installation ==

1. Upload the 'simple-full-screen-background' folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Appearance > Fullscreen BG Image and upload your background image

== Screenshots ==

1. Main settings
2. Settings with image
3. Sample frontend

== Changelog ==
= 1.2.10 =
* Updated tested up to version.
* Added Contributors.

= 1.2.9 =
* Fix: When first installed, the plugin was expecting a value to be displayed where none existed, which threw a PHP notice. Plugin now checks to see if the correct value exists before attempting to display it.

= 1.2.8 =
* Updated tested up to version.

= 1.2.7 =
* Reverted plugin suggestions from v1.2.6

= 1.2.6 =
* Updated translation file.
* Added suggestions for plugins in relevant contexts.

= 1.2.5 =
* Detection of the pro version of the plugin did not fully remove this version's admin menu, which caused paying users to have a confusing experience when clicking on the wrong menu.

= 1.2.4 =
* Added automatic deactivation when the pro version of this plugin is installed as the two plugins should not be used together.
* Automatic deactivation migrates Simple Full Screen Background Image to Pro's settings so the site's layout won't be changed by deactivating the Simple Full Screen Background Image plugin.
* Added uninstall.php to clean up when the plugin is deleted.

= 1.2.3 =
* Included support for [Instant Images](https://wordpress.org/plugins/instant-images/) plugin. This provides users with an easy way to search for and upload images from Unsplash.com locally to their WordPress site. The Instant Images button is now included on the Appearance > Fullscreen BG Image settings page. This requires that the Instant Images plugin be installed and active.

= 1.2.2 =
* Included alt tag in background image to comply with Web Content Accessibility Guidelines (WCAG). The background image is considered decoration, and so an empty alt tag (i.e. alt="") is an acceptable way to allow assistive technology to ignore the image.
* Update: Improved translatable POT file.
* Update: Added subtle upsell (dismissible) messaging in the plugin to inform users of the pro version of this plugin.

= 1.2.1 =
* Updated contributor/author

= 1.2 =
* Added missing text domains for translation
* Added a .pot file

= 1.1 =
* Updated the admin page to use the improved media manager UII
* Fixed function name conflicts with the Pro version.
* Improved data validation

= 1.0.4 =
* Fixed an issue with images loading via http when on an https site
* Did some general code cleanup and improvement


== Upgrade Notice ==
= 1.2.10 =
* Updated tested up to version.
* Added Contributors.
