=== IMG Mouseover ===
Contributors: tychay
Donate link: http://www.kiva.org/lender/tychay
Tags: images, mouseover
Requires at least: 2.9
Tested up to: 3.0
Stable tag: trunk

Embed a mouseover image into a regular image tag without resorting to hacky inline Javascript.

== Description ==

This allows you to create a simple image mouseover feature by adding properties to an image:

It understands the following attributes which can be added to the HTML:

* img.class="mouseover" (activates IMG mouseover for this image)
* img.oversrc (If specified, this rewrites the image on mouse rollover)
* img.clicksrc (If specified, this rewrites the image on mouse click)
* img.noresize (If set, this will make the image have the natural dimensions instead of the dimensions of the original image).
* a.class="mouseover" (activates A tag as a controller for another image, when clicked a class "selected" is tacked on)
* a.for (Should be specified to point to target image. Image must be class mouseover)
* a.for_link (If specified the ID of a link is replaced with this one)
* a.src (If specified, the click will replace the image with this)
* a.oversrc (If specified, the click will replace the image mouse rollover with this)
* a.clicksrc (If specified, the click will replace the image mouse click with this)

== Installation ==

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`. Then just visit your admin area and activate the plugin. That's it!

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== ChangeLog ==

**Version 1.4.1**

* Fixed bug where mouseovers broke in version 1.4 when they are placed outside of post_content (for instance, in a widget). Note that these cannot be made HTML5 compliant (currently). (Thanks, FlowerLark for helping me find the bug.)

**Version 1.4**

* HTML 5 compliant (no need to change your code, but you may need to clear supercache) as per new dataset attributes
* Added GPL 2 license to code.
* Updated documentation to note testing on WordPress 3.0

**Version 1.3**

* TinyMCE (Visual Editor) no longer munges the HTML code

**Version 1.2**

* Fixed bug that required PHP 5 in order to run.

**Version 1.1**

* Support for link controllers

**Version 1.0**

* Initial release
