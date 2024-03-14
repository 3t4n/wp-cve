=== WenderHost Subpages Widget ===
Contributors: TheWebist
Plugin URI: http://wordpress.org/extend/plugins/wenderhost-subpages-widget/
Donate link: http://wenderhost.com
Tags: widget, subpages, hierarchy
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.5.3

A widget for displaying a list of subpage links. The list remains consistent regardless of where you are in the hierarchy.

== Description ==

A widget for displaying a list of subpage links. The list remains consistent regardless of where you are in the hierarchy.

Other features include:

* Widget displays only on pages with subpages or on subpages.
* List title is a link to the main page parent.
* You can specify the link text for the list title, or you can completely hide it.
* Sort subpages by _Menu Order_ or _Page Title_.
* Specify the depth of pages shown (all in hierarchy, all in flat list, 1 level, 2 levels, etc.)
* Presentation utilizes minimal markup to allow for easy styling via your theme's CSS.

== Installation ==

1. Upload the folder `wenderhost-subpages-widget` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure the plugin via Appearance > Widgets.

== Frequently Asked Questions ==

= How should I use this widget? =

Add this widget to the top of your sidebar. It will appear on pages that have subpages.

== Changelog ==

= 1.5.3 (09/15/2010) =
* Bug Fix: In some instances, `get_post_ancestors()` called in `get_parent_id()` was returning an empty array even though we were several levels down in the heirarchy. This would result in no subpages showing up on a page. A [little research](http://wordpress.org/support/topic/get_post_ancestors-page-gtid-is-always-empty "WordPress.org Forums: get_post_ancestors $page->ID is always empty") provided this `wp_cache_delete( $id, 'posts' )` to add to the top of `get_parent_id()`.

= 1.5.2 (09/10/2010) =
* Bug Fix: Added code to prevent widget from displaying on Search results pages.

= 1.5.1 (08/31/2010) =
* Bug Fix: Adjusted code to not echo any html when `Hide Title` is checked (previously an empty `<h3>` was being echoed).

= 1.5 (08/30/2010) =
* Added option to completely hide widget title.

= 1.4 (08/12/2010) =
* Added an option for setting the depth of pages to display.

= 1.3 (06/28/2010) =
* Bug Fix: `$parentID` was empty when viewing pages greater than 1 level below a top-level page.

= 1.2 (06/26/2010) =
* Completely re-wrote the plugin. The backend now uses the [WordPress Widgets API](http://codex.wordpress.org/Widgets_API#Example "WordPress Widgets API Example"). 
* The frontend simply displays the entire widget between `$before_widget` and `$after_widget`. The parent page displays between `$before_title` and `$after_title`. The subpages are listed inside a simple `<ul>`. This allows for a clean presentation that you can control via CSS.

= 1.0.1 (06/04/2007) =
* Updated function names to prevent naming conflicts with other plugins.

= 1.0 (05/23/2007) =
* v 1.0 - May 23, 2007 - Original Release date.