=== Simple WP Retina ===
Contributors: desrosj, slocumstudio
Tags: Retina, high DPI, high pixel density, high resolution screens, @2x, image, images, media, thumbnail, upload
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Seamlessly replaces images with @2x versions on Retina and other high pixel density screens. Change is performed server side saving requests.

== Description ==

Seamlessly switches out images on Retina and other high pixel density screens with @2x versions.

The switch happens server side saving on HTTP requests.

No action is required on your part other than to activate it and to regenerate your thumbnails with the [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) plugin.

Plugin automatically:

*   Detects a HighDPI screen such as a Retina display
*   Detects all image sizes added using add_image_size() and adds @2x versions.
*   Replaces featured thumbnails and content images with the @2x versions for users with HighDPI screens.

== Installation ==

Setup is easy.

1. Upload plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If you already have images on your site, running the [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) plugin will help by generating the new image sizes created by the plugin.

== Frequently Asked Questions ==

= It does not work for me =

Are you using any form of caching? Unfortunately the plugin currently does not play friendly with caching.

= It works, but the images served on high pixel density screens are the full size images.

Running the [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) plugin will help by generating the new image sizes created by the plugin.

= The images still look pixelated on high pixel density screens =

The plugin can not scale up images. In order for this to work, the original images need to be at least double the dimensions of the desired image size.

== Changelog ==

= 1.1.1 =
* Fixing bug when using full image size. Props Tavicu.

= 1.1 =
* Added gallery support. Thumbnails are now @2x versions.

= 1.0 =
* Hello World!
* Let's make our WordPress site look great on Retina and other high DPI screens super easy.