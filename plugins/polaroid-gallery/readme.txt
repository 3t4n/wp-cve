=== Polaroid Gallery ===
Contributors: janisto, tashemi
Donate link: http://goo.gl/0gvUvm
Tags: image, images, gallery, media, library, photo, photos, picture, pictures, polaroid, lightbox, fancybox, jquery, css3
Requires at least: 3.1
Tested up to: 4.7
Stable tag: 2.2.0
License: Unlicense

Polaroid Gallery is a CSS3 & jQuery Image Gallery plugin for WordPress Media Library.

== Description ==

Polaroid Gallery is a CSS3 & jQuery Image Gallery plugin for WordPress Media Library. It is used to overlay images as polaroid pictures on the current page or post and uses WordPress Media Library. Using Polaroid Gallery you add unique view for your blog posts. Polaroid Gallery adds feeling of old good times. 
It is quite easy to use. All you need to do is to create standard wordpress gallery. All other things Polaroid Gallery will make for you by its own. Once you try it you love it.

Polaroid Gallery has translations for the following languages:

* English (default)
* Finnish

Use [Regenerate Thumbnails](http://wordpress.org/extend/plugins/regenerate-thumbnails/) plugin to regenerate thumbnails for all images that you have uploaded to your blog. 

For more information visit [WordPress Gallery support](http://en.support.wordpress.com/images/gallery/).
Plugin in use: 
[Demo 1](http://wp.mikkonen.info/summer-2010/)
[Demo 2](https://life-thai.com/chem-horosha-shri-lanka/)

Feel donating? You are wellcome [to donate](http://goo.gl/0gvUvm)

Would you like to add your language to the list? Contact [janisto](http://www.mikkonen.info/polaroid_gallery/) or [tashemi](info@life-thai.com)

== Installation ==

You can use the built in installer and upgrader, or you can install the plugin manually.

To do a manual installation of the plugin, please follow these steps.

1. Download the zipped plugin file to your local machine.
2. Unzip the file.
3. Upload the `polaroid-gallery` folder to the `/wp-content/plugins/` directory.
4. Activate the plugin through the *Plugins* menu in WordPress.
5. Configure any options as desired
6. Add a gallery and upload some images (the main gallery folder must have write permission).

== Frequently Asked Questions ==

= Why doesn't it work for me? =

1. Some other gallery plugin might be causing a conflict with Polaroid Gallery.

2. Test if the plugin works properly with the default theme. Your theme might also generate some kind of css conflict.

3. If plugin does not display corret after update please clear your cache

= How to choose when to use polaroid gallery and when default wordpress gallery? =

If you want to use default wordpress gallery in some places it is easy. Add to the gallery shortcode on the page attribute `usedefault="true"`. It should looks like `[gallery ids="1466,1464,1465" usedefault="true"]`

== Changelog ==
= 2.2 =
* Removed deprecated jQuery methods (.load(), .browser)
* CSS and javascript minified to fit Google Pagespeeds rules
* Fixed small css bugs
* Autocenterring gallery (works with specified number of columns only)

= 2.1.5 = 
* Fixed few bugs
* Update MobileDetect library up to 2.8.11

= 2.1.4 = 
* Fixed "Notice: Undefined variable: output"

= 2.1.3 =
* Fixed error "Undefined variable"
* Added posibility to choose when to use Polaroid Gallery on Pages with attr "usedefault"

= 2.1.2 = 
* Fixed SSL issue: error "blocked loading mixed active content" in FF when user opens webpage via ssl connection. 
* Bug fix. Default value for setting "Show Polaroid Gallery in pages" was ignored. 

= 2.1.1 = 
* Bug fix. Plugin now works properly with any thumbnail text settings.

= 2.1 =
* Fancybox  js-files moved on CDNjs.com
* Fixed bug with showing images from all galleries on the page.
* Fixed bug with z-index on paralax scrolling pages
* Prevent loading plugin's libraries if pages are displayed. Plugin loads only for single post
* Added option "Load gallery in list of posts"
* Support mobile phones
* Optimized script speed

= 2.0.7 =
* Minor bug fix. Plugin now displays properly more then 100 thumbnails per page.

= 2.0.6 =
* Localization support.
* Proper script/style loading.
* Updated JS libraries.

= 2.0.5 =
* jQuery / IE9 bug fix.

= 2.0.3 =
* Added Add scratches to thumbnails option.
* More CSS3 effects and better support for IE9.
* New screenshots.

= 2.0.2 =
* Added Thumbnail text visibility option.
* Fancybox upgraded to version 1.3.4.
* New screenshots.

= 2.0.1 =
* Added Ignore Gallery columns option for fluid layouts.
* Added Custom text option for "Image".
* Added Thumbnails text settings.
* Added Image text settings.

= 1.1.2 =
* Fixed: Safari failed to initialize properly.
* Fixed: IE8 transparent PNG issues.
* Fixed: Gallery container width issue.

= 1.1.1 =
* Fixed: Localization was missing from the thumbnail image (Image).

= 1.1.0 =
* Added localization support.
* Added admin menu. You can now choose between Medium, Large or Full size images for fullscreen overlay.
* Fixed: Post content was filtered too much.

= 1.0.0 =
* First public version.

== Screenshots ==

1. Choose between Medium, Large or Full size images for fullscreen overlay and configure rest of the options as desired.
2. Change your thumbnail size and optionally use Regenerate Thumbnails plugin to regenerate all thumbnails.
3. Click "Add an Image" in post or page and upload your pictures.
4. Edit title and caption for the images and then click "Save all changes".
5. Choose your order and Gallery columns and then click "Insert gallery".
6. Edit page.
7. Post page.
8. Fullscreen image.

== License ==

Polaroid Gallery is free and unencumbered [public domain][Unlicense] software.

[Unlicense]: http://unlicense.org/