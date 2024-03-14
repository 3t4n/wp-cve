=== Requirements Checklist ===
Contributors: dauidus
Author URI: http://dauid.us
Tags: requirements, require, required, requirement, publish, post type, metabox, wysiwyg, featured image, author, excerpt
Requires at least: 3.1
Tested up to: 4.2
Stable tag: 2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows admins to require content to be entered before a page/post can be published.

== Description ==

Requirements Checklist allows admins to require content to be entered before a page/post can be published.  Currently it supports requirements for the following areas on the add/edit screen:
** **
* title
* WYSIWYG editor
* featured image
* excerpt
* categories (allows for min and max number of categories, 1-3 & infinite)
* tags (allows for min and max number of tags, 1-5, 7, 10, 15, 25 & infinite)
* up to 5 custom taxonomies per post type (allows for min and max number of categories/tags, as detailed above)
* support for WordPress SEO by Yoast (Focus Keyword and Meta Description fields)
* support for All In One SEO Pack (Title, Description and Keywords fields)

Requirements Checklist uses OOP standards to add options only for those metaboxes which are supported for each post type and to execute code only on those pages where it is needed.  It works especially well for sites with many custom post types that require content to be entered in a specific way (ie. when a post type requires a custom excerpt or when the absence of a featured image will break the intended look of a post).  Think of any theme or plugin that supports an image slider powered by a featured image, and you can surely see where this plugin will come in handy.

To be clear, Requirements Checklist does absolutely nothing to the front-end of your site.  It simply forces certain types of data to be added to the add/edit page/post admin screen in order for that content to be published or updated.  If content requirements are not met, a draft can still be saved.

Requirements Checklist works with multisite networks and allows users to define settings on a per-site basis.

As of version 2.3.1, Requirements Checklist has support for localization.  To translate this plugin into your language, please contact the author.

Translators:
French - Jean-Michel Meyer (Li-An)

== Installation ==

Installation from zip:

1. From wp-admin interface, select Plugins -> Add New
2. Click Upload
3. Click "Choose File" and select post-type-requirements-checklist.zip
4. Click "Install Now"
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Add instructive text from the `settings -> Requirements Checklist` admin page

Installation from WordPress Plugin Directory:

1. From wp-admin interface, go to Plugins -> Add New
2. Search for "Requirements Checklist"
3. Click Install Now under the plugin name
4. Click Ok to install the plugin
5. Click Activate Plugin once installed
6. Add instructive text from the `settings -> Requirements Checklist` admin page

== Frequently Asked Questions ==

= Does it support Multisite? =

Yes.  This plugin can be either network activated or activated individually for each site on a network.

= How can I delete all data associated with this plugin? =

Simply delete this plugin to remove all data associated with it.  Deactivating the plugin will keep all plugin data saved in the database, but will not remove it.

= Can I save a draft if requirements are not met? =

Of course!  Just save as a draft as normal. 

== Screenshots ==

1. Publishing/updating is disabled until all requirements are met.
2. Once requirements are met, the user can publish/update.
3. Settings page (showing custom post type "Movies" with multiple custom taxonomies)

== Changelog ==

= 2.4 =
* adds support for All In One Seo Pack

= 2.3.2 =
* localization support actually working now

= 2.3.1 =
* localization support

= 2.3 =
* allow for add-on plugins in settings API
* adds support for WordPress SEO by Yoast
* now works with Drag & Drop Featured Image plugin (user request)
* adds subtle animations to edit page when requirements met

= 2.2.1 =
* fixed a bug which displays errors for some users

= 2.2 =
* admins can now set a number of maximum allowed categories and tags - this is based on current SEO standards for categories and tags, but allows for an unlimited option
* slight changes to settings page responsive style to play nicely with mobile devices
* settings pages now group each setting by content type and add help text for taxonomies

= 2.1 =
* Rename from "Post Type Requirements Checklist" to "Requirements Checklist"

= 2.0 =
* major release with new features
* add support for custom taxonomies (up to 5 per post type)
* rewrite some plugin logic for scalability
* slight changes to checklist style for readability
* slight changes to settings page style

= 1.0.2 =
* hide requirement checklist for post types that don’t utilize it

= 1.0.1 =
* small change to checklist style

= 1.0 =
* initial release

== Upgrade Notice ==

= 2.4 =
Adds support for All In One Seo Pack.  Now hides 3rd-Party features from those who don't need them.

= 2.3.2 =
Can now be translated in your language... for realzees!

= 2.3.1 =
Can now be translated in your language!

= 2.3 =
Adds support for WordPress SEO by Yoast and Drag & Drop Featured Images plugins.

= 2.2.1 =
Fixes a bug which displays errors for some users

= 2.2 =
Release with new features for maximum allowed number of categories and tags, based on current SEO standards.  Users are strongly urged to update.

= 2.0 =
Major release with new features.  Users are strongly urged to update.

= 1.0.1 =
Adds a small change to the checklist style that plays more nicely with text added to the publish metabox via other plugins.

= 1.0 =
initial release

