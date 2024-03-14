=== Admin Menu Tree Page View ===
Contributors: butterflymedia
Donate link: https://www.buymeacoffee.com/wolffe
Tags: admin, page, page tree, hierarchy, cms, tree, view, admin menu, menu
Requires at least: 4.9
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 2.8.5
License: GNU General Public License v3 or later

Get a tree view of all your pages directly in the admin menu. Search, add, edit, view, re-order – all is just one click away!

== Description ==

The **Admin Menu Tree Page View** plugin adds a tree-view layout to all your pages - directly accessible in the admin menu. This way, all your content will be available with just one click, no matter where you are in the admin area.

You can also add posts, pages and custom post types directly in the tree and you can quickly find your pages by using the real-time search box.

[Homepage](https://getbutterfly.com/wordpress-plugins/admin-menu-tree-page-view/) | [2.8 Refactor](https://getbutterfly.com/admin-menu-tree-page-view-2-8-refactoring-update/)

#### Top features
* Change the order of your pages with drag-and-drop
* View all your pages - no matter where in the admin you are
* View the hierarchy (parent/child relationship) of your pages
* Add pages directly after or inside another post - no need to first create the post and then select the parent
* Adds link to view a public post type directly from the admin menu - no need to first edit the post and then click the view link

Works perfectly in WordPress installations with lots of pages in a tree hierarchy.

== Installation ==

1. Upload the folder "admin-menu-tree-page-view" to "/wp-content/plugins/"
1. Activate the plugin through the "Plugins" screen in WordPress
1. Done!

Now the tree with the pages will be visible in the admin menu to the left, in the **AMTPV (CMS)** top level menu.

== Screenshots ==

1. Main plugin page
2. Adding new content after or inside another post type
3. Searching for a post type

== Changelog ==

= 2.8.5 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Removed old files (3)

= 2.8.4 =
* UPDATE: Updated WordPress compatibility

= 2.8.3 =
* FIX: Removed top-level menu and moved it to the Settings menu
* FIX: Removed the Content tab and made the Dashboard default
* FIX: Used proper semver versioning to avoid issues with the WordPress.org repository

= 2.8.2 =
* UPDATE: Reverted adding all public post types
* UPDATE: Reverted removal of the page tree from the admin menu

= 2.8.1 =
* FIX: Fixed and improved sorting to adhere to the latest jQuery UI included with WordPress 6+
* FIX: Fixed caching issues with the tree view after re-ordering content
* UPDATE: Added a "Tree View" menu item under each public post type for easier access
* UPDATE: Added a "Settings" link on the "Plugins" screen
* UI: Fixed dropdown size by decreasing the font size
* UI: Fixed post/page pop-up margin and padding for the list of new post/pages
* UI: Fixed post/page pop-up misaligned radio boxes by removing the margin and adding padding
* UI: Added placeholder element when reordering posts and pages
* PERFORMANCE: Removed legacy nestedSortable add-on for jQuery UI

= 2.8.0 =
* UPDATE: Refactored plugin to use a top level menu page
* UPDATE: Added all public post types
* UPDATE: Removed expand/collapse functionality
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated screenshots
* PERFORMANCE: Removed cookies
* PERFORMANCE: Removed unused files
* PERFORMANCE: Removed external images

= 2.7.7 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated copyright year
* UPDATE: Added WPCS ruleset

= 2.7.6 =
* FIX: Fixed global variable
* UPDATE: Updated WordPress compatibility

= 2.7.5 =
* FIX: Added properly enqueued scripts (changed hook from `admin_init` to `admin_enqueue_scripts`, added version number and moved scripts to footer)
* UPDATE: Updated PHP compatibility (PHP 7+)

= 2.7.4 =
* FIX: Fixed +/- icon being too "fussy"
* FIX: Fixed PHP 8 warning
* UPDATE: Updated PHP compatibility
* UPDATE: Removed unused files
* UPDATE: Standardized plugins_url() path and removed some obsolete constants

= 2.7.3 =
* UPDATE: Updated WordPress compatibility
* UPDATE: Updated CSS for modern browsers and WordPress 5+
* UPDATE: Updated visual assets
* UPDATE: Removed unused files

= 2.7.2 =
* FIX: Fixed conflicts with other post types (thanks Steph Wells (@sswells))
* UPDATE: Removed unused jquery.client.js file
* UPDATE: Updated WordPress compatibility

= 2.7.1 (August 2018) =
* Don't only rely on nonce when adding or moving pages, also check current user capability.

= 2.7 (January 2018) =
* Add nonce check when moving page and adding new pages.

= 2.6.9 =
* Make hidden page not clickable. Fixes https://wordpress.org/support/topic/hide-the-page
* Load icons localy instead. Fixes https://wordpress.org/support/topic/styles-loading-icon-pngs-over-http-instead-of-https

= 2.6.8 =
* Added German translation. Thanks [Michael Thielemann](https://www.thielemann.eu).

= 2.6.7 =
* Added Spanish translation. Thanks Andrew Kurtis/[WebHostingHub](https://www.webhostinghub.com/).

= 2.6.6 =
* CSS fixes for new admin theme in WordPress 3.8. Thanks to Américo Cruces for making most of the the nice changes.

= 2.6.5 =
* Fixed a bug that caused errors when bulk editing posts

= 2.6.4 =
* Added Italian translation. Thanks!

= 2.6.3 =
* Added Dutch translation. Thanks!

= 2.6.2 =
* Added Slovak translation. Thanks Branco.

= 2.6.1 =
* Hopefully loads scripts and styles over SSL/HTTPS if FORCE_SSL is set.

= 2.6 =
* Fixes for popup on WP 3.5
* Replaced live() with on() for jQuery
* Small CSS fixes, for example search box label being a bit off

= 2.5 =
* Fix for search highlight being to big

= 2.4 =
* Fix for flyout menu not working

= 2.3 =
* Fixed: major speedup, like 300 % faster generation of the tree
* Fixed: added is_admin()-check to the plugin, the plugin code is only parsed when in the administration panel. This could make the public part of your site some milliseconds faster.

= 2.2 =
* Fixed: icons where misplaced when using minimized admin menu.
* Fixed: page actions where not visible when using minimized admin menu.
* Fixed: hopefully works better with WPML now.

= 2.1 =
* Fixed: forgot to remove console.log at some places. sorry!
* Updated: Drag and drop now works better. Still not 100%, but I can't find the reason why I does order the pages a bit wrong sometimes. Any ideas?

= 2.0 =
* Added: Now you can order posts with drag and drop. Just click and hold mouse button and move post up/down. But please note that you can only move posts that have the same level/depth in the tree.

= 1.6 =
* Fixed: post titles where not escaped.

= 1.5 =
* Could not edit names in Chrome
* Removed add page-link. pages are instead added automatically. no more clicks; I think feels so much more effective.

= 1.4 =
* moved JS and CSS to own folders
* can now add multiple pages at once
* can now set the status of the created page(s)

= 1.3 =
* An ul that was opened because of a search did not get the minus-sign
* New "popup" with actions when hovering a page. No more clicking to get to the actions. I really like it!

= 1.2.1 =
* The plus-sign/expand link now works at least three levels down in the tree

= 1.2 =
* Tree now always opens up when editing a page, so you will always see the page you're ediiting.
* When searching, the parents of a page with a match is opened, so search hits will always be visible.
* When searching and no pages found, show text "no pages found".
* CSS changes for upcoming admin area CSS changes in WordPress (may look wierd on current/older versions of WordPress...)
* Some preparing for using nestedSortable to order the pages

= 1.1 =
* Children count was sometines wrong.

= 1.0 =
* Added functionality to expand/collapse

= 0.6 =
* View link now uses wordpress function get_permalinks(). Previously you could get non-working links.

= 0.5 =
* Swedish translation added
* Moved load_plugin_textdomain to action "menu" instead of "init"

= 0.4 =
* Fixed a couple of small bugs
* Prepare for translation
* Moved JS to own file

= 0.3 =
* Removed some notices
* Added a search/filter box. Search your pages in real time. I love it! :)

= 0.2 =
* Some CSS changes. The icons and text and smaller now. I think it's better this way, you can fit so many more pages in the tree now.
* Now you can add new pages below or as a child to a page. For me this has been the feature I've missed the most.

= 0.1 =
* It's alive!
