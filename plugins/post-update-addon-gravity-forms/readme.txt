=== Post Update Add-On - Gravity Forms ===
Contributors: alexusblack
Tags: gravity forms, post update, update post, post edit, edit post, change post, post change
Requires at least: 5.4.0
Tested up to: 6.3.0
Stable tag: 1.1.4
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Update/Edit a post or a custom post type posts with Gravity Forms.

== Description ==

This simple Gravity Forms Add-On allows to update/edit/change existing posts or custom post type posts.

How to use:

- In form settings open "Post Update"
- Press "Add New"
- (Required) Configure "Post ID" (number, insert merge tag or current page/post id)
- Configure Post Settings (Author ID, Post Status)
- Configure Tags & Categories & Custom Taxonomies
- Configure Post Title and Content manually or insert merge tags
- Configure Featured Image
- Configure Custom fields
- Configure Conditional logic
- Press "Save Settings"

Features:

* Update Author and Status
* Update "Post Title" and "Post Content"
* Update "Tags", "Categories" and "Custom Taxonomies"
* Update "Featured Image"
* Update "Post Custom Fields"
* Optional update if the field is empty
* Combine values with merge tags
* Conditional logic support

== Installation ==

1. Install the plugin either via the WordPress.org plugin directory, or by uploading the files to your server
2. Activate the plugin via the Plugins admin page.

== Screenshots ==

1. User Interface

== Changelog ==

= 1.1.4 =
- Added support for data type casting. Example: logo<post_id> in key for custom field. This would make sure that uploaded images saved as a post_id.

= 1.1.3 =
- Support for custom taxonomies

= 1.1.2 =
- Support for checkboxes and similar fields
- Featured image update bugfix

= 1.1.1 =
- Update only non-empty fields option

= 1.1.0 =
- Current post target feature
- Update tags feature
- Update categories feature
- Update featured image feature
- Allow empty page content feature
- Allow empty featured image feature
- Code refactoring
- Minor UI improvements

= 1.0.1 =
* Making the plugin fully translatable
* Bumping WP version to latest tested

= 1.0.0 =
* Initial release
