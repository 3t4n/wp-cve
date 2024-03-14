=== Plugin Name ===
Contributors: endortrails, philiparthurmoore, kcssm, nhuja
Donate link: http://graphpaperpress.com/
Tags: gallery, slideshow, images, portfolio, photos, photo gallery, lightbox, graphpaperpress
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.3.5

A minimalist slideshow plugin that creates a new gallery post type. Add slideshows to widgets, posts, pages and gallery posts.

== Description ==

The GPP Slideshow plugin for WordPress allows you to create minimalist image slideshows using the new Gallery post type or using WordPress' built in [gallery] shortcode on Posts and Pages.  The plugin comes with a  Widget for easily inserting a specific gallery into any widgetized are on your theme.  This plugin requires WordPress 3.1 and works best with [a Graph Paper Press theme](http://graphpaperpress.com/themes/).

[Live demo](http://demo.graphpaperpress.com/gpp-slideshow/)

[Release info](http://graphpaperpress.com/plugins/gpp-slideshow/)

[Support](http://graphpaperpress.com/support/)

== Installation ==

1. Upload the entire `gpp-slideshow` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Follow the prompts to configure your plugin options and update your permalinks.

== Screenshots ==
1. Example single gallery slideshow
2. Example gallery archive
3. Add new gallery page
4. Gallery options page

== Frequently Asked Questions ==

= Basic Usage =
Activate the plugin and follow the prompts for configuring the plugin and updating your permalinks.  Visiting the permalinks page registers the new Gallery post type.  Next, visit the Gallery -> Add New page located in the left menu column, give your Gallery a title, use the Upload button to add images, click Save and Exit the Upload box.  When you Save of Preview the Gallery, your thumbnail images will appear below the Upload button.

You can add specific Galleries or Gallery Collections to your Menus using the Appearance -> Menu settings. If the options don't appear on the screen at first, use the Screen Options tab in the upper right of the page to make them available.

Always assign a Featured Image for each Gallery.  This Featured Image will become the image that represents the Gallery on the Archive page.

This plugin requires WordPress 3.1 and works best with [a Graph Paper Press theme](http://graphpaperpress.com/themes/).

= Image Dimensions =
The theme files packaged with this plugin use fluid css widths to integrate as best as possible with a wide variety of themes.  We do make some initial suggestions for image widths when adding new posts, but ultimately you must determine the best image sizes that fit your theme best.  This plugin doesn't resize any images you add; It merely displays whatever size images you add to it.

= Template Files =
This plugin contains two pre-built template files:

* single.php - Used to display single entries.
* archive.php - Used to display an archive page listing all single entries.

These template file are located in the plugin's theme folder.  Feel free to customize, if you know a bit of html and css.  Be sure to backup the original plugin theme before customizing.  If you do customize either the css or the template files, your modifications will be lost when you upgrade this plugin.  Always backup your modifications before updating.

= CSS Customization =
The default stylesheet (/css/style.css) uses fluid css widths to integrate as best as possible with a wide variety of themes.  We have packaged a fluid 12-column grid framework in this plugin.  It is best to add your custom css styles to your theme's style.css file and leave the default styles in this plugin alone.  If you do customize either the css or the template files, your modifications will be lost when you upgrade this plugin.  Always backup your modifications before updating.

== Upgrade Notice ==
* All new Javascript & CSS. If you use a caching plugin, please clear the cache so the new JS & CSS files are replaced.

== Changelog ==

= Version 1.3.5 =
* More control navigation issue fix.

= Version 1.3.4 =
* Control navigation issue fix.

= Version 1.3.3 =
* Resolved conflict with Sell Media.
* Updated Flexslider
* Image title and caption interchanged

= Version 1.3.2 =
* WordPress 3.5 media gallery bug fix.

= Version 1.3 =
* WordPress 3.5 media gallery support.

= Version 1.2.1 =
* Fixed bug on template redirect and archive conflict.

= Version 1.1.9 =
* Added support for password protection.

= Version 1.1.8 =
* Replaced query_posts with WP_query inside gpp_widget.php

= Version 1.1.7 =
* Browser resets added for better CSS inheritance
* Option to allow GPP sliders instead of generic WP gallery on category/archive pages

= Version 1.1.6 =
* Galleries and Gallery archives are now Responsive
* Supports multiple galleries per archive page

= Version 1.1.5 =
* Disable galleries on Graph Paper Press' blog template pages

= Version 1.1.4 =
* Add gpp-gallery-description class to gallery descriptions for CSS manipulation.

= Version 1.1.3 =
* Bug fix in taxonomy-collection.php

= Version 1.1.2 =
* Added ability to insert Galleries in any place on Posts and Pages.

= Version 1.1.1 =
* Change `if ( taxonomy_exists('collection') ) {` to `if ( is_tax('gallery_collections')) {` so that Collections pages display properly.

= Version 1.1.0 =
* Adding description to the single-gallery.php template.

= Version 1.0.9 =
* Plugin deactivation triggers error.  (Another) Bugfix.

= Version 1.0.8 =
* Plugin deactivation triggers error.  Bugfix.

= Version 1.0.7 =
* Adds taxonomy-collection.php for taxonomy archive.
* Check to show image captions by default option bug fix.

= Version 1.0.6 =
* Fixes image sort order

= Version 1.0.5 =
* Fixes gallery description not showing on archive page template

= Version 1.0.4 =
* Fixes widget title option not saving

= Version 1.0.3 =
* Fixes template redirect issue for gallery archive page

= Version 1.0.2 =
* Fixes stylesheet not loading bug

= Version 1.0.1 =
* Determining the width of the theme using $content_width
* Improves theme compatibility

= Version 1.0 =
* Initial release