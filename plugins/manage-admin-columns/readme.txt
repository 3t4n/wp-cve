=== Manage Admin Columns ===
Contributors: sanbec, elemendas
Donate link: https://paypal.me/sanbec
Tags: featured image, admin columns
Requires at least: 5.0
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 1.5.0
License: GPL 3.0 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin adds a featured image column to WordPress Dashboard. It automatically adds a column to any post type which supports a featured image. 
Planning of adding another column for the slug.

== Description ==

This plugin adds a featured image column to WordPress Dashboard. It automatically adds a column to any post type which supports a featured image. It's an improvement from the plugin [Add Featured Image Column](https://wordpress.org/plugins/add-featured-image-column/).

* If the post has no featured image, it displays a "No image" svg icon to indicate this.
* If the featured image is broken, it displays a red "Broken image" svg icon to indicate this.
* The plugin opens a lightbox when click on featured image.
* You can enable or disable the blue border on hover.
* You can enable or disable the lightbox feature.
* You can choose the shape and size of the thumbnail displayed at the dashboard.


== Installation ==

= Minimum Requirements =

* WordPress 5.0 or greater
* PHP version 5.6 or greater
* MySQL version 5.0 or greater

= We recommend your host supports: =

* PHP version 7.0 or greater
* MySQL version 5.6 or greater
* WordPress Memory limit of 64 MB or greater (128 MB or higher is preferred)

= Installation =

Follow the steps below:

1. From your WordPress dashboard -> Go to 'Plugins' -> 'Add new' screen.
2. In the 'Search plugins...' field, enter "Manage Admin Columns" and choose it.
3. Press 'Install Now'.
4. After installation, click 'Activate'.

Alternatively:
1. Extract the zip file and drop the contents of the entire `manage-admin-columns` folder in the `wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

It's simple! Just activate, and visit the Settings > Manage Admin Columns to change the default behavior of the plugin.

== Screenshots ==
1. Circle, XL, border on hover posts featured images
1. Square, M, no border on hover posts featured images
1. Manage admin columns settings page
1. Featured Image Ligthbox
1. Icons for image broken and no image

== Changelog ==

= 1.5.0 =
* new: If the featured image is broken or invalid, it displays a red icon to indicate this.

= 1.4.0 =
* new: Lightbox setting
* improvement: Remove border on hover for noimage placeholders
* improvement: Simpler lightbox effect with tickbox

= 1.3.1 =
* new: The image columm opens in a Ligthbox on click
* improvement: Move Image Column after the select checkbox
* improvement: Changed function prefixes to namespaces
* improvement: Convert class into static
* improvement: Better code structure
* improvement: Better code to order columns
* fix: Wrong metadata
* fix: Bad syntax

= 1.3.0 =
* new: setting to choose if the image border is shown on hover

= 1.2.0 =
* new: svg image for posts with no featured image
* improved: better code 

= 1.1.5 =
* improved: output for posts with no featured image

= 1.1.4 =
* fixed: featured image column display on mobile

= 1.1.3 =
* Improved: any post type which supports featured images (including private post types) will display a featured image column
* Added: the args to get the list of post types has been added to the post types filter
* Changed: admin column heading is just "Image" instead of "Featured Image"

= 1.1.2 =
* Added: text_domain, language files
* Fixed (really): featured image column on mobile

= 1.1.1 =
* Fixed: featured image column on mobile

= 1.1.0 =
* Added: the featured image column is now sortable.
* Due to redundancy, this plugin now deactivates if Display Featured Image for Genesis is active.

= 1.0.1 =
* Added filter to exclude post types

= 1.0.0 =
* Initial release on WordPress.org

= 0.9.0 =
* Initial release on Github
