=== ACF: Google Maps Field (Multiple Markers) ===
Contributors: Rajiv Lodhia
Tags: acf, custom fields, advanced custom fields, acf addon, google maps, maps, markers, pins
Requires at least: 4.7
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An advanced Google Maps field for ACF that allows you to add multiple markers/pins to a single map field.

== Description ==

A new field type for Advanced Custom Fields (ACF) that allows you to place multiple markers and choose multiple locations all on a single map field, resulting in a cleaner admin UI, better user experience for the CMS user and fewer Google Map API loads. The field will display the address for each map marker below the map so it's even more manageable.

This field type solves the problem of only being able to select one location with the standard ACF Google Map field.
To choose multiple locations, you'd normally be required to use a repeater field with a Google Map field in it. This can quickly become chaotic and difficult to keep track of/manage.

= Compatibility =

This ACF field type is compatible with:
* ACF 5

== Installation ==

1. Copy the `acf-google-map-multi` folder into your `wp-content/plugins` folder
2. Activate the Google Map (Multiple Markers) plugin via the plugins admin page
3. Register your Google Maps API key in your theme by following the instructions here: https://www.advancedcustomfields.com/resources/google-map/#requirements
4. Create a new field in ACF and select the Google Map (Multiple Markers) type under the jQuery category

== Usage Instructions ==

On the new Google Maps (Multiple Markers) field, you can:
- LEFT CLICK on the map to place a new marker
- RIGHT CLICK on a marker to remove it
- CLICK AND DRAG a marker around on the map
- SEARCH for a place or address in the search box
- HOVER over an address in the address list below the map to see which pin it corresponds to
- LEFT CLICK on the trash icon on a row in the address list to remove it's corresponding marker.

== Google Maps API ==

Your Google Maps API key will need the following APIs enabled:
- Geocoding API
- Places API
- Maps JavaScript API

== Credits ==

This is a modified/enhanced version of the standard ACF Google Map plugin, so some of the Javascript code derives from the original field. Credit for the original of this ACF field goes to the developers of Advanced Custom Fields.

== Screenshots ==
1. Example of the new field in action
2. Field configuration
3. Max pins

== Changelog ==

= 1.0.0 =
* Initial Release.

= 1.0.1 =
* Updated readme.
* Tested with newest version of WordPress.

= 1.0.2 =
* Fixed bug where the field value wouldn't properly reset when adding a new marker.
* Changed name from "Google Map" to "Google Maps"

= 1.0.3 =
* Bumped WordPress tested version to 5.9
* Adjusted text in readme.txt

= 1.0.4 =
* JS set to strict mode with "use strict";

= 1.0.5 =
* Retested with WordPress 6.0
