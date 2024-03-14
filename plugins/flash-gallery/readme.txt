=== Flash Gallery ===
Contributors: ulfben
Donate link: http://amzn.com/w/2QB6SQ5XX2U0N
Tags: gallery, flashgallery, flash, slideshow, wall, album, fullscreen, picture, photo, image
Requires at least: 3.3
Tested up to: 3.3
Stable tag: 1.4.1

Flash Gallery is the only practical way to publish VAST amount of pictures in a post. Features full screen viewing, slideshows, albums and more.

== Description ==

The Flash Gallery plugin lets you turn your ordinary galleries into awesome image walls, with support for multiple albums per post, full screen viewing and slideshows.

It is *especially* usefull for when you have *lots* of images in a post, or want to present images sorted into separate albums.
Here's [an example with 175 images](http://game.hgo.se/blog/gotland-game-awards-2010/). 

I use the Flash Gallery primarily to controll the height of my posts. Whenever there would be more than 4 rows of thumbnails I replace the ordinary `[gallery]` with a `[flashgallery]`.  You can [browse through the archives here](http://game.hgo.se/cat/projects/) to see how that works.

If you value [my plugins](http://profiles.wordpress.org/users/ulfben/), please help me out by [Flattr-ing them](http://flattr.com/thing/367557/Support-my-WordPress-plugins)! Or perhaps [send me a book](http://www.amazon.com/gp/registry/wishlist/2QB6SQ5XX2U0N/105-3209188-5640446?reveal=unpurchased&filter=all&sort=priority&layout=standard&x=11&y=10)? Used ones are fine! :)

*//[Ulf Benjaminsson](http://profiles.wordpress.org/users/ulfben/)*

= Flash Gallery supports: =
* full screen viewing
* slideshow
* multiple albums/categories per post
* [deep Linking](http://game.hgo.se/blog/gamescom-2010/#/gamescom-2010/dsc_7722.jpg) (scroll down...) :)
* RSS-readers
* mouse- & keyboard interaction
* configurable gallery background, logo and highlight color.
* being enabled / disabled by the visitor, at any time.
* right-click menu with *"open image in new tab"* and *"copy image url"*
* complete fallback for Flash and JS-less browsers.

= Changes in 1.4.1 (2011-12-28) =
1. Sanitize URLs (cleaner, shorter links)
1. Keep multiple instances on same page from fighting over the URL
1. [Rewrote scaling routine to fix edge cases](http://wordpress.org/support/topic/square-images-cropped-in-fullscreen?replies=10)
1. Cleaned up some PHP error notifications
1. Stopped forcing the Google CDN for SWFObject

= Changes in 1.4 (2010-10-25) =
1. *Halved the amount of markup generated!*
1. Fixed no-flash fallback
1. Rewrote Flash-toggle to use query params instead of cookies
1. Less JS, less HTML, less work - more speed all around!

= Changes in 1.3.4 (2010-10-24) =
1. Added support for keyboard input in fullscreen
1. *Only includes scripts if gallery is used on current page* (performance!)
1. Removed [black backing of thumbnails](http://wordpress.org/support/topic/plugin-flash-gallery-thumbnail-black-border?replies=1)
1. Updated FLA to Flash CS5
1. Flash Gallery now requires [Flash 10 or newer](http://www.adobe.com/products/flashplayer/)!
1. Added error catching to avoid scripts blowing up on errors

[Older changelogs moved here](http://wordpress.org/extend/plugins/flash-gallery/changelog/).

== Changelog ==

= Changes in 1.4 (2010-10-25) =
1. Halved the amount of markup generated!
1. Fixed no-flash fallback
1. Rewrote Flash-toggle to use query params instead of cookies
1. Less JS, less HTML, less work - more speed all around!

= Changes in 1.3.4 (2010-10-24) =
1. Added support for keyboard input in fullscreen
1. Will only include scripts if gallery is used on current page (performance!)
1. Removed [black backing of thumbnails](http://wordpress.org/support/topic/plugin-flash-gallery-thumbnail-black-border?replies=1)
1. Updated FLA to Flash CS5
1. Flash Gallery now requires [Flash 10 or newer](http://www.adobe.com/products/flashplayer/)!
1. Added error catching to avoid scripts blowing up on errors

= Changes in 1.3.3 (2010-07-29) =
1. Added "hidetoggle"-parameter to hide the gallery toggle link
1. Avoid dry runs of the filter (performance)

= 1.3.2 (2010-07-05) =
1. Fix for 1.3.1 breaking the rich text editor.
1. Added "delay"-parameter to set slideshow speed.
1. Improved thumbsize detection.

= 1.3.1 (2010-07-04) =
1. Halfed the gallery size (113KB to 50KB!)
1. The gallery sleeps (consumes no CPU) when the mouse leaves.
1. Proper fallback for visitors lacking javascript or Flash.
1. "Enable / Disable"-option for visitors to revert to the ordinary gallery at any time.
1. Right-click menu now features *"open image in new tab"* and *"copy image url"*.
1. Gallery auto-detects thumbnail size.
1. Scaling option "fit" now considers menubar, to avoid cropping the image.
1. Upgraded scripts to SWFObject 2 and SWFAdress 2.4
1. All scripts moved to site footer - allowing page to render before downloading the flash gallery.
1. [Lots of new options](http://wordpress.org/extend/plugins/flash-gallery/faq/). 

= 1.0 (2009-06-27) =
1. Initial release

== Upgrade Notice ==

= 1.4.1 =
Fixes and tweaks. Recommended.

= 1.4 =
Massive performance improvements. Upgrade!

= 1.3.4 =
Fullscreen keyboard support. [Flash Player 10](http://www.adobe.com/products/flashplayer/) required!

= 1.3.3 =
Added "hidetoggle"-parameter to disable the gallery toggle link. Avoid dry runs (performance!).

= 1.3.2 =
Fix for 1.3.1 breaking the rich text editor, added "delay"-parameter to set slideshow speed. Improved thumbsize detection.

= 1.3.1 =
Half the size, twice the speed. Sleeps when unused. Auto detect thumb size, safe fall back for flashless visitors. Lots of new options. 

== Installation ==

1. Unzip to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use [flashgallery] instead of [gallery] in your posts.

= Usage = 

Upload your images [like usual](http://wordpress.org/extend/plugins/flash-gallery/faq/), but use the [flashgallery]-shortcode instead of [gallery]. 

[For configuration, see Frequently Asked Questions](http://wordpress.org/extend/plugins/flash-gallery/faq/).

== Frequently Asked Questions ==

= How do I configure Flash Gallery? = 

All settings are optional, and made through the shortcode syntax. 

Here's an example that will display a gallery with five rows, 650px high, and 3 Albums with 10 images in each:

	`[flashgallery orderby="ID" rows="5" height="650px" albums="Prep_10 % Exhibit_10 % Presentations_10"]`

It's nothing fancy - a title, an underscore and the number of images you want to put in that album. Separate each album with "%". So if there's 100 images attached to a post and you do; 

`[flashgallery albums="First Album_50 % Second_25 % Third_25"]`

You'd get three albums, "First Album" would get the first 50 images of the post, "Second" would display the next 25 and "Third" would display the last 25.
	
All the regular `[gallery]` parameters still apply ('cept for columns), and you've got these extra to play with:
	
* 	`cats = "Album1_12 % Album2_33 % Album3_66"`: **deprecated since 1.3, use `albums` instead**
*	`albums = "Title1_10 % Another Title_20"`: *two albums, and their image count*
*	`height = "400px"`, "100%"
*	`hidetoggle = "false"`: *true to not show the toggle gallery-link.*
*	`delay = "3"`: *time to display images in slideshow, in seconds.*
*	`exclude = "39,42"`: *exclude images with ID 39 & 42*
* 	`rows = "3"`: *number of rows in the gallery thumbnail view.*
*	`background = "background.jpg"`: *URL to high-rez background*
*	`logo = "logo.png"`: *URL to logo*
*   `transparent = "false"`: *set flash's wmode*
*	`scaling = "fill"`, "fit" or "noscale": *how images are scaled when displayed(default: fit)*
*	`thumbsize = "110"`: *size (in pixels) of thumbnails* **ignored. gallery auto-senses thumbsize since 1.3**
*	`color = '0xFF0099'`: *color of the interface highlights*	
*	`usescroll = 'true'`: *enable browsing by scrollwheel*
*	`showtitles = 'false'`: *display picture title over thumbnail*
*	`allowdownload = 'true'`: *give option to download image from right-click menu*
*	`rowmajor = 'false'`: *arrange thumbs in column order or row order? (eg. top-to-bottom or left-to-right)*
*	`animate = 'true'`: *let thumbs fly into position*

Notice that color is a hexadecimal value with the `0x`-prefix, not `#` as is common in CSS and web development.

= My thumbnails overlap / look wierd / behaves badly =
Flash Gallery assumes you've got square thumbnails - same width as height. Make sure you've checked "`Crop thumbnail to exact dimensions`" in WordPress Admin -> Media.

You can run [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) to apply your new settings to all images you've uploaded.

= Where's the FLA-source? =
[In the development version](http://wordpress.org/extend/plugins/flash-gallery/download/).

= How do I create WordPress galleries?  =
Use the built-in media uploader to create and insert galleries in your posts. See [the screencast](http://wordpress.org/development/2008/03/wordpress-25-rc2/) (at 01:35), or [read the instructions](http://codex.wordpress.org/Using_Images#Inserting_Images_into_Posts).

= How do I ask for help? =
1. Search [the forums](http://wordpress.org/tags/flash-gallery?forum_id=10) and post in a relevant thread if one exists.
1. Always tag your post with `Flash Gallery`
1. State your problem succintly, *provide a link*!
1. Always tag your post `resolved` and publish your solution.

= Can I help you in any way? =
Absolutely! Please help me out by [Flattr-ing my plugins](http://flattr.com/thing/367557/Support-my-WordPress-plugins), or [send me a book](http://www.amazon.com/gp/registry/wishlist/2QB6SQ5XX2U0N/105-3209188-5640446?reveal=unpurchased&filter=all&sort=priority&layout=standard&x=11&y=10)? Used ones are fine! :)


== Screenshots ==

1. Full screen gallery view, with the Album-selector opened.
2. Gallery view as seen in a post (before entering full screen mode)
3. Single-image view.

== Other Notes ==

The included FLA-source is a heavily modified version of [Jerald Zhan's](http://www.zcube.sg) stock [zGallery](http://flashden.net/item/zgallery-v1-fit-2-screen-xml-gallery/43071).

Jerald graciously allowed me to distribute the modified source for everyone to benifit from the improvements:
	
* WordPress integration

* deep linking supports

* scaling options (fit, fill, no scaling)
	
* mouse- & keyboard interaction	
	
* exposing all practical settings to flashvars
	
* various tweaks and fixes	


Copyright (C) 2009-2012 Ulf Benjaminsson (my first name at ulfben dot com).

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA