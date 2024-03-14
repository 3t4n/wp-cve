=== Shutter Reloaded Plus ===
Contributors: Danaila Iulian Nicu
Plugin Name: Shutter Reloaded Plus
Plugin URI: http://www.itinfo.ro/shutter-reloaded-plus/
Author URI: http://www.itinfo.ro/
Author: Danaila Iulian Nicu
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QHKTHAXG6RKUY
License: GPLv2 or later
Tags: images, javascript, viewer, lightbox, keyboard navigation, arrow keys, responsive, gallery, slideshow
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 0.6

Darkens the current page and displays an image (like Lightbox, Thickbox, etc.), but is a lot smaller (8KB) and faster.


== Description ==

Shutter Reloaded Plus is an image viewer for your website that works similarly to Lightbox, Thickbox, etc. but is under 8KB in size and does not require any external libraries.

It is fully responsive, cross-browser and mobile ready. Tested on Chrome, Safari, Firefox, Internet Explorer, Android phones and tablets, Windows Phone 8.

It has many features:

- keyboard navigation with arrow keys, left and right arrows, and close by pressing the Esc key
- resizing large images if the window is too small to display them with option to show the full size image
- combining images in sets
- redrawing the window after resizing, RESPONSIVE
- pre-loading of neighbour images for faster display and very good browser compatibility
- integrate with Google Analytics to count each image view as a page view
- option to overwrite NextGen Gallery's effects (Lightbox, Fancybox)
- option to display a like button for the current page in the control bar
- click on the image goes to the next image
- click outsite de image to close de slideshow
- all images used for buttons are in a PNG sprite, so it only has 3 HTTP request (image, js and css)
- option to only load it on single pages and posts

This plugin offers customization of the colour and opacity settings for the background and colour for the caption text, buttons text and the menu background.

There are options to enable it for all links pointing to an image on your site (with option to exclude some pages), or just on selected pages. It can be enabled only for image links with CSS class="shutter" with option to create a single set or multiple sets for each page.

The plugin can also "auto-make" image sets for each Post, so when several posts are displayed on the "Home" page, links to images on each post will be in a separate set. See the built-in help for more information.

== Changelog ==

= 0.6 =
* Partial fix on Iphones on Safari
* Added color pickers in the admin page
* Added Help Tab in the admin page
* Refactored admin page html code for clearer options and brought to 3.8 standards

= 0.5 =
* Fixed margin showing under the bottom bar
* Modifyed colors of overlayer and bar to black for a more crisp look
* Added Facebook Like Button for the current page in the bottom bar
* Option to only load it on single pages or posts
* Fixed click outside the picture to close
* Option to overwrite Nextgen Gallery's lightbox

= 0.4 =
* Fixed overlayer not showing up upon install
* Fixed buttons which showed up smaller because of theme influence. Now they won't be affected by the theme used

= 0.3 =
* Google Analytics integrations, each view of a picture will count as a view of the page

= 0.2 =
* Sprite icons as png file
* Remove css and js version to enable browser cache

= 0.1 =
* Bigger buttons than the default ones
* Keyboard navigation, left-right arrows and close using Esc key
* Click on the picture to go to next picture
* Click outside the picture to close

== Installation ==

Standard WordPress quick and easy installation:

1. Download.
2. Unzip.
3. Upload the shutter-reloaded-plus folder to the plugins directory.
4. Activate the plugin.
5. Go to "Appearance - Shutter Reloaded Plus" and set your preferences.

= Upgrade =

1. Deactivate and delete the old version.
2. Upload and activate the new one.


== Frequently Asked Questions ==

= I have ... plugin installed that uses javascript, will there be any conflicts/incompatibilities? =

Since Shutter Reloaded Plus does not use any js libraries, it does not interfere with them.

= What will happen if my site visitors have JavaScript disabled? =

Then none of your links will be changed and will work as usual.

= I have a thumbnail link but it points to a webpage, not to image. Will that affect Shutter Reloaded Plus? =

No, Shutter Reloaded Plus looks only for links pointing to an image (with thumbnails or not), and will not change any other link, even if the link has the same CSS class used for activation.


== Screenshots ==

For demo and screenshots visit the home page for [Shutter Reloaded Plus](http://www.itinfo.ro/shutter-reloaded-plus/).
