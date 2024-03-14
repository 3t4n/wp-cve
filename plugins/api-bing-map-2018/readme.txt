=== WP Bing Map Pro ===
Contributors: dan009
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HH7J3U2U9YYQ2
Tags: bing map, api bing map, maps, bing, wordpress plugin bing map, plugin bing map, Woocommerce checkout address suggestions, address suggestions, address auto-complete woocommerce, 
Requires at least: 5.0.1
Tested up to: 6.3
Stable tag: 5.0
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Designed to create accesible maps from bing, with multiple options of pins, width, height, custom pins, and address.

== Description ==

WP Bing Map Pro is the right map for your wordpress website, with plenty of map settings, and pin, shapes options. (ALL FREE)
Create any shapes, with html customizable infobox (links, images included ). Also Checkout auto-complete address for WooCommerce.
This plugin comes with multiple pin location, coordinates, address, width, height, map zoom, custom pin url, HTML Class attribute, and map type.
In order to use this Plugin you need to register to bing website to get and API Key.
Newly added map views.
For support or suggestions please email me at: developer@tuskcode.com

Video tutorial on how to set up WP Bing Map Pro
[youtube https://youtu.be/NE3vFFaz91c]

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/wp-bing-map-pro` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Under the admin menu page 'API Bing Map 2018' you can configure the plugin.
4. In the 'WP Bing Map Pro' page make sure to add API key from bing maps website, a link will be displayed beside the input.
5. After all the custom setting have been applied, grab the shortcode  and place it in any of your pages body to display the map
6. For support or suggestions please email me at: developer@tuskcode.com



== Frequently Asked Questions ==

= What is the Map View doing = 
Map views are a great way to point the map to a specific location.
Eg: Simply create a map, with 10 pins, and 10 map view, each view pointing to each pin, and load maps according to pins positions.

= The map is not showing =
Make sure under the  setting page you have a valid Bing API KEY from [here](https://www.bingmapsportal.com/), and address

= Address Suggestion is not working =
Address suggestion for new pins is working only if you have a valid Bing Map API Key

= Why pins are not showing on the map =
First you have to create a pin under the 'Pins' tab, and after that, edit the map, and add the pin under the 'Pins' tab

= Can I set different sizes to my map =
Yes. Width and height can be specified with the dimensions specified in the settings page
Be aware when setting the map height to 100%, as it will not be seen on the map, but is there

= What is the 'HTML Attribute Class for? =
This field can be used to customise the map if you have CSS knowledge

= How can I have a pin with no address =
Simply find the coordinates of your location, and paste them in the fields - Latitude, and Longitude, leaving the address input empty

= How can I use the plugin to have auto-complete address fields in the Woocommerce checkout page? =
The plugin has integrated Bing Map Autosuggest module for addresses.
Go to Settings page of the plugin and enable autosuggest. Make sure you have a valid api key, and woocommerce active.

= Is this plugin working on multisites? =
This plugin has never been tested on multisite

= Support =
For support or suggestions please email me at: developer@tuskcode.com


== Screenshots ==

1. settings-page.png
2. widget-page-location.png


== Changelog ==

= 2023-09-22 = 
* Added namespaces to classes.
* nonce for ajax calls
* Fixed multiple views on the same page
* Tested with wordpress 6.3
* Added multiple languages

= 02-12-2022 = 
* Tested with latest wp version 6.1

= 15-07-2022 = 
* Tested with lates version 6.0.1

= 24-02-2022 = 
* Fixed issue with Safari mobile and table ( map not showing )

= 16-02-2022 =
* Added filter by country for address suggestion on checkout 
* Fixed error on new shape created
* Compatible with 5.9

= 11-12-2021 =
* Fixed rich text editor - not showing 
* Added extra fix info for rich text editor 
* Style checkout suggestion - removed list style, added border bottom for list item
* Added filter to enable rich text editor for Plugin, and Shapes page


= 21-08-2021 = 
* Fixed shortcodes 
* Fixed loading the map only once for a single page 
* Fixed assign to new pins/shapes
* Changed shortcodes to [bing-map-pro]
* Fixed html editor for advanced infobox (not loading for some websites)
* Fixed bing map not loading completely 


= 15-06-2021 =
* Fixed Roles
* Fixed Assign to Map
* Fixed map full screen
* Fixed woocommerce autosuggest checkout fields


= 19-04-2021 = 
* Fixed clash with another plugin
* Removed pluggable file 

= 30-12-2020 =
* Added tooltip for icons
* Added Assign to map for shapes and pins
* Added no of active pins/shapes for map table
* Style buttons
* Added modal when new pin is created
* Added 'Save and New' option for new pins
* Drag and drop pin to new location functionality
* Mark pin input fields to required
* Added number or maps for shapes and pins in the table grid
* Add action icon 'Assign to map' for pins and shapes
* Drag and drop functionality for 'Assign to Map'
* Added advanced map option into a modal 


= 16-08-2020 =
* Fixed bug - disabling/enabling map 
* Display more fixes for Advanced editor 
* Fix bug duplicated columns for db tables 

= 04-08-2020 =
* Added Checkout Address Suggestion for Woocommerce using Bing Map Autosuggest module *
* Feedback form on plugin uninstall *

= 05-07-2020 =
* Added new map Navigation Bar - Square 
* Added feature to Restrict Map View
* Info Modal when Tiny MCE not loading 
* Moved Advanced map settings into Modal


= 30-05-2020 =
* Added new feature - Map Clustering
* Fixed translation issues,
* Added 'Infobox type' for pins grid
* Fixed Infobox position for pins (now the arrow it will be in the center of the pin )
* Fixed default pin for bing map
* Added new columns in pins, and maps table

= 21-05-2020 =
* Fixed typo error

= 02-06-2020 =
* Added fix to db schema *

= 17-05-2020 =
* Fixed - new first Polyline
* Added translation for Languages: French, Italian, Polish
* Updated missing translations
* Fixed - Loading map coordinates set to 'world view'

= 13-05-2020 =
* Added Radius to Circle *
* Added Save&New to Modal Shapes *
* Fix modal showing up *
* Improve Fast Loading Polygons, and Polylines *
* Translation to Chinese, Romanian, German, Spanish *


= 16-04-2020 =
* Added Map Views *

= 26-03-2020 =
* Fix advanced infobox for pin
* Added shape circle
* Added shape polyline
* Added shape polygon

= 29-12-2019 =
* Fixed pin custom url
* Added Menu on the left side
* Added Permission settings for User Roles ( Author, Contributor, Editor )
* Custom icons url can be added from the media file

= 08-12-2019 =
* HTML Infobox for pins *
* Options for Pin Infobox None/Simple/Advanced
* Added option to show infobox on map when hover over pin
* Added option to have fullscreen map 
* Show/Hide fullscreen icon for map
* Fixed map attribute class 
* Fixed Pins rows append
* Added custom icon library from https://mapicons.mapsmarker.com/
* Custom url icons
* Added custom sizes for advanced infobox for mobile/table/desktop
* Added TinyMCE Advanced Editor for Advanced Infobox 


= 24-July-2019 =
* Sync Bing Map request*

= 16-July-2019 =
* BEFORE UPDATING *
* MAJOR UPDATE - PLEASE SAVE MAP, AND PLUGIN DETAILS, AS ALL THE DATA FROM PREVIOUS MAP WILL BE LOST *
* Major release 2.0.1 *
* Multiple maps can be created *
* Multiple pins can be added to any map *
* Multiple maps per singles site or page *
* Easier to navigate over the plugin *
* Maps, Pins, and Settings reside on different pages *

= 20-January-2019 =
* Fix mixed content 

= 11-September-2018 1.2.0 =
* Fixed map center 
* Added Reset map Center
* Added address suggestion for new pin

= 29-August-2018 1.1.8 =
* Added Latitude, Longitude attributes to new pins
* Center the map for desired location

= 10-August-2018 1.1.6 = 
* Added 600+ custom icons
* Modified new icon layout

= 02-July-2018 1.1.1 =
* Fix uninstall

=  02-July-2018 1.1.0 =
* Added option disable/enable map scroll

=  02-July-2018 - 1.0.4 =
* Fix https request 

= 1.0.3 =
* Fix - Get address coordinates only if the map is present in the page
* Fix - Async javascript request

= 1.0.2 =
* Fix - Show display default.png

= 1.0.1 =
* No changes made yet

== Upgrade Notice ==

= 1.0.3 =
* Simple fixes, no features added 

= 1.0.4 =
* Fix https request 

= 1.1.0 =
* Added option disable/enable map scroll

= 1.1.1 =
* Fix uninstall


== A brief Markdown Example ==
1. Multiple maps pers site
2. Multiple maps with shortcode per page (can show one or more maps in a single page )
3. Multiple Pins
4. Custom width and height
5. HTML Class
6. Map Zoom
7. Map Type selection
8. Widget
9. Disable/Enable Scroll on map
10. Short Code
11. Latitude and Longitude fields for new pins
12. Center map on desired location
13. Address Suggestion
14. Title and description for new pins 
15. Multiple lines for pin description
16. User Roles Permissions (Author, Contributor, Editor)
17. Url pin address
18. Uploaded pins 
18. Full Screen Map capability
19. Advanced infobox for pins (html ready)
20. Shapes (polyline, circle, polygon )
21. Clustering
22. Map Views
23. Woocommerce Checkout Suggestion Address (Billing, Shipping forms)
