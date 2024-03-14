=== Google Maps CP ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/content-tools/codepeople-post-map
Tags:google maps,maps,marker,gmap,places,shortcode,map,categories,post map,point,location,address,images,geocoder,google,shape,list,grouping,cluster,infowindow,route,pin,streetview,post,posts,pages,widget,image,exif tag,plugin,sidebar,stylize,admin
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Google Maps CP allows to associate geolocation information to your posts and to integrate your blog with Google Maps in an easy and natural way.

== Description ==

Google Map features:

► Insert a Google map in the best position within your blog
► Deal with large volumes of dots or markers on the Google Maps
► Uses Google Maps to discover additional entries related to the post
► The location can be defined by physical address and point coordinates
► Map markers customization
► Allows to embed Google Maps in multiple languages
► Allows several Google Maps controls and configuration options

**Google Maps CP** allows to insert a Google Maps in a post or in any of the WordPress templates that display multiple posts.

The Google Maps inserted in a single post displays a marker at the position indicated by the geolocation information pertaining to the post, but also shows markers of the last posts published in related categories. The number of markers to display on the Google Maps can be set in the plugin's settings.

The Google Maps inserted into a template displaying multiple posts will contain as many markers as posts making up the page with the associated geolocation info. When the mouse is hovered over the marker, the post to which it belongs gets highlighted.

**Google Maps** has a wide range of settings to make your maps more versatile and adaptable.

**More about the Main Features of Google Maps CP:**

*   The plugin is capable of dealing with **large volumes of dots or markers**.
*   Another way for users to discover **additional entries related** to the post.
*   The **location information** can be defined by physical address and point coordinates.
*   Allows to **insert the Google Maps** in the best position within your blog or simply **associate the geolocation information** to the post but without displaying the Google maps.
*   Markers **customization**.
*   Display or hide the bubbles with markers information.
*   Allows to display a bubble opened by default.
*   Based on **Google Maps Javascript API Version 3**.
*   Allows to embed Google maps in **multiple languages**.
*   Displays **markers** belonging to posts of the same categories.
*   **Several customization options** are available: initial zoom, width, height, margins, alignment, map type, map language, the way the map is displayed in a single post (either fully deployed or icon to display the Google maps), enable or disable map controls, the number of points plotted on a Google map, as well as the class that will be assigned to the post when the mouse hovers over the marker associated with the post.

**Premium Features of Google Maps CP:**

*   Load points belonging to specific pages or posts.
*   Load in a same map all points that belong to a specific category.
*   Load in a same map the points associated to all posts.
*   Display the points that belong to the posts with a specific tag.
*   The location information and description may be used in posts search.
*   Allows to associate multiple Google maps points to each post/page.
*   Allows to draw routes through points in the same post.
*   Include a search box on the map for searching additional places.
*   Draw shapes on the Google Maps.
*   Allows to create a legend with categories, tags, or custom taxonomies, and display or hide the points, checking or unchecking legend items.
*   Include a link to get directions to the point from Google Maps.
*   Include a link to open the point directly on Google Maps.
*   Include a link to display directly the street view in the specific point.
*   Allows to display multiple Google Maps in the same post/page (but displays the same points in all maps on page).
*   Allows to insert the map as widget on sidebars.
*   Allows to styling the map.
*   Allows grouping multiple markers in a cluster.
*   Allows to display the user location on map.
*   Allows to refresh the user location on map in the time interval defined in the map's settings.
*	Generates dynamic points from the geolocation information, stored in the image's metadata when it is uploaded to WordPress, and includes a button for processing all previous images.
*	Generates dynamic points on map, relative to the geolocation information, assigned to the posts from WordPress App.
*   Allows to associate the Google maps with any public post_type in WordPress.
*   In non singular webpages, Google Maps display a map for each post.
*   Allows to export all points defined in the website to a CSV file.
*   Allows to import the points from a CSV or KML file.
*   Allows to load a KML layer on the map from a KML file.

The third, and most extended version of the plugin is the "Developer Version". The Developer version of the Google Maps CP plugin includes all features of the Premium version, and the features listed below:

**Developer Features of Google Maps CP**

*   Allows design a Contact Form, and associate it with the points in the map.
*   Send notification emails with the information collected by the form.
*   Associate an email address to the points, to contact a different person by each point, or a global email address to be notified from all points.
*   Supports Contact Form 7 plugin's forms in the points' descriptions by inserting their shortcodes ( [contact-form-7 id="1" title="Contact Form" label="Click Here"] )
*   Allows to use the image associated with the point as the point's icon on map.

**Demo of the Developer Version of Google Maps CP Plugin**

[https://demos.dwbooster.com/cp-google-maps/wp-login.php](https://demos.dwbooster.com/cp-google-maps/wp-login.php "Click to access the administration area demo")

[https://demos.dwbooster.com/cp-google-maps/](https://demos.dwbooster.com/cp-google-maps/ "Click to access the Public Page")


Note 1: To display all points that belong to a specific category in the same Google Map, it is required to insert the following shortcode `[codepeople-post-map cat="3"]`. The number 3 represent the category ID, replace this number by the corresponding category's ID. To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode('[codepeople-post-map cat="3"]'); ?>

Note 2:	To display all points that belong to more than one category in a same Google Map, separate the categories IDs with the comma symbol `[codepeople-post-map cat="3,5"]`. The numbers 3 y 5 are the categories IDs, replace these numbers by the corresponding categories IDs. To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode('[codepeople-post-map cat="3,5"]'); ?>

Note 3: To display all points defined in the website in a same Google Map, use -1 as the category's ID:

	[codepeople-post-map cat="-1"]

or

	<?php echo do_shortcode('[codepeople-post-map cat="-1"]'); ?> for template.

Note 4:	To display all points that belong to the posts with a specific tag assigned in a same Google Map, for example the tag name "mytag", use the shortcode's attribute "tag", as follows: `[codepeople-post-map tag="mytag"]`. To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode('[codepeople-post-map tag="mytag"]'); ?>

Note 5:	To display the points belonging to specific posts or pages in a same Google Map, enter their ids separated by comma through the "id" attribute in the shortcode as follows: `[codepeople-post-map id="123"]`. To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode('[codepeople-post-map id="123"]'); ?>

The "id" attribute has preference over the rest of the filtering attributes.

If you prefer configure your map directly from the shortcode, then you must enter an attribute for each map feature to specify. For example:

	[codepeople-post-map width="500" height="500"]

The complete list of allowed attributes are:

Very Important. Some of attributes are available only in the premium and developer versions of the plugin.

width:  Values allowed, number or percentage. Defines the map's width:

	[codepeople-post-map width="300"]

or

	[codepeople-post-map width="100%"]

height:  Values allowed, number or percentage (In the web's development, the height in percentage is effective only if the parent element has a height defined). Defines the map's height:

	[codepeople-post-map height="300"]

align:  Values allowed, left, right, center. Aligns the map's container to the left, right or center:

	[codepeople-post-map align="center"]

dynamic_zoom:  Values allowed, 1 or 0. Adjust the zoom of map dynamically to display all points on map at the same time:

	[codepeople-post-map dynamic_zoom="1"]

zoom:  Accepts a number to define the map's zoom. To apply a zoom to the map, the dynamic zoom should be 0:

	[codepeople-post-map dynamic_zoom="0" zoom="5"]

type:  Values allowed, SATELLITE, ROADMAP, TERRAIN and HYBRID. Select the type of map to display:

	[codepeople-post-map type="ROADMAP"]

language:  Values allowed, en for English, es for Spanish, pt for Portuguese, etc. (for the complete list, check the Google Maps documentation). Select a language to display on map:

	[codepeople-post-map language="en"]

route:  Values allowed, 0 or 1. Draw or not the route between points in a same post or page:

	[codepeople-post-map route="1"]

mode:  Values allowed, DRIVING, BICYCLING and WALKING. Define the type of route:

	[codepeople-post-map route="1" mode="DRIVING"]

show_window:  Values allowed, 0 or 1. To enable or disable the infowindows:

	[codepeople-post-map show_window="1" ]

show_default:  Values allowed, 0 or 1. Display or not an infowindow expanded by default:

	[codepeople-post-map show_window="1" show_default="1"]

markerclusterer:  Values allowed, 0 or 1. Displays a cluster with the number of points in an area:

	[codepeople-post-map markerclusterer="1"]

mousewheel:  Values allowed, 0 or 1. Enables the map's zoom with the mouse wheel:

	[codepeople-post-map mousewheel="1"]

zoompancontrol:  Values allowed, 0 or 1. Displays or hide the zoom controls in the Google Maps:

	[codepeople-post-map zoompancontrol="1"]

typecontrol:  Values allowed, 0 or 1. Displays or hide the type control in the Google Map:

	[codepeople-post-map typecontrol="1"]

streetviewcontrol:  Values allowed, 0 or 1. Displays or hide the street-view control in the Google Map:

	[codepeople-post-map streetviewcontrol="1"]

defaultpost: Defines the post ID, for centring the map, and display by default the infowindow corresponding to the first point associated to this post:

	[codepeople-post-map defaultpost="396"]

center: To define the center of the map, but if the infowindows are configured to be opened by default, the map will be centered in the point with the infowindow opened:

	[codepeople-post-map center="40.7127837,-74.00594130000002"]

legend:  Accepts a taxonomy name as value. Some common taxonomies names are:  category and post_tag, for the categories and tags, respectively. Displays the legend with the list of elements that belong to the taxonomy and are assigned to the posts associated with the points:

	[codepeople-post-map legend="category"]

legend_title:  Text to be used as legend title:

	[codepeople-post-map legend="category" legend_title="Select the categories to display on map"]

legend_class:  Class name to be assigned to the legend. The legend design may be modified through CSS styles. Creates a class name, with the styles definition, and associates the new class name to the legend through the legend_class attribute:

	[codepeople-post-map legend="category" legend_class="my-legend-class"]

tag:  Tags slugs separated by ",". Displays on map the points whose posts have assigned the tags:

	[codepeople-post-map tag="tag1,tag2,tag3"]

cat:  Categories IDs separated by "," or -1. Displays on map the points whose posts belong to the categories. The special value -1, allows display on map all points defined in the website:

	[codepeople-post-map cat="2,4,56"]

	[codepeople-post-map cat="-1"]

excludecat:  Categories IDs to exclude, separated by ",". From points to be displayed on map, the plugin excludes the points whose posts belong to the categories to exclude:

	[codepeople-post-map tag="tag1,tag2" excludecat="4"]

excludepost:  Posts IDs to exclude separated by ",":

	[codepeople-post-map cat="-1" excludepost="235,260"]

excludetag:  Tags IDs to exclude separated by ",":

	[codepeople-post-map excludetag="2,13"]

taxonomy:  The taxonomy is a special attribute that should be combined with other attributes, depending of taxonomies to use for points filtering. Suppose the website includes two new taxonomies:  taxonomyA and taxonomyB, and the map should display all points that belong to the posts with the value T1 for taxonomyA, and T3,T4 for taxonomyB, the shortcode would be:

	[codepeople-post-map taxonomy="taxonomyA,taxonomyB" taxonomyA="T1" taxonomyB="t2,t3"]

Note 5: The geolocation information is stored in image's metadata from mobiles or cameras with GPS devices.

Note 6: Some plugins interfere with the shortcodes replacements, and provokes that maps don't be loaded correctly, in this case should be passed a new parameter through the shortcode print=1

Passing the parameter print=1, displays the map at beginning of page/post content.

Note 7: To display  in the Google Map all points in posts with a specific taxonomy assigned, or multiple taxonomies, should be used the "taxonomy" attribute in the shortcode, with the list of all taxonomies separated by the comma symbol, for example: taxonomy="taxonomy1,taxonomy2", and a new attribute for each taxonomy with the values corresponding. For example if you want select the points that belong to the posts with the values: "value1" for "taxonomy1", the shortcode would be:

	[codepeople-post-map taxonomy="taxonomy1" taxonomy1="value1"]

for multiple taxonomies:

	[codepeople-post-map taxonomy="taxonomy1,taxonomy2" taxonomy1="value1" taxonomy2="value2,value3"].

post_type: In combination with other attributes like: cat, tag or taxonomy, it is possible to load on map only the points that belong to the post types in the list:

	[codepeople-post-map cat="-1" post_type="post,page"]

excludepost_type: In combination with other attributes like: cat, tag or taxonomy, it is possible to load on map only the points that do not belong to the post types in the list:

	[codepeople-post-map cat="-1" excludepost_type="post"]

kml: Absolute URL (http://...) to a KML file with the Keyhole Markup Language:

	[codepeople-post-map kml="http://www.yourwebsite.com/your-file.kml"]

If you want more information about this plugin or another one don't doubt to visit my website:

[http://wordpress.dwbooster.com/content-tools/codepeople-post-map](http://wordpress.dwbooster.com/content-tools/codepeople-post-map "Google Maps CP")

== Installation ==

**To install Google Maps CP, follow these steps:**

1.	Download and unzip the plugin
2.	Upload the entire codepeople-post-map/ directory to the /wp-content/plugins/ directory
3.	Activate the plugin through the Plugins menu in WordPress

== Interface ==

**Google Maps** offers several setting options and is highly flexible. Options can be set up in the Settings page (and will become the **default setup** for all maps added to posts in the future), or may be **specific to each post** to be associated with the Google maps (in this case the values are entered in the editing screen of the post in question.)

The settings are divided into two main groups, those belonging to the Google maps and those belonging to the geolocation point.

**Google Maps configuration options:**

*   Map zoom: Initial map zoom.
*   Dynamic zoom: Allows to adjust the map's zoom dynamically to display all points at the same time.
*   Map width: Width of the map.
*   Map height: Height of the map.
*   Map margin: Margin of the map.
*   Map align: Aligns the map at left, center or right of area.
*   Map type: Select one of the possible types of maps to load (roadmap, satellite, terrain, hybrid).
*   Map language: a large number of languages is available to be used on maps, select the one that matches your blog's language.
*   Allow drag the map: allows drag the map to see other places.
*   Map route: Draws the route through the points that belong to the same post (available only in the premium and developer versions of plugin)
*   Travel Mode: Travel mode used in route drawing (available only in the premium and developer versions of plugin)
*   Include Traffic Layer: Displays a traffic layer over the map.
*   Show info bubbles: display or hide the bubbles with the information associated to the points.
*   Display a bubble by default: display  a bubble opened by default.
*   Display map in post / page: When the Google maps are inserted in a post you can select whether to display the Google maps or display an icon, which displays the map, when pressed (if the Google maps are inserted into a template that allows multiple posts, this option does not apply)
*   Options: This setting allows you to select which map controls should be available.
*	Display a bundle of points in the same area, like a cluster: Allows grouping multiple points in a cluster (available only in the premium and developer versions of plugin)
*	Display Featured Image by default: Displays the Featured Image in posts and pages in the infowindows, if the points don't have associated an image.
*	Display the user's location: Displays a marker with the location of user that is visiting the webpage (available only in the premium and developer versions of plugin)
*	Refresh the user's location every: Integer number that represent the interval in milliseconds to refresh the user's location (available only in the premium and developer versions of plugin)
*	Title of user's location: Enter the title of infowindow belonging to the user's marker (available only in the premium and developer versions of plugin)
*	Display the get directions link: Displays a link in the infowindow to get the directions to the point (available only in the premium and developer versions of plugin)
*	Display a link to Google Maps: Displays a link in the infowindow to load the point directly on Google Maps.
*	Display a link to Street View: Displays a link in the infowindow to display the street view in the specific point.
*   Enter the number of points on the post / page map: When the Google maps are inserted into a post, points that belong to the same categories will be shown on the same Google map. This option allows you to set the number of points to be shown. When the Google maps are inserted into a template that allows multiple posts this option does not apply.
*   Generate points dynamically from geolocation information included on images, when images are uploaded to WordPress: If the image uploaded to WordPress includes geolocation information is generated a point with related to the geolocation information.
*   Generate points dynamically from geolocation information included on posts: Displays new points on maps, if the post includes geolocation information, generated by WordPress App.
*   Allow stylize the maps: Allows to define a JSON object to stylize the maps.
*   Display maps legends: Check the option to display a legend with categories, tags, or custom taxonomies, to display or hide the points on map dynamically.
*   Select the taxonomy to display on legend: Select the taxonomies to display on legend.
*   Enter a title for legend: Enter the title to display in the legend.
*   Enter a classname to be applied to the legend: To customize the legend appearance, associate to it a classname, and set the class definition in any of style files in your website.
*   Highlight post when mouse hovers over related point on map:  When the Google maps are inserted into a template that allows multiple posts,  hovering the mouse over one of the points will highlight the associated post through assignment of a class in the next setup option.
*   Highlight class: Name of the class to be assigned to a post to highlight when the mouse is hovered over the point associated with that post on the Google map.
*   Use points information in search results: Allows search in the points information ( available only in the premium and developer versions of plugin )
*	Allow to associate a map to the post types: Allows to associate points to custom post types in website ( available only in the premium and developer versions of plugin )

**Configuration options related to the points location**

*   Location name: Name of the place you are indicating on the Google maps, alternatively, the name of the post can be used.
*   Location description: Description of the place you are showing on the Google maps. If left blank, the post summary will be used.
*   Select an image from media library: Select an image to associate with the localization point.
*   Address: Physical address of the geolocation point.
*   Latitude: Latitude of the geolocation point (gotten from Google Maps).
*   Longitude: Longitude of the geolocation point (gotten from Google Maps).
*   Verify: This button allows you to check the accuracy of the geolocation point address by updating the latitude and longitude where necessary.
*   Select the marker by clicking on the images: Select the bookmark icon to show on the Google Maps.
*   Insert the map tag: Inserts a shortcode in the content of the post where the Google Map is displayed with the features selected in the setup. You can attach geolocation information to a post but choose not to show the Google maps in the content of the post. In case you do want to display a map in the post content, use this button.

**Configure Shapes**

*   Check the box over the "Insert the map tag" button.
*   Enter the stroke weight of shape.
*   Enter the color of shape.
*   Enter the opacity of shape.
*   Press with the mouse on map at right to draw the shape.

**Inserting maps as widgets on sidebars** (available only in the premium and developer versions of plugin)

To insert the maps as widget on sidebars, go to the menu option "Appearance / Widgets", and drag the "CP Google Maps" widget to the sidebar.

It is possible define, for each map on sidebar, all attributes available with the format attr="value". The map's width is set to the 100% of sidebar by default.

**Translations**

The Google Maps CP uses the English language by default, but includes the following language packages:

* Spanish
* French

Note: The languages packages are generated dynamically. If detects any errors in the translation, please, contact us to correct it.

== Frequently Asked Questions ==

= Q: Why the Google Map shortcode is not inserted on page content? =

A: There are some content editors, available as WordPress plugins, that provoke some compatibility issues with WordPress, in this case you should type the shortcode manually:

	[codepeople-post-map]

= Q: How many Google Maps I can insert into a post? =

A: In the free version of plugin only one map with only one point associated in each post/page. In the premium version of plugin it is possible associate multiple points to the post and insert multiple shortcodes ( if there are multiple maps included in the same post/page, all of them will display the same points)

= Q: How to insert Google Maps into a template? =

A: Load the template in which you want to place the map in the text editor of your choice and place the following code in the position where you want to display the Google maps:

	<?php echo do_shortcode ('[codepeople-post-map]'); ?>

= Q: Is possible to load all points that belong to the posts with a tag assigned in a same Google Map? =

A:	To display all points that belong to the posts with a specific tag assigned, for example the tag name "mytag", use the shortcode's attribute "tag", as follows:

	[codepeople-post-map tag="mytag"]

To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode('[codepeople-post-map tag="mytag"]'); ?>

= Q: How to know the ID of a category? =

A: The explanation to determine the ID of a category is applied to other taxonomies.

Access to the categories through the menu option: "Posts/Categories". After accessing to the categories page, click the "Edit" link corresponding to the category, and pays special attention to the parameter "tag_ID", in the URL on browser. The value in this parameter correspond to the ID of the category.

= Q: Is possible to load all points in a category in a same Google Map? =

A: To display all points that belong to a specific category, it is required to insert the following shortcode

	[codepeople-post-map cat="3"]

The number 3 represent the category ID, replace this number by the corresponding category's ID. To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode ('[codepeople-post-map cat="3"]'); ?>

= Q: How to exclude the points in a category? =

A: To exclude the points that belong to a specific category, or various categories, inserts the attribute excludecat in the shortcode:

	[codepeople-post-map excludecat="3,4"]

The number 3 and 4 represent the categories IDs.

= Q: How to exclude the points in a post? =

A: To exclude the points that belong to a post, or various posts, inserts the attribute excludepos in the shortcode:

	[codepeople-post-map excludepost="3,4"]

The number 3 and 4 represent the posts IDs.

= Q: How to exclude the points in posts with tag? =

A: To exclude the points that belong to the post with a specific tag, or various tags, inserts the attribute excludetag in the shortcode:

	[codepeople-post-map excludetag="3,4"]

The number 3 and 4 represent the tags IDs.

= Q: Is possible to load all points in more than one category in a same Google Map? =

A: To display all points that belong to multiple categories, it is required separate the categories IDs with comma ","

	[codepeople-post-map cat="3,5"]

The numbers 3 and 5 are the categories IDs, replace these numbers with the corresponding categories IDs. To insert the code directly in a template, the snippet of code would be:

	<?php echo do_shortcode ('[codepeople-post-map cat="3,5"]'); ?>

= Q: Is possible to load all points in the website in a same Google Map? =

A: To display all points in the website use -1 as the category ID:

	[codepeople-post-map cat="-1"]

or

	<?php echo do_shortcode ('[codepeople-post-map cat="-1"]'); ?>

for template.

= Q: If I link geolocation information to a post but do not insert a Google map in it, will the geolocation information be available? =

A: If you have inserted a Google map into a template where multiple posts are displayed, then the geolocation information associated with posts is displayed on the map.

= Q: How can I disable the information window of point opened by default? =

A: Go to the settings of map (the settings page of plugin for settings of all maps, or the settings of a particular map), and uncheck the option "Display a bubble by default"

= Q: How can I disable all information windows of points? =

A: Go to the settings of map (the settings page of plugin for settings of all maps, or the settings of a particular map), and uncheck the option "Show info bubbles"

= Q: How can I stylize the Google Maps? =

A: In the premium version of plugin is possible define a JSON object to stylize the maps: the maps' colors, labels, etc.

To generate the styles used on maps, I personally recommend to visit the following link that publishes a visual generator of styles, and get the JSON object, to be use in our plugin:

[http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html](http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html "How to use the Styled Maps Wizard")


= Q: How can I use different icons, in the points markers?  =

A: To use your own icons, you only should to upload the icons images to the following location: "/wp-content/plugins/codepeople-post-map/images/icons/", and then select the image from the list in the point's definition.

= Q: How can I use particular settings in a map? =

A: You may use a particular settings in a map, defining the options directly as attributes of shortcode:

	[codepeople-post-map width="100%"]

If you are inserting the map in a particular page/post, you may check the field named "Use particular settings for this map", and then entering the particular values in the settings options.

= Q: Is possible to display the map as responsive design? =

A: Yes, that is possible, you only should to define the width of map with values in percent. For example: 100%
Pay attention the height definition with percent is not recommended, because it is only possible if the map's container has a fixed height.

= Q: How to get the directions to the point? =

A: Go to the settings page of plugin and check the box to display the "Get directions" link in the infowindow. The "Get directions" link will be displayed in the infowindow.

= Q: Is possible to include multiple taxonomies in the maps' legends? =

A: In the shortcode can be defined the attribute "legend" for selecting the taxonomy to  use in the legend of map:

	[codepeople-post-map legend="category"]

but from the version 5.0.6 of the plugin it is possible to define in the legend multiple taxonomies separated by comma, to include all of them in the map's legend:

	[codepeople-post-map legend="category,post_tag"]

= Q: How to open the point on Google Maps? =

A: If you want to display a link to open the point directly on Google Maps, go to the settings page of plugin, and checks the box to display the link in the infowindow.

= Q: Could I insert the map as widget? =

A: Yes, you can. Go to the menu option: "Appearance / Widgets" and insert the "CP Google Maps" widget on side bar.

= Q: My images include geolocation information. Is possible use the geolocaion information stored in the image to generate points on map? =

A: Go to the settings page of plugin and select the corresponding option to allow processing the information stored on image's metadata, and then if an image uploaded to WordPress, includes geolocation information, will be generated a point with this information dynamically, that will be displayed on map.

= Q: Why the maps are not showing on website? =

A: Some plugins interfere with the shortcodes replacements, and provokes that maps don't be loaded correctly, in this case should be passed a new parameter through the shortcode print=1
Passing the parameter print=1, displays the map at beginning of page/post content.

= Q: It is possible to draw routes for flights between countries? =

A: By default Google draws routes between points connected by cars, bus, trains, etc, but not for flights. So, in this case should be used polylines. To connect points with polylines, tick the attribute: "Connect the points with polylines, even if there is not a route between points", in the settings page of maps.

= Q: How can I change the route between address? =

A: The route depends on the order of the points. If you want change the route, you should change the order of point. Takes the point by the handle's icon, and drag it to its correct position in the points list.

= Q: What are the differences between routes and polylines? =

A: The routes are drawn on maps, only if there are known routes between the locations, based in the travelling mode (driving, walking), but if Google unknown a way between both address, is not able to draw the route. The polylines are straight lines connecting two addresses, even if there is not  a route. It is main difference between both concepts, and  why the polylines are preferred for representing flights.

= Q: Is possible create routes between points in different posts? =

A: Yes, it's possible to draw a route including points belonging to different posts/pages, but in the current version of the plugin would be needed duplicate some points. Visit the following link to our technical blog with detailed instructions:

[http://blog.net-factor.com/how-connecting-multiple-posts-on-a-route/](http://blog.net-factor.com/how-connecting-multiple-posts-on-a-route/ "How connecting multiple posts on a route?")

= Q: How can I centring the map in a point defined a specific post, and display its infowindow? =

A: Use the "defaultpost" attribute, in the map's shortcode, as follows:

	[codepeople-post-map defaultpost="231"]

The number is the post's ID

= Q: I've configured the sizes of the map to be displayed with a responsive design, but the map is not showing =

A: To display the maps with a responsive design, you should define the map's width with percentages (for example 100% if you want that the map width be the same that its container), but PAY ATTENTION, in web development the treatment of the width and height is different. The page width is limited by the browser's width, but with the height it is not apply. So, you should enter a fixed height(for example 320px).

The only way to define the map's height in percentages, is if the element that contain the map has defined a fixed height.

= Q: Can be inserted a link in the infowindow? =

A: It is possible insert links, and any other HTML element in the infowindow. You only should insert HTML tags directly in the point description. For example, to insert a link to our web page: &lt;a href=&quot;http://wordpress.dwbooster.com&quot;&gt;Click Here&lt;/a&gt;

= Q: After entering an address, and to press the verify button, the address is modified, and the pin is displayed in a different location of the map =

A: If after pressing the "verify" button, the address is modified dynamically, and the pin is displayed in another location, the cause is simple. If Google Maps does not recognize an address, it uses the nearest known address, and displays the pin on this location.

To solve the issue, you simply should drag and drop the pin in the correct location, and type the address again, but this time "DON'T PRESS AGAIN THE VERIFY BUTTON".

= Q: I've inserted an image in the page, but have not been generated a new point in the map =

A: First, be sure you have checked the option "Generate points dynamically from geolocation information included on images, when images are uploaded to WordPress", from the settings page of the plugin.

Second, be sure the image includes the Exif tags with the geolocation information (latitude and longitude)

Finally, you should upload the image from the "Add Media" button of the page or post, and not from the media library.

= Q: Can be generated a point from the page publication, with the location of the author? =

A: Yes, that is possible but only from the WordPress App, available for iPhone, iPad and Android, with the option for sharing the location enabled in the application. Furthermore, from the settings page of the plugin, should be checked the option: "Generate points dynamically from geolocation information included on posts"

= Q: How to display a map in the search results page, with the points defined in the resulting posts and pages? =

A: To insert a map in the results page with the points defined in the resulting posts and pages, you simply should identify the template file, in the active theme on your WordPress, that is used in the search page (usually it is called search.php), and inserts the map's shortcode directly in the file's content:

	<?php echo do_shortcode( '[codepeople-post-map]' ); ?>

= Q: Can be searched in the website by the points information? =

A: If was checked the option "Use points information in search results", from the settings page of the plugin. The searching process will consider the points information too, and the posts and pages resulting could be selected by its points.

Pay attention, the results of search will be the posts and pages that include the points, not the point directly.

= Q: What styles are used in the infowindows? =

A: The design of infowindows is defined through styles in the cpm-styles.css file, located in "/wp-content/plugins/codepeople-post-map/styles/cpm-styles.css", specifically with the styles:

        .cpm-infowindow {margin:0; padding:0px; min-height:80px; font-size:11px; clear:both;}
        .cpm-infowindow .cpm-content {float:left;width:100%; color:black;}
        .cpm-infowindow .cpm-content .title {font-size:12px; line-height: 18px; font-weight:bold; color:black;}
        .cpm-infowindow .cpm-content .address {font-weight:bold; font-size:9px;}
        .cpm-infowindow .cpm-content .description {font-size:10px;}

= Q: Can be hidden the local listings from Google Maps API? =

A: Yes of course, if you want hide the local listings from Google Maps API, open the settings page of plugin, select the "Allow stylize the maps" attribute, and finally, paste the following code in the textarea:

	[
		{
			featureType: "poi",
			stylers: [
			  { visibility: "off" }
			]
		}
	]

= Q: Can be highlighted the post or page related with the point on map? =

A: From the settings page of the plugin, there are two options:

* Highlight post when mouse move over related point on map
* Highlight class

If you check the option: "Highlight post when mouse move over related point on map", and enter a class name in the "Highlight class" attribute, in the maps inserted on pages with multiple entries, the class name will be applied to the post, or page, when the mouse is moved over a point associated in the corresponding page or post.

= Q: How can be created shapes on map? =

A: In the map's definition, directly in the page or post where the map is being inserted, there is the checkbox: "Do you want display a shape on map?", please, tick the checkbox to expand the shape's section.

The shape's section includes some options like: the stroke weight (if you don't want to display an stroke around the shape, set its value to zero), the fill colour (the colour code with the format: #FFFFFF, to select the shape colour), and the opacity to be applied to the shape, take into consideration than zero is transparent and 1 is opaque. Remember, the shape is displayed over the map, if you set the opacity to 1, won't be possible to see the map's area.

Now the most important part create the shape's area. To create the shape's area is sufficient with click in the map at right (Don't worry, the pins are not included in the final map, are inserted in the preview map to allow modify the shape's area). Through the pins at right map it is possible modify the shape's area, click on the pin to delete it, or drag the pin to another location to vary the shape.

== Screenshots ==

01. Maps in action
02. Styling the maps
03. Map with user's location
04. Map with shape
05. Global maps settings
06. Point insertion's form
07. Inserting map on sidebars
08. Generates points, from the geolocation information stored on image's metadata
09. Contact Form Builder (only available in the Developer version of the plugin)
10. Associate the contact form with the point, and define an email address between the point's data (only available in the Developer version of the plugin)
11. Export/Import section (only available in the Developer version of the plugin)

== Changelog ==

= 1.1.5 =

* Implements the integration with new Borlabs Cookie version.

= 1.1.4 =

* Improves the integration with third-party plugins.

= 1.1.3 =

* Removes deprecated JS code.

= 1.1.2 =

* Hides the map gray background color on zoom out.

= 1.1.1 =

* Fixes an issue in default variables.

= 1.1.0 =

* Improves the plugin interface.
* Implements the WCML integration to display points from active language.

= 1.0.44 =

* Improves the plugin security.

= 1.0.43 =

* Modifies the Google Maps loading process to avoid conflicts with third-party themes.

= 1.0.42 =

* Modifies the plugin configuration making it easier to use.

= 1.0.41 =

* Fixes a conflict with third-party plugins.

= 1.0.40 =

* Implements the integration with Borlabs Cookie to block Google Maps until the cookies are accepted.

= 1.0.39 =

* Includes three new filters, cmp-point, cpm-point-infowindow-template, and cpm-point-infowindow, to edit the attributes of the point, modify the infowindows template, and edit the generated infowindows, respectively.

= 1.0.38 =

* Allows the use of shortcodes into the points' descriptions.

= 1.0.37 =

* Modifies the interaction with the Google APIs to prevent the limits on Google requests, affect our plugin.

= 1.0.36 =

* Modifies the plugin's settings.

= 1.0.35 =

* Includes a video tutorial in the plugin's interface to improve the users' experience.

= 1.0.34 =

* Includes new validation rules to prevent possible errors and warnings' messages, and removes unnecessary blocks of code.

= 1.0.33 =

* Fixes some notice messages.

= 1.0.32 =

* Modifies the Mouse Wheel behavior, to make it less intrusive.

= 1.0.31 =

* Fixes an encoding issue in some ampersand symbols on generated URLs.

= 1.0.30 =

* Assigns a class name to the points' thumbnails, to allow exclude them from the lazy loading of third party plugin that break the javascript blocks of code.

= 1.0.29 =

* Improves the detection of misconfigured Google Maps Projects.

= 1.0.28 =

* Includes some modifications in the infowindows styles.
* Fixes an issue to determine the featured images associated to the posts.
* Allows generating points from images when teh posts are published by email (Advanced and Developer versions of the plugin)

= 1.0.27 =

* Fixes an issue with the "cpm-complete-structured-query" filter.

= 1.0.26 =

* Includes new filters to allow the integration with third party plugins, or information stored in custom fields:

cpm-complete-structured-query
cpm-post-latitude
cpm-post-longitude
cpm-post-address
cpm-point-image
cpm-point-description
cpm-point-address

= 1.0.25 =

* Modifies the access to the demos.

= 1.0.24 =

* Fixes an issue processing the maps settings.

= 1.0.23 =

* Improves the plugin performance and the maps generation.

= 1.0.22 =

* Modifies the blocks for the Gutenberg editor,  preparing the plugin for WordPress 5.1

= 1.0.21 =

* Modifies the integration with the Gutenberg editor, loading the maps directly in the editor.

= 1.0.20 =

* Implements the integration with the latest version of the Gutenberg editor.

= 1.0.19 =

* Solves a conflict with the "Speed Booster Pack" plugin.

= 1.0.18 =

* Modifies the integration with the Gutenberg editor to includes the new features of Gutenberg.

= 1.0.17 =

* Modifies the activation/deactivation process.
* Solves an issue with the maps' sizes (width and height)

= 1.0.16 =

* Solves a conflict with the new styles of Google Maps.

= 1.0.15 =

* Modifies the instructions in the interface of the plugin.

= 1.0.14 =

* Fixes a javascript parser error when the user leave in blank the zoom attribute in the map's  settings.

= 1.0.13 =

* Fixes the issue in the plugin that clears the general settings when the updates are installed.

= 1.0.12 =

* Allows to select the information to display in the tooltips associated to the pins (the point location name, the address, or none).

= 1.0.11 =

* Fixes an issue processing the latitudes and longitudes.

= 1.0.10 =

* Removes the invalid characters from the points' latitudes and longitudes.

= 1.0.9 =

* Uses correct protocol in the markers' urls.

= 1.0.8 =

* Allows the integration with the Gutenberg Editor, the text editor that will be distributed with the next versions of WordPress.

= 1.0.7 =

* Replaces the maps' shortcodes into the WordPress "Text" widget.
* Fixes an issue in the javascript code.

= 1.0.6 =

* Modifies the plugin to use the new controls of Google Maps like the fullscreen control.

= 1.0.5 =

* Checks if the get_post_meta does not unserialize the result, and applies the unserialize it if corresponds.

= 1.0.4 =

* Fixes a Cross Site Scripting vulnerability modifying the form's action, in the settings page of the plugin. Fortunately, this vulnerability can be exploded only by users with access to the settings page of the plugin that is restricted only to the website's administrators. The vulnerability was Discovered with DefenseCode WebScanner Security Analyzer by Neven Biruski.

= 1.0.3 =

* Includes a "Troubleshoot Section" in the settings page of the plugin to load the resources used by the plugin in the body or footer of webpage to prevent conflicts with other plugins.

= 1.0.2 =

* Allows to display a traffic layer over the map.
* Fixes some errors.
* Modifies the menu options to improve the access to the plugin documentation.

= 1.0.1 =

* Allows to get the latitude and longitude of points from the point definition
* Corrects some conflicts with styles defined in some themes that interfere  with the Google Maps styles.
* Allows display the maps, in themes that use AJAX to load the posts and pages.
* Corrects some issues related with the update in the version of jQuery Framework, and force the inclusion of jQuery if it is not loaded by the website.
* Improves the plugin interface to allow modify the maps settings easily.
* Allows controlling the maps settings directly through attributes in the shortcode.
* Corrects an issue with the insertion of maps with a responsive design.
* Allows insert links in the points description.
* Include online demos.
* Change the icons URL to preserve the references with domain changes.
* Allows display and hide map when is inserted like an icon in page.
* Allows the selection of point images as thumbnail to optimize the page load.
* Changes the HTML generated to meet the W3C validation
* Include the use of nonce fields to protect the maps settings form.
* The Google Api is loaded with the same schema of webpage, and use https if it is required.

= 1.0 =

* Insert a Google Map in template files page with multiple entries.
* Improves of meta-boxes calling.
* Correct some bugs in Internet Explorer 8.
* Integrate the Google Map with non-standard WordPress themes.
* Allows the use of language files in the plugin
* Allows set an icon by default in the points of map.
* Uses Google Maps to discover additional entries related to the post
* The location can be defined by physical address and point coordinates
* Allows the map markers customization
* Allows to embed Google Maps in multiple languages
* Allows several Google Maps controls and configuration options

= 0.9b =

* First stable version released