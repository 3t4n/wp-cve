=== Specify Missing Image Dimensions ===
Contributors: yasir129
Tags: speed, pagespeed, images, Optimize, image optimization, image, CLS
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 5.3
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin helps to add missing width and height attributes to images.

== Description ==
<strong>Specify Missing Image Dimensions</strong> is a WordPress plugin that helps to add missing width and height attributes to images. This plugin scans the entire HTML page and specify missing width and height to <img> tags.

If you are seeing error like "Image elements do not have explicit width and height" on [Google PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/) then this plugin will help you to remove that error by adding missing width and height attributes to the required images.

By adding width and height to the images, Cumulative Layout Shift (CLS) also improves as there will be no more unwanted jumps while HTML page rendering.

== How this Plugin Works ==

1. <strong>Specify Missing Image Dimensions</strong> plugin scans the entire HTML page and finds images with missing width and height attributes.
2. After finding the images, this plugin gets the image dimensions using getimagesize() function.
3. After getting the required dimensions, plugin adds the width and height attributes to the images.

== Features ==
* Exclude images by image extension
* Exclude images by image Name
* Exclude images by class
* Exclude images by ID

== Installation ==

Search for "Specify Missing Image Dimensions" under "Plugins" → "Add New" in your WordPress dashboard to install the plugin.

Or install it manually:

1. Download the plugin's [zip file](https://wordpress.org/plugins/specify-missing-image-dimensions/).
2. Go to *Plugins* → *Add New* in your WordPress admin.
3. Click on the *Upload Plugin* button.
4. Select the file you downloaded.
5. Click *Install Plugin*.
6. Activate.

== Frequently Asked Questions ==

= Why this plugin is important? =

Once this plugin assigns missing width and height attributes to required images, there will be no more unwanted jumps while page rendering. Adding missing width and height attributes also help to improve scores on website speed tools such as [Google's PageSpeed](https://developers.google.com/speed/pagespeed/insights/) and [GTmetrix](https://gtmetrix.com)

= Does this plugin also works for SVG? =

Yes, if the SVG are added using <img> tag, then this plugin will work fine for them.
But if you see some issues with SVG or any other type of images, you can exclude SVG using plugin's option.

== Screenshots ==

1. Access "Specify Missing Image Dimensions" Settings Page
2. "Specify Missing Image Dimensions"  Settings Page

== Changelog ==

= 1.0.2 =
* Handled running of plugin's functionality at backend (wp-admin)

= 1.0.1 =
* Handled Error causing by SVG images

= 1.0.0 =
* Initial release