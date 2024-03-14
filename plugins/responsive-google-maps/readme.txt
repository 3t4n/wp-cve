=== Plugin Name ===
Contributors: Zaglov, imbaa
Tags: responsive, google, google maps
Requires at least: 4.0.1
Tested up to: 4.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This Plug-In displays responsive and configurable Maps by Google Maps in your WordPress Site.

== Description ==

The responsive Goolge Maps Plug-In brings Responsive [Google Maps API](https://developers.google.com/maps/?hl=de "Google Maps API") via the Google Maps API  to your site.
When the browser-window is resized, the Plug-In automatically recenters the map view to the desired coordinates. You can activate and deactivate map control elements, set the height and the type of map (road, satellite).

And that’s pretty much it.

**How to use**

The Plug-In requires at leas two values to work: the latitude and the longitude of the location to display

[responsive_map lat="51.44303" lng="7.01247"]

If you need to use the Shortcode inside a theme, just use `<?php do_shortcode('[responsive_map lat="51.44303" lng="7.01247"]') ?>`.

See it in action on [imbaa Kreativagentur](http://www.imbaa.de "imbaa Kreativagentur Essen")'s site.

**Infowindow**

Version 1.1.1 of Responsive Google Maps is capable of displaying infowindows on the markers location.
To enable an infowindow simply put your infowindow text inside the [responsive_map] Tag.

You can use HTML if you like.

[responsive_map lat="51.44" lng="7.01"]Infotext goes here[/responsive_map]

**Possible other Parameters:**


*   lng: 51.44  / Longitude
*   lat: 7.01 / Latitude
*   height = 400px / Height of the Container of the Map
*   zoom = 10 / Zoom Level of the Map
*   show_marker = true / Should a marker be displayed?
*   title = null / Title of the Marker
*   zoom_control = true / Enable or disable Zoom-Conrol
*   pan_control = true / Enable or disable Pan-Conrol
*   map_type_control = true / Enable or disable Map-Type-Conrol
*   scale_control = true / Enable or disable Scale Control
*   street_view_control = true / Enable or disable Street View
*   overview_map_control = true / Enable or disable Map Control
*   map_type (str) = road / Set Map Typ to Road („road“) or satellite („sattelite“)
*   scrollwheel = true / Enable or disable zooming via scrollwheel
*   draggable = false / Enable or disable draggable map. On touchscreen devices dragging is disabled
*   auto_open_info_window = false / Open info-window automatically, when map is loaded

**Coming up next:**

Custom marker images



== Installation ==

1. Upload the Plug-In Direcotry into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the [responsive_map] Shortcode in your Site

== Frequently Asked Questions ==

= The map controls do not display properly. What can I do!? =

This is a common issues on responsive sites. Try to use the following CSS-Code within your Themes style.css to fix the issue:

.responsiveMap label {
width: auto;
display: inline;
}
.responsiveMap img {
max-width: none;
max-height: none;
}

= Dragging the map on a touchscreen device is not working, why? =

The drag functionality is quite an issue.
The problem is, that when enabled, users will experience problems on touch devices.
They will be able to scroll over inside the map, but not be able to "overscroll" the map itself.

This can be quite frustrating. So the dragging feature is disabled on touchscreen devices.

== Changelog ==

= 1.2.6=

Removed absolute arguments

= 1.2.5 =

* Added support for custom marker through icon_url parameter.

= 1.2.4 =

* Added Settings page in backend. You are now able to enter an Google Maps API-Key. Without the API Key the map can not be displayed.

= 1.2.3 =
* Removed Google Sign-In due to bugs in map display


= 1.2.2 =
* Minor Bugfixes

= 1.2.1 =
* Changed Plug-In name to prevent update-problems with samenamed plug-in from Codecanyon

= 1.2.0 =
* Added loading indicator and RWD-Fix for some of the GoogleMaps control elements

= 1.1.2 =
* Hotfix: removed some PHP that shouldn't be in the plug-in

= 1.1.1 =
* Feature: Infowindow display
* Improvement: Asynchronous Loading of Google Maps API v3 added

= 1.1.0 =
* Enable or disable scrollwheel zooming.
* Enable or disable map-dragging.
* Fixed bug with title not showing on hovering over marker

== Upgrade Notice ==

= 1.1.1 =
We added the possibility of displaying an info window. ALso the Google Maps library now loads asynchronously and only if not already loaded by other plug-in