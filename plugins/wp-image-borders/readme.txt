=== Plugin Name ===
Contributors: BenSibley
Tags: posts, post, images, image, blog, photo, photos, picture, pictures, remove image borders, add image borders, change image border styles
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 2.02
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Image Borders makes it easy to add decorative image borders to pictures in your blog posts.

== Description ==

WP Image Borders makes it easy to:

* automatically add borders around images in posts
* selectively add borders around images in any part of your site
* change image border styles
* change image border colors
* change image border width
* add shadows to images in your posts

To add borders around images in your posts, simply check the checkbox in the settings titled "Add borders to all images in blog posts." This will automatically add the borders you've created to all images in your blog posts.

To add borders around images in specific parts of your site, you can add **a comma-delimited list** of CSS classes of the image containers. You can use this to add borders to images in a certain part of your site, or in addition to adding borders to all images in your blog posts.

WP Image Borders comes with the following options for you to style your image borders with:

* 8 Border styles (solid, dotted, dashed, double, groove, ridge, inset, outset)
* Border width (in px)
* Border radius (in px)
* Border color (color picker)
* Drop shadows (distance, blur, spread, and color options)

Additionally, you can use this plugin to remove unwanted borders on images by leaving all border style options at their default values.

== Installation ==

1. Download the plugin
1. Go to your Plugins page, then click on Add new and select the plugin's .zip file
1. Alternatively, you can extract the contents of the zip file directly to your wp-content/plugins/ folder
1. Finally, go to your Plugins page and activate WP Image Borders


== Frequently Asked Questions ==

= How do I change the direction of the drop shadows? =

A positive Horizontal Distance value will put the shadow on the right side of the image, but if you make this value negative, it will move the shadow the same distance to the left instead.  If you create a negative value for the Vertical Distance, it will move the shadow above the image rather than below.

= What is Border Radius? =

Border radius is the level of curvature of the image's border.  A higher border radius will result in rounder corners, while a border radius of zero will result in crisp right angled corners.

== Screenshots ==

1. This is the WP Image Borders options page
2. This is an example of an image modified with WP Image Borders.  It's been given a white border and box shadow.

== Changelog ==

= 2.02 =
* updated incorrect namespace for color picker function

= 2.01 =
* fixed 404 to stylesheet

= 2.0 =
* improved image targeting
* simplified how custom css classes are handled
* internationalized
* minor style & copy updates to settings page
* codebase refactored and reorganized
* added additional sanitization and now preventing direct access to php files

= 1.5.3 =
* Fixed CSS 404 error

= 1.5.2 =
* Fixed bug with styles not enqueueing

= 1.5 =
* removed Support Dash
* added a default 'add' instead of default 'remove' checkbox
* added option to add image borders based on specific CSS classes
* now correctly enqueueing and adding inline styles
* updated plugin banner

= 1.4.5 =
* fixed mistake in uploading 1.4.4

= 1.4.4 =
* Updated Support Dash script

= 1.4.3 = 
* Fixed error in previous update

= 1.4.2 =
* Now works on all pages too

= 1.4.1 =
* Updated to override other css generated

= 1.4 =
* Fixed potential error with multisite installs

= 1.3 =
* Fixed bug where gray line would appear - certain unset fields now default to zero

= 1.2 =
* Fixed link error

= 1.1 =
* Fixed bug with drop shadows still appearing
* added link to review below options form
* added settings link on plugin page
* added screenshots
* add plugin banner to public plugin page
* changed tags
* added video tutorial

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.4.3 = 
* Fixed error in previous update

= 1.4.2 =
* Now works on all posts & pages

= 1.4.1  =
* will now override other plugin generated CSS

= 1.4 =
* fixed potential issue with multisite installs

=1.2=
* fixed gray lines bug

= 1.1 = 
* Fixed bug that left drop shadows on images

= 1.0 =
* This is the initial version of the plugin