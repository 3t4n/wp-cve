=== Slider Pro ===
Contributors: bqworks
Donate link: https://bqworks.net/premium-add-ons/
Tags: slider, post slider, content slider, responsive slider, touch slider, carousel slider, image slider, thumbnail scroller, lightbox slider, animated layers, full width
Requires at least: 4.0
Tested up to: 6.4
Stable tag: 4.8.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Slider Pro is a responsive slider plugin that offers Premium features for FREE, including animated layers, post content, full width layout and more.

== Description ==

[Slider Pro](https://bqworks.net/slider-pro/) is a fully responsive and touch-enabled WordPress slider plugin that allows you to create professional and elegant sliders. This slider plugin was built with user experience in mind, providing a clean and intuitive user interface in the admin area and a smooth navigation experience for the end-users.

See a few examples on the [slider's presentation page](https://bqworks.net/slider-pro/).

Features:

* Fully responsive on any device
* Touch support for touch-enabled screens
* Set different configurations for the same slider, based on screen sizes (using breakpoints)
* Load images conditionally based on the size of the slider (different image sizes for different screen sizes)
* Animated and static layers, which can contain text, images or any HTML content
* Lightbox integration
* Full Width and Full Window support
* Carousel layout with looping/infinite scrolling
* Thumbnails that contain both images and text.
* Auto height based on inner content size
* Lazy loading for images
* Deep linking (link to specific slide inside the slider)
* Keyboard navigation
* Clean and intuitive admin interface
* Preview sliders directly in the admin area
* Drag and drop slide sorting for easy management of the slides' order
* Publish sliders in any post (including pages and custom post types), in PHP code, and widget areas
* Caching system for quick loading times
* Optimized file loading. The JavaScript and CSS files are loaded only in pages where there are sliders
* Load images (e.g., featured images) and content dynamically, from posts (including custom post types), WordPress galleries and Flickr
* Action and filter hooks to add to the functionality of the slider
* Import and export sliders between different plugin installations

[These videos](https://bqworks.net/slider-pro/screencasts/) demonstrate the full capabilities of the plugin.

[Premium Add-ons](https://bqworks.net/premium-add-ons/#sliderpro) allow you to further extend the functionality of the slider:

* [Custom CSS and JavaScript](https://bqworks.net/premium-add-ons/#custom-css-js-for-sliderpro): Allows you to add custom CSS and JavaScript code to your sliders in a syntax highlighting code editor. It also features a revisions system that will backup all your code edits, allow you to compare between multiple revisions and restore a certain revision.
* [Revisions](https://bqworks.net/premium-add-ons/#revisions-for-sliderpro): Automatically stores a record of each edit/update of your sliders, for comparison or backup purposes. Each slider will have its own list of revisions, allowing you to easily preview a revision, analyze its settings, compare it to other revisions or restore it.

== Installation ==

To install the plugin:

1. Install the plugin through Plugins > Add New > Upload or by copying the unzipped package to wp-content/plugins/.
2. Activate the Slider Pro plugin through the 'Plugins > Installed Plugins' menu in WordPress.

To create sliders:

1. Go to Slider Pro > Add New and click the 'Add Panels' button.
2. Select one or more images from the Media Library and click 'Insert into post'. 
3. After you customized the slider, click the 'Create' button.

To publish sliders:

Copy the [sliderpro id="1"] shortcode in the post or page where you want the slider to appear. You can also insert it in PHP code by using <?php do_shortcode( '[sliderpro id="1"]' ); ?>, or in the widgets area by using the built-in Slider Pro widget.

== Frequently Asked Questions ==

= How can I set the size of the images? =

When you select an image from the Media Library, in the right columns, under 'ATTACHMENT DISPLAY SETTINGS', you can use the 'Size' option to select the most appropriate size for the images.

== Screenshots ==

1. Slider with text thumbnails and animated layers.
2. Slider with carousel layout and captions.
3. Slider with image thumbnails.
4. Slider with mixed content.
5. Slider with right-side thumbnails.
6. The admin interface for creating and editing a slider.
7. The preview window in the admin area.
8. The layer editor in the admin area.
9. The main image editor in the admin area.
10. Adding dynamic tags for sliders generated from posts.

== Changelog ==

= 4.8.8 =
* fix deprecation notice regarding optional parameter in php 8

= 4.8.7 =
* minor security hardening
* other minor fixes

= 4.8.6 =
* add support for deferred loading of scripts
* improve support for gallery slides
* other fixes and improvements

= 4.8.5 =
* modify user capabilities requirements for editing sliders

= 4.8.4 =
* add possibility to extend the sidebar settings panels
* other fixes and improvements

= 4.8.3 =
* add Gutenberg block

= 4.8.2 =
* add possibility to import sliders from plugin versions older than 4.0
* other fixes and improvements

= 4.8.1 =
* added code mirror editor to HTML textareas
* add filter for allowed HTML tags
* other fixes and improvements

= 4.8.0 =
* added the add-on installation interface

= 4.7.8 =
* allow the possibility to remove existing custom CSS and/or JavaScript

= 4.7.7 =
* fixed the inline CSS width and height of the slider

= 4.7.6 =
* fixed type of Width and Height from 'number' to 'mixed' to address validation issue

= 4.7.5 =
* allow more HTML tags inside the slider

= 4.7.4 =
* fixed layers' display option bug

= 4.7.3 =
* fixed dynamic URL bug

= 4.7.2 =
* improve modal windows' display
* fixed some bugs

= 4.7.1 =
* fix modal windows' display

= 4.7.0 =
* initial release on WordPress.org