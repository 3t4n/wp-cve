=== Events Manager - OpenStreetMaps ===
Plugin Name: 		Events Manager - OpenStreetMaps
Contributors: 		DuisterDenHaag
Tags: 				Events Manager, Maps, Open, Street, free, map
Donate link: 		https://useplink.com/payment/VRR7Ty32FJ5mSJe8nFSx
Requires at least: 	5.4
Tested up to: 		6.0
Requires PHP: 		7.3
Stable tag: 		trunk
License: 			GPLv2 or later
License URI: 		http://www.gnu.org/licenses/gpl-2.0.html


OpenStreetMaps for Events Manager. An add-on to replace Google Maps with OpenStreetMap. 0% Google, 100% open source.


== Description ==
> Requires [Events Manager](https://wordpress.org/plugins/events-manager/) to be installed & activated.

**0% Google, 100% open source.**
Use the free and open source OpenStreetMap to show your Events Manager Location Maps.

Existing locations work right out-of-the-box and creating new ones is extremely easy!

This plugin completely replaces the original Google Maps API (paid) with OpenStreetMap (open source). Once installed and activated, this plugin will automatically disable the Google Maps integration in Events Manager for you and replace them with OpenStreetMaps. When you uninstall this plugin, those adaptions will be reversed automatically.


**Available Options:**
- Set Marker Color per location.
- Set Map Type per location.
- Set default Marker Color.
- Set default Map Type.
- Set default Zoom Level.
- Show/hide Zoom Controls.
- Show/hide current Zoom Level.
- Show/hide Full Screen Control.
- Show/hide Map Scale (metric & imperial).
- Apply filters to change the default map tile server.
- Apply filters to change the single location map tile server.
- Apply filters to change the default marker markup.
- Apply filters to change the single location marker markup.

There are currently 12 different map tile servers available (overview in the Plugin Settings Page), but you can add your own by using filters.

**Geolocation Search** is currently not available when using this plugin.

== Localisation ==
* US English (default)
* Dutch (included & updated)
* French (included)
* German (included)

The plugin is ready to be translated, all texts are defined in the POT file. Any contributions to localize this plugin are very welcome!


== Feedback ==
I am open to your suggestions and feedback!
[Please also check out my other plugins.](https://www.stonehengecreations.n/creations/)


== Frequently Asked Questions ==
= Are you part of the Events Manager team? =
**No, I am not!**
I am not associated with [Events Manager](https://wordpress.org/plugins/events-manager/) or its developer, [Marcus Sykes](http://netweblogic.com/), in <em>any</em> way.

= Do I really need Events Manager? =
Yes, this plugin is an add-on for Events Manager. It cannot be used without it.

= What is the big benefit of OpenStreetMap? =
**1)** OpenStreetMap uses a free, open source platform.
Check their website for more information: [OpenStreetMap.org](https://www.openstreetmap.org/about).

**2)** This plugin with OpenStreetMap will <b><i>not</i></b> request any visitor (location) info to display the map. So, that makes it easier to include in your own GDPR compliance.

= Why are Map Sizes different? =
The maps shown in the meta boxes <em>(Add/Edit Location & Event in the back-end and the front-end submission forms)</em> have fixed dimensions of 400px X 300px (EM default) to ensure a correct display of the meta boxes.

You can set the dimensions for #_LOCATIONMAP (single location) in Events &rarr; Settings &rarr; Formatting &rarr; Maps.
These will also be the default dimension for the [locations_map]. If you wish to display the [locations_map] differently, you can set those dimensions from within the shortcode: `[locations_map width="500px" height="500px"]`

= My maps won't load / Map tiles all over the screen =
All EM OSM scripts and styles need to be loaded in a very specific order. Caching & optimizing plugins tend to combine multiple files into one. Please exclude the '/wp-content/plugins/stonehenge-em-osm/' folder in the settings of your optimization plugin to prevent these errors.

= Why is my map not visible? =
You probably have set your map dimension in percentages (100%). Please check Events &rarr; Settings &rarr; Formatting &rarr; Single Event Page &rarr; Single Event Page format.
Replace: `<div style="float:right; margin:0px 0px 15px 15px;">#_LOCATIONMAP</div>`
With: `#_LOCATIONMAP`
Because the div has no width set, it is automatically scaled to 0px. Therefore your map is filling 100% of 0px.

If you are using a caching plugin and/or optimizer plugin, please exclude wp-content/plugins/stonehenge-em-osm/ in the settings of that plugin. OSM Leaflet assets have to be loaded in a very specific order and such optimizers break that. All included assets are fully optimized already. A filter for AutOptimize is already built-in this plugin.

**All EM OpenStreetMaps are being wrapped in a div.**
You can target that with custom css in your stylesheet to best suit your theme's responsiveness. "#em-osm-map-container {}"


== Installation ==
1. First make sure the original Events Manager plugin is installed and activated.
2. Install and activate this plugin.
3. Upon activation this plugin will automatically disable the Google Maps integration in Events Manager for you.
4. Enter your free OpenCage API key and preferred settings in the options page.
5. Enjoy the free OpenStreetMaps on your website.


== Screenshots ==
1. Single Location Map.
2. Multiple Locations Map.
3. Select Map Style and Marker Color.
4. Edit Event Page (Front-End Submission).
5. Add padding to the map.
6. Apply filters to change markers and map tile server.


== Upgrade Notice ==


== Changelog ==
= 4.2.1 =
- Confirmed compatibility with WordPress 6.0 beta.


= 4.2.0 =
- Added: Support for EM Locations Types. (Did not add the URL field to a physical location as EM does not save nor use or calls it.)
- Updated: FontAwesome to version 5.14.0.
- Updated: FontAwesome is now only loaded if a Marker Filter has been applied. (User Requested Feature)
- Confirmed compatibility with WordPress 5.5.

= 4.1.8 =
- Confirmed compatibility with WordPress 5.4.3.

= 4.1.7 =
- Changes to the core code to prevent "intl module not installed", as this add-on does not use those shared functions.

= 4.1.6 =
- Typo.

= 4.1.5 =
- Bug fix in updater.

= 4.1.4 =
- Some code changes.
- Confirmed compatibility with WordPress 5.4.1.

= 4.1.3 =
- Changed WordPress dependency to minimal version 5.3, due to used functions.
- Implemented several fallbacks if the settings are incomplete.

= 4.1.2 =
- Minor bugfixes.

= 4.1.1 =.
- Typo corrected.

= 4.1.0 =
- Re-coded the core to be compatible with the upcoming **EM - PDF Gift Cards plugin.**</em>
- Minor bug fixes to be compatible with WordPress 5.4.
- Confirmed compatibility with WordPress 5.4.
- Confirmed compatibility with PHP 7.4.2.

= 4.0.5 =
- Added missing marker_shadow.png that prevented maps to be loaded correctly.

= 4.0.4 =
- Enhanced responsiveness of the Admin Map.
- Updated code to be fully compatible with PHP 7.4.

= 4.0.3 =
- Minor bug fix in the Location Select DropDown.

= 4.0.2 =
- Bug fix that always unchecked the "No Location" checkbox when editing an existing event.
- Bug fix on page load when creating a new Location in the "Add New Location" Page.
- Bug fix when using [events_map] shortcode.

= 4.0.1 =
- Small bug fix that checked the "No Location" checkbox when creating a new event.
- Bug fix when not selecting the first suggestion in the Ajax Location Search results.

= 4.0.0 =
- **NEW:** Use the `'em_osm_default_tiles'` & `'em_osm_location_tiles'` filters to add your custom map tile server url(s).
- **NEW:** Use the `'em_osm_default_marker'` & `'em_osm_location_marker'` filters to customize your marker(s) in color, shape & [FontAwesome 5 free](https://fontawesome.com/icons?d=gallery&m=free) icon (uses [Leaflet.ExtraMarkers](https://github.com/coryasilva/Leaflet.ExtraMarkers)).
- **NEW:** Added a section to the plugin settings page to clearly explain how to use the new filters.
- **NEW:** Admin maps now change real-time when changing the Map Type and/or Marker Color drop-downs.
- Added: A new screenshot.
- Removed: The need to place template files in your theme folder. This also solves issues with child themes and theme switching.
- Bug fix: Not showing a marker when "This event does not have a physical location" is unchecked.
- Bug fix: Lowered the z-index of the default Leaflet marker shadow (no longer on top of the pin &rarr; Made no sense in Leaflet).
- Bug fix: Removed a comma in the Multiple Map javaScript that prevented IE11 to display the map correctly.
- Updated: The .pot file for translations & the included Dutch translation. **(Revised translations in other languages are extremely welcome!)**

= 3.0.0 =
In Events Manager version 5.9.7 a lot of changes were made that influenced this plugin. Especially in how locations are now being saved and updated.

- Bug fix in a new location not being saved and going back to coordinates 0,0 (center of the world map = the ocean below Gold Coast, Africa).
- Bug fix in custom colors and markers not being saved for new locations.
- Bug fix in selected map type not being applied after Ajax search.
- Bug fix in if the marker is draggable or not - dragging the marker will change the found coordinates to place it even more accurate. **The marker is <em>only</em> draggable if the location address fields can be edited as well** (creating a new location in the Edit Single Event Page or creating/editing in the Edit Single Location Page).
- Tested & confirmed compatibility with Events Manager version 5.9.7.1.

= 2.9.9.1 =
- Minor bug fix that prevented the option to hide the zoom control buttons.

= 2.9.8 =
- Minor bug fix in responsiveness of the form fields in Edit Location page.
- Improved plugin dependencies.
- Some CSS changes for WordPress 5.3.
- Confirmed compatibility with WordPress 5.3.

= 2.9.7 =
- Disabled unavailable Geo Search options in the Events Manager settings to avoid confusion. This plugin does not support Geo Search, because EM has that much too entangled with Google Maps.

= 2.9.6 =
**User Requested:**
Added the argument for extra padding (in pixels) inside a multi-marker map.

- Map Bounds and Zoom Level are always automatically calculated, but this additional argument will allow you to zoom out a little.
- Can be applied to [locations_map] and [events_map].
- If not used, the map will (still) default to 10.
- Usage: [locations_map padding="50"].


= 2.9.5 =
- **User Requested:** Added four additional Stamen map tiles (Toner, Toner Lite, Terrain & Water Color).
	Their maximum zoom level is 18. Max zoom is automatically adjusted if one of these servers is selected, to prevent gray screens.
- Minor bug fix in responsive layout of the location select dropdown admin page.
- Updated readme.txt file.
- Confirmed compatibility with WordPress 5.2.4.
