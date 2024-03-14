=== MyGeoPosition.com Geotagging: Geotags / GeoMetatags / GeoFeedtags / GeoMicrodata / Maps ===
Contributors: MyGeoPosition.com
Tags: Post, posts, plugin, admin, page, geo, geocoding, geo coding, geotagging, geo tagging, geo location, geolocation, tagging, geotag, geo tag, geotags, geo tags, geometatags, geo metatags, geo meta tags, geoposttags, geo posttags, geo post tags, geofeedtags, geo feedtags, geo feed tags, geomicrodata, geo microdata, geo micro data, geomicroformats, geo microformats, geo micro formats
Requires at least: 2.9
Tested up to: 4.2.2
Stable tag: 1.3.8

Create geo-posttags, geo-metatags & maps for posts + pages. An easy-to-use geopicker map with auto-locating functionality helps entering locations.

== Description ==

Create geo-posttags, geo-metatags, geo-feedtags, geo-microdata and maps for posts and pages. Display the geotagged location in form of a map before, after or within the post. An easy-to-use geopicker map with search, drag & drop and optional auto-locating functionality helps entering locations. Based on the http://mygeoposition.com GeoPicker API.

* Add Geo-Metatags to posts & pages
* Add Geo-Posttags to posts
* Add Geo-Feedtags to newsfeeds
* Add Geo-Microformats to posts & pages
* Add Geo-Microdata to posts & pages
* Add map with geotagged location to posts & pages
* Use geopicker map to search for a location and finetune result with drag & drop (MyGeoPosition.com / Google Maps API)
* Let the browser detect your position automatically (W3C geolocation API, FF4+, IE9+)

Supported geotagging:

* geo meta tags: geo.region, geo.placename, geo.position, geo.ICBM
* geo post tags: geotagged, geo:lat=xxx, geo:lon=xxx
* geo feed tags: geo(W3C geodata), geoRSS/KML, geoURL and ICBM
* geo microformats: http://microformats.org/wiki/geo
* geo microdata: http://schema.org/GeoCoordinates

Check http://api.mygeoposition.com/wordpress/ for more information.

Languages:

* English
* German
* Spanish ( thanks to Maria Ramos, WebHostingHub / http://webhostinghub.com )

== Screenshots ==

1. Geodata input on "Edit post" page: Will be used for Post-/Meta-/Feedtags. Enter it manually or use the geopicker tool.
2. Geopicker tool, that helps to search a location and finetune it using a drag & drop marker. Clicking the cross-hair button next to the search input will locate your position using the W3C geolocation API (FF4, IE9+). Clicking "Return data" fills all geodata input fields (above) automatically.
3. The plugin will automatically add geotags to your post tags.
4. Example: Generated Geo-Metatags in HTML source code
5. Example: Generated Geo-Posttags
6. Example: Generated Geo-Feedtags in RSS source code
7. Example: Generated Geo-Microtags in HTML source code
8. Example: Map of geotagged location after the post
9. Available settings.

== Changelog ==

= 1.3.8 =
* 2020-01-30
* Latest version of language files

= 1.3.7 =
* 2015-05-25
* Added microdata support (in http://schema.org/GeoCoordinates format)

= 1.3.6 =
* 2015-05-25
* Tested WordPress 4.2.2 compatibility

= 1.3.5 =
* 2015-03-11
* Removed "http:" from Google Maps API URL to avoid mixed content warning

= 1.3.4 =
* 2014-01-05
* Documentation updates

= 1.3.3 =
* 2013-11-24
* Added spanish language support (thanks to Maria Ramos)

= 1.3.2 =
* 2012-06-16
* Fixed file naming problem that caused an error in WordPress MU

= 1.3.1 =
* 2012-01-01
* WordPress 1.3 compatibility

= 1.3 =
* 2011-11-12
* Added microformats

= 1.2 =
* 2011-11-10
* Added maps of geotagged locations

= 1.1 =
* 2011-11-01
* Added Geo-Feedtags

= 1.0 =
* 2011-11-01
* Initial version

== Installation ==

1. Extract `mygeopositioncom-geotags-geometatags.zip`.
2. Upload the `mygeopositioncom-geotags-geometatags`-folder to your `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Upgrade Notice ==

NEW: Adds geo-microformats
NEW: Displays a map of the geotagged location before/after/within the post
NEW: Renders geo-feed-tags for newsfeeds

== Copyright info ==

"World Icons" are copyright by Oxygen Icons

* http://www.iconfinder.com/icondetails/9506/48/earth_internet_world_icon
* http://www.iconfinder.com/icondetails/8833/128/browser_earth_internet_network_planet_world_icon
* http://www.oxygen-icons.org/?page_id=4