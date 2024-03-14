=== MW Google Maps ===
Contributors: inc2734
Tags: GoogleMaps, google, map, route
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

MW Google Maps adds google maps in your post easy.

== Description ==

MW Google Maps adds google maps in your post easy.

= Shortcode =
* **[mw-google-maps]**  
Map of this post has been displayed.

* **[mw-google-maps zoom="18"]**  
Map that zoom level is 18.

* **[mw-google-maps id="352"]**  
Map that post_id is 353 is displayed.

* **[mw-google-maps-multi]**  
Map of this posts has been displayed.

* **[mw-google-maps-multi ids="19,85,330"]**  
Map that post_id is 19 or 85 or 330 is displayed.

== Installation ==

1. Upload `mw-google-maps` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Setting the plugin through the 'Options > MW Google Maps' menu.
4. Setting Google Maps in your post.
5. Add shortcode '[mw-google-maps]' in your post.

== Changelog ==

= 1.3.1 =
* Some bug fixes.

= 1.3.0 =
* Add zoom in [mw-google-maps].
* Saved zoom value in admin page are now reflected in the single map.

= 1.2.0 =
* Post type that set show_ui has been displayed in settings page.

= 1.1.2 =
* Change: Open only single info wndow .
* Added : Add mw-google-maps-window filter hook.

= 1.1.1 =
* Bugfix: [mw-google-maps-multi ids=""]
* Change: The Posts orderby is 'post__in' if set 'ids' in [mw-google-maps-multi].

= 1.1 =
* Added : Added mw-google-maps-multi's attribute 'use_route'. ex:[mw-google-maps-multi use_route=true]

= 1.0.4 =
* Added : Added max-width: none in Google Maps css.

= 1.0.3 =
* Change: Scrollwheel is false on mw-google-maps.
* Bugfix: Fix Zoom Control Button may not be displayed.

= 1.0.2 =
* Bugfix: mw-google-maps-multi shortcode.

= 1.0.1 =
* Editor, Author and Contributor can use MW Google Maps in posts.

= 1.0 =
* First Commit.