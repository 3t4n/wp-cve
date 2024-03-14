=== Google Photos Gallery with Shortcodes ===
Contributors: nakunakifi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAZHU9ERY8W34
Tags: best gallery plugin, free gallery, gallery plugin, album, gallery, slideshow, photo, google photos, google picasa, image, images gallery, lightbox, justified gallery, picasa, picasa web, photo, photos
Requires at least: 3.0.1
Tested up to: 6.2
Stable tag: 4.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The best Google Photos Gallery plugin to display your Google Photo Albums on your WordPress blog. It is fully responsive and looks awesome. 

== Description ==

The best Google Photos Gallery plugin to display your Google Photo Albums on your WordPress blog. It is fully responsive and looks awesome. Google Photo Gallery is based on Google Photos API. Use the plugin to display your Google Photo Albums on your WordPress blog. Using the shortcodes it is simple to embed your albums. Images in album are displayed in a lightbox.

* See plugin example Google Photos Albums in <a href="http://wordpress-plugin-google-photos.cheshirewebsolutions.com/display-albums-grid/">Google Photos Albums</a>


== Installation ==

1. Unzip into your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make your settings, Admin->Google Photos->Settings
4. Use the 'Display Album' shortcode [cws_gpp_albums_gphotos] on a page of your choice.
5. To display the album's images place the shortcode, [cws_gpp_images_in_album_gphotos] on a page
6. Update the shortcode used in step 4 to include the result_page option. [cws_gpp_albums_gphotos results_page='page-slug-here']

== Screenshots ==

1. An example of Google Photos Album Grid View.
3. This is the default settings page. 
6. This is an example of the lightbox displaying photo you clicked on.

== Credits ==

* Lightbox2 (http://lokeshdhakar.com/projects/lightbox2)

== Changelog ==

= 4.0.3 =
* Improvement: Pro Only - Justified Image Grid 
* Security Fix: Sanitized paramaters

= 4.0.2 =
* Bug Fix: Lightbox Image Size was not being set when activated

= 4.0.1 =
* Removed obsolete code

= 4.0 =
* A rewrite of the plugin to support Google Photos API (Google deprecated Google Picasa API)