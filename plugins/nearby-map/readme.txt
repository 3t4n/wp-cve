=== Nearby Map by Wabeo ===
Contributors: willybahuaud
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=A4P2WCN4TZK26&lc=FR&item_name=Wabeo&item_number=3
Tags: Nearby map, map, Leaflet, around, geolocalization, route, places, CloudMade, OpenStreetMap, planning event, Google Maps
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 0.9.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allow you to insert a map to show activities, places and services around a given geographical point.

== Description ==

Nearby Map allow you to easily insert a Map into your content. With it, you can show a main location and every interesting places around it.

The map is dynamic. You can show to users the best way to travel from your main location to any other one.
Nearby Map also includes SEO optimisation with Schema.org markup.

= Should I use Nearby Map ? =
  
Here's a few example to show you what you can do with Nearby Map

* You're planning an event, and you want to show every location involved in it (for example a wedding or a trip)
* You own a camping or an hotel, and you want to show to every customer what's around it
* You are planning a seminar, and you want to give to everyone informations about restaurants and hotels for this event.
* And everything else involving a map of course...

= Features =

1. It creates a custom post type to handle every location, with an easy metabox to enter every information about them.
2. It creates a nice map with custom markers and informations.
3. It adds a full interactive list of each place with more information for users.
4. Users have access to a navigation system betwen every location on the map.
5. for each place, Nearby Map creates a new page with more detail and geographical information

= How does Nearby Map works ? =

Nearby Map does not use Google Map, beacause there is other good librairies :

* [Leaflet](http://leafletjs.com/), in order to create the map and every Location (under [BSD open-source licence](http://fr.wikipedia.org/wiki/Licence_BSD))
* [OpenStreetMap](http://www.openstreetmap.org/), to gather geograhpical information (under [Open Data Commons Open Database License](http://opendatacommons.org/licenses/odbl/))
* [CloudMade](http://cloudmade.com/) to retrieve GPS coordinates, and in order to generate every route from one location to another [Creative Commons Attribution 2.0 License](http://creativecommons.org/licenses/by/2.0/)
* An [icon font](http://fontello.com) for every custom Markers ([maki, by MapBox (BSD)](https://github.com/mapbox/maki/blob/gh-pages/LICENSE.txt),[Typicons, by Stephen Hutchings (CC BY-SA 3.0)](http://creativecommons.org/licenses/by-sa/3.0/) and [Font Awesome, by Dave Gandy (CC BY 3.0)](http://creativecommons.org/licenses/by/3.0/))
* For Pins Maps , i've drawn my inspiration from [those icons](http://medialoot.com/item/free-vector-map-location-pins/)

= Nearby Map is optimised for SEO =

When a map is displayed, structured data from [schema.org](http://schema.org/) is included for each location.

= An adaptable plugin =

Nearby Map has been created with custom hooks and filter : you can override every data and action to improve or adapt the plugin to suit your needs. [FAQ](http://wordpress.org/extend/plugins/nearby-map/faq/)

== Installation ==

1. Upload the plugin's folder into `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use `[maps]` into your content area, or use `<?php echo nbm_render_map(); ?>` into one template file to render the map
4. Use `[place]` into your place content area, or use `<?php echo nbm_place_information(); ?>` into your single place (or specified CPT) template file to show information about current place
5. For working correctly, you need to enter at least 1 place, and you also need to define 1 of places as the central place

== Frequently Asked Questions ==

= How can I use an existing post type, instead of let the plugin creating one ? =

Using *nbm_post_type*. You just have to modify then paste this following code into your functions.php theme file.
`<?php add_filter( 'nbm_post_type', 'function_for_alter' );
function function_for_alter(){
	return 'posts';
} ?>`

= I want to change map style, is it possible ? =

Tired of the same old maps? CloudMade give ability for users to [use custom map or build thier own](http://maps.cloudmade.com/editor).
After choosing, you juste have to precise the id of your custom style into your functions.php, by the way of the filter cloudmade_style. 

`<?php add_filter( 'cloudmade_style', 'my_custom_style' );
function my_custom_style(){
	//If I want to use "midnight Comander coloration" style
	return 999;
} ?>`

Loading time tiles is still faster if you opt for a custom style proposed by CloudMade (8 possibilities).

You can see some examples at [screenshots section](http://wordpress.org/extend/plugins/nearby-map/screenshots/).

= Is there a way to use another tile provider than CloudMade ? =

Yes, there are other tile provider than CloudMade (used by default in this plugin). To chose for another, simply paste this function into your functions.php.

`<?php add_filter( 'maps_datas', 'function_for_alter' );
function function_for_alter( $maps_datas ){
	$maps_datas['tiles'] = "http://{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpg";
	$maps_datas['attribution'] = "attribution I want/use to show";
	$maps_datas['subdomains'] = array('otile1','otile2','otile3','otile4');
	return $maps_datas;
} ?>`

I tested some tiles providers, and I confirm they work with Nearby Map :

* mapquest (this one need to precise subdomains) :
	* http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpg
	* http://otile1.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg 
		* this one dont deliver tiles for high zoom level (except for USA)
* openstreetmap :
	* http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png
	* http://{s}.tile.osm.org/{z}/{x}/{y}.png
* mapbox
	* http://{s}.tiles.mapbox.com/v3/{user}.{map}/{z}/{x}/{y}.png
* OpenCycleMap
	* http://{s}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png
* Stamen
	* http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png
* ESRI
	* http://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}.png
	* http://server.arcgisonline.com/ArcGIS/rest/services/World_Shaded_Relief/MapServer/tile/{z}/{y}/{x}.png
* Open Weather Map
	* http://{s}.tile.openweathermap.org/map/clouds/{z}/{x}/{y}.png

You can also modify many other options using *maps_datas* filter

= Can I change default post type properties ? =

Yes, doing this into your functions.php theme file.
*places_args* filter 
`<?php add_filter( 'places_args', 'function_for_alter' );
function function_for_alter( $args ){
	$args['rewrite'] = array( 'slug', 'local business' );
	$args['supports'][] = 'custom-fields';
	return $args;
} ?>`

= Locate places with Nearby Map seem to be imprecise, can I improve precision of returned coordinates ? =

Use the filter namned *nbm_try_to_find_with_openstreetmap*. Just paste this :
`<?php add_filter( 'nbm_try_to_find_with_openstreetmap', '__return_false' ); ?>`

= I already have a CloudMade API key, can I use it ? =

You can use yours, using *cloudmade_key* filter hook, into your functions.php
`<?php add_filter( 'cloudmade_key', 'function_for_alter' );
function function_for_alter(){
	return 'dfsljfdjfsdjfqsjdkdfjkfqf'; //for example
} ?>`

= I dont want to see a list of all place behind the map, how can I remove it ? =

You can return false on *nbm_need_more* filter hook :
`<?php add_filter( 'nbm_need_more', '__return_false' ); ?>`

= I dont need route system also, how can I remove it ? =

You can return false on *nbm_need_route* filter hook :
`<?php add_filter( 'nbm_need_route', '__return_false' ); ?>`

= I dont have single page for Place, so I want to remove the link. Can I do ? =

Just return false on *nbm_places_link* filter hook :
`<?php add_filter( 'nbm_places_link', '__return_false' ); ?>`

= I want to proceed some change on the place query =

It's easy to rewrite all the query with *markers_querys*.
`<?php add_filter( 'markers_querys', 'function_for_alter' );
function function_for_alter( $m ){
	$m['order'] = 'DESC';
	$m['tax_query'] = array(
		array(
			'taxonomy' => 'type_of_place',
			'field' => 'id',
			'term' => 56
		)
	);
	return $m;
} ?>`

= I want to alter something in HTML returned for all places =

There is *nbm_map* for that...
`<?php add_filter( 'nbm_map', 'function_for_alter' );
function function_for_alter(){
	//Do stuff...
} ?>`

= I want to alter something in HTML returned datas of single place information =

Use *nbm_place_information* filter...
`<?php add_filter( 'nbm_place_information', 'function_for_alter' );
function function_for_alter(){
	//Do stuff...
} ?>`

== Screenshots ==

1. View of the main map, during a route animation
2. Some example of custom map styles
3. View of a place block information


== Changelog ==

= 0.9.3 =
* Solve a bug on post's permalinks which include "place" shortcode

= 0.9.2 =
* Different speed for route markers (bicyle, foot or car)
* Solve a minor bug when you donesn't want a central place

= 0.9.1 =
* Deliver a new filter hook for customize map styles (to choose from http://maps.cloudmade.com/editor)
* Solve a bug into *cloudmade_key* function

= 0.9 =
* Initial release
