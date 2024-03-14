=== Google Maps GPX Viewer ===
Contributors: ATLsoft
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q8PALXTRBLFP8
Tags: google, google maps, mobile, mobile phone, gps, gps track, gps route, gps file, gpx, gpx file, kml, kml file, track, tracks, route, routes, waypoint, waypoints, elevation, elevation profile, POI, POI's, points of interest, OSM, openstreetmap, Open Street Map, OCM, opencyclemap, Open Cycle Map, wms, web map service, osgeo, osgeo map, position, map, maps, latitude, longitude, marker, geo, geoportal, geoblog, geolocation, icon, traffic, streetview, earth, biking, hiking, cycling, walking, offroad, fusion, google fusion, google fusion table, panoramio, images, pictures, tags, user-id, meter, miles, feet, toggle
Requires at least: 2.8
Tested up to: 3.5
Stable tag: 1.03

Place your GPS tracks with Google maps! Show an elevation profile and download button. Test the Map-editor.

== Description ==

**Demo pages**<br />
- [GPX file with multiple tracks and POIs](http://www.atlsoft.de/poi-marker-manager/#tabs1-engl) <br />
- [GPX Viewer, invite your visitors to drag there own gpx or kml on the map](http://www.atlsoft.de/gpx-viewer/) <br />
- [TourScout, lists all tracks, google placec search](http://www.atlsoft.de/tourscout/) <br />
- [TourScout Navi App, lists all tracks, google placec search](http://www.atlsoft.de/outdoor-with-adroid-maplab/) <br />
- [GPX & KML files with elevation profile](http://www.atlsoft.de/gps-track-hoehenprofil/#tabs1-engl) <br />
- [Topo maps from foreign countries](http://www.atlsoft.de/web-map-service/#tabs1-engl) <br />
- [Google Fusion Tables](http://www.atlsoft.de/fusion-table/#tabs1-engl) <br />
- [Yellow-Pages & Google Places](http://www.atlsoft.de/branchenbuch-yellow-pages/#tabs1-engl) <br />
- [Map search with Geocodes](http://www.atlsoft.de/geocode/#tabs1-engl) <br />
- [Track & POI Editor](http://www.atlsoft.de/map-editor-help/#tabs1-engl) <br />
 <br />
[Description, FAQ, Support blog](http://www.atlsoft.de/google-maps-gpx-viewer/#tabs1-engl) <br />

This plugin inserts google maps to your posts.<br />
Upload GPS tracks, trails or Points of Interests by gps files like kml or gpx<br />
Create and edit tracks, waypoints and POI's.<br />
Show an interactive elevation profile and download link on tracks & routes.<br />
Insert Panoramio pictures selected by tags or user ID.<br />
Add address or lat-lon markers with your own image or icon.<br />
In edit mode use the `Insert Map Button` to place your map or use shortcodes.
Specially adapted for geoportal owner and blogger like cycling, biking, walking, sailing, flying, offroad but usable for travelling, tourist office, car rental and real estate too.

**Features:**<br />
* GPS files KML or GPX <br />
* GPS Viewer drag KML or GPX on the map<br />
* TourScout<br />
* Map Editor<br />
* Panoramio Pictures<br />
* Elevation Profile on tracks<br />
* Google Fusion Table with query option and auto viewport<br />
* Open Street Map, Open Cycle Map, OSGEO Map <br />
* add unlimited WMS, OSM or OSGEO server<br />
* multiple maps on the same post<br />
* Marker-manager to toggle POIs<br />

**Supported ShortCodes**<br />
Insert the following shortcode [map option="value"] into your posts<br />
where **option** can be:<br />
**maptype=OSM**  values: roadmap, terrain, satellit, hybrid, Relief, OSM, 'OSM cycle', WMS<br />
**style="width:300px; height400px; margin:20px"**  must be css-conform<br />
**gpx="direct or absolute path to the gpx-file"**<br />
**kml=absolute path to the kml-file**<br />
**address="Berlin, Germany"** location by geocoded address<br />
**lat="51.093" lon="7.23544"** location latitude/longitude<br />
**z="15"** set zoom level  (0-20) depends on maptype<br />
**marker="yes"**  add location marker<br />
**infowindow="text to be published"** add Infowindow<br />
**markerimage="URL"** add custom marker image<br />
**pano="yes"**add images from Panoramio<br />
**panotag="tag/user-id"**select Ponoramio images by tag or user ID<br />
**traffic="yes"** add traffic layer<br />
**bike="yes"** add bicyle layer<br />
**fusion="934502,Location,postcode < 40000"** add google fusion table layer<br />
**mtoggle="yes"** markers list to toggle POIs<br />

== Installation ==

This section describes how to install the plugin and get it running.

1. Download and unzip `Google-Maps-GPX-Viewer.zip` in your `/wp-content/plugins/` directory
1. Activate the plugin through the `Plugins` menu in WordPress
1. In case of `updating` you may need to adjust your `option settings`.
1. In edit mode use the `Insert Map Button` to configure and insert maps or use shortcodes.
1. Use the WP-Widget Interface to get Widget Maps with POIs or a Map search
1. Since V 3.6 After Updating your Advance,- Pro- or Ultimate- Version go to the plugins settings page delete your key and press save settings, after that reenter the key and press save settings! 
== Screenshots ==

1. GPX file with track, waypoints, elevation profile and download link in your post
2. Map manager to style and configure your map 
3. Option setting page
4. Track and waypoint editor

== Frequently Asked Questions ==
=  Do you have questions or problems with Google Maps GPX Viewer drop me a line here =
[FAQ, description, demos, blog](http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/)


== Changelog ==

= 3.6 =
* Map search widget, <br /> 
* GPX upload widget, POI-widget, (advanced-version only)<br />
* TourScout Addon (pro-version only)
* TourScout Android App (ultimate only)

= 3.5 =
* bugfixes PLUGIN_NAME, Lat-Lon KML center

= 2.7 =
* incompatibility with jetpack solved, minor bugfixes

= 2.5 =
* extended PRO functionality Google Places (+ Local) search,

= 2.2 =
* POI Marker-Manager to toggle GPX Markers on/off,

= 2.1 =
* toggle kml overlay on/off, search geolocation

= 2.0 =
* multiple extensions like panoramio pictures, track and waypoint editor, track file upload

= 1.25 =
* map manager to style and configure maps

= 1.24 =
* full size mode bugfix

= 1.23 =
* KML/GPX bugfix

= 1.22 =
* Prepared for Mobile Sensor API.

= 1.21 =
* Elevation Profile and Download Link on GPX and KML files containing track data

= 1.10 = 
* new JS fkt. `showMarkers(map, sym, visible)` now you can control the visibility of GPX markers <br />
  sample call: `showMarkers(map_0, 2, false);` hides all `sym_2.png` icons
* bugfix: kml multimap

