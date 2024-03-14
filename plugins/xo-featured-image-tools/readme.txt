=== XO Featured Image Tools ===
Contributors: ishitaka, archidoc
Tags: featured image, thumbnail
Requires at least: 4.9
Tested up to: 6.5
Requires PHP: 5.6
Stable tag: 1.14.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically generate the featured image from the image of the post.

== Description ==

Automatically generate the featured image from the image of the post.

= Functions =

* Generate the featured image collectively.
* Automatically generate the featured image when you save a post.
* Bulk delete the featured image of the posts.
* Display the featured image items in the Post list.

== Installation ==

1. Upload the `xo-featured-image-tools` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress.

== Screenshots ==

1. Featured Image Tool.
2. Featured Image Tool Execution screen.
3. Post List.

== Changelog ==

= 1.14.0 =
* Added the ability to exclude specific file names.

= 1.13.1 =
* Adhered WordPress coding standards 3.0.1.

= 1.13.0 =
* Added a setting to whether to target shortcode content and synced pattern content.

= 1.12.1 =
* Fixed a bug that search did not work properly on the post list page of the admin screen.

= 1.12.0 =
* Fixed a bug that some images such as external images could not be acquired.
* Improved accuracy in acquiring featured images.
* Added a plugin action link.
* Supported PHP 8.1 and WordPress 6.2.

= 1.11.1 =
* Fixed a bug that caused a warning message to appear when deleting a post.

= 1.11.0 =
* Added xo_featured_image_tools_post_content filter.

= 1.10.0 =
* Added an option to exclude draft post.

= 1.9.1 =
* Fixed a bug that sometimes failed to acquire external images.

= 1.9.0 =
* Added support for image URLs with query parameters.
* Added xo_featured_image_tools_image_url filter.

= 1.8.0 =
* Added the function to batch delete featured images.
* Changed the post status to batch generate feature images.
* Added escaping to multiple translate texts for enhanced security.

= 1.7.0 =
* Fixed a bug that the featured image was not set correctly when skipping small images.
* Added support for WebP external image.

= 1.6.0 =
* Added support for gallery (block, shortcode).

= 1.5.0 =
* Added the option to exclude small images.

= 1.4.0 =
* Batch processing has been changed to process only posts that have no featured images to speed up processing.

= 1.3.0 =
* Added option to set default image.

= 1.2.0 =
* Migrated language packs to translate.wordpress.org (GlotPress).

= 1.1.0 =
* Support external images.

= 1.0.0 =
* Initial release.
