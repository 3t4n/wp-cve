=== Plugin Name ===
Contributors: lpeharda
Donate link: http://lukapeharda.com/
Tags: comments, spam
Requires at least: 3.1.0
Tested up to: 3.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple and easy way of adding post type archive links to your menu. Links will be highlighted when you navigate to custom archive or single item page.

== Description ==

Simple and easy way of adding post type archive links to your menu.

Features:

* Links will be highlighted (active) upon navigation to eiter archive page or single item page.
* Works from WordPress version 3.1

== Installation ==

Install from WP admin of follow these instructions:

1. Unzip `post-type-archive-in-menu.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to WordPress Appearance->Menu and start adding archive links to your menu

== Frequently Asked Questions ==

= I have activated the plugin but can't see the "Archives" box on menu editing page?! =

* Make sure that your theme [supports menus](http://www.devlounge.net/code/how-to-add-support-for-menus-in-your-wordpress-theme).
* If above didn't help [enable "Archives" box in "Screen Options"](http://codex.wordpress.org/Administration_Screens#Screen_Options)(when on menu editing page).
* Make sure that you have some custom post types and that they [support archives functionality](http://codex.wordpress.org/Function_Reference/register_post_type#Arguments)(has_archive property).

= I can see the "Archives" box but it is disabled?! =

Make sure that you have created a menu.

= I have successfully added the links but they aren't showing on my menu = 

Make sure that you have selected correct menu in "Theme Locations" box and did display this menu in your theme.

== Screenshots ==

1. This is how "Archives" can be selected and added to your menu in WP menu administration.
2. Sample of how menu items are added (and highlighted). Sample data used.

== Changelog ==

= 1.0.1 =
* Fixed minor issues with 3.5 and up.

= 1.0 =
* Initial version.
